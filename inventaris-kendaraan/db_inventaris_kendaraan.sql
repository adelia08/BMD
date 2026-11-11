-- MySQL dump 10.13  Distrib 26.10.0, for macos27.0 (arm64)
--
-- Host: 127.0.0.1    Database: db_inventaris_kendaraan
-- ------------------------------------------------------
-- Server version	26.10.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `db_inventaris_kendaraan`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `db_inventaris_kendaraan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `db_inventaris_kendaraan`;

--
-- Table structure for table `golongan`
--

DROP TABLE IF EXISTS `golongan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `golongan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `golongan_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `golongan`
--

LOCK TABLES `golongan` WRITE;
/*!40000 ALTER TABLE `golongan` DISABLE KEYS */;
INSERT INTO `golongan` VALUES (1,'I/a','Juru Muda','2026-11-10 22:12:18','2026-11-10 22:12:18'),(2,'I/b','Juru Muda Tingkat I','2026-11-10 22:12:18','2026-11-10 22:12:18'),(3,'I/c','Juru','2026-11-10 22:12:18','2026-11-10 22:12:18'),(4,'I/d','Juru Tingkat I','2026-11-10 22:12:18','2026-11-10 22:12:18'),(5,'II/a','Pengatur Muda','2026-11-10 22:12:18','2026-11-10 22:12:18'),(6,'II/b','Pengatur Muda Tingkat I','2026-11-10 22:12:18','2026-11-10 22:12:18'),(7,'II/c','Pengatur','2026-11-10 22:12:18','2026-11-10 22:12:18'),(8,'II/d','Pengatur Tingkat I','2026-11-10 22:12:18','2026-11-10 22:12:18'),(9,'III/a','Penata Muda','2026-11-10 22:12:18','2026-11-10 22:12:18'),(10,'III/b','Penata Muda Tingkat I','2026-11-10 22:12:18','2026-11-10 22:12:18'),(11,'III/c','Penata','2026-11-10 22:12:18','2026-11-10 22:12:18'),(12,'III/d','Penata Tingkat I','2026-11-10 22:12:18','2026-11-10 22:12:18'),(13,'IV/a','Pembina','2026-11-10 22:12:18','2026-11-10 22:12:18'),(14,'IV/b','Pembina Tingkat I','2026-11-10 22:12:18','2026-11-10 22:12:18'),(15,'IV/c','Pembina Utama Muda','2026-11-10 22:12:18','2026-11-10 22:12:18'),(16,'IV/d','Pembina Utama Madya','2026-11-10 22:12:18','2026-11-10 22:12:18'),(17,'IV/e','Pembina Utama','2026-11-10 22:12:18','2026-11-10 22:12:18');
/*!40000 ALTER TABLE `golongan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jabatan`
--

DROP TABLE IF EXISTS `jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jabatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jabatan_nama_unique` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jabatan`
--

LOCK TABLES `jabatan` WRITE;
/*!40000 ALTER TABLE `jabatan` DISABLE KEYS */;
INSERT INTO `jabatan` VALUES (1,'Kepala Dinas','2026-11-10 22:12:18','2026-11-10 22:12:18'),(2,'Sekretaris','2026-11-10 22:12:18','2026-11-10 22:12:18'),(3,'Kepala Bidang','2026-11-10 22:12:18','2026-11-10 22:12:18'),(4,'Kepala Seksi','2026-11-10 22:12:18','2026-11-10 22:12:18'),(5,'Kepala Sub Bagian','2026-11-10 22:12:18','2026-11-10 22:12:18'),(6,'Analis Kebijakan','2026-11-10 22:12:18','2026-11-10 22:12:18'),(7,'Pengelola Barang','2026-11-10 22:12:18','2026-11-10 22:12:18'),(8,'Pengurus Barang','2026-11-10 22:12:18','2026-11-10 22:12:18'),(9,'Bendahara','2026-11-10 22:12:18','2026-11-10 22:12:18'),(10,'Pranata Komputer','2026-11-10 22:12:18','2026-11-10 22:12:18'),(11,'Arsiparis','2026-11-10 22:12:18','2026-11-10 22:12:18'),(12,'Staf Administrasi','2026-11-10 22:12:18','2026-11-10 22:12:18'),(13,'Pengemudi','2026-11-10 22:12:18','2026-11-10 22:12:18');
/*!40000 ALTER TABLE `jabatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kendaraan`
--

DROP TABLE IF EXISTS `kendaraan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kendaraan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nopol` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_model` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_produksi` year NOT NULL,
  `warna` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kondisi` enum('BAIK','RUSAK RINGAN','RUSAK BERAT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `perangkat_daerah_id` bigint unsigned NOT NULL,
  `pegawai_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kendaraan_nopol_unique` (`nopol`),
  KEY `kendaraan_perangkat_daerah_id_foreign` (`perangkat_daerah_id`),
  KEY `kendaraan_pegawai_id_foreign` (`pegawai_id`),
  CONSTRAINT `kendaraan_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `kendaraan_perangkat_daerah_id_foreign` FOREIGN KEY (`perangkat_daerah_id`) REFERENCES `perangkat_daerah` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kendaraan`
--

LOCK TABLES `kendaraan` WRITE;
/*!40000 ALTER TABLE `kendaraan` DISABLE KEYS */;
INSERT INTO `kendaraan` VALUES (4,'B 1234 UTR','HONDA','BEAT',2025,'HITAM','BAIK',1,NULL,'2026-11-10 21:44:17','2026-11-10 21:44:17');
/*!40000 ALTER TABLE `kendaraan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000003_create_perangkat_daerah_table',1),(3,'0001_01_01_000004_create_pegawai_table',1),(4,'0001_01_01_000005_create_kendaraan_table',1),(5,'2026_11_11_000001_add_role_to_users_table',2),(6,'2026_11_11_000002_add_singkatan_to_perangkat_daerah_table',3),(7,'2026_11_11_000003_create_jabatan_table',4),(8,'2026_11_11_000004_create_golongan_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `pegawai`
--

DROP TABLE IF EXISTS `pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pegawai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `golongan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perangkat_daerah_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pegawai_nip_unique` (`nip`),
  KEY `pegawai_perangkat_daerah_id_foreign` (`perangkat_daerah_id`),
  CONSTRAINT `pegawai_perangkat_daerah_id_foreign` FOREIGN KEY (`perangkat_daerah_id`) REFERENCES `perangkat_daerah` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pegawai`
--

LOCK TABLES `pegawai` WRITE;
/*!40000 ALTER TABLE `pegawai` DISABLE KEYS */;
INSERT INTO `pegawai` VALUES (4,'SOS-12345','DINDA (DINAS SOSIAL)','PROGRAMMER','III-A',6,'2026-11-10 21:50:22','2026-11-10 21:50:22');
/*!40000 ALTER TABLE `pegawai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perangkat_daerah`
--

DROP TABLE IF EXISTS `perangkat_daerah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perangkat_daerah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `singkatan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `perangkat_daerah_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perangkat_daerah`
--

LOCK TABLES `perangkat_daerah` WRITE;
/*!40000 ALTER TABLE `perangkat_daerah` DISABLE KEYS */;
INSERT INTO `perangkat_daerah` VALUES (1,'001','DINAS PENDIDIKAN','DISDIK','2026-11-10 21:14:56','2026-11-10 21:14:56'),(2,'002','DINAS KESEHATAN','DINKES','2026-11-10 21:14:56','2026-11-10 21:14:56'),(3,'003','DINAS PEKERJAAN UMUM DAN PENATAAN RUANG','PUPR','2026-11-10 21:14:56','2026-11-10 21:14:56'),(6,'DNS-05','DINAS SOSIAL','DINSOS','2026-11-10 21:49:33','2026-11-10 22:10:23');
/*!40000 ALTER TABLE `perangkat_daerah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','operator','viewer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'viewer',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@bmd.test',NULL,'$2y$12$hQ70PGQj9MXc3Pt5bOePZOreEX8ZvKtkobXc0BRWqAupGWZEBxTuG','admin','MaLOASt7olBtwXo7O49A6UrF7fqAsQ00QvGk1POMfVbJJlV4h6UmVUDK7VrE','2026-11-10 21:14:57','2026-11-10 21:14:57'),(4,'adel','adeltest@gmail.com',NULL,'$2y$12$DkL1Ky37Ch2w3r3cowlEVOyc3gFaESZq0vr9eCBsj4emptzec8rhK','operator','RAaWt3uLbBcz5cu4qv6aDusWrGgS8Y2MalPcfm7BueNnSrWTOJf3sydPG4rc','2026-11-10 21:48:39','2026-11-10 21:48:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-11 12:12:26
