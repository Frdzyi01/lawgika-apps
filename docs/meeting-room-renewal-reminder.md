# Dokumentasi Fitur Reminder Masa Aktif Paket Meeting Room (H-30, H-7, & Hari H Expired) via Botcake Official WABA API

Dokumen ini menjelaskan arsitektur, konfigurasi, dan alur kerja fitur **Reminder Masa Aktif Paket Meeting Room H-30, H-7, dan Hari H Expired** pada aplikasi Lawgika.

---

## 1. File yang Dibuat & Diubah

| No | File | Status | Keterangan |
|:---:|:---|:---:|:---|
| 1 | `database/migrations/2026_08_03_170000_add_renewal_reminder_h30_to_room_benefits_table.php` | **[NEW]** | Migration menambah kolom `renewal_reminder_h30_sent_at` pada `room_benefits` |
| 2 | `database/migrations/2026_08_03_180000_add_renewal_reminder_h7_to_room_benefits_table.php` | **[NEW]** | Migration menambah kolom `renewal_reminder_h7_sent_at` pada `room_benefits` |
| 3 | `database/migrations/2026_08_03_190000_add_renewal_reminder_expired_to_room_benefits_table.php` | **[NEW]** | Migration menambah kolom `renewal_reminder_expired_sent_at` pada `room_benefits` |
| 4 | `app/Models/RoomBenefit.php` | **[MODIFY]** | Penambahan `renewal_reminder_h30_sent_at`, `renewal_reminder_h7_sent_at`, & `renewal_reminder_expired_sent_at` pada `$fillable` dan `$casts` |
| 5 | `app/Services/WhatsAppService.php` | **[MODIFY]** | Penambahan `notifyMeetingRoomRenewalReminderH30()`, `notifyMeetingRoomRenewalReminderH7()`, & `notifyMeetingRoomExpired()` |
| 6 | `app/Console/Commands/MeetingRoomRenewalReminder.php` | **[NEW]** | Artisan Command `meeting-room:renewal-reminder` (H-30, H-7, & Expired) |
| 7 | `routes/console.php` | **[MODIFY]** | Registrasi scheduler harian pukul 09:00 WIB |
| 8 | `config/services.php` | **[MODIFY]** | Mapping template `meeting_room_renewal_h30`, `meeting_room_renewal_h7`, & `meeting_room_expired` |
| 9 | `.env` | **[MODIFY]** | `BOTCAKE_TEMPLATE_MEETING_ROOM_RENEWAL_H30`, `BOTCAKE_TEMPLATE_MEETING_ROOM_RENEWAL_H7`, & `BOTCAKE_TEMPLATE_MEETING_ROOM_EXPIRED` |
| 10 | `docs/meeting-room-renewal-reminder.md` | **[NEW]** | Dokumentasi ini |

---

## 2. Flow Lengkap (H-30, H-7, & Hari H Expired)

```
Scheduler (09:00 WIB Setiap Hari)
    │
    ▼
Artisan Command: meeting-room:renewal-reminder
    │
    ├────────────► [1] Process H-30 Reminders
    │              - Filter: expired_at == Hari Ini + 30 Hari AND renewal_reminder_h30_sent_at IS NULL
    │              - Kirim via WhatsAppService::notifyMeetingRoomRenewalReminderH30()
    │              - Update: renewal_reminder_h30_sent_at = now()
    │
    ├────────────► [2] Process H-7 Reminders
    │              - Filter: expired_at == Hari Ini + 7 Hari AND renewal_reminder_h7_sent_at IS NULL
    │              - Kirim via WhatsAppService::notifyMeetingRoomRenewalReminderH7()
    │              - Update: renewal_reminder_h7_sent_at = now()
    │
    └────────────► [3] Process Hari H Expired Reminders
                   - Filter: expired_at <= Hari Ini AND renewal_reminder_expired_sent_at IS NULL AND is_active = 1
                   - Kirim via WhatsAppService::notifyMeetingRoomExpired()
                   - Update: is_active = 0, renewal_reminder_expired_sent_at = now()
```

---

## 3. Scheduler & Cron

File: `routes/console.php`

```php
Schedule::command('meeting-room:renewal-reminder')->dailyAt('09:00');
```

