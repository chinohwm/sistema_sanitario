-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2023 a las 15:41:05
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
-- Base de datos: `secretaria_salud`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id_cargo` int(11) NOT NULL,
  `cargo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id_cargo`, `cargo`) VALUES
(1, 'Administrador'),
(2, 'Promotor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `glucemia`
--

CREATE TABLE `glucemia` (
  `id_paciente` int(11) NOT NULL,
  `sede` varchar(50) NOT NULL,
  `localidad` varchar(50) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `derivacion` varchar(30) NOT NULL,
  `observacion` text NOT NULL,
  `fecha` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mamografia`
--

CREATE TABLE `mamografia` (
  `id_paciente` int(11) NOT NULL,
  `observacion` text NOT NULL,
  `turno` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` varchar(10) NOT NULL,
  `celular` int(30) NOT NULL,
  `genero` varchar(30) NOT NULL,
  `fecha_nacimiento` text NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `localidad` varchar(50) NOT NULL,
  `domicilio` varchar(50) NOT NULL,
  `obra_social` varchar(10) NOT NULL,
  `peso` varchar(5) NOT NULL,
  `talla` varchar(3) NOT NULL,
  `promotor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `nombre`, `apellido`, `dni`, `celular`, `genero`, `fecha_nacimiento`, `correo_electronico`, `localidad`, `domicilio`, `obra_social`, `peso`, `talla`, `promotor`) VALUES
