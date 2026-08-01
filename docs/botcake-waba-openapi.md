# Dokumentasi Integrasi Botcake Official WABA API

Dokumen ini berisi panduan lengkap implementasi pengiriman WhatsApp Template Message melalui **Botcake Official WABA API** pada aplikasi Laravel Lawgika.

Seluruh implementasi mengikuti 100% spesifikasi **OpenAPI Botcake (`document.json`)**.

---

## 1. File yang Diubah

| No | File | Perubahan |
| :---: | :--- | :--- |
| 1 | `.env` | Menambahkan `BOTCAKE_API_URL`, `BOTCAKE_ACCESS_TOKEN`, `BOTCAKE_PAGE_ID`, dan 3 Template ID |
| 2 | `config/services.php` | Mendaftarkan konfigurasi `botcake` (api_url, access_token, page_id, templates) |
| 3 | `app/Services/WhatsAppService.php` | Menambah `sendTemplateById()`, `formatPsid()`, update 3 notify methods |

---

## 2. Method yang Diubah

| Method | Perubahan |
| :--- | :--- |
| `sendTemplateById()` | **BARU** — menggantikan `sendTemplate()`. Signature: `sendTemplateById(string $phone, string $templateId, string $category, array $parameters)` |
| `notifyMeetingRoomCreated()` | Sekarang memanggil `sendTemplateById()` dengan `config('services.botcake.templates.meeting_room_confirmation')` |
| `notifyMeetingRoomCheckOut()` | Sekarang memanggil `sendTemplateById()` dengan `config('services.botcake.templates.meeting_room_checkout')` |
| `notifyPodcastRoomCreated()` | Sekarang memanggil `sendTemplateById()` dengan `config('services.botcake.templates.podcast_room_confirmation')` |
| `formatPsid()` | **BARU** — mengkonversi nomor telepon ke format PSID `wa_628xxx` |

---

## 3. Config yang Ditambahkan

### `.env`
```env
BOTCAKE_API_URL=https://botcake.io/api/public_api/v1
BOTCAKE_ACCESS_TOKEN=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
BOTCAKE_PAGE_ID=waba_1053844194483527
BOTCAKE_TEMPLATE_MEETING_ROOM_CONFIRMATION=1852676328789174
BOTCAKE_TEMPLATE_MEETING_ROOM_CHECKOUT=1852676328789180
BOTCAKE_TEMPLATE_PODCAST_ROOM_CONFIRMATION=1852676328789191
```

### `config/services.php`
```php
'botcake' => [
    'api_url'      => env('BOTCAKE_API_URL', 'https://botcake.io/api/public_api/v1'),
    'access_token' => env('BOTCAKE_ACCESS_TOKEN'),
    'page_id'      => env('BOTCAKE_PAGE_ID', env('PANCAKE_PAGE_ID')),
    'templates'    => [
        'meeting_room_confirmation' => env('BOTCAKE_TEMPLATE_MEETING_ROOM_CONFIRMATION', '...'),
        'meeting_room_checkout'     => env('BOTCAKE_TEMPLATE_MEETING_ROOM_CHECKOUT', '...'),
        'podcast_room_confirmation' => env('BOTCAKE_TEMPLATE_PODCAST_ROOM_CONFIRMATION', '...'),
    ],
],
```

---

## 4. Mapping Template ID

| Config Key | Env Variable | Template ID | Nama Template di Botcake |
| :--- | :--- | :--- | :--- |
| `meeting_room_confirmation` | `BOTCAKE_TEMPLATE_MEETING_ROOM_CONFIRMATION` | `1852676328789174` | meeting_room_booking_confirmation |
| `meeting_room_checkout` | `BOTCAKE_TEMPLATE_MEETING_ROOM_CHECKOUT` | `1852676328789180` | meeting_room_checkout |
| `podcast_room_confirmation` | `BOTCAKE_TEMPLATE_PODCAST_ROOM_CONFIRMATION` | `1852676328789191` | podcast_room_booking_confirmation |

---

## 5. Mapping Placeholder

### Meeting Room Confirmation (6 Parameter)
| Placeholder | Parameter Name | Data Source |
| :--- | :--- | :--- |
| `{{1}}` | Nama Client | `$booking->user->pic_name ?? name` |
| `{{2}}` | Nama Ruangan | `$booking->room_name` |
| `{{3}}` | Tanggal Meeting | `Carbon::parse($booking->date)->format('d M Y')` |
| `{{4}}` | Jam Mulai | `Carbon::parse($booking->start_time)->format('H:i')` |
| `{{5}}` | Jam Selesai | `Carbon::parse($booking->end_time)->format('H:i')` |
| `{{6}}` | Sisa Kuota | `calculateMeetingRoomRemainingQuota()` |

### Meeting Room Check Out (6 Parameter)
| Placeholder | Parameter Name | Data Source |
| :--- | :--- | :--- |
| `{{1}}` | Nama Client | `$booking->user->pic_name ?? name` |
| `{{2}}` | Nama Ruangan | `$booking->room_name` |
| `{{3}}` | Tanggal | `Carbon::parse($checkoutAt)->format('d M Y')` |
| `{{4}}` | Jam Mulai | `Carbon::parse($checkinAt)->format('H:i')` |
| `{{5}}` | Jam Selesai | `Carbon::parse($checkoutAt)->format('H:i')` |
| `{{6}}` | Sisa Kuota | `calculateMeetingRoomRemainingQuota()` |

