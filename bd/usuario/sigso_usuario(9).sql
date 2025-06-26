-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-06-2025 a las 23:02:50
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
-- Base de datos: `sigso_usuario`
--
CREATE DATABASE IF NOT EXISTS `sigso_usuario` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sigso_usuario`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_bitacora` int(11) NOT NULL,
  `usuario` varchar(45) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `modulo` varchar(45) NOT NULL,
  `accion_bitacora` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_bitacora`, `usuario`, `modulo`, `accion_bitacora`, `fecha`, `hora`) VALUES
(1, 'lz2712', 'Bitácora', '(lz2712), Ingresó al módulo de Bitácora', '2025-05-15', '18:14:38'),
(2, 'lz2712', 'Bitácora', '(lz2712), Ingresó al módulo de Bitácora', '2025-05-15', '18:15:11');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id_modulo` int(11) NOT NULL,
  `nombre_modulo` varchar(45) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id_modulo`, `nombre_modulo`) VALUES
(1, 'usuario'),
(2, 'rol'),
(3, 'bitacora'),
(4, 'mantenimiento'),
(5, 'empleado'),
(6, 'tecnico'),
(7, 'solicitud'),
(8, 'hoja_servicio'),
(9, 'ente'),
(10, 'dependencia'),
(11, 'unidad'),
(12, 'cargo'),
(13, 'tipo_servicio'),
(14, 'bien'),
(15, 'tipo_bien'),
(16, 'marca'),
(17, 'equipo'),
(18, 'switch'),
(19, 'patch_panel'),
(20, 'interconexion'),
(21, 'punto_conexion'),
(22, 'piso'),
(23, 'oficina'),
(24, 'material');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id` int(11) NOT NULL,
  `usuario` varchar(45) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `modulo` varchar(45) NOT NULL,
  `mensaje` int(100) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` varchar(45) NOT NULL DEFAULT 'Nuevo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificacion`
--

