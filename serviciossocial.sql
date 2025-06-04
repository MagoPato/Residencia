-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: pdb1039.awardspace.net
-- Tiempo de generación: 04-06-2025 a las 16:14:40
-- Versión del servidor: 8.0.32
-- Versión de PHP: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `3911954_serviciossocialtecnl`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `RFC` varchar(8) COLLATE utf8mb4_general_ci NOT NULL,
  `Nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Apellidos` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `No_Control` varchar(8) COLLATE utf8mb4_general_ci NOT NULL,
  `Id_Direccion` int DEFAULT NULL,
  `Nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Apellido_P` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Apellido_M` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Telefono` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Semestre` tinyint NOT NULL,
  `Modalidad` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `Correo` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `Turno` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `Estatus` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `Genero` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `Carrera` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Créditos` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`No_Control`, `Id_Direccion`, `Nombre`, `Apellido_P`, `Apellido_M`, `Telefono`, `Semestre`, `Modalidad`, `Correo`, `Turno`, `Estatus`, `Genero`, `Carrera`, `Créditos`) VALUES
('20190266', 2, 'Cristian Fernando', 'González', 'Juan', '9721154100', 9, 'P', 'lb20190266@nuevoleon.tecnm.mx', 'M', 'Candidato', 'M', 'Ingeniería en Sistemas Computacionales', 150),
('20481033', 3, 'Jose Luis', 'Covarrubias', 'Hernandez', '8110436647', 9, 'P', 'Josecovaherna@gmail.com', 'M', 'Baja', 'M', 'Ingeniería en Sistemas Computacionales', NULL),
('20481035', 5, 'David Patricio', 'Escmilla', 'Marquez', '8123292430', 1, 'P', 'pato@gmail.com', 'M', 'Candidato', 'F', 'Ingeniería Electromecánica', 160),
('20481045', 17, 'David Patricio', 'Escmilla', 'Marquez', '8123292430', 12, 'P', 'l20481045@nuevoleon.tecnm.mx', 'M', 'Candidato', 'F', 'Ingeniería Industrial', 300),
('22480600', 4, 'Arely', 'Centeno', 'Roblez', '9913245679', 7, 'P', 'l22480600@nuevoleon.tecnm.mx', 'M', 'Candidato', 'F', 'Ingeniería en Gestión Empresarial', NULL),
('24480616', 1, 'Citlaly Anayenci', 'Montemayor', 'Garcia', '8112183734', 1, 'P', 'citlalymontemayor3@gmail.com', 'M', 'Candidato', 'F', 'Ingeniería en Gestión Empresarial', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `Id` int NOT NULL,
  `Usuario` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(65) COLLATE utf8mb4_general_ci NOT NULL,
  `Estatus` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `Tipo_Usuario` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `Id_Alumno` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `cuenta`
--

INSERT INTO `cuenta` (`Id`, `Usuario`, `Password`, `Estatus`, `Tipo_Usuario`, `Id_Alumno`) VALUES
(1, '24480616', '$2y$10$1/BKLYdoSLxAy7pQaNjxXex/w/wcOhY63ArkrlhACrkF5H8CvW69i', 'A', 'U', '24480616'),
(2, '20190266', '$2y$10$7nu9Istcso35GEYtLraUTOI2pRvOcXBBHx.OQbVk8yDQLLe83dkie', 'A', 'A', '20190266'),
(3, '20481033', '$2y$10$9g5ciH/xcElupt1..e0yuu5EM988GC/L5DZME.PsJyktwu8IAuxoq', 'A', 'U', '20481033'),
(4, '22480600', '$2y$10$eASzOfDQ5YEUQINQhjBYm.LQ1LUlggatttXuOd55RoPY/Fsb8DPGe', 'A', 'U', '22480600'),
(5, '20481035', '$2y$12$UEPR06RfI/FC1o0PFJ5ZU.b0.FI4ZS1zdGN2fyQcFqHLyg8GgiA8G', 'A', 'U', '20481035');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cupos`
--

CREATE TABLE `cupos` (
  `Id` int NOT NULL,
  `Id_Servicio` int DEFAULT NULL,
  `Cupo` tinyint NOT NULL,
  `Carrera` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `Horario` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cupos`
--

INSERT INTO `cupos` (`Id`, `Id_Servicio`, `Cupo`, `Carrera`, `Horario`) VALUES
(1, 1, 0, 'Todas', '9:00 - 13:00'),
(2, 1, 1, 'Todas', '12:00 - 16:00'),
(3, 1, 1, 'Todas', '13:00 - 17:00'),
(4, 1, 2, 'Todas', '15:00 - 19:00'),
(5, 2, 0, 'IA', '11:00 - 15:00'),
(6, 3, 0, 'Todas', '10:00 - 14:00'),
(9, 6, 1, 'IA', '10:00 - 14:00'),
(10, 2, 3, 'IEM', '11:00 - 15:00'),
(11, 2, 3, 'IE', '11:00 - 15:00'),
(12, 6, 3, 'IEM', '10:00 - 14:00'),
(13, 6, 3, 'IE', '10:00 - 14:00'),
(14, 6, 3, 'IGE', '10:00 - 14:00'),
(15, 6, 3, 'II', '10:00 - 14:00'),
(16, 6, 3, 'IMCT', '10:00 - 14:00'),
(17, 6, 3, 'ISC', '10:00 - 14:00'),
(18, 6, 3, 'ISM', '10:00 - 14:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencia`
--

CREATE TABLE `dependencia` (
  `Id` int NOT NULL,
  `Nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Encargado_N` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Encargado_A` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Puesto_En` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dependencia`
--

INSERT INTO `dependencia` (`Id`, `Nombre`, `Encargado_N`, `Encargado_A`, `Puesto_En`) VALUES
(1, 'INSTITUTO TECNOLÓGICO DE NUEVO LEÓN', 'Pedro', 'Rosales Gutiérrez.', 'Director'),
(2, 'INSTITUTO TECNOLÓGICO DE NUEVO LEÓN', 'Pedro', 'Rosales Gutiérrez.', 'Director'),
(3, 'INSTITUTO TECNOLÓGICO DE NUEVO LEÓN', ' Pedro', 'Rosales Gutiérrez.', 'Director'),
(6, 'INSTITUTO TECNOLOGICO DE NUEVO LEON', 'pedro rosales', 'gutierrez', 'director'),
(7, 'INSTITUTO TECNOLOGICO DE NUEVO LEON', 'pedro rosales', 'gutierrez', 'director');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `Id` int NOT NULL,
  `Calle` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Colonia` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Estado` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Ciudad` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `CP` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `NumeroExt` smallint NOT NULL,
  `NumeroInt` smallint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`Id`, `Calle`, `Colonia`, `Estado`, `Ciudad`, `CP`, `NumeroExt`, `NumeroInt`) VALUES
(1, 'paseo las margaritas', 'Paseo de las flores', 'Nuevo Leon', 'Juarez', '67240', 1345, 1345),
(2, 'Valle de las Lavandas', 'Bello amanecer', 'Nuevo Leon', 'Guadalupe', '67132', 1409, 0),
(3, 'lomas de noruega', 'Ampliación Rancho Viejo', 'Nuevo Leon', 'Juarez', '67800', 0, 243),
(4, 'ibiza', 'San Andres', 'Nuevo Leon', 'Pesqueria', '67777', 2019, 0),
(5, 'Guerra Jiménez', 'Guerra', 'Nuevo Leon', 'Guadalupe Zitoon', '67155', 1012, 0),
(9, 'Guerra Jiménez', 'Guerra', 'Nuevo Leon', 'Guadalupe Zitoon', '67155', 1012, 0),
(12, 'Guerra Jiménez', 'Guerra', 'Nuevo Leon', 'Guadalupe Zitoon', '67155', 1012, 0),
(14, 'Guerra Jiménez', 'Guerra', 'Nuevo Leon', 'Guadalupe Zitoon', '67155', 1012, 0),
(15, 'Guerra Jiménez', 'Guerra', 'Nuevo Leon', 'Guadalupe Zitoon', '67155', 1012, 0),
(17, 'Guerra Jiménez', 'Guerra', 'Nuevo Leon', 'Guadalupe Zitoon', '67155', 1012, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_registro`
--

CREATE TABLE `fecha_registro` (
  `Id` int NOT NULL,
  `Fecha_In` date NOT NULL,
  `Fecha_Fin` date NOT NULL,
  `tipo` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fecha_registro`
--

INSERT INTO `fecha_registro` (`Id`, `Fecha_In`, `Fecha_Fin`, `tipo`) VALUES
(1, '2025-02-27', '2025-06-04', 'formulario'),
(2, '2025-02-27', '2025-06-03', 'servicio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `ID` int UNSIGNED NOT NULL,
  `Id_Alumno` varchar(8) COLLATE utf8mb4_general_ci NOT NULL,
  `Id_Cupo` int NOT NULL,
  `Id_Servicio` int NOT NULL,
  `Id_Dependencia` int NOT NULL,
  `Id_Rspnb` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`ID`, `Id_Alumno`, `Id_Cupo`, `Id_Servicio`, `Id_Dependencia`, `Id_Rspnb`) VALUES
(45, '20481035', 9, 6, 1, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE `reporte` (
  `Id` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `Id_Alumno` varchar(8) COLLATE utf8mb4_general_ci NOT NULL,
  `Id_Servicio` int DEFAULT NULL,
  `No_Reporte` varchar(2) COLLATE utf8mb4_general_ci NOT NULL,
  `Horas_Rep` smallint NOT NULL,
  `Horas_Acu` smallint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsable`
--

CREATE TABLE `responsable` (
  `id` int NOT NULL,
  `nombre` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `puesto` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `responsable`
--

INSERT INTO `responsable` (`id`, `nombre`, `puesto`) VALUES
(1, 'Lesly Yamilett Treviño Reyna', 'Jefa del Depto. de Gestión Tecnológica y Vinculación'),
(2, 'Lesly Yamilett Treviño Reyna', 'Jefa del Depto. de Gestión Tecnológica y Vinculación'),
(3, 'Lesly Yamilett Treviño Reyna', 'Jefa del Depto. de Gestión Tecnológica y Vinculación'),
(4, 'yo', 'y'),
(5, '', ''),
(6, 'pinoles', 'jefe de dpto.'),
(7, 'albertano', 'jefe de dpto.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `Id` int NOT NULL,
  `Id_Dependencia` int NOT NULL,
  `Programa` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Departamento` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Actividades` text COLLATE utf8mb4_general_ci NOT NULL,
  `Tipo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Id_Responsable` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`Id`, `Id_Dependencia`, `Programa`, `Departamento`, `Actividades`, `Tipo`, `Id_Responsable`) VALUES
(1, 1, 'Apoyo Administrativo y de atención al estudiante en Gestión Tecnológica y Vinculación', 'Gestión  Tecnológica y  Vinculación', ' Atención a estudiante en ventanilla.\r\n• Apoyo en recepción de documentos.\r\n• Apoyo administrativo.\r\n• Recepción de llamadas.\r\n• Archivo de documentos.\r\n• Apoyo en eventos.\r\n• Apoyo en oficinas S.E, S.S y P.P. ', 'Otrs', 1),
(2, 2, 'Coordinación lengua extranjera', 'Gestión  Tecnológica y  Vinculación', '1', 'Otrs', 2),
(3, 3, 'Manos de ayuda', 'Gestión Tecnológica y Vinculación', 'Dar clases \r\nDonar', 'DesCom', 3),
(6, 1, 'voluntariado', 'gestion', '-proyecto de investigación', 'ACv', 6),
(7, 7, 'voluntariado', 'ISC', 'mantenimiento equipos de computo', 'Otrs', 7);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`RFC`);

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`No_Control`),
  ADD KEY `fk_Id_Direccion` (`Id_Direccion`);

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Cuenta_Id_Alumno` (`Id_Alumno`);

--
-- Indices de la tabla `cupos`
--
ALTER TABLE `cupos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Cupos_Id_Servicio` (`Id_Servicio`);

--
-- Indices de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `fecha_registro`
--
ALTER TABLE `fecha_registro`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_Alumno_inscripciones` (`Id_Alumno`),
  ADD KEY `fk_cupos_incripciones` (`Id_Cupo`),
  ADD KEY `fk_servicio_inscripciones` (`Id_Servicio`),
  ADD KEY `fk_dependencia` (`Id_Dependencia`),
  ADD KEY `fk_responsable_incrip` (`Id_Rspnb`);

--
-- Indices de la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Reporte_Id_Alumno` (`Id_Alumno`),
  ADD KEY `fk_Reporte_Id_Servicio` (`Id_Servicio`);

--
-- Indices de la tabla `responsable`
--
ALTER TABLE `responsable`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Id_Dependencia` (`Id_Dependencia`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cupos`
--
ALTER TABLE `cupos`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `fecha_registro`
--
ALTER TABLE `fecha_registro`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `responsable`
--
ALTER TABLE `responsable`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `fk_Id_Direccion` FOREIGN KEY (`Id_Direccion`) REFERENCES `direccion` (`Id`);

--
-- Filtros para la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD CONSTRAINT `fk_Cuenta_Id_Alumno` FOREIGN KEY (`Id_Alumno`) REFERENCES `alumno` (`No_Control`);

--
-- Filtros para la tabla `cupos`
--
ALTER TABLE `cupos`
  ADD CONSTRAINT `fk_Cupos_Id_Servicio` FOREIGN KEY (`Id_Servicio`) REFERENCES `servicio` (`Id`);

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `fk_Alumno_inscripciones` FOREIGN KEY (`Id_Alumno`) REFERENCES `alumno` (`No_Control`),
  ADD CONSTRAINT `fk_cupos_incripciones` FOREIGN KEY (`Id_Cupo`) REFERENCES `cupos` (`Id`),
  ADD CONSTRAINT `fk_dependencia` FOREIGN KEY (`Id_Dependencia`) REFERENCES `dependencia` (`Id`),
  ADD CONSTRAINT `fk_responsable_incrip` FOREIGN KEY (`Id_Rspnb`) REFERENCES `responsable` (`id`),
  ADD CONSTRAINT `fk_servicio_inscripciones` FOREIGN KEY (`Id_Servicio`) REFERENCES `servicio` (`Id`);

--
-- Filtros para la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD CONSTRAINT `fk_Reporte_Id_Alumno` FOREIGN KEY (`Id_Alumno`) REFERENCES `alumno` (`No_Control`),
  ADD CONSTRAINT `fk_Reporte_Id_Servicio` FOREIGN KEY (`Id_Servicio`) REFERENCES `servicio` (`Id`);

--
-- Filtros para la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD CONSTRAINT `fk_Id_Dependencia` FOREIGN KEY (`Id_Dependencia`) REFERENCES `dependencia` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
