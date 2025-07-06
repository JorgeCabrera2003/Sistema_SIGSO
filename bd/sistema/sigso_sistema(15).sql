-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-07-2025 a las 17:28:05
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
CREATE DATABASE IF NOT EXISTS `sigso_sistema` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sigso_sistema`;

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_tecnico_porArea` (IN `area_param` INT)   BEGIN
    SELECT cedula_empleado, nombre_empleado, id_servicio 
    FROM empleado
    WHERE id_servicio = area_param
    ORDER BY nombre_empleado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_patch_panel` (IN `p_nro_piso` INT)   BEGIN
    SELECT 
        total.total_puertos AS 'Cantidad Total',
        ocupado.puertos_ocupados AS 'Cantidad Ocupado',
        (total.total_puertos - ocupado.puertos_ocupados) AS 'Cantidad Disponible',
        (SELECT COUNT(*) 
         FROM patch_panel p
         JOIN bien b ON b.codigo_bien = p.codigo_bien 
         JOIN piso pi ON p.id_piso = pi.id_piso
         WHERE b.estatus = 1 AND pi.nro_piso = p_nro_piso) AS 'Cantidad Patch Panel'
    FROM
        (SELECT SUM(p.cantidad_puertos) AS total_puertos
         FROM patch_panel p
         JOIN bien b ON b.codigo_bien = p.codigo_bien
         JOIN piso pi ON p.id_piso = pi.id_piso
         WHERE b.estatus = 1 AND pi.nro_piso = p_nro_piso) AS total,
        
        (SELECT COUNT(*) AS puertos_ocupados
         FROM patch_panel p
         JOIN bien b ON b.codigo_bien = p.codigo_bien
         JOIN piso pi ON p.id_piso = pi.id_piso
         JOIN punto_conexion pc ON pc.codigo_patch_panel = p.codigo_bien
         WHERE b.estatus = 1 AND pi.nro_piso = p_nro_piso) AS ocupado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_patch_panel_por_nro` (IN `p_nro_piso` VARCHAR(10))   BEGIN
    SELECT 
        total.total_puertos AS 'Cantidad Total',
        ocupado.puertos_ocupados AS 'Cantidad Ocupado',
        (total.total_puertos - ocupado.puertos_ocupados) AS 'Cantidad Disponible',
        (SELECT COUNT(*) 
         FROM patch_panel p
         JOIN bien b ON b.codigo_bien = p.codigo_bien 
         JOIN piso pi ON p.id_piso = pi.id_piso
         WHERE b.estatus = 1 AND pi.nro_piso = p_nro_piso) AS 'Cantidad Patch Panel'
    FROM
        (SELECT SUM(p.cantidad_puertos) AS total_puertos
         FROM patch_panel p
         JOIN bien b ON b.codigo_bien = p.codigo_bien
         JOIN piso pi ON p.id_piso = pi.id_piso
         WHERE b.estatus = 1 AND pi.nro_piso = p_nro_piso) AS total,
        
        (SELECT COUNT(*) AS puertos_ocupados
         FROM patch_panel p
         JOIN bien b ON b.codigo_bien = p.codigo_bien
         JOIN piso pi ON p.id_piso = pi.id_piso
         JOIN punto_conexion pc ON pc.codigo_patch_panel = p.codigo_bien
         WHERE b.estatus = 1 AND pi.nro_piso = p_nro_piso) AS ocupado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_switches` (IN `p_id_piso` INT)   BEGIN
    SELECT 
        total.total_puertos AS 'Cantidad Total',
        ocupado.puertos_ocupados AS 'Cantidad Ocupado',
        (total.total_puertos - ocupado.puertos_ocupados) AS 'Cantidad Disponible',
        (SELECT COUNT(*) 
         FROM switch s
         JOIN bien b ON b.codigo_bien = s.codigo_bien 
         WHERE b.estatus = 1 AND s.id_piso = p_id_piso) AS 'Cantidad Switch'
    FROM
        (SELECT SUM(s.cantidad_puertos) AS total_puertos
         FROM switch s
         JOIN bien b ON b.codigo_bien = s.codigo_bien
         WHERE b.estatus = 1 AND s.id_piso = p_id_piso) AS total,
        
        (SELECT COUNT(*) AS puertos_ocupados
         FROM switch s
         JOIN bien b ON b.codigo_bien = s.codigo_bien
         JOIN interconexion i ON i.codigo_switch = s.codigo_bien
         WHERE b.estatus = 1 AND s.id_piso = p_id_piso) AS ocupado;
END$$

DELIMITER ;

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
('123124', 1, 1, '123213', 'Nuevo', 'V-31843937', 2, 1),
('213', 1, 2, 'asd', 'Usado', 'V-1234567', 3, 1),
('2132', 1, 1, 'PC GAMER', 'Nuevo', 'V-21140325', 2, 1),
('21332', 1, 1, 'Maquiba', 'null', 'V-21140325', 2, 0),
('213s', 1, 1, 'Equipo', 'Nuevo', 'V-1234567', 3, 0),
('2324', 1, 2, '432', 'Nuevo', 'V-1234567', 2, 1),
('2343', 1, 1, 'Maquiba', 'Usado', 'V-21140325', 2, 0),
('234332', 2, 3, 'Equipo', 'null', 'V-21140325', 3, 0),
('23434', 1, 1, 'Equipo', 'Nuevo', 'V-21140325', 4, 0),
('234343', 1, 1, 'Equipo', 'Nuevo', NULL, 2, 1),
('23435', 1, 1, 'Super', 'Nuevo', 'V-21140325', 4, 0),
('32334', 1, 1, 'Equipo23', 'null', 'V-1234567', 3, 0),
('324234', 1, 1, '23423', 'Nuevo', 'V-1234567', 2, 0),
('gfdgf', 1, 1, 'hfhgfg', 'Dañado', 'V-21140325', 3, 1),
('JK2450', 1, 3, 'Ejemplo', 'Usado', 'V-30587785', 2, 1),
('sad', 1, 2, 'asd', 'Nuevo', 'V-23432452', 2, 1);

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
(2, 'Director de Telefonía', 1),
(3, 'Secretaria', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencia`
--

