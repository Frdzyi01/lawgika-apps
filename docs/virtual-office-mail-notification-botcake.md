# Dokumentasi Fitur Notifikasi Surat Masuk Virtual Office via Botcake Official WABA API

Dokumen ini menjelaskan arsitektur, konfigurasi, dan alur kerja fitur **Notifikasi Surat Masuk Virtual Office** pada aplikasi Lawgika.

---

## 1. File yang Diubah & Dibuat

| No | File | Status | Keterangan |
|:---:|:---|:---:|:---|
| 1 | `database/migrations/2026_08_01_150000_create_virtual_office_mail_notifications_table.php` | **[NEW]** | Migration tabel `virtual_office_mail_notifications` |
| 2 | `app/Models/VirtualOfficeMailNotification.php` | **[NEW]** | Model Eloquent `VirtualOfficeMailNotification` |
| 3 | `app/Models/Order.php` | **[MODIFY]** | Penambahan relasi `mailNotifications()` |
| 4 | `.env` | **[MODIFY]** | Penambahan `BOTCAKE_TEMPLATE_VIRTUAL_OFFICE_MAIL_NOTIFICATION=2856503864713589` |
| 5 | `config/services.php` | **[MODIFY]** | Penambahan mapping template `virtual_office_mail_notification` |
| 6 | `routes/web.php` | **[MODIFY]** | Penambahan route POST `admin/virtual-office/{id}/mail-notification` |
| 7 | `app/Http/Controllers/Admin/VirtualOfficeController.php` | **[MODIFY]** | Penambahan method `sendMailNotification()` |
| 8 | `app/Services/WhatsAppService.php` | **[MODIFY]** | Penambahan method `notifyVirtualOfficeMailNotification()` |
| 9 | `resources/views/admin/virtual-office/index.blade.php` | **[MODIFY]** | Penambahan tombol Action `📨 Surat Masuk`, Modal Popup, Live Preview, & AJAX SweetAlert2 |

---

## 2. Migration yang Dibuat

File: `database/migrations/2026_08_01_150000_create_virtual_office_mail_notifications_table.php`

```php
Schema::create('virtual_office_mail_notifications', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('virtual_office_id');
    $table->unsignedBigInteger('client_id');
    $table->date('received_date');
    $table->string('received_time', 20);
    $table->string('sender_name');
    $table->string('document_type');
    $table->string('tracking_number')->nullable();
    $table->text('internal_note')->nullable();
    $table->string('whatsapp_status', 50)->default('PENDING');
    $table->string('botcake_message_id')->nullable();
    $table->unsignedBigInteger('created_by')->nullable();
    $table->timestamps();

    $table->foreign('virtual_office_id')->references('id')->on('orders')->onDelete('cascade');
    $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
});
```

---

## 3. Model Baru

File: `app/Models/VirtualOfficeMailNotification.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualOfficeMailNotification extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'received_date' => 'date',
    ];

    public function virtualOffice()
    {
        return $this->belongsTo(Order::class, 'virtual_office_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

---

## 4. Route Baru

File: `routes/web.php` (di dalam middleware `auth`, `role:admin,admin1,admin2`)

```php
Route::post('virtual-office/{id}/mail-notification', [\App\Http\Controllers\Admin\VirtualOfficeController::class, 'sendMailNotification'])
    ->name('admin.virtual-office.mail-notification.store');
