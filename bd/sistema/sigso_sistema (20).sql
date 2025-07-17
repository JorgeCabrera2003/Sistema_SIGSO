-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-07-2025 a las 04:01:41
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
CREATE DATABASE IF NOT EXISTS `sigso_sistema` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sigso_sistema`;

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_tecnicos_por_servicio` (IN `tipo_servicio_param` INT)   BEGIN
    SELECT 
        e.cedula_empleado, 
        CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre,
        COALESCE(hs.cant_hojas, 0) AS hojas_mes
    FROM empleado e
    LEFT JOIN (
        SELECT 
            cedula_tecnico, 
            COUNT(*) AS cant_hojas
        FROM hoja_servicio
        WHERE estatus = 'A'
        GROUP BY cedula_tecnico
    ) hs ON hs.cedula_tecnico = e.cedula_empleado
    WHERE e.id_servicio = tipo_servicio_param
        AND e.id_cargo = 1  -- 1 = Técnico
        AND e.estatus = 1   -- 1 = Activo
    ORDER BY hojas_mes ASC, nombre ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_tecnico_porArea` (IN `area_param` INT)   BEGIN
    SELECT cedula_empleado, nombre_empleado, id_servicio 
    FROM empleado
    WHERE id_servicio = area_param
    ORDER BY nombre_empleado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_crear_hoja_servicio` (IN `p_nro_solicitud` INT, IN `p_id_tipo_servicio` INT, IN `p_cedula_tecnico` VARCHAR(12), OUT `p_codigo_hoja` INT, OUT `p_resultado` VARCHAR(50), OUT `p_mensaje` VARCHAR(200))   BEGIN
    DECLARE v_existe_solicitud INT;
    DECLARE v_hoja_existente INT;
    
    SELECT COUNT(*) INTO v_existe_solicitud 
    FROM solicitud 
    WHERE nro_solicitud = p_nro_solicitud AND estatus = 1;
    
    IF v_existe_solicitud = 0 THEN
        SET p_resultado = 'error';
        SET p_mensaje = 'La solicitud no existe o está inactiva';
        SET p_codigo_hoja = NULL;
    ELSE
        SELECT COUNT(*) INTO v_hoja_existente 
        FROM hoja_servicio 
        WHERE nro_solicitud = p_nro_solicitud AND id_tipo_servicio = p_id_tipo_servicio;
        
        IF v_hoja_existente > 0 THEN
            SET p_resultado = 'error';
            SET p_mensaje = 'Ya existe una hoja para este tipo de servicio en la solicitud';
            SET p_codigo_hoja = NULL;
        ELSE
            INSERT INTO hoja_servicio 
            (nro_solicitud, id_tipo_servicio, estatus, cedula_tecnico) 
            VALUES (p_nro_solicitud, p_id_tipo_servicio, 'A', p_cedula_tecnico);
            
            SET p_codigo_hoja = LAST_INSERT_ID();
            SET p_resultado = 'success';
            SET p_mensaje = 'Hoja de servicio creada exitosamente';
            
            UPDATE solicitud 
            SET estado_solicitud = 'En proceso'
            WHERE nro_solicitud = p_nro_solicitud;
        END IF;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_bienes` (IN `p_id_tipo_bien` INT, IN `p_estado` VARCHAR(45), IN `p_id_oficina` INT, IN `p_fecha_inicio` DATE, IN `p_fecha_fin` DATE)   BEGIN
    SELECT 
        tb.nombre_tipo_bien AS 'Tipo de Bien',
        b.estado AS 'Estado',
        o.nombre_oficina AS 'Oficina',
        COUNT(*) AS 'Cantidad'
    FROM 
        bien b
    JOIN 
        tipo_bien tb ON b.id_tipo_bien = tb.id_tipo_bien
    JOIN 
        oficina o ON b.id_oficina = o.id_oficina
    LEFT JOIN
        equipo e ON e.codigo_bien = b.codigo_bien
    WHERE 
        (p_id_tipo_bien IS NULL OR b.id_tipo_bien = p_id_tipo_bien)
        AND (p_estado IS NULL OR b.estado = p_estado)
        AND (p_id_oficina IS NULL OR b.id_oficina = p_id_oficina)
        AND (p_fecha_inicio IS NULL OR p_fecha_fin IS NULL OR 
            (e.fecha_registro BETWEEN p_fecha_inicio AND p_fecha_fin))
    GROUP BY 
        tb.nombre_tipo_bien, b.estado, o.nombre_oficina
    ORDER BY 
        tb.nombre_tipo_bien, b.estado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_materiales_utilizados` (IN `p_id_material` INT, IN `p_id_oficina` INT, IN `p_fecha_inicio` DATE, IN `p_fecha_fin` DATE)   BEGIN
    SELECT 
        m.nombre_material AS 'Material',
        o.nombre_oficina AS 'Ubicación',
        dh.componente AS 'Componente',
        SUM(mm.cantidad) AS 'Cantidad Utilizada',
        MIN(s.fecha_solicitud) AS 'Primer Uso',
        MAX(s.fecha_solicitud) AS 'Último Uso'
    FROM 
        detalle_hoja dh
    JOIN 
        movimiento_materiales mm ON dh.id_movimiento_material = mm.id_movimiento_material
    JOIN 
        material m ON mm.id_material = m.id_material
    JOIN 
        oficina o ON m.ubicacion = o.id_oficina
    JOIN 
        hoja_servicio hs ON dh.codigo_hoja_servicio = hs.codigo_hoja_servicio
    JOIN 
        solicitud s ON hs.nro_solicitud = s.nro_solicitud
    WHERE 
        (p_id_material IS NULL OR m.id_material = p_id_material)
        AND (p_id_oficina IS NULL OR o.id_oficina = p_id_oficina)
        AND (p_fecha_inicio IS NULL OR p_fecha_fin IS NULL OR 
            (s.fecha_solicitud BETWEEN p_fecha_inicio AND p_fecha_fin))
    GROUP BY 
        m.nombre_material, o.nombre_oficina, dh.componente
    ORDER BY 
        SUM(mm.cantidad) DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_movimientos_materiales` (IN `p_id_material` INT, IN `p_accion` VARCHAR(45), IN `p_id_oficina` INT, IN `p_fecha_inicio` DATE, IN `p_fecha_fin` DATE)   BEGIN
    SELECT 
        m.nombre_material AS 'Material',
        mm.accion AS 'Tipo de Movimiento',
        o.nombre_oficina AS 'Ubicación',
        SUM(mm.cantidad) AS 'Cantidad',
        MIN(mm.fecha_registro) AS 'Primer Movimiento',
        MAX(mm.fecha_registro) AS 'Último Movimiento'
    FROM 
        movimiento_materiales mm
    JOIN 
        material m ON mm.id_material = m.id_material
    JOIN 
        oficina o ON m.ubicacion = o.id_oficina
    WHERE 
        (p_id_material IS NULL OR m.id_material = p_id_material)
        AND (p_accion IS NULL OR mm.accion = p_accion)
        AND (p_id_oficina IS NULL OR o.id_oficina = p_id_oficina)
        AND (p_fecha_inicio IS NULL OR p_fecha_fin IS NULL OR 
            (mm.fecha_registro BETWEEN p_fecha_inicio AND p_fecha_fin))
    GROUP BY 
        m.nombre_material, mm.accion, o.nombre_oficina
    ORDER BY 
        MAX(mm.fecha_registro) DESC;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_rendimiento_tecnicos` (IN `p_cedula_tecnico` VARCHAR(12), IN `p_id_tipo_servicio` INT, IN `p_fecha_inicio` DATE, IN `p_fecha_fin` DATE)   BEGIN
    SELECT 
        CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS 'Técnico',
        ts.nombre_tipo_servicio AS 'Área',
        COUNT(DISTINCT hs.nro_solicitud) AS 'Solicitudes Atendidas',
        AVG(TIMESTAMPDIFF(HOUR, s.fecha_solicitud, hs.fecha_resultado)) AS 'Tiempo Promedio (horas)',
        SUM(CASE WHEN hs.resultado_hoja_servicio = 'Completado' THEN 1 ELSE 0 END) AS 'Completadas',
        SUM(CASE WHEN hs.resultado_hoja_servicio = 'Pendiente' THEN 1 ELSE 0 END) AS 'Pendientes',
        SUM(CASE WHEN hs.resultado_hoja_servicio = 'Fallido' THEN 1 ELSE 0 END) AS 'Fallidas'
    FROM 
        hoja_servicio hs
    JOIN 
        empleado e ON hs.cedula_tecnico = e.cedula_empleado
    JOIN 
        tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
    JOIN 
        solicitud s ON hs.nro_solicitud = s.nro_solicitud
    WHERE 
        hs.estatus = 'I' /* Solo hojas finalizadas */
        AND (p_cedula_tecnico IS NULL OR hs.cedula_tecnico = p_cedula_tecnico)
        AND (p_id_tipo_servicio IS NULL OR ts.id_tipo_servicio = p_id_tipo_servicio)
        AND (p_fecha_inicio IS NULL OR p_fecha_fin IS NULL OR 
            (s.fecha_solicitud BETWEEN p_fecha_inicio AND p_fecha_fin))
    GROUP BY 
        hs.cedula_tecnico, ts.nombre_tipo_servicio
    ORDER BY 
        AVG(TIMESTAMPDIFF(HOUR, s.fecha_solicitud, hs.fecha_resultado));
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_solicitudes_atendidas` (IN `p_cedula_tecnico` VARCHAR(12), IN `p_id_tipo_servicio` INT, IN `p_fecha_inicio` DATE, IN `p_fecha_fin` DATE, IN `p_estatus` VARCHAR(1))   BEGIN
    SELECT 
        CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS 'Técnico',
        ts.nombre_tipo_servicio AS 'Área',
        COUNT(hs.codigo_hoja_servicio) AS 'Total Asignadas',
        SUM(CASE WHEN hs.estatus = 'I' THEN 1 ELSE 0 END) AS 'Finalizadas',
        SUM(CASE WHEN hs.estatus = 'A' THEN 1 ELSE 0 END) AS 'En Proceso',
        SUM(CASE WHEN hs.estatus = 'E' THEN 1 ELSE 0 END) AS 'Eliminadas'
    FROM 
        hoja_servicio hs
    JOIN 
        empleado e ON hs.cedula_tecnico = e.cedula_empleado
    JOIN 
        tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
    JOIN 
        solicitud s ON hs.nro_solicitud = s.nro_solicitud
    WHERE 
        (p_cedula_tecnico IS NULL OR hs.cedula_tecnico = p_cedula_tecnico)
        AND (p_id_tipo_servicio IS NULL OR ts.id_tipo_servicio = p_id_tipo_servicio)
        AND (p_fecha_inicio IS NULL OR p_fecha_fin IS NULL OR 
            (s.fecha_solicitud BETWEEN p_fecha_inicio AND p_fecha_fin))
        AND (p_estatus IS NULL OR hs.estatus = p_estatus)
    GROUP BY 
        hs.cedula_tecnico, ts.nombre_tipo_servicio
    ORDER BY 
        COUNT(hs.codigo_hoja_servicio) DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_solicitudes_equipo` (IN `p_tipo_equipo` VARCHAR(45), IN `p_id_marca` INT, IN `p_fecha_inicio` DATE, IN `p_fecha_fin` DATE)   BEGIN
    SELECT 
        e.tipo_equipo AS 'Tipo de Equipo',
        m.nombre_marca AS 'Marca',
        b.descripcion AS 'Descripción',
        COUNT(s.nro_solicitud) AS 'Total Solicitudes',
        MIN(s.fecha_solicitud) AS 'Primera Solicitud',
        MAX(s.fecha_solicitud) AS 'Última Solicitud'
    FROM 
        solicitud s
    JOIN 
        equipo e ON s.id_equipo = e.id_equipo
    JOIN 
        bien b ON e.codigo_bien = b.codigo_bien
    LEFT JOIN 
        marca m ON b.id_marca = m.id_marca
    WHERE 
        (p_tipo_equipo IS NULL OR e.tipo_equipo LIKE CONCAT('%', p_tipo_equipo, '%'))
        AND (p_id_marca IS NULL OR m.id_marca = p_id_marca)
        AND (p_fecha_inicio IS NULL OR p_fecha_fin IS NULL OR 
            (s.fecha_solicitud BETWEEN p_fecha_inicio AND p_fecha_fin))
    GROUP BY 
        e.tipo_equipo, m.nombre_marca, b.descripcion
    ORDER BY 
        COUNT(s.nro_solicitud) DESC;
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
  `id_categoria` int(11) NOT NULL,
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

