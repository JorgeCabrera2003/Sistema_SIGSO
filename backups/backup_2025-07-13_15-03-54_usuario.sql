-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: sigso_usuario
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
-- Current Database: `sigso_usuario`
--

/*!40000 DROP DATABASE IF EXISTS `sigso_usuario`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sigso_usuario` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `sigso_usuario`;

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacora` (
  `id_bitacora` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `modulo` varchar(45) NOT NULL,
  `accion_bitacora` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  PRIMARY KEY (`id_bitacora`),
  KEY `usuario` (`usuario`),
  CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`nombre_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8664 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (8662,'cabrerajorge','Backup','(cabrerajorge), Se generó un nuevo backup','2025-07-13','15:01:44'),(8663,'cabrerajorge','Backup','(cabrerajorge), Se eliminó un backup','2025-07-13','15:02:21');
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulo` (
  `id_modulo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_modulo` varchar(45) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
  PRIMARY KEY (`id_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'usuario'),(2,'rol'),(3,'bitacora'),(4,'mantenimiento'),(5,'empleado'),(6,'tecnico'),(7,'solicitud'),(8,'hoja_servicio'),(9,'ente'),(10,'dependencia'),(11,'unidad'),(12,'cargo'),(13,'tipo_servicio'),(14,'bien'),(15,'tipo_bien'),(16,'marca'),(17,'equipo'),(18,'switch'),(19,'patch_panel'),(20,'interconexion'),(21,'punto_conexion'),(22,'piso'),(23,'oficina'),(24,'material');
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion`
--

DROP TABLE IF EXISTS `notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notificacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `modulo` varchar(45) NOT NULL,
  `mensaje` varchar(200) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` varchar(45) NOT NULL DEFAULT 'Nuevo',
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`),
  CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`nombre_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion`
--

LOCK TABLES `notificacion` WRITE;
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
INSERT INTO `notificacion` VALUES (19,'cabrerajorge','Material','Nuevo material registrado por cabrerajorge: Conector RJ45','2025-07-07','19:52:49','Nuevo');
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `id_rol` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `accion_permiso` varchar(100) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_permiso`),
  KEY `id_rol` (`id_rol`),
  KEY `id_modulo` (`id_modulo`),
  CONSTRAINT `permiso_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permiso_ibfk_3` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1266 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
INSERT INTO `permiso` VALUES (1,1,1,'registrar',1),(2,1,1,'ver',1),(3,1,1,'modificar',1),(4,1,1,'eliminar',1),(5,1,2,'registrar',1),(6,1,2,'ver',1),(7,1,2,'modificar',1),(8,1,2,'eliminar',1),(9,1,3,'ver',1),(10,1,4,'ver',1),(11,1,4,'exportar',1),(12,1,4,'importar',1),(13,1,4,'eliminar',1),(14,1,5,'registrar',1),(15,1,5,'ver',1),(16,1,5,'modificar',1),(17,1,5,'eliminar',1),(18,1,6,'registrar',1),(19,1,6,'ver',1),(20,1,6,'modificar',1),(21,1,6,'eliminar',1),(22,1,7,'registrar',1),(23,1,7,'ver_solicitud',1),(24,1,7,'ver_mi_solicitud',1),(25,1,7,'modificar',1),(26,1,7,'eliminar',1),(27,1,8,'registrar',1),(28,1,8,'ver',1),(29,1,8,'modificar',1),(30,1,8,'eliminar',1),(31,1,9,'registrar',1),(32,1,9,'ver',1),(33,1,9,'modificar',1),(34,1,9,'eliminar',1),(35,1,10,'registrar',1),(36,1,10,'ver',1),(37,1,10,'modificar',1),(38,1,10,'eliminar',1),(39,1,11,'registrar',1),(40,1,11,'ver',1),(41,1,11,'modificar',1),(42,1,11,'eliminar',1),(43,1,12,'registrar',1),(44,1,12,'ver',1),(45,1,12,'modificar',1),(46,1,12,'eliminar',1),(47,1,13,'registrar',1),(48,1,13,'ver',1),(49,1,13,'modificar',1),(50,1,13,'eliminar',1),(51,1,14,'registrar',1),(52,1,14,'ver',1),(53,1,14,'modificar',1),(54,1,14,'eliminar',1),(55,1,15,'registrar',1),(56,1,15,'ver',1),(57,1,15,'modificar',1),(58,1,15,'eliminar',1),(59,1,16,'registrar',1),(60,1,16,'ver',1),(61,1,16,'modificar',1),(62,1,16,'eliminar',1),(63,1,17,'registrar',1),(64,1,17,'ver',1),(65,1,17,'modificar',1),(66,1,17,'eliminar',1),(67,1,18,'registrar',1),(68,1,18,'ver',1),(69,1,18,'modificar',1),(70,1,18,'eliminar',1),(71,1,19,'registrar',1),(72,1,19,'ver',1),(73,1,19,'modificar',1),(74,1,19,'eliminar',1),(75,1,20,'registrar',1),(76,1,20,'ver',1),(77,1,20,'modificar',1),(78,1,20,'eliminar',1),(79,1,21,'registrar',1),(80,1,21,'ver',1),(81,1,21,'modificar',1),(82,1,21,'eliminar',1),(83,1,22,'registrar',1),(84,1,22,'ver',1),(85,1,22,'modificar',1),(86,1,22,'eliminar',1),(87,1,23,'registrar',1),(88,1,23,'ver',1),(89,1,23,'modificar',1),(90,1,23,'eliminar',1),(91,1,24,'registrar',1),(92,1,24,'ver',1),(93,1,24,'modificar',1),(94,1,24,'eliminar',1),(95,2,1,'registrar',1),(96,2,1,'ver',1),(97,2,1,'modificar',1),(98,2,1,'eliminar',1),(99,2,2,'registrar',1),(100,2,2,'ver',1),(101,2,2,'modificar',1),(102,2,2,'eliminar',1),(103,2,3,'ver',1),(104,2,4,'ver',1),(105,2,4,'exportar',1),(106,2,4,'importar',1),(107,2,4,'eliminar',1),(108,2,5,'registrar',1),(109,2,5,'ver',1),(110,2,5,'modificar',1),(111,2,5,'eliminar',1),(112,2,6,'registrar',1),(113,2,6,'ver',1),(114,2,6,'modificar',1),(115,2,6,'eliminar',1),(116,2,7,'registrar',1),(117,2,7,'ver_solicitud',1),(118,2,7,'ver_mi_solicitud',1),(119,2,7,'modificar',1),(120,2,7,'eliminar',1),(121,2,8,'registrar',1),(122,2,8,'ver',1),(123,2,8,'modificar',1),(124,2,8,'eliminar',1),(125,2,9,'registrar',1),(126,2,9,'ver',1),(127,2,9,'modificar',1),(128,2,9,'eliminar',1),(129,2,10,'registrar',1),(130,2,10,'ver',1),(131,2,10,'modificar',1),(132,2,10,'eliminar',1),(133,2,11,'registrar',1),(134,2,11,'ver',1),(135,2,11,'modificar',1),(136,2,11,'eliminar',1),(137,2,12,'registrar',1),(138,2,12,'ver',1),(139,2,12,'modificar',1),(140,2,12,'eliminar',1),(141,2,13,'registrar',1),(142,2,13,'ver',1),(143,2,13,'modificar',1),(144,2,13,'eliminar',1),(145,2,14,'registrar',1),(146,2,14,'ver',1),(147,2,14,'modificar',1),(148,2,14,'eliminar',1),(149,2,15,'registrar',1),(150,2,15,'ver',1),(151,2,15,'modificar',1),(152,2,15,'eliminar',1),(153,2,16,'registrar',1),(154,2,16,'ver',1),(155,2,16,'modificar',1),(156,2,16,'eliminar',1),(157,2,17,'registrar',0),(158,2,17,'ver',0),(159,2,17,'modificar',0),(160,2,17,'eliminar',0),(161,2,18,'registrar',0),(162,2,18,'ver',0),(163,2,18,'modificar',0),(164,2,18,'eliminar',0),(165,2,19,'registrar',0),(166,2,19,'ver',0),(167,2,19,'modificar',0),(168,2,19,'eliminar',0),(169,2,20,'registrar',0),(170,2,20,'ver',0),(171,2,20,'modificar',0),(172,2,20,'eliminar',0),(173,2,21,'registrar',0),(174,2,21,'ver',0),(175,2,21,'modificar',0),(176,2,21,'eliminar',0),(177,2,22,'registrar',0),(178,2,22,'ver',0),(179,2,22,'modificar',0),(180,2,22,'eliminar',0),(181,2,23,'registrar',0),(182,2,23,'ver',0),(183,2,23,'modificar',0),(184,2,23,'eliminar',0),(185,2,24,'registrar',0),(186,2,24,'ver',0),(187,2,24,'modificar',0),(188,2,24,'eliminar',0),(189,3,1,'registrar',1),(190,3,1,'ver',1),(191,3,1,'modificar',1),(192,3,1,'eliminar',1),(193,3,2,'registrar',1),(194,3,2,'ver',1),(195,3,2,'modificar',1),(196,3,2,'eliminar',1),(197,3,3,'ver',1),(198,3,4,'ver',1),(199,3,4,'exportar',1),(200,3,4,'importar',1),(201,3,4,'eliminar',1),(202,3,5,'registrar',1),(203,3,5,'ver',1),(204,3,5,'modificar',1),(205,3,5,'eliminar',1),(206,3,6,'registrar',1),(207,3,6,'ver',1),(208,3,6,'modificar',1),(209,3,6,'eliminar',1),(210,3,7,'registrar',0),(211,3,7,'ver_solicitud',0),(212,3,7,'ver_mi_solicitud',0),(213,3,7,'modificar',0),(214,3,7,'eliminar',0),(215,3,8,'registrar',1),(216,3,8,'ver',1),(217,3,8,'modificar',1),(218,3,8,'eliminar',1),(219,3,9,'registrar',0),(220,3,9,'ver',0),(221,3,9,'modificar',0),(222,3,9,'eliminar',0),(223,3,10,'registrar',0),(224,3,10,'ver',0),(225,3,10,'modificar',0),(226,3,10,'eliminar',0),(227,3,11,'registrar',0),(228,3,11,'ver',0),(229,3,11,'modificar',0),(230,3,11,'eliminar',0),(231,3,12,'registrar',0),(232,3,12,'ver',0),(233,3,12,'modificar',0),(234,3,12,'eliminar',0),(235,3,13,'registrar',0),(236,3,13,'ver',0),(237,3,13,'modificar',0),(238,3,13,'eliminar',0),(239,3,14,'registrar',0),(240,3,14,'ver',0),(241,3,14,'modificar',0),(242,3,14,'eliminar',0),(243,3,15,'registrar',0),(244,3,15,'ver',0),(245,3,15,'modificar',0),(246,3,15,'eliminar',0),(247,3,16,'registrar',0),(248,3,16,'ver',0),(249,3,16,'modificar',0),(250,3,16,'eliminar',0),(251,3,17,'registrar',0),(252,3,17,'ver',0),(253,3,17,'modificar',0),(254,3,17,'eliminar',0),(255,3,18,'registrar',0),(256,3,18,'ver',0),(257,3,18,'modificar',0),(258,3,18,'eliminar',0),(259,3,19,'registrar',0),(260,3,19,'ver',0),(261,3,19,'modificar',0),(262,3,19,'eliminar',0),(263,3,20,'registrar',0),(264,3,20,'ver',0),(265,3,20,'modificar',0),(266,3,20,'eliminar',0),(267,3,21,'registrar',0),(268,3,21,'ver',0),(269,3,21,'modificar',0),(270,3,21,'eliminar',0),(271,3,22,'registrar',1),(272,3,22,'ver',1),(273,3,22,'modificar',1),(274,3,22,'eliminar',1),(275,3,23,'registrar',0),(276,3,23,'ver',0),(277,3,23,'modificar',0),(278,3,23,'eliminar',0),(279,3,24,'registrar',0),(280,3,24,'ver',0),(281,3,24,'modificar',0),(282,3,24,'eliminar',0),(398,1,1,'restaurar',1),(399,1,2,'restaurar',1),(400,1,5,'restaurar',1),(401,1,6,'restaurar',1),(402,1,7,'restaurar',1),(403,1,8,'restaurar',1),(404,1,9,'restaurar',1),(405,1,10,'restaurar',1),(406,1,11,'restaurar',1),(407,1,12,'restaurar',1),(408,1,13,'restaurar',1),(409,1,14,'restaurar',1),(410,1,15,'restaurar',1),(411,1,16,'restaurar',1),(412,1,17,'restaurar',1),(413,1,18,'restaurar',1),(414,1,19,'restaurar',1),(415,1,22,'restaurar',1),(416,1,23,'restaurar',1),(417,1,24,'restaurar',1),(418,2,1,'restaurar',1),(419,2,2,'restaurar',1),(420,2,5,'restaurar',0),(421,2,6,'restaurar',0),(422,2,7,'restaurar',0),(423,2,8,'restaurar',0),(424,2,9,'restaurar',0),(425,2,10,'restaurar',0),(426,2,11,'restaurar',0),(427,2,12,'restaurar',0),(428,2,13,'restaurar',1),(429,2,14,'restaurar',1),(430,2,15,'restaurar',1),(431,2,16,'restaurar',1),(432,2,17,'restaurar',0),(433,2,18,'restaurar',0),(434,2,19,'restaurar',0),(435,2,22,'restaurar',0),(436,2,23,'restaurar',0),(437,2,24,'restaurar',0),(894,1,17,'historial',0),(895,1,24,'historial',1),(1012,4,1,'registrar',0),(1013,4,1,'ver',0),(1014,4,1,'modificar',0),(1015,4,1,'eliminar',0),(1016,4,1,'restaurar',0),(1017,4,2,'registrar',0),(1018,4,2,'ver',0),(1019,4,2,'modificar',0),(1020,4,2,'eliminar',0),(1021,4,2,'restaurar',0),(1022,4,3,'ver',0),(1023,4,4,'ver',0),(1024,4,4,'exportar',0),(1025,4,4,'importar',0),(1026,4,4,'eliminar',0),(1027,4,5,'registrar',0),(1028,4,5,'ver',0),(1029,4,5,'modificar',0),(1030,4,5,'eliminar',0),(1031,4,5,'restaurar',0),(1032,4,6,'registrar',0),(1033,4,6,'ver',0),(1034,4,6,'modificar',0),(1035,4,6,'eliminar',0),(1036,4,6,'restaurar',0),(1037,4,7,'registrar',1),(1038,4,7,'ver_solicitud',1),(1039,4,7,'ver_mi_solicitud',1),(1040,4,7,'modificar',1),(1041,4,7,'eliminar',1),(1042,4,7,'restaurar',1),(1043,4,8,'registrar',1),(1044,4,8,'ver',1),(1045,4,8,'modificar',1),(1046,4,8,'eliminar',1),(1047,4,8,'restaurar',1),(1048,4,9,'registrar',0),(1049,4,9,'ver',0),(1050,4,9,'modificar',0),(1051,4,9,'eliminar',0),(1052,4,9,'restaurar',0),(1053,4,10,'registrar',0),(1054,4,10,'ver',0),(1055,4,10,'modificar',0),(1056,4,10,'eliminar',0),(1057,4,10,'restaurar',0),(1058,4,11,'registrar',0),(1059,4,11,'ver',0),(1060,4,11,'modificar',0),(1061,4,11,'eliminar',0),(1062,4,11,'restaurar',0),(1063,4,12,'registrar',0),(1064,4,12,'ver',0),(1065,4,12,'modificar',0),(1066,4,12,'eliminar',0),(1067,4,12,'restaurar',0),(1068,4,13,'registrar',0),(1069,4,13,'ver',0),(1070,4,13,'modificar',0),(1071,4,13,'eliminar',0),(1072,4,13,'restaurar',0),(1073,4,14,'registrar',0),(1074,4,14,'ver',0),(1075,4,14,'modificar',0),(1076,4,14,'eliminar',0),(1077,4,14,'restaurar',0),(1078,4,15,'registrar',0),(1079,4,15,'ver',0),(1080,4,15,'modificar',0),(1081,4,15,'eliminar',0),(1082,4,15,'restaurar',0),(1083,4,16,'registrar',0),(1084,4,16,'ver',0),(1085,4,16,'modificar',0),(1086,4,16,'eliminar',0),(1087,4,16,'restaurar',0),(1088,4,17,'registrar',0),(1089,4,17,'ver',0),(1090,4,17,'modificar',0),(1091,4,17,'eliminar',0),(1092,4,17,'historial',0),(1093,4,17,'restaurar',0),(1094,4,24,'registrar',0),(1095,4,24,'ver',0),(1096,4,24,'modificar',0),(1097,4,24,'eliminar',0),(1098,4,24,'historial',0),(1099,4,24,'restaurar',0),(1100,4,18,'registrar',0),(1101,4,18,'ver',0),(1102,4,18,'modificar',0),(1103,4,18,'eliminar',0),(1104,4,18,'restaurar',0),(1105,4,19,'registrar',0),(1106,4,19,'ver',0),(1107,4,19,'modificar',0),(1108,4,19,'eliminar',0),(1109,4,19,'restaurar',0),(1110,4,20,'registrar',0),(1111,4,20,'ver',0),(1112,4,20,'modificar',0),(1113,4,20,'eliminar',0),(1114,4,21,'registrar',0),(1115,4,21,'ver',0),(1116,4,21,'modificar',0),(1117,4,21,'eliminar',0),(1118,4,22,'registrar',0),(1119,4,22,'ver',0),(1120,4,22,'modificar',0),(1121,4,22,'eliminar',0),(1122,4,22,'restaurar',0),(1123,4,23,'registrar',0),(1124,4,23,'ver',0),(1125,4,23,'modificar',0),(1126,4,23,'eliminar',0),(1127,4,23,'restaurar',0),(1244,3,1,'restaurar',0),(1245,3,2,'restaurar',0),(1246,3,5,'restaurar',0),(1247,3,6,'restaurar',0),(1248,3,7,'restaurar',0),(1249,3,8,'restaurar',1),(1250,3,9,'restaurar',0),(1251,3,10,'restaurar',0),(1252,3,11,'restaurar',0),(1253,3,12,'restaurar',0),(1254,3,13,'restaurar',0),(1255,3,14,'restaurar',0),(1256,3,15,'restaurar',0),(1257,3,16,'restaurar',0),(1258,3,17,'historial',0),(1259,3,17,'restaurar',0),(1260,3,24,'historial',0),(1261,3,24,'restaurar',0),(1262,3,18,'restaurar',0),(1263,3,19,'restaurar',0),(1264,3,22,'restaurar',0),(1265,3,23,'restaurar',0);
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `estatus` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'SUPERUSUARIO',1),(2,'ADMINISTRADOR',1),(3,'TECNICO',1),(4,'SECRETARIA',1),(5,'SOLICITANTE',1);
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `nombre_usuario` varchar(45) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `nombres` varchar(65) NOT NULL,
  `apellidos` varchar(65) NOT NULL,
  `telefono` varchar(13) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `clave` varchar(128) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `estatus` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`nombre_usuario`),
  UNIQUE KEY `cedula` (`cedula`),
  UNIQUE KEY `cedula_2` (`cedula`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES ('cabrerajorge','V-31843937',1,'Jorge','Cabrera','0424-5567016','cabrerajorge2003@gmail.com','$2y$10$TmQDE1rEH.E3KthQ.0QEJOl7ccMlfeBcwCr8AKz.mzLlm5OgOptY6','',1),('frank30','V-30454597',2,'Frankling','Fonseca','0424-5041921','ranklinjavierfonsecavasquez@gmail.com','$2y$10$d64FtFMmW8sTyuiKyxD52eN0q9vdBEglqAbOJXUzw80aRB3/uko7K','',0),('joli17','V-4865342',4,'Angelina','Joliet','0414-1050663','joliethollywood@gmail.com','$2y$10$4tor5/z17Cpg3bCWfe05q.scz4VHF.fWdr/JDFDAEkmMEOKKDZWiu','',1),('lonnar15','V-22450312',2,'Leonardo','DiCaprio','0416-7091089','leonard_15@gmail.com','$2y$10$twZvWGxCCvMx.MxhU.AqGeKzN5IInqTZyCC4WkL1z8MbUpnMIjT3m','',1),('lz2712','V-30266398',2,'Leizer','Torrealba','0416-0506544','leizeraponte2020@gmail.com','$2y$10$sONqWv4yy5PEeePKYljGXOLjFuJa1lMz9yua.3cMVAHG4hU.75Jpe','assets/img/foto-perfil/V-30266398.png',1),('mari14','V-30587785',2,'Mariangel','Bokor','0424-5319088','bokorarcangel47@gmail.com','','',1),('maria123','V-21140325',2,'Felix','Mujica','0400-0000000','ejemplo@gmail.com','12345','',0),('root','V-1234567',1,'root','admin','0000-0000000','prueba@gmail.com','$2y$10$iCwnWHHoZErMPiyX4hzuTOlE7g2RJ8AVI/Y/N6yD9uXyA2D0pYFim','',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-13 15:03:54
