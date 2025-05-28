CREATE DATABASE IF NOT EXISTS escuela;
USE escuela;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','alumno') NOT NULL
);

-- Tabla de alumnos
CREATE TABLE alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    apellido VARCHAR(100),
    fecha_nacimiento DATE,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    correo VARCHAR(100),
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de profesores
CREATE TABLE profesores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    apellido VARCHAR(100),
    especialidad VARCHAR(100),
    telefono VARCHAR(20),
    correo VARCHAR(100)
);

-- Tabla de materias
CREATE TABLE materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT
);

-- Tabla de grupos
CREATE TABLE grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    nivel VARCHAR(50)
);

-- Asociación alumnos-grupos
CREATE TABLE alumnos_grupos (
    alumno_id INT,
    grupo_id INT,
    PRIMARY KEY (alumno_id, grupo_id),
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);

-- Asociación materias-profesores-grupos
CREATE TABLE asignaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    materia_id INT,
    profesor_id INT,
    grupo_id INT,
    FOREIGN KEY (materia_id) REFERENCES materias(id),
    FOREIGN KEY (profesor_id) REFERENCES profesores(id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);

-- Calificaciones
CREATE TABLE calificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumno_id INT NOT NULL,
    materia_id INT NOT NULL,
    profesor_id INT NOT NULL,
    grupo_id INT NOT NULL,
    calificacion DECIMAL(5,2) NOT NULL,
    fecha DATE DEFAULT CURRENT_DATE,
    comentario TEXT NULL,
    
    CONSTRAINT fk_calif_alumno FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE,
    CONSTRAINT fk_calif_materia FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE RESTRICT,
    CONSTRAINT fk_calif_profesor FOREIGN KEY (profesor_id) REFERENCES profesores(id) ON DELETE RESTRICT,
    CONSTRAINT fk_calif_grupo FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE RESTRICT
);

