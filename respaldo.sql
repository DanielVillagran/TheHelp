-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8890
-- Generation Time: Mar 30, 2020 at 05:40 PM
-- Server version: 5.7.25
-- PHP Version: 7.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `casamar5_zumpango`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(100) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `data` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('0bc4dd50fafcc6677a502eb5ab00b36e27eef9e9', '::1', 1585611634, '__ci_last_regenerate|i:1585611475;user_id|s:2:\"45\";username|s:27:\"brayam.morando@zumpnago.com\";client|s:1:\"1\";status|s:1:\"1\";'),
('68521aca9880c7dd36469abf2c4fc53fa2840e59', '::1', 1585607571, '__ci_last_regenerate|i:1585607571;user_id|s:2:\"45\";username|s:27:\"brayam.morando@zumpnago.com\";client|s:1:\"1\";status|s:1:\"1\";'),
('984bfb5aba177aef70407ff81cda1871994d8fe6', '::1', 1585608555, '__ci_last_regenerate|i:1585608555;user_id|s:2:\"45\";username|s:27:\"brayam.morando@zumpnago.com\";client|s:1:\"1\";status|s:1:\"1\";'),
('9bb4cca174d4c698f15e846ec42d65bf8d34c23f', '::1', 1585607251, '__ci_last_regenerate|i:1585607251;user_id|s:2:\"45\";username|s:27:\"brayam.morando@zumpnago.com\";client|s:1:\"1\";status|s:1:\"1\";'),
('e5c7af07dc070527283c1120156eba8e2f126caa', '::1', 1585611475, '__ci_last_regenerate|i:1585611475;user_id|s:2:\"45\";username|s:27:\"brayam.morando@zumpnago.com\";client|s:1:\"1\";status|s:1:\"1\";'),
('ea93216a950fbe0f377006455fdb9943817b3b6e', '::1', 1585607929, '__ci_last_regenerate|i:1585607929;user_id|s:2:\"45\";username|s:27:\"brayam.morando@zumpnago.com\";client|s:1:\"1\";status|s:1:\"1\";');

-- --------------------------------------------------------

--
-- Table structure for table `colonias`
--

CREATE TABLE `colonias` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `colonias`
--

INSERT INTO `colonias` (`id`, `nombre`) VALUES
(1, 'colonia de Prueba'),
(2, 'Nueva colonia');

-- --------------------------------------------------------

--
-- Table structure for table `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `telefono` text NOT NULL,
  `contacto` text NOT NULL,
  `logo` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departamentos`
--

INSERT INTO `departamentos` (`id`, `nombre`, `telefono`, `contacto`, `logo`) VALUES
(1, 'Prueba', 'Prueba', 'Prueba', NULL),
(2, 'Otra prueba', 'kuhhg', 'kgkghk', NULL),
(3, 'Pagos Probando esta made', '3317201258', 'Gerente de Pagos', NULL),
(4, 'Esta es prueba 2 de lo nuevo', 'khkjhkjh', 'kjhkjh', 'files/fotos/logo_0000000004.esprezza.png'),
(5, 'kjhkhkjhkjhkj', 'hkjhkjhkjh', 'kjhkjhkjhkjh', 'files/fotos/logo_0000000000.0x0ss-P33.jpg'),
(6, 'kjhkhkjhkjhkj', 'hkjhkjhkjh', 'kjhkjhkjhkjh', 'files/fotos/logo_0000000000.0x0ss-P33.jpg'),
(7, 'jhgjhgjhg', 'jhgjhgjhghj', 'gjhgjg', 'files/fotos/logo_0000000000.3max.jpg'),
(8, 'ghjgjgjhg', 'jhgjhghjg', 'jhgjhg', 'files/fotos/logo_0000000000.0x0ss-P34.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `departamentos_servicios`
--

CREATE TABLE `departamentos_servicios` (
  `id` int(11) NOT NULL,
  `departamento_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `pagos` tinyint(1) NOT NULL,
  `citas` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departamentos_servicios`
--

INSERT INTO `departamentos_servicios` (`id`, `departamento_id`, `nombre`, `descripcion`, `pagos`, `citas`) VALUES
(4, 4, 'PRobando', 'Probando', 1, 1),
(8, 4, 'mnbmnbnbmbmn', 'bmnbmb', 1, 1),
(9, 0, '', '', 0, 0),
(10, 4, 'Prueba', 'Prueba', 0, 1),
(11, 8, 'Prueba', 'PRueba', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `titulo` text NOT NULL,
  `descripcion` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `titulo`, `descripcion`, `created`) VALUES
(1, 'PRuebas', 'Hello', '2020-03-29 21:07:27');

-- --------------------------------------------------------

--
-- Table structure for table `users_user`
--

CREATE TABLE `users_user` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_passwd` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `is_client` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_user`
--

INSERT INTO `users_user` (`id`, `user_name`, `user_passwd`, `name`, `middle_name`, `last_name`, `image`, `status`, `is_client`) VALUES
(1, 'oswaldo.villagrana@esprezza.com', '$2y$10$15M29YFy/wJZBC4i3yjsK.LZguOOpBGknqM5XOD3M/dUi0rCnayk2', 'Esprezza', '', '', NULL, 1, NULL),
(45, 'brayam.morando@zumpnago.com', '$2y$10$5TorusrZFsYEkX7eYkV9t.9yMc3/fiJPSaggSqjdfPdFIIlQN8j6a', 'Brayam', 'Morando', 'Perez', NULL, 1, b'1'),
(46, 'carlos.garcia@zumpango.com', '$2y$10$M6.QwYux/cdZnWVQIz5an.Z3KYb5ROg2GBUbjfn8slK5QCiv/2Qz6', 'Carlos', 'Garcia', '', NULL, 1, b'1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colonias`
--
ALTER TABLE `colonias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departamentos_servicios`
--
ALTER TABLE `departamentos_servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_user`
--
ALTER TABLE `users_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `colonias`
--
ALTER TABLE `colonias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departamentos_servicios`
--
ALTER TABLE `departamentos_servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_user`
--
ALTER TABLE `users_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
