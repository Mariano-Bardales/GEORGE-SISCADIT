-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: george_siscadit
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `control_menor1s`
--

DROP TABLE IF EXISTS `control_menor1s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_menor1s` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_niño` bigint(20) unsigned NOT NULL,
  `numero_control` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `control_menor1s_id_niño_numero_control_unique` (`id_niño`,`numero_control`),
  KEY `control_menor1s_id_niño_index` (`id_niño`),
  KEY `control_menor1s_fecha_index` (`fecha`),
  KEY `control_menor1s_id_niño_numero_control_index` (`id_niño`,`numero_control`),
  CONSTRAINT `control_menor1s_id_niño_foreign` FOREIGN KEY (`id_niño`) REFERENCES `ninos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_menor1s`
--

LOCK TABLES `control_menor1s` WRITE;
/*!40000 ALTER TABLE `control_menor1s` DISABLE KEYS */;
INSERT INTO `control_menor1s` VALUES (67,1,1,'2024-07-29'),(68,1,2,'2024-06-20'),(69,1,3,'2024-06-20'),(70,1,4,'2024-06-20'),(71,1,5,'2024-06-20'),(72,1,6,'2024-06-20'),(73,1,7,'2024-06-20'),(74,1,8,'2024-06-20'),(75,1,9,'2024-06-15'),(76,1,10,'2024-06-20'),(77,1,11,'2024-06-20');
/*!40000 ALTER TABLE `control_menor1s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `control_rns`
--

DROP TABLE IF EXISTS `control_rns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_rns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_niño` bigint(20) unsigned NOT NULL,
  `numero_control` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_rns_id_niño_index` (`id_niño`),
  KEY `control_rns_fecha_index` (`fecha`),
  KEY `control_rns_id_niño_numero_control_index` (`id_niño`,`numero_control`),
  CONSTRAINT `control_rns_id_niño_foreign` FOREIGN KEY (`id_niño`) REFERENCES `ninos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_rns`
--

LOCK TABLES `control_rns` WRITE;
/*!40000 ALTER TABLE `control_rns` DISABLE KEYS */;
INSERT INTO `control_rns` VALUES (25,1,1,'2024-06-19'),(26,1,2,'2024-06-25'),(27,1,3,'2024-07-02'),(28,1,4,'2024-06-20');
/*!40000 ALTER TABLE `control_rns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `datos_extras`
--

DROP TABLE IF EXISTS `datos_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datos_extras` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_niño` bigint(20) unsigned NOT NULL,
  `red` varchar(100) DEFAULT NULL,
  `microred` varchar(100) DEFAULT NULL,
  `eess_nacimiento` varchar(150) DEFAULT NULL,
  `distrito` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `seguro` varchar(100) DEFAULT NULL,
  `programa` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `datos_extras_id_niño_index` (`id_niño`),
  KEY `datos_extras_red_index` (`red`),
  KEY `datos_extras_microred_index` (`microred`),
  KEY `datos_extras_distrito_index` (`distrito`),
  CONSTRAINT `datos_extras_id_niño_foreign` FOREIGN KEY (`id_niño`) REFERENCES `ninos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datos_extras`
--

LOCK TABLES `datos_extras` WRITE;
/*!40000 ALTER TABLE `datos_extras` DISABLE KEYS */;
INSERT INTO `datos_extras` VALUES (14,1,'HOSPITAL REGIONAL DE PUCALLPA','Microred Centro','Hospital Regional de Pucallpa','Callería','Coronel Portillo','Ucayali','SIS','CRED');
/*!40000 ALTER TABLE `datos_extras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `madres`
--

DROP TABLE IF EXISTS `madres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `madres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_niño` bigint(20) unsigned DEFAULT NULL,
  `dni` varchar(15) DEFAULT NULL,
  `apellidos_nombres` varchar(150) NOT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `domicilio` text DEFAULT NULL,
  `referencia_direccion` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `madres_id_niño_index` (`id_niño`),
  KEY `madres_dni_index` (`dni`),
  CONSTRAINT `madres_ibfk_1` FOREIGN KEY (`id_niño`) REFERENCES `ninos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `madres`
--

