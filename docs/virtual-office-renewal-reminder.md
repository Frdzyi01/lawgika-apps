# Dokumentasi Fitur Reminder Renewal Virtual Office (H-30, H-7, & Hari H Expired) via Botcake Official WABA API

Dokumen ini menjelaskan arsitektur, konfigurasi, dan alur kerja fitur **Reminder Renewal Virtual Office H-30, H-7, & Hari H Expired** pada aplikasi Lawgika.

---

## 1. File yang Dibuat & Diubah

| No | File | Status | Keterangan |
|:---:|:---|:---:|:---|
| 1 | `database/migrations/2026_08_03_140000_add_renewal_reminder_h30_to_orders_table.php` | **[NEW]** | Migration menambah kolom `renewal_reminder_h30_sent_at` |
| 2 | `database/migrations/2026_08_03_150000_add_renewal_reminder_h7_to_orders_table.php` | **[NEW]** | Migration menambah kolom `renewal_reminder_h7_sent_at` |
| 3 | `database/migrations/2026_08_03_160000_add_renewal_reminder_expired_to_orders_table.php` | **[NEW]** | Migration menambah kolom `renewal_reminder_expired_sent_at` |
| 4 | `app/Console/Commands/VirtualOfficeRenewalReminder.php` | **[NEW]** | Artisan Command `virtual-office:renewal-reminder` (H-30, H-7, & Expired) |
| 5 | `app/Services/WhatsAppService.php` | **[MODIFY]** | Penambahan `notifyVirtualOfficeRenewalReminder()`, `notifyVirtualOfficeRenewalReminderH7()`, & `notifyVirtualOfficeExpired()` (dengan percabangan template Enterprise [3 params] vs Premium/Eksklusif [4 params]) |
| 6 | `routes/console.php` | **[MODIFY]** | Registrasi scheduler harian pukul 09:00 WIB |
| 7 | `config/services.php` | **[MODIFY]** | Mapping template `virtual_office_renewal_h30`, `virtual_office_renewal_h7`, `virtual_office_expired`, & `virtual_office_expired_enterprise` |
| 8 | `.env` | **[MODIFY]** | `BOTCAKE_TEMPLATE_VO_RENEWAL_H30`, `BOTCAKE_TEMPLATE_VO_RENEWAL_H7`, `BOTCAKE_TEMPLATE_VO_EXPIRED`, & `BOTCAKE_TEMPLATE_VO_EXPIRED_ENTERPRISE` |
| 9 | `docs/virtual-office-renewal-reminder.md` | **[NEW]** | Dokumentasi ini |

---

## 2. Flow Lengkap (Scheduler H-30, H-7, & Hari H Expired)

```
Scheduler (09:00 WIB Setiap Hari)
    │
    ▼
Artisan Command: virtual-office:renewal-reminder
    │
    ├───────────────────────────────────┬───────────────────────────────────┐
    │                                   │                                   │
    ▼                                   ▼                                   ▼
[Proses H-30 Reminder]              [Proses H-7 Reminder]              [Proses Hari H Expired]
Query: VO Aktif &                   Query: VO Aktif &                   Query: VO Aktif &
renewal_reminder_h30_sent_at        renewal_reminder_h7_sent_at         renewal_reminder_expired_sent_at
IS NULL                             IS NULL                             IS NULL
    │                                   │                                   │
    ▼                                   ▼                                   ▼
Bandingkan expired_at               Bandingkan expired_at               Bandingkan expired_at
== Hari Ini + 30                    == Hari Ini + 7                     == Hari Ini
    │                                   │                                   │
    ├── YA                              ├── YA                              ├── YA
    │   │                               │   │                               │   │
    │   ▼                               │   ▼                               │   ▼
    │ WhatsAppService::                 │ WhatsAppService::                 │ WhatsAppService::
    │ notifyVirtualOfficeRenewal        │ notifyVirtualOfficeRenewal        │ notifyVirtualOfficeExpired($vo)
    │ Reminder($vo)                     │ ReminderH7($vo)                   │   │
    │   │                               │   │                               │   ├─ Paket Enterprise?
    │   ▼                               │   ▼                               │   │   ├── YA → Template: virtual_office_expired_enterprise (ID 1818055572497687, 3 params)
    │ sendTemplateById()                │ sendTemplateById()                │   │   └── TIDAK → Template: virtual_office_expired_notification (ID 1757632552089608, 4 params)
    │ (Template ID: 1329567535585592)   │ (Template ID: 1025817360352995)   │   │
    │   │                               │   │                               │   ▼
    │   ▼                               │   ▼                               │ sendTemplateById()
    │ Update: renewal_reminder_h30      │ Update: renewal_reminder_h7       │   │
    │ _sent_at = now()                  │ _sent_at = now()                  │   ▼
    │                                   │                                   │ Update: renewal_reminder_expired
    │                                   │                                   │ _sent_at = now()
    └── TIDAK → Skip                    └── TIDAK → Skip                    └── TIDAK → Skip
```

