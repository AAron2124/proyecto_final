-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 05:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `escuela`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `apellido`, `fecha_nacimiento`, `direccion`, `telefono`, `correo`, `usuario_id`) VALUES
(2, 'Luisa', 'Fernandez', '2003-06-20', 'Av. Reforma 101', '555-2222', 'luisa.fernandez@correo.com', 2),
(3, 'Miguel', 'Hernandez', '2005-02-18', 'Col. Centro', '555-3333', 'miguel.h@correo.com', 3),
(4, 'Sofia', 'Gonzalez', '2004-08-30', 'Calle Progreso 55', '555-4444', 'sofia.g@correo.com', 4),
(5, 'Andrés', 'Martinez', '2003-12-01', 'Av. Independencia', '555-5555', 'andres.m@correo.com', 5),
(10, 'Aaron', 'Colombres', '2004-12-24', 'Martin De Mayorga 432', '8444876274', 'aaron@gmail.com', 18);

-- --------------------------------------------------------

--
-- Table structure for table `alumnos_grupos`
--

CREATE TABLE `alumnos_grupos` (
  `alumno_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `alumnos_grupos`
--

INSERT INTO `alumnos_grupos` (`alumno_id`, `grupo_id`) VALUES
(2, 2),
(3, 3),
(4, 4),
(10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `asignaciones`
--

CREATE TABLE `asignaciones` (
  `id` int(11) NOT NULL,
  `materia_id` int(11) DEFAULT NULL,
  `profesor_id` int(11) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `asignaciones`
--

INSERT INTO `asignaciones` (`id`, `materia_id`, `profesor_id`, `grupo_id`) VALUES
(2, 2, 2, 2),
(3, 3, 3, 3),
(4, 4, 4, 4),
(6, 6, 6, 2),
(7, 7, 7, 3),
(8, 8, 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `calificacion` decimal(5,2) NOT NULL,
  `fecha` date DEFAULT curdate(),
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `calificaciones`
--

INSERT INTO `calificaciones` (`id`, `alumno_id`, `materia_id`, `profesor_id`, `grupo_id`, `calificacion`, `fecha`, `comentario`) VALUES
(3, 2, 6, 6, 2, 98.00, '2025-06-02', NULL),
(4, 3, 3, 3, 3, 70.00, '2025-06-02', NULL),
(5, 3, 7, 7, 3, 98.00, '2025-06-02', NULL),
(7, 2, 2, 2, 2, 90.00, '2025-06-02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `nivel` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `grupos`
--

INSERT INTO `grupos` (`id`, `nombre`, `nivel`) VALUES
(2, 'Grupo B', 'Ingeniería Mecánica'),
(3, 'Grupo C', 'Ingeniería Eléctrica'),
(4, 'Grupo D', 'Ingeniería de Sistemas');

-- --------------------------------------------------------

--
-- Table structure for table `grupos_materias`
--

CREATE TABLE `grupos_materias` (
  `id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `grupos_materias`
--

INSERT INTO `grupos_materias` (`id`, `grupo_id`, `materia_id`) VALUES
(3, 2, 2),
(4, 2, 6),
(5, 3, 3),
(6, 3, 7),
(7, 4, 4),
(8, 4, 8);

-- --------------------------------------------------------

--
-- Table structure for table `materias`
--

CREATE TABLE `materias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `materias`
--

INSERT INTO `materias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Resistencia de Materiales', 'Estudio del comportamiento de materiales bajo cargas'),
(2, 'Termodinámica', 'Principios de energía y transferencia de calor'),
(3, 'Circuitos Eléctricos', 'Análisis y diseño de circuitos eléctricos'),
(4, 'Programación Orientada a Objetos', 'Conceptos y práctica de programación avanzada'),
(5, 'Química Orgánica', 'Estructura, propiedades y reacciones de compuestos orgánicos'),
(6, 'Sistemas Ambientales', 'Estudio del impacto ambiental y gestión de recursos'),
(7, 'Gestión de la Producción', 'Optimización de procesos productivos'),
(8, 'Comunicaciones Digitales', 'Transmisión y procesamiento de señales digitales');

-- --------------------------------------------------------

--
-- Table structure for table `profesores`
--

CREATE TABLE `profesores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `profesores`
--

INSERT INTO `profesores` (`id`, `nombre`, `apellido`, `especialidad`, `telefono`, `correo`) VALUES
(2, 'Luis', 'Martínez', 'Ingeniería Mecánica', '5555678', 'luis.martinez@uni.edu'),
(3, 'María', 'Pérez', 'Ingeniería Eléctrica', '5558765', 'maria.perez@uni.edu'),
(4, 'Carlos', 'López', 'Ingeniería de Sistemas', '5554321', 'carlos.lopez@uni.edu'),
(5, 'Sofía', 'Ramírez', 'Ingeniería Química', '5551224', 'sofia.ramirez@uni.edu'),
(6, 'Jorge', 'Fernández', 'Ingeniería Ambiental', '5553344', 'jorge.fernandez@uni.edu'),
(7, 'Lucía', 'Santos', 'Ingeniería Industrial', '5555566', 'lucia.santos@uni.edu'),
(8, 'David', 'Rojas', 'Ingeniería en Telecomunicaciones', '55577880', 'david.rojas@uni.edu');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','alumno') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `rol`) VALUES
(2, '20230002', 'clave123', 'alumno'),
(3, '20230003', 'clave123', 'alumno'),
(4, '20230004', 'clave123', 'alumno'),
(5, '20230005', 'clave123', 'alumno'),
(6, 'admin01', 'admin123', 'admin'),
(18, '21051401', 'pass123', 'alumno');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `alumnos_grupos`
--
ALTER TABLE `alumnos_grupos`
  ADD PRIMARY KEY (`alumno_id`,`grupo_id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- Indexes for table `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materia_id` (`materia_id`),
  ADD KEY `profesor_id` (`profesor_id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- Indexes for table `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_calif_alumno` (`alumno_id`),
  ADD KEY `fk_calif_materia` (`materia_id`),
  ADD KEY `fk_calif_profesor` (`profesor_id`),
  ADD KEY `fk_calif_grupo` (`grupo_id`);

--
-- Indexes for table `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grupos_materias`
--
ALTER TABLE `grupos_materias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grupo_id` (`grupo_id`,`materia_id`),
  ADD KEY `materia_id` (`materia_id`);

--
-- Indexes for table `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `grupos_materias`
--
ALTER TABLE `grupos_materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `alumnos_grupos`
--
ALTER TABLE `alumnos_grupos`
  ADD CONSTRAINT `alumnos_grupos_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  ADD CONSTRAINT `alumnos_grupos_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);

--
-- Constraints for table `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`),
  ADD CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`),
  ADD CONSTRAINT `asignaciones_ibfk_3` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);

--
-- Constraints for table `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `fk_calif_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_calif_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `fk_calif_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`),
  ADD CONSTRAINT `fk_calif_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`);

--
-- Constraints for table `grupos_materias`
--
ALTER TABLE `grupos_materias`
  ADD CONSTRAINT `grupos_materias_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `grupos_materias_ibfk_2` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
