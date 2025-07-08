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
INSERT INTO `bien` VALUES ('0001',1,1,'Impresora rh200','Nuevo','V-21140325',1,1),('0002',1,4,'Computador Oficina','Nuevo','V-31843937',2,1),('0003',2,1,'Mesa ejecutiva','Usado','V-21140325',3,1),('0004',1,2,'Patch panel rojo 45k','Nuevo','V-30587785',5,1),('0005',1,2,'Pacth panel 500tc','Nuevo','V-30587785',4,1),('0006',1,1,'Switch prt24','null','V-30587785',3,1),('0007',1,2,'Switch prt27','null','V-30587785',3,1),('0008',1,2,'Switch rt45','null','V-30587785',3,1);
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
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER tr_before_insert_detalle_hoja
BEFORE INSERT ON detalle_hoja
FOR EACH ROW
BEGIN
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
  KEY `id_material` (`id_material`),
  CONSTRAINT `detalle_solicitud_compra_ibfk_1` FOREIGN KEY (`id_solicitud_compra`) REFERENCES `solicitud_compra` (`id_solicitud_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_solicitud_compra_ibfk_2` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`) ON DELETE SET NULL ON UPDATE CASCADE
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
  KEY `nro_bien` (`codigo_bien`),
  CONSTRAINT `equipo_ibfk_3` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `equipo_ibfk_4` FOREIGN KEY (`id_unidad`) REFERENCES `unidad` (`id_unidad`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo`
--

LOCK TABLES `equipo` WRITE;
/*!40000 ALTER TABLE `equipo` DISABLE KEYS */;
INSERT INTO `equipo` VALUES (13,'Empresarial','000001','0001',1,1),(14,'Computador','00002','0002',2,1);
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
  KEY `cedula_tecnico` (`cedula_tecnico`),
  CONSTRAINT `hoja_servicio_ibfk_1` FOREIGN KEY (`nro_solicitud`) REFERENCES `solicitud` (`nro_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hoja_servicio_ibfk_2` FOREIGN KEY (`cedula_tecnico`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hoja_servicio_ibfk_3` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `hoja_servicio_ibfk_4` FOREIGN KEY (`redireccion`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hoja_servicio`
--

LOCK TABLES `hoja_servicio` WRITE;
/*!40000 ALTER TABLE `hoja_servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `hoja_servicio` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER tr_after_insert_hoja_servicio
AFTER INSERT ON hoja_servicio
FOR EACH ROW
BEGIN
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
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER tr_after_update_hoja_servicio
AFTER UPDATE ON hoja_servicio
FOR EACH ROW
BEGIN
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
  KEY `id_material` (`id_material`),
  CONSTRAINT `movimiento_materiales_ibfk_1` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`) ON DELETE CASCADE ON UPDATE CASCADE
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
  KEY `id_piso` (`id_piso`),
  CONSTRAINT `oficina_ibfk_1` FOREIGN KEY (`id_piso`) REFERENCES `piso` (`id_piso`) ON DELETE CASCADE ON UPDATE CASCADE
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
  KEY `Piso` (`id_piso`),
  CONSTRAINT `Piso` FOREIGN KEY (`id_piso`) REFERENCES `piso` (`id_piso`),
  CONSTRAINT `patch_panel_ibfk_1` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patch_panel`
--

LOCK TABLES `patch_panel` WRITE;
/*!40000 ALTER TABLE `patch_panel` DISABLE KEYS */;
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
  KEY `id_equipo` (`id_equipo`),
  CONSTRAINT `punto_conexion_ibfk_1` FOREIGN KEY (`codigo_patch_panel`) REFERENCES `patch_panel` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `punto_conexion_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud`
--

LOCK TABLES `solicitud` WRITE;
/*!40000 ALTER TABLE `solicitud` DISABLE KEYS */;
/*!40000 ALTER TABLE `solicitud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_compra`
--

DROP TABLE IF EXISTS `solicitud_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitud_compra` (
  `id_solicitud_compra` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_solicitud` varchar(20) NOT NULL,
  `cedula_solicitante` varchar(12) NOT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `motivo` varchar(200) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `observaciones` varchar(200) DEFAULT NULL,
  `id_tipo_servicio` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_solicitud_compra`),
  UNIQUE KEY `codigo_solicitud` (`codigo_solicitud`),
  KEY `cedula_solicitante` (`cedula_solicitante`),
  KEY `id_tipo_servicio` (`id_tipo_servicio`),
  CONSTRAINT `solicitud_compra_ibfk_1` FOREIGN KEY (`cedula_solicitante`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `solicitud_compra_ibfk_2` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE SET NULL ON UPDATE CASCADE
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
  KEY `Piso_id` (`id_piso`),
  CONSTRAINT `Piso_id` FOREIGN KEY (`id_piso`) REFERENCES `piso` (`id_piso`),
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
  `cedula_encargado` varchar(12) DEFAULT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id_tipo_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_servicio`
--

LOCK TABLES `tipo_servicio` WRITE;
/*!40000 ALTER TABLE `tipo_servicio` DISABLE KEYS */;
INSERT INTO `tipo_servicio` VALUES (1,'Soporte Técnico',NULL,1),(2,'Redes',NULL,1),(3,'Telefonía',NULL,1),(4,'Electrónica',NULL,1);
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
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `filtrado_empleado` AS select (select count(0) from `sigso_usuario`.`usuario` `u` where `u`.`estatus` = 1) AS `Total usuario`,(select count(0) from `sigso_sistema`.`oficina` `o` where `o`.`estatus` = 1) AS `Total oficina`,(select count(0) from ((`sigso_sistema`.`empleado` `e` join `sigso_sistema`.`unidad` `u` on(`u`.`id_unidad` = `e`.`id_unidad`)) join `sigso_sistema`.`dependencia` `d` on(`d`.`id` = `u`.`id_dependencia`)) where `e`.`estatus` = 1 and `d`.`id` = 1) AS `Total empleados OFITIC`,(select count(0) from `sigso_sistema`.`empleado` `e` where `e`.`estatus` = 1) AS `Total empleados general` from `sigso_sistema`.`empleado` `e` where `e`.`estatus` = 1 limit 1 */;
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
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `filtrado_hoja` AS select `ts`.`nombre_tipo_servicio` AS `Área con más hojas`,count(`hs`.`codigo_hoja_servicio`) AS `Cantidad de hojas`,(select count(0) from (`hoja_servicio` `sh` join `solicitud` `s` on(`s`.`nro_solicitud` = `sh`.`nro_solicitud`)) where `sh`.`estatus` = 'E' and `s`.`estatus` = 1) AS `Hojas eliminadas`,(select count(0) from (`hoja_servicio` `sh` join `solicitud` `s` on(`s`.`nro_solicitud` = `sh`.`nro_solicitud`)) where `sh`.`estatus` = 'A' and `s`.`estatus` = 1) AS `Hojas activas`,(select count(0) from (`hoja_servicio` `sh` join `solicitud` `s` on(`s`.`nro_solicitud` = `sh`.`nro_solicitud`)) where `sh`.`estatus` = 'I' and `s`.`estatus` = 1) AS `Hojas finalizadas` from ((`hoja_servicio` `hs` join `solicitud` `s` on(`s`.`nro_solicitud` = `hs`.`nro_solicitud`)) join `tipo_servicio` `ts` on(`hs`.`id_tipo_servicio` = `ts`.`id_tipo_servicio`)) where `s`.`estatus` = 1 group by `hs`.`id_tipo_servicio`,`ts`.`nombre_tipo_servicio` order by count(`hs`.`codigo_hoja_servicio`) desc limit 1 */;
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
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `filtrado_tecnico` AS select (select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1) AS `Total tecnicos`,(select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 1) AS `Total soporte`,(select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 2) AS `Total redes`,(select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 3) AS `Total telefono`,(select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 4) AS `Total electronica`,(select concat('CI: ',`e`.`cedula_empleado`,' - Nombre: ',`e`.`nombre_empleado`) from `empleado` `e` where `e`.`cedula_empleado` = (select `hs`.`cedula_tecnico` from `hoja_servicio` `hs` where `hs`.`estatus` = 'I' group by `hs`.`cedula_tecnico` order by count(0) desc limit 1)) AS `Tecnico eficiente` from `empleado` `e` where `e`.`estatus` = 1 limit 1 */;
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
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
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
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
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
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
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
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
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

-- Dump completed on 2025-07-07 20:05:12