---

## 3. Scheduler & Cron

File: `routes/console.php`

```php
Schedule::command('virtual-office:renewal-reminder')->dailyAt('09:00');
```

**Prasyarat Server Production:**

Pastikan cron entry berikut aktif di server production:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 4. Artisan Command

```bash
php artisan virtual-office:renewal-reminder
```

---

## 5. Mapping Placeholder & Matriks Template

### A. Template H-30 (`virtual_office_renewal_h30_v2`)
- **Template ID**: `1329567535585592`
- **ENV Key**: `BOTCAKE_TEMPLATE_VO_RENEWAL_H30`
- **Kategori**: `UTILITY` | **Bahasa**: `id`

| Placeholder | Parameter | Sumber Data | Contoh |
|:---:|:---|:---|:---|
| `{{1}}` | Nama PT | `user.company_name` / `form_data['company_name']` | PT Sinema Mandiri |
| `{{2}}` | Tanggal Berakhir | `room_benefits.expired_at` (format `d F Y`) | 01 September 2026 |
| `{{3}}` | Nama Paket | `orders.service_name` | Virtual Office – Business Package |
| `{{4}}` | Tanggal Mulai | `room_benefits.created_at` (format `d F Y`) | 01 September 2025 |
| `{{5}}` | Tanggal Berakhir | `room_benefits.expired_at` (format `d F Y`) | 01 September 2026 |

---

### B. Template H-7 (`virtual_office_renewal_h7`)
- **Template ID**: `1025817360352995`
- **ENV Key**: `BOTCAKE_TEMPLATE_VO_RENEWAL_H7`
- **Kategori**: `UTILITY` | **Bahasa**: `id`

| Placeholder | Parameter | Sumber Data | Contoh |
|:---:|:---|:---|:---|
| `{{1}}` | Nama PT | `user.company_name` / `form_data['company_name']` | PT Sinema Mandiri |
| `{{2}}` | Tanggal Berakhir | `room_benefits.expired_at` (format `d F Y`) | 01 September 2026 |
| `{{3}}` | Nama Paket | `orders.service_name` | Virtual Office – Business Package |
| `{{4}}` | Tanggal Mulai | `room_benefits.created_at` (format `d F Y`) | 01 September 2025 |
| `{{5}}` | Tanggal Berakhir | `room_benefits.expired_at` (format `d F Y`) | 01 September 2026 |

---

### C. Template Hari H Expired — Enterprise (`virtual_office_expired_enterprise`)
- **Paket Target**: `Enterprise`
- **Template ID**: `1818055572497687`
- **ENV Key**: `BOTCAKE_TEMPLATE_VO_EXPIRED_ENTERPRISE`
- **Kategori**: `UTILITY` | **Bahasa**: `id`

| Placeholder | Parameter | Sumber Data | Contoh |
|:---:|:---|:---|:---|
| `{{1}}` | Nama PT | `user.company_name` / `form_data['company_name']` | PT Sinema Mandiri |
| `{{2}}` | Tanggal Nonaktif | `room_benefits.expired_at` (format `d F Y`) | 01 September 2026 |
| `{{3}}` | Nama Layanan / Paket | `orders.service_name` / `form_data['package']` | Virtual Office Enterprise |

---

### D. Template Hari H Expired — Premium & Eksklusif (`virtual_office_expired_notification`)
- **Paket Target**: `Premium`, `Eksklusif`, `Regular`, dll.
- **Template ID**: `1757632552089608`
- **ENV Key**: `BOTCAKE_TEMPLATE_VO_EXPIRED`
- **Kategori**: `UTILITY` | **Bahasa**: `id`

| Placeholder | Parameter | Sumber Data | Contoh |
|:---:|:---|:---|:---|
| `{{1}}` | Nama PT | `user.company_name` / `form_data['company_name']` | PT Sinema Mandiri |
| `{{2}}` | Tanggal Berakhir | `room_benefits.expired_at` (format `d F Y`) | 01 September 2026 |
| `{{3}}` | Tanggal Nonaktif | `room_benefits.expired_at` (format `d F Y`) | 01 September 2026 |
| `{{4}}` | Nama Layanan / Paket | `orders.service_name` / `form_data['package']` | Virtual Office Premium / Eksklusif |

