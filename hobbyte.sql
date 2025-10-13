-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-10-2025 a las 22:33:50
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
  `estado` enum('oculta','ganada','perdida') NOT NULL DEFAULT 'oculta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casilla`
--

INSERT INTO `casilla` (`id_casilla`, `id_partida`, `posicion`, `tipo_prueba`, `esfuerzo`, `estado`) VALUES
(1, 1, 1, 'habilidad', 25, 'oculta'),
(2, 1, 2, 'fuerza', 40, 'oculta'),
(3, 1, 3, 'magia', 5, 'oculta'),
(4, 1, 4, 'magia', 30, 'oculta'),
(5, 1, 5, 'fuerza', 5, 'oculta'),
(6, 1, 6, 'habilidad', 10, 'oculta'),
(7, 1, 7, 'habilidad', 10, 'oculta'),
(8, 1, 8, 'fuerza', 10, 'oculta'),
(9, 1, 9, 'magia', 15, 'oculta'),
(10, 1, 10, 'fuerza', 45, 'oculta'),
(11, 2, 1, 'magia', 40, 'oculta'),
(12, 2, 2, 'magia', 20, 'oculta'),
(13, 2, 3, 'magia', 35, 'oculta'),
(14, 2, 4, 'fuerza', 30, 'oculta'),
(15, 2, 5, 'fuerza', 40, 'oculta'),
(16, 2, 6, 'fuerza', 25, 'oculta'),
(17, 2, 7, 'habilidad', 15, 'oculta'),
(18, 2, 8, 'fuerza', 30, 'oculta'),
(19, 2, 9, 'fuerza', 10, 'oculta'),
(20, 2, 10, 'habilidad', 30, 'oculta'),
(21, 3, 1, 'magia', 15, 'perdida'),
(22, 3, 2, 'fuerza', 15, 'perdida'),
(23, 3, 3, 'magia', 30, 'perdida'),
(24, 3, 4, 'fuerza', 15, 'perdida'),
(25, 3, 5, 'habilidad', 10, 'perdida'),
(26, 3, 6, 'fuerza', 20, 'perdida'),
(27, 3, 7, 'habilidad', 30, 'perdida'),
(28, 3, 8, 'magia', 5, 'perdida'),
(29, 3, 9, 'magia', 50, 'perdida'),
(30, 3, 10, 'habilidad', 5, 'perdida'),
(31, 3, 11, 'fuerza', 15, 'perdida'),
(32, 3, 12, 'fuerza', 50, 'perdida'),
(33, 3, 13, 'habilidad', 35, 'perdida'),
(34, 3, 14, 'habilidad', 10, 'perdida'),
(35, 3, 15, 'fuerza', 5, 'perdida'),
(36, 3, 16, 'magia', 20, 'perdida'),
(37, 3, 17, 'habilidad', 10, 'perdida'),
(38, 3, 18, 'fuerza', 5, 'perdida'),
(39, 3, 19, 'habilidad', 25, 'perdida'),
(40, 3, 20, 'fuerza', 40, 'perdida'),
(41, 4, 1, 'habilidad', 5, 'perdida'),
(42, 4, 2, 'habilidad', 15, 'perdida'),
(43, 4, 3, 'habilidad', 20, 'perdida'),
(44, 4, 4, 'fuerza', 5, 'perdida'),
(45, 4, 5, 'magia', 10, 'perdida'),
(46, 4, 6, 'habilidad', 5, 'perdida'),
(47, 4, 7, 'fuerza', 10, 'perdida'),
(48, 4, 8, 'magia', 45, 'perdida'),
(49, 4, 9, 'habilidad', 35, 'perdida'),
(50, 4, 10, 'magia', 20, 'perdida'),
(51, 4, 11, 'magia', 30, 'perdida'),
(52, 4, 12, 'habilidad', 15, 'perdida'),
(53, 4, 13, 'habilidad', 10, 'perdida'),
(54, 4, 14, 'magia', 20, 'perdida'),
(55, 4, 15, 'magia', 20, 'perdida'),
(56, 5, 1, 'habilidad', 20, 'perdida'),
(57, 5, 2, 'fuerza', 20, 'perdida'),
(58, 5, 3, 'magia', 5, 'perdida'),
(59, 5, 4, 'fuerza', 45, 'perdida'),
(60, 5, 5, 'habilidad', 5, 'perdida'),
(61, 5, 6, 'magia', 25, 'perdida'),
(62, 5, 7, 'magia', 5, 'perdida'),
(63, 5, 8, 'magia', 5, 'perdida'),
(64, 5, 9, 'habilidad', 25, 'perdida'),
(65, 5, 10, 'habilidad', 50, 'perdida'),
(66, 5, 11, 'fuerza', 40, 'perdida'),
(67, 5, 12, 'habilidad', 15, 'perdida'),
(68, 5, 13, 'magia', 25, 'perdida'),
(69, 5, 14, 'fuerza', 20, 'perdida'),
(70, 5, 15, 'habilidad', 20, 'perdida'),
(71, 6, 1, 'habilidad', 20, 'perdida'),
(72, 6, 2, 'fuerza', 25, 'perdida'),
(73, 6, 3, 'magia', 5, 'perdida'),
(74, 6, 4, 'magia', 20, 'perdida'),
(75, 6, 5, 'magia', 30, 'perdida'),
(76, 6, 6, 'habilidad', 40, 'perdida'),
(77, 6, 7, 'magia', 40, 'perdida'),
(78, 6, 8, 'habilidad', 5, 'perdida'),
(79, 6, 9, 'fuerza', 35, 'perdida'),
(80, 6, 10, 'fuerza', 40, 'perdida'),
(81, 6, 11, 'magia', 20, 'perdida'),
(82, 6, 12, 'magia', 40, 'perdida'),
(83, 6, 13, 'magia', 35, 'perdida'),
(84, 6, 14, 'magia', 20, 'perdida'),
(85, 6, 15, 'fuerza', 5, 'perdida'),
(86, 7, 1, 'fuerza', 35, 'perdida'),
(87, 7, 2, 'habilidad', 50, 'perdida'),
(88, 7, 3, 'habilidad', 10, 'perdida'),
(89, 7, 4, 'magia', 15, 'perdida'),
(90, 7, 5, 'habilidad', 20, 'perdida'),
(91, 7, 6, 'habilidad', 25, 'perdida'),
(92, 7, 7, 'magia', 5, 'perdida'),
(93, 7, 8, 'magia', 15, 'perdida'),
(94, 7, 9, 'magia', 5, 'perdida'),
(95, 7, 10, 'fuerza', 15, 'perdida'),
(96, 7, 11, 'habilidad', 20, 'perdida'),
(97, 7, 12, 'magia', 5, 'perdida'),
(98, 7, 13, 'magia', 10, 'perdida'),
(99, 7, 14, 'habilidad', 10, 'perdida'),
(100, 7, 15, 'habilidad', 15, 'perdida'),
(101, 8, 1, 'habilidad', 15, 'perdida'),
(102, 8, 2, 'fuerza', 25, 'perdida'),
(103, 8, 3, 'habilidad', 20, 'perdida'),
(104, 8, 4, 'magia', 15, 'perdida'),
(105, 8, 5, 'habilidad', 5, 'perdida'),
(106, 8, 6, 'magia', 25, 'perdida'),
(107, 8, 7, 'habilidad', 40, 'perdida'),
(108, 8, 8, 'habilidad', 20, 'perdida'),
(109, 8, 9, 'magia', 15, 'perdida'),
(110, 8, 10, 'magia', 10, 'perdida'),
(111, 8, 11, 'magia', 20, 'perdida'),
(112, 8, 12, 'magia', 25, 'perdida'),
(113, 8, 13, 'magia', 20, 'perdida'),
(114, 8, 14, 'habilidad', 30, 'perdida'),
(115, 8, 15, 'magia', 20, 'perdida'),
(116, 9, 1, 'fuerza', 25, 'perdida'),
(117, 9, 2, 'fuerza', 5, 'perdida'),
(118, 9, 3, 'fuerza', 5, 'perdida'),
(119, 9, 4, 'fuerza', 10, 'perdida'),
(120, 9, 5, 'habilidad', 50, 'oculta'),
(121, 9, 6, 'fuerza', 30, 'ganada'),
(122, 9, 7, 'habilidad', 50, 'oculta'),
(123, 9, 8, 'fuerza', 40, 'oculta'),
(124, 9, 9, 'habilidad', 20, 'perdida'),
(125, 9, 10, 'habilidad', 10, 'oculta'),
(126, 9, 11, 'magia', 20, 'oculta'),
(127, 9, 12, 'magia', 15, 'oculta'),
(128, 9, 13, 'magia', 15, 'oculta'),
(129, 9, 14, 'habilidad', 15, 'oculta'),
(130, 9, 15, 'magia', 15, 'oculta'),
(131, 10, 1, 'fuerza', 15, 'oculta'),
(132, 10, 2, 'magia', 10, 'oculta'),
(133, 10, 3, 'magia', 5, 'oculta'),
(134, 10, 4, 'habilidad', 40, 'oculta'),
(135, 10, 5, 'habilidad', 35, 'oculta'),
(136, 10, 6, 'magia', 5, 'oculta'),
(137, 10, 7, 'magia', 5, 'oculta'),
(138, 10, 8, 'magia', 35, 'oculta'),
(139, 10, 9, 'fuerza', 5, 'oculta'),
(140, 10, 10, 'magia', 40, 'ganada'),
(141, 10, 11, 'fuerza', 20, 'oculta'),
(142, 10, 12, 'fuerza', 20, 'oculta'),
(143, 10, 13, 'fuerza', 20, 'oculta'),
(144, 10, 14, 'habilidad', 20, 'oculta'),
(145, 10, 15, 'habilidad', 40, 'oculta');

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
  `intentos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partida`
