# Dokumentasi Fitur Notifikasi Tamu Datang Virtual Office via Botcake Official WABA API

Dokumen ini menjelaskan arsitektur, konfigurasi, dan alur kerja fitur **Notifikasi Tamu Datang Virtual Office** pada aplikasi Lawgika.

---

## 1. File yang Diubah & Dibuat

| No | File | Status | Keterangan |
|:---:|:---|:---:|:---|
| 1 | `database/migrations/2026_08_01_151000_create_virtual_office_guest_notifications_table.php` | **[NEW]** | Migration tabel `virtual_office_guest_notifications` |
| 2 | `app/Models/VirtualOfficeGuestNotification.php` | **[NEW]** | Model Eloquent `VirtualOfficeGuestNotification` |
| 3 | `app/Models/Order.php` | **[MODIFY]** | Penambahan relasi `guestNotifications()` |
| 4 | `.env` | **[MODIFY]** | Penambahan `BOTCAKE_TEMPLATE_VIRTUAL_OFFICE_GUEST_NOTIFICATION=1712545996642391` |
| 5 | `config/services.php` | **[MODIFY]** | Penambahan mapping template `virtual_office_guest_notification` |
| 6 | `routes/web.php` | **[MODIFY]** | Penambahan route POST `admin/virtual-office/{id}/guest-notification` |
| 7 | `app/Http/Controllers/Admin/VirtualOfficeController.php` | **[MODIFY]** | Penambahan method `sendGuestNotification()` |
| 8 | `app/Services/WhatsAppService.php` | **[MODIFY]** | Penambahan method `notifyVirtualOfficeGuest()` |
| 9 | `resources/views/admin/virtual-office/index.blade.php` | **[MODIFY]** | Penambahan tombol Action `👥 Tamu Datang`, Modal Popup, Live Preview, & AJAX SweetAlert2 |

---

## 2. Migration Baru

File: `database/migrations/2026_08_01_151000_create_virtual_office_guest_notifications_table.php`

```php
Schema::create('virtual_office_guest_notifications', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('virtual_office_id');
    $table->unsignedBigInteger('client_id');
    $table->string('guest_name');
    $table->string('guest_phone');
    $table->string('guest_company');
    $table->string('arrival_time', 20);
    $table->text('purpose');
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

File: `app/Models/VirtualOfficeGuestNotification.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualOfficeGuestNotification extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
Route::post('virtual-office/{id}/guest-notification', [\App\Http\Controllers\Admin\VirtualOfficeController::class, 'sendGuestNotification'])
    ->name('admin.virtual-office.guest-notification.store');