---

## 6. Payload Botcake (Contoh Premium / Eksklusif 4 Parameter)

Format payload JSON 4 parameter yang dikirimkan oleh `WhatsAppService::sendTemplateById()` untuk paket Premium / Eksklusif:

```json
{
  "psid": "wa_6281219110199",
  "data": {
    "version": "v2",
    "content": {
      "messages": [
        {
          "type": "whatsapp_message_template",
          "template_id": "1757632552089608",
          "language": "id",
          "category": "UTILITY",
          "components": [
            {
              "type": "BODY",
              "params": [
                { "key": "{{1}}", "parameter_name": "1", "value": "PT Sinema Mandiri" },
                { "key": "{{2}}", "parameter_name": "2", "value": "01 September 2026" },
                { "key": "{{3}}", "parameter_name": "3", "value": "01 September 2026" },
                { "key": "{{4}}", "parameter_name": "4", "value": "Virtual Office Premium" }
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

### Kolom Baru pada Tabel `orders`

| Kolom | Tipe | Default | Keterangan |
|:---|:---|:---:|:---|
| `renewal_reminder_h30_sent_at` | `DATETIME` | `NULL` | Timestamp kapan reminder H-30 terkirim. |
| `renewal_reminder_h7_sent_at` | `DATETIME` | `NULL` | Timestamp kapan reminder H-7 terkirim. |
| `renewal_reminder_expired_sent_at` | `DATETIME` | `NULL` | Timestamp kapan reminder Hari H Expired terkirim. |

### Tabel `whatsapp_logs`

Setiap pengiriman reminder (H-30, H-7, & Expired) dicatat di tabel `whatsapp_logs`:

| Field | Isi |
|:---|:---|
| `client_id` | `user_id` dari order VO |
| `order_id` | `id` dari order VO |
| `phone_number` | Nomor WhatsApp client |
| `message` | Template ID, parameter, dan sisa karakter |
| `status` | `SUCCESS` / `FAILED` |
| `response` | Response dari Botcake API |

---

## 8. Cara Testing

### A. Testing Manual Paket Premium & Eksklusif

```bash
# 1. Jalankan migration
php artisan migrate

# 2. Test run Artisan Command
php artisan virtual-office:renewal-reminder

# 3. Cek hasil
#    - Periksa output console
#    - Cek tabel orders: kolom renewal_reminder_expired_sent_at terisi jika match
#    - Cek tabel whatsapp_logs: entry log baru tersimpan dengan Template ID 1757632552089608 dan 4 parameter (termasuk {{4}} Nama Paket)
```

### B. Testing Scheduler

```bash
# Simulasikan scheduler run
php artisan schedule:run

# Cek daftar jadwal scheduler
php artisan schedule:list
```

### C. Testing Idempotency (Tidak Dikirim Dua Kali)

```bash
# Jalankan command dua kali berturut-turut
php artisan virtual-office:renewal-reminder
php artisan virtual-office:renewal-reminder

# Pada run kedua, VO yang sudah terkirim H-30 / H-7 / Expired akan di-skip otomatis.
```

---

## 9. Checklist Go Live

- [x] Migration H-30, H-7, & Expired sudah dijalankan (`php artisan migrate`)
- [x] Template H-30, H-7, Expired, & Expired Enterprise dikonfigurasi di `.env`:
  - `BOTCAKE_TEMPLATE_VO_RENEWAL_H30=1329567535585592`
  - `BOTCAKE_TEMPLATE_VO_RENEWAL_H7=1025817360352995`
  - `BOTCAKE_TEMPLATE_VO_EXPIRED=1757632552089608`
  - `BOTCAKE_TEMPLATE_VO_EXPIRED_ENTERPRISE=1818055572497687`
- [x] Config cache di-clear (`php artisan config:clear`)
- [ ] Cron entry aktif di server: `* * * * * php artisan schedule:run`
- [x] Testing manual berhasil: `php artisan virtual-office:renewal-reminder`
- [x] Log tercatat lengkap di `whatsapp_logs` dengan Template ID & parameter yang tepat (4 params untuk Premium/Eksklusif, 3 params untuk Enterprise)

---

## 10. Yang TIDAK Diubah

- ❌ Reminder H-30 & H-7 yang sudah ada
- ❌ Template Enterprise
- ❌ Flow Virtual Office (CRUD, approval, payment)
- ❌ Meeting Room / Podcast Room
- ❌ Surat Masuk / Guest Notification
- ❌ `sendTemplateById()` method
- ❌ Business logic lama