### Podcast Room Confirmation (4 Parameter)
| Placeholder | Parameter Name | Data Source |
| :--- | :--- | :--- |
| `{{1}}` | Nama Client | `$booking->user->pic_name ?? name` |
| `{{2}}` | Tanggal Penggunaan | `Carbon::parse($booking->date)->format('d M Y')` |
| `{{3}}` | Jam Mulai | `Carbon::parse($booking->start_time)->format('H:i')` |
| `{{4}}` | Jam Selesai | `Carbon::parse($booking->end_time)->format('H:i')` |

---

## 6. Contoh Request JSON

### Endpoint
```
POST https://botcake.io/api/public_api/v1/pages/waba_1053844194483527/flows/send_content
```

### Headers
```http
Accept: application/json
Content-Type: application/json
access-token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Body
```json
{
    "psid": "wa_6281219110199",
    "data": {
        "version": "v2",
        "content": {
            "messages": [
                {
                    "type": "whatsapp_message_template",
                    "template_id": "1852676328789174",
                    "language": "id",
                    "category": "UTILITY",
                    "components": [
                        {
                            "type": "BODY",
                            "params": [
                                {
                                    "key": "{{1}}",
                                    "parameter_name": "1",
                                    "value": "Siti Aminah"
                                },
                                {
                                    "key": "{{2}}",
                                    "parameter_name": "2",
                                    "value": "Meeting Room VIP"
                                },
                                {
                                    "key": "{{3}}",
                                    "parameter_name": "3",
                                    "value": "01 Aug 2026"
                                },
                                {
                                    "key": "{{4}}",
                                    "parameter_name": "4",
                                    "value": "09:00"
                                },
                                {
                                    "key": "{{5}}",
                                    "parameter_name": "5",
                                    "value": "11:00"
                                },
                                {
                                    "key": "{{6}}",
                                    "parameter_name": "6",
                                    "value": "10 Jam"
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

## 7. Contoh Response JSON

### Sukses (HTTP 200)
```json
{
    "success": true
}
```

### Gagal
```json
{
    "success": false,
    "error": {
        "code": 400,
        "message": "Invalid template_id or PSID not found"
    }
}
```

---

## 8. Cara Kerja Flow Baru

```
Admin membuat reservasi Meeting Room
         ↓
Controller memanggil WhatsAppService::notifyMeetingRoomCreated($booking)
         ↓
Method menyiapkan 6 parameter dari data booking
         ↓
Membaca template_id dari config('services.botcake.templates.meeting_room_confirmation')
         ↓
Memanggil sendTemplateById($phone, $templateId, 'UTILITY', $params)
         ↓
sendTemplateById() mem-format nomor HP → PSID (wa_628xxx)
         ↓
sendTemplateById() menyusun payload sesuai OpenAPI Botcake
         ↓
HTTP POST ke https://botcake.io/api/public_api/v1/pages/{page_id}/flows/send_content
         ↓
Header: access-token (JWT), Content-Type, Accept
         ↓
Botcake meneruskan ke Meta WhatsApp Cloud API
         ↓
WhatsApp Template Message dikirim langsung ke nomor client
         ↓
Response dicatat ke Log::info/error + WhatsappLog DB
```

---

## 9. Perbedaan Implementasi Lama vs Baru

| Aspek | Implementasi LAMA ❌ | Implementasi BARU ✅ |
| :--- | :--- | :--- |
| **Endpoint** | `/conversations/{id}/messages` | `/pages/{id}/flows/send_content` |
| **Auth** | `?page_access_token=` (query param) | Header `access-token` |
| **Identitas Template** | `template_name` (string nama) | `template_id` (string ID numerik) |
| **Identitas Penerima** | `conversation_id` | `psid` (wa_628xxx) |
| **Payload Root** | `action: send_whatsapp_template` | `psid + data.version + data.content.messages[]` |
| **Param Format** | `{ type: text, text: value }` | `{ key: "{{1}}", parameter_name: "1", value: "..." }` |
| **Method** | `sendTemplate($key, $phone, ...)` | `sendTemplateById($phone, $templateId, $category, ...)` |
| **Referensi** | Asumsi / Pancake docs | OpenAPI Spec `document.json` |

---

## 10. Checklist Go-Live

- [ ] Pastikan `BOTCAKE_API_URL` = `https://botcake.io/api/public_api/v1`
- [ ] Pastikan `BOTCAKE_ACCESS_TOKEN` terisi JWT token valid dari Botcake
- [ ] Pastikan `BOTCAKE_PAGE_ID` terisi ID WABA Page (contoh: `waba_1053844194483527`)
- [ ] Pastikan seluruh `BOTCAKE_TEMPLATE_*` terisi ID numerik template yang sudah ACTIVE di Botcake
- [ ] Jalankan `php artisan config:cache` di production
- [ ] Test kirim 1 template ke nomor internal
- [ ] Verifikasi log di `storage/logs/laravel.log`
- [ ] Verifikasi record di tabel `whatsapp_logs`
- [ ] Hapus file `test_botcake_payload.php` dari production
