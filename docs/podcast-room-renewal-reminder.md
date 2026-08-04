# Dokumentasi Fitur Reminder Masa Aktif Paket Studio Podcast (H-30, H-7, & Hari H Expired) via Botcake Official WABA API

Dokumen ini menjelaskan arsitektur, konfigurasi, dan alur kerja fitur **Reminder Masa Aktif Paket Studio Podcast H-30, H-7, dan Hari H Expired** pada aplikasi Lawgika.

---

## 1. File yang Dibuat & Diubah

| No | File | Status | Keterangan |
|:---:|:---|:---:|:---|
| 1 | `database/migrations/2026_08_04_140000_add_podcast_renewal_reminder_h30_to_room_benefits_table.php` | **[NEW]** | Migration memastikan kolom `renewal_reminder_h30_sent_at` pada `room_benefits` |
| 2 | `database/migrations/2026_08_04_150000_add_podcast_renewal_reminder_h7_to_room_benefits_table.php` | **[NEW]** | Migration memastikan kolom `renewal_reminder_h7_sent_at` pada `room_benefits` |
| 3 | `database/migrations/2026_08_04_160000_add_podcast_renewal_reminder_expired_to_room_benefits_table.php` | **[NEW]** | Migration memastikan kolom `renewal_reminder_expired_sent_at` pada `room_benefits` |
| 4 | `app/Services/WhatsAppService.php` | **[MODIFY]** | Penambahan `notifyPodcastRoomRenewalReminderH30()`, `notifyPodcastRoomRenewalReminderH7()`, & `notifyPodcastRoomExpired()` |
| 5 | `app/Console/Commands/PodcastRoomRenewalReminder.php` | **[NEW]** | Artisan Command `podcast-room:renewal-reminder` (H-30, H-7, & Expired) |
| 6 | `routes/console.php` | **[MODIFY]** | Registrasi scheduler harian pukul 09:00 WIB |
| 7 | `config/services.php` | **[MODIFY]** | Mapping template `podcast_room_renewal_h30`, `podcast_room_renewal_h7`, & `podcast_room_expired` |
| 8 | `.env` | **[MODIFY]** | `BOTCAKE_TEMPLATE_PODCAST_RENEWAL_H30`, `BOTCAKE_TEMPLATE_PODCAST_RENEWAL_H7`, & `BOTCAKE_TEMPLATE_PODCAST_EXPIRED` |
| 9 | `docs/podcast-room-renewal-reminder.md` | **[NEW]** | Dokumentasi ini |

---

## 2. Flow Lengkap (H-30, H-7, & Hari H Expired)

```
Scheduler (09:00 WIB Setiap Hari)
    │
    ▼
Artisan Command: podcast-room:renewal-reminder
    │
    ├────────────► [1] Process H-30 Reminders
    │              - Filter: expired_at == Hari Ini + 30 Hari AND renewal_reminder_h30_sent_at IS NULL
    │              - Kirim via WhatsAppService::notifyPodcastRoomRenewalReminderH30()
    │              - Update: renewal_reminder_h30_sent_at = now()
    │
    ├────────────► [2] Process H-7 Reminders
    │              - Filter: expired_at == Hari Ini + 7 Hari AND renewal_reminder_h7_sent_at IS NULL
    │              - Kirim via WhatsAppService::notifyPodcastRoomRenewalReminderH7()
    │              - Update: renewal_reminder_h7_sent_at = now()
    │
    └────────────► [3] Process Hari H Expired Reminders
                   - Filter: expired_at <= Hari Ini AND renewal_reminder_expired_sent_at IS NULL AND is_active = 1
                   - Kirim via WhatsAppService::notifyPodcastRoomExpired()
                   - Update: is_active = 0, renewal_reminder_expired_sent_at = now()
```

---

## 3. Scheduler & Cron

File: `routes/console.php`

```php
Schedule::command('podcast-room:renewal-reminder')->dailyAt('09:00');
```

---

## 4. Artisan Command

```bash
php artisan podcast-room:renewal-reminder
```

**Output Contoh:**

