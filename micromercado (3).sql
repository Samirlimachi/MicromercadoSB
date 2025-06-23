-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 20-06-2025 a las 03:21:17
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
-- Base de datos: `micromercado`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Panes'),
(2, 'Frutas'),
(3, 'Carnes'),
(4, 'Lacteos'),
(5, 'Despensa'),
(6, 'Bebidas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `id_venta`, `id_producto`, `cantidad`, `subtotal`) VALUES
(1, 1, 4, 1, 12.00),
(2, 2, 4, 1, 12.00),
(3, 2, 7, 1, 12.00),
(4, 3, 10, 1, 12.00),
(5, 4, 10, 7, 84.00),
(6, 5, 10, 2, 24.00),
(7, 6, 10, 1, 12.00),
(8, 7, 10, 10, 120.00),
(9, 8, 10, 1, 12.00),
(10, 9, 10, 11, 132.00),
(11, 10, 11, 1, 12.00),
(12, 11, 10, 2, 24.00),
(13, 12, 10, 1, 12.00),
(14, 13, 11, 1, 12.00),
(15, 13, 12, 1, 3.00),
(16, 13, 13, 1, 21.00),
(17, 14, 13, 1, 21.00),
(18, 15, 11, 2, 24.00),
(19, 16, 13, 2, 42.00),
(20, 17, 5, 1, 13.00),
(21, 18, 5, 1, 13.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `id_categoria` int(11) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `stock_minimo` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `cantidad`, `id_categoria`, `fecha_vencimiento`, `stock_minimo`) VALUES
(4, 'Carne Hambuerguesa', 'las mas ricas de Bolivia', 12.00, 'uploads/Captura de pantalla 2025-05-17 115024.png', 0, 1, '2025-06-22', 5),
(5, '5', '5', 13.00, 'uploads/GalletaPit.webp', 20, 5, '2025-06-21', 15),
(6, 'Pollo', 'dasdsadas', 15.00, 'uploads/Captura de pantalla 2025-06-15 191642.png', 20, 6, '2025-06-18', 3),
(7, 'yogurt', 'dasdasddasd', 12.00, 'uploads/Captura de pantalla (2).png', 11, 5, '2025-06-19', 3),
(8, 'a', 'a', 12.00, 'uploads/GalletaPit.webp', 12, 5, '2025-06-18', 1),
(9, 'b', 'b', 12.00, 'uploads/GalletaPit.webp', 12, 5, '2025-06-26', 2),
(10, '1', '1', 12.00, 'uploads/PERF.jpg', 10, 1, '2025-06-18', 2),
(11, '2', '2', 12.00, 'uploads/LogoPanaderia.png', 28, 2, '2025-06-18', 2),
(12, '3', '3', 3.00, 'uploads/PanFrances.jpeg', 13, 3, '2025-06-19', 1),
(13, '4', '4', 21.00, 'uploads/20190882-creativo-astratto-ponte-logo-disegno-vettore-design-modello-gratuito-vettoriale.png', 37, 4, '2025-06-19', 2),
(14, '6', '6', 6.00, 'uploads/mario.webp', 6, 6, '2025-06-21', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol` enum('admin','cliente') DEFAULT 'cliente',
  `puntos` int(11) DEFAULT 0,
  `token` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `rol`, `puntos`, `token`, `token_expira`) VALUES
(3, 'Admin Prueba', 'admin@gmail.com', '$2y$10$E3mCEXZ/tJWhXHloHu50feYF5B9M9Q.ciRbFcBne7AOTUCvg3Z83G', 'admin', 0, 'ec6768c4e1a6127134fa52ae16138c02', '2025-06-18 09:23:52'),
(4, 'Jose', 'romerobonny786@gmail.com', '$2y$10$eFz2EacrAdEqWOuErWg0Hujeq.i3UlMfcx9a9OnVpiCn9oRRtDeBC', 'cliente', 0, NULL, NULL),
(5, 'Samir', 'samirlimachi@gmail.com', '$2y$10$FXfTPuENmyg7ApQ7h2Rlm.Govxl3.kUu.TXEs/y/HYrieugvbHktS', 'cliente', 16, '8a968c7519a9d729b774d8b84e0feaf8', '2025-06-20 04:19:09'),
(6, 'NUEVO', 'nuevo@gmail.com', '$2y$10$qbMV6Z4XWNsApBTElovBEepdbyMpB55B8Psq8BbJYMTocBSWavoai', 'cliente', 0, 'a73d905aa041ac0d8c7ecfe40cafb8bf', '2025-06-18 09:11:42'),
(7, 'a', 'a@gmail', '$2y$10$o3F8XdxMM5gM3mafDDRynu8tHoFrrNe3LpKiPKjW4cVEQ28sJ.0Z6', 'cliente', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `id_usuario`, `fecha`, `total`) VALUES
(1, 5, '2025-06-16 10:57:29', 12.00),
(2, 5, '2025-06-16 13:03:00', 24.00),
(3, 5, '2025-06-18 00:00:09', 12.00),
(4, 5, '2025-06-18 00:03:01', 84.00),
(5, 5, '2025-06-18 00:03:18', 24.00),
(6, 5, '2025-06-18 00:03:47', 11.00),
(7, 5, '2025-06-18 00:08:25', 120.00),
(8, 5, '2025-06-18 00:08:39', 11.00),
(9, 5, '2025-06-18 00:21:17', 132.00),
(10, 5, '2025-06-18 00:22:01', 10.00),
(11, 5, '2025-06-18 00:24:04', 24.00),
(12, 5, '2025-06-18 00:32:27', 12.00),
(13, 5, '2025-06-18 00:34:42', 36.00),
(14, 5, '2025-06-18 00:35:25', 21.00),
(15, 5, '2025-06-18 01:06:37', 24.00),
(16, 5, '2025-06-18 01:09:46', 42.00),
(17, 7, '2025-06-18 09:37:42', 13.00),
(18, 7, '2025-06-18 13:42:01', 13.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
