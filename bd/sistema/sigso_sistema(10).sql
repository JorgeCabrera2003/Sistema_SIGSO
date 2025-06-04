-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2025 at 01:13 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sigso_sistema`
--
CREATE DATABASE IF NOT EXISTS `sigso_sistema` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `sigso_sistema`;

-- --------------------------------------------------------

--
-- Table structure for table `bien`
--

CREATE TABLE `bien` (
  `codigo_bien` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `id_tipo_bien` int NOT NULL,
  `id_marca` int DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `cedula_empleado` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_oficina` int NOT NULL,
  `estatus` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bien`
--

INSERT INTO `bien` (`codigo_bien`, `id_tipo_bien`, `id_marca`, `descripcion`, `estado`, `cedula_empleado`, `id_oficina`, `estatus`) VALUES
('JK2450', 1, 3, 'Ejemplo', 'Usado', 'V-30587785', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cargo`
--

CREATE TABLE `cargo` (
  `id_cargo` int NOT NULL,
  `nombre_cargo` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `estatus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cargo`
--

INSERT INTO `cargo` (`id_cargo`, `nombre_cargo`, `estatus`) VALUES
(1, 'Técnico', 1),
(2, 'Director de Telefonía', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dependencia`
--

CREATE TABLE `dependencia` (
  `id` int NOT NULL,
  `id_ente` int NOT NULL,
  `nombre` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `estatus` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dependencia`
--

INSERT INTO `dependencia` (`id`, `id_ente`, `nombre`, `estatus`) VALUES
(1, 1, 'OFITIC', 1),
(2, 1, 'Contraloría', 1);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_hoja`
--

CREATE TABLE `detalle_hoja` (
  `codigo_hoja_servicio` int NOT NULL,
  `componente` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `detalle` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_movimiento_material` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empleado`
--

CREATE TABLE `empleado` (
  `cedula_empleado` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre_empleado` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `apellido_empleado` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `id_cargo` int DEFAULT NULL,
  `id_servicio` int DEFAULT NULL,
  `id_unidad` int NOT NULL,
  `telefono_empleado` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo_empleado` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `empleado`
--

INSERT INTO `empleado` (`cedula_empleado`, `nombre_empleado`, `apellido_empleado`, `id_cargo`, `id_servicio`, `id_unidad`, `telefono_empleado`, `correo_empleado`) VALUES
('V-1234567', 'Maria', 'Peres', NULL, NULL, 1, '0426-5575858', 'prueba@gmail.com'),
('V-21140325', 'Félix', 'Mujica', NULL, NULL, 1, '0400-0000000', 'ejemplo@gmail.com'),
('V-30266398', 'Leizer', 'Torrealba', NULL, NULL, 1, '0416-0506544', 'leizeraponte2020@gmail.com'),
('V-30454597', 'Franklin', 'Fonseca', NULL, NULL, 1, '0424-5041921', 'franklinjavierfonsecavasquez@gmail.com'),
('V-30587785', 'Mariangel', 'Bokor', NULL, NULL, 1, '0424-5319088', 'bokorarcangel447@gmail.com'),
('V-31843937', 'Jorge', 'Cabrera', NULL, NULL, 1, '0424-5567016', 'cabrerajorge2003@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `ente`
--

CREATE TABLE `ente` (
  `id` int NOT NULL,
  `nombre` varchar(90) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre_responsable` varchar(65) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estatus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ente`
--

INSERT INTO `ente` (`id`, `nombre`, `direccion`, `telefono`, `nombre_responsable`, `estatus`) VALUES
(1, 'Gobernación', '', '', '', 1),
(3, 'Teatro Juaréz', '', '', '', 1),
(4, 'Parque Baradida', 'Carrera 18 con calle 55 y 54', '0251-0070881', 'Ricardo Guzmán', 1);

-- --------------------------------------------------------

--
-- Table structure for table `equipo`
--

CREATE TABLE `equipo` (
  `id_equipo` int NOT NULL,
  `tipo_equipo` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `serial` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `codigo_bien` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_unidad` int DEFAULT NULL,
  `estatus` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hoja_servicio`
--

CREATE TABLE `hoja_servicio` (
  `codigo_hoja_servicio` int NOT NULL,
  `nro_solicitud` int NOT NULL,
  `id_tipo_servicio` int NOT NULL,
  `redireccion` int DEFAULT NULL,
  `cedula_tecnico` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_resultado` datetime NOT NULL,
  `resultado_hoja_servicio` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observacion` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estatus` varchar(1) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interconexion`
--

CREATE TABLE `interconexion` (
  `id_interconexion` int NOT NULL,
  `codigo_switch` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `codigo_patch_panel` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `puerto_switch` int NOT NULL,
  `puerto_patch_panel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marca`
--

CREATE TABLE `marca` (
  `id_marca` int NOT NULL,
  `nombre_marca` varchar(45) COLLATE utf8mb3_unicode_ci NOT NULL,
  `estatus` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `marca`
--

INSERT INTO `marca` (`id_marca`, `nombre_marca`, `estatus`) VALUES
(1, 'Lenovo', 1),
(2, 'HP', 1),
(3, 'SAMSUNG', 1),
(4, 'VIT', 1),
(5, 'Apple', 1),
(6, 'OPPO', 0);

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `id_material` int NOT NULL,
  `ubicacion` int DEFAULT NULL,
  `nombre_material` varchar(45) COLLATE utf8mb3_unicode_ci NOT NULL,
  `stock` int NOT NULL,
  `estatus` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movimiento_materiales`
--

CREATE TABLE `movimiento_materiales` (
  `id_movimiento_material` int NOT NULL,
  `id_material` int NOT NULL,
  `accion` varchar(45) COLLATE utf8mb3_unicode_ci NOT NULL,
  `cantidad` int NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oficina`
--

CREATE TABLE `oficina` (
  `id_oficina` int NOT NULL,
  `id_piso` int NOT NULL,
  `nombre_oficina` varchar(45) COLLATE utf8mb3_unicode_ci NOT NULL,
  `estatus` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `oficina`
--

INSERT INTO `oficina` (`id_oficina`, `id_piso`, `nombre_oficina`, `estatus`) VALUES
(1, 1, 'Taller 1', 1),
(2, 2, 'Taller 2', 1),
(3, 1, 'Oficina', 1),
(4, 1, 'Depósito', 1),
(5, 3, 'Taller de Electrónica', 1);

-- --------------------------------------------------------

--
-- Table structure for table `patch_panel`
--

CREATE TABLE `patch_panel` (
  `codigo_bien` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `serial` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_patch_panel` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad_puertos` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `piso`
--

CREATE TABLE `piso` (
  `id_piso` int NOT NULL,
  `tipo_piso` varchar(45) COLLATE utf8mb3_unicode_ci NOT NULL,
  `nro_piso` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL,
  `estatus` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `piso`
--

INSERT INTO `piso` (`id_piso`, `tipo_piso`, `nro_piso`, `estatus`) VALUES
(1, 'Planta Baja', '0', 1),
(2, 'Piso', '1', 0),
(3, 'Piso', '1', 1),
(4, 'Sótano', '2', 1),
(5, 'Piso', '2', 1),
(6, 'Piso', '3', 1),
(7, 'Sótano', '1', 1),
(8, 'Piso', '4', 1),
(9, 'Piso', '5', 1),
(10, 'Piso', '6', 1),
(11, 'Piso', '7', 1),
(12, 'Terraza', '10', 1),
(13, 'Sótano', '3', 1),
(14, 'Sótano', '4', 1),
(15, 'Sótano', '5', 0),
(16, 'Sótano', '6', 0),
(17, 'Sótano', '7', 0),
(18, 'Terraza', '8', 0);

-- --------------------------------------------------------

--
-- Table structure for table `punto_conexion`
--

CREATE TABLE `punto_conexion` (
  `id_punto_conexion` int NOT NULL,
  `codigo_patch_panel` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `id_equipo` int DEFAULT NULL,
  `puerto_patch_panel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `solicitud`
--

CREATE TABLE `solicitud` (
  `nro_solicitud` int NOT NULL,
  `cedula_solicitante` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `motivo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `id_equipo` int DEFAULT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_solicitud` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pendiente',
  `resultado_solicitud` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estatus` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solicitud`
--

INSERT INTO `solicitud` (`nro_solicitud`, `cedula_solicitante`, `motivo`, `id_equipo`, `fecha_solicitud`, `estado_solicitud`, `resultado_solicitud`, `estatus`) VALUES
(43, 'V-31843937', 'Prueba', NULL, '2025-06-04 09:11:26', 'En Proceso', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `switch`
--

CREATE TABLE `switch` (
  `codigo_bien` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `serial` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad_puertos` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipo_bien`
--

CREATE TABLE `tipo_bien` (
  `id_tipo_bien` int NOT NULL,
  `nombre_tipo_bien` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `estatus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipo_bien`
--

INSERT INTO `tipo_bien` (`id_tipo_bien`, `nombre_tipo_bien`, `estatus`) VALUES
(1, 'Electrónico', 1),
(2, 'Mueble', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_servicio`
--

CREATE TABLE `tipo_servicio` (
  `id_tipo_servicio` int NOT NULL,
  `nombre_tipo_servicio` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `estatus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipo_servicio`
--

INSERT INTO `tipo_servicio` (`id_tipo_servicio`, `nombre_tipo_servicio`, `estatus`) VALUES
(1, 'Soporte Técnico', 0),
(2, 'Redes', 0),
(3, 'Telefonía', 0),
(4, 'Electrónica', 0);

-- --------------------------------------------------------

--
-- Table structure for table `unidad`
--

CREATE TABLE `unidad` (
  `id_unidad` int NOT NULL,
  `id_dependencia` int NOT NULL,
  `nombre_unidad` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `estatus` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unidad`
--

INSERT INTO `unidad` (`id_unidad`, `id_dependencia`, `nombre_unidad`, `estatus`) VALUES
(1, 1, 'Bienes', 1),
(2, 1, 'Seguridad', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bien`
--
ALTER TABLE `bien`
  ADD PRIMARY KEY (`codigo_bien`),
  ADD KEY `id_tipo_bien` (`id_tipo_bien`),
  ADD KEY `cedula_empleado` (`cedula_empleado`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_oficina` (`id_oficina`);

--
-- Indexes for table `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indexes for table `dependencia`
--
ALTER TABLE `dependencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ente` (`id_ente`);

--
-- Indexes for table `detalle_hoja`
--
ALTER TABLE `detalle_hoja`
  ADD PRIMARY KEY (`codigo_hoja_servicio`),
  ADD KEY `id_movimiento_material` (`id_movimiento_material`);

--
-- Indexes for table `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`cedula_empleado`),
  ADD KEY `empleado_ibfk_1` (`id_unidad`),
  ADD KEY `tipo` (`id_cargo`),
  ADD KEY `empleado_ibfk_4` (`id_servicio`);

--
-- Indexes for table `ente`
--
ALTER TABLE `ente`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id_equipo`),
  ADD UNIQUE KEY `serial` (`serial`),
  ADD KEY `equipo_ibfk_2` (`id_unidad`),
  ADD KEY `nro_bien` (`codigo_bien`);

--
-- Indexes for table `hoja_servicio`
--
ALTER TABLE `hoja_servicio`
  ADD PRIMARY KEY (`codigo_hoja_servicio`),
  ADD KEY `hoja_servicio_ibfk_1` (`nro_solicitud`),
  ADD KEY `hoja_servicio_ibfk_2` (`id_tipo_servicio`),
  ADD KEY `redireccion` (`redireccion`),
  ADD KEY `id_tipo_servicio` (`id_tipo_servicio`),
  ADD KEY `cedula_tecnico` (`cedula_tecnico`);

--
-- Indexes for table `interconexion`
--
ALTER TABLE `interconexion`
  ADD PRIMARY KEY (`id_interconexion`),
  ADD KEY `codigo_switch` (`codigo_switch`),
  ADD KEY `codigo_patch_panel_2` (`codigo_patch_panel`);

--
-- Indexes for table `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id_material`),
  ADD KEY `material_ibfk_1` (`ubicacion`);

--
-- Indexes for table `movimiento_materiales`
--
ALTER TABLE `movimiento_materiales`
  ADD PRIMARY KEY (`id_movimiento_material`),
  ADD KEY `id_material` (`id_material`);

--
-- Indexes for table `oficina`
--
ALTER TABLE `oficina`
  ADD PRIMARY KEY (`id_oficina`),
  ADD KEY `id_piso` (`id_piso`);

--
-- Indexes for table `patch_panel`
--
ALTER TABLE `patch_panel`
  ADD PRIMARY KEY (`codigo_bien`),
  ADD UNIQUE KEY `serial` (`serial`),
  ADD KEY `codigo_bien` (`codigo_bien`);

--
-- Indexes for table `piso`
--
ALTER TABLE `piso`
  ADD PRIMARY KEY (`id_piso`);

--
-- Indexes for table `punto_conexion`
--
ALTER TABLE `punto_conexion`
  ADD PRIMARY KEY (`id_punto_conexion`),
  ADD KEY `codigo_patch` (`codigo_patch_panel`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indexes for table `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`nro_solicitud`),
  ADD KEY `solicitud_ibfk_1` (`cedula_solicitante`),
  ADD KEY `solicitud_ibfk_2` (`id_equipo`);

--
-- Indexes for table `switch`
--
ALTER TABLE `switch`
  ADD PRIMARY KEY (`codigo_bien`),
  ADD UNIQUE KEY `serial` (`serial`);

--
-- Indexes for table `tipo_bien`
--
ALTER TABLE `tipo_bien`
  ADD PRIMARY KEY (`id_tipo_bien`);

--
-- Indexes for table `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  ADD PRIMARY KEY (`id_tipo_servicio`);

--
-- Indexes for table `unidad`
--
ALTER TABLE `unidad`
  ADD PRIMARY KEY (`id_unidad`),
  ADD KEY `id_dependencia` (`id_dependencia`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id_cargo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dependencia`
--
ALTER TABLE `dependencia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ente`
--
ALTER TABLE `ente`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_equipo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hoja_servicio`
--
ALTER TABLE `hoja_servicio`
  MODIFY `codigo_hoja_servicio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interconexion`
--
ALTER TABLE `interconexion`
  MODIFY `id_interconexion` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `id_material` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movimiento_materiales`
--
ALTER TABLE `movimiento_materiales`
  MODIFY `id_movimiento_material` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oficina`
--
ALTER TABLE `oficina`
  MODIFY `id_oficina` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `piso`
--
ALTER TABLE `piso`
  MODIFY `id_piso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `punto_conexion`
--
ALTER TABLE `punto_conexion`
  MODIFY `id_punto_conexion` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `nro_solicitud` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tipo_bien`
--
ALTER TABLE `tipo_bien`
  MODIFY `id_tipo_bien` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  MODIFY `id_tipo_servicio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `unidad`
--
ALTER TABLE `unidad`
  MODIFY `id_unidad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bien`
--
ALTER TABLE `bien`
  ADD CONSTRAINT `bien_ibfk_1` FOREIGN KEY (`cedula_empleado`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `bien_ibfk_4` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `bien_ibfk_5` FOREIGN KEY (`id_tipo_bien`) REFERENCES `tipo_bien` (`id_tipo_bien`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bien_ibfk_6` FOREIGN KEY (`id_oficina`) REFERENCES `oficina` (`id_oficina`) ON DELETE CASCADE;

--
-- Constraints for table `dependencia`
--
ALTER TABLE `dependencia`
  ADD CONSTRAINT `dependencia_ibfk_1` FOREIGN KEY (`id_ente`) REFERENCES `ente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_hoja`
--
ALTER TABLE `detalle_hoja`
  ADD CONSTRAINT `detalle_hoja_ibfk_1` FOREIGN KEY (`codigo_hoja_servicio`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_hoja_ibfk_2` FOREIGN KEY (`id_movimiento_material`) REFERENCES `movimiento_materiales` (`id_movimiento_material`);

--
-- Constraints for table `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`id_unidad`) REFERENCES `unidad` (`id_unidad`);

--
-- Constraints for table `equipo`
--
ALTER TABLE `equipo`
  ADD CONSTRAINT `equipo_ibfk_3` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `equipo_ibfk_4` FOREIGN KEY (`id_unidad`) REFERENCES `unidad` (`id_unidad`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `hoja_servicio`
--
ALTER TABLE `hoja_servicio`
  ADD CONSTRAINT `hoja_servicio_ibfk_1` FOREIGN KEY (`nro_solicitud`) REFERENCES `solicitud` (`nro_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hoja_servicio_ibfk_2` FOREIGN KEY (`cedula_tecnico`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hoja_servicio_ibfk_3` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hoja_servicio_ibfk_4` FOREIGN KEY (`redireccion`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `interconexion`
--
ALTER TABLE `interconexion`
  ADD CONSTRAINT `interconexion_ibfk_1` FOREIGN KEY (`codigo_switch`) REFERENCES `switch` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `interconexion_ibfk_2` FOREIGN KEY (`codigo_patch_panel`) REFERENCES `patch_panel` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `material_ibfk_1` FOREIGN KEY (`ubicacion`) REFERENCES `oficina` (`id_oficina`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `movimiento_materiales`
--
ALTER TABLE `movimiento_materiales`
  ADD CONSTRAINT `movimiento_materiales_ibfk_1` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `oficina`
--
ALTER TABLE `oficina`
  ADD CONSTRAINT `oficina_ibfk_1` FOREIGN KEY (`id_piso`) REFERENCES `piso` (`id_piso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patch_panel`
--
ALTER TABLE `patch_panel`
  ADD CONSTRAINT `patch_panel_ibfk_1` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `punto_conexion`
--
ALTER TABLE `punto_conexion`
  ADD CONSTRAINT `punto_conexion_ibfk_1` FOREIGN KEY (`codigo_patch_panel`) REFERENCES `patch_panel` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `punto_conexion_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `solicitud_ibfk_1` FOREIGN KEY (`cedula_solicitante`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `switch`
--
ALTER TABLE `switch`
  ADD CONSTRAINT `switch_ibfk_1` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `unidad`
--
ALTER TABLE `unidad`
  ADD CONSTRAINT `unidad_ibfk_1` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
