-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-05-2025 a las 05:07:22
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sigso_sistema`
--
CREATE DATABASE `sigso_sistema`;
USE `sigso_sistema`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bien`
--

CREATE TABLE `bien` (
  `codigo_bien` varchar(20) NOT NULL,
  `id_tipo_bien` int(11) NOT NULL,
  `id_marca` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `estado` varchar(45) NOT NULL,
  `cedula_empleado` varchar(12) DEFAULT NULL,
  `id_oficina` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bien`
--

INSERT INTO `bien` (`codigo_bien`, `id_tipo_bien`, `id_marca`, `descripcion`, `estado`, `cedula_empleado`, `id_oficina`, `estatus`) VALUES
('JK2450', 1, 3, 'Ejemplo', 'Usado', 'V-30587785', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id_cargo` int(11) NOT NULL,
  `nombre_cargo` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id_cargo`, `nombre_cargo`, `estatus`) VALUES
(1, 'Técnico', 1),
(2, 'Director de Telefonía', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencia`
--

CREATE TABLE `dependencia` (
  `id` int(11) NOT NULL,
  `id_ente` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `estatus` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dependencia`
--

INSERT INTO `dependencia` (`id`, `id_ente`, `nombre`, `estatus`) VALUES
(1, 1, 'OFITIC', 1),
(2, 1, 'Contraloría', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_hoja`
--

CREATE TABLE `detalle_hoja` (
  `codigo_hoja_servicio` int(11) NOT NULL,
  `componente` varchar(100) DEFAULT NULL,
  `detalle` varchar(200) DEFAULT NULL,
  `id_movimiento_material` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `cedula_empleado` varchar(12) NOT NULL,
  `nombre_empleado` varchar(45) NOT NULL,
  `apellido_empleado` varchar(45) NOT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `id_unidad` int(11) NOT NULL,
  `telefono_empleado` varchar(15) DEFAULT NULL,
  `correo_empleado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
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
-- Estructura de tabla para la tabla `ente`
--

CREATE TABLE `ente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(90) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `nombre_responsable` varchar(65) DEFAULT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ente`
--

INSERT INTO `ente` (`id`, `nombre`, `direccion`, `telefono`, `nombre_responsable`, `estatus`) VALUES
(1, 'Gobernación', '', '', '', 1),
(3, 'Teatro Juaréz', '', '', '', 1),
(4, 'Parque Baradida', 'Carrera 18 con calle 55 y 54', '0251-0070881', 'Ricardo Guzmán', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id_equipo` int(11) NOT NULL,
  `tipo_equipo` varchar(45) DEFAULT NULL,
  `serial` varchar(45) DEFAULT NULL,
  `codigo_bien` varchar(20) DEFAULT NULL,
  `id_unidad` int(11) DEFAULT NULL,
  `estatus` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoja_servicio`
--

CREATE TABLE `hoja_servicio` (
  `codigo_hoja_servicio` int(11) NOT NULL,
  `nro_solicitud` int(11) NOT NULL,
  `id_tipo_servicio` int(11) NOT NULL,
  `redireccion` int(11) DEFAULT NULL,
  `cedula_tecnico` varchar(12) NOT NULL,
  `fecha_resultado` datetime NOT NULL,
  `resultado_hoja_servicio` varchar(45) DEFAULT NULL,
  `observacion` varchar(200) DEFAULT NULL,
  `estatus` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interconexion`
--

CREATE TABLE `interconexion` (
  `id_interconexion` int(11) NOT NULL,
  `codigo_switch` varchar(20) NOT NULL,
  `codigo_patch_panel` varchar(20) NOT NULL,
  `puerto_switch` int(11) NOT NULL,
  `puerto_patch_panel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL,
  `nombre_marca` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `marca`
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
-- Estructura de tabla para la tabla `material`
--

CREATE TABLE `material` (
  `id_material` int(11) NOT NULL,
  `ubicacion` int(11) DEFAULT NULL,
  `nombre_material` varchar(45) NOT NULL,
  `stock` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_materiales`
--

CREATE TABLE `movimiento_materiales` (
  `id_movimiento_material` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `accion` varchar(45) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oficina`
--

CREATE TABLE `oficina` (
  `id_oficina` int(11) NOT NULL,
  `id_piso` int(11) NOT NULL,
  `nombre_oficina` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `oficina`
--

INSERT INTO `oficina` (`id_oficina`, `id_piso`, `nombre_oficina`, `estatus`) VALUES
(1, 1, 'Taller 1', 1),
(2, 2, 'Taller 2', 1),
(3, 1, 'Oficina', 1),
(4, 1, 'Depósito', 1),
(5, 3, 'Taller de Electrónica', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patch_panel`
--

CREATE TABLE `patch_panel` (
  `codigo_bien` varchar(20) NOT NULL,
  `serial` varchar(45) NOT NULL,
  `tipo_patch_panel` varchar(45) NOT NULL,
  `cantidad_puertos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `piso`
--

CREATE TABLE `piso` (
  `id_piso` int(11) NOT NULL,
  `tipo_piso` varchar(45) NOT NULL,
  `nro_piso` varchar(10) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `piso`
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
-- Estructura de tabla para la tabla `punto_conexion`
--

CREATE TABLE `punto_conexion` (
  `id_punto_conexion` int(11) NOT NULL,
  `codigo_patch_panel` varchar(20) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `puerto_patch_panel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `nro_solicitud` int(11) NOT NULL,
  `cedula_solicitante` varchar(12) NOT NULL,
  `motivo` varchar(200) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `estado_solicitud` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `resultado_solicitud` varchar(20) DEFAULT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud`
--

INSERT INTO `solicitud` (`nro_solicitud`, `cedula_solicitante`, `motivo`, `id_equipo`, `fecha_solicitud`, `estado_solicitud`, `resultado_solicitud`, `estatus`) VALUES
(2, 'V-1234567', 'Reparar mi equipo', NULL, '2025-04-08 00:00:00', 'Pendiente', NULL, 1),
(3, 'V-1234567', 'Quiero arreglar el PC', NULL, '2025-04-09 00:00:00', 'En Proceso', NULL, 1),
(4, 'V-1234567', 'Quiero arreglar el PC', NULL, '2025-04-09 00:00:00', 'En Proceso', NULL, 1),
(5, 'V-30266398', 'Se daño el pc', NULL, '2025-04-23 00:00:00', 'En Proceso', NULL, 1),
(34, 'V-31843937', 'hjgjh', NULL, '2025-04-27 00:00:00', 'En Proceso', NULL, 1),
(35, 'V-31843937', 'lkjkjk', NULL, '2025-04-27 00:00:00', 'En Proceso', NULL, 1),
(37, 'V-30266398', 'No me agarra internet el pc', NULL, '2025-05-21 15:13:47', 'En Proceso', NULL, 1),
(38, 'V-30266398', 'No me agarra internet el pc', NULL, '2025-05-21 15:17:29', 'En Proceso', NULL, 1),
(39, 'V-30266398', 'Arreglen el router otra vez', NULL, '2025-05-21 15:35:09', 'En Proceso', NULL, 1),
(40, 'V-30266398', 'Cambiar de red', NULL, '2025-05-21 15:36:03', 'En Proceso', NULL, 1),
(41, 'V-30266398', 'Cambiar la impresora', NULL, '2025-05-21 15:37:04', 'En Proceso', NULL, 1),
(42, 'V-30266398', 'PC no prende', NULL, '2025-05-21 15:47:15', 'En Proceso', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `switch`
--

CREATE TABLE `switch` (
  `codigo_bien` varchar(20) NOT NULL,
  `serial` varchar(45) NOT NULL,
  `cantidad_puertos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_bien`
--

CREATE TABLE `tipo_bien` (
  `id_tipo_bien` int(11) NOT NULL,
  `nombre_tipo_bien` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_bien`
--

INSERT INTO `tipo_bien` (`id_tipo_bien`, `nombre_tipo_bien`, `estatus`) VALUES
(1, 'Electrónico', 1),
(2, 'Mueble', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_servicio`
--

CREATE TABLE `tipo_servicio` (
  `id_tipo_servicio` int(11) NOT NULL,
  `nombre_tipo_servicio` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_servicio`
--

INSERT INTO `tipo_servicio` (`id_tipo_servicio`, `nombre_tipo_servicio`, `estatus`) VALUES
(1, 'Soporte Técnico', 0),
(2, 'Redes', 0),
(3, 'Telefonía', 0),
(4, 'Electrónica', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad`
--

CREATE TABLE `unidad` (
  `id_unidad` int(11) NOT NULL,
  `id_dependencia` int(11) NOT NULL,
  `nombre_unidad` varchar(45) NOT NULL,
  `estatus` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidad`
--

INSERT INTO `unidad` (`id_unidad`, `id_dependencia`, `nombre_unidad`, `estatus`) VALUES
(1, 1, 'Bienes', 1),
(2, 1, 'Seguridad', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bien`
--
ALTER TABLE `bien`
  ADD PRIMARY KEY (`codigo_bien`),
  ADD KEY `id_tipo_bien` (`id_tipo_bien`),
  ADD KEY `cedula_empleado` (`cedula_empleado`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_oficina` (`id_oficina`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ente` (`id_ente`);

--
-- Indices de la tabla `detalle_hoja`
--
ALTER TABLE `detalle_hoja`
  ADD PRIMARY KEY (`codigo_hoja_servicio`),
  ADD KEY `id_movimiento_material` (`id_movimiento_material`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`cedula_empleado`),
  ADD KEY `empleado_ibfk_1` (`id_unidad`),
  ADD KEY `tipo` (`id_cargo`),
  ADD KEY `empleado_ibfk_4` (`id_servicio`);

--
-- Indices de la tabla `ente`
--
ALTER TABLE `ente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id_equipo`),
  ADD UNIQUE KEY `serial` (`serial`),
  ADD KEY `equipo_ibfk_2` (`id_unidad`),
  ADD KEY `nro_bien` (`codigo_bien`);

--
-- Indices de la tabla `hoja_servicio`
--
ALTER TABLE `hoja_servicio`
  ADD PRIMARY KEY (`codigo_hoja_servicio`),
  ADD KEY `hoja_servicio_ibfk_1` (`nro_solicitud`),
  ADD KEY `hoja_servicio_ibfk_2` (`id_tipo_servicio`),
  ADD KEY `redireccion` (`redireccion`),
  ADD KEY `id_tipo_servicio` (`id_tipo_servicio`),
  ADD KEY `cedula_tecnico` (`cedula_tecnico`);

--
-- Indices de la tabla `interconexion`
--
ALTER TABLE `interconexion`
  ADD PRIMARY KEY (`id_interconexion`),
  ADD KEY `codigo_switch` (`codigo_switch`),
  ADD KEY `codigo_patch_panel_2` (`codigo_patch_panel`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indices de la tabla `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id_material`),
  ADD KEY `material_ibfk_1` (`ubicacion`);

--
-- Indices de la tabla `movimiento_materiales`
--
ALTER TABLE `movimiento_materiales`
  ADD PRIMARY KEY (`id_movimiento_material`),
  ADD KEY `id_material` (`id_material`);

--
-- Indices de la tabla `oficina`
--
ALTER TABLE `oficina`
  ADD PRIMARY KEY (`id_oficina`),
  ADD KEY `id_piso` (`id_piso`);

--
-- Indices de la tabla `patch_panel`
--
ALTER TABLE `patch_panel`
  ADD PRIMARY KEY (`codigo_bien`),
  ADD UNIQUE KEY `serial` (`serial`),
  ADD KEY `codigo_bien` (`codigo_bien`);

--
-- Indices de la tabla `piso`
--
ALTER TABLE `piso`
  ADD PRIMARY KEY (`id_piso`);

--
-- Indices de la tabla `punto_conexion`
--
ALTER TABLE `punto_conexion`
  ADD PRIMARY KEY (`id_punto_conexion`),
  ADD KEY `codigo_patch` (`codigo_patch_panel`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`nro_solicitud`),
  ADD KEY `solicitud_ibfk_1` (`cedula_solicitante`),
  ADD KEY `solicitud_ibfk_2` (`id_equipo`);

--
-- Indices de la tabla `switch`
--
ALTER TABLE `switch`
  ADD PRIMARY KEY (`codigo_bien`),
  ADD UNIQUE KEY `serial` (`serial`);

--
-- Indices de la tabla `tipo_bien`
--
ALTER TABLE `tipo_bien`
  ADD PRIMARY KEY (`id_tipo_bien`);

--
-- Indices de la tabla `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  ADD PRIMARY KEY (`id_tipo_servicio`);

--
-- Indices de la tabla `unidad`
--
ALTER TABLE `unidad`
  ADD PRIMARY KEY (`id_unidad`),
  ADD KEY `id_dependencia` (`id_dependencia`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ente`
--
ALTER TABLE `ente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `hoja_servicio`
--
ALTER TABLE `hoja_servicio`
  MODIFY `codigo_hoja_servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `interconexion`
--
ALTER TABLE `interconexion`
  MODIFY `id_interconexion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `material`
--
ALTER TABLE `material`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimiento_materiales`
--
ALTER TABLE `movimiento_materiales`
  MODIFY `id_movimiento_material` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `oficina`
--
ALTER TABLE `oficina`
  MODIFY `id_oficina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `piso`
--
ALTER TABLE `piso`
  MODIFY `id_piso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `punto_conexion`
--
ALTER TABLE `punto_conexion`
  MODIFY `id_punto_conexion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `nro_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `tipo_bien`
--
ALTER TABLE `tipo_bien`
  MODIFY `id_tipo_bien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  MODIFY `id_tipo_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `unidad`
--
ALTER TABLE `unidad`
  MODIFY `id_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bien`
--
ALTER TABLE `bien`
  ADD CONSTRAINT `bien_ibfk_1` FOREIGN KEY (`cedula_empleado`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `bien_ibfk_4` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `bien_ibfk_5` FOREIGN KEY (`id_tipo_bien`) REFERENCES `tipo_bien` (`id_tipo_bien`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bien_ibfk_6` FOREIGN KEY (`id_oficina`) REFERENCES `oficina` (`id_oficina`) ON DELETE CASCADE;

--
-- Filtros para la tabla `dependencia`
--
ALTER TABLE `dependencia`
  ADD CONSTRAINT `dependencia_ibfk_1` FOREIGN KEY (`id_ente`) REFERENCES `ente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_hoja`
--
ALTER TABLE `detalle_hoja`
  ADD CONSTRAINT `detalle_hoja_ibfk_1` FOREIGN KEY (`codigo_hoja_servicio`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_hoja_ibfk_2` FOREIGN KEY (`id_movimiento_material`) REFERENCES `movimiento_materiales` (`id_movimiento_material`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`id_unidad`) REFERENCES `unidad` (`id_unidad`);

--
-- Filtros para la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD CONSTRAINT `equipo_ibfk_3` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `equipo_ibfk_4` FOREIGN KEY (`id_unidad`) REFERENCES `unidad` (`id_unidad`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `hoja_servicio`
--
ALTER TABLE `hoja_servicio`
  ADD CONSTRAINT `hoja_servicio_ibfk_1` FOREIGN KEY (`nro_solicitud`) REFERENCES `solicitud` (`nro_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hoja_servicio_ibfk_2` FOREIGN KEY (`cedula_tecnico`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hoja_servicio_ibfk_3` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hoja_servicio_ibfk_4` FOREIGN KEY (`redireccion`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `interconexion`
--
ALTER TABLE `interconexion`
  ADD CONSTRAINT `interconexion_ibfk_1` FOREIGN KEY (`codigo_switch`) REFERENCES `switch` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `interconexion_ibfk_2` FOREIGN KEY (`codigo_patch_panel`) REFERENCES `patch_panel` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `material_ibfk_1` FOREIGN KEY (`ubicacion`) REFERENCES `oficina` (`id_oficina`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimiento_materiales`
--
ALTER TABLE `movimiento_materiales`
  ADD CONSTRAINT `movimiento_materiales_ibfk_1` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `oficina`
--
ALTER TABLE `oficina`
  ADD CONSTRAINT `oficina_ibfk_1` FOREIGN KEY (`id_piso`) REFERENCES `piso` (`id_piso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `patch_panel`
--
ALTER TABLE `patch_panel`
  ADD CONSTRAINT `patch_panel_ibfk_1` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `punto_conexion`
--
ALTER TABLE `punto_conexion`
  ADD CONSTRAINT `punto_conexion_ibfk_1` FOREIGN KEY (`codigo_patch_panel`) REFERENCES `patch_panel` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `punto_conexion_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `solicitud_ibfk_1` FOREIGN KEY (`cedula_solicitante`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `switch`
--
ALTER TABLE `switch`
  ADD CONSTRAINT `switch_ibfk_1` FOREIGN KEY (`codigo_bien`) REFERENCES `bien` (`codigo_bien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `unidad`
--
ALTER TABLE `unidad`
  ADD CONSTRAINT `unidad_ibfk_1` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