LOCK TABLES `madres` WRITE;
/*!40000 ALTER TABLE `madres` DISABLE KEYS */;
INSERT INTO `madres` VALUES (14,1,'87654321','GARCÍA LÓPEZ, MARÍA ELENA','987654321','Jr. Los Olivos 123','Frente al mercado central');
/*!40000 ALTER TABLE `madres` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_11_23_153728_create_solicituds_table',1),(6,'2025_11_24_031446_create_roles_table',1),(7,'2025_11_24_031448_add_role_id_to_users_table',1),(8,'2025_11_24_031455_create_madres_table',1),(9,'2025_11_24_031455_create_ninos_table',1),(10,'2025_11_24_031456_create_datos_extras_table',1),(11,'2025_11_24_031456_create_recien_nacidos_table',1),(12,'2025_11_24_031457_create_tamizaje_neonatals_table',1),(13,'2025_11_24_031457_create_vacuna_rns_table',1),(14,'2025_11_24_031458_create_control_menor1s_table',1),(15,'2025_11_24_031458_create_control_rns_table',1),(16,'2025_11_24_031459_create_visita_domiciliarias_table',1),(17,'2025_11_24_032531_update_solicitudes_table_structure',1),(18,'2025_11_24_032532_update_users_table_add_role',1),(19,'2025_11_24_052422_add_id_madre_to_ninos_table',1),(20,'2025_11_24_080433_add_user_id_to_solicitudes_table',1),(21,'2025_12_02_034345_add_peso_talla_perimetro_to_controles_menor1_table',1),(22,'2025_12_02_034359_add_peso_talla_perimetro_to_controles_rn_table',1),(23,'2025_12_03_005405_modify_peso_column_recien_nacido_table',1),(24,'2025_12_06_000001_fix_madres_id_nino_column',2),(25,'2025_12_10_120016_add_indexes_to_tables',3),(26,'2025_12_10_120019_add_soft_deletes_to_tables',3),(27,'2025_12_10_120021_create_audit_logs_table',3),(28,'2025_12_10_124850_remove_roles_table',4),(29,'2025_12_10_125500_remove_redundant_age_fields',5),(30,'2025_12_10_130507_optimize_control_menor1s_table',6),(31,'2025_12_10_130513_remove_audit_logs_table',6),(32,'2025_12_10_140325_remove_estado_cred_fields_from_control_menor1s',7),(33,'2025_12_10_142020_remove_campos_medicos_from_control_menor1s',8),(34,'2025_12_10_142023_remove_campos_medicos_from_control_rns',8),(35,'2025_12_10_142025_remove_campos_from_tamizaje_neonatals',8),(40,'2025_12_10_142029_remove_estados_from_vacuna_rns',9),(41,'2025_12_10_142027_modify_visitas_domiciliarias_table',10),(42,'2025_12_10_143530_remove_estado_from_control_rns',11),(43,'2025_12_10_144918_remove_deleted_at_from_all_tables',12),(44,'2025_12_10_145729_remove_timestamps_from_all_tables',13),(45,'2025_12_10_172419_reset_auto_increment_ids',14),(46,'2025_12_10_172530_reset_auto_increment_tables',14);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ninos`
--

DROP TABLE IF EXISTS `ninos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ninos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_madre` bigint(20) unsigned DEFAULT NULL,
  `establecimiento` varchar(150) DEFAULT NULL,
  `tipo_doc` varchar(10) DEFAULT NULL,
  `numero_doc` varchar(20) DEFAULT NULL,
  `apellidos_nombres` varchar(150) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `datos_extras` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ninos_id_madre_index` (`id_madre`),
  KEY `ninos_fecha_nacimiento_index` (`fecha_nacimiento`),
  KEY `ninos_numero_doc_index` (`numero_doc`),
  KEY `ninos_apellidos_nombres_index` (`apellidos_nombres`),
  CONSTRAINT `ninos_id_madre_foreign` FOREIGN KEY (`id_madre`) REFERENCES `madres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ninos`
--

LOCK TABLES `ninos` WRITE;
/*!40000 ALTER TABLE `ninos` DISABLE KEYS */;
INSERT INTO `ninos` VALUES (1,NULL,'Hospital Regional de Pucallpa','DNI','12345678','PÉREZ GARCÍA, JUAN CARLOS','2024-06-15','M',NULL);
/*!40000 ALTER TABLE `ninos` ENABLE KEYS */;
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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recien_nacidos`
--

DROP TABLE IF EXISTS `recien_nacidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recien_nacidos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_niño` bigint(20) unsigned NOT NULL,
  `peso` smallint(6) DEFAULT NULL,
  `edad_gestacional` int(11) DEFAULT NULL,
  `clasificacion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recien_nacidos_id_niño_foreign` (`id_niño`),
  CONSTRAINT `recien_nacidos_id_niño_foreign` FOREIGN KEY (`id_niño`) REFERENCES `ninos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recien_nacidos`
--

LOCK TABLES `recien_nacidos` WRITE;
/*!40000 ALTER TABLE `recien_nacidos` DISABLE KEYS */;
INSERT INTO `recien_nacidos` VALUES (2,1,3,38,'Normal');
/*!40000 ALTER TABLE `recien_nacidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitudes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_tipo_documento` int(11) NOT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `codigo_red` int(11) NOT NULL,
  `codigo_microred` varchar(255) NOT NULL,
  `id_establecimiento` varchar(255) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `cargo` varchar(255) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `accept_terms` tinyint(1) NOT NULL DEFAULT 0,
  `estado` enum('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `solicitudes_numero_documento_index` (`numero_documento`),
  KEY `solicitudes_estado_index` (`estado`),
  KEY `solicitudes_user_id_foreign` (`user_id`),
  CONSTRAINT `solicitudes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes`
--

LOCK TABLES `solicitudes` WRITE;
/*!40000 ALTER TABLE `solicitudes` DISABLE KEYS */;
INSERT INTO `solicitudes` VALUES (1,1,'73811019',1,'MICRORED AGUAYTIA','EST_PTDS_LOS_OLIVOS','Requiero una cuenta de jefe de microred','obstetra','951293509','vanesaruiz1267@gmail.com',1,'aprobada',NULL);
/*!40000 ALTER TABLE `solicitudes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tamizaje_neonatals`
--

DROP TABLE IF EXISTS `tamizaje_neonatals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tamizaje_neonatals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_niño` bigint(20) unsigned NOT NULL,
  `fecha_tam_neo` date DEFAULT NULL,
  `galen_fecha_tam_feo` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tamizaje_neonatals_id_niño_foreign` (`id_niño`),
  CONSTRAINT `tamizaje_neonatals_id_niño_foreign` FOREIGN KEY (`id_niño`) REFERENCES `ninos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tamizaje_neonatals`
