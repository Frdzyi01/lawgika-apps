-- ============================================================================
-- Incremental Upgrade SQL dari database/db/lawgikac_db-2.sql
-- Aman dijalankan langsung pada database live (tanpa DROP / hapus data lama).
-- Mempertahankan seluruh data client aktif, order, dokumen, & room benefits.
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- 1. Tambah kolom workflow approval, invoice, tanggal transaksi, revisi, dan soft delete pada `orders`
ALTER TABLE `orders`
  ADD COLUMN `invoice_number` varchar(100) NULL AFTER `order_number`,
  ADD COLUMN `transaction_date` date NULL AFTER `invoice_number`,
  ADD COLUMN `payment_method` varchar(100) NULL AFTER `payment_proof`,
  ADD COLUMN `submitted_at` timestamp NULL DEFAULT NULL AFTER `created_by`,
  ADD COLUMN `approved_by` bigint(20) unsigned NULL AFTER `submitted_at`,
  ADD COLUMN `approved_at` timestamp NULL DEFAULT NULL AFTER `approved_by`,
  ADD COLUMN `rejected_by` bigint(20) unsigned NULL AFTER `approved_at`,
  ADD COLUMN `rejected_at` timestamp NULL DEFAULT NULL AFTER `rejected_by`,
  ADD COLUMN `rejection_reason` text NULL AFTER `rejected_at`,
  ADD COLUMN `revision_count` int(10) unsigned NOT NULL DEFAULT 0 AFTER `rejection_reason`,
  ADD COLUMN `last_revised_at` timestamp NULL DEFAULT NULL AFTER `revision_count`,
  ADD COLUMN `deleted_by` bigint(20) unsigned NULL AFTER `last_revised_at`,
  ADD COLUMN `delete_reason` text NULL AFTER `deleted_by`,
  ADD COLUMN `deleted_at` timestamp NULL DEFAULT NULL AFTER `updated_at`,
  ADD UNIQUE KEY `orders_invoice_number_unique` (`invoice_number`),
  ADD KEY `orders_transaction_date_index` (`transaction_date`),
  ADD KEY `orders_approved_by_foreign` (`approved_by`),
  ADD KEY `orders_rejected_by_foreign` (`rejected_by`),
  ADD KEY `orders_deleted_by_foreign` (`deleted_by`),
  ADD CONSTRAINT `orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- 2. Tambah kolom tanggal mulai & metadata approval pada `room_benefits`
ALTER TABLE `room_benefits`
  ADD COLUMN `started_at` timestamp NULL DEFAULT NULL AFTER `is_active`,
  ADD COLUMN `approved_by` bigint(20) unsigned NULL AFTER `expired_at`,
  ADD COLUMN `approved_at` timestamp NULL DEFAULT NULL AFTER `approved_by`,
  ADD KEY `room_benefits_approved_by_foreign` (`approved_by`),
  ADD CONSTRAINT `room_benefits_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- 3. Tabel histori status pesanan (`order_histories`)
CREATE TABLE IF NOT EXISTS `order_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `action` varchar(50) NOT NULL,
  `from_status` varchar(50) DEFAULT NULL,
  `to_status` varchar(50) DEFAULT NULL,
  `actor_id` bigint(20) unsigned DEFAULT NULL,
  `actor_role` varchar(30) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `snapshot` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_histories_action_index` (`action`),
  KEY `order_histories_order_id_created_at_index` (`order_id`,`created_at`),
  KEY `order_histories_actor_id_foreign` (`actor_id`),
  CONSTRAINT `order_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_histories_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabel audit trail transaksi & dokumen (`order_audit_logs`)
CREATE TABLE IF NOT EXISTS `order_audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_role` varchar(30) DEFAULT NULL,
  `action` varchar(60) NOT NULL,
  `reason` text DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_audit_logs_action_index` (`action`),
  KEY `order_audit_logs_order_id_created_at_index` (`order_id`,`created_at`),
  KEY `order_audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `order_audit_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabel pengajuan edit/hapus oleh Admin 1 (`order_change_requests`)
CREATE TABLE IF NOT EXISTS `order_change_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `request_type` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `reason` text NOT NULL,
  `proposed_data` json DEFAULT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_change_requests_request_type_index` (`request_type`),
  KEY `order_change_requests_status_index` (`status`),
  KEY `order_change_requests_order_id_status_index` (`order_id`,`status`),
  KEY `order_change_requests_requested_by_foreign` (`requested_by`),
  KEY `order_change_requests_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `order_change_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_change_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_change_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Backfill data orders & room_benefits lama secara aman (tanpa mengubah expired_at/quota yang sudah berjalan)
UPDATE `orders`
SET
  `invoice_number` = COALESCE(`invoice_number`, CONCAT('INV-', `order_number`)),
  `transaction_date` = COALESCE(`transaction_date`, DATE(`created_at`)),
  `submitted_at` = COALESCE(`submitted_at`, `created_at`);

UPDATE `orders`
SET `approved_at` = COALESCE(`approved_at`, `updated_at`, `created_at`)
WHERE `status` IN ('approved', 'processing', 'completed', 'verified');

UPDATE `room_benefits` rb
LEFT JOIN `orders` o ON o.`id` = rb.`order_id`
SET
  rb.`started_at` = COALESCE(
    rb.`started_at`,
    CASE
      WHEN o.`transaction_date` IS NOT NULL THEN TIMESTAMP(o.`transaction_date`, '00:00:00')
      ELSE rb.`created_at`
    END
  ),
  rb.`approved_at` = COALESCE(rb.`approved_at`, o.`approved_at`, rb.`created_at`);

-- 7. Tambahkan akun Admin 3 jika belum ada (default password: 'password' - segera ubah di produksi)
INSERT INTO `users` (`name`, `email`, `role`, `phone`, `password`, `created_at`, `updated_at`)
SELECT
  'Admin Verifikasi 3',
  'admin3@example.com',
  'admin3',
  '08444444444',
  '$2y$12$aRLLZjuRlT4Sr8OBbkwwDuYI19zlZuLF4NCvb9cqO6mN0WV/y1dD.',
  NOW(),
  NOW()
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `users` WHERE `email` = 'admin3@example.com'
);

-- 8. Catat migration ke tabel `migrations` agar `php artisan migrate` tetap sinkron
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_11_13_000001_add_transaction_approval_audit_workflow', COALESCE((SELECT MAX(m.`batch`) + 1 FROM `migrations` m), 1)
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations` WHERE `migration` = '2026_11_13_000001_add_transaction_approval_audit_workflow'
);

COMMIT;
