-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: george_siscadit
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
) ENGINE=InnoDB AUTO_INCREMENT=617 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_menor1s`
--

LOCK TABLES `control_menor1s` WRITE;
/*!40000 ALTER TABLE `control_menor1s` DISABLE KEYS */;
INSERT INTO `control_menor1s` VALUES (78,2,1,'2025-08-24'),(79,2,2,'2025-09-19'),(80,2,3,'2025-10-06'),(81,2,4,'2025-11-11'),(82,2,5,'2025-12-13'),(83,2,6,'2025-12-28'),(84,2,7,'2026-02-05'),(85,2,8,'2026-03-12'),(86,2,9,'2026-04-02'),(87,2,10,'2026-04-24'),(88,2,11,'2026-06-01'),(89,3,1,'2025-03-24'),(90,3,2,'2025-04-07'),(91,3,3,'2025-05-20'),(92,3,4,'2025-06-09'),(93,3,5,'2025-07-03'),(94,3,6,'2025-08-22'),(95,3,7,'2025-09-08'),(96,3,8,'2025-10-10'),(97,3,9,'2025-11-04'),(98,3,10,'2025-12-08'),(99,3,11,'2026-01-05'),(100,4,1,'2026-01-10'),(101,4,2,'2026-02-06'),(102,4,3,'2026-03-24'),(103,4,4,'2026-04-08'),(104,4,5,'2026-05-05'),(105,4,6,'2026-06-05'),(106,4,7,'2026-07-08'),(107,4,8,'2026-08-04'),(108,4,9,'2026-09-22'),(109,4,10,'2026-10-26'),(110,4,11,'2026-10-29'),(111,5,1,'2025-05-30'),(112,5,2,'2025-06-26'),(113,5,3,'2025-07-29'),(114,5,4,'2025-08-18'),(115,5,5,'2025-09-28'),(116,5,6,'2025-10-18'),(117,5,7,'2025-11-12'),(118,5,8,'2025-12-20'),(119,5,9,'2026-01-06'),(120,5,10,'2026-02-26'),(121,5,11,'2026-03-24'),(122,6,1,'2025-03-09'),(123,6,2,'2025-05-02'),(124,6,3,'2025-05-20'),(125,6,4,'2025-06-12'),(126,6,5,'2025-07-18'),(127,6,6,'2025-08-14'),(128,6,7,'2025-09-26'),(129,6,8,'2025-10-17'),(130,6,9,'2025-11-06'),(131,6,10,'2025-12-09'),(132,6,11,'2026-01-13'),(133,7,1,'2025-04-23'),(134,7,2,'2025-05-21'),(135,7,3,'2025-06-08'),(136,7,4,'2025-08-03'),(137,7,5,'2025-09-04'),(138,7,6,'2025-09-15'),(139,7,7,'2025-10-12'),(140,7,8,'2025-11-18'),(141,7,9,'2025-12-22'),(142,7,10,'2026-01-12'),(143,7,11,'2026-02-16'),(144,8,1,'2025-08-05'),(145,8,2,'2025-09-06'),(146,8,3,'2025-09-25'),(147,8,4,'2025-11-12'),(148,8,5,'2025-11-25'),(149,8,6,'2026-01-08'),(150,8,7,'2026-02-04'),(151,8,8,'2026-03-06'),(152,8,9,'2026-03-27'),(153,8,10,'2026-04-29'),(154,8,11,'2026-06-11'),(155,9,1,'2026-01-04'),(156,9,2,'2026-02-13'),(157,9,3,'2026-03-22'),(158,9,4,'2026-04-19'),(159,9,5,'2026-04-26'),(160,9,6,'2026-06-11'),(161,9,7,'2026-07-15'),(162,9,8,'2026-08-01'),(163,9,9,'2026-09-06'),(164,9,10,'2026-10-01'),(165,9,11,'2026-11-15'),(166,10,1,'2025-07-27'),(167,10,2,'2025-08-30'),(168,10,3,'2025-09-28'),(169,10,4,'2025-11-05'),(170,10,5,'2025-11-28'),(171,10,6,'2025-12-28'),(172,10,7,'2026-02-22'),(173,10,8,'2026-03-12'),(174,10,9,'2026-04-20'),(175,10,10,'2026-05-11'),(176,10,11,'2026-06-19'),(177,11,1,'2025-08-18'),(178,11,2,'2025-10-12'),(179,11,3,'2025-10-26'),(180,11,4,'2025-12-02'),(181,11,5,'2026-01-05'),(182,11,6,'2026-01-23'),(183,11,7,'2026-03-02'),(184,11,8,'2026-03-22'),(185,11,9,'2026-04-21'),(186,11,10,'2026-05-18'),(187,11,11,'2026-07-12'),(188,12,1,'2025-11-30'),(189,12,2,'2025-12-25'),(190,12,3,'2026-02-10'),(191,12,4,'2026-02-26'),(192,12,5,'2026-03-25'),(193,12,6,'2026-04-26'),(194,12,7,'2026-06-08'),(195,12,8,'2026-06-23'),(196,12,9,'2026-08-05'),(197,12,10,'2026-09-01'),(198,12,11,'2026-09-19'),(199,13,1,'2025-07-28'),(200,13,2,'2025-08-02'),(201,13,3,'2025-09-02'),(202,13,4,'2025-10-20'),(203,13,5,'2025-11-01'),(204,13,6,'2025-12-15'),(205,13,7,'2026-01-06'),(206,13,8,'2026-02-13'),(207,13,9,'2026-03-05'),(208,13,10,'2026-04-02'),(209,13,11,'2026-05-09'),(210,14,1,'2025-12-12'),(211,14,2,'2026-01-06'),(212,14,3,'2026-01-31'),(213,14,4,'2026-02-21'),(214,14,5,'2026-04-17'),(215,14,6,'2026-04-28'),(216,14,7,'2026-05-30'),(217,14,8,'2026-06-26'),(218,14,9,'2026-08-03'),(219,14,10,'2026-08-24'),(220,14,11,'2026-10-12'),(221,15,1,'2025-07-11'),(222,15,2,'2025-09-04'),(223,15,3,'2025-09-29'),(224,15,4,'2025-10-16'),(225,15,5,'2025-11-19'),(226,15,6,'2025-12-29'),(227,15,7,'2026-01-07'),(228,15,8,'2026-03-04'),(229,15,9,'2026-03-31'),(230,15,10,'2026-04-26'),(231,15,11,'2026-06-02'),(232,16,1,'2025-08-09'),(233,16,2,'2025-08-29'),(234,16,3,'2025-10-01'),(235,16,4,'2025-11-02'),(236,16,5,'2025-12-07'),(237,16,6,'2025-12-25'),(238,16,7,'2026-01-22'),(239,16,8,'2026-02-28'),(240,16,9,'2026-04-07'),(241,16,10,'2026-04-22'),(242,16,11,'2026-06-17'),(243,17,1,'2025-05-19'),(244,17,2,'2025-06-02'),(245,17,3,'2025-07-08'),(246,17,4,'2025-08-10'),(247,17,5,'2025-09-04'),(248,17,6,'2025-10-13'),(249,17,7,'2025-11-17'),(250,17,8,'2025-12-08'),(251,17,9,'2026-01-02'),(252,17,10,'2026-02-17'),(253,17,11,'2026-03-13'),(254,18,1,'2025-12-19'),(255,18,2,'2026-01-18'),(256,18,3,'2026-02-06'),(257,18,4,'2026-04-03'),(258,18,5,'2026-04-11'),(259,18,6,'2026-06-04'),(260,18,7,'2026-06-19'),(261,18,8,'2026-07-27'),(262,18,9,'2026-08-30'),(263,18,10,'2026-09-09'),(264,18,11,'2026-10-15'),(265,19,1,'2025-10-07'),(266,19,2,'2025-10-30'),(267,19,3,'2025-11-21'),(268,19,4,'2026-01-10'),(269,19,5,'2026-01-21'),(270,19,6,'2026-02-28'),(271,19,7,'2026-03-31'),(272,19,8,'2026-04-27'),(273,19,9,'2026-05-29'),(274,19,10,'2026-06-11'),(275,19,11,'2026-07-11'),(276,20,1,'2025-07-10'),(277,20,2,'2025-08-18'),(278,20,3,'2025-09-03'),(279,20,4,'2025-10-24'),(280,20,5,'2025-11-10'),(281,20,6,'2025-11-29'),(282,20,7,'2025-12-25'),(283,20,8,'2026-02-11'),(284,20,9,'2026-03-09'),(285,20,10,'2026-04-17'),(286,20,11,'2026-05-23'),(287,21,1,'2025-06-04'),(288,21,2,'2025-06-18'),(289,21,3,'2025-08-08'),(290,21,4,'2025-08-20'),(291,21,5,'2025-09-23'),(292,21,6,'2025-10-21'),(293,21,7,'2025-11-15'),(294,21,8,'2025-12-17'),(295,21,9,'2026-02-04'),(296,21,10,'2026-02-12'),(297,21,11,'2026-03-26'),(298,22,1,'2026-01-17'),(299,22,2,'2026-02-19'),(300,22,3,'2026-03-15'),(301,22,4,'2026-04-23'),(302,22,5,'2026-04-29'),(303,22,6,'2026-06-11'),(304,22,7,'2026-06-25'),(305,22,8,'2026-08-13'),(306,22,9,'2026-09-08'),(307,22,10,'2026-10-07'),(308,22,11,'2026-11-01'),(309,23,1,'2025-12-22'),(310,23,2,'2026-01-11'),(311,23,3,'2026-02-28'),(312,23,4,'2026-03-30'),(313,23,5,'2026-05-03'),(314,23,6,'2026-05-07'),(315,23,7,'2026-06-14'),(316,23,8,'2026-07-24'),(317,23,9,'2026-08-16'),(318,23,10,'2026-09-25'),(319,23,11,'2026-10-19'),(320,24,1,'2025-04-05'),(321,24,2,'2025-05-08'),(322,24,3,'2025-06-04'),(323,24,4,'2025-06-30'),(324,24,5,'2025-07-23'),(325,24,6,'2025-08-24'),(326,24,7,'2025-10-03'),(327,24,8,'2025-10-25'),(328,24,9,'2025-11-25'),(329,24,10,'2026-01-04'),(330,24,11,'2026-01-31'),(331,25,1,'2025-02-04'),(332,25,2,'2025-03-27'),(333,25,3,'2025-04-27'),(334,25,4,'2025-05-12'),(335,25,5,'2025-06-15'),(336,25,6,'2025-07-06'),(337,25,7,'2025-08-26'),(338,25,8,'2025-09-13'),(339,25,9,'2025-10-18'),(340,25,10,'2025-11-11'),(341,25,11,'2025-12-24'),(342,26,1,'2025-02-21'),(343,26,2,'2025-03-17'),(344,26,3,'2025-04-06'),(345,26,4,'2025-05-15'),(346,26,5,'2025-06-15'),(347,26,6,'2025-07-12'),(348,26,7,'2025-08-16'),(349,26,8,'2025-09-16'),(350,26,9,'2025-09-21'),(351,26,10,'2025-10-24'),(352,26,11,'2025-12-12'),(353,27,1,'2025-05-30'),(354,27,2,'2025-06-18'),(355,27,3,'2025-07-15'),(356,27,4,'2025-08-22'),(357,27,5,'2025-10-12'),(358,27,6,'2025-10-22'),(359,27,7,'2025-12-02'),(360,27,8,'2025-12-16'),(361,27,9,'2026-01-25'),(362,27,10,'2026-02-26'),(363,27,11,'2026-03-12'),(364,28,1,'2026-01-02'),(365,28,2,'2026-02-02'),(366,28,3,'2026-03-02'),(367,28,4,'2026-04-24'),(368,28,5,'2026-05-23'),(369,28,6,'2026-06-29'),(370,28,7,'2026-07-05'),(371,28,8,'2026-08-09'),(372,28,9,'2026-09-07'),(373,28,10,'2026-10-01'),(374,28,11,'2026-10-30'),(375,29,1,'2025-04-06'),(376,29,2,'2025-05-08'),(377,29,3,'2025-06-11'),(378,29,4,'2025-07-28'),(379,29,5,'2025-09-03'),(380,29,6,'2025-09-05'),(381,29,7,'2025-10-17'),(382,29,8,'2025-11-23'),(383,29,9,'2025-12-17'),(384,29,10,'2026-01-05'),(385,29,11,'2026-02-09'),(386,30,1,'2025-09-21'),(387,30,2,'2025-10-23'),(388,30,3,'2025-12-08'),(389,30,4,'2025-12-27'),(390,30,5,'2026-01-31'),(391,30,6,'2026-03-10'),(392,30,7,'2026-04-06'),(393,30,8,'2026-04-16'),(394,30,9,'2026-06-09'),(395,30,10,'2026-07-04'),(396,30,11,'2026-08-09'),(397,31,1,'2025-11-28'),(398,31,2,'2026-01-16'),(399,31,3,'2026-02-22'),(400,31,4,'2026-03-28'),(401,31,5,'2026-04-18'),(402,31,6,'2026-05-20'),(403,31,7,'2026-05-30'),(404,31,8,'2026-07-16'),(405,31,9,'2026-08-11'),(406,31,10,'2026-09-22'),(407,31,11,'2026-10-08'),(408,32,1,'2025-08-08'),(409,32,2,'2025-09-04'),(410,32,3,'2025-09-18'),(411,32,4,'2025-10-31'),(412,32,5,'2025-11-12'),(413,32,6,'2025-12-30'),(414,32,7,'2026-01-28'),(415,32,8,'2026-03-02'),(416,32,9,'2026-03-16'),(417,32,10,'2026-04-13'),(418,32,11,'2026-05-30'),(419,33,1,'2025-08-28'),(420,33,2,'2025-09-16'),(421,33,3,'2025-10-25'),(422,33,4,'2025-11-02'),(423,33,5,'2025-12-26'),(424,33,6,'2026-01-14'),(425,33,7,'2026-02-09'),(426,33,8,'2026-03-17'),(427,33,9,'2026-04-16'),(428,33,10,'2026-05-05'),(429,33,11,'2026-06-04'),(430,34,1,'2025-03-24'),(431,34,2,'2025-04-10'),(432,34,3,'2025-05-14'),(433,34,4,'2025-06-24'),(434,34,5,'2025-07-31'),(435,34,6,'2025-08-21'),(436,34,7,'2025-09-12'),(437,34,8,'2025-10-25'),(438,34,9,'2025-11-21'),(439,34,10,'2025-12-12'),(440,34,11,'2026-01-10'),(441,35,1,'2025-07-31'),(442,35,2,'2025-08-17'),(443,35,3,'2025-09-27'),(444,35,4,'2025-11-01'),(445,35,5,'2025-11-14'),(446,35,6,'2025-12-20'),(447,35,7,'2026-01-17'),(448,35,8,'2026-03-02'),(449,35,9,'2026-03-12'),(450,35,10,'2026-04-17'),(451,35,11,'2026-05-19'),(452,36,1,'2025-03-04'),(453,36,2,'2025-03-23'),(454,36,3,'2025-04-22'),(455,36,4,'2025-06-02'),(456,36,5,'2025-06-30'),(457,36,6,'2025-07-17'),(458,36,7,'2025-08-29'),(459,36,8,'2025-09-20'),(460,36,9,'2025-10-19'),(461,36,10,'2025-11-11'),(462,36,11,'2025-12-26'),(463,37,1,'2025-12-05'),(464,37,2,'2026-01-11'),(465,37,3,'2026-01-16'),(466,37,4,'2026-03-14'),(467,37,5,'2026-04-13'),(468,37,6,'2026-05-09'),(469,37,7,'2026-05-24'),(470,37,8,'2026-07-06'),(471,37,9,'2026-08-07'),(472,37,10,'2026-08-31'),(473,37,11,'2026-09-26'),(474,38,1,'2025-07-30'),(475,38,2,'2025-09-21'),(476,38,3,'2025-10-17'),(477,38,4,'2025-10-24'),(478,38,5,'2025-12-01'),(479,38,6,'2026-01-04'),(480,38,7,'2026-02-03'),(481,38,8,'2026-02-25'),(482,38,9,'2026-04-14'),(483,38,10,'2026-05-04'),(484,38,11,'2026-06-03'),(485,39,1,'2025-05-28'),(486,39,2,'2025-06-19'),(487,39,3,'2025-08-02'),(488,39,4,'2025-08-12'),(489,39,5,'2025-09-18'),(490,39,6,'2025-10-26'),(491,39,7,'2025-11-28'),(492,39,8,'2025-12-15'),(493,39,9,'2026-01-16'),(494,39,10,'2026-02-12'),(495,39,11,'2026-04-03'),(496,40,1,'2025-05-05'),(497,40,2,'2025-06-03'),(498,40,3,'2025-06-16'),(499,40,4,'2025-07-23'),(500,40,5,'2025-09-02'),(501,40,6,'2025-09-09'),(502,40,7,'2025-10-18'),(503,40,8,'2025-11-21'),(504,40,9,'2025-12-21'),(505,40,10,'2026-01-03'),(506,40,11,'2026-02-20'),(507,41,1,'2025-05-26'),(508,41,2,'2025-06-04'),(509,41,3,'2025-07-11'),(510,41,4,'2025-08-14'),(511,41,5,'2025-09-21'),(512,41,6,'2025-10-09'),(513,41,7,'2025-11-04'),(514,41,8,'2025-12-25'),(515,41,9,'2026-01-14'),(516,41,10,'2026-02-16'),(517,41,11,'2026-02-27'),(518,42,1,'2025-02-24'),(519,42,2,'2025-04-11'),(520,42,3,'2025-05-12'),(521,42,4,'2025-05-18'),(522,42,5,'2025-06-14'),(523,42,6,'2025-07-16'),(524,42,7,'2025-08-25'),(525,42,8,'2025-10-07'),(526,42,9,'2025-11-07'),(527,42,10,'2025-11-27'),(528,42,11,'2025-12-16'),(529,43,1,'2025-02-03'),(530,43,2,'2025-03-15'),(531,43,3,'2025-04-12'),(532,43,4,'2025-04-30'),(533,43,5,'2025-06-08'),(534,43,6,'2025-07-09'),(535,43,7,'2025-08-14'),(536,43,8,'2025-09-04'),(537,43,9,'2025-10-17'),(538,43,10,'2025-11-16'),(539,43,11,'2025-12-07'),(540,44,1,'2025-04-15'),(541,44,2,'2025-05-08'),(542,44,3,'2025-06-15'),(543,44,4,'2025-06-28'),(544,44,5,'2025-08-09'),(545,44,6,'2025-08-20'),(546,44,7,'2025-10-11'),(547,44,8,'2025-10-19'),(548,44,9,'2025-11-14'),(549,44,10,'2026-01-02'),(550,44,11,'2026-02-03'),(551,45,1,'2025-07-08'),(552,45,2,'2025-07-16'),(553,45,3,'2025-08-18'),(554,45,4,'2025-09-20'),(555,45,5,'2025-11-07'),(556,45,6,'2025-12-04'),(557,45,7,'2025-12-23'),(558,45,8,'2026-01-18'),(559,45,9,'2026-02-26'),(560,45,10,'2026-04-08'),(561,45,11,'2026-05-10'),(562,46,1,'2025-04-20'),(563,46,2,'2025-05-21'),(564,46,3,'2025-06-12'),(565,46,4,'2025-07-01'),(566,46,5,'2025-07-31'),(567,46,6,'2025-09-08'),(568,46,7,'2025-10-05'),(569,46,8,'2025-10-31'),(570,46,9,'2025-11-28'),(571,46,10,'2025-12-31'),(572,46,11,'2026-02-15'),(573,47,1,'2025-02-09'),(574,47,2,'2025-04-06'),(575,47,3,'2025-05-01'),(576,47,4,'2025-06-07'),(577,47,5,'2025-06-27'),(578,47,6,'2025-07-29'),(579,47,7,'2025-08-27'),(580,47,8,'2025-09-30'),(581,47,9,'2025-11-03'),(582,47,10,'2025-11-23'),(583,47,11,'2026-01-02'),(584,48,1,'2025-07-25'),(585,48,2,'2025-08-18'),(586,48,3,'2025-09-25'),(587,48,4,'2025-10-27'),(588,48,5,'2025-11-15'),(589,48,6,'2025-12-26'),(590,48,7,'2026-02-03'),(591,48,8,'2026-02-22'),(592,48,9,'2026-03-25'),(593,48,10,'2026-05-06'),(594,48,11,'2026-05-24'),(595,49,1,'2025-09-02'),(596,49,2,'2025-10-12'),(597,49,3,'2025-10-28'),(598,49,4,'2025-11-13'),(599,49,5,'2025-12-15'),(600,49,6,'2026-01-24'),(601,49,7,'2026-03-01'),(602,49,8,'2026-03-26'),(603,49,9,'2026-05-02'),(604,49,10,'2026-05-28'),(605,49,11,'2026-06-25');
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
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_rns`
--

