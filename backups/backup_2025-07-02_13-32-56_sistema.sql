-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: sigso_sistema
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
-- Current Database: `sigso_sistema`
--

/*!40000 DROP DATABASE IF EXISTS `sigso_sistema`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sigso_sistema` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `sigso_sistema`;

--
-- Table structure for table `bien`
--

DROP TABLE IF EXISTS `bien`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bien` (
  `codigo_bien` varchar(20) NOT NULL,
  `id_tipo_bien` int(11) NOT NULL,
  `id_marca` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `estado` varchar(45) NOT NULL,
  `cedula_empleado` varchar(12) DEFAULT NULL,
  `id_oficina` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`codigo_bien`),
  KEY `id_tipo_bien` (`id_tipo_bien`),
  KEY `cedula_empleado` (`cedula_empleado`),
  KEY `id_marca` (`id_marca`),
  KEY `id_oficina` (`id_oficina`),
  CONSTRAINT `bien_ibfk_1` FOREIGN KEY (`cedula_empleado`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `bien_ibfk_4` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `bien_ibfk_5` FOREIGN KEY (`id_tipo_bien`) REFERENCES `tipo_bien` (`id_tipo_bien`) ON UPDATE CASCADE,
  CONSTRAINT `bien_ibfk_6` FOREIGN KEY (`id_oficina`) REFERENCES `oficina` (`id_oficina`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bien`
--

LOCK TABLES `bien` WRITE;
/*!40000 ALTER TABLE `bien` DISABLE KEYS */;
INSERT INTO `bien` VALUES ('213',1,2,'asd','Usado','V-1234567',3,1),('JK2450',1,3,'Ejemplo','Usado','V-30587785',2,1);
/*!40000 ALTER TABLE `bien` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargo`
--

DROP TABLE IF EXISTS `cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cargo` (
  `id_cargo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cargo` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id_cargo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargo`
--

LOCK TABLES `cargo` WRITE;
/*!40000 ALTER TABLE `cargo` DISABLE KEYS */;
INSERT INTO `cargo` VALUES (1,'Técnico',1),(2,'Director de Telefonía',1),(3,'Secretaria',1);
/*!40000 ALTER TABLE `cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dependencia`
--

DROP TABLE IF EXISTS `dependencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dependencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ente` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `id_ente` (`id_ente`),
  CONSTRAINT `dependencia_ibfk_1` FOREIGN KEY (`id_ente`) REFERENCES `ente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dependencia`
--

LOCK TABLES `dependencia` WRITE;
/*!40000 ALTER TABLE `dependencia` DISABLE KEYS */;
INSERT INTO `dependencia` VALUES (1,1,'OFITIC',1),(2,1,'Contraloría',1);
/*!40000 ALTER TABLE `dependencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_hoja`
--

DROP TABLE IF EXISTS `detalle_hoja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_hoja` (
  `id_detalle_` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_hoja_servicio` int(11) NOT NULL,
  `componente` varchar(100) DEFAULT NULL,
  `detalle` varchar(200) DEFAULT NULL,
  `id_movimiento_material` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_`),
  KEY `id_movimiento_material` (`id_movimiento_material`),
  KEY `Union_hoja` (`codigo_hoja_servicio`),
  CONSTRAINT `Union_hoja` FOREIGN KEY (`codigo_hoja_servicio`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`),
  CONSTRAINT `detalle_hoja_ibfk_1` FOREIGN KEY (`codigo_hoja_servicio`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_hoja_ibfk_2` FOREIGN KEY (`id_movimiento_material`) REFERENCES `movimiento_materiales` (`id_movimiento_material`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_hoja`
--

LOCK TABLES `detalle_hoja` WRITE;
/*!40000 ALTER TABLE `detalle_hoja` DISABLE KEYS */;
INSERT INTO `detalle_hoja` VALUES (2,10,'aaaa','aaaaa',NULL),(3,10,'eeeee','eeeeee',NULL);
/*!40000 ALTER TABLE `detalle_hoja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado` (
  `cedula_empleado` varchar(12) NOT NULL,
  `nombre_empleado` varchar(45) NOT NULL,
  `apellido_empleado` varchar(45) NOT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `id_unidad` int(11) NOT NULL,
  `telefono_empleado` varchar(15) DEFAULT NULL,
  `correo_empleado` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cedula_empleado`),
  KEY `empleado_ibfk_1` (`id_unidad`),
  KEY `tipo` (`id_cargo`),
  KEY `empleado_ibfk_4` (`id_servicio`),
  CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`id_unidad`) REFERENCES `unidad` (`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` VALUES ('V-1234567','Maria','Peres',NULL,NULL,1,'0426-5575858','prueba@gmail.com'),('V-21140325','Félix','Mujica',NULL,NULL,1,'0400-0000000','ejemplo@gmail.com'),('V-30266398','Leizer','Torrealba',NULL,NULL,1,'0416-0506544','leizeraponte2020@gmail.com'),('V-30454597','Franklin','Fonseca',NULL,NULL,1,'0424-5041921','franklinjavierfonsecavasquez@gmail.com'),('V-30587785','Mariangel','Bokor',NULL,NULL,1,'0424-5319088','bokorarcangel447@gmail.com'),('V-31843937','Jorge','Cabrera',1,1,1,'0424-5567016','cabrerajorge2003@gmail.com');
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ente`
--

DROP TABLE IF EXISTS `ente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(90) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `nombre_responsable` varchar(65) DEFAULT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ente`
--

LOCK TABLES `ente` WRITE;
/*!40000 ALTER TABLE `ente` DISABLE KEYS */;
INSERT INTO `ente` VALUES (1,'Gobernación','','','',1),(3,'Teatro Juaréz','','','',1),(4,'Parque Baradida','Carrera 18 con calle 55 y 54','0251-0070881','Ricardo Guzmán',1);
/*!40000 ALTER TABLE `ente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipo`
--

DROP TABLE IF EXISTS `equipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipo` (
  `id_equipo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_equipo` varchar(45) DEFAULT NULL,
  `serial` varchar(45) DEFAULT NULL,
  `codigo_bien` varchar(20) DEFAULT NULL,
  `id_unidad` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_equipo`),
  UNIQUE KEY `serial` (`serial`),
  KEY `equipo_ibfk_2` (`id_unidad`),
  KEY `nro_bien` (`codigo_bien`),
  CONSTRAINT `equipo_ibfk_3` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `equipo_ibfk_4` FOREIGN KEY (`id_unidad`) REFERENCES `unidad` (`id_unidad`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo`
--

LOCK TABLES `equipo` WRITE;
/*!40000 ALTER TABLE `equipo` DISABLE KEYS */;
INSERT INTO `equipo` VALUES (2,'12123','3123','JK2450',1,1),(4,'12123','31232','213',1,1);
/*!40000 ALTER TABLE `equipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hoja_servicio`
--

DROP TABLE IF EXISTS `hoja_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hoja_servicio` (
  `codigo_hoja_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `nro_solicitud` int(11) NOT NULL,
  `id_tipo_servicio` int(11) NOT NULL,
  `redireccion` int(11) DEFAULT NULL,
  `cedula_tecnico` varchar(12) DEFAULT NULL,
  `fecha_resultado` datetime DEFAULT NULL,
  `resultado_hoja_servicio` varchar(45) DEFAULT NULL,
  `observacion` varchar(200) DEFAULT NULL,
  `estatus` varchar(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo_hoja_servicio`),
  KEY `hoja_servicio_ibfk_1` (`nro_solicitud`),
  KEY `hoja_servicio_ibfk_2` (`id_tipo_servicio`),
  KEY `redireccion` (`redireccion`),
  KEY `id_tipo_servicio` (`id_tipo_servicio`),
  KEY `cedula_tecnico` (`cedula_tecnico`),
  CONSTRAINT `hoja_servicio_ibfk_1` FOREIGN KEY (`nro_solicitud`) REFERENCES `solicitud` (`nro_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hoja_servicio_ibfk_2` FOREIGN KEY (`cedula_tecnico`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hoja_servicio_ibfk_3` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hoja_servicio_ibfk_4` FOREIGN KEY (`redireccion`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hoja_servicio`
--

LOCK TABLES `hoja_servicio` WRITE;
/*!40000 ALTER TABLE `hoja_servicio` DISABLE KEYS */;
INSERT INTO `hoja_servicio` VALUES (10,128,1,NULL,NULL,'0000-00-00 00:00:00','','','A'),(11,129,1,NULL,NULL,'0000-00-00 00:00:00',NULL,NULL,'A'),(12,131,1,NULL,NULL,'2025-06-27 10:07:11',NULL,NULL,'A'),(13,130,1,NULL,NULL,'2025-06-27 10:08:08',NULL,NULL,'E'),(14,127,1,NULL,NULL,'2025-06-27 10:08:19',NULL,NULL,'E'),(15,123,2,NULL,NULL,NULL,'','El problema es que tenia que conectarle bien el cable','E');
/*!40000 ALTER TABLE `hoja_servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interconexion`
--

DROP TABLE IF EXISTS `interconexion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interconexion` (
  `id_interconexion` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_switch` varchar(20) NOT NULL,
  `codigo_patch_panel` varchar(20) NOT NULL,
  `puerto_switch` int(11) NOT NULL,
  `puerto_patch_panel` int(11) NOT NULL,
  PRIMARY KEY (`id_interconexion`),
  KEY `codigo_switch` (`codigo_switch`),
  KEY `codigo_patch_panel_2` (`codigo_patch_panel`),
  CONSTRAINT `interconexion_ibfk_1` FOREIGN KEY (`codigo_switch`) REFERENCES `switch` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `interconexion_ibfk_2` FOREIGN KEY (`codigo_patch_panel`) REFERENCES `patch_panel` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interconexion`
--

LOCK TABLES `interconexion` WRITE;
/*!40000 ALTER TABLE `interconexion` DISABLE KEYS */;
/*!40000 ALTER TABLE `interconexion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marca`
--

DROP TABLE IF EXISTS `marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_marca` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES (1,'Lenovo',1),(2,'HP',1),(3,'SAMSUNG',1),(4,'VIT',1),(5,'Apple',1),(6,'OPPO',0);
/*!40000 ALTER TABLE `marca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material`
--

DROP TABLE IF EXISTS `material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material` (
  `id_material` int(11) NOT NULL AUTO_INCREMENT,
  `ubicacion` int(11) DEFAULT NULL,
  `nombre_material` varchar(45) NOT NULL,
  `stock` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_material`),
  KEY `material_ibfk_1` (`ubicacion`),
  CONSTRAINT `material_ibfk_1` FOREIGN KEY (`ubicacion`) REFERENCES `oficina` (`id_oficina`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material`
--

LOCK TABLES `material` WRITE;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
INSERT INTO `material` VALUES (1,1,'Pepito',213,0),(2,3,'Pepito2',213,0),(3,4,'gumersildad',2142,0),(4,1,'gumersildad',213,1),(5,3,'Pepito2',213,1),(6,2,'Pepito',213,1),(7,2,'gumersildad',2132,1),(8,5,'Pepito2',213,0),(9,4,'Pepito2',213,1),(10,2,'Pepito2',213,1);
/*!40000 ALTER TABLE `material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimiento_materiales`
--

DROP TABLE IF EXISTS `movimiento_materiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimiento_materiales` (
  `id_movimiento_material` int(11) NOT NULL AUTO_INCREMENT,
  `id_material` int(11) NOT NULL,
  `accion` varchar(45) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_movimiento_material`),
  KEY `id_material` (`id_material`),
  CONSTRAINT `movimiento_materiales_ibfk_1` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimiento_materiales`
--

LOCK TABLES `movimiento_materiales` WRITE;
/*!40000 ALTER TABLE `movimiento_materiales` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimiento_materiales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oficina`
--

DROP TABLE IF EXISTS `oficina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oficina` (
  `id_oficina` int(11) NOT NULL AUTO_INCREMENT,
  `id_piso` int(11) NOT NULL,
  `nombre_oficina` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_oficina`),
  KEY `id_piso` (`id_piso`),
  CONSTRAINT `oficina_ibfk_1` FOREIGN KEY (`id_piso`) REFERENCES `piso` (`id_piso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oficina`
--

LOCK TABLES `oficina` WRITE;
/*!40000 ALTER TABLE `oficina` DISABLE KEYS */;
INSERT INTO `oficina` VALUES (1,1,'Taller 1',1),(2,2,'Taller 2',1),(3,1,'Oficina',1),(4,1,'Depósito',1),(5,3,'Taller de Electrónica',1),(6,9,'Pepito2',1),(7,11,'Hotel',1);
/*!40000 ALTER TABLE `oficina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patch_panel`
--

DROP TABLE IF EXISTS `patch_panel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patch_panel` (
  `codigo_bien` varchar(20) NOT NULL,
  `serial` varchar(45) NOT NULL,
  `tipo_patch_panel` varchar(45) NOT NULL,
  `cantidad_puertos` int(11) NOT NULL,
  PRIMARY KEY (`codigo_bien`),
  UNIQUE KEY `serial` (`serial`),
  KEY `codigo_bien` (`codigo_bien`),
  CONSTRAINT `patch_panel_ibfk_1` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patch_panel`
--

LOCK TABLES `patch_panel` WRITE;
/*!40000 ALTER TABLE `patch_panel` DISABLE KEYS */;
INSERT INTO `patch_panel` VALUES ('213','2343','Fuerte',28),('JK2450','1223234','Fuerte',24);
/*!40000 ALTER TABLE `patch_panel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `piso`
--

DROP TABLE IF EXISTS `piso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `piso` (
  `id_piso` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_piso` varchar(45) NOT NULL,
  `nro_piso` varchar(10) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_piso`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `piso`
--

LOCK TABLES `piso` WRITE;
/*!40000 ALTER TABLE `piso` DISABLE KEYS */;
INSERT INTO `piso` VALUES (1,'Planta Baja','0',1),(2,'Piso','1',0),(3,'Piso','1',1),(4,'Sótano','2',1),(5,'Piso','2',1),(6,'Piso','3',1),(7,'Sótano','1',1),(8,'Piso','4',1),(9,'Piso','5',1),(10,'Piso','6',1),(11,'Piso','7',1),(12,'Terraza','10',1),(13,'Sótano','3',1),(14,'Sótano','4',1),(15,'Sótano','5',0),(16,'Sótano','6',0),(17,'Sótano','7',0),(18,'Terraza','8',0);
/*!40000 ALTER TABLE `piso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `punto_conexion`
--

DROP TABLE IF EXISTS `punto_conexion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `punto_conexion` (
  `id_punto_conexion` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_patch_panel` varchar(20) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `puerto_patch_panel` int(11) NOT NULL,
  PRIMARY KEY (`id_punto_conexion`),
  KEY `codigo_patch` (`codigo_patch_panel`),
  KEY `id_equipo` (`id_equipo`),
  CONSTRAINT `punto_conexion_ibfk_1` FOREIGN KEY (`codigo_patch_panel`) REFERENCES `patch_panel` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `punto_conexion_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `punto_conexion`
--

LOCK TABLES `punto_conexion` WRITE;
/*!40000 ALTER TABLE `punto_conexion` DISABLE KEYS */;
/*!40000 ALTER TABLE `punto_conexion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud`
--

DROP TABLE IF EXISTS `solicitud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud` (
  `nro_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_solicitante` varchar(12) NOT NULL,
  `motivo` varchar(200) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `estado_solicitud` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `resultado_solicitud` varchar(20) DEFAULT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`nro_solicitud`),
  KEY `solicitud_ibfk_1` (`cedula_solicitante`),
  KEY `solicitud_ibfk_2` (`id_equipo`),
  CONSTRAINT `solicitud_ibfk_1` FOREIGN KEY (`cedula_solicitante`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud`
--

LOCK TABLES `solicitud` WRITE;
/*!40000 ALTER TABLE `solicitud` DISABLE KEYS */;
INSERT INTO `solicitud` VALUES (108,'V-30266398','Prueba registrosos',2,'2025-06-24 19:25:58','Pendiente',NULL,0),(109,'V-31843937','asdasd',2,'2025-06-24 19:28:20','Pendiente',NULL,0),(110,'V-1234567','23432',2,'2025-06-24 19:49:08','Pendiente',NULL,0),(111,'V-1234567','Prueba de registrar',2,'2025-06-24 22:14:36','Pendiente',NULL,0),(112,'V-21140325','Chiclesa',2,'2025-06-24 22:53:25','Pendiente',NULL,0),(113,'V-30454597','1923',2,'2025-06-25 08:59:58','Pendiente',NULL,0),(114,'V-30454597','Soy un mami',2,'2025-06-25 09:04:03','Pendiente',NULL,0),(115,'V-30266398','aaaaaa',2,'2025-06-25 09:05:15','Pendiente',NULL,0),(116,'V-1234567','qwerts',2,'2025-06-25 09:06:31','Pendiente',NULL,0),(117,'V-21140325','sdasd',2,'2025-06-25 09:06:53','Pendiente',NULL,0),(118,'V-30454597','Preueba hoja',2,'2025-06-25 09:39:52','Pendiente',NULL,0),(119,'V-1234567','Prueba',2,'2025-06-25 09:42:21','Pendiente',NULL,0),(120,'V-1234567','Hojaaa',2,'2025-06-25 09:44:52','Pendiente',NULL,0),(121,'V-1234567','asd',2,'2025-06-25 09:48:27','Pendiente',NULL,0),(122,'V-21140325','prueba mill',2,'2025-06-25 09:50:15','Pendiente',NULL,0),(123,'V-1234567','muvi',2,'2025-06-25 09:51:15','En proceso',NULL,0),(124,'V-21140325','asdf',2,'2025-06-25 09:54:09','Pendiente',NULL,0),(125,'V-30587785','asd3',2,'2025-06-25 09:56:00','Pendiente',NULL,0),(126,'V-1234567','hola',2,'2025-06-25 10:16:36','Pendiente',NULL,0),(127,'V-1234567','Necesita mas ram a su equipo',2,'2025-06-25 10:18:43','En proceso',NULL,1),(128,'V-1234567','hoja',2,'2025-06-25 10:24:37','En proceso',NULL,0),(129,'V-31843937','Mentira se llama jorge c',NULL,'2025-06-25 10:29:39','En proceso',NULL,0),(130,'V-30266398','Necesita un monitos LCTRCs',2,'2025-06-27 10:05:26','En proceso',NULL,0),(131,'V-30266398','Necesita un monitor LCRCT',NULL,'2025-06-27 10:07:11','En proceso',NULL,1),(132,'V-31843937','no internet',NULL,'2025-07-02 13:17:50','Pendiente',NULL,1);
/*!40000 ALTER TABLE `solicitud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `switch`
--

DROP TABLE IF EXISTS `switch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `switch` (
  `codigo_bien` varchar(20) NOT NULL,
  `serial` varchar(45) NOT NULL,
  `cantidad_puertos` int(11) NOT NULL,
  PRIMARY KEY (`codigo_bien`),
  UNIQUE KEY `serial` (`serial`),
  CONSTRAINT `switch_ibfk_1` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `switch`
--

LOCK TABLES `switch` WRITE;
/*!40000 ALTER TABLE `switch` DISABLE KEYS */;
/*!40000 ALTER TABLE `switch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_bien`
--

DROP TABLE IF EXISTS `tipo_bien`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_bien` (
  `id_tipo_bien` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_tipo_bien` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id_tipo_bien`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_bien`
--

LOCK TABLES `tipo_bien` WRITE;
/*!40000 ALTER TABLE `tipo_bien` DISABLE KEYS */;
INSERT INTO `tipo_bien` VALUES (1,'Electrónico',1),(2,'Mueble',1);
/*!40000 ALTER TABLE `tipo_bien` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_servicio`
--

DROP TABLE IF EXISTS `tipo_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_servicio` (
  `id_tipo_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_tipo_servicio` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id_tipo_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_servicio`
--

LOCK TABLES `tipo_servicio` WRITE;
/*!40000 ALTER TABLE `tipo_servicio` DISABLE KEYS */;
INSERT INTO `tipo_servicio` VALUES (1,'Soporte Técnico',1),(2,'Redes',1),(3,'Telefonía',1),(4,'Electrónica',1);
/*!40000 ALTER TABLE `tipo_servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidad`
--

DROP TABLE IF EXISTS `unidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidad` (
  `id_unidad` int(11) NOT NULL AUTO_INCREMENT,
  `id_dependencia` int(11) NOT NULL,
  `nombre_unidad` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_unidad`),
  KEY `id_dependencia` (`id_dependencia`),
  CONSTRAINT `unidad_ibfk_1` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidad`
--

LOCK TABLES `unidad` WRITE;
/*!40000 ALTER TABLE `unidad` DISABLE KEYS */;
INSERT INTO `unidad` VALUES (1,1,'Bienes',1),(2,1,'Seguridad',1);
/*!40000 ALTER TABLE `unidad` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-02 13:33:02