---

## 4. Artisan Command

```bash
php artisan meeting-room:renewal-reminder
```

**Output Contoh:**

```
=== Meeting Room Package Renewal Reminder (H-30, H-7, & Expired) ===
Tanggal: 03 Aug 2026 16:00:00

--- Checking H-30 Reminders (Target Expired: 02 Sep 2026) ---
  [MATCH H-30] Benefit #1 (Bundling Virtual Office – Meeting Room) - Client: PT Sinema Mandiri - Expired: 02 Sep 2026
  [SENT H-30] WhatsApp H-30 terkirim untuk Benefit #1

--- Checking H-7 Reminders (Target Expired: 10 Aug 2026) ---
  [MATCH H-7] Benefit #2 (Bundling Virtual Office – Meeting Room) - Client: PT Sinema Mandiri - Expired: 10 Aug 2026
  [SENT H-7] WhatsApp H-7 terkirim untuk Benefit #2

--- Checking Hari H Expired Reminders (Target Expired: <= 03 Aug 2026) ---
  [MATCH EXPIRED] Benefit #3 (Paket Meeting Room 60 Jam) - Client: PT Sinema Mandiri - Expired: 03 Aug 2026
  [SENT EXPIRED] WhatsApp Expired terkirim & status dinonaktifkan untuk Benefit #3

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

### A. Template H-30 (`meeting_room_renewal_h30`)
- Template ID: `1062841652789555`
- ENV Key: `BOTCAKE_TEMPLATE_MEETING_ROOM_RENEWAL_H30`
- Kategori: `UTILITY` | Bahasa: `id` | **5 Parameter** (`{{1}}` - `{{5}}`)

### B. Template H-7 (`meeting_room_renewal_h7`)
- Template ID: `1291481916230373`
- ENV Key: `BOTCAKE_TEMPLATE_MEETING_ROOM_RENEWAL_H7`
- Kategori: `UTILITY` | Bahasa: `id` | **6 Parameter** (`{{1}}` - `{{6}}`)

### C. Template Expired (`meeting_room_expired_notification`)
- Template ID: `1020652014088993`
- ENV Key: `BOTCAKE_TEMPLATE_MEETING_ROOM_EXPIRED`
- Kategori: `UTILITY` | Bahasa: `id` | **3 Parameter**

| Placeholder | Parameter | Sumber Data | Contoh |
|:---:|:---|:---|:---|
| `{{1}}` | Nama Client | `user.company_name` / `user.name` | PT Sinema Mandiri |
| `{{2}}` | Tanggal Nonaktif | `room_benefits.expired_at` (format `d F Y`) | 03 August 2026 |
| `{{3}}` | Nama Paket Meeting Room | `room_benefits.paket` | Bundling Virtual Office – Meeting Room |

---

## 6. Payload Botcake Expired

Format payload JSON 3 parameter yang dikirimkan oleh `WhatsAppService::notifyMeetingRoomExpired()`:

```json
{
  "psid": "wa_6281219110199",
  "data": {
    "version": "v2",
    "content": {
      "messages": [
        {
          "type": "whatsapp_message_template",
          "template_id": "1020652014088993",
          "language": "id",
          "category": "UTILITY",
          "components": [
            {
              "type": "BODY",
              "params": [
                { "key": "{{1}}", "parameter_name": "1", "value": "PT Sinema Mandiri" },
                { "key": "{{2}}", "parameter_name": "2", "value": "03 August 2026" },
                { "key": "{{3}}", "parameter_name": "3", "value": "Bundling Virtual Office – Meeting Room" }
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
php artisan meeting-room:renewal-reminder
```

---

## 9. Checklist Go Live

- [x] Migration `2026_08_03_190000_add_renewal_reminder_expired_to_room_benefits_table` sudah dijalankan (`php artisan migrate`)
- [x] Config cache di-clear (`php artisan config:clear`)
- [x] Template ID dikonfigurasi di `.env`: `BOTCAKE_TEMPLATE_MEETING_ROOM_EXPIRED=1020652014088993`
- [ ] Cron entry aktif di server: `* * * * * php artisan schedule:run`
- [x] Testing manual & verifikasi 3 parameter ke `whatsapp_logs` sukses
