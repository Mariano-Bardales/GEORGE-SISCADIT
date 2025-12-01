-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: siscadit
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
-- Table structure for table `controles_menor1`
--

DROP TABLE IF EXISTS `controles_menor1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `controles_menor1` (
  `id_cred` int(11) NOT NULL AUTO_INCREMENT,
  `id_niño` int(11) DEFAULT NULL,
  `numero_control` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `estado_cred_once` varchar(20) DEFAULT NULL,
  `estado_cred_final` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_cred`),
  KEY `id_niño` (`id_niño`),
  CONSTRAINT `controles_menor1_ibfk_1` FOREIGN KEY (`id_niño`) REFERENCES `niños` (`id_niño`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `controles_rn`
--

DROP TABLE IF EXISTS `controles_rn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `controles_rn` (
  `id_crn` int(11) NOT NULL AUTO_INCREMENT,
  `id_niño` int(11) DEFAULT NULL,
  `numero_control` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_crn`),
  KEY `id_niño` (`id_niño`),
  CONSTRAINT `controles_rn_ibfk_1` FOREIGN KEY (`id_niño`) REFERENCES `niños` (`id_niño`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `datos_extra`
--

DROP TABLE IF EXISTS `datos_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datos_extra` (
  `id_extra` int(11) NOT NULL AUTO_INCREMENT,
  `id_niño` int(11) DEFAULT NULL,
  `red` varchar(100) DEFAULT NULL,
  `microred` varchar(100) DEFAULT NULL,
  `eess_nacimiento` varchar(150) DEFAULT NULL,
  `distrito` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `seguro` varchar(100) DEFAULT NULL,
  `programa` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_extra`),
  KEY `id_niño` (`id_niño`),
  CONSTRAINT `datos_extra_ibfk_1` FOREIGN KEY (`id_niño`) REFERENCES `niños` (`id_niño`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `madres`
--

DROP TABLE IF EXISTS `madres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `madres` (
  `id_madre` int(11) NOT NULL AUTO_INCREMENT,
  `dni` varchar(15) DEFAULT NULL,
  `apellidos_nombres` varchar(150) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `domicilio` text DEFAULT NULL,
  `referencia_direccion` text DEFAULT NULL,
  PRIMARY KEY (`id_madre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ninos`
--

DROP TABLE IF EXISTS `ninos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ninos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_madre` bigint(20) unsigned NOT NULL,
  `establecimiento` varchar(150) DEFAULT NULL,
  `tipo_doc` varchar(10) DEFAULT NULL,
  `numero_doc` varchar(20) DEFAULT NULL,
  `apellidos_nombres` varchar(150) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `edad_meses` int(11) DEFAULT NULL,
  `edad_dias` int(11) DEFAULT NULL,
  `datos_extras` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `niños`
--

DROP TABLE IF EXISTS `niños`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `niños` (
  `id_niño` int(11) NOT NULL AUTO_INCREMENT,
  `id_madre` int(11) DEFAULT NULL,
  `establecimiento` varchar(150) DEFAULT NULL,
  `tipo_doc` varchar(10) DEFAULT NULL,
  `numero_doc` varchar(20) DEFAULT NULL,
  `apellidos_nombres` varchar(150) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `edad_meses` int(11) DEFAULT NULL,
  `edad_dias` int(11) DEFAULT NULL,
  `datos_extras` text DEFAULT NULL,
  PRIMARY KEY (`id_niño`),
  KEY `id_madre` (`id_madre`),
  CONSTRAINT `niños_ibfk_1` FOREIGN KEY (`id_madre`) REFERENCES `madres` (`id_madre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `recien_nacido`
--

DROP TABLE IF EXISTS `recien_nacido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recien_nacido` (
  `id_rn` int(11) NOT NULL AUTO_INCREMENT,
  `id_niño` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `edad_gestacional` int(11) DEFAULT NULL,
  `clasificacion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_rn`),
  KEY `id_niño` (`id_niño`),
  CONSTRAINT `recien_nacido_ibfk_1` FOREIGN KEY (`id_niño`) REFERENCES `niños` (`id_niño`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `solicitudes_numero_documento_index` (`numero_documento`),
  KEY `solicitudes_estado_index` (`estado`),
  KEY `solicitudes_user_id_foreign` (`user_id`),
  CONSTRAINT `solicitudes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tamizaje_neonatal`
--

DROP TABLE IF EXISTS `tamizaje_neonatal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tamizaje_neonatal` (
  `id_tamizaje` int(11) NOT NULL AUTO_INCREMENT,
  `id_niño` int(11) DEFAULT NULL,
  `fecha_29_dias` date DEFAULT NULL,
  `fecha_tam_neo` date DEFAULT NULL,
  `edad_tam_neo` int(11) DEFAULT NULL,
  `galen_fecha_tam_feo` date DEFAULT NULL,
  `galen_dias_tam_feo` int(11) DEFAULT NULL,
  `cumple_tam_neo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_tamizaje`),
  KEY `id_niño` (`id_niño`),
  CONSTRAINT `tamizaje_neonatal_ibfk_1` FOREIGN KEY (`id_niño`) REFERENCES `niños` (`id_niño`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vacuna_rn`
--

DROP TABLE IF EXISTS `vacuna_rn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacuna_rn` (
  `id_vacuna` int(11) NOT NULL AUTO_INCREMENT,
  `id_niño` int(11) DEFAULT NULL,
  `fecha_bcg` date DEFAULT NULL,
  `edad_bcg` int(11) DEFAULT NULL,
  `estado_bcg` varchar(20) DEFAULT NULL,
  `fecha_hvb` date DEFAULT NULL,
  `edad_hvb` int(11) DEFAULT NULL,
  `estado_hvb` varchar(20) DEFAULT NULL,
  `cumple_BCG_HVB` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_vacuna`),
  KEY `id_niño` (`id_niño`),
  CONSTRAINT `vacuna_rn_ibfk_1` FOREIGN KEY (`id_niño`) REFERENCES `niños` (`id_niño`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `visitas_domiciliarias`
--

DROP TABLE IF EXISTS `visitas_domiciliarias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitas_domiciliarias` (
  `id_visita` int(11) NOT NULL AUTO_INCREMENT,
  `id_niño` int(11) DEFAULT NULL,
  `grupo_visita` varchar(2) DEFAULT NULL,
  `fecha_visita` date DEFAULT NULL,
  `numero_visitas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_visita`),
  KEY `id_niño` (`id_niño`),
  CONSTRAINT `visitas_domiciliarias_ibfk_1` FOREIGN KEY (`id_niño`) REFERENCES `niños` (`id_niño`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 14:10:44