LOCK TABLES `control_rns` WRITE;
/*!40000 ALTER TABLE `control_rns` DISABLE KEYS */;
INSERT INTO `control_rns` VALUES (29,2,1,'2025-06-30'),(30,2,2,'2025-07-04'),(31,2,3,'2025-07-15'),(32,2,4,'2025-07-22'),(33,3,1,'2025-02-07'),(34,3,2,'2025-02-11'),(35,3,3,'2025-02-16'),(36,3,4,'2025-02-28'),(37,4,1,'2025-12-08'),(38,4,2,'2025-12-09'),(39,4,3,'2025-12-21'),(40,4,4,'2025-12-23'),(41,5,1,'2025-04-10'),(42,5,2,'2025-04-15'),(43,5,3,'2025-04-22'),(44,5,4,'2025-05-03'),(45,6,1,'2025-02-08'),(46,6,2,'2025-02-15'),(47,6,3,'2025-02-21'),(48,6,4,'2025-03-02'),(49,7,1,'2025-03-11'),(50,7,2,'2025-03-18'),(51,7,3,'2025-03-23'),(52,7,4,'2025-04-02'),(53,8,1,'2025-06-24'),(54,8,2,'2025-06-28'),(55,8,3,'2025-07-02'),(56,8,4,'2025-07-13'),(57,9,1,'2025-11-27'),(58,9,2,'2025-12-02'),(59,9,3,'2025-12-12'),(60,9,4,'2025-12-15'),(61,10,1,'2025-06-30'),(62,10,2,'2025-07-06'),(63,10,3,'2025-07-12'),(64,10,4,'2025-07-25'),(65,11,1,'2025-07-21'),(66,11,2,'2025-07-31'),(67,11,3,'2025-08-07'),(68,11,4,'2025-08-13'),(69,12,1,'2025-10-23'),(70,12,2,'2025-10-28'),(71,12,3,'2025-11-05'),(72,12,4,'2025-11-12'),(73,13,1,'2025-06-08'),(74,13,2,'2025-06-12'),(75,13,3,'2025-06-22'),(76,13,4,'2025-06-23'),(77,14,1,'2025-10-27'),(78,14,2,'2025-10-28'),(79,14,3,'2025-11-10'),(80,14,4,'2025-11-11'),(81,15,1,'2025-06-12'),(82,15,2,'2025-06-18'),(83,15,3,'2025-06-24'),(84,15,4,'2025-07-01'),(85,16,1,'2025-06-25'),(86,16,2,'2025-07-02'),(87,16,3,'2025-07-10'),(88,16,4,'2025-07-17'),(89,17,1,'2025-03-30'),(90,17,2,'2025-04-06'),(91,17,3,'2025-04-12'),(92,17,4,'2025-04-21'),(93,18,1,'2025-11-13'),(94,18,2,'2025-11-20'),(95,18,3,'2025-11-25'),(96,18,4,'2025-11-30'),(97,19,1,'2025-08-18'),(98,19,2,'2025-08-25'),(99,19,3,'2025-09-01'),(100,19,4,'2025-09-05'),(101,20,1,'2025-05-31'),(102,20,2,'2025-06-07'),(103,20,3,'2025-06-14'),(104,20,4,'2025-06-22'),(105,21,1,'2025-04-18'),(106,21,2,'2025-04-27'),(107,21,3,'2025-05-04'),(108,21,4,'2025-05-13'),(109,22,1,'2025-11-30'),(110,22,2,'2025-12-03'),(111,22,3,'2025-12-16'),(112,22,4,'2025-12-20'),(113,23,1,'2025-11-10'),(114,23,2,'2025-11-17'),(115,23,3,'2025-11-24'),(116,23,4,'2025-12-04'),(117,24,1,'2025-02-22'),(118,24,2,'2025-02-28'),(119,24,3,'2025-03-04'),(120,24,4,'2025-03-14'),(121,25,1,'2025-01-08'),(122,25,2,'2025-01-10'),(123,25,3,'2025-01-17'),(124,25,4,'2025-01-26'),(125,26,1,'2024-12-27'),(126,26,2,'2025-01-04'),(127,26,3,'2025-01-13'),(128,26,4,'2025-01-21'),(129,27,1,'2025-04-19'),(130,27,2,'2025-04-26'),(131,27,3,'2025-05-01'),(132,27,4,'2025-05-11'),(133,28,1,'2025-12-08'),(134,28,2,'2025-12-15'),(135,28,3,'2025-12-17'),(136,28,4,'2025-12-27'),(137,29,1,'2025-03-13'),(138,29,2,'2025-03-21'),(139,29,3,'2025-03-28'),(140,29,4,'2025-03-30'),(141,30,1,'2025-08-22'),(142,30,2,'2025-08-25'),(143,30,3,'2025-09-05'),(144,30,4,'2025-09-13'),(145,31,1,'2025-11-04'),(146,31,2,'2025-11-06'),(147,31,3,'2025-11-13'),(148,31,4,'2025-11-24'),(149,32,1,'2025-06-17'),(150,32,2,'2025-06-21'),(151,32,3,'2025-06-28'),(152,32,4,'2025-07-03'),(153,33,1,'2025-07-05'),(154,33,2,'2025-07-10'),(155,33,3,'2025-07-19'),(156,33,4,'2025-07-25'),(157,34,1,'2025-02-12'),(158,34,2,'2025-02-20'),(159,34,3,'2025-03-01'),(160,34,4,'2025-03-07'),(161,35,1,'2025-06-16'),(162,35,2,'2025-06-22'),(163,35,3,'2025-06-26'),(164,35,4,'2025-07-06'),(165,36,1,'2025-01-10'),(166,36,2,'2025-01-17'),(167,36,3,'2025-01-24'),(168,36,4,'2025-01-29'),(169,37,1,'2025-10-23'),(170,37,2,'2025-10-27'),(171,37,3,'2025-11-06'),(172,37,4,'2025-11-09'),(173,38,1,'2025-06-29'),(174,38,2,'2025-07-07'),(175,38,3,'2025-07-09'),(176,38,4,'2025-07-16'),(177,39,1,'2025-04-16'),(178,39,2,'2025-04-25'),(179,39,3,'2025-05-03'),(180,39,4,'2025-05-05'),(181,40,1,'2025-03-10'),(182,40,2,'2025-03-21'),(183,40,3,'2025-03-27'),(184,40,4,'2025-04-04'),(185,41,1,'2025-04-03'),(186,41,2,'2025-04-07'),(187,41,3,'2025-04-20'),(188,41,4,'2025-04-28'),(189,42,1,'2025-01-19'),(190,42,2,'2025-01-26'),(191,42,3,'2025-01-30'),(192,42,4,'2025-02-07'),(193,43,1,'2025-01-01'),(194,43,2,'2025-01-11'),(195,43,3,'2025-01-16'),(196,43,4,'2025-01-22'),(197,44,1,'2025-02-20'),(198,44,2,'2025-02-26'),(199,44,3,'2025-03-08'),(200,44,4,'2025-03-15'),(201,45,1,'2025-05-21'),(202,45,2,'2025-05-29'),(203,45,3,'2025-05-30'),(204,45,4,'2025-06-08'),(205,46,1,'2025-03-05'),(206,46,2,'2025-03-08'),(207,46,3,'2025-03-15'),(208,46,4,'2025-03-23'),(209,47,1,'2025-01-13'),(210,47,2,'2025-01-18'),(211,47,3,'2025-01-26'),(212,47,4,'2025-01-31'),(213,48,1,'2025-06-20'),(214,48,2,'2025-06-25'),(215,48,3,'2025-07-05'),(216,48,4,'2025-07-08'),(217,49,1,'2025-07-20'),(218,49,2,'2025-07-24'),(219,49,3,'2025-07-30'),(220,49,4,'2025-08-11');
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
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datos_extras`
--

LOCK TABLES `datos_extras` WRITE;
/*!40000 ALTER TABLE `datos_extras` DISABLE KEYS */;
INSERT INTO `datos_extras` VALUES (15,2,'CENTRO DE SALUD YARINACOCHA','Microred Callería','Centro de Salud Yarinacocha','Callería','Coronel Portillo','Ucayali','ESSALUD','PAMI'),(16,3,'CENTRO DE SALUD YARINACOCHA','Microred Aguaytía','Centro de Salud Yarinacocha','Aguaytía','Padre Abad','Ucayali','ESSALUD','JUNTOS'),(17,4,'CENTRO DE SALUD CALLERíA','Microred Campoverde','Centro de Salud Callería','Campoverde','Coronel Portillo','Ucayali','FFAA','JUNTOS'),(18,5,'CENTRO DE SALUD AGUAYTíA','Microred Masisea','Centro de Salud Aguaytía','Masisea','Padre Abad','Ucayali','SIS','PAMI'),(19,6,'CENTRO DE SALUD AGUAYTíA','Microred Campoverde','Centro de Salud Aguaytía','Campoverde','Coronel Portillo','Ucayali','FFAA','JUNTOS'),(20,7,'POSTA MéDICA MASISEA','Microred Callería','Posta Médica Masisea','Callería','Padre Abad','Ucayali','FFAA','PAMI'),(21,8,'POSTA MéDICA MASISEA','Microred Aguaytía','Posta Médica Masisea','Aguaytía','Coronel Portillo','Ucayali','FFAA','PAMI'),(22,9,'CENTRO DE SALUD CALLERíA','Microred Campoverde','Centro de Salud Callería','Campoverde','Coronel Portillo','Ucayali','SIS','PAMI'),(23,10,'CENTRO DE SALUD YARINACOCHA','Microred Aguaytía','Centro de Salud Yarinacocha','Aguaytía','Atalaya','Ucayali','FFAA','JUNTOS'),(24,11,'HOSPITAL REGIONAL DE PUCALLPA','Microred Callería','Hospital Regional de Pucallpa','Callería','Padre Abad','Ucayali','Particular','JUNTOS'),(25,12,'HOSPITAL REGIONAL DE PUCALLPA','Microred Campoverde','Hospital Regional de Pucallpa','Campoverde','Coronel Portillo','Ucayali','SIS','CRED'),(26,13,'CENTRO DE SALUD AGUAYTíA','Microred Callería','Centro de Salud Aguaytía','Callería','Padre Abad','Ucayali','SIS','JUNTOS'),(27,14,'POSTA MéDICA MASISEA','Microred Campoverde','Posta Médica Masisea','Campoverde','Padre Abad','Ucayali','SIS','JUNTOS'),(28,15,'CENTRO DE SALUD YARINACOCHA','Microred Yarinacocha','Centro de Salud Yarinacocha','Yarinacocha','Coronel Portillo','Ucayali','FFAA','CRED'),(29,16,'POSTA MéDICA MASISEA','Microred Yarinacocha','Posta Médica Masisea','Yarinacocha','Coronel Portillo','Ucayali','ESSALUD','PAMI'),(30,17,'POSTA MéDICA MASISEA','Microred Masisea','Posta Médica Masisea','Masisea','Coronel Portillo','Ucayali','ESSALUD','JUNTOS'),(31,18,'HOSPITAL REGIONAL DE PUCALLPA','Microred Masisea','Hospital Regional de Pucallpa','Masisea','Atalaya','Ucayali','ESSALUD','JUNTOS'),(32,19,'CENTRO DE SALUD CALLERíA','Microred Campoverde','Centro de Salud Callería','Campoverde','Atalaya','Ucayali','Particular','JUNTOS'),(33,20,'CENTRO DE SALUD AGUAYTíA','Microred Campoverde','Centro de Salud Aguaytía','Campoverde','Coronel Portillo','Ucayali','SIS','PAMI'),(34,21,'HOSPITAL REGIONAL DE PUCALLPA','Microred Yarinacocha','Hospital Regional de Pucallpa','Yarinacocha','Atalaya','Ucayali','FFAA','JUNTOS'),(35,22,'HOSPITAL REGIONAL DE PUCALLPA','Microred Callería','Hospital Regional de Pucallpa','Callería','Coronel Portillo','Ucayali','ESSALUD','CRED'),(36,23,'CENTRO DE SALUD AGUAYTíA','Microred Yarinacocha','Centro de Salud Aguaytía','Yarinacocha','Atalaya','Ucayali','SIS','CRED'),(37,24,'CENTRO DE SALUD YARINACOCHA','Microred Masisea','Centro de Salud Yarinacocha','Masisea','Padre Abad','Ucayali','Particular','JUNTOS'),(38,25,'POSTA MéDICA MASISEA','Microred Callería','Posta Médica Masisea','Callería','Padre Abad','Ucayali','ESSALUD','PAMI'),(39,26,'CENTRO DE SALUD AGUAYTíA','Microred Callería','Centro de Salud Aguaytía','Callería','Coronel Portillo','Ucayali','ESSALUD','JUNTOS'),(40,27,'HOSPITAL REGIONAL DE PUCALLPA','Microred Masisea','Hospital Regional de Pucallpa','Masisea','Padre Abad','Ucayali','SIS','CRED'),(41,28,'CENTRO DE SALUD CALLERíA','Microred Yarinacocha','Centro de Salud Callería','Yarinacocha','Coronel Portillo','Ucayali','ESSALUD','JUNTOS'),(42,29,'CENTRO DE SALUD CALLERíA','Microred Yarinacocha','Centro de Salud Callería','Yarinacocha','Coronel Portillo','Ucayali','FFAA','PAMI'),(43,30,'CENTRO DE SALUD CALLERíA','Microred Yarinacocha','Centro de Salud Callería','Yarinacocha','Coronel Portillo','Ucayali','SIS','JUNTOS'),(44,31,'POSTA MéDICA MASISEA','Microred Callería','Posta Médica Masisea','Callería','Padre Abad','Ucayali','SIS','CRED'),(45,32,'POSTA MéDICA MASISEA','Microred Aguaytía','Posta Médica Masisea','Aguaytía','Padre Abad','Ucayali','FFAA','CRED'),(46,33,'POSTA MéDICA MASISEA','Microred Callería','Posta Médica Masisea','Callería','Coronel Portillo','Ucayali','SIS','PAMI'),(47,34,'CENTRO DE SALUD AGUAYTíA','Microred Callería','Centro de Salud Aguaytía','Callería','Coronel Portillo','Ucayali','Particular','PAMI'),(48,35,'CENTRO DE SALUD YARINACOCHA','Microred Aguaytía','Centro de Salud Yarinacocha','Aguaytía','Atalaya','Ucayali','SIS','CRED'),(49,36,'CENTRO DE SALUD AGUAYTíA','Microred Callería','Centro de Salud Aguaytía','Callería','Atalaya','Ucayali','Particular','JUNTOS'),(50,37,'CENTRO DE SALUD CALLERíA','Microred Masisea','Centro de Salud Callería','Masisea','Coronel Portillo','Ucayali','Particular','PAMI'),(51,38,'CENTRO DE SALUD CALLERíA','Microred Masisea','Centro de Salud Callería','Masisea','Coronel Portillo','Ucayali','Particular','CRED'),(52,39,'CENTRO DE SALUD AGUAYTíA','Microred Yarinacocha','Centro de Salud Aguaytía','Yarinacocha','Coronel Portillo','Ucayali','Particular','CRED'),(53,40,'HOSPITAL REGIONAL DE PUCALLPA','Microred Masisea','Hospital Regional de Pucallpa','Masisea','Coronel Portillo','Ucayali','SIS','CRED'),(54,41,'POSTA MéDICA MASISEA','Microred Masisea','Posta Médica Masisea','Masisea','Atalaya','Ucayali','SIS','CRED'),(55,42,'CENTRO DE SALUD AGUAYTíA','Microred Campoverde','Centro de Salud Aguaytía','Campoverde','Coronel Portillo','Ucayali','SIS','JUNTOS'),(56,43,'CENTRO DE SALUD AGUAYTíA','Microred Aguaytía','Centro de Salud Aguaytía','Aguaytía','Padre Abad','Ucayali','FFAA','PAMI'),(57,44,'CENTRO DE SALUD AGUAYTíA','Microred Aguaytía','Centro de Salud Aguaytía','Aguaytía','Padre Abad','Ucayali','FFAA','PAMI'),(58,45,'CENTRO DE SALUD CALLERíA','Microred Masisea','Centro de Salud Callería','Masisea','Coronel Portillo','Ucayali','ESSALUD','PAMI'),(59,46,'HOSPITAL REGIONAL DE PUCALLPA','Microred Campoverde','Hospital Regional de Pucallpa','Campoverde','Atalaya','Ucayali','ESSALUD','PAMI'),(60,47,'CENTRO DE SALUD YARINACOCHA','Microred Callería','Centro de Salud Yarinacocha','Callería','Padre Abad','Ucayali','Particular','CRED'),(61,48,'POSTA MéDICA MASISEA','Microred Campoverde','Posta Médica Masisea','Campoverde','Atalaya','Ucayali','SIS','PAMI'),(62,49,'CENTRO DE SALUD CALLERíA','Microred Masisea','Centro de Salud Callería','Masisea','Padre Abad','Ucayali','Particular','CRED'),(64,51,'AGUAYTIA','MICRORED AGUAYTIA','AGUAYTIA','calleria','coronel portillo','Ucayali','ESSALUD','PIANE');
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
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `madres`
--

LOCK TABLES `madres` WRITE;
/*!40000 ALTER TABLE `madres` DISABLE KEYS */;
INSERT INTO `madres` VALUES (15,2,'55472564','SÁNCHEZ CRUZ, CARLOS','944068195','Jr. JOSÉ 460','Frente a la plaza'),(16,3,'30116918','GONZÁLEZ SÁNCHEZ, LUIS','916595212','Jr. PEDRO 328','Frente a la plaza'),(17,4,'69343935','FLORES RIVERA, PEDRO','936751677','Jr. SUSANA 689','Frente a el colegio'),(18,5,'29059593','MORALES CHÁVEZ, CARLOS','953987210','Jr. ELENA 872','Frente a el colegio'),(19,6,'40157107','CHÁVEZ RAMÍREZ, RICARDO','925458199','Jr. MIGUEL 781','Frente a la iglesia'),(20,7,'29435914','TORRES CHÁVEZ, ANA','942653729','Jr. MARÍA 750','Frente a la plaza'),(21,8,'12059345','GUTIÉRREZ LÓPEZ, LUIS','926077347','Jr. ROSA 911','Frente a la plaza'),(22,9,'81709423','GONZÁLEZ MORALES, ANTONIO','985664211','Jr. MARTA 335','Frente a el colegio'),(23,10,'36378051','GARCÍA LÓPEZ, MANUEL','980365831','Jr. PATRICIA 670','Frente a el colegio'),(24,11,'50221969','GÓMEZ GUTIÉRREZ, MIGUEL','985965227','Jr. MARÍA 965','Frente a la plaza'),(25,12,'45073087','MORALES PÉREZ, RICARDO','937099011','Jr. CARLOS 880','Frente a la iglesia'),(26,13,'75528077','SÁNCHEZ FLORES, ROSA','965892285','Jr. FRANCISCO 972','Frente a la iglesia'),(27,14,'37839049','GUTIÉRREZ CRUZ, ROSA','999443520','Jr. FRANCISCO 407','Frente a la iglesia'),(28,15,'93909426','CRUZ GÓMEZ, SUSANA','940340665','Jr. CARLOS 177','Frente a el mercado'),(29,16,'80453784','MORALES RUIZ, MANUEL','983486454','Jr. FRANCISCO 744','Frente a la plaza'),(30,17,'83385967','GONZÁLEZ RODRÍGUEZ, RICARDO','978841712','Jr. SUSANA 500','Frente a la plaza'),(31,18,'37968935','RIVERA ORTIZ, ELENA','987945789','Jr. JESÚS 240','Frente a el mercado'),(32,19,'72175229','RIVERA TORRES, JUAN','961365642','Jr. ROSA 590','Frente a el mercado'),(33,20,'81422562','GONZÁLEZ FLORES, SUSANA','951088281','Jr. JOSÉ 784','Frente a el mercado'),(34,21,'54437069','MARTÍNEZ PÉREZ, SUSANA','939085483','Jr. CARLOS 951','Frente a el mercado'),(35,22,'45536974','GÓMEZ CHÁVEZ, MANUEL','992759631','Jr. MANUEL 372','Frente a la plaza'),(36,23,'38030601','TORRES CRUZ, PATRICIA','917827779','Jr. JESÚS 242','Frente a la iglesia'),(37,24,'12686660','LÓPEZ SÁNCHEZ, LAURA','977290213','Jr. ANTONIO 709','Frente a el mercado'),(38,25,'87456285','GARCÍA LÓPEZ, FRANCISCO','910376333','Jr. ANA 926','Frente a el mercado'),(39,26,'56615094','FLORES LÓPEZ, ANA','970887999','Jr. JESÚS 662','Frente a el colegio'),(40,27,'55774988','GUTIÉRREZ GONZÁLEZ, JESÚS','911080404','Jr. FRANCISCO 230','Frente a el colegio'),(41,28,'30504878','TORRES DÍAZ, FRANCISCO','946608155','Jr. JESÚS 655','Frente a el mercado'),(42,29,'55678659','MENDOZA FLORES, LAURA','985140513','Jr. JUAN 628','Frente a la iglesia'),(43,30,'54519040','GÓMEZ RAMÍREZ, LAURA','985280587','Jr. PEDRO 624','Frente a el colegio'),(44,31,'47333214','MARTÍNEZ GONZÁLEZ, JOSÉ','935807381','Jr. MANUEL 786','Frente a la plaza'),(45,32,'93721053','RAMÍREZ PÉREZ, LAURA','996018511','Jr. JESÚS 439','Frente a la plaza'),(46,33,'76961852','DÍAZ GONZÁLEZ, RICARDO','918065848','Jr. JOSÉ 409','Frente a el colegio'),(47,34,'91985924','GARCÍA TORRES, LAURA','952971243','Jr. MIGUEL 894','Frente a la iglesia'),(48,35,'83883579','RAMÍREZ SÁNCHEZ, CARMEN','935573104','Jr. ELENA 168','Frente a la iglesia'),(49,36,'97476303','PÉREZ GÓMEZ, RICARDO','937694924','Jr. MANUEL 953','Frente a el colegio'),(50,37,'36982424','MENDOZA PÉREZ, ELENA','944661524','Jr. SUSANA 610','Frente a la iglesia'),(51,38,'23044539','GARCÍA TORRES, FRANCISCO','994092145','Jr. ANTONIO 698','Frente a el colegio'),(52,39,'33116289','PÉREZ GUTIÉRREZ, JOSÉ','926934545','Jr. MARTA 898','Frente a el colegio'),(53,40,'48022056','SÁNCHEZ MORALES, CARLOS','954934477','Jr. MARTA 701','Frente a el colegio'),(54,41,'99327591','CRUZ GARCÍA, SUSANA','947778331','Jr. MARÍA 301','Frente a la plaza'),(55,42,'77502739','GONZÁLEZ MARTÍNEZ, FRANCISCO','935356381','Jr. JESÚS 960','Frente a la plaza'),(56,43,'33367360','DÍAZ GARCÍA, MIGUEL','964787790','Jr. JESÚS 200','Frente a la plaza'),(57,44,'42979642','PÉREZ DÍAZ, ANTONIO','990097287','Jr. JUAN 854','Frente a la plaza'),(58,45,'70451445','MARTÍNEZ RODRÍGUEZ, ROSA','917548554','Jr. ELENA 391','Frente a la plaza'),(59,46,'32980572','FLORES RIVERA, RICARDO','951472767','Jr. CARLOS 330','Frente a la plaza'),(60,47,'81195917','GÓMEZ DÍAZ, FRANCISCO','990084825','Jr. PEDRO 284','Frente a la plaza'),(61,48,'68008005','FLORES RUIZ, ANTONIO','942720916','Jr. ROSA 945','Frente a la plaza'),(62,49,'60512688','TORRES GUTIÉRREZ, MANUEL','961620985','Jr. FRANCISCO 151','Frente a el mercado'),(64,51,'31231233','Elvita cruz ruiz','961456795','123213213123','al costado del colegio pedro portillo');
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ninos`
--