```

---

## 5. Controller yang Diubah

File: `app/Http/Controllers/Admin/VirtualOfficeController.php`

```php
public function sendGuestNotification(Request $request, $id, WhatsAppService $whatsAppService)
{
    $request->validate([
        'guest_name'    => 'required|string|max:255',
        'guest_phone'   => 'required|string|max:50',
        'guest_company' => 'required|string|max:255',
        'arrival_time'  => 'required',
        'purpose'       => 'required|string',
        'internal_note' => 'nullable|string',
    ]);

    $vo = Order::with('user')->findOrFail($id);

    $clientId = $vo->user_id ?? auth()->id();

    $notification = VirtualOfficeGuestNotification::create([
        'virtual_office_id' => $vo->id,
        'client_id'         => $clientId,
        'guest_name'        => $request->guest_name,
        'guest_phone'       => $request->guest_phone,
        'guest_company'     => $request->guest_company,
        'arrival_time'      => $request->arrival_time,
        'purpose'           => $request->purpose,
        'internal_note'     => $request->internal_note,
        'whatsapp_status'   => 'PENDING',
        'created_by'        => auth()->id(),
    ]);

    // Panggil Service WhatsApp
    $whatsAppService->notifyVirtualOfficeGuest($notification);

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi tamu berhasil dikirim ke WhatsApp Client.',
            'data'    => $notification->fresh(),
        ]);
    }

    return redirect()->back()->with('success', 'Notifikasi tamu berhasil dikirim ke WhatsApp Client.');
}
```

---

## 6. Service yang Diubah

File: `app/Services/WhatsAppService.php`

```php
public function notifyVirtualOfficeGuest(VirtualOfficeGuestNotification $notification): ?WhatsappLog
{
    $notification->loadMissing(['virtualOffice.user', 'client']);

    $phone = $notification->client->phone
        ?? $notification->virtualOffice->user->phone
        ?? ($notification->virtualOffice->form_data['pic_phone'] ?? null);

    if (empty($phone)) {
        Log::warning('WhatsAppService::notifyVirtualOfficeGuest - Nomor telepon client kosong.');
        $notification->update(['whatsapp_status' => 'FAILED']);
        return null;
    }

    $companyName = $notification->virtualOffice->user->company_name
        ?? $notification->client->company_name
        ?? ($notification->virtualOffice->form_data['company_name'] ?? null)
        ?? $notification->client->name
        ?? 'Client';

    $guestName    = $notification->guest_name ?? '-';
    $guestPhone   = $notification->guest_phone ?? '-';
    $guestCompany = $notification->guest_company ?? '-';
    $arrivalTime  = $notification->arrival_time
        ? \Carbon\Carbon::parse($notification->arrival_time)->format('H:i')
        : '-';
    $purpose      = $notification->purpose ?? '-';

    $templateId = config('services.botcake.templates.virtual_office_guest_notification', '1712545996642391');

    $log = $this->sendTemplateById(
        $phone,
        $templateId,
        'UTILITY',
        [
            $companyName,  // {{1}} Nama PT
            $guestName,    // {{2}} Nama Tamu
            $guestPhone,   // {{3}} Kontak Tamu
            $guestCompany, // {{4}} Instansi
            $arrivalTime,  // {{5}} Jam Datang
            $purpose,      // {{6}} Keperluan
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

Template Name: `virtual_office_guest_notification`  
Template ID: `1712545996642391`  
Category: `UTILITY`  
Language: `id`

| Placeholder | Parameter Name | Field Data | Contoh Value |
|:---:|:---:|:---|:---|
| `{{1}}` | 1 | Nama Perusahaan / PT | `PT Sumber Makmur Jaya` |
| `{{2}}` | 2 | Nama Tamu | `Bpk. Ahmad Subagyo` |
| `{{3}}` | 3 | Kontak Tamu (Nomor HP Tamu) | `081234567890` |
| `{{4}}` | 4 | Instansi | `PT Telkom Indonesia` |
| `{{5}}` | 5 | Jam Datang (Formatted) | `14:30` |
| `{{6}}` | 6 | Keperluan | `Diskusi penawaran kerjasama dan penyerahan proposal` |

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
                    "template_id": "1712545996642391",
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
                                    "value": "Bpk. Ahmad Subagyo"
                                },
                                {
                                    "key": "{{3}}",
                                    "parameter_name": "3",
                                    "value": "081234567890"
                                },
                                {
                                    "key": "{{4}}",
                                    "parameter_name": "4",
                                    "value": "PT Telkom Indonesia"
                                },
                                {
                                    "key": "{{5}}",
                                    "parameter_name": "5",
                                    "value": "14:30"
                                },
                                {
                                    "key": "{{6}}",
                                    "parameter_name": "6",
                                    "value": "Diskusi penawaran kerjasama dan penyerahan proposal"
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

## 9. Contoh Request dan Response

### Request Data dari Form Admin
```json
{
    "virtual_office_id": 12,
    "client_id": 5,
    "guest_name": "Bpk. Ahmad Subagyo",
    "guest_phone": "081234567890",
    "guest_company": "PT Telkom Indonesia",
    "arrival_time": "14:30",
    "purpose": "Diskusi penawaran kerjasama dan penyerahan proposal",
    "internal_note": "Tamu menunggu di lobby utama lantai 38",
    "created_by": 1
}
```

### Response HTTP Botcake
```json
{
    "success": true
}
```

---

## 10. Flow Sistem Lengkap

```
Admin Membuka Halaman Virtual Office (/admin/virtual-office)
                          ↓
Admin Klik Tombol "👥 Tamu Datang" Pada Salah Satu Client
                          ↓
Modal Popup Muncul (Data Client terisi otomatis sebagai Read-only)
                          ↓
Admin Mengisi Form: Nama Tamu, No HP Tamu, Instansi, Jam Datang, Keperluan
                          ↓
Live Preview WhatsApp Berubah Secara Realtime Mengikuti Input Admin
                          ↓
Admin Klik "Kirim Notifikasi"
                          ↓
Submit Form via AJAX ke POST /admin/virtual-office/{id}/guest-notification
                          ↓
VirtualOfficeController::sendGuestNotification() Memvalidasi Input
                          ↓
Simpan Data ke Tabel virtual_office_guest_notifications (status: PENDING)
                          ↓
Controller Memanggil WhatsAppService::notifyVirtualOfficeGuest()
                          ↓
Service Menyusun 6 Parameter & Memanggil sendTemplateById()
                          ↓
sendTemplateById() Memformat Nomor HP → PSID (wa_628xxx) & Kirim HTTP POST ke Botcake API
                          ↓
Log Lengkap (Request/Response) Dicatat ke whatsapp_logs & storage/logs/laravel.log
                          ↓
Status di virtual_office_guest_notifications Diperbarui Menjadi SUCCESS / FAILED
                          ↓
SweetAlert2 Muncul: "Notifikasi tamu berhasil dikirim ke WhatsApp Client."
```

---

## 11. Konsistensi Arsitektur Botcake Official WABA API

Seluruh notifikasi WhatsApp di sistem Lawgika (Meeting Room Confirmation, Meeting Room Checkout, Podcast Confirmation, Podcast Checkout, Surat Masuk Virtual Office, dan **Tamu Datang Virtual Office**) menggunakan arsitektur terpusat yang sama:

1. **Centralized Transport Engine**: `WhatsAppService::sendTemplateById()` bertindak sebagai single source of truth untuk komunikasi HTTP ke Botcake Public API.
2. **Unified OpenAPI Format**: Seluruh payload dibentuk 100% mengikuti spesifikasi Botcake OpenAPI (`document.json`).
3. **Comprehensive Logging**: Setiap request dan response mentah tersimpan secara konsisten di tabel `whatsapp_logs` dan `laravel.log`.
4. **Zero Impact on Existing Features**: Pengembangan modul baru dilakukan sebagai modul tambahan tanpa mengubah business logic atau flow yang sudah berjalan.
