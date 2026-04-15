CREATE DATABASE gestion_curricular;

USE gestion_curricular;


-- Crear usuario
CREATE USER IF NOT EXISTS 'usuario_icesi'@'localhost' IDENTIFIED BY 'vj.Kgov.iYh*Uz](';

-- Dar todos los privilegios sobre la base de datos
GRANT ALL PRIVILEGES ON gestion_curricular.* TO 'usuario_icesi'@'localhost';

-- Aplicar cambios
FLUSH PRIVILEGES;

CREATE TABLE programa_academico(
    pro_aca_id INT PRIMARY KEY AUTO_INCREMENT,
    pro_aca_descripcion VARCHAR(250),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE curso(
    cur_id INT PRIMARY KEY AUTO_INCREMENT,
    cur_descripcion VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE programa_academico_curso(
    pro_aca_cur_id INT PRIMARY KEY AUTO_INCREMENT,
    pro_aca_id INT,
    cur_id INT
);

CREATE TABLE curso_objetivo(
    cur_obj_id INT PRIMARY KEY AUTO_INCREMENT,
    cur_id INT,
    obj_id INT,
    nivel VARCHAR(4),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE objetivo(
    obj_id INT PRIMARY KEY AUTO_INCREMENT,
    obj_descripcion VARCHAR(200),
    com_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE competencia(
    com_id INT PRIMARY KEY AUTO_INCREMENT,
    com_descripcion VARCHAR(250),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);
COMMIT;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);
COMMIT;

-- =========================
-- PROGRAMAS ACADÉMICOS
-- =========================
INSERT INTO programa_academico (pro_aca_descripcion) VALUES
('Ingeniería de Sistemas'),
('Ingeniería de Software'),
('Psicología'),
('Contaduría Pública'),
('Diseño Gráfico');

-- =========================
-- CURSOS
-- =========================
INSERT INTO curso (cur_descripcion) VALUES
-- Ingeniería de Sistemas
('Programación I'),
('Programación II'),
('Bases de Datos I'),
('Bases de Datos II'),
('Ingeniería de Software');

-- =========================
-- PROGRAMA ACADEMICO CURSO (Relación)
-- =========================
INSERT INTO programa_academico_curso (pro_aca_id, cur_id) VALUES

-- Ingeniería de Sistemas (1)
(1,1),(1,2),(1,3),(1,4),(1,5),

-- Ingeniería de Software (2)
(2,1),(2,2),(2,3),(2,4),(2,5);

-- =========================
-- COMPETENCIAS
-- =========================
INSERT INTO competencia (com_descripcion) VALUES
('Pensamiento lógico'),
('Gestión organizacional'),
('Análisis del comportamiento humano'),
('Normativa contable'),
('Creatividad visual');

-- =========================
-- OBJETIVOS
-- =========================
INSERT INTO objetivo (obj_descripcion, com_id) VALUES
('Desarrollar algoritmos básicos', 1),
('Diseñar esquemas de bases de datos', 1),

('Comprender procesos administrativos', 2),
('Optimizar recursos financieros', 2),

('Aplicar principios contables básicos', 2),

('Crear composiciones visuales digitales', 5);

-- =========================
-- CURSO_OBJETIVO (relación)
-- =========================
INSERT INTO curso_objetivo (cur_id, obj_id, nivel) VALUES
-- Programación I
(1, 1, 'V'),
-- Bases de Datos
(2, 2, 'I'),

-- Administración
(3, 3, 'V'),
-- Gestión Financiera
(4, 4, 'I'),

-- Psicología
(5, 5, 'F'),

-- Contabilidad
(6, 6, 'F');