INSERT INTO `notificacion` (`id`, `usuario`, `modulo`, `mensaje`, `fecha`, `hora`, `estado`) VALUES
(1, 'lz2712', 'Solicitudes', 0, '2025-06-04', '15:23:49', 'Leído'),
(2, 'lz2712', 'Material', 0, '2025-06-04', '15:25:00', 'Leído');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `accion_permiso` varchar(100) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id_permiso`, `id_rol`, `id_modulo`, `accion_permiso`, `estado`) VALUES
(1, 1, 1, 'registrar', 1),
(2, 1, 1, 'ver', 1),
(3, 1, 1, 'modificar', 1),
(4, 1, 1, 'eliminar', 1),
(5, 1, 2, 'registrar', 1),
(6, 1, 2, 'ver', 1),
(7, 1, 2, 'modificar', 1),
(8, 1, 2, 'eliminar', 1),
(9, 1, 3, 'ver', 1),
(10, 1, 4, 'ver', 1),
(11, 1, 4, 'exportar', 1),
(12, 1, 4, 'importar', 1),
(13, 1, 4, 'eliminar', 1),
(14, 1, 5, 'registrar', 1),
(15, 1, 5, 'ver', 1),
(16, 1, 5, 'modificar', 1),
(17, 1, 5, 'eliminar', 1),
(18, 1, 6, 'registrar', 1),
(19, 1, 6, 'ver', 1),
(20, 1, 6, 'modificar', 1),
(21, 1, 6, 'eliminar', 1),
(22, 1, 7, 'registrar', 1),
(23, 1, 7, 'ver_solicitud', 1),
(24, 1, 7, 'ver_mi_solicitud', 1),
(25, 1, 7, 'modificar', 1),
(26, 1, 7, 'eliminar', 1),
(27, 1, 8, 'registrar', 1),
(28, 1, 8, 'ver', 1),
(29, 1, 8, 'modificar', 1),
(30, 1, 8, 'eliminar', 1),
(31, 1, 9, 'registrar', 1),
(32, 1, 9, 'ver', 1),
(33, 1, 9, 'modificar', 1),
(34, 1, 9, 'eliminar', 1),
(35, 1, 10, 'registrar', 1),
(36, 1, 10, 'ver', 1),
(37, 1, 10, 'modificar', 1),
(38, 1, 10, 'eliminar', 1),
(39, 1, 11, 'registrar', 1),
(40, 1, 11, 'ver', 1),
(41, 1, 11, 'modificar', 1),
(42, 1, 11, 'eliminar', 1),
(43, 1, 12, 'registrar', 1),
(44, 1, 12, 'ver', 1),
(45, 1, 12, 'modificar', 1),
(46, 1, 12, 'eliminar', 1),
(47, 1, 13, 'registrar', 1),
(48, 1, 13, 'ver', 1),
(49, 1, 13, 'modificar', 1),
(50, 1, 13, 'eliminar', 1),
(51, 1, 14, 'registrar', 1),
(52, 1, 14, 'ver', 1),
(53, 1, 14, 'modificar', 1),
(54, 1, 14, 'eliminar', 1),
(55, 1, 15, 'registrar', 1),
(56, 1, 15, 'ver', 1),
(57, 1, 15, 'modificar', 1),
(58, 1, 15, 'eliminar', 1),
(59, 1, 16, 'registrar', 1),
(60, 1, 16, 'ver', 1),
(61, 1, 16, 'modificar', 1),
(62, 1, 16, 'eliminar', 1),
(63, 1, 17, 'registrar', 1),
(64, 1, 17, 'ver', 1),
(65, 1, 17, 'modificar', 1),
(66, 1, 17, 'eliminar', 1),
(67, 1, 18, 'registrar', 1),
(68, 1, 18, 'ver', 1),
(69, 1, 18, 'modificar', 1),
(70, 1, 18, 'eliminar', 1),
(71, 1, 19, 'registrar', 1),
(72, 1, 19, 'ver', 1),
(73, 1, 19, 'modificar', 1),
(74, 1, 19, 'eliminar', 1),
(75, 1, 20, 'registrar', 1),
(76, 1, 20, 'ver', 1),
(77, 1, 20, 'modificar', 1),
(78, 1, 20, 'eliminar', 1),
(79, 1, 21, 'registrar', 1),
(80, 1, 21, 'ver', 1),
(81, 1, 21, 'modificar', 1),
(82, 1, 21, 'eliminar', 1),
(83, 1, 22, 'registrar', 1),
(84, 1, 22, 'ver', 1),
(85, 1, 22, 'modificar', 1),
(86, 1, 22, 'eliminar', 1),
(87, 1, 23, 'registrar', 1),
(88, 1, 23, 'ver', 1),
(89, 1, 23, 'modificar', 1),
(90, 1, 23, 'eliminar', 1),
(91, 1, 24, 'registrar', 1),
(92, 1, 24, 'ver', 1),
(93, 1, 24, 'modificar', 1),
(94, 1, 24, 'eliminar', 1),
(95, 2, 1, 'registrar', 1),
(96, 2, 1, 'ver', 1),
(97, 2, 1, 'modificar', 1),
(98, 2, 1, 'eliminar', 1),
(99, 2, 2, 'registrar', 1),
(100, 2, 2, 'ver', 1),
(101, 2, 2, 'modificar', 1),
(102, 2, 2, 'eliminar', 1),
(103, 2, 3, 'ver', 1),
(104, 2, 4, 'ver', 1),
(105, 2, 4, 'exportar', 1),
(106, 2, 4, 'importar', 1),
(107, 2, 4, 'eliminar', 1),
(108, 2, 5, 'registrar', 0),
(109, 2, 5, 'ver', 0),
(110, 2, 5, 'modificar', 0),
(111, 2, 5, 'eliminar', 0),
(112, 2, 6, 'registrar', 0),
(113, 2, 6, 'ver', 0),
(114, 2, 6, 'modificar', 0),
(115, 2, 6, 'eliminar', 0),
(116, 2, 7, 'registrar', 0),
(117, 2, 7, 'ver_solicitud', 0),
(118, 2, 7, 'ver_mi_solicitud', 0),
(119, 2, 7, 'modificar', 0),
(120, 2, 7, 'eliminar', 0),
(121, 2, 8, 'registrar', 0),
(122, 2, 8, 'ver', 0),
(123, 2, 8, 'modificar', 0),
(124, 2, 8, 'eliminar', 0),
(125, 2, 9, 'registrar', 0),
(126, 2, 9, 'ver', 0),
(127, 2, 9, 'modificar', 0),
(128, 2, 9, 'eliminar', 0),
(129, 2, 10, 'registrar', 0),
(130, 2, 10, 'ver', 0),
(131, 2, 10, 'modificar', 0),
(132, 2, 10, 'eliminar', 0),
(133, 2, 11, 'registrar', 0),
(134, 2, 11, 'ver', 0),
(135, 2, 11, 'modificar', 0),
(136, 2, 11, 'eliminar', 0),
(137, 2, 12, 'registrar', 0),
(138, 2, 12, 'ver', 0),
(139, 2, 12, 'modificar', 0),
(140, 2, 12, 'eliminar', 0),
(141, 2, 13, 'registrar', 0),
(142, 2, 13, 'ver', 0),
(143, 2, 13, 'modificar', 0),
(144, 2, 13, 'eliminar', 0),
(145, 2, 14, 'registrar', 0),
(146, 2, 14, 'ver', 0),
(147, 2, 14, 'modificar', 0),
(148, 2, 14, 'eliminar', 0),
(149, 2, 15, 'registrar', 0),
(150, 2, 15, 'ver', 0),
(151, 2, 15, 'modificar', 0),
(152, 2, 15, 'eliminar', 0),
(153, 2, 16, 'registrar', 0),
(154, 2, 16, 'ver', 0),
(155, 2, 16, 'modificar', 0),
(156, 2, 16, 'eliminar', 0),
(157, 2, 17, 'registrar', 0),
(158, 2, 17, 'ver', 0),
(159, 2, 17, 'modificar', 0),
(160, 2, 17, 'eliminar', 0),
(161, 2, 18, 'registrar', 0),
(162, 2, 18, 'ver', 0),
(163, 2, 18, 'modificar', 0),
(164, 2, 18, 'eliminar', 0),
(165, 2, 19, 'registrar', 0),
(166, 2, 19, 'ver', 0),
(167, 2, 19, 'modificar', 0),
(168, 2, 19, 'eliminar', 0),
(169, 2, 20, 'registrar', 0),
(170, 2, 20, 'ver', 0),
(171, 2, 20, 'modificar', 0),
(172, 2, 20, 'eliminar', 0),
(173, 2, 21, 'registrar', 0),
(174, 2, 21, 'ver', 0),
(175, 2, 21, 'modificar', 0),
(176, 2, 21, 'eliminar', 0),
(177, 2, 22, 'registrar', 0),
(178, 2, 22, 'ver', 0),
(179, 2, 22, 'modificar', 0),
(180, 2, 22, 'eliminar', 0),
(181, 2, 23, 'registrar', 0),
(182, 2, 23, 'ver', 0),
(183, 2, 23, 'modificar', 0),
(184, 2, 23, 'eliminar', 0),
(185, 2, 24, 'registrar', 0),
(186, 2, 24, 'ver', 0),
(187, 2, 24, 'modificar', 0),
(188, 2, 24, 'eliminar', 0),
(189, 3, 1, 'registrar', 1),
(190, 3, 1, 'ver', 1),
(191, 3, 1, 'modificar', 1),
(192, 3, 1, 'eliminar', 1),
(193, 3, 2, 'registrar', 1),
(194, 3, 2, 'ver', 1),
(195, 3, 2, 'modificar', 1),
(196, 3, 2, 'eliminar', 1),
(197, 3, 3, 'ver', 1),
(198, 3, 4, 'ver', 1),
(199, 3, 4, 'exportar', 1),
(200, 3, 4, 'importar', 1),
(201, 3, 4, 'eliminar', 1),
(202, 3, 5, 'registrar', 1),
(203, 3, 5, 'ver', 1),
(204, 3, 5, 'modificar', 1),
(205, 3, 5, 'eliminar', 1),
(206, 3, 6, 'registrar', 1),
(207, 3, 6, 'ver', 1),
(208, 3, 6, 'modificar', 1),
(209, 3, 6, 'eliminar', 1),
(210, 3, 7, 'registrar', 0),
(211, 3, 7, 'ver_solicitud', 0),
(212, 3, 7, 'ver_mi_solicitud', 0),
(213, 3, 7, 'modificar', 0),
(214, 3, 7, 'eliminar', 0),
(215, 3, 8, 'registrar', 0),
(216, 3, 8, 'ver', 0),
(217, 3, 8, 'modificar', 0),
(218, 3, 8, 'eliminar', 0),
(219, 3, 9, 'registrar', 0),
(220, 3, 9, 'ver', 0),
(221, 3, 9, 'modificar', 0),
(222, 3, 9, 'eliminar', 0),
(223, 3, 10, 'registrar', 0),
(224, 3, 10, 'ver', 0),
(225, 3, 10, 'modificar', 0),
(226, 3, 10, 'eliminar', 0),
(227, 3, 11, 'registrar', 0),
(228, 3, 11, 'ver', 0),
(229, 3, 11, 'modificar', 0),
(230, 3, 11, 'eliminar', 0),
(231, 3, 12, 'registrar', 0),
(232, 3, 12, 'ver', 0),
(233, 3, 12, 'modificar', 0),
(234, 3, 12, 'eliminar', 0),
(235, 3, 13, 'registrar', 0),
(236, 3, 13, 'ver', 0),
(237, 3, 13, 'modificar', 0),
(238, 3, 13, 'eliminar', 0),
(239, 3, 14, 'registrar', 0),
(240, 3, 14, 'ver', 0),
(241, 3, 14, 'modificar', 0),
(242, 3, 14, 'eliminar', 0),
(243, 3, 15, 'registrar', 0),
(244, 3, 15, 'ver', 0),
(245, 3, 15, 'modificar', 0),
(246, 3, 15, 'eliminar', 0),
(247, 3, 16, 'registrar', 0),
(248, 3, 16, 'ver', 0),
(249, 3, 16, 'modificar', 0),
(250, 3, 16, 'eliminar', 0),
(251, 3, 17, 'registrar', 0),
(252, 3, 17, 'ver', 0),
(253, 3, 17, 'modificar', 0),
(254, 3, 17, 'eliminar', 0),
(255, 3, 18, 'registrar', 0),
(256, 3, 18, 'ver', 0),
(257, 3, 18, 'modificar', 0),
(258, 3, 18, 'eliminar', 0),
(259, 3, 19, 'registrar', 0),
(260, 3, 19, 'ver', 0),
(261, 3, 19, 'modificar', 0),
(262, 3, 19, 'eliminar', 0),
(263, 3, 20, 'registrar', 0),
(264, 3, 20, 'ver', 0),
(265, 3, 20, 'modificar', 0),
(266, 3, 20, 'eliminar', 0),
(267, 3, 21, 'registrar', 0),
(268, 3, 21, 'ver', 0),
(269, 3, 21, 'modificar', 0),
(270, 3, 21, 'eliminar', 0),
(271, 3, 22, 'registrar', 1),
(272, 3, 22, 'ver', 1),
(273, 3, 22, 'modificar', 1),
(274, 3, 22, 'eliminar', 1),
(275, 3, 23, 'registrar', 0),
(276, 3, 23, 'ver', 0),
(277, 3, 23, 'modificar', 0),
(278, 3, 23, 'eliminar', 0),
(279, 3, 24, 'registrar', 0),
(280, 3, 24, 'ver', 0),
(281, 3, 24, 'modificar', 0),
(282, 3, 24, 'eliminar', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `estatus` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`, `estatus`) VALUES
(1, 'SUPERUSUARIO', 1),
(2, 'ADMINISTRADOR', 1),
(3, 'TECNICO', 1),
(4, 'SECRETARIA', 1),
(5, 'SOLICITANTE', 1),
(6, 'VISITANTE', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `nombre_usuario` varchar(45) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `nombres` varchar(65) NOT NULL,
  `apellidos` varchar(65) NOT NULL,
  `telefono` varchar(13) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `clave` varchar(128) NOT NULL,
  `foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`nombre_usuario`, `cedula`, `id_rol`, `nombres`, `apellidos`, `telefono`, `correo`, `clave`, `foto`) VALUES
('cabrerajorge', 'V-31843937', 1, 'Jorge', 'Cabrera', '0424-5567016', 'cabrerajorge2003@gmail.com', '$2y$10$1vXPHPs29V2T.1HVvUHXn.rzC3KfFwxTXbnosxiJRJEWA4ZATIEBm', ''),
('elymost_wanted', 'V-31245781', 6, 'Elys', 'Sivira', '0416-0426123', 'elyelprogamer@gmail.com', '', ''),
('frank30', 'V-30454597', 2, 'Frankling', 'Fonseca', '0424-5041921', 'ranklinjavierfonsecavasquez@gmail.com', '$2y$10$d64FtFMmW8sTyuiKyxD52eN0q9vdBEglqAbOJXUzw80aRB3/uko7K', ''),
('imanol1520', 'V-32678991', 3, 'Imanol', 'Patiño', '0412-1670812', 'imanol_45@gmail.com', '', ''),
('joli17', 'V-4865342', 6, 'Angelina', 'Joliet', '0414-1050667', 'joliethollywood@gmail.com', '', ''),
('lonnar15', 'V-22450312', 2, 'Leonardo', 'DiCaprio', '0416-7091089', 'leonard_15@gmail.com', '$2y$10$twZvWGxCCvMx.MxhU.AqGeKzN5IInqTZyCC4WkL1z8MbUpnMIjT3m', ''),
('lz2712', 'V-30266398', 2, 'Leizer', 'Torrealba', '0416-0506544', 'leizeraponte2020@gmail.com', '$2y$10$sONqWv4yy5PEeePKYljGXOLjFuJa1lMz9yua.3cMVAHG4hU.75Jpe', 'assets/img/foto-perfil/V-30266398.png'),
('mari14', 'V-30587785', 2, 'Mariangel', 'Bokor', '0424-5319088', 'bokorarcangel447@gmail.com', '$2y$10$nMQ5inBjrq6FeZbt8sTQk.9Mkx4c.H93TVw.39zCiC3ovXCZoqyaa', ''),
('maria123', 'V-21140325', 2, 'Felix', 'Mujica', '0400-0000000', 'ejemplo@gmail.com', '12345', ''),
('root', 'V-1234567', 1, 'root', 'admin', '0000-0000000', 'prueba@gmail.com', '123', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_bitacora`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`nombre_usuario`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `cedula_2` (`cedula`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_bitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6233;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`nombre_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`nombre_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD CONSTRAINT `permiso_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permiso_ibfk_3` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
