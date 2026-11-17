-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for osx10.10 (x86_64)
--
-- Host: 127.0.0.1    Database: lawgikac_db_migrated
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `beritas`
--

DROP TABLE IF EXISTS `beritas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `beritas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `konten` text DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `jabatan_penulis` varchar(255) NOT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `beritas_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beritas`
--

LOCK TABLES `beritas` WRITE;
/*!40000 ALTER TABLE `beritas` DISABLE KEYS */;
/*!40000 ALTER TABLE `beritas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('budi@example.com|124.153.9.30','i:2;',1794562302),('budi@example.com|124.153.9.30:timer','i:1794562302;',1794562302);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `correspondences`
--

DROP TABLE IF EXISTS `correspondences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `correspondences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `sender_role` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `correspondences_user_id_foreign` (`user_id`),
  KEY `correspondences_parent_id_foreign` (`parent_id`),
  CONSTRAINT `correspondences_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `correspondences` (`id`) ON DELETE SET NULL,
  CONSTRAINT `correspondences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `correspondences`
--

LOCK TABLES `correspondences` WRITE;
/*!40000 ALTER TABLE `correspondences` DISABLE KEYS */;
/*!40000 ALTER TABLE `correspondences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_requirements`
--

DROP TABLE IF EXISTS `document_requirements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document_requirements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` bigint(20) unsigned NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `min_required` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `max_allowed` tinyint(3) unsigned NOT NULL DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_requirements_service_id_document_type_unique` (`service_id`,`document_type`),
  CONSTRAINT `document_requirements_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_requirements`
--

LOCK TABLES `document_requirements` WRITE;
/*!40000 ALTER TABLE `document_requirements` DISABLE KEYS */;
INSERT INTO `document_requirements` VALUES (1,1,'ktp','KTP Pemilik',1,10,'2026-09-02 13:25:05','2026-09-02 13:25:05'),(2,1,'npwp','NPWP Pribadi/Perusahaan',1,10,'2026-09-02 13:25:05','2026-09-02 13:25:05'),(3,1,'akta_pendirian','Akta Pendirian (Opsional)',0,10,'2026-09-02 13:25:05','2026-09-02 13:25:05'),(4,1,'other','Dokumen Pendukung Lainnya',0,10,'2026-09-02 13:25:05','2026-09-02 13:25:05'),(5,2,'KTP_DIREKTUR','KTP Direktur',1,3,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(6,2,'NPWP_DIREKTUR','NPWP Direktur',1,3,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(7,3,'KTP_PEMEGANG_SAHAM','KTP Pemegang Saham',2,10,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(8,3,'NPWP_PEMEGANG_SAHAM','NPWP Pemegang Saham',2,10,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(9,3,'KTP_DIREKTUR','KTP Direktur',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(10,3,'NPWP_DIREKTUR','NPWP Direktur',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(11,3,'KTP_KOMISARIS','KTP Komisaris',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(12,3,'NPWP_KOMISARIS','NPWP Komisaris',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(13,4,'KTP_PEMEGANG_SAHAM','KTP Pemegang Saham',2,10,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(14,4,'NPWP_PEMEGANG_SAHAM','NPWP Pemegang Saham',2,10,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(15,4,'KTP_DIREKTUR','KTP Direktur',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:26'),(16,4,'NPWP_DIREKTUR','NPWP Direktur',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(17,4,'KTP_KOMISARIS','KTP Komisaris',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:26'),(18,4,'NPWP_KOMISARIS','NPWP Komisaris',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(19,5,'KTP_SEKUTU_AKTIF','KTP Sekutu Aktif',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(20,5,'NPWP_SEKUTU_AKTIF','NPWP Sekutu Aktif',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(21,5,'KTP_SEKUTU_PASIF','KTP Sekutu Pasif',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(22,5,'NPWP_SEKUTU_PASIF','NPWP Sekutu Pasif',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(23,6,'KTP_PEMBINA','KTP Pembina',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(24,6,'NPWP_PEMBINA','NPWP Pembina',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(25,6,'KTP_PENGURUS','KTP Pengurus',1,10,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(26,6,'NPWP_PENGURUS','NPWP Pengurus',1,10,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(27,6,'KTP_PENGAWAS','KTP Pengawas',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(28,6,'NPWP_PENGAWAS','NPWP Pengawas',1,5,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(29,7,'KTP_SEKUTU','KTP Sekutu',2,10,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(30,7,'NPWP_SEKUTU','NPWP Sekutu',2,10,'2026-09-05 10:06:25','2026-09-05 10:06:25');
/*!40000 ALTER TABLE `document_requirements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `document_type` varchar(255) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_user_id_foreign` (`user_id`),
  KEY `documents_order_id_foreign` (`order_id`),
  KEY `documents_service_id_foreign` (`service_id`),
  CONSTRAINT `documents_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (1,4,1,'akta_pendirian','1788602944_usvbke7q-400x400.jpg','usvBkE7Q_400x400.jpg','documents/1/1788602944_usvbke7q-400x400.jpg','akta_pendirian','approved',NULL,1,'2026-09-05 10:09:04','2026-09-05 10:09:04','2026-09-05 10:09:04',1),(2,4,1,'ktp','1788602944_labelfragile.jpg','LabelFragile.jpg','documents/1/1788602944_labelfragile.jpg','ktp','approved',NULL,1,'2026-09-05 10:09:04','2026-09-05 10:09:04','2026-09-05 10:09:04',1),(3,4,1,'npwp','1788602944_logo-bireuen.jpg','Logo_Bireuen.jpg','documents/1/1788602944_logo-bireuen.jpg','npwp','rejected','salah',NULL,NULL,'2026-09-05 10:09:04','2026-11-13 09:58:01',1),(4,4,1,'other','1788602944_labelfragile.jpg','LabelFragile.jpg','documents/1/1788602944_labelfragile.jpg','other','approved',NULL,1,'2026-09-05 10:09:04','2026-09-05 10:09:04','2026-09-05 10:09:04',1),(5,9,2,'akta_pendirian','1789445073_cv-tiga-bintang-agro-raya-1.pdf','CV TIGA BINTANG AGRO RAYA (1).pdf','documents/2/1789445073_cv-tiga-bintang-agro-raya-1.pdf','akta_pendirian','approved',NULL,2,'2026-09-15 04:04:33','2026-09-15 04:04:33','2026-09-15 04:04:33',1),(6,9,2,'ktp','1789445073_ktp-npwp-david-julianto.pdf','KTP NPWP David Julianto.pdf','documents/2/1789445073_ktp-npwp-david-julianto.pdf','ktp','approved',NULL,2,'2026-09-15 04:04:33','2026-09-15 04:04:33','2026-09-15 04:04:33',1),(7,9,2,'npwp','1789445073_npwp-cv-tiga-bintang-agro-raya.pdf','NPWP CV TIGA BINTANG AGRO RAYA.pdf','documents/2/1789445073_npwp-cv-tiga-bintang-agro-raya.pdf','npwp','approved',NULL,2,'2026-09-15 04:04:34','2026-09-15 04:04:34','2026-09-15 04:04:34',1),(8,9,2,'other','1789445074_nib-cv-tiga-bintang-agro-raya.pdf','NIB CV TIGA BINTANG AGRO RAYA.pdf','documents/2/1789445074_nib-cv-tiga-bintang-agro-raya.pdf','other','approved',NULL,2,'2026-09-15 04:04:34','2026-09-15 04:04:34','2026-09-15 04:04:34',1),(9,9,2,'other','1789445074_surat-pernyataan-kegiatan-mikro.pdf','SURAT PERNYATAAN KEGIATAN MIKRO.pdf','documents/2/1789445074_surat-pernyataan-kegiatan-mikro.pdf','other','approved',NULL,2,'2026-09-15 04:04:34','2026-09-15 04:04:34','2026-09-15 04:04:34',1),(10,9,2,'other','1789445074_surat-keterangan-terdaftar-ahu-0054858-ah0114-tahun-2026.pdf','Surat Keterangan Terdaftar AHU-0054858-AH.01.14 Tahun 2026.pdf','documents/2/1789445074_surat-keterangan-terdaftar-ahu-0054858-ah0114-tahun-2026.pdf','other','approved',NULL,2,'2026-09-15 04:04:34','2026-09-15 04:04:34','2026-09-15 04:04:34',1),(11,10,3,'akta_pendirian','1789458670_2026-08-03-akta-pendirian-no-1-pt-digital-indonesia-nusantara.pdf','2026-08-03_AKTA PENDIRIAN NO. 1 PT DIGITAL INDONESIA NUSANTARA.pdf','documents/3/1789458670_2026-08-03-akta-pendirian-no-1-pt-digital-indonesia-nusantara.pdf','akta_pendirian','approved',NULL,2,'2026-09-15 07:51:11','2026-09-15 07:51:11','2026-09-15 07:51:11',1),(12,10,3,'ktp','1789458671_ktp-estika-suraida.jpg','KTP Estika Suraida.jpg','documents/3/1789458671_ktp-estika-suraida.jpg','ktp','approved',NULL,2,'2026-09-15 07:51:11','2026-09-15 07:51:11','2026-09-15 07:51:11',1),(13,10,3,'ktp','1789458671_ktp-hesron.jpeg','KTP Hesron.jpeg','documents/3/1789458671_ktp-hesron.jpeg','ktp','approved',NULL,2,'2026-09-15 07:51:11','2026-09-15 07:51:11','2026-09-15 07:51:11',1),(14,10,3,'ktp','1789458671_npwp-estika-suraida.jpeg','NPWP Estika Suraida.jpeg','documents/3/1789458671_npwp-estika-suraida.jpeg','ktp','approved',NULL,2,'2026-09-15 07:51:11','2026-09-15 07:51:11','2026-09-15 07:51:11',1),(15,10,3,'ktp','1789458671_npwp-hesron-sandie.jpeg','NPWP Hesron Sandie.jpeg','documents/3/1789458671_npwp-hesron-sandie.jpeg','ktp','approved',NULL,2,'2026-09-15 07:51:11','2026-09-15 07:51:11','2026-09-15 07:51:11',1),(16,10,3,'npwp','1789458671_npwp-pt-digital-indonesia-nusantara.pdf','NPWP PT DIGITAL INDONESIA NUSANTARA.pdf','documents/3/1789458671_npwp-pt-digital-indonesia-nusantara.pdf','npwp','approved',NULL,2,'2026-09-15 07:51:11','2026-09-15 07:51:11','2026-09-15 07:51:11',1),(17,10,3,'other','1789458671_2026-08-04-sk-pendirian-pt-digital-indonesia-nusantara.pdf','2026-08-04_SK PENDIRIAN PT DIGITAL INDONESIA NUSANTARA.pdf','documents/3/1789458671_2026-08-04-sk-pendirian-pt-digital-indonesia-nusantara.pdf','other','approved',NULL,2,'2026-09-15 07:51:11','2026-09-15 07:51:11','2026-09-15 07:51:11',1),(18,10,3,'other','1789458671_nib-pt-digital-indonesia-nusantara.pdf','NIB PT DIGITAL INDONESIA NUSANTARA.pdf','documents/3/1789458671_nib-pt-digital-indonesia-nusantara.pdf','other','approved',NULL,2,'2026-09-15 07:51:11','2026-09-15 07:51:11','2026-09-15 07:51:11',1),(19,11,4,'akta_pendirian','1790071053_akta-pendirian-pt-ranking-digital-indonesia.pdf','Akta Pendirian PT Ranking Digital Indonesia.pdf','documents/4/1790071053_akta-pendirian-pt-ranking-digital-indonesia.pdf','akta_pendirian','approved',NULL,2,'2026-09-22 09:57:33','2026-09-22 09:57:33','2026-09-22 09:57:33',1),(20,11,4,'ktp','1790071053_ktp-pak-tommy-direktur.jpeg','KTP Pak Tommy (Direktur).jpeg','documents/4/1790071053_ktp-pak-tommy-direktur.jpeg','ktp','approved',NULL,2,'2026-09-22 09:57:33','2026-09-22 09:57:33','2026-09-22 09:57:33',1),(21,11,4,'ktp','1790071053_npwp-pak-tommy-direktur.jpeg','NPWP Pak Tommy (Direktur).jpeg','documents/4/1790071053_npwp-pak-tommy-direktur.jpeg','ktp','approved',NULL,2,'2026-09-22 09:57:33','2026-09-22 09:57:33','2026-09-22 09:57:33',1),(22,11,4,'npwp','1790071053_npwp-pt-ranking-digital-indonesia.pdf','NPWP PT Ranking Digital Indonesia.pdf','documents/4/1790071053_npwp-pt-ranking-digital-indonesia.pdf','npwp','approved',NULL,2,'2026-09-22 09:57:33','2026-09-22 09:57:33','2026-09-22 09:57:33',1),(23,11,4,'other','1790071053_nib-pt-ranking-digital-indonesia.pdf','NIB PT Ranking Digital Indonesia.pdf','documents/4/1790071053_nib-pt-ranking-digital-indonesia.pdf','other','approved',NULL,2,'2026-09-22 09:57:33','2026-09-22 09:57:33','2026-09-22 09:57:33',1),(24,11,4,'other','1790071053_sk-menhukam-pt-ranking-digital-indonesia.pdf','SK Menhukam PT Ranking Digital Indonesia.pdf','documents/4/1790071053_sk-menhukam-pt-ranking-digital-indonesia.pdf','other','approved',NULL,2,'2026-09-22 09:57:33','2026-09-22 09:57:33','2026-09-22 09:57:33',1),(25,18,5,'ktp','1793605380_ktp-direktur-pt-anugrah-jaya-prasetya.jpg','KTP DIREKTUR PT Anugrah Jaya Prasetya.jpg','documents/5/1793605380_ktp-direktur-pt-anugrah-jaya-prasetya.jpg','ktp','approved',NULL,2,'2026-11-02 07:43:00','2026-11-02 07:43:00','2026-11-02 07:43:00',1),(26,19,6,'akta_pendirian','1794483191_2026-10-26-akta-pendirian-no-370-pt-arus-teknologi-nusantara.pdf','2026-10-26_AKTA PENDIRIAN NO. 370 PT ARUS TEKNOLOGI NUSANTARA.pdf','documents/6/1794483191_2026-10-26-akta-pendirian-no-370-pt-arus-teknologi-nusantara.pdf','akta_pendirian','approved',NULL,2,'2026-11-12 11:33:11','2026-11-12 11:33:11','2026-11-12 11:33:11',1),(27,19,6,'ktp','1794483191_revisi-ktp-dan-npwp-pengurus-dan-pemegang-saham-pt-arus-teknologi-nusantara.pdf','REVISI_KTP DAN NPWP PENGURUS DAN PEMEGANG SAHAM PT ARUS TEKNOLOGI NUSANTARA.pdf','documents/6/1794483191_revisi-ktp-dan-npwp-pengurus-dan-pemegang-saham-pt-arus-teknologi-nusantara.pdf','ktp','approved',NULL,2,'2026-11-12 11:33:11','2026-11-12 11:33:11','2026-11-12 11:33:11',1),(28,19,6,'npwp','1794483191_npwp-pt-arus-teknologi-nusantara.pdf','NPWP_PT ARUS TEKNOLOGI NUSANTARA.pdf','documents/6/1794483191_npwp-pt-arus-teknologi-nusantara.pdf','npwp','approved',NULL,2,'2026-11-12 11:33:11','2026-11-12 11:33:11','2026-11-12 11:33:11',1),(29,19,6,'other','1794483191_45-sk-pendirian-pt-anugrah-jaya-prasetya.pdf','45_SK PENDIRIAN PT ANUGRAH JAYA PRASETYA.pdf','documents/6/1794483191_45-sk-pendirian-pt-anugrah-jaya-prasetya.pdf','other','approved',NULL,2,'2026-11-12 11:33:11','2026-11-12 11:33:11','2026-11-12 11:33:11',1);
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_up_comings`
--

DROP TABLE IF EXISTS `event_up_comings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_up_comings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_event` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `lokasi` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `waktu_event` varchar(255) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `narasumber` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`narasumber`)),
  `harga` int(11) NOT NULL DEFAULT 0,
  `tipe_event` enum('gratis','berbayar') NOT NULL DEFAULT 'gratis',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_up_comings`
--

LOCK TABLES `event_up_comings` WRITE;
/*!40000 ALTER TABLE `event_up_comings` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_up_comings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `karirs`
--

DROP TABLE IF EXISTS `karirs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `karirs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karirs`
--

LOCK TABLES `karirs` WRITE;
/*!40000 ALTER TABLE `karirs` DISABLE KEYS */;
/*!40000 ALTER TABLE `karirs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_room_bookings`
--

DROP TABLE IF EXISTS `meeting_room_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meeting_room_bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `source_type` varchar(255) NOT NULL DEFAULT 'manual',
  `benefit_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `total_used_minutes` int(11) NOT NULL DEFAULT 0,
  `total_used_seconds` int(11) NOT NULL DEFAULT 0,
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `participants` int(11) NOT NULL,
  `status` enum('pending','pending_approval','approved','rejected','checkin','paused','selesai') NOT NULL DEFAULT 'pending',
  `checkin_at` datetime DEFAULT NULL,
  `checkout_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nama_perusahaan` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat_usaha` text DEFAULT NULL,
  `bidang_usaha` varchar(255) DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meeting_room_bookings_user_id_foreign` (`user_id`),
  KEY `meeting_room_bookings_benefit_id_foreign` (`benefit_id`),
  KEY `meeting_room_bookings_created_by_foreign` (`created_by`),
  CONSTRAINT `meeting_room_bookings_benefit_id_foreign` FOREIGN KEY (`benefit_id`) REFERENCES `room_benefits` (`id`) ON DELETE SET NULL,
  CONSTRAINT `meeting_room_bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `meeting_room_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_room_bookings`
--

LOCK TABLES `meeting_room_bookings` WRITE;
/*!40000 ALTER TABLE `meeting_room_bookings` DISABLE KEYS */;
INSERT INTO `meeting_room_bookings` VALUES (1,'benefit',1,4,'PT Inovasi Software Solution',NULL,NULL,NULL,48,0,0,NULL,'approved',NULL,1,'approved',NULL,NULL,'2026-09-05 10:09:04','2026-09-05 10:09:04','PT Inovasi Software Solution','riyan@example.com',NULL,NULL,NULL,NULL,'Benefit otomatis dari Order ORD-VIR-20260905-XUGHQ',NULL),(2,'benefit',3,9,'CV Tiga Bintang Agro Raya',NULL,NULL,NULL,12,0,0,NULL,'approved',NULL,1,'approved',NULL,NULL,'2026-09-15 04:04:34','2026-09-15 04:04:34','CV Tiga Bintang Agro Raya','simbolondavid7@gmail.com',NULL,NULL,NULL,NULL,'Benefit otomatis dari Order ORD-VIR-20260915-RGLPT',NULL),(3,'benefit',4,10,'PT Digital Inovasi Nusantara',NULL,NULL,NULL,48,0,0,NULL,'approved',NULL,1,'approved',NULL,NULL,'2026-09-15 07:51:11','2026-09-15 07:51:11','PT Digital Inovasi Nusantara','essuraida@gmail.com',NULL,NULL,NULL,NULL,'Benefit otomatis dari Order ORD-VIR-20260915-5VXVY',NULL),(4,'benefit',6,11,'PT Ranking Digital Indonesia',NULL,NULL,'12:00:00',12,0,14400,NULL,'approved',NULL,1,'approved',NULL,'2026-09-24 13:32:26','2026-09-22 09:57:33','2026-09-24 06:32:26','PT Ranking Digital Indonesia','tommyliusudarso.ll@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL),(5,'manual',7,17,'riyan',NULL,NULL,NULL,60,0,3600,NULL,'approved','Payment WA',1,'approved',NULL,'2026-09-28 18:36:01','2026-09-28 11:34:34','2026-09-28 11:36:01','riyan','riyanfirdzi@gmail.com',NULL,'it',NULL,NULL,NULL,1),(6,'benefit',8,18,'PT Anugrah Jaya Prasetya',NULL,NULL,NULL,48,0,0,NULL,'approved',NULL,1,'approved',NULL,NULL,'2026-11-02 07:43:00','2026-11-02 07:43:00','PT Anugrah Jaya Prasetya','jakaprasetya14@gmail.com',NULL,NULL,NULL,NULL,'Benefit otomatis dari Order ORD-VIR-20261102-9JJYN',NULL),(7,'benefit',10,19,'PT ARUS TEKNOLOGI NUSANTARA',NULL,NULL,NULL,48,0,0,NULL,'approved',NULL,1,'approved',NULL,NULL,'2026-11-12 11:33:11','2026-11-12 11:33:11','PT ARUS TEKNOLOGI NUSANTARA','petoi.tomy@gmail.com',NULL,NULL,NULL,NULL,'Benefit otomatis dari Order ORD-VIR-20261112-FWDD6',NULL);
/*!40000 ALTER TABLE `meeting_room_bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000000_add_role_and_create_app_tables',1),(5,'2024_01_02_000000_update_orders_add_service_name',1),(6,'2026_04_07_224438_create_promos_table',1),(7,'2026_04_07_224453_create_peraturan_k_b_l_i_s_table',1),(8,'2026_04_07_224524_create_event_up_comings_table',1),(9,'2026_04_13_095007_create_beritas_table',1),(10,'2026_04_15_082400_add_new_fields_to_event_up_comings_table',1),(11,'2026_04_20_000001_add_payment_fields_to_orders_table',1),(12,'2026_04_22_000001_expand_documents_type_enum',1),(13,'2026_04_22_100539_create_meeting_room_bookings_table',1),(14,'2026_04_22_101502_add_time_tracking_to_meeting_room_bookings_table',1),(15,'2026_04_22_102328_add_total_used_minutes_to_meeting_room_bookings_table',1),(16,'2026_04_22_102719_update_status_enum_in_meeting_room_bookings',1),(17,'2026_04_22_111343_add_payment_fields_to_meeting_room_bookings_table',1),(18,'2026_04_22_115448_create_spt_requests_table',1),(19,'2026_04_22_123042_create_karirs_table',1),(20,'2026_04_22_151040_create_spt_badans_table',1),(21,'2026_04_24_000001_create_podcast_room_bookings_table',1),(22,'2026_04_26_204401_add_total_used_seconds_to_meeting_room_bookings_table',1),(23,'2026_04_26_205158_create_room_usage_logs_table',1),(24,'2026_04_26_205448_add_total_used_seconds_to_podcast_room_bookings_table',1),(25,'2026_04_26_211436_create_quota_logs_table',1),(26,'2026_04_26_211436_create_user_room_quotas_table',1),(27,'2026_04_26_212511_change_documents_table_columns_to_string',1),(28,'2026_04_26_213352_add_source_type_to_room_bookings',1),(29,'2026_04_27_000001_add_subject_fields_to_spt_badans_table',1),(30,'2026_04_30_000001_create_room_benefits_table',1),(31,'2026_04_30_000002_create_room_benefit_logs_table',1),(32,'2026_04_30_000003_add_benefit_id_to_booking_tables',1),(33,'2026_04_30_000004_update_status_enum_for_benefit_flow',1),(34,'2026_04_30_000005_add_timestamps_to_room_benefit_logs',1),(35,'2026_04_30_100001_create_document_requirements_table',1),(36,'2026_04_30_100002_add_document_approval_fields_to_documents',1),(37,'2026_04_30_100003_convert_orders_status_to_varchar',1),(38,'2026_04_30_200001_create_correspondences_table',1),(39,'2026_05_03_121307_make_date_and_start_time_nullable_in_meeting_room_bookings_table',1),(40,'2026_05_03_211357_add_customer_details_to_meeting_room_bookings_table',1),(41,'2026_05_06_124600_seed_virtual_office_service',1),(42,'2026_05_07_224054_drop_unique_order_id_from_room_benefits_table',1),(43,'2026_06_22_000001_extend_users_role_to_support_admin_roles',1),(44,'2026_07_13_000001_add_master_client_fields_to_users_table',1),(45,'2026_07_13_000002_add_admin_booking_fields_to_meeting_room_bookings',1),(46,'2026_07_13_000003_add_admin_booking_fields_to_podcast_room_bookings',1),(47,'2026_07_13_000004_add_admin_order_fields_to_orders_table',1),(48,'2026_07_14_073952_add_payment_method_to_room_bookings_table',1),(49,'2026_07_14_144036_make_date_and_start_time_nullable_in_podcast_room_bookings_table',1),(50,'2026_07_21_104435_create_whatsapp_logs_table',1),(51,'2026_08_01_150000_create_virtual_office_mail_notifications_table',1),(52,'2026_08_01_151000_create_virtual_office_guest_notifications_table',1),(53,'2026_08_03_140000_add_renewal_reminder_h30_to_orders_table',1),(54,'2026_08_03_150000_add_renewal_reminder_h7_to_orders_table',1),(55,'2026_08_03_160000_add_renewal_reminder_expired_to_orders_table',1),(56,'2026_08_03_170000_add_renewal_reminder_h30_to_room_benefits_table',1),(57,'2026_08_03_180000_add_renewal_reminder_h7_to_room_benefits_table',1),(58,'2026_08_03_190000_add_renewal_reminder_expired_to_room_benefits_table',1),(59,'2026_08_04_140000_add_podcast_renewal_reminder_h30_to_room_benefits_table',1),(60,'2026_08_04_150000_add_podcast_renewal_reminder_h7_to_room_benefits_table',1),(61,'2026_08_04_160000_add_podcast_renewal_reminder_expired_to_room_benefits_table',1),(62,'2026_08_04_200000_add_payment_rejection_reason_to_orders_table',1),(63,'2026_08_04_200640_make_order_id_nullable_in_room_benefits_table',1),(64,'2026_08_31_204000_add_notes_to_room_usage_logs_table',1),(65,'2026_09_05_163000_add_qr_token_to_orders_table',2),(66,'2026_11_13_000001_add_transaction_approval_audit_workflow',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_audit_logs`
--

DROP TABLE IF EXISTS `order_audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_role` varchar(30) DEFAULT NULL,
  `action` varchar(60) NOT NULL,
  `reason` text DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_audit_logs_user_id_foreign` (`user_id`),
  KEY `order_audit_logs_order_id_created_at_index` (`order_id`,`created_at`),
  KEY `order_audit_logs_action_index` (`action`),
  CONSTRAINT `order_audit_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_audit_logs`
--

LOCK TABLES `order_audit_logs` WRITE;
/*!40000 ALTER TABLE `order_audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_change_requests`
--

DROP TABLE IF EXISTS `order_change_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_change_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `request_type` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `reason` text NOT NULL,
  `proposed_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`proposed_data`)),
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_change_requests_requested_by_foreign` (`requested_by`),
  KEY `order_change_requests_reviewed_by_foreign` (`reviewed_by`),
  KEY `order_change_requests_order_id_status_index` (`order_id`,`status`),
  KEY `order_change_requests_request_type_index` (`request_type`),
  KEY `order_change_requests_status_index` (`status`),
  CONSTRAINT `order_change_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_change_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_change_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_change_requests`
--

LOCK TABLES `order_change_requests` WRITE;
/*!40000 ALTER TABLE `order_change_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_change_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_histories`
--

DROP TABLE IF EXISTS `order_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `action` varchar(50) NOT NULL,
  `from_status` varchar(50) DEFAULT NULL,
  `to_status` varchar(50) DEFAULT NULL,
  `actor_id` bigint(20) unsigned DEFAULT NULL,
  `actor_role` varchar(30) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`snapshot`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_histories_actor_id_foreign` (`actor_id`),
  KEY `order_histories_order_id_created_at_index` (`order_id`,`created_at`),
  KEY `order_histories_action_index` (`action`),
  CONSTRAINT `order_histories_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_histories`
--

LOCK TABLES `order_histories` WRITE;
/*!40000 ALTER TABLE `order_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `total_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `payment_status` enum('unpaid','pending_verification','verified','rejected') NOT NULL DEFAULT 'unpaid',
  `payment_rejection_reason` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint(20) unsigned DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `revision_count` int(10) unsigned NOT NULL DEFAULT 0,
  `last_revised_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `delete_reason` text DEFAULT NULL,
  `form_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`form_data`)),
  `qr_token` varchar(64) DEFAULT NULL,
  `renewal_reminder_h30_sent_at` datetime DEFAULT NULL,
  `renewal_reminder_h7_sent_at` datetime DEFAULT NULL,
  `renewal_reminder_expired_sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  UNIQUE KEY `orders_qr_token_unique` (`qr_token`),
  UNIQUE KEY `orders_invoice_number_unique` (`invoice_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_service_id_foreign` (`service_id`),
  KEY `orders_created_by_foreign` (`created_by`),
  KEY `orders_approved_by_foreign` (`approved_by`),
  KEY `orders_rejected_by_foreign` (`rejected_by`),
  KEY `orders_deleted_by_foreign` (`deleted_by`),
  KEY `orders_transaction_date_index` (`transaction_date`),
  CONSTRAINT `orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-VIR-20260905-XUGHQ','INV-ORD-VIR-20260905-XUGHQ','2026-09-05',4,1,'Virtual Office – Business Package','revision',5800000.00,NULL,NULL,NULL,NULL,'verified',NULL,1,'2026-09-05 10:09:04',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'{\"service\":\"virtual-office\",\"package\":\"enterprise\",\"director_name\":\"Riyan F\",\"director_phone\":\"081219110199\",\"company_name\":\"PT Inovasi Software Solution\",\"pic_name\":\"Riyan F\",\"pic_phone\":\"081219110199\",\"company_email\":\"riyan@example.com\",\"operational_address\":\"Jl. Jend. Sudirman No. 1, Jakarta Pusat\",\"business_field\":\"Teknologi Informasi\",\"modal_dasar\":null}','OrNAvVNAzAbSRotZNWKkmukT65epIBre7q7GiFev8Hv4SgNY',NULL,NULL,NULL,'2026-09-05 10:09:04','2026-11-13 09:58:01',NULL),(2,'ORD-VIR-20260915-RGLPT','INV-ORD-VIR-20260915-RGLPT','2026-09-15',9,1,'Virtual Office – Basic Package','approved',2800000.00,NULL,NULL,NULL,NULL,'verified',NULL,2,'2026-09-15 04:04:33',NULL,'2026-09-15 04:04:33',NULL,NULL,NULL,0,NULL,NULL,NULL,'{\"service\":\"virtual-office\",\"package\":\"premium\",\"director_name\":\"David Julianto Simbolon\",\"director_phone\":\"081280594939\",\"company_name\":\"CV Tiga Bintang Agro Raya\",\"pic_name\":\"David Julianto Simbolon\",\"pic_phone\":\"081280594939\",\"company_email\":\"simbolondavid7@gmail.com\",\"operational_address\":null,\"business_field\":\"Perkebunan Kelapa Sawit\",\"modal_dasar\":null}',NULL,NULL,NULL,NULL,'2026-09-15 04:04:33','2026-09-15 04:04:33',NULL),(3,'ORD-VIR-20260915-5VXVY','INV-ORD-VIR-20260915-5VXVY','2026-09-15',10,1,'Virtual Office – Business Package','approved',5800000.00,NULL,NULL,NULL,NULL,'verified',NULL,2,'2026-09-15 07:51:10',NULL,'2026-09-15 07:51:10',NULL,NULL,NULL,0,NULL,NULL,NULL,'{\"service\":\"virtual-office\",\"package\":\"enterprise\",\"director_name\":\"Estika Suraida\",\"director_phone\":\"081316350501\",\"company_name\":\"PT Digital Inovasi Nusantara\",\"pic_name\":\"Estika Suraida\",\"pic_phone\":\"081316350501\",\"company_email\":\"essuraida@gmail.com\",\"operational_address\":null,\"business_field\":\"Pemasangan Sistem Elektronika\",\"modal_dasar\":null}',NULL,NULL,NULL,NULL,'2026-09-15 07:51:10','2026-09-15 07:51:10',NULL),(4,'ORD-VIR-20260922-YMYNT','INV-ORD-VIR-20260922-YMYNT','2026-09-22',11,1,'Virtual Office – Basic Package','approved',2800000.00,NULL,NULL,NULL,NULL,'verified',NULL,2,'2026-09-22 09:57:33',NULL,'2026-09-22 09:57:33',NULL,NULL,NULL,0,NULL,NULL,NULL,'{\"service\":\"virtual-office\",\"package\":\"premium\",\"director_name\":\"Tommy Liusudarso\",\"director_phone\":\"81138800688\",\"company_name\":\"PT Ranking Digital Indonesia\",\"pic_name\":\"Tommy Liusudarso\",\"pic_phone\":\"81138800688\",\"company_email\":\"tommyliusudarso.ll@gmail.com\",\"operational_address\":null,\"business_field\":\"Platform Digital\",\"modal_dasar\":null}',NULL,NULL,NULL,NULL,'2026-09-22 09:57:33','2026-09-22 09:57:33',NULL),(5,'ORD-VIR-20261102-9JJYN','INV-ORD-VIR-20261102-9JJYN','2026-11-02',18,1,'Virtual Office – Business Package','approved',58000000.00,NULL,NULL,NULL,NULL,'verified',NULL,2,'2026-11-02 07:43:00',NULL,'2026-11-12 11:05:46',NULL,NULL,NULL,0,NULL,NULL,NULL,'{\"service\":\"virtual-office\",\"package\":\"enterprise\",\"director_name\":\"Bernadus Jaka Prasetya\",\"director_phone\":\"085642566620\",\"company_name\":\"PT Anugrah Jaya Prasetya\",\"pic_name\":\"Bernadus Jaka Prasetya\",\"pic_phone\":\"085642566620\",\"company_email\":\"jakaprasetya14@gmail.com\",\"operational_address\":null,\"business_field\":\"Finance\",\"modal_dasar\":null}','kldis7PMjs01Ob0jgJ5zZ1382iEOxQwvO2lFjgjGbaHyWI87',NULL,NULL,NULL,'2026-11-02 07:43:00','2026-11-12 11:05:46',NULL),(6,'ORD-VIR-20261112-FWDD6','INV-ORD-VIR-20261112-FWDD6','2026-11-12',19,1,'Virtual Office – Business Package','approved',5800000.00,NULL,NULL,NULL,NULL,'verified',NULL,2,'2026-11-12 11:33:11',NULL,'2026-11-12 11:33:11',NULL,NULL,NULL,0,NULL,NULL,NULL,'{\"service\":\"virtual-office\",\"package\":\"enterprise\",\"director_name\":\"Tomy\",\"director_phone\":\"0819-0883-6888\",\"company_name\":\"PT ARUS TEKNOLOGI NUSANTARA\",\"pic_name\":\"Tomy\",\"pic_phone\":\"0819-0883-6888\",\"company_email\":\"petoi.tomy@gmail.com\",\"operational_address\":\"World Capital Tower Lantai 38 unit 06-07, Jalan DR. Ide Anak Agung Gde Agung Lot. D, RT.5\\/RW.2, Kuningan, Mega Kuningan, Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950, Desa\\/Kelurahan Kuningan Timur, Kec. Setiabudi, Kota Adm. Jakarta Selatan, Provinsi DKI Jakarta, Kode Pos: 12950\",\"business_field\":\"Penerbitan lainnya dan Aktivitas Jasa Portal Pencarian Web dan Informasi Lainnya\",\"modal_dasar\":null}',NULL,NULL,NULL,NULL,'2026-11-12 11:33:11','2026-11-12 11:33:11',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peraturan_k_b_l_i_s`
--

DROP TABLE IF EXISTS `peraturan_k_b_l_i_s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peraturan_k_b_l_i_s` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_kbli` varchar(255) NOT NULL,
  `judul_peraturan` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_berlaku` date NOT NULL,
  `status` enum('aktif','direvisi','dicabut') NOT NULL DEFAULT 'aktif',
  `file_dokumen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peraturan_k_b_l_i_s`
--

LOCK TABLES `peraturan_k_b_l_i_s` WRITE;
/*!40000 ALTER TABLE `peraturan_k_b_l_i_s` DISABLE KEYS */;
/*!40000 ALTER TABLE `peraturan_k_b_l_i_s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `podcast_room_bookings`
--

DROP TABLE IF EXISTS `podcast_room_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `podcast_room_bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `source_type` varchar(255) NOT NULL DEFAULT 'manual',
  `benefit_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `podcast_title` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `participants` int(11) NOT NULL DEFAULT 1,
  `package` varchar(255) NOT NULL DEFAULT 'regular',
  `total_price` bigint(20) NOT NULL DEFAULT 0,
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `status` enum('pending','pending_approval','approved','rejected','checkin','paused','selesai') NOT NULL DEFAULT 'pending',
  `checkin_at` timestamp NULL DEFAULT NULL,
  `checkout_at` timestamp NULL DEFAULT NULL,
  `total_used_minutes` int(11) NOT NULL DEFAULT 0,
  `total_used_seconds` int(11) NOT NULL DEFAULT 0,
  `room_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `podcast_room_bookings_order_number_unique` (`order_number`),
  KEY `podcast_room_bookings_user_id_foreign` (`user_id`),
  KEY `podcast_room_bookings_benefit_id_foreign` (`benefit_id`),
  KEY `podcast_room_bookings_created_by_foreign` (`created_by`),
  CONSTRAINT `podcast_room_bookings_benefit_id_foreign` FOREIGN KEY (`benefit_id`) REFERENCES `room_benefits` (`id`) ON DELETE SET NULL,
  CONSTRAINT `podcast_room_bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `podcast_room_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `podcast_room_bookings`
--

LOCK TABLES `podcast_room_bookings` WRITE;
/*!40000 ALTER TABLE `podcast_room_bookings` DISABLE KEYS */;
INSERT INTO `podcast_room_bookings` VALUES (1,'benefit',2,4,'ORD-VIR-20260905-XUGHQ','PT Inovasi Software Solution',NULL,NULL,NULL,NULL,12,1,'Bundling Virtual Office – Podcast Room',0,NULL,'approved',NULL,'approved',NULL,NULL,0,0,NULL,'Benefit otomatis dari Order ORD-VIR-20260905-XUGHQ',NULL,'2026-09-05 10:09:04','2026-09-05 10:09:04'),(2,'benefit',5,10,'ORD-VIR-20260915-5VXVY','PT Digital Inovasi Nusantara',NULL,NULL,NULL,NULL,12,1,'Bundling Virtual Office – Podcast Room',0,NULL,'approved',NULL,'approved',NULL,NULL,0,0,NULL,'Benefit otomatis dari Order ORD-VIR-20260915-5VXVY',NULL,'2026-09-15 07:51:11','2026-09-15 07:51:11'),(3,'manual',NULL,17,'PODCAST-20260928-GWG0C','riyan',NULL,'2026-09-28',NULL,'22:00:00',4,1,'Sewa Sesi (4 Jam)',1300000,NULL,'approved','Payment WA','selesai',NULL,'2026-09-28 11:40:52',0,3600,'Podcast Room 1',NULL,1,'2026-09-28 11:39:23','2026-09-28 11:40:52'),(4,'manual',NULL,17,'PODCAST-20260928-FMBIJ','riyan',NULL,'2026-09-28','19:00:00','22:00:00',2,1,'Sewa Sesi (2 Jam)',700000,NULL,'approved','Payment WA','selesai',NULL,'2026-09-28 11:56:28',0,3600,'Podcast Room 1',NULL,1,'2026-09-28 11:53:29','2026-09-28 11:56:28'),(5,'benefit',9,18,'ORD-VIR-20261102-9JJYN','PT Anugrah Jaya Prasetya',NULL,NULL,NULL,NULL,12,1,'Bundling Virtual Office – Podcast Room',0,NULL,'approved',NULL,'approved',NULL,NULL,0,0,NULL,'Benefit otomatis dari Order ORD-VIR-20261102-9JJYN',NULL,'2026-11-02 07:43:00','2026-11-02 07:43:00'),(6,'benefit',11,19,'ORD-VIR-20261112-FWDD6','PT ARUS TEKNOLOGI NUSANTARA',NULL,NULL,NULL,NULL,12,1,'Bundling Virtual Office – Podcast Room',0,NULL,'approved',NULL,'approved',NULL,NULL,0,0,NULL,'Benefit otomatis dari Order ORD-VIR-20261112-FWDD6',NULL,'2026-11-12 11:33:12','2026-11-12 11:33:12'),(7,'manual',NULL,15,'PODCAST-20261113-XENAJ','Septiano Bintang Pradhana',NULL,'2026-11-27','10:00:00','12:00:00',4,6,'Sewa Sesi (4 Jam)',1300000,NULL,'approved','Payment WA','approved',NULL,NULL,0,0,'Podcast Studio Lawgika Office, World Capital Tower Lt. 38 Unit 6-7','Add-On: Mikrofon (1 Unit), Kamera (2 Unit), Operator Podcast (1 Orang) [Payment: Transfer Bank BCA] | Penambahan 2 Kamera dan Pendampingan Operator\r\nPenyewaan 4 jam dan dibagi menjadi 2 Sesi',1,'2026-11-13 09:38:51','2026-11-13 09:41:10');
/*!40000 ALTER TABLE `podcast_room_bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promos`
--

DROP TABLE IF EXISTS `promos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tipe_diskon` enum('persen','nominal') NOT NULL DEFAULT 'persen',
  `diskon` decimal(15,2) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promos`
--

LOCK TABLES `promos` WRITE;
/*!40000 ALTER TABLE `promos` DISABLE KEYS */;
/*!40000 ALTER TABLE `promos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quota_logs`
--

DROP TABLE IF EXISTS `quota_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quota_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `duration` bigint(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quota_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `quota_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quota_logs`
--

LOCK TABLES `quota_logs` WRITE;
/*!40000 ALTER TABLE `quota_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `quota_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_benefit_logs`
--

DROP TABLE IF EXISTS `room_benefit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_benefit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `benefit_id` bigint(20) unsigned NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `duration_minutes` int(10) unsigned NOT NULL DEFAULT 0,
  `action` varchar(255) NOT NULL,
  `action_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `checkin_at` timestamp NULL DEFAULT NULL,
  `checkout_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_benefit_logs_benefit_id_foreign` (`benefit_id`),
  CONSTRAINT `room_benefit_logs_benefit_id_foreign` FOREIGN KEY (`benefit_id`) REFERENCES `room_benefits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_benefit_logs`
--

LOCK TABLES `room_benefit_logs` WRITE;
/*!40000 ALTER TABLE `room_benefit_logs` DISABLE KEYS */;
INSERT INTO `room_benefit_logs` VALUES (1,6,'meeting',240,'checkout','2026-09-24 06:32:26','2026-09-24 03:17:12','2026-09-24 06:32:26','2026-09-24 06:32:26','2026-09-24 06:32:26'),(2,7,'meeting',60,'checkout','2026-09-28 11:36:01','2026-09-28 11:35:24','2026-09-28 11:36:01','2026-09-28 11:36:01','2026-09-28 11:36:01'),(3,7,'podcast',60,'checkout','2026-09-28 11:40:52','2026-09-28 11:40:27','2026-09-28 11:40:52','2026-09-28 11:40:52','2026-09-28 11:40:52'),(4,7,'podcast',60,'checkout','2026-09-28 11:56:28','2026-09-28 11:56:13','2026-09-28 11:56:28','2026-09-28 11:56:28','2026-09-28 11:56:28');
/*!40000 ALTER TABLE `room_benefit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_benefits`
--

DROP TABLE IF EXISTS `room_benefits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_benefits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `meeting_room_booking_id` bigint(20) unsigned DEFAULT NULL,
  `podcast_room_booking_id` bigint(20) unsigned DEFAULT NULL,
  `paket` varchar(255) NOT NULL,
  `total_minutes` int(10) unsigned NOT NULL DEFAULT 3600,
  `used_minutes` int(10) unsigned NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT 'shared',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `started_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `renewal_reminder_h30_sent_at` datetime DEFAULT NULL,
  `renewal_reminder_h7_sent_at` datetime DEFAULT NULL,
  `renewal_reminder_expired_sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_benefits_user_id_is_active_index` (`user_id`,`is_active`),
  KEY `room_benefits_order_id_index` (`order_id`),
  KEY `room_benefits_meeting_room_booking_id_foreign` (`meeting_room_booking_id`),
  KEY `room_benefits_podcast_room_booking_id_foreign` (`podcast_room_booking_id`),
  KEY `room_benefits_approved_by_foreign` (`approved_by`),
  CONSTRAINT `room_benefits_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `room_benefits_meeting_room_booking_id_foreign` FOREIGN KEY (`meeting_room_booking_id`) REFERENCES `meeting_room_bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_benefits_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_benefits_podcast_room_booking_id_foreign` FOREIGN KEY (`podcast_room_booking_id`) REFERENCES `podcast_room_bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_benefits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_benefits`
--

LOCK TABLES `room_benefits` WRITE;
/*!40000 ALTER TABLE `room_benefits` DISABLE KEYS */;
INSERT INTO `room_benefits` VALUES (1,4,1,1,NULL,'Bundling Virtual Office – Meeting Room',2880,0,'meeting',1,'2026-09-04 17:00:00','2027-09-05 10:09:04',NULL,'2026-09-05 10:09:04',NULL,NULL,NULL,'2026-09-05 10:09:04','2026-09-05 10:09:04'),(2,4,1,NULL,1,'Bundling Virtual Office – Podcast Room',720,0,'podcast',1,'2026-09-04 17:00:00','2027-09-05 10:09:04',NULL,'2026-09-05 10:09:04',NULL,NULL,NULL,'2026-09-05 10:09:04','2026-09-05 10:09:04'),(3,9,2,2,NULL,'Premium Virtual Office – Meeting Room',720,0,'meeting',1,'2026-09-14 17:00:00','2027-09-15 04:04:34',NULL,'2026-09-15 04:04:33',NULL,NULL,NULL,'2026-09-15 04:04:34','2026-09-15 04:04:34'),(4,10,3,3,NULL,'Bundling Virtual Office – Meeting Room',2880,0,'meeting',1,'2026-09-14 17:00:00','2027-09-15 07:51:11',NULL,'2026-09-15 07:51:10',NULL,NULL,NULL,'2026-09-15 07:51:11','2026-09-15 07:51:11'),(5,10,3,NULL,2,'Bundling Virtual Office – Podcast Room',720,0,'podcast',1,'2026-09-14 17:00:00','2027-09-15 07:51:11',NULL,'2026-09-15 07:51:10',NULL,NULL,NULL,'2026-09-15 07:51:11','2026-09-15 07:51:12'),(6,11,4,4,NULL,'Premium Virtual Office – Meeting Room',720,240,'meeting',1,'2026-09-21 17:00:00','2027-09-22 09:57:33',NULL,'2026-09-22 09:57:33',NULL,NULL,NULL,'2026-09-22 09:57:33','2026-09-24 06:32:26'),(7,17,NULL,5,NULL,'Paket Meeting Room (60 Jam / 1 Tahun)',3600,180,'meeting',1,'2026-09-28 11:34:34','2027-09-28 11:34:34',NULL,'2026-09-28 11:34:34',NULL,NULL,NULL,'2026-09-28 11:34:34','2026-09-28 11:56:28'),(8,18,5,6,NULL,'Bundling Virtual Office – Meeting Room',2880,0,'meeting',1,'2026-11-01 17:00:00','2027-11-02 07:43:00',NULL,'2026-11-12 11:05:46',NULL,NULL,NULL,'2026-11-02 07:43:00','2026-11-02 07:43:00'),(9,18,5,NULL,5,'Bundling Virtual Office – Podcast Room',720,0,'podcast',1,'2026-11-01 17:00:00','2027-11-02 07:43:00',NULL,'2026-11-12 11:05:46',NULL,NULL,NULL,'2026-11-02 07:43:00','2026-11-02 07:43:00'),(10,19,6,7,NULL,'Bundling Virtual Office – Meeting Room',2880,0,'meeting',1,'2026-11-11 17:00:00','2027-11-12 11:33:11',NULL,'2026-11-12 11:33:11',NULL,NULL,NULL,'2026-11-12 11:33:11','2026-11-12 11:33:11'),(11,19,6,NULL,6,'Bundling Virtual Office – Podcast Room',720,0,'podcast',1,'2026-11-11 17:00:00','2027-11-12 11:33:11',NULL,'2026-11-12 11:33:11',NULL,NULL,NULL,'2026-11-12 11:33:12','2026-11-12 11:33:12');
/*!40000 ALTER TABLE `room_benefits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_usage_logs`
--

DROP TABLE IF EXISTS `room_usage_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_usage_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_usage_logs`
--

LOCK TABLES `room_usage_logs` WRITE;
/*!40000 ALTER TABLE `room_usage_logs` DISABLE KEYS */;
INSERT INTO `room_usage_logs` VALUES (1,4,'meeting_room','checkin','2026-09-24 10:17:12','Benefit otomatis dari Order ORD-VIR-20260922-YMYNT','2026-09-24 03:17:12','2026-09-24 03:17:12'),(2,4,'meeting_room','checkout','2026-09-24 13:32:26','Benefit otomatis dari Order ORD-VIR-20260922-YMYNT','2026-09-24 06:32:26','2026-09-24 06:32:26'),(3,5,'meeting_room','checkin','2026-09-28 18:35:24',NULL,'2026-09-28 11:35:24','2026-09-28 11:35:24'),(4,5,'meeting_room','checkout','2026-09-28 18:36:01',NULL,'2026-09-28 11:36:01','2026-09-28 11:36:01'),(5,3,'podcast_room','checkin','2026-09-28 18:40:27',NULL,'2026-09-28 11:40:27','2026-09-28 11:40:27'),(6,3,'podcast_room','checkout','2026-09-28 18:40:52',NULL,'2026-09-28 11:40:52','2026-09-28 11:40:52'),(7,4,'podcast_room','checkin','2026-09-28 18:56:14',NULL,'2026-09-28 11:56:14','2026-09-28 11:56:14'),(8,4,'podcast_room','checkout','2026-09-28 18:56:28',NULL,'2026-09-28 11:56:28','2026-09-28 11:56:28');
/*!40000 ALTER TABLE `room_usage_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Virtual Office','virtual-office','Miliki alamat bisnis prestisius tanpa perlu menyewa kantor fisik. Hemat biaya operasional hingga 90% dengan layanan Virtual Office dari Lawgika.',NULL,NULL,1,'2026-09-02 13:25:05','2026-09-02 13:25:05'),(2,'Pendirian PT Perorangan','pendirian-pt-perorangan',NULL,NULL,'pendirian-badan-usaha',1,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(3,'Pendirian PT','pendirian-pt',NULL,NULL,'pendirian-badan-usaha',1,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(4,'Pendirian PT PMA','pendirian-pt-pma',NULL,NULL,'pendirian-badan-usaha',1,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(5,'Pendirian CV','pendirian-cv',NULL,NULL,'pendirian-badan-usaha',1,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(6,'Pendirian Yayasan','pendirian-yayasan',NULL,NULL,'pendirian-badan-usaha',1,'2026-09-05 10:06:25','2026-09-05 10:06:25'),(7,'Pendirian Firma','pendirian-firma',NULL,NULL,'pendirian-badan-usaha',1,'2026-09-05 10:06:25','2026-09-05 10:06:25');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spt_badans`
--

DROP TABLE IF EXISTS `spt_badans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spt_badans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `subject_type` varchar(255) NOT NULL DEFAULT 'badan',
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `perusahaan` varchar(255) DEFAULT NULL,
  `npwp_perusahaan` varchar(255) DEFAULT NULL,
  `jenis_usaha` varchar(255) DEFAULT NULL,
  `tahun_pajak` int(11) NOT NULL,
  `laporan_keuangan` varchar(255) NOT NULL,
  `status_lapor` varchar(255) NOT NULL,
  `status_pesanan` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spt_badans_user_id_foreign` (`user_id`),
  CONSTRAINT `spt_badans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spt_badans`
--

LOCK TABLES `spt_badans` WRITE;
/*!40000 ALTER TABLE `spt_badans` DISABLE KEYS */;
/*!40000 ALTER TABLE `spt_badans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spt_requests`
--

DROP TABLE IF EXISTS `spt_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spt_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `nama` varchar(255) NOT NULL,
  `perusahaan` varchar(255) NOT NULL,
  `jenis_usaha` varchar(255) NOT NULL,
  `tahun_pajak` year(4) NOT NULL,
  `laporan_keuangan` varchar(255) NOT NULL,
  `status_lapor` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spt_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `spt_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spt_requests`
--

LOCK TABLES `spt_requests` WRITE;
/*!40000 ALTER TABLE `spt_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `spt_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_room_quotas`
--

DROP TABLE IF EXISTS `user_room_quotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_room_quotas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `total_seconds` bigint(20) NOT NULL DEFAULT 216000,
  `used_seconds` bigint(20) NOT NULL DEFAULT 0,
  `remaining_seconds` bigint(20) NOT NULL DEFAULT 216000,
  `expired_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_room_quotas_user_id_unique` (`user_id`),
  CONSTRAINT `user_room_quotas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_room_quotas`
--

LOCK TABLES `user_room_quotas` WRITE;
/*!40000 ALTER TABLE `user_room_quotas` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_room_quotas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `pic_name` varchar(255) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin SPV','admin@example.com',NULL,'admin','08111111111',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$h34r/XLhCsyJwHw.nyz43e8QKVwd1feYUxCN45zLbX.rB81A7Ekm.','ACZzdV2JHohYl5ybdgn0uCcl2T8ubj9D1B60QTYSshAaQOX7PEMxTCb0mPQh','2026-09-02 13:25:06','2026-09-05 10:06:22'),(2,'Admin Order 1','admin1@example.com',NULL,'admin1','08222222222',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$QwaAASp2U5zi0LiTe7wqqOtYWMGAxEEn7WDdVsjCakBIYjQ3w3yfy','MOi4r6P0jdBdGSxF9uJCqduaYVHXoKeEzCV8emqGKuBkEsTQyX1N50ylr7Av','2026-09-02 13:25:07','2026-09-05 10:06:23'),(3,'Admin Konten 2','admin2@example.com',NULL,'admin2','08333333333',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$3yAZ2MiPLz2vFYUL.bpncu1H9OID8j/OqrqadxP3RBkaJo2d7J9qi',NULL,'2026-09-02 13:25:07','2026-09-05 10:06:23'),(4,'Riyan F','riyan@example.com',NULL,'customer','081219110199','Jl. Jend. Sudirman No. 1, Jakarta Pusat','PT Inovasi Software Solution',NULL,NULL,'Teknologi Informasi',NULL,'$2y$12$XGHtQ6w4VuxA.KBKInekeOhsg2w4OoZrExIlwGWks6sU/YnH4vT7G','rxDLi7Uch1d3QZBJqBxWiMwdIIqyyQTYS5RkjDk92K7e4k0eDD1vVSSkarIy','2026-09-02 13:25:09','2026-11-12 10:46:49'),(5,'Siti Aminah','siti@example.com',NULL,'customer','089876543210','Jl. Gatot Subroto No. 45, Bandung','CV Karya Indah',NULL,NULL,'Desain Grafis dan Percetakan',NULL,'$2y$12$q36GKdKrOe7g6BiXl0Qi8..LmyH3IY1vC/zaHiNdINhIedyuBlpP.',NULL,'2026-09-02 13:25:09','2026-09-05 10:06:25'),(6,'Andi Wijaya','andi@example.com',NULL,'customer','085612349876','Jl. MH Thamrin No. 10, Surabaya','Firma Hukum Wijaya & Rekan',NULL,NULL,'Layanan Hukum',NULL,'$2y$12$pglD0/kSmc8LU9PEE8nehuP0AOQ7bdsTdFI52ATuW0vr1Xq5nSQWa',NULL,'2026-09-02 13:25:09','2026-09-05 10:06:25'),(7,'Rina Melati','rina@example.com',NULL,'customer','082233445566','Jl. Merdeka No. 99, Yogyakarta','Yayasan Pendidikan Indonesia',NULL,NULL,'Pendidikan',NULL,'$2y$12$NfiuD36Im2Z9uMpwTtRI..hYn0xAdm1mJeBIKpbo69N2sYm9mweLu',NULL,'2026-09-02 13:25:09','2026-11-12 11:32:33'),(8,'Joko Santoso','joko@example.com',NULL,'customer','081344556677','Jl. Kemang Raya No. 12, Jakarta Selatan','PT Sinema Mandiri',NULL,NULL,'Hiburan dan Media',NULL,'$2y$12$39rUtQRAbc2zxDdyuc8bleLOzC1dTKR4SEL4rsosuy0KlXK/DkSG.',NULL,'2026-09-02 13:25:09','2026-09-05 10:06:25'),(9,'David Julianto Simbolon','simbolondavid7@gmail.com',NULL,'customer','081280594939',NULL,'CV Tiga Bintang Agro Raya',NULL,'1000000010754471','Perkebunan Kelapa Sawit',NULL,'$2y$12$Ih5JwvfmkXDNItZEZKRV.OfKKQeql.9eBpKPX.fLpWDpPN5rojl6i',NULL,'2026-09-15 03:44:31','2026-09-15 03:44:31'),(10,'Estika Suraida','essuraida@gmail.com',NULL,'customer','081316350501',NULL,'PT Digital Inovasi Nusantara',NULL,'1000000010693518','Pemasangan Sistem Elektronika',NULL,'$2y$12$ABHnb.l5u1GyEV3tWoaBsengs.mTICm3crmYvbECsKTj9k0gUPnmW',NULL,'2026-09-15 07:48:44','2026-09-15 07:48:44'),(11,'Tommy Liusudarso','tommyliusudarso.ll@gmail.com',NULL,'customer','081138800688',NULL,'PT Ranking Digital Indonesia',NULL,'1000000008032059','Platform Digital',NULL,'$2y$12$xQIn9SqUQGAUu44rtteXhuCeaOR4ISeDox3uhvw57msRtnEMUD97O',NULL,'2026-09-22 09:55:46','2026-09-24 03:19:03'),(12,'Shaluna Sonia','communication@iccindonesia.org',NULL,'customer','081517612139',NULL,'ICC Indonesia',NULL,'73.010.738.0-063.000','Organisasi',NULL,'$2y$12$h2H.5NKaXwe6g5Vo2SK35eWwEWvjLXxaqmjzNVEUfMcj0nm/76YvC',NULL,'2026-09-24 02:21:02','2026-09-24 03:08:32'),(13,'Amanda Risky Amalia','b@example.com',NULL,'customer','085771780598',NULL,'PT Widia Inovasi Nusantara',NULL,'03.093.840.1-031.000','Edukasi','Kuota podcast sisa 4 jam','$2y$12$bvLiE2EElsMFZsafHArfbObgtzM0lVepDuHKb4IreF94g4aaf974K',NULL,'2026-09-24 02:50:13','2026-09-24 03:13:48'),(14,'Jihan Fatimah Azzahra','jihanftzra@gmail.com',NULL,'customer','082124918780',NULL,'-',NULL,NULL,NULL,NULL,'$2y$12$38HyjE4/shpN43k8otlU7.A55REa/nLYzsYW8pDxL4ybrZNKBiQ8e',NULL,'2026-09-24 02:54:12','2026-09-24 02:54:12'),(15,'Septiano Bintang Pradhana','bintang@example.com',NULL,'customer','081217237556',NULL,'PT Konsultan Strategi Penjualan',NULL,'84.423.640.6-076.000','Agensi Kreatif',NULL,'$2y$12$nyyemIcn9XHLKd1B/GT6SeDh/Yq.Snssn.kn2wN/BGasmqbL9cuoi',NULL,'2026-09-24 03:12:31','2026-09-24 03:12:31'),(16,'Muhammad Fadlhy Fajarsyach','fadlhy435@gmail.com',NULL,'customer','082321782953',NULL,'-',NULL,NULL,NULL,NULL,'$2y$12$XB5pcPOywTwAZQr2ot9GUeuifR2LbmIAddjfntWQeVbfvpUTk/J1a',NULL,'2026-09-24 03:15:39','2026-09-24 03:15:39'),(17,'riyan','riyanfirdzi@gmail.com',NULL,'customer','081219110199',NULL,'riyan','riyan`',NULL,'IT',NULL,'$2y$12$foRBj2XUKxcWhshWEqjaW.ZupKsQbuHNW/LZzijZww8fwiGGHYCnW',NULL,'2026-09-28 11:34:08','2026-11-12 10:41:26'),(18,'Bernadus Jaka Prasetya','jakaprasetya14@gmail.com',NULL,'customer','085642566620',NULL,'PT Anugrah Jaya Prasetya',NULL,NULL,'Finance',NULL,'$2y$12$nQpv.v1P2KtMG4iVr3/31umE9roT1wPqj7xgN68V4exVo4Y2Cn42u',NULL,'2026-11-02 07:32:25','2026-11-02 07:32:25'),(19,'Tomy','petoi.tomy@gmail.com',NULL,'customer','0819-0883-6888','World Capital Tower Lantai 38 unit 06-07, Jalan DR. Ide Anak Agung Gde Agung Lot. D, RT.5/RW.2, Kuningan, Mega Kuningan, Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950, Desa/Kelurahan Kuningan Timur, Kec. Setiabudi, Kota Adm. Jakarta Selatan, Provinsi DKI Jakarta, Kode Pos: 12950','PT ARUS TEKNOLOGI NUSANTARA',NULL,'1000000011762674','Penerbitan lainnya dan Aktivitas Jasa Portal Pencarian Web dan Informasi Lainnya',NULL,'$2y$12$wxjceewFlYnRSx.zqJLnyO3o/RCmhhw0TrXDQBL6aoQij2VFxms4G',NULL,'2026-11-12 10:42:36','2026-11-12 11:31:32'),(20,'Admin Verifikasi 3','admin3@example.com',NULL,'admin3','08444444444',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$aRLLZjuRlT4Sr8OBbkwwDuYI19zlZuLF4NCvb9cqO6mN0WV/y1dD.',NULL,'2026-11-17 11:19:25','2026-11-17 11:19:25');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `virtual_office_guest_notifications`
--

DROP TABLE IF EXISTS `virtual_office_guest_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `virtual_office_guest_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `virtual_office_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `guest_name` varchar(255) NOT NULL,
  `guest_phone` varchar(255) NOT NULL,
  `guest_company` varchar(255) NOT NULL,
  `arrival_time` varchar(20) NOT NULL,
  `purpose` text NOT NULL,
  `internal_note` text DEFAULT NULL,
  `whatsapp_status` varchar(50) NOT NULL DEFAULT 'PENDING',
  `botcake_message_id` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `virtual_office_guest_notifications_virtual_office_id_foreign` (`virtual_office_id`),
  KEY `virtual_office_guest_notifications_client_id_foreign` (`client_id`),
  KEY `virtual_office_guest_notifications_created_by_foreign` (`created_by`),
  CONSTRAINT `virtual_office_guest_notifications_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `virtual_office_guest_notifications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `virtual_office_guest_notifications_virtual_office_id_foreign` FOREIGN KEY (`virtual_office_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `virtual_office_guest_notifications`
--

LOCK TABLES `virtual_office_guest_notifications` WRITE;
/*!40000 ALTER TABLE `virtual_office_guest_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `virtual_office_guest_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `virtual_office_mail_notifications`
--

DROP TABLE IF EXISTS `virtual_office_mail_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `virtual_office_mail_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `virtual_office_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `received_date` date NOT NULL,
  `received_time` varchar(20) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `internal_note` text DEFAULT NULL,
  `whatsapp_status` varchar(50) NOT NULL DEFAULT 'PENDING',
  `botcake_message_id` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `virtual_office_mail_notifications_virtual_office_id_foreign` (`virtual_office_id`),
  KEY `virtual_office_mail_notifications_client_id_foreign` (`client_id`),
  KEY `virtual_office_mail_notifications_created_by_foreign` (`created_by`),
  CONSTRAINT `virtual_office_mail_notifications_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `virtual_office_mail_notifications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `virtual_office_mail_notifications_virtual_office_id_foreign` FOREIGN KEY (`virtual_office_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `virtual_office_mail_notifications`
--

LOCK TABLES `virtual_office_mail_notifications` WRITE;
/*!40000 ALTER TABLE `virtual_office_mail_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `virtual_office_mail_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `whatsapp_logs`
--

DROP TABLE IF EXISTS `whatsapp_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `whatsapp_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `whatsapp_logs_client_id_index` (`client_id`),
  KEY `whatsapp_logs_order_id_index` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `whatsapp_logs`
--

LOCK TABLES `whatsapp_logs` WRITE;
/*!40000 ALTER TABLE `whatsapp_logs` DISABLE KEYS */;
INSERT INTO `whatsapp_logs` VALUES (1,11,NULL,'6281138800688','[TEMPLATE ID: 1732248697805244] Params: [\"Tommy Liusudarso\",\"Ruang Meetingroom 1\",\"24 Sep 2026\",\"10:00\",\"12:00\",\"11 Jam\"] | Body Length: 281/1024 (Remaining: 743)','SUCCESS','{\"success\":true}','2026-09-24 03:17:16','2026-09-24 03:17:16'),(2,11,NULL,'6281138800688','[TEMPLATE ID: 805177639284905] Params: [\"Tommy Liusudarso\",\"Meeting Room\",\"24 Sep 2026\",\"10:17\",\"13:32\",\"8 Jam\"] | Body Length: 273/1024 (Remaining: 751)','SUCCESS','{\"success\":true}','2026-09-24 06:32:30','2026-09-24 06:32:30'),(3,17,NULL,'6281219110199','[TEMPLATE ID: 1732248697805244] Params: [\"riyan`\",\"Ruang Meetingroom 1\",\"29 Sep 2026\",\"09:00\",\"13:00\",\"59 Jam\"] | Body Length: 271/1024 (Remaining: 753)','SUCCESS','{\"success\":true}','2026-09-28 11:35:27','2026-09-28 11:35:27'),(4,17,NULL,'6281219110199','[TEMPLATE ID: 805177639284905] Params: [\"riyan`\",\"Meeting Room\",\"28 Sep 2026\",\"09:00\",\"19:00\",\"59 Jam\"] | Body Length: 264/1024 (Remaining: 760)','SUCCESS','{\"success\":true}','2026-09-28 11:36:02','2026-09-28 11:36:02'),(5,17,NULL,'6281219110199','[TEMPLATE ID: 1827038834946958] Params: [\"riyan`\",\"28 Sep 2026\",\"18:00\",\"22:00\"] | Body Length: 230/1024 (Remaining: 794)','SUCCESS','{\"success\":true}','2026-09-28 11:40:28','2026-09-28 11:40:28'),(6,17,NULL,'6281219110199','[TEMPLATE ID: 1819424119047730] Params: [\"riyan`\",\"28 Sep 2026\",\"18:40\",\"18:40\"] | Body Length: 27/1024 (Remaining: 997)','SUCCESS','{\"success\":true}','2026-09-28 11:40:53','2026-09-28 11:40:53'),(7,17,NULL,'6281219110199','[TEMPLATE ID: 1827038834946958] Params: [\"riyan`\",\"28 Sep 2026\",\"19:00\",\"22:00\"] | Body Length: 230/1024 (Remaining: 794)','SUCCESS','{\"success\":true}','2026-09-28 11:56:16','2026-09-28 11:56:16'),(8,17,NULL,'6281219110199','[TEMPLATE ID: 1819424119047730] Params: [\"riyan`\",\"28 Sep 2026\",\"19:00\",\"19:00\"] | Body Length: 27/1024 (Remaining: 997)','SUCCESS','{\"success\":true}','2026-09-28 11:56:29','2026-09-28 11:56:29');
/*!40000 ALTER TABLE `whatsapp_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-17 18:20:29