--

LOCK TABLES `tamizaje_neonatals` WRITE;
/*!40000 ALTER TABLE `tamizaje_neonatals` DISABLE KEYS */;
INSERT INTO `tamizaje_neonatals` VALUES (7,1,'2024-06-20','2024-06-23');
/*!40000 ALTER TABLE `tamizaje_neonatals` ENABLE KEYS */;
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
  `role` varchar(255) NOT NULL DEFAULT 'usuario',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador DIRESA','diresa@siscadit.com','admin','2025-12-06 10:36:49','$2y$12$VR9MkZaIOed9Jb3.1nDcqer8tmuJxtOS8gcju0XlE/fnl3zKGjCKC',NULL),(2,'Administrador','admin@siscadit.com','admin','2025-12-06 10:36:50','$2y$12$dxycIPLOodK6OZ51HkjbAO59WeTw.gftVoRxgazNq3qhURegzRKIG',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacuna_rns`
--

DROP TABLE IF EXISTS `vacuna_rns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacuna_rns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_niño` bigint(20) unsigned NOT NULL,
  `fecha_bcg` date DEFAULT NULL,
  `fecha_hvb` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vacuna_rns_id_niño_foreign` (`id_niño`),
  CONSTRAINT `vacuna_rns_id_niño_foreign` FOREIGN KEY (`id_niño`) REFERENCES `ninos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacuna_rns`
--

LOCK TABLES `vacuna_rns` WRITE;
/*!40000 ALTER TABLE `vacuna_rns` DISABLE KEYS */;
INSERT INTO `vacuna_rns` VALUES (7,1,'2024-06-16','2024-06-16');
/*!40000 ALTER TABLE `vacuna_rns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visita_domiciliarias`
--

DROP TABLE IF EXISTS `visita_domiciliarias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visita_domiciliarias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_niño` bigint(20) unsigned NOT NULL,
  `control_de_visita` int(11) NOT NULL COMMENT '1=28 días, 2=60-150 días, 3=180-240 días, 4=270-330 días',
  `fecha_visita` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `visita_domiciliarias_id_niño_control_unique` (`id_niño`,`control_de_visita`),
  KEY `visita_domiciliarias_id_niño_index` (`id_niño`),
  KEY `visita_domiciliarias_fecha_visita_index` (`fecha_visita`),
  CONSTRAINT `visita_domiciliarias_id_niño_foreign` FOREIGN KEY (`id_niño`) REFERENCES `ninos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visita_domiciliarias`
--

LOCK TABLES `visita_domiciliarias` WRITE;
/*!40000 ALTER TABLE `visita_domiciliarias` DISABLE KEYS */;
INSERT INTO `visita_domiciliarias` VALUES (25,1,1,'2024-07-13'),(26,1,2,'2024-09-28'),(27,1,3,'2025-01-11'),(28,1,4,'2025-04-11');
/*!40000 ALTER TABLE `visita_domiciliarias` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-10 17:25:35