--

INSERT INTO `partida` (`id_partida`, `id_usuario`, `tipo`, `estado`, `fecha_inicio`, `intentos`) VALUES
(1, 28, 'personalizada', 'en_curso', '2025-10-12 23:06:08', 0),
(2, 28, 'personalizada', 'en_curso', '2025-10-12 23:06:17', 0),
(3, 27, 'personalizada', 'rendida', '2025-10-12 23:06:41', 1),
(4, 27, 'personalizada', 'rendida', '2025-10-12 23:14:37', 0),
(5, 27, 'personalizada', 'rendida', '2025-10-12 23:16:06', 0),
(6, 27, 'personalizada', 'rendida', '2025-10-12 23:16:12', 0),
(7, 27, 'personalizada', 'rendida', '2025-10-13 00:03:07', 0),
(8, 27, 'personalizada', 'rendida', '2025-10-13 00:32:52', 0),
(9, 27, 'personalizada', 'perdida', '2025-10-13 17:53:20', 5),
(10, 27, 'personalizada', 'en_curso', '2025-10-13 18:52:17', 0);

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

--
-- Volcado de datos para la tabla `personaje`
--

INSERT INTO `personaje` (`id_personaje`, `nombre`, `tipo_prueba`, `capacidad_max`, `id_partida`) VALUES
(1, 'Gandalf', 'magia', 50, 1),
(2, 'Thorin', 'fuerza', 50, 1),
(3, 'Bilbo', 'habilidad', 50, 1),
(4, 'Gandalf', 'magia', 50, 2),
(5, 'Thorin', 'fuerza', 50, 2),
(6, 'Bilbo', 'habilidad', 50, 2),
(7, 'Gandalf', 'magia', 0, 3),
(8, 'Thorin', 'fuerza', 30, 3),
(9, 'Bilbo', 'habilidad', 0, 3),
(10, 'Gandalf', 'magia', 50, 4),
(11, 'Thorin', 'fuerza', 50, 4),
(12, 'Bilbo', 'habilidad', 50, 4),
(13, 'Gandalf', 'magia', 50, 5),
(14, 'Thorin', 'fuerza', 50, 5),
(15, 'Bilbo', 'habilidad', 50, 5),
(16, 'Gandalf', 'magia', 50, 6),
(17, 'Thorin', 'fuerza', 50, 6),
(18, 'Bilbo', 'habilidad', 50, 6),
(19, 'Gandalf', 'magia', 50, 7),
(20, 'Thorin', 'fuerza', 50, 7),
(21, 'Bilbo', 'habilidad', 50, 7),
(22, 'Gandalf', 'magia', 25, 8),
(23, 'Thorin', 'fuerza', 50, 8),
(24, 'Bilbo', 'habilidad', 50, 8),
(25, 'Gandalf', 'magia', 50, 9),
(26, 'Thorin', 'fuerza', 0, 9),
(27, 'Bilbo', 'habilidad', 0, 9),
(28, 'Gandalf', 'magia', 10, 10),
(29, 'Thorin', 'fuerza', 50, 10),
(30, 'Bilbo', 'habilidad', 50, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'admin'),
(2, 'usuario');

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
(4, 'Bono', 'u2@.com', '60777fea806cc617bf579149cd4dc9a9', '2025-10-07 19:14:51'),
(7, 'Tom Hanks', 'gump@.com', '57e09186f03a955a27677e50129a00b4', '2025-10-07 19:41:58'),
(19, 'UsuarioNuevo', 'nuevo@correo.com', '81dc9bdb52d04dc20036dbd8313ed055', '2025-10-08 14:44:07'),
(21, 'UsuarioNuevo', '@correo.com', '81dc9bdb52d04dc20036dbd8313ed055', '2025-10-08 14:46:47'),
(26, 'UsuarioNuevo', 'prueba@correo.com', '81dc9bdb52d04dc20036dbd8313ed055', '2025-10-08 14:48:18'),
(27, 'Rosa', 'admin@.com', '81dc9bdb52d04dc20036dbd8313ed055', '2025-10-08 15:15:08'),
(28, 'Anahis', 'h@correo.com', '81dc9bdb52d04dc20036dbd8313ed055', '2025-10-08 16:22:47'),
(31, 'Robert Zemeckis', 'roberto@correo.com', '81dc9bdb52d04dc20036dbd8313ed055', '2025-10-08 17:57:35'),
(32, 'Marta', 'marta@correo.com', '014436b6640304b2cfad8a43f4aaad1a', '2025-10-09 18:09:52'),
(33, 'Marta', 'martin@correo.com', '014436b6640304b2cfad8a43f4aaad1a', '2025-10-10 06:41:08'),
(34, 'Roberto Benigni', 'buongiorno@correo.com', '014436b6640304b2cfad8a43f4aaad1a', '2025-10-12 21:14:15'),
(37, 'Roberto Benigni', 'b@correo.com', '014436b6640304b2cfad8a43f4aaad1a', '2025-10-13 00:01:52'),
(38, 'Agatha Christie', 'christie@gmail.com', '014436b6640304b2cfad8a43f4aaad1a', '2025-10-13 17:47:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`id_usuario`, `id_rol`) VALUES
(26, 1),
(26, 2),
(27, 1),
(28, 2),
(31, 2),
(32, 1),
(33, 1),
(34, 1),
(37, 2),
(38, 2);

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
  MODIFY `id_casilla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `personaje`
--
ALTER TABLE `personaje`
  MODIFY `id_personaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
