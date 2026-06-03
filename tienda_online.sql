-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:522
-- Generation Time: Jun 03, 2026 at 02:44 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tienda_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`) VALUES
(2, 'Laptops', 'Portátiles de diferentes gamas'),
(3, 'celulares', 'Teléfonos móviles de última generación'),
(5, 'Frutas y verduras', 'vegetales y frutas'),
(6, 'Carnes y maricos', 'carnes frias y productos del mar'),
(7, 'Tecnología', 'Productos tecnológicos en general'),
(8, 'Celulares y Accesorios', 'Smartphones, cargadores, audífonos y más'),
(9, 'Computadores y Portátiles', 'PC, laptops y accesorios'),
(10, 'Audio y Video', 'Parlantes, audífonos, televisores'),
(11, 'Electrodomésticos', 'Productos para el hogar'),
(12, 'Hogar y Decoración', 'Muebles, decoración y utilidades'),
(13, 'Ropa y Moda', 'Prendas para todas las edades'),
(14, 'Calzado', 'Zapatos, tenis y sandalias'),
(15, 'Belleza y Cuidado Personal', 'Cosméticos y cuidado personal'),
(16, 'Deportes y Fitness', 'Artículos deportivos'),
(17, 'Videojuegos', 'Consolas, juegos y accesorios'),
(18, 'Juguetes', 'Juguetes para niños y coleccionables'),
(19, 'Libros y Papelería', 'Libros, cuadernos y útiles'),
(20, 'Mascotas', 'Accesorios y alimentos para mascotas'),
(21, 'Vehículos y Accesorios', 'Repuestos y accesorios'),
(22, 'Servicios', 'Servicios ofrecidos por vendedores'),
(23, 'Otros', 'Productos que no encajan en otra categoría'),
(24, 'Artesanias', 'prodducto elaborado a mano');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_carrito`
--