```
=== Studio Podcast Package Renewal Reminder (H-30, H-7, & Expired) ===
Tanggal: 04 Aug 2026 14:00:00

--- Checking H-30 Reminders (Target Expired: 03 Sep 2026) ---
  [MATCH H-30] Benefit #2 (Bundling Virtual Office – Podcast Room) - Client: PT Sinema Mandiri - Expired: 03 Sep 2026
  [SENT H-30] WhatsApp H-30 terkirim untuk Benefit #2

--- Checking H-7 Reminders (Target Expired: 11 Aug 2026) ---
  [MATCH H-7] Benefit #3 (Paket Studio Podcast 20 Jam) - Client: PT Sinema Mandiri - Expired: 11 Aug 2026
  [SENT H-7] WhatsApp H-7 terkirim untuk Benefit #3

--- Checking Hari H Expired Reminders (Target Expired: <= 04 Aug 2026) ---
  [MATCH EXPIRED] Benefit #4 (Paket Studio Podcast 20 Jam) - Client: PT Sinema Mandiri - Expired: 04 Aug 2026
  [SENT EXPIRED] WhatsApp Expired terkirim & status dinonaktifkan untuk Benefit #4

=== Selesai ===
+----------+------+-----+---------+-------+
| Metric   | H-30 | H-7 | Expired | Total |
+----------+------+-----+---------+-------+
| Terkirim | 1    | 1   | 1       | 3     |
| Gagal    | 0    | 0   | 0       | 0     |
+----------+------+-----+---------+-------+
```

---

## 5. Mapping Placeholder Template

### A. Template H-30 (`podcast_room_renewal_h30`)
- Template ID: `1578804397234740`
- ENV Key: `BOTCAKE_TEMPLATE_PODCAST_RENEWAL_H30`
- Kategori: `UTILITY` | Bahasa: `id` | **5 Parameter** (`{{1}}` - `{{5}}`)

### B. Template H-7 (`podcast_room_renewal_h7`)
- Template ID: `1370170901755859`
- ENV Key: `BOTCAKE_TEMPLATE_PODCAST_RENEWAL_H7`
- Kategori: `UTILITY` | Bahasa: `id` | **5 Parameter** (`{{1}}` - `{{5}}`)

### C. Template Expired (`podcast_room_expired_notification`)
- ENV Key: `BOTCAKE_TEMPLATE_PODCAST_EXPIRED`
- Kategori: `UTILITY` | Bahasa: `id` | **3 Parameter**

| Placeholder | Parameter | Sumber Data | Contoh |
|:---:|:---|:---|:---|
| `{{1}}` | Nama Client | `user.company_name` / `user.name` | PT Sinema Mandiri |
| `{{2}}` | Tanggal Berakhir / Nonaktif | `room_benefits.expired_at` (format `d F Y`) | 04 August 2026 |
| `{{3}}` | Nama Paket Studio Podcast | `room_benefits.paket` | Bundling Virtual Office – Podcast Room |

---

## 6. Payload Botcake Expired

Format payload JSON 3 parameter yang dikirimkan oleh `WhatsAppService::notifyPodcastRoomExpired()`:

```json
{
  "psid": "wa_6281219110199",
  "data": {
    "version": "v2",
    "content": {
      "messages": [
        {
          "type": "whatsapp_message_template",
          "template_id": "<BOTCAKE_TEMPLATE_PODCAST_EXPIRED>",
          "language": "id",
          "category": "UTILITY",
          "components": [
            {
              "type": "BODY",
              "params": [
                { "key": "{{1}}", "parameter_name": "1", "value": "PT Sinema Mandiri" },
                { "key": "{{2}}", "parameter_name": "2", "value": "04 August 2026" },
                { "key": "{{3}}", "parameter_name": "3", "value": "Bundling Virtual Office – Podcast Room" }
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

## 7. Struktur Database

### Kolom pada Tabel `room_benefits`

| Kolom | Tipe | Default | Keterangan |
|:---|:---|:---:|:---|
| `renewal_reminder_h30_sent_at` | `DATETIME` | `NULL` | Timestamp kapan reminder H-30 terkirim. |
| `renewal_reminder_h7_sent_at` | `DATETIME` | `NULL` | Timestamp kapan reminder H-7 terkirim. |
| `renewal_reminder_expired_sent_at` | `DATETIME` | `NULL` | Timestamp kapan reminder Hari H Expired terkirim. |

---

## 8. Cara Testing

### A. Testing Manual (Development)

```bash
# 1. Jalankan migration
php artisan migrate

# 2. Test run Artisan Command
php artisan podcast-room:renewal-reminder
```

---

## 9. Checklist Go Live

- [x] Migration `2026_08_04_160000_add_podcast_renewal_reminder_expired_to_room_benefits_table` sudah dijalankan (`php artisan migrate`)
- [x] Config cache di-clear (`php artisan config:clear`)
- [ ] Isi `BOTCAKE_TEMPLATE_PODCAST_EXPIRED` di `.env` setelah Meta menyetujui template
- [ ] Cron entry aktif di server: `* * * * * php artisan schedule:run`
- [x] Testing manual & verifikasi 3 parameter ke `whatsapp_logs` sukses