```

---

## 5. Controller yang Diubah

File: `app/Http/Controllers/Admin/VirtualOfficeController.php`

```php
public function sendMailNotification(Request $request, $id, WhatsAppService $whatsAppService)
{
    $request->validate([
        'received_date'   => 'required|date',
        'received_time'   => 'required',
        'sender_name'     => 'required|string|max:255',
        'document_type'   => 'required|string|in:Surat,Paket,Kartu Kredit,Dokumen Legal,Sertifikat,Invoice,Lainnya',
        'tracking_number' => 'nullable|string|max:255',
        'internal_note'    => 'nullable|string',
    ]);

    $vo = Order::with('user')->findOrFail($id);

    $clientId = $vo->user_id ?? auth()->id();

    $notification = VirtualOfficeMailNotification::create([
        'virtual_office_id' => $vo->id,
        'client_id'         => $clientId,
        'received_date'     => $request->received_date,
        'received_time'     => $request->received_time,
        'sender_name'       => $request->sender_name,
        'document_type'     => $request->document_type,
        'tracking_number'   => $request->tracking_number,
        'internal_note'    => $request->internal_note,
        'whatsapp_status'   => 'PENDING',
        'created_by'        => auth()->id(),
    ]);

    // Panggil Service WhatsApp
    $whatsAppService->notifyVirtualOfficeMailNotification($notification);

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi surat berhasil dikirim ke WhatsApp Client.',
            'data'    => $notification->fresh(),
        ]);
    }

    return redirect()->back()->with('success', 'Notifikasi surat berhasil dikirim ke WhatsApp Client.');
}
```

---

## 6. Service yang Diubah

File: `app/Services/WhatsAppService.php`

```php
public function notifyVirtualOfficeMailNotification(VirtualOfficeMailNotification $notification): ?WhatsappLog
{
    $notification->loadMissing(['virtualOffice.user', 'client']);

    $phone = $notification->client->phone
        ?? $notification->virtualOffice->user->phone
        ?? ($notification->virtualOffice->form_data['pic_phone'] ?? null);

    if (empty($phone)) {
        Log::warning('WhatsAppService::notifyVirtualOfficeMailNotification - Nomor telepon kosong.');
        $notification->update(['whatsapp_status' => 'FAILED']);
        return null;
    }

    $companyName = $notification->virtualOffice->user->company_name
        ?? $notification->client->company_name
        ?? ($notification->virtualOffice->form_data['company_name'] ?? null)
        ?? $notification->client->name
        ?? 'Client';

    $tanggal = $notification->received_date
        ? \Carbon\Carbon::parse($notification->received_date)->format('d M Y')
        : '-';

    $jam = $notification->received_time
        ? \Carbon\Carbon::parse($notification->received_time)->format('H:i')
        : '-';

    $pengirim = $notification->sender_name ?? '-';

    $templateId = config('services.botcake.templates.virtual_office_mail_notification', '2856503864713589');

    $log = $this->sendTemplateById(
        $phone,
        $templateId,
        'UTILITY',
        [
            $companyName, // {{1}} Nama PT
            $tanggal,     // {{2}} Tanggal Terima
            $jam,         // {{3}} Jam Terima
            $pengirim,    // {{4}} Pengirim
        ],
        $notification->client_id,
        $notification->virtual_office_id
    );

    $status = ($log && $log->status === WhatsappLog::STATUS_SUCCESS) ? 'SUCCESS' : 'FAILED';
    $notification->update(['whatsapp_status' => $status]);

    return $log;
}
```

---

## 7. Mapping Placeholder

Template Name: `virtual_office_mail_notification`  
Template ID: `2856503864713589`  
Category: `UTILITY`  
Language: `id`

| Placeholder | Parameter Name | Field Data | Contoh Value |
|:---:|:---:|:---|:---|
| `{{1}}` | 1 | Nama Perusahaan / PT | `PT Sumber Makmur Jaya` |
| `{{2}}` | 2 | Tanggal Terima (Formatted) | `01 Aug 2026` |
| `{{3}}` | 3 | Jam Terima (Formatted) | `14:30` |
| `{{4}}` | 4 | Pengirim | `PT Bank Central Asia` |

---

## 8. Payload JSON yang Dikirim ke Botcake

```http
POST https://botcake.io/api/public_api/v1/pages/waba_1053844194483527/flows/send_content
Accept: application/json
Content-Type: application/json
access-token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

```json
{
    "psid": "wa_6281219110199",
    "data": {
        "version": "v2",
        "content": {
            "messages": [
                {
                    "type": "whatsapp_message_template",
                    "template_id": "2856503864713589",
                    "language": "id",
                    "category": "UTILITY",
                    "components": [
                        {
                            "type": "BODY",
                            "params": [
                                {
                                    "key": "{{1}}",
                                    "parameter_name": "1",
                                    "value": "PT Sumber Makmur Jaya"
                                },
                                {
                                    "key": "{{2}}",
                                    "parameter_name": "2",
                                    "value": "01 Aug 2026"
                                },
                                {
                                    "key": "{{3}}",
                                    "parameter_name": "3",
                                    "value": "14:30"
                                },
                                {
                                    "key": "{{4}}",
                                    "parameter_name": "4",
                                    "value": "PT Bank Central Asia"
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    }
}
```