(35, 'Norma', 'Pereira', '12345066', 1157225992, 'Femenino', '1954-07-01', '@', 'San justo', 'Montanesas 3963', 'Si', '0', '0', 'Rocio'),
(36, 'Silvia', 'Narracci', '10395407', 1169378964, 'Femenino', '1952-09-24', '@', 'Ramos mejia', 'Bolivar 2470', 'No', '0', '0', 'Rocio'),
(37, 'Jacinto ', 'Catriel', '8215436', 0, 'Masculino', '1951-08-24', '@', 'Ramos Mejia', 'Nicaragua 222', 'No', '0', '0', 'Rocio'),
(38, 'Juan', 'Sosa', '7785312', 1151757346, 'Masculino', '1947-03-22', '@', 'Isidro casanova', 'Los andes 3691', 'Si', '0', '0', 'Rocio'),
(39, 'Aldo', 'Tevez', '13282772', 11622626, 'Masculino', '1956-08-30', '@', 'Isidro Casanova', 'Albarellos 5256', 'Si', '0', '0', 'Rocio'),
(40, 'Aminda ', 'Jallasi ', '94209834', 1123103005, 'Femenino', '1975-02-08', '@', 'R.castillo', 'Eduardo Bunje 48', 'No', '0', '0', 'Rocio'),
(41, 'Silvia ', 'Huallpacuiza', '95814503', 1123010892, 'Femenino', '1992-05-19', '@', 'V.fiorito', 'Canada 1476', 'No', '0', '0', 'Rocio'),
(42, 'Lidia ', 'Gonzalez ', '16783517', 1167081470, 'Femenino', '1962-03-11', '@', 'San justo ', 'Guatemala 4656', 'Si', '0', '0', 'Rocio'),
(43, 'Veronica ', 'Turca ', '45275536', 1162606370, 'Femenino', '1955-07-03', '@', 'L. de zamora', 'J.M.De rosas 11 de julio ', 'No', '0', '0', 'Rocio'),
(44, 'Juana', 'Colque', '44283988', 0, 'Femenino', '1986-03-03', '@', 'R. Castillo', 'Mza 10 casa 20 sn ', 'No', '0', '0', 'Rocio'),
(45, 'Francisca ', 'Pongo', '94470256', 1134777256, 'Femenino', '1974-12-03', '@', 'B.Union', 'G.merou sn ', 'No', '0', '0', 'Rocio'),
(46, 'Paulina ', 'Mamani ', '95763901', 1136665534, 'Femenino', '1984-03-02', '@', 'B.Union ', 'Anamariag. 3678', 'No', '0', '0', 'Rocio'),
(47, 'Dionisia ', 'Martinez', '94283426', 1168693571, 'Femenino', '1962-12-08', '@', 'C. evita ', 'El quebracho 900', 'No', '0', '0', 'Rocio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presion`
--

CREATE TABLE `presion` (
  `id_paciente` int(11) NOT NULL,
  `sede` varchar(50) NOT NULL,
  `localidad` varchar(50) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `derivacion` varchar(30) NOT NULL,
  `observacion` text NOT NULL,
  `fecha` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `presion`
--

INSERT INTO `presion` (`id_paciente`, `sede`, `localidad`, `estado`, `derivacion`, `observacion`, `fecha`) VALUES
(0, '6', 'Laferrere', '170-180', 'No', 'Controlado', '2023-10-11'),
(0, '9', 'Laferrere', '170-180', 'No', 'Controlado', '2023-10-12'),
(3, '8', '', '170-180', 'No', 'Controlado', '2023-10-11'),
(3, '3', 'Laferrere', '80', 'No', 'Control', '2001-01-01'),
(3, '10', 'Lafe', '90', 'Si', 'uashgbu', '2023-10-12'),
(3, '11', 'Gonzalez Catan', '180-190', 'No', 'Controlado', '2023-10-02'),
(35, 'Plaza San Justo', 'San Justo', '14/6', 'No', 'Hta', '2023-10-11'),
(36, 'Plaza San Justo ', 'San justo', '13/8', 'No', '-', '2023-10-11'),
(37, 'Plaza San justo', 'San justo', '13/6', 'No', 'Hta', '2023-10-11'),
(38, 'Plaza San justo', 'San justo', '14/7', 'No', '-', '2023-10-11'),
(39, 'Plaza San justo', 'San justo', '14/8', 'No', '-\r\n', '2023-10-11'),
(40, 'Plaza San Justo', 'San justo', '13/9', 'No', '-', '2023-10-11'),
(41, 'Plaza San justo', 'San justo ', '12/8', 'No', '-', '2023-10-11'),
(42, 'Plaza San justo ', 'San justo ', '12/8', 'No', '-', '2023-02-11'),
(43, 'Plaza San justo', 'San justo', '11/6', 'No', '-', '2023-02-11'),
(44, 'Plaza San justo ', 'San justo ', '11/6', 'No', '-', '2023-10-11'),
(45, 'Plaza San justo', 'San justo', '11/8', 'No', '-', '2023-10-11'),
(46, 'Plaza San justo', 'San justo', '12/7', 'No', '-', '2023-10-11'),
(47, 'Plaza San Justo', 'San justo ', '13/8', 'No', '-', '2023-10-11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sangre_oculta`
--

CREATE TABLE `sangre_oculta` (
  `id_paciente` int(11) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `fecha` text NOT NULL,
  `observacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sifilis`
--

CREATE TABLE `sifilis` (
  `id_paciente` int(11) NOT NULL,
  `sifilis` varchar(30) NOT NULL,
  `observacion` text NOT NULL,
  `derivacion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `id_cargo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellido`, `usuario`, `password`, `id_cargo`) VALUES
(1, 'Secretaria ', 'Salud', 'secretaria', 'salud', 1),
(3, 'Geronimo', 'Ricardes', 'gero', '1234', 2),
(5, 'Lorenzo', 'Ferreyra', 'lolo', 'ferrey', 1),
(7, 'Gonzalo', 'Romano', 'Gonza', 'Romano', 1),
(8, 'Ayrton', 'Practicas', 'ayrton', '9090', 1),
(10, 'Rocio', 'Montaño', 'rocio', 'montaño', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vih`
--

CREATE TABLE `vih` (
  `id_paciente` int(11) NOT NULL,
  `vih` varchar(30) NOT NULL,
  `observacion` text NOT NULL,
  `derivacion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vph`
--

CREATE TABLE `vph` (
  `id_paciente` int(11) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `fecha` text NOT NULL,
  `observacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `mamografia`
--
ALTER TABLE `mamografia`
  ADD PRIMARY KEY (`id_paciente`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `sangre_oculta`
--
ALTER TABLE `sangre_oculta`
  ADD PRIMARY KEY (`id_paciente`);

--
-- Indices de la tabla `sifilis`
--
ALTER TABLE `sifilis`
  ADD PRIMARY KEY (`id_paciente`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- Indices de la tabla `vih`
--
ALTER TABLE `vih`
  ADD PRIMARY KEY (`id_paciente`);

--
-- Indices de la tabla `vph`
--
ALTER TABLE `vph`
  ADD PRIMARY KEY (`id_paciente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mamografia`
--
ALTER TABLE `mamografia`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `sangre_oculta`
--
ALTER TABLE `sangre_oculta`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `sifilis`
--
ALTER TABLE `sifilis`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `vih`
--
ALTER TABLE `vih`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mamografia`
--
ALTER TABLE `mamografia`
  ADD CONSTRAINT `fk_mamografia_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE,
  ADD CONSTRAINT `mamografia_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);

--
-- Filtros para la tabla `sangre_oculta`
--
ALTER TABLE `sangre_oculta`
  ADD CONSTRAINT `sangre_oculta_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sifilis`
--
ALTER TABLE `sifilis`
  ADD CONSTRAINT `fk_sifilis_pacientes` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE,
  ADD CONSTRAINT `sifilis_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`);

--
-- Filtros para la tabla `vih`
--
ALTER TABLE `vih`
  ADD CONSTRAINT `vih_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);

--
-- Filtros para la tabla `vph`
--
ALTER TABLE `vph`
  ADD CONSTRAINT `fk_vph_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE,
  ADD CONSTRAINT `vph_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