LOCK TABLES `ninos` WRITE;
/*!40000 ALTER TABLE `ninos` DISABLE KEYS */;
INSERT INTO `ninos` VALUES (2,NULL,'Centro de Salud Yarinacocha','DNI','64241491','MARTÍNEZ FLORES, ROSA MARÍA','2025-06-27','M',NULL),(3,NULL,'Centro de Salud Yarinacocha','DNI','42931618','GARCÍA RODRÍGUEZ, PATRICIA ELENA','2025-02-02','M',NULL),(4,NULL,'Centro de Salud Callería','DNI','71294412','MENDOZA GARCÍA, PEDRO ANTONIO','2025-12-02','F',NULL),(5,NULL,'Centro de Salud Aguaytía','DNI','28628535','GONZÁLEZ ORTIZ, RICARDO ALBERTO','2025-04-07','F',NULL),(6,NULL,'Centro de Salud Aguaytía','DNI','45301921','RAMÍREZ FLORES, CARLOS ALBERTO','2025-02-06','M',NULL),(7,NULL,'Posta Médica Masisea','DNI','46488939','GÓMEZ GUTIÉRREZ, RICARDO ALBERTO','2025-03-09','F',NULL),(8,NULL,'Posta Médica Masisea','DNI','27421402','MENDOZA MENDOZA, LUIS FERNANDO','2025-06-18','M',NULL),(9,NULL,'Centro de Salud Callería','DNI','90949447','SÁNCHEZ RAMÍREZ, LUIS FERNANDO','2025-11-23','F',NULL),(10,NULL,'Centro de Salud Yarinacocha','DNI','45508983','GUTIÉRREZ PÉREZ, SOFÍA ANA','2025-06-28','F',NULL),(11,NULL,'Hospital Regional de Pucallpa','DNI','14639449','FLORES GONZÁLEZ, LAURA PATRICIA','2025-07-19','F',NULL),(12,NULL,'Hospital Regional de Pucallpa','DNI','97221409','RODRÍGUEZ GARCÍA, MARTA LUCÍA','2025-10-19','M',NULL),(13,NULL,'Centro de Salud Aguaytía','DNI','41160232','MENDOZA GUTIÉRREZ, PEDRO ANTONIO','2025-06-02','F',NULL),(14,NULL,'Posta Médica Masisea','DNI','30311434','GONZÁLEZ SÁNCHEZ, MARTA LUCÍA','2025-10-21','F',NULL),(15,NULL,'Centro de Salud Yarinacocha','DNI','57879673','RIVERA RODRÍGUEZ, ROSA MARÍA','2025-06-10','F',NULL),(16,NULL,'Posta Médica Masisea','DNI','74294710','CRUZ GARCÍA, ELENA CARMEN','2025-06-23','M',NULL),(17,NULL,'Posta Médica Masisea','DNI','31980470','MARTÍNEZ GÓMEZ, PATRICIA ELENA','2025-03-25','M',NULL),(18,NULL,'Hospital Regional de Pucallpa','DNI','64638869','CHÁVEZ MENDOZA, RICARDO ALBERTO','2025-11-08','M',NULL),(19,NULL,'Centro de Salud Callería','DNI','48618420','DÍAZ RIVERA, LAURA PATRICIA','2025-08-15','F',NULL),(20,NULL,'Centro de Salud Aguaytía','DNI','13328133','ORTIZ DÍAZ, ELENA CARMEN','2025-05-29','F',NULL),(21,NULL,'Hospital Regional de Pucallpa','DNI','93594094','TORRES LÓPEZ, ELENA CARMEN','2025-04-15','M',NULL),(22,NULL,'Hospital Regional de Pucallpa','DNI','68741102','RAMÍREZ MENDOZA, MARTA LUCÍA','2025-11-26','M',NULL),(23,NULL,'Centro de Salud Aguaytía','DNI','28997148','DÍAZ GARCÍA, SOFÍA ANA','2025-11-07','M',NULL),(24,NULL,'Centro de Salud Yarinacocha','DNI','16172604','GÓMEZ GUTIÉRREZ, ANA SOFÍA','2025-02-17','M',NULL),(25,NULL,'Posta Médica Masisea','DNI','99969510','CHÁVEZ SÁNCHEZ, ANTONIO JOSÉ','2025-01-03','F',NULL),(26,NULL,'Centro de Salud Aguaytía','DNI','64031685','LÓPEZ GARCÍA, MARTA LUCÍA','2024-12-25','F',NULL),(27,NULL,'Hospital Regional de Pucallpa','DNI','53859923','RAMÍREZ RUIZ, MIGUEL ÁNGEL','2025-04-16','F',NULL),(28,NULL,'Centro de Salud Callería','DNI','22448596','SÁNCHEZ PÉREZ, JUAN CARLOS','2025-12-02','M',NULL),(29,NULL,'Centro de Salud Callería','DNI','86646965','GÓMEZ FLORES, ANTONIO JOSÉ','2025-03-08','M',NULL),(30,NULL,'Centro de Salud Callería','DNI','99471531','GONZÁLEZ CRUZ, CARMEN ROSA','2025-08-16','F',NULL),(31,NULL,'Posta Médica Masisea','DNI','38913748','RUIZ GUTIÉRREZ, LAURA PATRICIA','2025-10-30','F',NULL),(32,NULL,'Posta Médica Masisea','DNI','33535754','CHÁVEZ ORTIZ, PEDRO ANTONIO','2025-06-11','M',NULL),(33,NULL,'Posta Médica Masisea','DNI','94177526','SÁNCHEZ ORTIZ, FRANCISCO JAVIER','2025-07-01','F',NULL),(34,NULL,'Centro de Salud Aguaytía','DNI','38298715','MARTÍNEZ PÉREZ, MIGUEL ÁNGEL','2025-02-09','F',NULL),(35,NULL,'Centro de Salud Yarinacocha','DNI','82975327','DÍAZ GUTIÉRREZ, MARÍA ELENA','2025-06-12','F',NULL),(36,NULL,'Centro de Salud Aguaytía','DNI','97494425','ORTIZ GONZÁLEZ, CARLOS ALBERTO','2025-01-06','F',NULL),(37,NULL,'Centro de Salud Callería','DNI','79001600','DÍAZ GÓMEZ, ELENA CARMEN','2025-10-18','M',NULL),(38,NULL,'Centro de Salud Callería','DNI','69074043','SÁNCHEZ LÓPEZ, CARLOS ALBERTO','2025-06-24','F',NULL),(39,NULL,'Centro de Salud Aguaytía','DNI','80417472','MORALES MORALES, ANTONIO JOSÉ','2025-04-13','F',NULL),(40,NULL,'Hospital Regional de Pucallpa','DNI','27449516','MORALES RUIZ, CARMEN ROSA','2025-03-08','F',NULL),(41,NULL,'Posta Médica Masisea','DNI','64842028','FLORES CHÁVEZ, CARMEN ROSA','2025-03-31','M',NULL),(42,NULL,'Centro de Salud Aguaytía','DNI','90575751','ORTIZ MENDOZA, SUSANA MARÍA','2025-01-13','M',NULL),(43,NULL,'Centro de Salud Aguaytía','DNI','99181128','FLORES SÁNCHEZ, MANUEL JESÚS','2024-12-29','F',NULL),(44,NULL,'Centro de Salud Aguaytía','DNI','52878427','LÓPEZ SÁNCHEZ, PEDRO ANTONIO','2025-02-16','M',NULL),(45,NULL,'Centro de Salud Callería','DNI','52643709','GÓMEZ GONZÁLEZ, LAURA PATRICIA','2025-05-16','F',NULL),(46,NULL,'Hospital Regional de Pucallpa','DNI','91897389','ORTIZ TORRES, SOFÍA ANA','2025-03-01','M',NULL),(47,NULL,'Centro de Salud Yarinacocha','DNI','48648589','PÉREZ MENDOZA, ROSA MARÍA','2025-01-09','M',NULL),(48,NULL,'Posta Médica Masisea','DNI','52762402','GARCÍA SÁNCHEZ, ANTONIO JOSÉ','2025-06-15','M',NULL),(49,NULL,'Centro de Salud Callería','DNI','58563708','RAMÍREZ RODRÍGUEZ, PATRICIA ELENA','2025-07-16','F',NULL),(51,NULL,'AGUAYTIA','DNI','73811019','george michael aragon davila44','2025-12-11','M',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recien_nacidos`
--

LOCK TABLES `recien_nacidos` WRITE;
/*!40000 ALTER TABLE `recien_nacidos` DISABLE KEYS */;
INSERT INTO `recien_nacidos` VALUES (3,2,3,38,'Normal'),(4,3,4,39,'Normal'),(5,4,4,42,'Normal'),(6,5,4,40,'Normal'),(7,6,3,41,'Normal'),(8,7,3,38,'Normal'),(9,8,4,37,'Normal'),(10,9,4,38,'Normal'),(11,10,4,42,'Normal'),(12,11,3,37,'Normal'),(13,12,3,40,'Normal'),(14,13,3,36,'Bajo Peso al Nacer y/o Prematuro'),(15,14,3,41,'Normal'),(16,15,3,40,'Normal'),(17,16,4,39,'Normal'),(18,17,4,41,'Normal'),(19,18,4,42,'Normal'),(20,19,3,37,'Normal'),(21,20,3,40,'Normal'),(22,21,3,39,'Normal'),(23,22,4,42,'Normal'),(24,23,3,39,'Normal'),(25,24,3,37,'Normal'),(26,25,3,37,'Normal'),(27,26,4,40,'Normal'),(28,27,4,36,'Bajo Peso al Nacer y/o Prematuro'),(29,28,3,39,'Normal'),(30,29,4,39,'Normal'),(31,30,3,42,'Normal'),(32,31,4,38,'Normal'),(33,32,3,38,'Normal'),(34,33,3,42,'Normal'),(35,34,4,41,'Normal'),(36,35,4,40,'Normal'),(37,36,3,40,'Normal'),(38,37,3,36,'Bajo Peso al Nacer y/o Prematuro'),(39,38,3,39,'Normal'),(40,39,4,41,'Normal'),(41,40,3,39,'Normal'),(42,41,3,37,'Normal'),(43,42,3,40,'Normal'),(44,43,3,36,'Bajo Peso al Nacer y/o Prematuro'),(45,44,3,39,'Normal'),(46,45,4,36,'Bajo Peso al Nacer y/o Prematuro'),(47,46,4,41,'Normal'),(48,47,3,40,'Normal'),(49,48,4,42,'Normal'),(50,49,4,39,'Normal');
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
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tamizaje_neonatals`
--

LOCK TABLES `tamizaje_neonatals` WRITE;
/*!40000 ALTER TABLE `tamizaje_neonatals` DISABLE KEYS */;
INSERT INTO `tamizaje_neonatals` VALUES (8,2,'2025-07-03','2025-07-07'),(9,3,'2025-02-07','2025-02-09'),(10,4,'2025-12-09','2025-12-12'),(11,5,'2025-04-14','2025-04-17'),(12,6,'2025-02-12','2025-02-16'),(13,7,'2025-03-16','2025-03-20'),(14,8,'2025-06-24','2025-06-27'),(15,9,'2025-11-30','2025-12-04'),(16,10,'2025-07-04','2025-07-08'),(17,11,'2025-07-26','2025-07-29'),(18,12,'2025-10-24','2025-10-28'),(19,13,'2025-06-08','2025-06-12'),(20,14,'2025-10-27','2025-10-30'),(21,15,'2025-06-17','2025-06-21'),(22,16,'2025-06-29','2025-07-01'),(23,17,'2025-03-31','2025-04-03'),(24,18,'2025-11-13','2025-11-17'),(25,19,'2025-08-21','2025-08-25'),(26,20,'2025-06-05','2025-06-07'),(27,21,'2025-04-21','2025-04-25'),(28,22,'2025-12-01','2025-12-03'),(29,23,'2025-11-12','2025-11-14'),(30,24,'2025-02-22','2025-02-26'),(31,25,'2025-01-09','2025-01-11'),(32,26,'2025-01-01','2025-01-03'),(33,27,'2025-04-21','2025-04-25'),(34,28,'2025-12-09','2025-12-12'),(35,29,'2025-03-13','2025-03-16'),(36,30,'2025-08-22','2025-08-26'),(37,31,'2025-11-04','2025-11-07'),(38,32,'2025-06-16','2025-06-18'),(39,33,'2025-07-06','2025-07-08'),(40,34,'2025-02-16','2025-02-18'),(41,35,'2025-06-19','2025-06-21'),(42,36,'2025-01-12','2025-01-16'),(43,37,'2025-10-25','2025-10-27'),(44,38,'2025-07-01','2025-07-04'),(45,39,'2025-04-18','2025-04-20'),(46,40,'2025-03-14','2025-03-17'),(47,41,'2025-04-05','2025-04-07'),(48,42,'2025-01-18','2025-01-22'),(49,43,'2025-01-05','2025-01-08'),(50,44,'2025-02-23','2025-02-25'),(51,45,'2025-05-22','2025-05-25'),(52,46,'2025-03-06','2025-03-10'),(53,47,'2025-01-14','2025-01-18'),(54,48,'2025-06-21','2025-06-23'),(55,49,'2025-07-21','2025-07-25');
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
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacuna_rns`
--

LOCK TABLES `vacuna_rns` WRITE;
/*!40000 ALTER TABLE `vacuna_rns` DISABLE KEYS */;
INSERT INTO `vacuna_rns` VALUES (8,2,'2025-06-29','2025-06-27'),(9,3,'2025-02-03','2025-02-02'),(10,4,'2025-12-03','2025-12-04'),(11,5,'2025-04-07','2025-04-07'),(12,6,'2025-02-06','2025-02-07'),(13,7,'2025-03-10','2025-03-11'),(14,8,'2025-06-18','2025-06-20'),(15,9,'2025-11-24','2025-11-24'),(16,10,'2025-06-30','2025-06-30'),(17,11,'2025-07-20','2025-07-21'),(18,12,'2025-10-21','2025-10-20'),(19,13,'2025-06-03','2025-06-02'),(20,14,'2025-10-23','2025-10-23'),(21,15,'2025-06-10','2025-06-11'),(22,16,'2025-06-23','2025-06-24'),(23,17,'2025-03-25','2025-03-27'),(24,18,'2025-11-08','2025-11-09'),(25,19,'2025-08-17','2025-08-16'),(26,20,'2025-05-29','2025-05-30'),(27,21,'2025-04-17','2025-04-15'),(28,22,'2025-11-27','2025-11-26'),(29,23,'2025-11-09','2025-11-08'),(30,24,'2025-02-18','2025-02-19'),(31,25,'2025-01-03','2025-01-03'),(32,26,'2024-12-27','2024-12-26'),(33,27,'2025-04-17','2025-04-18'),(34,28,'2025-12-02','2025-12-04'),(35,29,'2025-03-09','2025-03-10'),(36,30,'2025-08-16','2025-08-18'),(37,31,'2025-10-31','2025-11-01'),(38,32,'2025-06-11','2025-06-11'),(39,33,'2025-07-03','2025-07-03'),(40,34,'2025-02-10','2025-02-10'),(41,35,'2025-06-12','2025-06-12'),(42,36,'2025-01-08','2025-01-06'),(43,37,'2025-10-18','2025-10-19'),(44,38,'2025-06-24','2025-06-24'),(45,39,'2025-04-13','2025-04-13'),(46,40,'2025-03-10','2025-03-09'),(47,41,'2025-04-02','2025-04-02'),(48,42,'2025-01-14','2025-01-13'),(49,43,'2024-12-29','2024-12-31'),(50,44,'2025-02-18','2025-02-16'),(51,45,'2025-05-16','2025-05-16'),(52,46,'2025-03-02','2025-03-02'),(53,47,'2025-01-11','2025-01-10'),(54,48,'2025-06-16','2025-06-15'),(55,49,'2025-07-16','2025-07-16');
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
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visita_domiciliarias`
--

LOCK TABLES `visita_domiciliarias` WRITE;
/*!40000 ALTER TABLE `visita_domiciliarias` DISABLE KEYS */;
INSERT INTO `visita_domiciliarias` VALUES (29,2,1,'2025-07-25'),(30,2,2,'2025-09-15'),(31,2,3,'2026-01-26'),(32,2,4,'2026-04-28'),(33,3,1,'2025-03-02'),(34,3,2,'2025-04-11'),(35,3,3,'2025-09-13'),(36,3,4,'2025-10-30'),(37,4,1,'2025-12-30'),(38,4,2,'2026-04-04'),(39,4,3,'2026-06-14'),(40,4,4,'2026-09-11'),(41,5,1,'2025-05-05'),(42,5,2,'2025-07-29'),(43,5,3,'2025-10-05'),(44,5,4,'2026-01-11'),(45,6,1,'2025-03-06'),(46,6,2,'2025-05-12'),(47,6,3,'2025-09-13'),(48,6,4,'2025-12-31'),(49,7,1,'2025-04-06'),(50,7,2,'2025-07-13'),(51,7,3,'2025-10-31'),(52,7,4,'2026-01-15'),(53,8,1,'2025-07-16'),(54,8,2,'2025-08-23'),(55,8,3,'2025-12-25'),(56,8,4,'2026-04-15'),(57,9,1,'2025-12-21'),(58,9,2,'2026-04-21'),(59,9,3,'2026-06-15'),(60,9,4,'2026-09-18'),(61,10,1,'2025-07-26'),(62,10,2,'2025-11-11'),(63,10,3,'2026-01-07'),(64,10,4,'2026-04-20'),(65,11,1,'2025-08-16'),(66,11,2,'2025-10-07'),(67,11,3,'2026-01-24'),(68,11,4,'2026-05-23'),(69,12,1,'2025-11-16'),(70,12,2,'2026-03-15'),(71,12,3,'2026-05-31'),(72,12,4,'2026-07-26'),(73,13,1,'2025-06-30'),(74,13,2,'2025-10-30'),(75,13,3,'2025-12-28'),(76,13,4,'2026-03-09'),(77,14,1,'2025-11-18'),(78,14,2,'2026-02-14'),(79,14,3,'2026-05-03'),(80,14,4,'2026-08-22'),(81,15,1,'2025-07-08'),(82,15,2,'2025-10-29'),(83,15,3,'2026-01-18'),(84,15,4,'2026-03-28'),(85,16,1,'2025-07-21'),(86,16,2,'2025-08-23'),(87,16,3,'2026-01-14'),(88,16,4,'2026-04-05'),(89,17,1,'2025-04-22'),(90,17,2,'2025-06-30'),(91,17,3,'2025-10-24'),(92,17,4,'2026-01-14'),(93,18,1,'2025-12-06'),(94,18,2,'2026-03-10'),(95,18,3,'2026-06-15'),(96,18,4,'2026-08-10'),(97,19,1,'2025-09-12'),(98,19,2,'2025-12-17'),(99,19,3,'2026-02-17'),(100,19,4,'2026-06-23'),(101,20,1,'2025-06-26'),(102,20,2,'2025-09-20'),(103,20,3,'2025-12-27'),(104,20,4,'2026-03-20'),(105,21,1,'2025-05-13'),(106,21,2,'2025-07-11'),(107,21,3,'2025-10-21'),(108,21,4,'2026-01-18'),(109,22,1,'2025-12-24'),(110,22,2,'2026-04-12'),(111,22,3,'2026-07-23'),(112,22,4,'2026-09-16'),(113,23,1,'2025-12-05'),(114,23,2,'2026-03-25'),(115,23,3,'2026-06-03'),(116,23,4,'2026-08-23'),(117,24,1,'2025-03-17'),(118,24,2,'2025-06-26'),(119,24,3,'2025-08-20'),(120,24,4,'2025-11-27'),(121,25,1,'2025-01-31'),(122,25,2,'2025-05-21'),(123,25,3,'2025-07-29'),(124,25,4,'2025-10-01'),(125,26,1,'2025-01-22'),(126,26,2,'2025-02-24'),(127,26,3,'2025-07-19'),(128,26,4,'2025-11-07'),(129,27,1,'2025-05-14'),(130,27,2,'2025-08-18'),(131,27,3,'2025-11-02'),(132,27,4,'2026-02-17'),(133,28,1,'2025-12-30'),(134,28,2,'2026-02-26'),(135,28,3,'2026-07-23'),(136,28,4,'2026-09-19'),(137,29,1,'2025-04-05'),(138,29,2,'2025-07-18'),(139,29,3,'2025-09-16'),(140,29,4,'2025-12-09'),(141,30,1,'2025-09-13'),(142,30,2,'2025-12-04'),(143,30,3,'2026-04-06'),(144,30,4,'2026-05-30'),(145,31,1,'2025-11-27'),(146,31,2,'2026-02-15'),(147,31,3,'2026-05-27'),(148,31,4,'2026-08-23'),(149,32,1,'2025-07-09'),(150,32,2,'2025-10-07'),(151,32,3,'2025-12-30'),(152,32,4,'2026-05-05'),(153,33,1,'2025-07-29'),(154,33,2,'2025-10-28'),(155,33,3,'2026-01-25'),(156,33,4,'2026-04-13'),(157,34,1,'2025-03-09'),(158,34,2,'2025-05-03'),(159,34,3,'2025-08-24'),(160,34,4,'2025-12-07'),(161,35,1,'2025-07-10'),(162,35,2,'2025-10-11'),(163,35,3,'2025-12-26'),(164,35,4,'2026-04-26'),(165,36,1,'2025-02-03'),(166,36,2,'2025-06-04'),(167,36,3,'2025-07-18'),(168,36,4,'2025-11-14'),(169,37,1,'2025-11-15'),(170,37,2,'2026-01-30'),(171,37,3,'2026-06-11'),(172,37,4,'2026-08-10'),(173,38,1,'2025-07-22'),(174,38,2,'2025-10-09'),(175,38,3,'2026-01-16'),(176,38,4,'2026-04-16'),(177,39,1,'2025-05-11'),(178,39,2,'2025-07-26'),(179,39,3,'2025-11-16'),(180,39,4,'2026-01-15'),(181,40,1,'2025-04-05'),(182,40,2,'2025-05-23'),(183,40,3,'2025-09-28'),(184,40,4,'2025-12-14'),(185,41,1,'2025-04-28'),(186,41,2,'2025-08-10'),(187,41,3,'2025-11-20'),(188,41,4,'2026-02-17'),(189,42,1,'2025-02-10'),(190,42,2,'2025-03-14'),(191,42,3,'2025-09-05'),(192,42,4,'2025-11-05'),(193,43,1,'2025-01-26'),(194,43,2,'2025-04-06'),(195,43,3,'2025-07-17'),(196,43,4,'2025-10-19'),(197,44,1,'2025-03-16'),(198,44,2,'2025-04-23'),(199,44,3,'2025-09-14'),(200,44,4,'2025-12-14'),(201,45,1,'2025-06-13'),(202,45,2,'2025-08-09'),(203,45,3,'2025-12-26'),(204,45,4,'2026-03-07'),(205,46,1,'2025-03-29'),(206,46,2,'2025-05-03'),(207,46,3,'2025-09-23'),(208,46,4,'2025-12-01'),(209,47,1,'2025-02-06'),(210,47,2,'2025-03-16'),(211,47,3,'2025-07-23'),(212,47,4,'2025-10-17'),(213,48,1,'2025-07-13'),(214,48,2,'2025-11-11'),(215,48,3,'2025-12-26'),(216,48,4,'2026-03-16'),(217,49,1,'2025-08-13'),(218,49,2,'2025-09-21'),(219,49,3,'2026-01-12'),(220,49,4,'2026-05-22');
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

-- Dump completed on 2025-12-11 10:21:33
