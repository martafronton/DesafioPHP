-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-10-2025 a las 10:48:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hobbyte`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casilla`
--

CREATE TABLE `casilla` (
  `id_casilla` int(11) NOT NULL,
  `id_partida` int(11) NOT NULL,
  `posicion` int(11) NOT NULL,
  `tipo_prueba` enum('magia','fuerza','habilidad') NOT NULL,
  `esfuerzo` int(11) NOT NULL CHECK (`esfuerzo` in (5,10,15,20,25,30,35,40,45,50)),
  `estado` enum('oculta','destapada','resuelta') NOT NULL DEFAULT 'oculta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casilla`
--

INSERT INTO `casilla` (`id_casilla`, `id_partida`, `posicion`, `tipo_prueba`, `esfuerzo`, `estado`) VALUES
(1, 2, 1, 'fuerza', 15, 'oculta'),
(2, 2, 2, 'habilidad', 20, 'oculta'),
(3, 2, 3, 'fuerza', 50, 'oculta'),
(4, 2, 4, 'magia', 20, 'oculta'),
(5, 2, 5, 'habilidad', 20, 'oculta'),
(6, 2, 6, 'habilidad', 20, 'oculta'),
(7, 2, 7, 'fuerza', 30, 'oculta'),
(8, 2, 8, 'magia', 45, 'oculta'),
(9, 2, 9, 'habilidad', 15, 'oculta'),
(10, 2, 10, 'habilidad', 20, 'oculta'),
(11, 2, 11, 'magia', 5, 'oculta'),
(12, 2, 12, 'fuerza', 20, 'oculta'),
(13, 2, 13, 'fuerza', 10, 'oculta'),
(14, 2, 14, 'magia', 40, 'oculta'),
(15, 2, 15, 'magia', 30, 'oculta'),
(16, 2, 16, 'magia', 20, 'oculta'),
(17, 2, 17, 'magia', 15, 'oculta'),
(18, 2, 18, 'magia', 5, 'oculta'),
(19, 2, 19, 'magia', 5, 'oculta'),
(20, 2, 20, 'habilidad', 10, 'oculta'),
(21, 3, 1, 'habilidad', 10, 'oculta'),
(22, 3, 2, 'fuerza', 10, 'oculta'),
(23, 3, 3, 'habilidad', 10, 'oculta'),
(24, 3, 4, 'fuerza', 5, 'oculta'),
(25, 3, 5, 'habilidad', 20, 'oculta'),
(26, 3, 6, 'fuerza', 10, 'oculta'),
(27, 3, 7, 'magia', 5, 'oculta'),
(28, 3, 8, 'fuerza', 10, 'oculta'),
(29, 3, 9, 'magia', 10, 'oculta'),
(30, 3, 10, 'magia', 25, 'oculta'),
(31, 3, 11, 'magia', 5, 'oculta'),
(32, 3, 12, 'habilidad', 5, 'oculta'),
(33, 3, 13, 'fuerza', 15, 'oculta'),
(34, 3, 14, 'fuerza', 30, 'oculta'),
(35, 3, 15, 'habilidad', 10, 'oculta'),
(36, 3, 16, 'habilidad', 10, 'oculta'),
(37, 3, 17, 'habilidad', 25, 'oculta'),
(38, 3, 18, 'habilidad', 20, 'oculta'),
(39, 3, 19, 'magia', 20, 'oculta'),
(40, 3, 20, 'habilidad', 30, 'oculta'),
(41, 4, 1, 'magia', 35, 'oculta'),
(42, 4, 2, 'magia', 15, 'oculta'),
(43, 4, 3, 'magia', 35, 'oculta'),
(44, 4, 4, 'fuerza', 5, 'oculta'),
(45, 4, 5, 'fuerza', 20, 'oculta'),
(46, 4, 6, 'habilidad', 30, 'oculta'),
(47, 4, 7, 'fuerza', 15, 'oculta'),
(48, 4, 8, 'habilidad', 15, 'oculta'),
(49, 4, 9, 'magia', 40, 'oculta'),
(50, 4, 10, 'fuerza', 10, 'oculta'),
(51, 4, 11, 'fuerza', 35, 'oculta'),
(52, 4, 12, 'fuerza', 20, 'oculta'),
(53, 4, 13, 'fuerza', 10, 'oculta'),
(54, 4, 14, 'habilidad', 45, 'oculta'),
(55, 4, 15, 'fuerza', 35, 'oculta'),
(56, 4, 16, 'habilidad', 10, 'oculta'),
(57, 4, 17, 'magia', 30, 'oculta'),
(58, 4, 18, 'habilidad', 30, 'oculta'),
(59, 4, 19, 'magia', 20, 'oculta'),
(60, 4, 20, 'habilidad', 25, 'oculta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `id_partida` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo` enum('estandar','personalizada') NOT NULL DEFAULT 'estandar',
  `estado` enum('en_curso','ganada','perdida','rendida') NOT NULL DEFAULT 'en_curso',
  `fecha_inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_fin` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partida`
--

INSERT INTO `partida` (`id_partida`, `id_usuario`, `tipo`, `estado`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 2, 'personalizada', 'en_curso', '2025-10-07 20:27:22', NULL),
(2, 2, 'personalizada', 'en_curso', '2025-10-07 20:28:08', NULL),
(3, 3, 'personalizada', 'en_curso', '2025-10-07 22:08:07', NULL),
(4, 1, '', 'en_curso', '2025-10-07 22:08:29', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personaje`
--

CREATE TABLE `personaje` (
  `id_personaje` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo_prueba` enum('magia','fuerza','habilidad') NOT NULL,
  `capacidad_max` int(11) NOT NULL DEFAULT 50,
  `id_partida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `email`, `contrasena`, `fecha_registro`) VALUES
(1, 'marta', 'marta@example.com', '81dc9bdb52d04dc20036dbd8313ed055', '2025-10-06 04:37:34'),
(2, 'Michael', 'jackson@.com', '7841c69c19db4d0ac8485467b37e4a3c', '2025-10-07 18:36:42'),
(3, 'Steven', 'spielberg@.com', '54170f5fc4aeae2957ff71d0041b6046', '2025-10-07 18:49:28'),
(4, 'Bono', 'u2@.com', '0c001fa6dae730dcd318584a08def8c4', '2025-10-07 19:14:51'),
(7, 'Tom Hanks', 'gump@.com', '57e09186f03a955a27677e50129a00b4', '2025-10-07 19:41:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `casilla`
--
ALTER TABLE `casilla`
  ADD PRIMARY KEY (`id_casilla`),
  ADD KEY `id_partida` (`id_partida`);

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `personaje`
--
ALTER TABLE `personaje`
  ADD PRIMARY KEY (`id_personaje`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_partida` (`id_partida`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `casilla`
--
ALTER TABLE `casilla`
  MODIFY `id_casilla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `personaje`
--
ALTER TABLE `personaje`
  MODIFY `id_personaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `casilla`
--
ALTER TABLE `casilla`
  ADD CONSTRAINT `casilla_ibfk_1` FOREIGN KEY (`id_partida`) REFERENCES `partida` (`id_partida`) ON DELETE CASCADE;

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `partida_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `personaje`
--
ALTER TABLE `personaje`
  ADD CONSTRAINT `personaje_ibfk_1` FOREIGN KEY (`id_partida`) REFERENCES `partida` (`id_partida`);

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `usuario_rol_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_rol_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