CREATE TABLE `dependencia` (
  `id` int(11) NOT NULL,
  `id_ente` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
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
  `id_detalle_` int(11) NOT NULL,
  `codigo_hoja_servicio` int(11) NOT NULL,
  `componente` varchar(100) DEFAULT NULL,
  `detalle` varchar(200) DEFAULT NULL,
  `id_movimiento_material` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_hoja`
--

INSERT INTO `detalle_hoja` (`id_detalle_`, `codigo_hoja_servicio`, `componente`, `detalle`, `id_movimiento_material`) VALUES
(2, 10, 'aaaa', 'aaaaa', NULL),
(3, 10, 'eeeee', 'eeeeee', NULL),
(4, 16, '12', '23', NULL),
(5, 17, 'Ram', 'Adapto 8GB', 1),
(7, 21, 'asdas', 'asdsad', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_solicitud_compra`
--

CREATE TABLE `detalle_solicitud_compra` (
  `id_detalle` int(11) NOT NULL,
  `id_solicitud_compra` int(11) NOT NULL,
  `id_material` int(11) DEFAULT NULL,
  `descripcion` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `unidad_medida` varchar(20) DEFAULT NULL,
  `justificacion` varchar(200) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
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
  `correo_empleado` varchar(100) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`cedula_empleado`, `nombre_empleado`, `apellido_empleado`, `id_cargo`, `id_servicio`, `id_unidad`, `telefono_empleado`, `correo_empleado`, `estatus`) VALUES
('V-1234567', 'Maria', 'Peres', NULL, NULL, 1, '0426-5575858', 'prueba@gmail.com', 1),
('V-21140325', 'Félix', 'Mujica', NULL, NULL, 1, '0400-0000000', 'ejemplo@gmail.com', 1),
('V-2132143', 'Ligia', 'Duran', 3, NULL, 2, '4334-2132133', '233234@gmail.com', 1),
('V-23432452', 'Pepito', 'Markez', 1, 4, 1, '4334-2343452', '234sd@gmail.com', 1),
('V-29895827', 'Ferba', 'Markezs', 1, 3, 1, '4334-2334546', 'fenixl@gmail.com', 1),
('V-30266398', 'Leizer', 'Torrealba', NULL, NULL, 1, '0416-0506544', 'leizeraponte2020@gmail.com', 1),
('V-30454597', 'Franklin', 'Fonseca', 1, 1, 2, '0424-5041921', 'franklinjavierfonsecavasquez@gmail.com', 1),
('V-30587785', 'Mariangel', 'Bokor', 1, 2, 1, '0424-5319088', 'bokorarcangel447@gmail.com', 1),
('V-31843937', 'Jorge', 'Cabrera', 1, 1, 1, '0424-5567016', 'cabrerajorge2003@gmail.com', 1),
('V-32567189', 'Angelina', 'Joly', 3, NULL, 1, '1232-3243245', '23234@gmail.com', 1),
('V-4865342', 'Pepitods', 'Markez', 1, 1, 1, '4334-2324356', '23sda4@gmail.com', 1);

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
  `tipo_ente` varchar(20) NOT NULL DEFAULT 'interno',
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ente`
--

INSERT INTO `ente` (`id`, `nombre`, `direccion`, `telefono`, `nombre_responsable`, `tipo_ente`, `estatus`) VALUES
(1, 'Gobernación', '', '', '', 'interno', 1),
(3, 'Teatro Juaréz', '', '', '', 'externo', 1),
(4, 'Parque Baradida', 'Carrera 18 con calle 55 y 54', '0251-0070881', 'Ricardo Guzmán', 'interno', 1);

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
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id_equipo`, `tipo_equipo`, `serial`, `codigo_bien`, `id_unidad`, `estatus`) VALUES
(2, '12123', '3123', 'JK2450', 1, 1),
(4, '12123', '31232', '213', 1, 1),
(5, 'Computador', '32334', '213s', 1, 1),
(6, 'ew4w435', '54564657', 'gfdgf', NULL, 1),
(7, 'Computadorar', '545646572', '23435', NULL, 1),
(8, 'PC GAMERE2', '23234', '23434', NULL, 1),
(9, 'PC GAMERE23', '123123', '32334', NULL, 1),
(10, '234234', '232423', '324234', NULL, 1),
(11, 'Computador23', '32133124', '123124', 1, 1),
(12, 'Computador', '532646123', '234343', 1, 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `filtrado_empleado`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `filtrado_empleado` (
`Total usuario` bigint(21)
,`Total oficina` bigint(21)
,`Total empleados OFITIC` bigint(21)
,`Total empleados general` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `filtrado_hoja`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `filtrado_hoja` (
`Área con más hojas` varchar(45)
,`Cantidad de hojas` bigint(21)
,`Hojas eliminadas` bigint(21)
,`Hojas activas` bigint(21)
,`Hojas finalizadas` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `filtrado_tecnico`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `filtrado_tecnico` (
`Total tecnicos` bigint(21)
,`Total soporte` bigint(21)
,`Total redes` bigint(21)
,`Total telefono` bigint(21)
,`Total electronica` bigint(21)
,`Tecnico eficiente` varchar(72)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoja_servicio`
--

CREATE TABLE `hoja_servicio` (
  `codigo_hoja_servicio` int(11) NOT NULL,
  `nro_solicitud` int(11) NOT NULL,
  `id_tipo_servicio` int(11) NOT NULL,
  `redireccion` int(11) DEFAULT NULL,
  `cedula_tecnico` varchar(12) DEFAULT NULL,
  `fecha_resultado` datetime DEFAULT NULL,
  `resultado_hoja_servicio` varchar(45) DEFAULT NULL,
  `observacion` varchar(200) DEFAULT NULL,
  `estatus` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hoja_servicio`
--

INSERT INTO `hoja_servicio` (`codigo_hoja_servicio`, `nro_solicitud`, `id_tipo_servicio`, `redireccion`, `cedula_tecnico`, `fecha_resultado`, `resultado_hoja_servicio`, `observacion`, `estatus`) VALUES
(10, 128, 1, NULL, 'V-4865342', '0000-00-00 00:00:00', '', '', 'A'),
(11, 129, 1, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL, 'A'),
(12, 131, 1, NULL, 'V-31843937', '2025-07-02 21:46:06', 'Buen_funcionamiento', 'aaaaa', 'I'),
(13, 130, 1, NULL, NULL, '2025-06-27 10:08:08', NULL, NULL, 'E'),
(14, 127, 1, NULL, NULL, '2025-06-27 10:08:19', NULL, NULL, 'E'),
(15, 123, 2, NULL, NULL, NULL, '', 'El problema es que tenia que conectarle bien el cable', 'E'),
(16, 133, 2, NULL, 'V-31843937', '2025-07-02 22:36:00', 'Buen_funcionamiento', 'No lo conecto bien', 'I'),
(17, 134, 1, NULL, 'V-31843937', '2025-07-02 23:53:04', 'Buen_funcionamiento', 'Es que es un pendejo', 'I'),
(18, 135, 1, NULL, 'V-31843937', '2025-07-03 00:25:36', 'Buen_funcionamiento', 'aaaaaaaa', 'I'),
(19, 132, 2, NULL, 'V-23432452', '2025-07-03 00:31:14', 'Operativo', 'aaaaaaaaaaaaa', 'I'),
(20, 136, 1, NULL, 'V-31843937', '2025-07-03 08:23:03', 'Operativo', 'sadsad', 'I'),
(21, 108, 1, NULL, 'V-31843937', '2025-07-03 08:24:56', 'Buen_funcionamiento', 'dasdsa', 'I'),
(22, 137, 1, NULL, 'V-31843937', '2025-07-04 08:58:15', 'Buen_funcionamiento', 'hhhhhhh', 'I'),
(23, 138, 4, NULL, 'V-31843937', '2025-07-04 15:16:27', 'Buen_funcionamiento', '', 'I'),
(24, 139, 1, NULL, 'V-31843937', NULL, NULL, NULL, 'A'),
(25, 140, 1, NULL, NULL, NULL, NULL, NULL, 'A'),
(26, 142, 1, NULL, 'V-31843937', '2025-07-05 23:20:20', 'Buen_funcionamiento', 'dfdsfsdf', 'I');

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

--
-- Volcado de datos para la tabla `interconexion`
--

INSERT INTO `interconexion` (`id_interconexion`, `codigo_switch`, `codigo_patch_panel`, `puerto_switch`, `puerto_patch_panel`) VALUES
(1, 'sad', '213', 1, 12),
(2, 'sad', '213', 2, 3);

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

--
-- Volcado de datos para la tabla `material`
--

INSERT INTO `material` (`id_material`, `ubicacion`, `nombre_material`, `stock`, `estatus`) VALUES
(1, 1, 'Pepito', 213, 0),
(2, 3, 'Pepito2', 213, 0),
(3, 4, 'gumersildad', 2142, 0),
(4, 1, 'gumersildad', 2220, 1),
(5, 3, 'Pepito2', 213, 1),
(6, 2, 'Pepito', 213, 1),
(7, 2, 'gumersildad', 2132, 1),
(8, 5, 'Pepito2', 213, 0),
(9, 4, 'Pepito2', 213, 1),
(10, 2, 'Pepito2', 211, 1);

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

--
-- Volcado de datos para la tabla `movimiento_materiales`
--

INSERT INTO `movimiento_materiales` (`id_movimiento_material`, `id_material`, `accion`, `cantidad`, `descripcion`) VALUES
(1, 10, 'salida', 2, 'Uso en servicio #17');

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
(5, 3, 'Taller de Electrónica', 1),
(6, 9, 'Pepito2', 1),
(7, 11, 'Hotel', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patch_panel`
--

CREATE TABLE `patch_panel` (
  `codigo_bien` varchar(20) NOT NULL,
  `serial` varchar(45) NOT NULL,
  `tipo_patch_panel` varchar(45) NOT NULL,
  `cantidad_puertos` int(11) NOT NULL,
  `id_piso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `patch_panel`
--

INSERT INTO `patch_panel` (`codigo_bien`, `serial`, `tipo_patch_panel`, `cantidad_puertos`, `id_piso`) VALUES
('213', '2343', 'Fuerte', 28, 5),
('2132', '12312423', 'Red', 48, 5),
('2324', '123124', 'Red', 32, 6),
('JK2450', '1223234', 'Fuerte', 24, 5);

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
(5, 'Piso', '5', 1),
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

--
-- Volcado de datos para la tabla `punto_conexion`
--

INSERT INTO `punto_conexion` (`id_punto_conexion`, `codigo_patch_panel`, `id_equipo`, `puerto_patch_panel`) VALUES
(1, '213', 2, 12),
(2, '2324', 5, 30),
(3, '213', 4, 3),
(4, '213', 11, 2),
(5, '213', 12, 10);

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
(108, 'V-30266398', 'Prueba registrosos', 2, '2025-06-24 19:25:58', 'Finalizado', 'Completado', 1),
(109, 'V-31843937', 'asdasd', 2, '2025-06-24 19:28:20', 'Pendiente', NULL, 0),
(110, 'V-1234567', '23432', 2, '2025-06-24 19:49:08', 'Pendiente', NULL, 0),
(111, 'V-1234567', 'Prueba de registrar', 2, '2025-06-24 22:14:36', 'Pendiente', NULL, 0),
(112, 'V-21140325', 'Chiclesa', 2, '2025-06-24 22:53:25', 'Pendiente', NULL, 0),
(113, 'V-30454597', '1923', 2, '2025-06-25 08:59:58', 'Pendiente', NULL, 0),
(114, 'V-30454597', 'Soy un mami', 2, '2025-06-25 09:04:03', 'Pendiente', NULL, 0),
(115, 'V-30266398', 'aaaaaa', 2, '2025-06-25 09:05:15', 'Pendiente', NULL, 0),
(116, 'V-1234567', 'qwerts', 2, '2025-06-25 09:06:31', 'Pendiente', NULL, 0),
(117, 'V-21140325', 'sdasd', 2, '2025-06-25 09:06:53', 'Pendiente', NULL, 0),
(118, 'V-30454597', 'Preueba hoja', 2, '2025-06-25 09:39:52', 'Pendiente', NULL, 0),
(119, 'V-1234567', 'Prueba', 2, '2025-06-25 09:42:21', 'Pendiente', NULL, 0),
(120, 'V-1234567', 'Hojaaa', 2, '2025-06-25 09:44:52', 'Pendiente', NULL, 0),
(121, 'V-1234567', 'asd', 2, '2025-06-25 09:48:27', 'Pendiente', NULL, 0),
(122, 'V-21140325', 'prueba mill', 2, '2025-06-25 09:50:15', 'Pendiente', NULL, 0),
(123, 'V-1234567', 'muvi', 2, '2025-06-25 09:51:15', 'En proceso', NULL, 0),
(124, 'V-21140325', 'asdf', 2, '2025-06-25 09:54:09', 'Pendiente', NULL, 0),
(125, 'V-30587785', 'asd3', 2, '2025-06-25 09:56:00', 'Pendiente', NULL, 0),
(126, 'V-1234567', 'hola', 2, '2025-06-25 10:16:36', 'Pendiente', NULL, 0),
(127, 'V-1234567', 'Necesita mas ram a su equipo', 2, '2025-06-25 10:18:43', 'En proceso', NULL, 1),
(128, 'V-1234567', 'hoja', 2, '2025-06-25 10:24:37', 'En proceso', NULL, 0),
(129, 'V-31843937', 'Mentira se llama jorge c', NULL, '2025-06-25 10:29:39', 'En proceso', NULL, 0),
(130, 'V-30266398', 'Necesita un monitos LCTRCs', 2, '2025-06-27 10:05:26', 'En proceso', NULL, 0),
(131, 'V-30266398', 'Necesita un monitor LCRCT', NULL, '2025-06-27 10:07:11', 'Finalizado', 'Completado', 0),
(132, 'V-31843937', 'no internet', 0, '2025-07-02 13:17:50', 'Finalizado', 'Completado', 1),
(133, 'V-1234567', 'asdas', NULL, '2025-07-02 22:33:51', 'Finalizado', 'Completado', 0),
(134, 'V-30454597', 'No tiene ram', 2, '2025-07-02 23:52:05', 'Finalizado', 'Completado', 0),
(135, 'V-1234567', 'ddddd', NULL, '2025-07-03 00:24:26', 'Finalizado', 'Completado', 1),
(136, 'V-30266398', '1111111', 5, '2025-07-03 08:14:31', 'Finalizado', 'Completado', 1),
(137, 'V-31843937', 'internet', 11, '2025-07-04 08:57:15', 'Finalizado', 'Completado', 1),
(138, 'V-21140325', 'ddddddd', 4, '2025-07-04 15:15:47', 'Finalizado', 'Completado', 1),
(139, 'V-1234567', 'dsdasadasd', 2, '2025-07-05 21:48:56', 'En proceso', NULL, 1),
(140, 'V-31843937', 'asasasda', 2, '2025-07-05 21:49:47', 'En proceso', NULL, 1),
(142, 'V-32567189', 'Se me daño la impresora ', 5, '2025-07-05 22:15:28', 'Finalizado', 'Completado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_compra`
--

CREATE TABLE `solicitud_compra` (
  `id_solicitud_compra` int(11) NOT NULL,
  `codigo_solicitud` varchar(20) NOT NULL,
  `cedula_solicitante` varchar(12) NOT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `motivo` varchar(200) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `observaciones` varchar(200) DEFAULT NULL,
  `id_tipo_servicio` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `switch`
--

CREATE TABLE `switch` (
  `codigo_bien` varchar(20) NOT NULL,
  `serial` varchar(45) NOT NULL,
  `cantidad_puertos` int(11) NOT NULL,
  `id_piso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `switch`
--

INSERT INTO `switch` (`codigo_bien`, `serial`, `cantidad_puertos`, `id_piso`) VALUES
('sad', '12234', 16, 5);

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
(2, 'Mueble', 1),
(3, 'Samtel', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_servicio`
--

CREATE TABLE `tipo_servicio` (
  `id_tipo_servicio` int(11) NOT NULL,
  `nombre_tipo_servicio` varchar(45) NOT NULL,
  `cedula_encargado` varchar(12) DEFAULT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_servicio`
--

INSERT INTO `tipo_servicio` (`id_tipo_servicio`, `nombre_tipo_servicio`, `cedula_encargado`, `estatus`) VALUES
(1, 'Soporte Técnico', NULL, 1),
(2, 'Redes', NULL, 1),
(3, 'Telefonía', NULL, 1),
(4, 'Electrónica', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad`
--

CREATE TABLE `unidad` (
  `id_unidad` int(11) NOT NULL,
  `id_dependencia` int(11) NOT NULL,
  `nombre_unidad` varchar(45) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidad`
--

INSERT INTO `unidad` (`id_unidad`, `id_dependencia`, `nombre_unidad`, `estatus`) VALUES
(1, 1, 'Bienes', 1),
(2, 2, 'Seguridad', 1);

-- --------------------------------------------------------

--
-- Estructura para la vista `filtrado_empleado`
--
DROP TABLE IF EXISTS `filtrado_empleado`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `filtrado_empleado`  AS SELECT (select count(0) from `sigso_usuario`.`usuario` `u` where `u`.`estatus` = 1) AS `Total usuario`, (select count(0) from `oficina` `o` where `o`.`estatus` = 1) AS `Total oficina`, (select count(0) from ((`empleado` `e` join `unidad` `u` on(`u`.`id_unidad` = `e`.`id_unidad`)) join `dependencia` `d` on(`d`.`id` = `u`.`id_dependencia`)) where `e`.`estatus` = 1 and `d`.`id` = 1) AS `Total empleados OFITIC`, (select count(0) from `empleado` `e` where `e`.`estatus` = 1) AS `Total empleados general` FROM `empleado` AS `e` WHERE `e`.`estatus` = 1 LIMIT 0, 1 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `filtrado_hoja`
--
DROP TABLE IF EXISTS `filtrado_hoja`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `filtrado_hoja`  AS SELECT `ts`.`nombre_tipo_servicio` AS `Área con más hojas`, count(`hs`.`codigo_hoja_servicio`) AS `Cantidad de hojas`, (select count(0) from (`hoja_servicio` `sh` join `solicitud` `s` on(`s`.`nro_solicitud` = `sh`.`nro_solicitud`)) where `sh`.`estatus` = 'E' and `s`.`estatus` = 1) AS `Hojas eliminadas`, (select count(0) from (`hoja_servicio` `sh` join `solicitud` `s` on(`s`.`nro_solicitud` = `sh`.`nro_solicitud`)) where `sh`.`estatus` = 'A' and `s`.`estatus` = 1) AS `Hojas activas`, (select count(0) from (`hoja_servicio` `sh` join `solicitud` `s` on(`s`.`nro_solicitud` = `sh`.`nro_solicitud`)) where `sh`.`estatus` = 'I' and `s`.`estatus` = 1) AS `Hojas finalizadas` FROM ((`hoja_servicio` `hs` join `solicitud` `s` on(`s`.`nro_solicitud` = `hs`.`nro_solicitud`)) join `tipo_servicio` `ts` on(`hs`.`id_tipo_servicio` = `ts`.`id_tipo_servicio`)) WHERE `s`.`estatus` = 1 GROUP BY `hs`.`id_tipo_servicio`, `ts`.`nombre_tipo_servicio` ORDER BY count(`hs`.`codigo_hoja_servicio`) DESC LIMIT 0, 1 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `filtrado_tecnico`
--
DROP TABLE IF EXISTS `filtrado_tecnico`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `filtrado_tecnico`  AS SELECT (select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1) AS `Total tecnicos`, (select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 1) AS `Total soporte`, (select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 2) AS `Total redes`, (select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 3) AS `Total telefono`, (select count(0) from `empleado` `e` where `e`.`estatus` = 1 and `e`.`id_cargo` = 1 and `e`.`id_servicio` = 4) AS `Total electronica`, (select concat('CI: ',`e`.`cedula_empleado`,' - Nombre: ',`e`.`nombre_empleado`) from `empleado` `e` where `e`.`cedula_empleado` = (select `hs`.`cedula_tecnico` from `hoja_servicio` `hs` where `hs`.`estatus` = 'I' group by `hs`.`cedula_tecnico` order by count(0) desc limit 1)) AS `Tecnico eficiente` FROM `empleado` AS `e` WHERE `e`.`estatus` = 1 LIMIT 0, 1 ;

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
  ADD PRIMARY KEY (`id_detalle_`),
  ADD KEY `id_movimiento_material` (`id_movimiento_material`),
  ADD KEY `Union_hoja` (`codigo_hoja_servicio`);

--
-- Indices de la tabla `detalle_solicitud_compra`
--
ALTER TABLE `detalle_solicitud_compra`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_solicitud_compra` (`id_solicitud_compra`),
  ADD KEY `id_material` (`id_material`);

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
  ADD KEY `codigo_bien` (`codigo_bien`),
  ADD KEY `Piso` (`id_piso`);

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
-- Indices de la tabla `solicitud_compra`
--
ALTER TABLE `solicitud_compra`
  ADD PRIMARY KEY (`id_solicitud_compra`),
  ADD UNIQUE KEY `codigo_solicitud` (`codigo_solicitud`),
  ADD KEY `cedula_solicitante` (`cedula_solicitante`),
  ADD KEY `id_tipo_servicio` (`id_tipo_servicio`);

--
-- Indices de la tabla `switch`
--
ALTER TABLE `switch`
  ADD PRIMARY KEY (`codigo_bien`),
  ADD UNIQUE KEY `serial` (`serial`),
  ADD KEY `Piso_id` (`id_piso`);

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
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_hoja`
--
ALTER TABLE `detalle_hoja`
  MODIFY `id_detalle_` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `detalle_solicitud_compra`
--
ALTER TABLE `detalle_solicitud_compra`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ente`
--
ALTER TABLE `ente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `hoja_servicio`
--
ALTER TABLE `hoja_servicio`
  MODIFY `codigo_hoja_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `interconexion`
--
ALTER TABLE `interconexion`
  MODIFY `id_interconexion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `material`
--
ALTER TABLE `material`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `movimiento_materiales`
--
ALTER TABLE `movimiento_materiales`
  MODIFY `id_movimiento_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `oficina`
--
ALTER TABLE `oficina`
  MODIFY `id_oficina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `piso`
--
ALTER TABLE `piso`
  MODIFY `id_piso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `punto_conexion`
--
ALTER TABLE `punto_conexion`
  MODIFY `id_punto_conexion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `nro_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT de la tabla `solicitud_compra`
--
ALTER TABLE `solicitud_compra`
  MODIFY `id_solicitud_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_bien`
--
ALTER TABLE `tipo_bien`
  MODIFY `id_tipo_bien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `Union_hoja` FOREIGN KEY (`codigo_hoja_servicio`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`),
  ADD CONSTRAINT `detalle_hoja_ibfk_1` FOREIGN KEY (`codigo_hoja_servicio`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_hoja_ibfk_2` FOREIGN KEY (`id_movimiento_material`) REFERENCES `movimiento_materiales` (`id_movimiento_material`);

--
-- Filtros para la tabla `detalle_solicitud_compra`
--
ALTER TABLE `detalle_solicitud_compra`
  ADD CONSTRAINT `detalle_solicitud_compra_ibfk_1` FOREIGN KEY (`id_solicitud_compra`) REFERENCES `solicitud_compra` (`id_solicitud_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_solicitud_compra_ibfk_2` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `Piso` FOREIGN KEY (`id_piso`) REFERENCES `piso` (`id_piso`),
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
-- Filtros para la tabla `solicitud_compra`
--
ALTER TABLE `solicitud_compra`
  ADD CONSTRAINT `solicitud_compra_ibfk_1` FOREIGN KEY (`cedula_solicitante`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitud_compra_ibfk_2` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `switch`
--
ALTER TABLE `switch`
  ADD CONSTRAINT `Piso_id` FOREIGN KEY (`id_piso`) REFERENCES `piso` (`id_piso`),
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
