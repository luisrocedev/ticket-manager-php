-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8889
-- Tiempo de generación: 07-05-2025 a las 21:48:23
-- Versión del servidor: 5.7.44
-- Versión de PHP: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ticketscompra`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dni_cif` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellidos`, `dni_cif`, `direccion`, `telefono`, `email`, `created_at`) VALUES
(6, 'María', 'López García', '87654321B', 'C/ Ejemplo 45', '611223344', 'maria.lopez@example.com', '2025-05-02 12:21:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cif` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nombre`, `cif`, `direccion`, `telefono`, `email`, `logo`) VALUES
(4, 'Empresa Demo S.L.', 'B99999999', 'Calle Falsa 1234', '600112233', 'demo@empresa.com', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `numero_factura` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'emitida',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `numero_factura`, `ticket_id`, `fecha_emision`, `estado`, `created_at`) VALUES
(1, 'F-2025-85732', 9, '2025-05-02', 'emitida', '2025-05-02 13:14:12'),
(2, 'F-2025-04742', 12, '2025-05-02', 'emitida', '2025-05-02 17:04:02'),
(3, 'F-2025-97451', 12, '2025-05-02', 'emitida', '2025-05-02 17:24:23'),
(4, 'F-2025-82642', 19, '2025-05-03', 'emitida', '2025-05-03 12:04:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `precio` decimal(10,2) NOT NULL,
  `iva` decimal(5,2) NOT NULL DEFAULT '21.00',
  `stock` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `descripcion`, `precio`, `iva`, `stock`, `created_at`, `updated_at`) VALUES
(9, 'X001', 'Teclado Mecánico', 'Teclado gaming retroiluminado', 75.00, 21.00, 49, '2025-05-02 12:21:57', '2025-05-03 11:31:06'),
(10, 'X002', 'Monitor 24\"', 'Monitor Full HD 144Hz', 180.00, 21.00, 20, '2025-05-02 12:21:57', '2025-05-02 12:21:57'),
(11, 'x08', 'regular carne', 'carne de regular de ternera', 2.00, 10.00, 1979, '2025-05-03 11:58:03', '2025-05-03 12:03:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `numero_ticket` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva_total` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id`, `numero_ticket`, `fecha`, `empresa_id`, `cliente_id`, `subtotal`, `iva_total`, `total`, `metodo_pago`, `created_at`) VALUES
(9, '20250502-2914', '2025-05-02 13:13:51', 4, 6, 75.00, 15.75, 90.75, 'efectivo', '2025-05-02 13:13:51'),
(10, '20250502-9107', '2025-05-02 16:53:54', 4, 6, 75.00, 15.75, 90.75, 'efectivo', '2025-05-02 16:53:54'),
(11, '20250502-5775', '2025-05-02 17:03:23', 4, NULL, 75.00, 15.75, 90.75, 'efectivo', '2025-05-02 17:03:23'),
(12, '20250502-2366', '2025-05-02 17:03:49', 4, 6, 75.00, 15.75, 90.75, 'efectivo', '2025-05-02 17:03:49'),
(13, '20250502-4165', '2025-05-02 17:14:22', 4, NULL, 0.00, 0.00, 0.00, 'efectivo', '2025-05-02 17:14:22'),
(14, '20250503-5152', '2025-05-03 11:18:16', 4, NULL, 75.00, 15.75, 90.75, 'efectivo', '2025-05-03 11:18:16'),
(16, '20250503-5199', '2025-05-03 11:25:32', 4, NULL, 75.00, 15.75, 90.75, 'efectivo', '2025-05-03 11:25:32'),
(17, '20250503-2292', '2025-05-03 11:31:06', 4, NULL, 75.00, 15.75, 90.75, 'efectivo', '2025-05-03 11:31:06'),
(18, '20250503-4652', '2025-05-03 12:01:38', 4, NULL, 40.00, 4.00, 44.00, 'efectivo', '2025-05-03 12:01:38'),
(19, '20250503-4470', '2025-05-03 12:03:47', 4, 6, 2.00, 0.20, 2.20, 'tarjeta', '2025-05-03 12:03:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_items`
--

CREATE TABLE `ticket_items` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva_importe` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ticket_items`
--

INSERT INTO `ticket_items` (`id`, `ticket_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`, `iva_importe`, `total`) VALUES
(1, 9, 9, 1, 75.00, 75.00, 15.75, 90.75),
(2, 10, 9, 1, 75.00, 75.00, 15.75, 90.75),
(3, 11, 9, 1, 75.00, 75.00, 15.75, 90.75),
(4, 12, 9, 1, 75.00, 75.00, 15.75, 90.75),
(5, 14, 9, 1, 75.00, 75.00, 15.75, 90.75),
(6, 16, 9, 1, 75.00, 75.00, 15.75, 90.75),
(7, 17, 9, 1, 75.00, 75.00, 15.75, 90.75),
(8, 18, 11, 20, 2.00, 40.00, 4.00, 44.00),
(9, 19, 11, 1, 2.00, 2.00, 0.20, 2.20);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni_cif` (`dni_cif`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `idx_facturas_numero` (`numero_factura`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `idx_productos_codigo` (`codigo`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_ticket` (`numero_ticket`),
  ADD KEY `empresa_id` (`empresa_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `idx_tickets_fecha` (`fecha`),
  ADD KEY `idx_tickets_numero` (`numero_ticket`);

--
-- Indices de la tabla `ticket_items`
--
ALTER TABLE `ticket_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `ticket_items`
--
ALTER TABLE `ticket_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`);

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `ticket_items`
--
ALTER TABLE `ticket_items`
  ADD CONSTRAINT `ticket_items_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
  ADD CONSTRAINT `ticket_items_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