INSERT INTO `bien` (`codigo_bien`, `id_categoria`, `id_marca`, `descripcion`, `estado`, `cedula_empleado`, `id_oficina`, `estatus`) VALUES
('0001', 1, 1, 'Impresora rh200', 'Nuevo', 'V-21140325', 1, 1),
('00014', 1, 1, 'Computador 32GB', 'Usado', 'V-31843937', 2, 1),
('0002', 1, 4, 'Computador Oficina', 'Nuevo', 'V-31843937', 2, 1),
('0003', 2, 1, 'Mesa ejecutiva', 'Usado', 'V-21140325', 3, 1),
('0004', 1, 2, 'Patch panel rojo 45k', 'Nuevo', 'V-30587785', 5, 1),
('0005', 1, 2, 'Pacth panel 500tc', 'Nuevo', 'V-30587785', 4, 1),
('0006', 1, 1, 'Switch prt24', 'null', 'V-30587785', 3, 1),
('0007', 1, 2, 'Switch prt27', 'null', 'V-30587785', 3, 1),
('0008', 1, 2, 'Switch rt45', 'null', 'V-30587785', 3, 1),
('0009', 1, 1, 'Laptop', 'Dañado', 'V-30266398', 4, 1),
('0010', 1, 3, 'Equipo', 'Dañado', 'V-1234567', 3, 1),
('0011', 1, 3, 'Equipo', 'Usado', 'V-30266398', 4, 1);

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
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(45) NOT NULL,
  `id_tipo_servicio` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`, `id_tipo_servicio`, `estatus`) VALUES
(1, 'Electrónico', NULL, 1),
(2, 'Mueble', NULL, 1);

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
-- Disparadores `detalle_hoja`
--
DELIMITER $$
CREATE TRIGGER `tr_before_insert_detalle_hoja` BEFORE INSERT ON `detalle_hoja` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

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
('V-30266398', 'Leizer', 'Torrealba', NULL, NULL, 1, '0416-0506544', 'leizeraponte2020@gmail.com', 1),
('V-30454597', 'Franklin', 'Fonseca', 1, 1, 2, '0424-5041921', 'franklinjavierfonsecavasquez@gmail.com', 1),
('V-30587785', 'Mariangel', 'Bokor', 1, 2, 1, '0424-5319088', 'bokorarcangel447@gmail.com', 1),
('V-31843937', 'Jorge', 'Cabrera', 1, 1, 1, '0424-5567016', 'cabrerajorge2003@gmail.com', 1),
('V-32567189', 'Angelina', 'Joly', 3, NULL, 1, '1232-3243245', '23234@gmail.com', 1);

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
(4, 'Parque Baradida', 'Carrera 18 con calle 55 y 54', '0251-0070881', 'Ricardo Guzmán', 'externo', 1);

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
(13, 'Empresarial', '000001', '0001', 1, 1),
(14, 'Computador', '00002', '0002', 2, 1),
(15, 'Computador', '00010', '0010', 1, 1),
(16, 'Computador', '00011', '0011', 1, 1),
(17, 'Telefono', '00012', '0009', 1, 1),
(18, 'PC GAMERE', '00013', '0004', 2, 1),
(19, 'Computador', '31232', '0003', 1, 1),
(20, '12123', '00015', '0005', 1, 1),
(21, 'Computador', '3123234', '0006', 1, 1),
(22, 'Computador', '33123234', '0007', 1, 1),
(23, 'Computadorar', '000014', '00014', 1, 1);

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
(44, 166, 1, NULL, 'V-30454597', NULL, NULL, NULL, 'A'),
(45, 167, 2, NULL, 'V-30587785', NULL, NULL, NULL, 'A'),
(46, 167, 1, NULL, 'V-31843937', NULL, NULL, NULL, 'A'),
(47, 168, 1, NULL, 'V-30454597', NULL, NULL, NULL, 'A'),
(48, 171, 2, NULL, 'V-30587785', NULL, NULL, NULL, 'A'),
(49, 171, 1, NULL, 'V-31843937', NULL, NULL, NULL, 'A'),
(50, 172, 1, NULL, 'V-30454597', NULL, NULL, NULL, 'A'),
(51, 173, 1, NULL, 'V-31843937', NULL, NULL, NULL, 'A'),
(52, 173, 2, NULL, 'V-30587785', NULL, NULL, NULL, 'A'),
(53, 174, 1, NULL, 'V-30454597', NULL, NULL, NULL, 'A'),
(54, 175, 1, NULL, 'V-31843937', NULL, NULL, NULL, 'A'),
(55, 176, 1, NULL, 'V-30454597', NULL, NULL, NULL, 'A'),
(56, 175, 2, NULL, 'V-30587785', NULL, NULL, NULL, 'E'),
(57, 176, 2, NULL, 'V-30587785', NULL, NULL, NULL, 'A'),
(58, 177, 1, NULL, 'V-31843937', NULL, NULL, NULL, 'A'),
(60, 179, 1, NULL, 'V-30454597', NULL, NULL, NULL, 'A');

--
-- Disparadores `hoja_servicio`
--
DELIMITER $$
CREATE TRIGGER `tr_after_insert_hoja_servicio` AFTER INSERT ON `hoja_servicio` FOR EACH ROW BEGIN
    -- Actualizar estado de la solicitud a "En proceso" cuando se crea una hoja
    UPDATE solicitud 
    SET estado_solicitud = 'En proceso'
    WHERE nro_solicitud = NEW.nro_solicitud AND estado_solicitud = 'Pendiente';
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_after_update_hoja_servicio` AFTER UPDATE ON `hoja_servicio` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

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