---

## 9. Contoh Data yang Dikirim

```json
{
    "virtual_office_id": 12,
    "client_id": 5,
    "received_date": "2026-08-01",
    "received_time": "14:30",
    "sender_name": "PT Bank Central Asia",
    "document_type": "Surat",
    "tracking_number": "JNE987654321",
    "internal_note": "Surat resmi disimpan di Laci Resepsionis Unit 6",
    "created_by": 1
}
```

---

## 10. Flow Sistem Lengkap

```
Admin Membuka Halaman Virtual Office (/admin/virtual-office)
                          ↓
Admin Klik Tombol "📨 Surat Masuk" Pada Salah Satu Client
                          ↓
Modal Popup Muncul (Data Client terisi otomatis sebagai Read-only)
                          ↓
Admin Mengisi Form: Tanggal, Jam, Pengirim, Jenis Dokumen
                          ↓
Live Preview WhatsApp Berubah Secara Realtime Mengikuti Input Admin
                          ↓
Admin Klik "Kirim Notifikasi"
                          ↓
Submit Form via AJAX ke POST /admin/virtual-office/{id}/mail-notification
                          ↓
VirtualOfficeController::sendMailNotification() Memvalidasi Input
                          ↓
Simpan Data ke Tabel virtual_office_mail_notifications (status: PENDING)
                          ↓
Controller Memanggil WhatsAppService::notifyVirtualOfficeMailNotification()
                          ↓
Service Menyusun 4 Parameter & Memanggil sendTemplateById()
                          ↓
sendTemplateById() Memformat Nomor HP → PSID (wa_628xxx) & Kirim HTTP POST ke Botcake API
                          ↓
Log Lengkap (Request/Response) Dicatat ke whatsapp_logs & storage/logs/laravel.log
                          ↓
Status di virtual_office_mail_notifications Diperbarui Menjadi SUCCESS / FAILED
                          ↓
SweetAlert2 Muncul: "Notifikasi surat berhasil dikirim ke WhatsApp Client."
```

---

## 11. Panduan Developer: Menambahkan Template Virtual Office Lainnya di Masa Depan

Arsitektur dikembangkan menggunakan prinsip **Open-Closed Principle** (bebas dikembangkan tanpa mengubah core engine `sendTemplateById()`).

Jika di masa depan ada template Virtual Office baru (misalnya: Notifikasi Paket Datang, Pengingat Perpanjangan VO, Notifikasi Tagihan):

1. **Daftarkan Template ID di `.env`**:
   ```env
   BOTCAKE_TEMPLATE_VIRTUAL_OFFICE_EXPIRE_WARNING=ID_TEMPLATE_NUMERIK
   ```

2. **Daftarkan di `config/services.php`**:
   ```php
   'botcake' => [
       'templates' => [
           'virtual_office_expire_warning' => env('BOTCAKE_TEMPLATE_VIRTUAL_OFFICE_EXPIRE_WARNING', 'ID_DEFAULT'),
       ],
   ],
   ```

3. **Buat Method Baru di `WhatsAppService.php`**:
   ```php
   public function notifyVirtualOfficeExpireWarning(Order $vo): ?WhatsappLog
   {
       $phone = ...;
       $templateId = config('services.botcake.templates.virtual_office_expire_warning');

       return $this->sendTemplateById(
           $phone,
           $templateId,
           'UTILITY', // atau 'MARKETING'
           [
               $clientName,
               $packageName,
               $expiredDate
           ],
           $vo->user_id,
           $vo->id
       );
   }
   ```

4. **Panggil dari Controller**:
   Cukup panggil `$whatsAppService->notifyVirtualOfficeExpireWarning($vo)` tanpa perlu menyentuh HTTP client, header formatting, atau payload structure Botcake!
