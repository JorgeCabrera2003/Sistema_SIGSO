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
  `id_categoria` int(11) NOT NULL,
  `id_marca` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `estado` varchar(45) NOT NULL,
  `cedula_empleado` varchar(12) DEFAULT NULL,
  `id_oficina` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`codigo_bien`),
  KEY `id_tipo_bien` (`id_categoria`),
  KEY `cedula_empleado` (`cedula_empleado`),
  KEY `id_marca` (`id_marca`),
  KEY `id_oficina` (`id_oficina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bien`
--

LOCK TABLES `bien` WRITE;
/*!40000 ALTER TABLE `bien` DISABLE KEYS */;
INSERT INTO `bien` VALUES ('0001',1,1,'Impresora rh200','Nuevo','V-21140325',1,1),('00014',1,1,'Computador 32GB','Usado','V-31843937',2,1),('0002',4,4,'Computador Oficina','Nuevo','V-31843937',2,1),('0003',2,1,'Mesa ejecutiva','Usado','V-21140325',3,1),('0004',1,2,'Patch panel rojo 45k','Nuevo','V-30587785',5,1),('0005',1,2,'Pacth panel 500tc','Nuevo','V-30587785',4,1),('0006',1,1,'Switch prt24','null','V-30587785',3,1),('0007',1,2,'Switch prt27','null','V-30587785',3,1),('0008',1,2,'Switch rt45','null','V-30587785',3,0),('00082',1,1,'Pacth panel 500tcs','Nuevo','V-1234567',5,1),('000889',1,1,'yguhgf','Nuevo','V-1234567',3,1),('0009',1,1,'Laptop','Dañado','V-30266398',4,1),('00092',2,3,'1212','Dañado','V-30266398',4,1),('00093',1,1,'Switch rt45','Usado','V-21140325',3,1),('0010',1,3,'Equipo','Dañado','V-1234567',3,1),('0011',1,3,'Equipo','Usado','V-30266398',4,1),('1213',1,3,'croqueta','Usado','V-21140325',1,1),('123123',1,1,'REDES','Nuevo','V-1234567',1,1),('213',4,3,'Wifi casero','Nuevo','V-31843937',2,1),('2343',1,1,'Equipo Escritorio','Usado','V-31843937',2,1),('PP-101',1,2,'Patch Panel 24 Puertos CAT6 - Piso 1 Oficina A','Nuevo','V-30587785',1,1),('PP-102',1,2,'Patch Panel 48 Puertos CAT6 - Piso 1 Sala Servers','Nuevo','V-30587785',1,1),('PP-103',1,1,'Patch Panel 24 Puertos CAT5e - Piso 1 Recepción','Usado','V-30587785',3,1),('PP-104',1,3,'Patch Panel 12 Puertos Fibra - Piso 1 Datacenter','Nuevo','V-30587785',4,1),('PP-105',1,2,'Patch Panel 36 Puertos CAT6 - Piso 1 Área TI','Nuevo','V-30587785',1,1),('PP-201',1,2,'Patch Panel 24 Puertos CAT6 - Piso 2 Oficina B','Nuevo','V-30587785',2,1),('PP-202',1,1,'Patch Panel 48 Puertos CAT6 - Piso 2 Conferencias','Nuevo','V-30587785',2,1),('PP-203',1,3,'Patch Panel 24 Puertos CAT5e - Piso 2 Cubículos','Usado','V-30587785',2,1),('PP-204',1,2,'Patch Panel 12 Puertos Fibra - Piso 2 Servidores','Nuevo','V-30587785',2,1),('PP-205',1,1,'Patch Panel 36 Puertos CAT6 - Piso 2 Administración','Nuevo','V-30587785',2,1),('PP-301',1,2,'Patch Panel 24 Puertos CAT6 - Piso 3 Oficina C','Nuevo','V-30587785',5,1),('PP-302',1,1,'Patch Panel 48 Puertos CAT6 - Piso 3 Laboratorio','Nuevo','V-30587785',5,1),('PP-303',1,3,'Patch Panel 24 Puertos CAT5e - Piso 3 Desarrollo','Usado','V-30587785',5,1),('PP-304',1,2,'Patch Panel 12 Puertos Fibra - Piso 3 Testing','Nuevo','V-30587785',5,1),('PP-305',1,1,'Patch Panel 36 Puertos CAT6 - Piso 3 Diseño','Nuevo','V-30587785',5,1),('PP-401',1,2,'Patch Panel 24 Puertos CAT6 - Piso 4 Contabilidad','Nuevo','V-30587785',4,1),('PP-402',1,1,'Patch Panel 48 Puertos CAT6 - Piso 4 Finanzas','Nuevo','V-30587785',4,1),('PP-403',1,3,'Patch Panel 24 Puertos CAT5e - Piso 4 RH','Usado','V-30587785',4,1),('PP-404',1,2,'Patch Panel 12 Puertos Fibra - Piso 4 Dirección','Nuevo','V-30587785',4,1),('PP-405',1,1,'Patch Panel 36 Puertos CAT6 - Piso 4 Gerencia','Nuevo','V-30587785',4,1),('PP-501',1,2,'Patch Panel 24 Puertos CAT6 - Piso 5 Marketing','Nuevo','V-30587785',3,1),('PP-502',1,1,'Patch Panel 48 Puertos CAT6 - Piso 5 Ventas','Nuevo','V-30587785',3,1),('PP-503',1,3,'Patch Panel 24 Puertos CAT5e - Piso 5 Comercial','Usado','V-30587785',3,1),('PP-504',1,2,'Patch Panel 12 Puertos Fibra - Piso 5 Atención Cliente','Nuevo','V-30587785',3,1),('PP-505',1,1,'Patch Panel 36 Puertos CAT6 - Piso 5 Soporte','Nuevo','V-30587785',3,1),('PP-601',1,2,'Patch Panel 24 Puertos CAT6 - Piso 6 Almacén','Nuevo','V-30587785',4,1),('PP-602',1,1,'Patch Panel 48 Puertos CAT6 - Piso 6 Logística','Nuevo','V-30587785',4,1),('PP-603',1,3,'Patch Panel 24 Puertos CAT5e - Piso 6 Producción','Usado','V-30587785',4,1),('PP-604',1,2,'Patch Panel 12 Puertos Fibra - Piso 6 Calidad','Nuevo','V-30587785',4,1),('PP-605',1,1,'Patch Panel 36 Puertos CAT6 - Piso 6 Operaciones','Nuevo','V-30587785',4,1),('SW-101',1,2,'Switch 24 Puertos Gigabit - Piso 1 Core','Nuevo','V-30587785',1,1),('SW-102',1,1,'Switch 48 Puertos Gigabit - Piso 1 Distribución','Nuevo','V-30587785',1,1),('SW-103',1,3,'Switch 16 Puertos POE - Piso 1 VoIP','Usado','V-30587785',3,1),('SW-104',1,2,'Switch 8 Puertos Managed - Piso 1 Servidores','Nuevo','V-30587785',4,1),('SW-105',1,1,'Switch 24 Puertos POE+ - Piso 1 Acceso','Nuevo','V-30587785',1,1),('SW-201',1,2,'Switch 24 Puertos Gigabit - Piso 2 Core','Nuevo','V-30587785',2,1),('SW-202',1,1,'Switch 48 Puertos Gigabit - Piso 2 Distribución','Nuevo','V-30587785',2,1),('SW-203',1,3,'Switch 16 Puertos POE - Piso 2 VoIP','Usado','V-30587785',2,1),('SW-204',1,2,'Switch 8 Puertos Managed - Piso 2 Servidores','Nuevo','V-30587785',2,1),('SW-205',1,1,'Switch 24 Puertos POE+ - Piso 2 Acceso','Nuevo','V-30587785',2,1),('SW-301',1,2,'Switch 24 Puertos Gigabit - Piso 3 Core','Nuevo','V-30587785',5,1),('SW-302',1,1,'Switch 48 Puertos Gigabit - Piso 3 Distribución','Nuevo','V-30587785',5,1),('SW-303',1,3,'Switch 16 Puertos POE - Piso 3 VoIP','Usado','V-30587785',5,1),('SW-304',1,2,'Switch 8 Puertos Managed - Piso 3 Servidores','Nuevo','V-30587785',5,1),('SW-305',1,1,'Switch 24 Puertos POE+ - Piso 3 Acceso','Nuevo','V-30587785',5,1),('SW-401',1,2,'Switch 24 Puertos Gigabit - Piso 4 Core','Nuevo','V-30587785',4,1),('SW-402',1,1,'Switch 48 Puertos Gigabit - Piso 4 Distribución','Nuevo','V-30587785',4,1),('SW-403',1,3,'Switch 16 Puertos POE - Piso 4 VoIP','Usado','V-30587785',4,1),('SW-404',1,2,'Switch 8 Puertos Managed - Piso 4 Servidores','Nuevo','V-30587785',4,1),('SW-405',1,1,'Switch 24 Puertos POE+ - Piso 4 Acceso','Nuevo','V-30587785',4,1),('SW-501',1,2,'Switch 24 Puertos Gigabit - Piso 5 Core','Nuevo','V-30587785',3,1),('SW-502',1,1,'Switch 48 Puertos Gigabit - Piso 5 Distribución','Nuevo','V-30587785',3,1),('SW-503',1,3,'Switch 16 Puertos POE - Piso 5 VoIP','Usado','V-30587785',3,1),('SW-504',1,2,'Switch 8 Puertos Managed - Piso 5 Servidores','Nuevo','V-30587785',3,1),('SW-505',1,1,'Switch 24 Puertos POE+ - Piso 5 Acceso','Nuevo','V-30587785',3,1),('SW-601',1,2,'Switch 24 Puertos Gigabit - Piso 6 Core','Nuevo','V-30587785',4,1),('SW-602',1,1,'Switch 48 Puertos Gigabit - Piso 6 Distribución','Nuevo','V-30587785',4,1),('SW-603',1,3,'Switch 16 Puertos POE - Piso 6 VoIP','Usado','V-30587785',4,1),('SW-604',1,2,'Switch 8 Puertos Managed - Piso 6 Servidores','Nuevo','V-30587785',4,1),('SW-605',1,1,'Switch 24 Puertos POE+ - Piso 6 Acceso','Nuevo','V-30587785',4,1);
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
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(45) NOT NULL,
  `id_tipo_servicio` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id_categoria`),
  KEY `id_tipo_servicio` (`id_tipo_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Electrónico',1,1),(2,'Mueble',NULL,1),(4,'telefonoCable',2,1);
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
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
  KEY `id_ente` (`id_ente`)
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
  KEY `Union_hoja` (`codigo_hoja_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_hoja`
--

LOCK TABLES `detalle_hoja` WRITE;
/*!40000 ALTER TABLE `detalle_hoja` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_hoja` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_before_insert_detalle_hoja` BEFORE INSERT ON `detalle_hoja` FOR EACH ROW BEGIN
    DECLARE v_stock_actual INT;
    
    -- Solo validar si hay un movimiento de material asociado
    IF NEW.id_movimiento_material IS NOT NULL THEN
        -- Obtener el id_material y cantidad del movimiento
        SELECT m.id_material, mm.cantidad INTO @id_material, @cantidad
        FROM movimiento_materiales mm
        JOIN material m ON mm.id_material = m.id_material
        WHERE mm.id_movimiento_material = NEW.id_movimiento_material;
        
        -- Verificar stock disponible
        SELECT stock INTO v_stock_actual
        FROM material 
        WHERE id_material = @id_material AND estatus = 1;
        
        IF v_stock_actual < @cantidad THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Stock insuficiente para el material';
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `detalle_solicitud_compra`
--

DROP TABLE IF EXISTS `detalle_solicitud_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_solicitud_compra` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud_compra` int(11) NOT NULL,
  `id_material` int(11) DEFAULT NULL,
  `descripcion` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `unidad_medida` varchar(20) DEFAULT NULL,
  `justificacion` varchar(200) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_detalle`),
  KEY `id_solicitud_compra` (`id_solicitud_compra`),
  KEY `id_material` (`id_material`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_solicitud_compra`
--

LOCK TABLES `detalle_solicitud_compra` WRITE;
/*!40000 ALTER TABLE `detalle_solicitud_compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_solicitud_compra` ENABLE KEYS */;
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
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`cedula_empleado`),
  KEY `empleado_ibfk_1` (`id_unidad`),
  KEY `tipo` (`id_cargo`),
  KEY `empleado_ibfk_4` (`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` VALUES ('V-1234567','Maria','Peres',NULL,NULL,1,'0426-5575858','prueba@gmail.com',1),('V-21140325','Félix','Mujica',NULL,NULL,1,'0400-0000000','ejemplo@gmail.com',1),('V-30266398','Leizer','Torrealba',NULL,NULL,1,'0416-0506544','leizeraponte2020@gmail.com',1),('V-30454597','Franklin','Fonseca',1,1,2,'0424-5041921','franklinjavierfonsecavasquez@gmail.com',1),('V-30587785','Mariangel','Bokor',1,2,1,'0424-5319088','bokorarcangel447@gmail.com',1),('V-31843937','Jorge','Cabrera',1,1,1,'0424-5567016','cabrerajorge2003@gmail.com',1),('V-32567189','Angelina','Joly',3,NULL,1,'1232-3243245','23234@gmail.com',1);
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
  `tipo_ente` varchar(20) NOT NULL DEFAULT 'interno',
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ente`
--

LOCK TABLES `ente` WRITE;
/*!40000 ALTER TABLE `ente` DISABLE KEYS */;
INSERT INTO `ente` VALUES (1,'Gobernación','','','','interno',1),(3,'Teatro Juaréz','','','','externo',1),(4,'Parque Baradida','Carrera 18 con calle 55 y 54','0251-0070881','Ricardo Guzmán','externo',1);
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
  KEY `nro_bien` (`codigo_bien`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo`
--

LOCK TABLES `equipo` WRITE;
/*!40000 ALTER TABLE `equipo` DISABLE KEYS */;
INSERT INTO `equipo` VALUES (13,'Empresarial','000001','0001',1,1),(14,'Computador','00002','0002',2,1),(15,'Computador','00010','0010',1,1),(16,'Computador','00011','0011',1,1),(17,'Telefono','00012','0009',1,1),(18,'PC GAMERE','00013','0004',2,1),(19,'Computador','31232','0003',1,1),(20,'12123','00015','0005',1,1),(21,'Computador','3123234','0006',1,0),(22,'Computador','33123234','0007',1,0),(23,'Computadorar','000014','00014',1,0),(24,'Wifi','545646571','213',1,1),(25,'Computador FireSalt','212112','2343',2,1),(26,'Computador2','54563','1213',1,1),(27,'212','1212','00092',1,1);
/*!40000 ALTER TABLE `equipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `filtrado_empleado`
--

DROP TABLE IF EXISTS `filtrado_empleado`;
/*!50001 DROP VIEW IF EXISTS `filtrado_empleado`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `filtrado_empleado` AS SELECT
 1 AS `Total usuario`,
  1 AS `Total oficina`,
  1 AS `Total empleados OFITIC`,
  1 AS `Total empleados general` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `filtrado_hoja`
--

DROP TABLE IF EXISTS `filtrado_hoja`;
/*!50001 DROP VIEW IF EXISTS `filtrado_hoja`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `filtrado_hoja` AS SELECT
 1 AS `Área con más hojas`,
  1 AS `Cantidad de hojas`,
  1 AS `Hojas eliminadas`,
  1 AS `Hojas activas`,
  1 AS `Hojas finalizadas` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `filtrado_tecnico`
--

DROP TABLE IF EXISTS `filtrado_tecnico`;
/*!50001 DROP VIEW IF EXISTS `filtrado_tecnico`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `filtrado_tecnico` AS SELECT
 1 AS `Total tecnicos`,
  1 AS `Total soporte`,
  1 AS `Total redes`,
  1 AS `Total telefono`,
  1 AS `Total electronica`,
  1 AS `Tecnico eficiente` */;
SET character_set_client = @saved_cs_client;

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
  KEY `cedula_tecnico` (`cedula_tecnico`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hoja_servicio`
--

LOCK TABLES `hoja_servicio` WRITE;
/*!40000 ALTER TABLE `hoja_servicio` DISABLE KEYS */;
INSERT INTO `hoja_servicio` VALUES (44,166,1,NULL,'V-30454597',NULL,NULL,NULL,'A'),(45,167,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(46,167,1,NULL,'V-31843937',NULL,NULL,NULL,'A'),(47,168,1,NULL,'V-30454597',NULL,NULL,NULL,'A'),(48,171,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(49,171,1,NULL,'V-31843937',NULL,NULL,NULL,'A'),(50,172,1,NULL,'V-30454597',NULL,NULL,NULL,'A'),(51,173,1,NULL,'V-31843937',NULL,NULL,NULL,'A'),(52,173,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(53,174,1,NULL,'V-30454597',NULL,NULL,NULL,'A'),(54,175,1,NULL,'V-31843937',NULL,NULL,NULL,'A'),(55,176,1,NULL,'V-30454597',NULL,NULL,NULL,'A'),(56,175,2,NULL,'V-30587785',NULL,NULL,NULL,'E'),(57,176,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(58,177,1,NULL,'V-31843937',NULL,NULL,NULL,'A'),(60,179,1,NULL,'V-30454597',NULL,NULL,NULL,'A'),(61,2,1,NULL,'V-31843937',NULL,NULL,NULL,'A'),(62,3,1,NULL,'V-30454597',NULL,NULL,NULL,'A'),(63,4,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(64,5,1,NULL,'V-31843937',NULL,NULL,NULL,'A'),(65,6,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(66,5,1,64,'V-30454597',NULL,NULL,NULL,'A'),(67,7,1,NULL,'V-31843937',NULL,NULL,NULL,'A'),(68,8,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(69,9,1,NULL,'V-30454597',NULL,NULL,NULL,'A'),(70,10,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(71,11,1,NULL,'V-31843937',NULL,NULL,NULL,'A'),(72,12,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(73,13,1,NULL,'V-30454597',NULL,NULL,NULL,'A'),(74,14,2,NULL,'V-30587785',NULL,NULL,NULL,'A'),(75,14,1,74,'V-30454597',NULL,NULL,NULL,'A');
/*!40000 ALTER TABLE `hoja_servicio` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_after_insert_hoja_servicio` AFTER INSERT ON `hoja_servicio` FOR EACH ROW BEGIN
    -- Actualizar estado de la solicitud a "En proceso" cuando se crea una hoja
    UPDATE solicitud 
    SET estado_solicitud = 'En proceso'
    WHERE nro_solicitud = NEW.nro_solicitud AND estado_solicitud = 'Pendiente';
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_after_update_hoja_servicio` AFTER UPDATE ON `hoja_servicio` FOR EACH ROW BEGIN
    DECLARE v_hojas_pendientes INT;
    
    -- Solo actuar si la hoja se marcó como finalizada
    IF NEW.estatus = 'I' AND OLD.estatus = 'A' THEN
        -- Verificar si quedan hojas activas para esta solicitud
        SELECT COUNT(*) INTO v_hojas_pendientes
        FROM hoja_servicio 
        WHERE nro_solicitud = NEW.nro_solicitud AND estatus = 'A';
        
        IF v_hojas_pendientes = 0 THEN
            -- Actualizar estado de la solicitud a "Finalizado"
            UPDATE solicitud 
            SET estado_solicitud = 'Finalizado',
                resultado_solicitud = 'Completado'
            WHERE nro_solicitud = NEW.nro_solicitud;
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Temporary table structure for view `hojasactivas`
--

DROP TABLE IF EXISTS `hojasactivas`;
/*!50001 DROP VIEW IF EXISTS `hojasactivas`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `hojasactivas` AS SELECT
 1 AS `codigo_hoja_servicio`,
  1 AS `nro_solicitud`,
  1 AS `id_tipo_servicio`,
  1 AS `redireccion`,
  1 AS `cedula_tecnico`,
  1 AS `fecha_resultado`,
  1 AS `resultado_hoja_servicio`,
  1 AS `observacion`,
  1 AS `estatus`,
  1 AS `nombre_empleado`,
  1 AS `apellido_empleado`,
  1 AS `descripcion` */;
SET character_set_client = @saved_cs_client;

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
  KEY `codigo_patch_panel_2` (`codigo_patch_panel`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
  KEY `material_ibfk_1` (`ubicacion`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material`
--

LOCK TABLES `material` WRITE;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
INSERT INTO `material` VALUES (11,1,'Conector RJ45',100,1),(12,2,'Cable fibra optica',300,1),(13,1,'Pasta termica',50,1);
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
  KEY `id_material` (`id_material`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
  KEY `id_piso` (`id_piso`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oficina`
--

LOCK TABLES `oficina` WRITE;
/*!40000 ALTER TABLE `oficina` DISABLE KEYS */;
INSERT INTO `oficina` VALUES (1,1,'Taller 1',1),(2,2,'Taller 2',1),(3,1,'Oficina',1),(4,1,'Depósito',1),(5,3,'Taller de Electrónica',1);
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
  `id_piso` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo_bien`),
  UNIQUE KEY `serial` (`serial`),
  KEY `codigo_bien` (`codigo_bien`),
  KEY `Piso` (`id_piso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patch_panel`
--

LOCK TABLES `patch_panel` WRITE;
/*!40000 ALTER TABLE `patch_panel` DISABLE KEYS */;
INSERT INTO `patch_panel` VALUES ('PP-101','SN-PP101-2024','CAT6',24,1),('PP-102','SN-PP102-2024','CAT6',48,1),('PP-103','SN-PP103-2023','CAT5e',24,1),('PP-104','SN-PP104-2024','Fibra Óptica',12,1),('PP-105','SN-PP105-2024','CAT6',36,1),('PP-201','SN-PP201-2024','CAT6',24,2),('PP-202','SN-PP202-2024','CAT6',48,2),('PP-203','SN-PP203-2023','CAT5e',24,2),('PP-204','SN-PP204-2024','Fibra Óptica',12,2),('PP-205','SN-PP205-2024','CAT6',36,2),('PP-301','SN-PP301-2024','CAT6',24,3),('PP-302','SN-PP302-2024','CAT6',48,3),('PP-303','SN-PP303-2023','CAT5e',24,3),('PP-304','SN-PP304-2024','Fibra Óptica',12,3),('PP-305','SN-PP305-2024','CAT6',36,3),('PP-401','SN-PP401-2024','CAT6',24,4),('PP-402','SN-PP402-2024','CAT6',48,4),('PP-403','SN-PP403-2023','CAT5e',24,4),('PP-404','SN-PP404-2024','Fibra Óptica',12,4),('PP-405','SN-PP405-2024','CAT6',36,4),('PP-501','SN-PP501-2024','CAT6',24,5),('PP-502','SN-PP502-2024','CAT6',48,5),('PP-503','SN-PP503-2023','CAT5e',24,5),('PP-504','SN-PP504-2024','Fibra Óptica',12,5),('PP-505','SN-PP505-2024','CAT6',36,5),('PP-601','SN-PP601-2024','CAT6',24,6),('PP-602','SN-PP602-2024','CAT6',48,6),('PP-603','SN-PP603-2023','CAT5e',24,6),('PP-604','SN-PP604-2024','Fibra Óptica',12,6),('PP-605','SN-PP605-2024','CAT6',36,6);
/*!40000 ALTER TABLE `patch_panel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `piso`
--

DROP TABLE IF EXISTS `piso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `piso` (
  `id_piso` int(11) NOT NULL,
  `tipo_piso` varchar(45) NOT NULL,
  `nro_piso` varchar(10) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_piso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `piso`
--

LOCK TABLES `piso` WRITE;
/*!40000 ALTER TABLE `piso` DISABLE KEYS */;
INSERT INTO `piso` VALUES (1,'Planta Baja','1',1),(2,'Piso','2',0),(3,'Piso','3',1),(4,'Sótano','4',1),(5,'Piso','5',1),(6,'Piso','6',1),(7,'Sótano','7',1),(8,'Piso','8',1),(9,'Piso','9',1);
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
  KEY `id_equipo` (`id_equipo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `punto_conexion`
--

LOCK TABLES `punto_conexion` WRITE;
/*!40000 ALTER TABLE `punto_conexion` DISABLE KEYS */;
INSERT INTO `punto_conexion` VALUES (1,'PP-504',18,9),(2,'PP-404',14,3),(3,'PP-404',25,4),(4,'PP-105',15,5),(5,'PP-504',17,4),(6,'PP-504',26,6),(7,'PP-502',16,46),(8,'PP-502',13,7),(9,'PP-502',19,8);
/*!40000 ALTER TABLE `punto_conexion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio_prestado`
--

DROP TABLE IF EXISTS `servicio_prestado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicio_prestado` (
  `id` int(11) NOT NULL,
  `id_tipo_servicio` int(11) NOT NULL,
  `nombre` varchar(65) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `id_tipo_servicio` (`id_tipo_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio_prestado`
--

LOCK TABLES `servicio_prestado` WRITE;
/*!40000 ALTER TABLE `servicio_prestado` DISABLE KEYS */;
/*!40000 ALTER TABLE `servicio_prestado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio_realizado`
--

DROP TABLE IF EXISTS `servicio_realizado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicio_realizado` (
  `id_servicio_realizado` int(11) NOT NULL,
  `id_servicio_prestado` int(11) NOT NULL,
  `id_hoja_servicio` int(11) NOT NULL,
  PRIMARY KEY (`id_servicio_realizado`),
  KEY `id_servicio_prestado` (`id_servicio_prestado`),
  KEY `id_hoja_servicio` (`id_hoja_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio_realizado`
--

LOCK TABLES `servicio_realizado` WRITE;
/*!40000 ALTER TABLE `servicio_realizado` DISABLE KEYS */;
/*!40000 ALTER TABLE `servicio_realizado` ENABLE KEYS */;
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
  KEY `solicitud_ibfk_2` (`id_equipo`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud`
--

LOCK TABLES `solicitud` WRITE;
/*!40000 ALTER TABLE `solicitud` DISABLE KEYS */;
INSERT INTO `solicitud` VALUES (7,'V-31843937','Prueba',23,'2025-07-21 13:51:45','Eliminado',NULL,0),(8,'V-31843937','Prueba',14,'2025-07-23 10:39:02','Eliminado',NULL,0),(9,'V-31843937','Prueba',23,'2025-07-23 10:40:31','Eliminado',NULL,0),(10,'V-31843937','Prueba',14,'2025-07-23 10:53:53','Eliminado',NULL,0),(11,'V-31843937','Prueba',23,'2025-07-23 10:57:58','En proceso',NULL,1),(12,'V-31843937','Prueba',14,'2025-07-23 10:59:28','En proceso',NULL,1),(13,'V-31843937','Necesito',25,'2025-07-23 13:24:53','En proceso',NULL,1),(14,'V-31843937','Prueba',24,'2025-07-26 15:35:57','En proceso',NULL,1);
/*!40000 ALTER TABLE `solicitud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_compra`
--

DROP TABLE IF EXISTS `solicitud_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_compra` (
  `id_solicitud_compra` int(11) NOT NULL,
  `codigo_solicitud` varchar(20) NOT NULL,
  `cedula_solicitante` varchar(12) NOT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `motivo` varchar(200) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `observaciones` varchar(200) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_solicitud_compra`),
  UNIQUE KEY `codigo_solicitud` (`codigo_solicitud`),
  KEY `cedula_solicitante` (`cedula_solicitante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_compra`
--

LOCK TABLES `solicitud_compra` WRITE;
/*!40000 ALTER TABLE `solicitud_compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `solicitud_compra` ENABLE KEYS */;
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
  `id_piso` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo_bien`),
  UNIQUE KEY `serial` (`serial`),
  KEY `Piso_id` (`id_piso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `switch`
--

LOCK TABLES `switch` WRITE;
/*!40000 ALTER TABLE `switch` DISABLE KEYS */;
INSERT INTO `switch` VALUES ('SW-101','SN-SW101-2024',24,1),('SW-102','SN-SW102-2024',48,1),('SW-103','SN-SW103-2023',16,1),('SW-104','SN-SW104-2024',8,1),('SW-105','SN-SW105-2024',24,1),('SW-201','SN-SW201-2024',24,2),('SW-202','SN-SW202-2024',48,2),('SW-203','SN-SW203-2023',16,2),('SW-204','SN-SW204-2024',8,2),('SW-205','SN-SW205-2024',24,2),('SW-301','SN-SW301-2024',24,3),('SW-302','SN-SW302-2024',48,3),('SW-303','SN-SW303-2023',16,3),('SW-304','SN-SW304-2024',8,3),('SW-305','SN-SW305-2024',24,3),('SW-401','SN-SW401-2024',24,4),('SW-402','SN-SW402-2024',48,4),('SW-403','SN-SW403-2023',16,4),('SW-404','SN-SW404-2024',8,4),('SW-405','SN-SW405-2024',24,4),('SW-501','SN-SW501-2024',24,5),('SW-502','SN-SW502-2024',48,5),('SW-503','SN-SW503-2023',16,5),('SW-504','SN-SW504-2024',8,5),('SW-505','SN-SW505-2024',24,5),('SW-601','SN-SW601-2024',24,6),('SW-602','SN-SW602-2024',48,6),('SW-603','SN-SW603-2023',16,6),('SW-604','SN-SW604-2024',8,6),('SW-605','SN-SW605-2024',24,6);
/*!40000 ALTER TABLE `switch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_servicio`
--

DROP TABLE IF EXISTS `tipo_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_servicio` (
  `id_tipo_servicio` int(11) NOT NULL,
  `nombre_tipo_servicio` varchar(45) NOT NULL,
  `cedula_encargado` varchar(12) DEFAULT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id_tipo_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_servicio`
--

LOCK TABLES `tipo_servicio` WRITE;
/*!40000 ALTER TABLE `tipo_servicio` DISABLE KEYS */;
INSERT INTO `tipo_servicio` VALUES (1,'Soporte Técnico','V-31843937',1),(2,'Redes',NULL,1),(3,'Telefonía',NULL,1),(4,'Electrónica',NULL,1);
/*!40000 ALTER TABLE `tipo_servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidad`
--

DROP TABLE IF EXISTS `unidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidad` (
  `id_unidad` int(11) NOT NULL,
  `id_dependencia` int(11) NOT NULL,
  `nombre_unidad` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_unidad`),
  KEY `id_dependencia` (`id_dependencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidad`
--

LOCK TABLES `unidad` WRITE;
/*!40000 ALTER TABLE `unidad` DISABLE KEYS */;
INSERT INTO `unidad` VALUES (1,1,'Bienes',1),(2,2,'Seguridad',1);
/*!40000 ALTER TABLE `unidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vista_detalles_hoja`
--

DROP TABLE IF EXISTS `vista_detalles_hoja`;
/*!50001 DROP VIEW IF EXISTS `vista_detalles_hoja`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vista_detalles_hoja` AS SELECT
 1 AS `id_detalle_`,
  1 AS `codigo_hoja_servicio`,
  1 AS `componente`,
  1 AS `detalle`,
  1 AS `id_movimiento_material`,
  1 AS `id_material`,
  1 AS `cantidad`,
  1 AS `nombre_material` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vista_hoja_servicio_completa`
--

DROP TABLE IF EXISTS `vista_hoja_servicio_completa`;
/*!50001 DROP VIEW IF EXISTS `vista_hoja_servicio_completa`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vista_hoja_servicio_completa` AS SELECT
 1 AS `codigo_hoja_servicio`,
  1 AS `nro_solicitud`,
  1 AS `id_tipo_servicio`,
  1 AS `nombre_tipo_servicio`,
  1 AS `redireccion`,
  1 AS `cedula_tecnico`,
  1 AS `nombre_tecnico`,
  1 AS `fecha_resultado`,
  1 AS `resultado_hoja_servicio`,
  1 AS `observacion`,
  1 AS `estatus`,
  1 AS `motivo`,
  1 AS `fecha_solicitud`,
  1 AS `estado_solicitud`,
  1 AS `nombre_solicitante`,
  1 AS `telefono_empleado`,
  1 AS `correo_empleado`,
  1 AS `nombre_unidad`,
  1 AS `nombre_dependencia`,
  1 AS `tipo_equipo`,
  1 AS `serial`,
  1 AS `codigo_bien`,
  1 AS `nombre_marca`,
  1 AS `descripcion` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vista_hojas_servicio_completa`
--

DROP TABLE IF EXISTS `vista_hojas_servicio_completa`;
/*!50001 DROP VIEW IF EXISTS `vista_hojas_servicio_completa`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vista_hojas_servicio_completa` AS SELECT
 1 AS `codigo_hoja_servicio`,
  1 AS `nro_solicitud`,
  1 AS `id_tipo_servicio`,
  1 AS `nombre_tipo_servicio`,
  1 AS `redireccion`,
  1 AS `cedula_tecnico`,
  1 AS `nombre_tecnico`,
  1 AS `fecha_resultado`,
  1 AS `resultado_hoja_servicio`,
  1 AS `observacion`,
  1 AS `estatus`,
  1 AS `motivo`,
  1 AS `fecha_solicitud`,
  1 AS `estado_solicitud`,
  1 AS `nombre_solicitante`,
  1 AS `telefono_empleado`,
  1 AS `correo_empleado`,
  1 AS `nombre_unidad`,
  1 AS `nombre_dependencia`,
  1 AS `tipo_equipo`,
  1 AS `serial`,
  1 AS `codigo_bien`,
  1 AS `nombre_marca`,
  1 AS `descripcion` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vista_reporte_hojas_servicio`
--

DROP TABLE IF EXISTS `vista_reporte_hojas_servicio`;
/*!50001 DROP VIEW IF EXISTS `vista_reporte_hojas_servicio`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vista_reporte_hojas_servicio` AS SELECT
 1 AS `codigo_hoja_servicio`,
  1 AS `nro_solicitud`,
  1 AS `nombre_tipo_servicio`,
  1 AS `solicitante`,
  1 AS `tipo_equipo`,
  1 AS `nombre_marca`,
  1 AS `serial`,
  1 AS `codigo_bien`,
  1 AS `motivo`,
  1 AS `fecha_solicitud`,
  1 AS `resultado_hoja_servicio`,
  1 AS `observacion`,
  1 AS `estatus` */;
SET character_set_client = @saved_cs_client;

--
-- Current Database: `sigso_sistema`
--

USE `sigso_sistema`;

--
-- Final view structure for view `filtrado_empleado`
--

/*!50001 DROP VIEW IF EXISTS `filtrado_empleado`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `filtrado_empleado` AS select (select count(0) from `sigso_usuario`.`usuario` `u` where `u`.`estatus` = 1) AS `Total usuario`,(select count(0) from `sigso_sistema`.`oficina` `o` where `o`.`estatus` = 1) AS `Total oficina`,(select count(0) from ((`sigso_sistema`.`empleado` `e` join `sigso_sistema`.`unidad` `u` on(`u`.`id_unidad` = `e`.`id_unidad`)) join `sigso_sistema`.`dependencia` `d` on(`d`.`id` = `u`.`id_dependencia`)) where `e`.`estatus` = 1 and `d`.`id` = 1) AS `Total empleados OFITIC`,(select count(0) from `sigso_sistema`.`empleado` `e` where `e`.`estatus` = 1) AS `Total empleados general` from `sigso_sistema`.`empleado` `e` where `e`.`estatus` = 1 limit 0,1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `filtrado_hoja`
--

/*!50001 DROP VIEW IF EXISTS `filtrado_hoja`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `filtrado_hoja` AS select `ts`.`nombre_tipo_servicio` AS `Área con más hojas`,count(`hs`.`codigo_hoja_servicio`) AS `Cantidad de hojas`,(select count(0) from (`hoja_servicio` `sh` join `solicitud` `s` on(`s`.`nro_solicitud` = `sh`.`nro_solicitud`)) where `sh`.`estatus` = 'E' and `s`.`estatus` = 1) AS `Hojas eliminadas`,(select count(0) from (`hoja_servicio` `sh` join `solicitud` `s` on(`s`.`nro_solicitud` = `sh`.`nro_solicitud`)) where `sh`.`estatus` = 'A' and `s`.`estatus` = 1) AS `Hojas activas`,(select count(0) from (`hoja_servicio` `sh` join `solicitud` `s` on(`s`.`nro_solicitud` = `sh`.`nro_solicitud`)) where `sh`.`estatus` = 'I' and `s`.`estatus` = 1) AS `Hojas finalizadas` from ((`hoja_servicio` `hs` join `solicitud` `s` on(`s`.`nro_solicitud` = `hs`.`nro_solicitud`)) join `tipo_servicio` `ts` on(`hs`.`id_tipo_servicio` = `ts`.`id_tipo_servicio`)) where `s`.`estatus` = 1 group by `hs`.`id_tipo_servicio`,`ts`.`nombre_tipo_servicio` order by count(`hs`.`codigo_hoja_servicio`) desc limit 0,1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `filtrado_tecnico`
--

/*!50001 DROP VIEW IF EXISTS `filtrado_tecnico`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `filtrado_tecnico` AS select (select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1) AS `Total tecnicos`,(select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 1) AS `Total soporte`,(select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 2) AS `Total redes`,(select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 3) AS `Total telefono`,(select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 4) AS `Total electronica`,(select concat('CI: ',`e`.`cedula_empleado`,' - Nombre: ',`e`.`nombre_empleado`) from `empleado` `e` where `e`.`cedula_empleado` = (select `hs`.`cedula_tecnico` from `hoja_servicio` `hs` where `hs`.`estatus` = 'I' group by `hs`.`cedula_tecnico` order by count(0) desc limit 1)) AS `Tecnico eficiente` from `empleado` `e` where `e`.`estatus` = 1 limit 0,1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `hojasactivas`
--

/*!50001 DROP VIEW IF EXISTS `hojasactivas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `hojasactivas` AS select `h`.`codigo_hoja_servicio` AS `codigo_hoja_servicio`,`h`.`nro_solicitud` AS `nro_solicitud`,`h`.`id_tipo_servicio` AS `id_tipo_servicio`,`h`.`redireccion` AS `redireccion`,`h`.`cedula_tecnico` AS `cedula_tecnico`,`h`.`fecha_resultado` AS `fecha_resultado`,`h`.`resultado_hoja_servicio` AS `resultado_hoja_servicio`,`h`.`observacion` AS `observacion`,`h`.`estatus` AS `estatus`,`e`.`nombre_empleado` AS `nombre_empleado`,`e`.`apellido_empleado` AS `apellido_empleado`,`b`.`descripcion` AS `descripcion` from ((((`hoja_servicio` `h` join `solicitud` `s` on(`s`.`nro_solicitud` = `h`.`nro_solicitud`)) join `empleado` `e` on(`e`.`cedula_empleado` = `s`.`cedula_solicitante`)) join `equipo` `eq` on(`eq`.`id_equipo` = `s`.`id_equipo`)) join `bien` `b` on(`eq`.`codigo_bien` = `b`.`codigo_bien`)) where `h`.`estatus` = 'A' and `h`.`cedula_tecnico` = 'V-31843937' */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_detalles_hoja`
--

/*!50001 DROP VIEW IF EXISTS `vista_detalles_hoja`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_detalles_hoja` AS select `dh`.`id_detalle_` AS `id_detalle_`,`dh`.`codigo_hoja_servicio` AS `codigo_hoja_servicio`,`dh`.`componente` AS `componente`,`dh`.`detalle` AS `detalle`,`dh`.`id_movimiento_material` AS `id_movimiento_material`,`mm`.`id_material` AS `id_material`,`mm`.`cantidad` AS `cantidad`,`mat`.`nombre_material` AS `nombre_material` from ((`detalle_hoja` `dh` left join `movimiento_materiales` `mm` on(`dh`.`id_movimiento_material` = `mm`.`id_movimiento_material`)) left join `material` `mat` on(`mm`.`id_material` = `mat`.`id_material`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_hoja_servicio_completa`
--

/*!50001 DROP VIEW IF EXISTS `vista_hoja_servicio_completa`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_hoja_servicio_completa` AS select `hs`.`codigo_hoja_servicio` AS `codigo_hoja_servicio`,`hs`.`nro_solicitud` AS `nro_solicitud`,`hs`.`id_tipo_servicio` AS `id_tipo_servicio`,`ts`.`nombre_tipo_servicio` AS `nombre_tipo_servicio`,`hs`.`redireccion` AS `redireccion`,`hs`.`cedula_tecnico` AS `cedula_tecnico`,concat(coalesce(`tec`.`nombre_empleado`,''),' ',coalesce(`tec`.`apellido_empleado`,'')) AS `nombre_tecnico`,`hs`.`fecha_resultado` AS `fecha_resultado`,`hs`.`resultado_hoja_servicio` AS `resultado_hoja_servicio`,`hs`.`observacion` AS `observacion`,`hs`.`estatus` AS `estatus`,`s`.`motivo` AS `motivo`,`s`.`fecha_solicitud` AS `fecha_solicitud`,`s`.`estado_solicitud` AS `estado_solicitud`,concat(coalesce(`sol`.`nombre_empleado`,''),' ',coalesce(`sol`.`apellido_empleado`,'')) AS `nombre_solicitante`,coalesce(`sol`.`telefono_empleado`,'N/A') AS `telefono_empleado`,coalesce(`sol`.`correo_empleado`,'N/A') AS `correo_empleado`,coalesce(`u`.`nombre_unidad`,'N/A') AS `nombre_unidad`,coalesce(`d`.`nombre`,'N/A') AS `nombre_dependencia`,coalesce(`e`.`tipo_equipo`,'N/A') AS `tipo_equipo`,coalesce(`e`.`serial`,'N/A') AS `serial`,coalesce(`b`.`codigo_bien`,'N/A') AS `codigo_bien`,coalesce(`m`.`nombre_marca`,'N/A') AS `nombre_marca`,coalesce(`b`.`descripcion`,'N/A') AS `descripcion` from (((((((((`hoja_servicio` `hs` join `solicitud` `s` on(`hs`.`nro_solicitud` = `s`.`nro_solicitud`)) join `tipo_servicio` `ts` on(`hs`.`id_tipo_servicio` = `ts`.`id_tipo_servicio`)) join `empleado` `sol` on(`s`.`cedula_solicitante` = `sol`.`cedula_empleado`)) left join `empleado` `tec` on(`hs`.`cedula_tecnico` = `tec`.`cedula_empleado`)) left join `unidad` `u` on(`sol`.`id_unidad` = `u`.`id_unidad`)) left join `dependencia` `d` on(`u`.`id_dependencia` = `d`.`id`)) left join `equipo` `e` on(`s`.`id_equipo` = `e`.`id_equipo`)) left join `bien` `b` on(`e`.`codigo_bien` = `b`.`codigo_bien`)) left join `marca` `m` on(`b`.`id_marca` = `m`.`id_marca`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_hojas_servicio_completa`
--

/*!50001 DROP VIEW IF EXISTS `vista_hojas_servicio_completa`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_hojas_servicio_completa` AS select `hs`.`codigo_hoja_servicio` AS `codigo_hoja_servicio`,`hs`.`nro_solicitud` AS `nro_solicitud`,`hs`.`id_tipo_servicio` AS `id_tipo_servicio`,`ts`.`nombre_tipo_servicio` AS `nombre_tipo_servicio`,`hs`.`redireccion` AS `redireccion`,`hs`.`cedula_tecnico` AS `cedula_tecnico`,concat(coalesce(`tec`.`nombre_empleado`,''),' ',coalesce(`tec`.`apellido_empleado`,'')) AS `nombre_tecnico`,`hs`.`fecha_resultado` AS `fecha_resultado`,`hs`.`resultado_hoja_servicio` AS `resultado_hoja_servicio`,`hs`.`observacion` AS `observacion`,`hs`.`estatus` AS `estatus`,`s`.`motivo` AS `motivo`,`s`.`fecha_solicitud` AS `fecha_solicitud`,`s`.`estado_solicitud` AS `estado_solicitud`,concat(coalesce(`sol`.`nombre_empleado`,''),' ',coalesce(`sol`.`apellido_empleado`,'')) AS `nombre_solicitante`,coalesce(`sol`.`telefono_empleado`,'N/A') AS `telefono_empleado`,coalesce(`sol`.`correo_empleado`,'N/A') AS `correo_empleado`,coalesce(`u`.`nombre_unidad`,'N/A') AS `nombre_unidad`,coalesce(`d`.`nombre`,'N/A') AS `nombre_dependencia`,coalesce(`e`.`tipo_equipo`,'N/A') AS `tipo_equipo`,coalesce(`e`.`serial`,'N/A') AS `serial`,coalesce(`b`.`codigo_bien`,'N/A') AS `codigo_bien`,coalesce(`m`.`nombre_marca`,'N/A') AS `nombre_marca`,coalesce(`b`.`descripcion`,'N/A') AS `descripcion` from (((((((((`hoja_servicio` `hs` join `solicitud` `s` on(`hs`.`nro_solicitud` = `s`.`nro_solicitud`)) join `tipo_servicio` `ts` on(`hs`.`id_tipo_servicio` = `ts`.`id_tipo_servicio`)) join `empleado` `sol` on(`s`.`cedula_solicitante` = `sol`.`cedula_empleado`)) left join `empleado` `tec` on(`hs`.`cedula_tecnico` = `tec`.`cedula_empleado`)) left join `unidad` `u` on(`sol`.`id_unidad` = `u`.`id_unidad`)) left join `dependencia` `d` on(`u`.`id_dependencia` = `d`.`id`)) left join `equipo` `e` on(`s`.`id_equipo` = `e`.`id_equipo`)) left join `bien` `b` on(`e`.`codigo_bien` = `b`.`codigo_bien`)) left join `marca` `m` on(`b`.`id_marca` = `m`.`id_marca`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_reporte_hojas_servicio`
--

/*!50001 DROP VIEW IF EXISTS `vista_reporte_hojas_servicio`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_reporte_hojas_servicio` AS select `hs`.`codigo_hoja_servicio` AS `codigo_hoja_servicio`,`hs`.`nro_solicitud` AS `nro_solicitud`,`ts`.`nombre_tipo_servicio` AS `nombre_tipo_servicio`,concat(`sol`.`nombre_empleado`,' ',`sol`.`apellido_empleado`) AS `solicitante`,`e`.`tipo_equipo` AS `tipo_equipo`,`m`.`nombre_marca` AS `nombre_marca`,`e`.`serial` AS `serial`,`b`.`codigo_bien` AS `codigo_bien`,`s`.`motivo` AS `motivo`,`s`.`fecha_solicitud` AS `fecha_solicitud`,`hs`.`resultado_hoja_servicio` AS `resultado_hoja_servicio`,`hs`.`observacion` AS `observacion`,`hs`.`estatus` AS `estatus` from ((((((`hoja_servicio` `hs` join `solicitud` `s` on(`hs`.`nro_solicitud` = `s`.`nro_solicitud`)) join `tipo_servicio` `ts` on(`hs`.`id_tipo_servicio` = `ts`.`id_tipo_servicio`)) join `empleado` `sol` on(`s`.`cedula_solicitante` = `sol`.`cedula_empleado`)) left join `equipo` `e` on(`s`.`id_equipo` = `e`.`id_equipo`)) left join `bien` `b` on(`e`.`codigo_bien` = `b`.`codigo_bien`)) left join `marca` `m` on(`b`.`id_marca` = `m`.`id_marca`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-20 21:44:20