--
-- Volcado de datos para la tabla `material`
--

INSERT INTO `material` (`id_material`, `ubicacion`, `nombre_material`, `stock`, `estatus`) VALUES
(11, 1, 'Conector RJ45', 100, 1),
(12, 2, 'Cable fibra optica', 300, 1),
(13, 1, 'Pasta termica', 50, 1);

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
  `cantidad_puertos` int(11) NOT NULL,
  `id_piso` int(11) DEFAULT NULL
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
(1, 'Planta Baja', '1', 1),
(2, 'Piso', '2', 0),
(3, 'Piso', '3', 1),
(4, 'Sótano', '4', 1),
(5, 'Piso', '5', 1),
(6, 'Piso', '6', 1),
(7, 'Sótano', '7', 1),
(8, 'Piso', '8', 1),
(9, 'Piso', '9', 1);

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
-- Estructura de tabla para la tabla `servicio_prestado`
--

CREATE TABLE `servicio_prestado` (
  `id` int(11) NOT NULL,
  `id_tipo_servicio` int(11) NOT NULL,
  `nombre` varchar(65) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_realizado`
--

CREATE TABLE `servicio_realizado` (
  `id_servicio_realizado` int(11) NOT NULL,
  `id_servicio_prestado` int(11) NOT NULL,
  `id_hoja_servicio` int(11) NOT NULL
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
(165, 'V-31843937', 'Prueba', 14, '2025-07-08 23:57:50', 'Eliminado', NULL, 0),
(166, 'V-31843937', 'Computadora no enciended', 14, '2025-07-09 00:06:40', 'Eliminado', NULL, 0),
(167, 'V-31843937', 'Necesito super rapida', 23, '2025-07-09 00:08:22', 'Eliminado', NULL, 0),
(168, 'V-31843937', 'Necesito mas ram', 23, '2025-07-09 00:24:52', 'Eliminado', NULL, 0),
(169, 'V-31843937', 'No tengo internet', 14, '2025-07-09 00:49:26', 'Eliminado', NULL, 0),
(170, 'V-31843937', 'Prueba', 14, '2025-07-09 17:43:47', 'Eliminado', NULL, 0),
(171, 'V-31843937', 'prueba', 23, '2025-07-09 20:07:13', 'Eliminado', NULL, 0),
(172, 'V-31843937', 'Prueba', 14, '2025-07-10 19:09:40', 'Eliminado', NULL, 0),
(173, 'V-31843937', 'Prueba 2', 23, '2025-07-10 19:38:10', 'Eliminado', NULL, 0),
(174, 'V-31843937', 'Prueba 2 frank', 23, '2025-07-10 19:42:21', 'Eliminado', NULL, 0),
(175, 'V-31843937', 'Prueba del selectw2 con jor', 14, '2025-07-10 19:44:47', 'Eliminado', NULL, 0),
(176, 'V-31843937', 'Prueba frank', 23, '2025-07-10 19:45:12', 'Eliminado', NULL, 0),
(177, 'V-31843937', 'Preuba jorguin', 14, '2025-07-10 19:47:48', 'Eliminado', NULL, 0),
(178, 'V-31843937', 'Prueba', 14, '2025-07-13 22:04:20', 'Pendiente', NULL, 1),
(179, 'V-31843937', 'Prueba', 23, '2025-07-13 22:04:32', 'En proceso', NULL, 1);

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
('0008', '12234', 52, NULL);

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
(1, 'Soporte Técnico', 'V-31843937', 1),
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
-- Estructura Stand-in para la vista `vista_detalles_hoja`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_detalles_hoja` (
`id_detalle_` int(11)
,`codigo_hoja_servicio` int(11)
,`componente` varchar(100)
,`detalle` varchar(200)
,`id_movimiento_material` int(11)
,`id_material` int(11)
,`cantidad` int(11)
,`nombre_material` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_hojas_servicio_completa`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_hojas_servicio_completa` (
`codigo_hoja_servicio` int(11)
,`nro_solicitud` int(11)
,`id_tipo_servicio` int(11)
,`nombre_tipo_servicio` varchar(45)
,`redireccion` int(11)
,`cedula_tecnico` varchar(12)
,`nombre_tecnico` varchar(91)
,`fecha_resultado` datetime
,`resultado_hoja_servicio` varchar(45)
,`observacion` varchar(200)
,`estatus` varchar(1)
,`motivo` varchar(200)
,`fecha_solicitud` datetime
,`estado_solicitud` varchar(20)
,`nombre_solicitante` varchar(91)
,`telefono_empleado` varchar(15)
,`correo_empleado` varchar(100)
,`nombre_unidad` varchar(45)
,`nombre_dependencia` varchar(45)
,`tipo_equipo` varchar(45)
,`serial` varchar(45)
,`codigo_bien` varchar(20)
,`nombre_marca` varchar(45)
,`descripcion` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_hoja_servicio_completa`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_hoja_servicio_completa` (
`codigo_hoja_servicio` int(11)
,`nro_solicitud` int(11)
,`id_tipo_servicio` int(11)
,`nombre_tipo_servicio` varchar(45)
,`redireccion` int(11)
,`cedula_tecnico` varchar(12)
,`nombre_tecnico` varchar(91)
,`fecha_resultado` datetime
,`resultado_hoja_servicio` varchar(45)
,`observacion` varchar(200)
,`estatus` varchar(1)
,`motivo` varchar(200)
,`fecha_solicitud` datetime
,`estado_solicitud` varchar(20)
,`nombre_solicitante` varchar(91)
,`telefono_empleado` varchar(15)
,`correo_empleado` varchar(100)
,`nombre_unidad` varchar(45)
,`nombre_dependencia` varchar(45)
,`tipo_equipo` varchar(45)
,`serial` varchar(45)
,`codigo_bien` varchar(20)
,`nombre_marca` varchar(45)
,`descripcion` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_reporte_hojas_servicio`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_reporte_hojas_servicio` (
`codigo_hoja_servicio` int(11)
,`nro_solicitud` int(11)
,`nombre_tipo_servicio` varchar(45)
,`solicitante` varchar(91)
,`tipo_equipo` varchar(45)
,`nombre_marca` varchar(45)
,`serial` varchar(45)
,`codigo_bien` varchar(20)
,`motivo` varchar(200)
,`fecha_solicitud` datetime
,`resultado_hoja_servicio` varchar(45)
,`observacion` varchar(200)
,`estatus` varchar(1)
);

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

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_detalles_hoja`
--
DROP TABLE IF EXISTS `vista_detalles_hoja`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_detalles_hoja`  AS SELECT `dh`.`id_detalle_` AS `id_detalle_`, `dh`.`codigo_hoja_servicio` AS `codigo_hoja_servicio`, `dh`.`componente` AS `componente`, `dh`.`detalle` AS `detalle`, `dh`.`id_movimiento_material` AS `id_movimiento_material`, `mm`.`id_material` AS `id_material`, `mm`.`cantidad` AS `cantidad`, `mat`.`nombre_material` AS `nombre_material` FROM ((`detalle_hoja` `dh` left join `movimiento_materiales` `mm` on(`dh`.`id_movimiento_material` = `mm`.`id_movimiento_material`)) left join `material` `mat` on(`mm`.`id_material` = `mat`.`id_material`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_hojas_servicio_completa`
--
DROP TABLE IF EXISTS `vista_hojas_servicio_completa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_hojas_servicio_completa`  AS SELECT `hs`.`codigo_hoja_servicio` AS `codigo_hoja_servicio`, `hs`.`nro_solicitud` AS `nro_solicitud`, `hs`.`id_tipo_servicio` AS `id_tipo_servicio`, `ts`.`nombre_tipo_servicio` AS `nombre_tipo_servicio`, `hs`.`redireccion` AS `redireccion`, `hs`.`cedula_tecnico` AS `cedula_tecnico`, concat(coalesce(`tec`.`nombre_empleado`,''),' ',coalesce(`tec`.`apellido_empleado`,'')) AS `nombre_tecnico`, `hs`.`fecha_resultado` AS `fecha_resultado`, `hs`.`resultado_hoja_servicio` AS `resultado_hoja_servicio`, `hs`.`observacion` AS `observacion`, `hs`.`estatus` AS `estatus`, `s`.`motivo` AS `motivo`, `s`.`fecha_solicitud` AS `fecha_solicitud`, `s`.`estado_solicitud` AS `estado_solicitud`, concat(coalesce(`sol`.`nombre_empleado`,''),' ',coalesce(`sol`.`apellido_empleado`,'')) AS `nombre_solicitante`, coalesce(`sol`.`telefono_empleado`,'N/A') AS `telefono_empleado`, coalesce(`sol`.`correo_empleado`,'N/A') AS `correo_empleado`, coalesce(`u`.`nombre_unidad`,'N/A') AS `nombre_unidad`, coalesce(`d`.`nombre`,'N/A') AS `nombre_dependencia`, coalesce(`e`.`tipo_equipo`,'N/A') AS `tipo_equipo`, coalesce(`e`.`serial`,'N/A') AS `serial`, coalesce(`b`.`codigo_bien`,'N/A') AS `codigo_bien`, coalesce(`m`.`nombre_marca`,'N/A') AS `nombre_marca`, coalesce(`b`.`descripcion`,'N/A') AS `descripcion` FROM (((((((((`hoja_servicio` `hs` join `solicitud` `s` on(`hs`.`nro_solicitud` = `s`.`nro_solicitud`)) join `tipo_servicio` `ts` on(`hs`.`id_tipo_servicio` = `ts`.`id_tipo_servicio`)) join `empleado` `sol` on(`s`.`cedula_solicitante` = `sol`.`cedula_empleado`)) left join `empleado` `tec` on(`hs`.`cedula_tecnico` = `tec`.`cedula_empleado`)) left join `unidad` `u` on(`sol`.`id_unidad` = `u`.`id_unidad`)) left join `dependencia` `d` on(`u`.`id_dependencia` = `d`.`id`)) left join `equipo` `e` on(`s`.`id_equipo` = `e`.`id_equipo`)) left join `bien` `b` on(`e`.`codigo_bien` = `b`.`codigo_bien`)) left join `marca` `m` on(`b`.`id_marca` = `m`.`id_marca`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_hoja_servicio_completa`
--
DROP TABLE IF EXISTS `vista_hoja_servicio_completa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_hoja_servicio_completa`  AS SELECT `hs`.`codigo_hoja_servicio` AS `codigo_hoja_servicio`, `hs`.`nro_solicitud` AS `nro_solicitud`, `hs`.`id_tipo_servicio` AS `id_tipo_servicio`, `ts`.`nombre_tipo_servicio` AS `nombre_tipo_servicio`, `hs`.`redireccion` AS `redireccion`, `hs`.`cedula_tecnico` AS `cedula_tecnico`, concat(coalesce(`tec`.`nombre_empleado`,''),' ',coalesce(`tec`.`apellido_empleado`,'')) AS `nombre_tecnico`, `hs`.`fecha_resultado` AS `fecha_resultado`, `hs`.`resultado_hoja_servicio` AS `resultado_hoja_servicio`, `hs`.`observacion` AS `observacion`, `hs`.`estatus` AS `estatus`, `s`.`motivo` AS `motivo`, `s`.`fecha_solicitud` AS `fecha_solicitud`, `s`.`estado_solicitud` AS `estado_solicitud`, concat(coalesce(`sol`.`nombre_empleado`,''),' ',coalesce(`sol`.`apellido_empleado`,'')) AS `nombre_solicitante`, coalesce(`sol`.`telefono_empleado`,'N/A') AS `telefono_empleado`, coalesce(`sol`.`correo_empleado`,'N/A') AS `correo_empleado`, coalesce(`u`.`nombre_unidad`,'N/A') AS `nombre_unidad`, coalesce(`d`.`nombre`,'N/A') AS `nombre_dependencia`, coalesce(`e`.`tipo_equipo`,'N/A') AS `tipo_equipo`, coalesce(`e`.`serial`,'N/A') AS `serial`, coalesce(`b`.`codigo_bien`,'N/A') AS `codigo_bien`, coalesce(`m`.`nombre_marca`,'N/A') AS `nombre_marca`, coalesce(`b`.`descripcion`,'N/A') AS `descripcion` FROM (((((((((`hoja_servicio` `hs` join `solicitud` `s` on(`hs`.`nro_solicitud` = `s`.`nro_solicitud`)) join `tipo_servicio` `ts` on(`hs`.`id_tipo_servicio` = `ts`.`id_tipo_servicio`)) join `empleado` `sol` on(`s`.`cedula_solicitante` = `sol`.`cedula_empleado`)) left join `empleado` `tec` on(`hs`.`cedula_tecnico` = `tec`.`cedula_empleado`)) left join `unidad` `u` on(`sol`.`id_unidad` = `u`.`id_unidad`)) left join `dependencia` `d` on(`u`.`id_dependencia` = `d`.`id`)) left join `equipo` `e` on(`s`.`id_equipo` = `e`.`id_equipo`)) left join `bien` `b` on(`e`.`codigo_bien` = `b`.`codigo_bien`)) left join `marca` `m` on(`b`.`id_marca` = `m`.`id_marca`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_reporte_hojas_servicio`
--
DROP TABLE IF EXISTS `vista_reporte_hojas_servicio`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_reporte_hojas_servicio`  AS SELECT `hs`.`codigo_hoja_servicio` AS `codigo_hoja_servicio`, `hs`.`nro_solicitud` AS `nro_solicitud`, `ts`.`nombre_tipo_servicio` AS `nombre_tipo_servicio`, concat(`sol`.`nombre_empleado`,' ',`sol`.`apellido_empleado`) AS `solicitante`, `e`.`tipo_equipo` AS `tipo_equipo`, `m`.`nombre_marca` AS `nombre_marca`, `e`.`serial` AS `serial`, `b`.`codigo_bien` AS `codigo_bien`, `s`.`motivo` AS `motivo`, `s`.`fecha_solicitud` AS `fecha_solicitud`, `hs`.`resultado_hoja_servicio` AS `resultado_hoja_servicio`, `hs`.`observacion` AS `observacion`, `hs`.`estatus` AS `estatus` FROM ((((((`hoja_servicio` `hs` join `solicitud` `s` on(`hs`.`nro_solicitud` = `s`.`nro_solicitud`)) join `tipo_servicio` `ts` on(`hs`.`id_tipo_servicio` = `ts`.`id_tipo_servicio`)) join `empleado` `sol` on(`s`.`cedula_solicitante` = `sol`.`cedula_empleado`)) left join `equipo` `e` on(`s`.`id_equipo` = `e`.`id_equipo`)) left join `bien` `b` on(`e`.`codigo_bien` = `b`.`codigo_bien`)) left join `marca` `m` on(`b`.`id_marca` = `m`.`id_marca`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bien`
--
ALTER TABLE `bien`
  ADD PRIMARY KEY (`codigo_bien`),
  ADD KEY `id_tipo_bien` (`id_categoria`),
  ADD KEY `cedula_empleado` (`cedula_empleado`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_oficina` (`id_oficina`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `id_tipo_servicio` (`id_tipo_servicio`);

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
-- Indices de la tabla `servicio_prestado`
--
ALTER TABLE `servicio_prestado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipo_servicio` (`id_tipo_servicio`);

--
-- Indices de la tabla `servicio_realizado`
--
ALTER TABLE `servicio_realizado`
  ADD PRIMARY KEY (`id_servicio_realizado`),
  ADD KEY `id_servicio_prestado` (`id_servicio_prestado`),
  ADD KEY `id_hoja_servicio` (`id_hoja_servicio`);

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
  ADD KEY `cedula_solicitante` (`cedula_solicitante`);

--
-- Indices de la tabla `switch`
--
ALTER TABLE `switch`
  ADD PRIMARY KEY (`codigo_bien`),
  ADD UNIQUE KEY `serial` (`serial`),
  ADD KEY `Piso_id` (`id_piso`);

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
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_hoja`
--
ALTER TABLE `detalle_hoja`
  MODIFY `id_detalle_` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `hoja_servicio`
--
ALTER TABLE `hoja_servicio`
  MODIFY `codigo_hoja_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

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
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `movimiento_materiales`
--
ALTER TABLE `movimiento_materiales`
  MODIFY `id_movimiento_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT de la tabla `servicio_prestado`
--
ALTER TABLE `servicio_prestado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicio_realizado`
--
ALTER TABLE `servicio_realizado`
  MODIFY `id_servicio_realizado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `nro_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT de la tabla `solicitud_compra`
--
ALTER TABLE `solicitud_compra`
  MODIFY `id_solicitud_compra` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `bien_ibfk_5` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bien_ibfk_6` FOREIGN KEY (`id_oficina`) REFERENCES `oficina` (`id_oficina`) ON DELETE CASCADE;

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `categoria_ibfk_1` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `servicio_prestado`
--
ALTER TABLE `servicio_prestado`
  ADD CONSTRAINT `servicio_prestado_ibfk_1` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `servicio_realizado`
--
ALTER TABLE `servicio_realizado`
  ADD CONSTRAINT `servicio_realizado_ibfk_1` FOREIGN KEY (`id_servicio_prestado`) REFERENCES `servicio_prestado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `servicio_realizado_ibfk_2` FOREIGN KEY (`id_hoja_servicio`) REFERENCES `hoja_servicio` (`codigo_hoja_servicio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `solicitud_ibfk_1` FOREIGN KEY (`cedula_solicitante`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud_compra`
--
ALTER TABLE `solicitud_compra`
  ADD CONSTRAINT `solicitud_compra_ibfk_1` FOREIGN KEY (`cedula_solicitante`) REFERENCES `empleado` (`cedula_empleado`) ON DELETE CASCADE ON UPDATE CASCADE;

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