CREATE TABLE `detalle_carrito` (
  `id_detalle` int NOT NULL,
  `id_carrito` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle` int NOT NULL,
  `id_pedido` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` int DEFAULT '1',
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio`) VALUES
(19, 22, 2, 1, 1599.99),
(20, 23, 2, 1, 1599.99),
(21, 24, 1, 1, 999.99),
(22, 25, 5, 1, 999.99),
(23, 25, 3, 1, 2500.00),
(24, 25, 1, 1, 999.99),
(25, 26, 2, 1, 1599.99),
(26, 27, 1, 1, 999.99),
(27, 28, 2, 1, 1599.99),
(28, 29, 1, 1, 999.99),
(29, 30, 4, 1, 999.99),
(30, 31, 2, 1, 1599.99),
(31, 32, 1, 1, 999.99),
(32, 33, 5, 1, 999.99),
(33, 34, 5, 1, 999.99),
(34, 35, 2, 1, 1599.99),
(35, 36, 2, 1, 1599.99),
(36, 37, 2, 2, 1599.99),
(37, 38, 2, 1, 1599.99),
(38, 39, 1, 1, 999.99);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle` int NOT NULL,
  `id_venta` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_detalle`, `id_venta`, `id_producto`, `cantidad`) VALUES
(1, 4, 3, 1),
(2, 5, 4, 1),
(3, 6, 5, 1),
(4, 7, 4, 1),
(5, 8, 1, 1),
(6, 9, 4, 1),
(7, 10, 41, 1),
(8, 11, 37, 1),
(9, 12, 39, 1),
(10, 13, 41, 1),
(11, 14, 43, 1),
(12, 15, 43, 1),
(13, 16, 32, 1),
(14, 17, 38, 1);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id_detalle` int NOT NULL,
  `id_venta` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id_detalle`, `id_venta`, `id_producto`, `cantidad`, `precio`) VALUES
(1, 1, 1, 1, 999.99),
(2, 2, 1, 1, 999.99),
(3, 3, 1, 1, 999.99),
(4, 4, 3, 1, 2500.00),
(5, 5, 4, 1, 999.99),
(6, 6, 5, 1, 999.99),
(7, 7, 4, 1, 999.99),
(8, 8, 1, 1, 999.99),
(9, 9, 4, 1, 999.99),
(10, 10, 41, 1, 120.00),
(11, 11, 37, 1, 50.00),
(12, 12, 39, 1, 38.00),
(13, 13, 41, 1, 120.00),
(14, 14, 43, 1, 20.00),
(15, 15, 43, 1, 20.00),
(16, 16, 32, 1, 20.00),
(17, 17, 38, 1, 60.00);

-- --------------------------------------------------------

--
-- Table structure for table `imagenes_productos`
--

CREATE TABLE `imagenes_productos` (
  `id_imagen` int NOT NULL,
  `id_producto` int NOT NULL,
  `ruta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `imagenes_productos`
--

INSERT INTO `imagenes_productos` (`id_imagen`, `id_producto`, `ruta`) VALUES
(3, 31, '1776189428_a1d00540_extra_0.jpeg'),
(4, 31, '1776189428_c06a0bf2_extra_1.jpg'),
(5, 31, '1776189428_93a0635f_extra_2.jpeg'),
(6, 32, '1777049690_246a2b9d_extra_0.jpg'),
(7, 32, '1777049690_26ffee5b_extra_1.jpg'),
(8, 33, '1777049996_8813814e_extra_0.jpg'),
(9, 33, '1777049996_9cd5846a_extra_1.jpg'),
(10, 34, '1777050580_03d02627_extra_0.jpg'),
(11, 34, '1777050580_4ecbddde_extra_1.jpg'),
(12, 35, '1777052718_b128b569_extra_0.jpg'),
(13, 35, '1777052718_f81eaee0_extra_1.jpg'),
(14, 36, '1777053736_d3812c68_extra_0.jpg'),
(15, 36, '1777053736_5a551085_extra_1.jpg'),
(16, 37, '1777054017_ce017683_extra_0.jpg'),
(17, 37, '1777054017_89dda587_extra_1.jpg'),
(18, 38, '1777054325_3e8579d5_extra_0.jpg'),
(19, 38, '1777054325_1e547d12_extra_1.jpg'),
(20, 39, '1777054766_0edda4af_extra_0.jpg'),
(21, 39, '1777054766_3d887ab0_extra_1.jpg'),
(22, 40, '1777055898_56af58ed_extra_0.jpg'),
(23, 40, '1777055898_3369190a_extra_1.jpg'),
(24, 41, '1777056117_fb7c5155_extra_0.jpg'),
(25, 41, '1777056117_9fdf570e_extra_1.jpg'),
(26, 42, '1777056487_6f935b7c_extra_0.jpg'),
(29, 48, '1780432838_2aa5209a_extra_0.jpg'),
(30, 49, '1780433363_6b47d65d_extra_0.jpg'),
(31, 49, '1780433363_7ee78e4a_extra_1.jpg'),
(32, 50, '1780433581_947dff3c_extra_0.webp'),
(33, 50, '1780433581_df972830_extra_1.jpg'),
(34, 51, '1780434088_10cf0e21_extra_0.webp'),
(35, 51, '1780434088_e4db46dc_extra_1.webp'),
(36, 52, '1780435139_3460c016_extra_0.webp'),
(37, 52, '1780435139_cb766da3_extra_1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `total`, `direccion`, `fecha_pedido`) VALUES
(22, NULL, 1599.99, NULL, '2025-11-12 02:47:07'),
(23, NULL, 1599.99, NULL, '2025-11-19 21:39:59'),
(24, NULL, 999.99, NULL, '2025-11-20 01:35:21'),
(25, NULL, 4499.98, NULL, '2025-12-29 19:07:03'),
(26, NULL, 1599.99, NULL, '2026-03-25 18:07:36'),
(27, NULL, 999.99, NULL, '2026-03-25 18:09:40'),
(28, NULL, 1599.99, NULL, '2026-04-14 17:02:05'),
(29, NULL, 999.99, NULL, '2026-04-18 03:03:18'),
(30, NULL, 999.99, NULL, '2026-04-18 03:15:08'),
(31, NULL, 1599.99, NULL, '2026-04-24 20:44:28'),
(32, NULL, 999.99, NULL, '2026-04-24 20:54:18'),
(33, NULL, 999.99, NULL, '2026-04-24 21:01:05'),
(34, NULL, 999.99, NULL, '2026-04-24 21:01:21'),
(35, NULL, 1599.99, NULL, '2026-04-24 21:02:09'),
(36, NULL, 1599.99, NULL, '2026-04-24 21:07:05'),
(37, NULL, 3199.98, NULL, '2026-04-24 21:13:13'),
(38, NULL, 1599.99, NULL, '2026-04-24 21:18:02'),
(39, NULL, 999.99, NULL, '2026-04-24 21:18:57');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id_producto` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_vendedor` int DEFAULT NULL COMMENT 'FK hacia usuarios.id_usuario)',
  `id_categoria` int DEFAULT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `imagen`, `id_vendedor`, `id_categoria`, `fecha_publicacion`) VALUES
(1, 'iPhone 15 Pro', 'Chip A17 Pro, cámara de 48MP, titanio', 999.99, 'iphone15.jpg', NULL, NULL, '2025-12-26 12:04:08'),
(2, 'MacBook Pro M3', 'Chip M3, pantalla Retina, 16GB RAM, SSD 512GB', 1599.99, 'macbook.jpg', NULL, NULL, '2025-12-26 12:04:08'),
(3, 'iMac', 'CPU 8 núcleos, GPU 8 núcleos, 120 GB/s de ancho de banda', 2500.00, 'imac.jpg', NULL, NULL, '2025-12-26 12:04:08'),
(4, 'iPad', 'Pantalla Retina, chip A17, gran rendimiento', 999.99, 'ipad.jpg', NULL, NULL, '2025-12-26 12:04:08'),
(5, 'iMac mini', 'Compacto, potente, chip M3, diseño elegante', 999.99, 'imacmini.jpg', NULL, NULL, '2025-12-26 12:04:08'),
(10, 'canastas de frutas', 'estoy ofreciendo esta deliciosa canasta fresca recién cosechada el día de hoy por mi mano se encuentra en buen estado y muy fresca 😊', 70.00, '1768675088_fc5da6a89baf.jpg', 17, 5, '2026-01-17 13:38:08'),
(11, 'Fresas', 'estoy vendiendo estas canastas de fresas recien cosechadas en mi tierra, se encuentrasn recien recolectadas por mi mano💕', 10.00, '1768675911_a3cc0e83388a.jpg', 17, 5, '2026-01-17 13:51:51'),
(12, 'Banano', 'estoy vendiendo estos bananos recien recoguidos el dia de hoy en mi finca se encuentran frescos y', 25.00, '1768676012_b846b65c86ba.jpg', 17, 5, '2026-01-17 13:53:32'),
(13, 'Naranjas', 'En el dia de ayer en la recolecion de naranjas en mi finca me sobraro estas naranjas y me encuentro dispuesto a negocialas se encuentran frescas 😁👍', 30.00, '1768676273_e769a3731102.jpg', 17, 5, '2026-01-17 13:57:53'),
(19, 'audifonos', 'estos audifonos estan en perfesto estado los en cuentro vendiendo porque no me gusto el tamano de ellos', 100000.00, '1768679443_14d9d2bc5af6.jpg', 19, 7, '2026-01-17 14:50:43'),
(31, 'zapatos', 'lo vendo porque ya no me gusta', 20.00, '1776189428_65a9aea6.png', 17, 14, '2026-04-14 12:57:08'),
(32, 'almejas', 'las recogi esta mana estasn frescas y esta a un buen precio 💕👌', 20.00, '1777049690_52d27549.jpg', 18, 6, '2026-04-24 11:54:50'),
(33, 'robalo', 'fueron atraspados esta mana y todavia se encuentrasn fresco estan a un buen precio', 60.00, '1777049996_601b868f.jpg', 18, 6, '2026-04-24 11:59:56'),
(34, 'Camarones', 'estan recien atrapados y frescos', 80.00, '1777050580_99b6f71c.jpg', 18, 6, '2026-04-24 12:09:40'),
(35, 'langosta', 'recien atrapa', 120.00, '1777052718_45df8a75.jpg', 18, 6, '2026-04-24 12:45:18'),
(36, 'Ramos de flores cencillos', 'puedes escoger entre diferentes tipos de flores tu preferencias tenemos margaritas rosas y ortencias pero el precio siempre sera el mismo ❤️', 30.00, '1777053736_ef613158.JPG', 21, 24, '2026-04-24 13:02:16'),
(37, 'Ramo de girasoles', 'sorprende a esa persona especial con uno de estos maravilloso ramo de flores', 50.00, '1777054017_5a90a97e.jpg', 21, 24, '2026-04-24 13:06:57'),
(38, 'Ramo de rosas ❤️', 'no necesitas una fecha especial para regalar una ramo de estas hermosas roas', 60.00, '1777054325_9ef2039c.jpg', 21, 24, '2026-04-24 13:12:05'),
(39, 'Ramo de Margaaritas', 'Creo que estas son de mis flores fovoritas y el dia de hoy tengo las mas hermoas de todas, no piense en com si vale su precio porque sabes que si lo valen', 38.00, '1777054766_75cc9c80.jpg', 21, 24, '2026-04-24 13:19:26'),
(40, 'Jarron', 'este jarron fue forjado a mano y incluye estas dos adornos estra', 80.00, '1777055898_c90dee6c.jpg', 22, 24, '2026-04-24 13:38:18'),
(41, 'Jarron decorativo', 'este par de jarrones se ven por separado per el son la pareja perecta para decorar cualquier espacio', 120.00, '1777056117_3d329527.jpg', 22, 24, '2026-04-24 13:41:57'),
(42, 'Juego de losa', 'vivo enamorada de este juego de platos me encantan estan preciosos y van bien con cualquier ocasion 💕❤️😍😍😍😍', 80.00, '1777056487_045097b1.jpg', 22, 24, '2026-04-24 13:48:07'),
(43, 'trepadora', 'estas plata es muy linda para interiores te la recomiendo para cualquier espacio de tu casa', 20.00, '1777329426_bf7231b8.jpg', 23, 12, '2026-04-27 17:37:06'),
(44, 'cactus', 'es muy lindo y resistente te lo recomiendo si eres una persona muy descuidada y su cuidado es muy facil', 10.00, '1777329505_1a39f1c4.jpg', 23, 12, '2026-04-27 17:38:25'),
(45, 'trepadora', 'esta es de mis plantas favoritas me parece preciosa y va con cualquier lugar y hogar', 20.00, '1777329568_e828fe61.jpg', 23, 12, '2026-04-27 17:39:28'),
(48, 'Silla de madera', 'xxxxxxx', 120.00, '1780432838_7ee0293e.jpg', 28, 12, '2026-06-02 15:40:38'),
(49, 'Comedor', 'MNMNMNMNMNMNMNMMNMNMNMMNMNMN', 1000000.00, '1780433363_12dadfea.jpg', 28, 12, '2026-06-02 15:49:23'),
(50, 'Cama de madera', 'cvcvcvcvcvcvcvvcvcvcvcvcvcvcvcvcvvcc', 2000000.00, '1780433581_2d881893.webp', 28, 12, '2026-06-02 15:53:01'),
(51, 'Armario', 'ytytytytyytytytytytyytytytyty', 1000000.00, '1780434088_d4e13a8d.jpg', 28, 12, '2026-06-02 16:01:28'),
(52, 'muebles de madera', 'dfdfdfdfdfdfdfdfdfdfd', 6000000.00, '1780435139_fea0af38.jpg', 28, 12, '2026-06-02 16:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `resenas`
--

CREATE TABLE `resenas` (
  `id_resena` int NOT NULL,
  `id_producto` int NOT NULL,
  `id_usuario` int NOT NULL,
  `calificacion` int NOT NULL,
  `comentario` text,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `resenas`
--

INSERT INTO `resenas` (`id_resena`, `id_producto`, `id_usuario`, `calificacion`, `comentario`, `fecha`) VALUES
(1, 12, 19, 4, 'muy buenos platanos todavia tengo en cocina recomendado', '2026-01-21 23:19:40'),
(3, 10, 18, 5, 'las prove y me encantaron super recomendadas las frutas estaban muy frescas \n', '2026-01-22 02:13:31'),
(6, 10, 17, 4, 'las frutas estaban muy fresacas me encantaron ', '2026-03-25 18:08:43'),
(7, 41, 21, 1, 'de muy mal gusto ', '2026-05-15 00:23:06');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `foto_perfil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_portada` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ubicacion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `whatsapp` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rol` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'usuario',
  `verificado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `contrasena`, `fecha_registro`, `foto_perfil`, `foto_portada`, `ubicacion`, `whatsapp`, `rol`, `verificado`) VALUES
(17, 'Frank Martinez', 'fm3949461@gmail.com', '$2y$10$/u/OBKpFKwa7hY0Y7DRhmOllfYWD0ydMIhx5F8idh41w3hidJnpmi', '2025-11-12 00:35:50', '1775673834_17.jpg', '1775678659_17.jpg', 'apartado antioquia ', '573101234567', 'admin', 0),
(18, 'DANIELA.SA', 'frnacomar14@gmail.com', '$2y$10$VmgKIJIjheo5Ot0ZkG9eJutjzvT53MeobacfNbPhp8o01LexJWqN.', '2025-12-29 19:21:07', '1775679040_18.jpg', '1775679078_18.jpg', 'punta de piedra', '3226065720', 'usuario', 1),
(19, 'davida', 'ronaldjavierqm@gmail.com', '$2y$10$0CSzVAGbLvYbo9PH22cFTuh1CySsWhq0hJFy2nUJK8D5YkxQU/XM6', '2026-01-17 19:42:56', '1777147600_19.jpg', NULL, 'brasil', '', 'usuario', 0),
(21, 'Sofia Flores 💕', 'sofiaflores@gmail.com', '$2y$10$iHo5iMzHhnGp5Qvf5Yh.ye46sUT/qhLSl9p8OEGzvz964/jZQ7rMq', '2026-04-24 17:51:25', '1777053333_21.jpg', '1778802159_21.jpg', 'MEDELLIN', '3226065720', 'usuario', 1),
(22, 'Gloria Torres', 'gloriatorres@gmail.com', '$2y$10$as9IZdDnu85swYrgFYtOO.wahCyrv6phHvT2Vk6d.u7lTvzfFr6pi', '2026-04-24 18:25:12', '1777055678_22.JPG', '1777055512_22.jpg', 'CAZANARE', '3226065720', 'usuario', 0),
(23, 'Maria la de las plantas🌿', 'maria10@gmail.com', '$2y$10$0/UoIObVnSVDFwJ95WZ6oOSIIEpZO7To3ZndlY56iLWMb/becUUfO', '2026-04-27 22:24:05', '1777329348_23.jpg', '1777329338_23.jpg', 'monteria', '3107404775', 'usuario', 1),
(28, 'pablo', 'pablo14@gmailcom', '$2y$10$d6SbqPUm0KysWHwj6VY/uuX4M1IabuHA6xUodcn9WwUzdiV.9Zbhi', '2026-06-02 20:24:12', '1780432288_28.png', '1780432058_28.jpg', NULL, NULL, 'usuario', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int NOT NULL,
  `id_usuario` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(20) DEFAULT 'pendiente',
  `comprobante` varchar(255) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_usuario`, `total`, `estado`, `comprobante`, `fecha`) VALUES
(1, 18, 999.99, 'rechazado', '1777067013_Post de Instagram Buscamos cocinero minimalista blanco.png', '2026-04-24 21:43:01'),
(2, 18, 999.99, 'entregado', '1777067334_Post de Instagram Buscamos cocinero minimalista blanco.png', '2026-04-24 21:48:38'),
(3, 18, 999.99, 'entregado', '1777073678_210065692_350297563157075_9056465894378402845_n.jpg', '2026-04-24 23:34:11'),
(4, 18, 2500.00, 'entregado', '1777075755_Post de Instagram Buscamos cocinero minimalista blanco.png', '2026-04-25 00:09:01'),
(5, 18, 999.99, 'rechazado', '1777077790_210065692_350297563157075_9056465894378402845_n-Photoroom.png', '2026-04-25 00:37:21'),
(6, 18, 999.99, 'rechazado', '1777077849_Estilos de decoração para casamento cerimonialista colagem bege pin do pinterest .png', '2026-04-25 00:44:01'),
(7, 21, 999.99, 'verificado', '1777078209_Post de Instagram Buscamos cocinero minimalista blanco.png', '2026-04-25 00:49:41'),
(8, 21, 999.99, 'verificado', '1777080235_Estilos de decoração para casamento cerimonialista colagem bege pin do pinterest .png', '2026-04-25 01:23:29'),
(9, 21, 999.99, 'cancelado', NULL, '2026-04-25 01:32:10'),
(10, 21, 120.00, 'Completado', '1777145184_qrcode.png', '2026-04-25 19:26:03'),
(11, 22, 50.00, 'Completado', '1777145962_WhatsApp Image 2025-12-16 at 8.30.10 PM (4).jpeg', '2026-04-25 19:39:03'),
(12, 22, 38.00, 'Completado', '1777146075_Estilos de decoração para casamento cerimonialista colagem bege pin do pinterest .png', '2026-04-25 19:41:05'),
(13, 22, 120.00, 'rechazado', '1777148440_210065692_350297563157075_9056465894378402845_n.jpg', '2026-04-25 20:20:31'),
(14, 21, 20.00, 'Completado', '1777330408_maseta.jpg', '2026-04-27 22:53:12'),
(15, 21, 20.00, 'Completado', '1777333761_Post de Instagram Buscamos cocinero minimalista blanco.png', '2026-04-27 23:49:01'),
(16, 21, 20.00, 'verificado', '1778804799_descarga (1).jpg', '2026-05-15 00:25:47'),
(17, 22, 60.00, 'Completado', '1779744661_descarga (1).jpg', '2026-05-25 21:30:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_carrito` (`id_carrito`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id_detalle`);

--
-- Indexes for table `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_vendedor` (`id_vendedor`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indexes for table `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id_resena`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  MODIFY `id_imagen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id_resena` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD CONSTRAINT `detalle_carrito_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON UPDATE CASCADE;

--
-- Constraints for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON UPDATE CASCADE;

--
-- Constraints for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Constraints for table `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD CONSTRAINT `imagenes_productos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Constraints for table `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE,
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
