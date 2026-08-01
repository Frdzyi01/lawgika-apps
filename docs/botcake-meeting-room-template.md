# Dokumentasi Integrasi Botcake WhatsApp Template Message

Dokumen ini berisi panduan lengkap implementasi, penggunaan, pengembangaan, dan debugging notifikasi WhatsApp berbasis **Botcake API** (Pancake) pada aplikasi Laravel Lawgika.

---

## 1. File yang Diubah

| File | Status | Keterangan |
|------|--------|------------|
| [`.env`](file:///Applications/XAMPP/xamppfiles/htdocs/lawgika-apps/.env) | Modified | Menambahkan konfigurasi `BOTCAKE_API_KEY`, `BOTCAKE_BASE_URL`, `BOTCAKE_TEMPLATE_MEETING_ROOM_BOOKING`, `BOTCAKE_TEMPLATE_MEETING_ROOM_CHECKOUT`, dan `BOTCAKE_TEMPLATE_PODCAST_ROOM_BOOKING`. |
| [`config/services.php`](file:///Applications/XAMPP/xamppfiles/htdocs/lawgika-apps/config/services.php) | Modified | Menambahkan array `services.botcake` berisi `api_key`, `base_url`, `page_id`, dan daftar `templates`. |
| [`app/Services/WhatsAppService.php`](file:///Applications/XAMPP/xamppfiles/htdocs/lawgika-apps/app/Services/WhatsAppService.php) | Modified | Menambahkan method generic `sendTemplate()`, mengupdate `notifyMeetingRoomCreated()`, `notifyMeetingRoomCheckOut()`, `notifyPodcastRoomCreated()`, dan helper `calculateMeetingRoomRemainingQuota()`. |
| [`docs/botcake-meeting-room-template.md`](file:///Applications/XAMPP/xamppfiles/htdocs/lawgika-apps/docs/botcake-meeting-room-template.md) | Created/Updated | Dokumen panduan & teknis integrasi Botcake. |

---

## 2. Penjelasan Perubahan

### A. Arsitektur Terisolasi & Clean Code
- Integrasi **hanya** menggunakan **Botcake API (`pages.fm`)**, **tanpa Meta Graph API langsung**, tanpa `Phone Number ID`, dan tanpa `Access Token Meta`.
- `WhatsAppService.php` bertindak sebagai *service layer* terpusat. Controller (`PodcastRoomController.php`, `MeetingRoomController.php`), Model, Migration, dan Flow bisnis **TIDAK diubah**.

### B. Function Generic `sendTemplate()`
Method `sendTemplate()` dibuat generic untuk seluruh kebutuhan template di masa depan:
```php
public function sendTemplate(
    string $templateName,
    string $phone,
    array $parameters,
    ?int $clientId = null,
    ?int $orderId = null
): ?WhatsappLog
```

### C. Keamanan & Exception Handling
- Jika `BOTCAKE_API_KEY` belum dikonfigurasi (kosong), sistem tidak crash, melainkan mencatat Log warning dan mereturn instance `WhatsappLog` dengan status `FAILED`.
- Seluruh HTTP request dibungkus `try-catch`, dan hasilnya (berhasil/gagal) selalu dicatat di tabel `whatsapp_logs`.

---

## 3. Mapping Placeholder

### A. Template Meeting Room Created (`notifyMeetingRoomCreated`)
Template Name: `meeting_room_booking_confirmation`

| Placeholder | Deskripsi | Sumber Data Database | Fallback / Format |
|-------------|-----------|----------------------|-------------------|
| `{{1}}` | Nama Client | `$booking->user->pic_name` | `$booking->user->name` → `$booking->name` → `'Client'` |
| `{{2}}` | Nama Ruangan | `$booking->room_name` | `'Meeting Room'` |
| `{{3}}` | Tanggal Meeting | `$booking->date` | Format `d M Y` (Contoh: `15 Aug 2026`) |
| `{{4}}` | Jam Mulai | `$booking->start_time` | Format `H:i` (Contoh: `09:00`) |
| `{{5}}` | Jam Selesai | `$booking->end_time` | Format `H:i` (Contoh: `10:00`), fallback `$start_time + 1 jam` |
| `{{6}}` | Sisa Kuota | `calculateMeetingRoomRemainingQuota($booking)` | Pengecekan bertahap: Benefit → UserRoomQuota → Active Benefits |

---

### B. Template Meeting Room Check Out (`notifyMeetingRoomCheckOut`)
Template Name: `meeting_room_checkout`

| Placeholder | Deskripsi | Sumber Data / Parameter | Fallback / Format |
|-------------|-----------|-------------------------|-------------------|
| `{{1}}` | Nama Client | `$booking->user->pic_name` | `$booking->user->name` → `$booking->name` → `'Client'` |
| `{{2}}` | Nama Ruangan | `$booking->room_name` | `'Meeting Room'` |
| `{{3}}` | Tanggal Meeting | `$checkoutAt` | `$booking->date` → Format `d M Y` |
| `{{4}}` | Jam Mulai | `$checkinAt` | `$booking->start_time` → Format `H:i` |
| `{{5}}` | Jam Selesai | `$checkoutAt` | `$booking->end_time` → Format `H:i` |
| `{{6}}` | Sisa Kuota | `calculateMeetingRoomRemainingQuota($booking)` | Pengecekan bertahap: Benefit → UserRoomQuota → Active Benefits |

---

### C. Template Podcast Room Created (`notifyPodcastRoomCreated`)
Template Name: `podcast_room_booking_confirmation`

| Placeholder | Deskripsi | Sumber Data / Parameter | Fallback / Format |
|-------------|-----------|-------------------------|-------------------|
| `{{1}}` | Nama Client | `$booking->user->pic_name` | `$booking->user->name` → `$booking->name` → `'Client'` |
| `{{2}}` | Tanggal Penggunaan | `$booking->date` | Format `d M Y` (Contoh: `15 Aug 2026`) |
| `{{3}}` | Jam Mulai | `$booking->start_time` | Format `H:i` (Contoh: `09:00`) |
| `{{4}}` | Jam Selesai | `$booking->end_time` | Format `H:i` (Contoh: `10:00`), fallback `$start_time + 1 jam` |

---

## 4. Isi Template Message `podcast_room_booking_confirmation`

```
Halo Ibu/Bapak {{1}},

Booking Podcast Studio Anda telah dikonfirmasi! 🎉

━━━━━━━━━━━━━━━━━━

DETAIL BOOKING:

🎙️ Ruangan

Podcast Studio Lawgika Office, World Capital Tower Lt. 38 Unit 6-7, Mega Kuningan, Setia Budi, Jakarta Selatan, Indonesia

📅 Tanggal Penggunaan

{{2}}

🕒 Mulai

{{3}}

🕒 Selesai

{{4}}

━━━━━━━━━━━━━━━━━━

Info sebelum datang ke Lawgika:

✅ Booking sudah fix — tinggal datang

✅ Reschedule/batal maksimal H-1

✅ Booking & pembatalan harap dilakukan dengan bijak karena kuota terbatas

✅ No smoking & no vape di dalam ruangan

✅ Buanglah sampah pada tempatnya dan jagalah kebersihan

✅ Tukarkan KTP dengan Access Card dan sebutkan Lawgika.co.id Lantai 38

Tempat Parkir:

🏍️ Motor: WCT Lt LG

🚗 Mobil: WCT Lt LG - B2

Salam,

Lawgika.co.id
```

---

## 5. Cara Melakukan Testing & Debugging

### Testing via Tinker (CLI)
```bash
php artisan tinker
```
```php
$booking = App\Models\PodcastRoomBooking::latest()->first();
$service = app(App\Services\WhatsAppService::class);
$log = $service->notifyPodcastRoomCreated($booking);
dump($log);
```

### Cek Tabel Database `whatsapp_logs`
```sql
SELECT id, client_id, phone_number, message, status, response, created_at 
FROM whatsapp_logs 
ORDER BY id DESC 
LIMIT 10;
```

---

## 6. Checklist Implementasi

- [x] Template `podcast_room_booking_confirmation` terkonfigurasi di `.env` & `config/services.php`.
- [x] `notifyPodcastRoomCreated()` diperbarui untuk memanggil `sendTemplate()` dengan 4 parameter berurutan.
- [x] Parameter urutan: `[Nama Client, Tanggal Penggunaan, Jam Mulai, Jam Selesai]`.
- [x] Exception & response logging tercatat di `whatsapp_logs` dan `storage/logs/laravel.log`.
- [x] Controller `PodcastRoomController.php`, Model, Migration, & Flow Bisnis tetap utuh.
- [x] Syntax PHP terverifikasi bebas error.
