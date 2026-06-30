-- --------------------------------------------------------
-- BASE DE DATOS VIDEOJUEGOS
-- --------------------------------------------------------

DROP DATABASE IF EXISTS videojuegos;
CREATE DATABASE videojuegos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE videojuegos;

-- --------------------------------------------------------
-- Tabla `ADMINS`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
<<<<<<< HEAD
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla videojuegos.admins: ~0 rows (aproximadamente)
INSERT INTO `admins` (`id`, `usuario`, `password_hash`, `creado_en`) VALUES
	(1, 'admin', '$2y$10$d/alJrRQ.ntCPWo0.nseOuxl1pCt.399277wrhIved9JAatpS0Nay', '2026-06-23 16:53:18');

-- Las comillas son para las palabras reservadas
-- --------------------------------------------------------
-- Tabla `juegos`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `juegos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plataforma` enum('PS4','PS5','AMBOS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_lanzamiento` date DEFAULT NULL,
  `genero` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desarrollador` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagen_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_crop_x` float DEFAULT (50),
  `banner_crop_y` float DEFAULT (37),
  `banner_crop_width` float DEFAULT (100),
  `banner_crop_height` float DEFAULT (100),
  `pegi_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clasificacion_1_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clasificacion_2_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clasificacion_3_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_clas1` tinyint DEFAULT '1',
  `show_clas2` tinyint DEFAULT '1',
  `show_clas3` tinyint DEFAULT '1',
  `fecha_adicionado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dificultad_platino` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '01 sobre 10',
  `duracion_estimada` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trofeos_offline_platino` smallint unsigned DEFAULT '0',
  `trofeos_offline_oro` smallint unsigned DEFAULT '0',
  `trofeos_offline_plata` smallint unsigned DEFAULT '0',
  `trofeos_offline_bronce` smallint unsigned DEFAULT '0',
  `trofeos_online_platino` smallint unsigned DEFAULT '0',
  `trofeos_online_oro` smallint unsigned DEFAULT '0',
  `trofeos_online_plata` smallint unsigned DEFAULT '0',
  `trofeos_online_bronce` smallint unsigned DEFAULT '0',
  `total_trofeos` smallint unsigned DEFAULT '0',
  `pase_online` tinyint DEFAULT '0',
  `necesario_platino` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'NO',
  `trofeos_ocultos` text COLLATE utf8mb4_unicode_ci,
  `min_partidas` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '1 Partida',
  `trofeos_perdibles` text COLLATE utf8mb4_unicode_ci,
  `trucos_afectan` tinyint DEFAULT '0',
  `dificultad_afecta` tinyint DEFAULT '0',
  `comentario` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_titulo` (`titulo`),
  KEY `idx_plataforma` (`plataforma`),
  KEY `idx_fecha` (`fecha_lanzamiento`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
=======
	id int NOT NULL AUTO_INCREMENT,
	usuario varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
	password_hash varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
	creado_en timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY usuario (usuario)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla videojuegos.admins: ~1 rows (aproximadamente)
INSERT INTO `admins` (`id`, `usuario`, `password_hash`, `creado_en`) VALUES
	(1, 'admin', '$2y$10$d/alJrRQ.ntCPWo0.nseOuxl1pCt.399277wrhIved9JAatpS0Nay', '2026-06-23 16:53:18');

-- --------------------------------------------------------
-- Tabla `juegos`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS juegos (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	titulo VARCHAR(255) NOT NULL,
	plataforma ENUM('PS4','PS5','AMBOS') NOT NULL,
	fecha_lanzamiento DATE,
	genero VARCHAR(100),
	desarrollador VARCHAR(100),
	imagen_url VARCHAR(500),
	banner_url VARCHAR(500),
	banner_crop_x float DEFAULT (50),
	banner_crop_y float DEFAULT (37),
	banner_crop_width float DEFAULT (100),
	banner_crop_height float DEFAULT (100),
	pegi_url VARCHAR(500),
	clasificacion_1_url VARCHAR(500),
	clasificacion_2_url VARCHAR(500),
	clasificacion_3_url VARCHAR(500),
	show_clas1 tinyint DEFAULT 1,
	show_clas2 tinyint DEFAULT 1,
	show_clas3 tinyint DEFAULT 1,
	fecha_adicionado TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	dificultad_platino VARCHAR(50) DEFAULT '01 sobre 10',
	duracion_estimada VARCHAR(100),
	trofeos_offline_platino SMALLINT UNSIGNED DEFAULT 0,
	trofeos_offline_oro SMALLINT UNSIGNED DEFAULT 0,
	trofeos_offline_plata SMALLINT UNSIGNED DEFAULT 0,
	trofeos_offline_bronce SMALLINT UNSIGNED DEFAULT 0,
	trofeos_online_platino SMALLINT UNSIGNED DEFAULT 0,
	trofeos_online_oro SMALLINT UNSIGNED DEFAULT 0,
	trofeos_online_plata SMALLINT UNSIGNED DEFAULT 0,
	trofeos_online_bronce SMALLINT UNSIGNED DEFAULT 0,
	total_trofeos SMALLINT UNSIGNED DEFAULT 0,
	pase_online TINYINT DEFAULT 0,
	necesario_platino VARCHAR(100) DEFAULT 'NO',
	trofeos_ocultos TEXT,
	min_partidas VARCHAR(50) DEFAULT '1 Partida',
	trofeos_perdibles TEXT,
	trucos_afectan TINYINT DEFAULT 0,
	dificultad_afecta TINYINT DEFAULT 0,
	comentario TEXT,
	mapa_interactivo_url varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	PRIMARY KEY (id),
	KEY idx_titulo (titulo),
	KEY idx_plataforma (plataforma),
	KEY idx_fecha (fecha_lanzamiento)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a

-- --------------------------------------------------------
-- Tabla `dlcs`
-- --------------------------------------------------------
<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `dlcs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `videojuego_id` int unsigned NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_lanzamiento` date DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `imagen_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dificultad_platino` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duracion_estimada` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trofeos_offline_oro` int DEFAULT '0',
  `trofeos_offline_plata` int DEFAULT '0',
  `trofeos_offline_bronce` int DEFAULT '0',
  `trofeos_online_oro` int DEFAULT '0',
  `trofeos_online_plata` int DEFAULT '0',
  `trofeos_online_bronce` int DEFAULT '0',
  `trofeos_perdibles` text COLLATE utf8mb4_unicode_ci,
  `trucos_afectan` tinyint DEFAULT '0',
  `dificultad_afecta` tinyint DEFAULT '0',
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `fecha_adicionado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_videojuego_id` (`videojuego_id`),
  KEY `idx_nombre` (`nombre`),
  CONSTRAINT `dlcs_ibfk_1` FOREIGN KEY (`videojuego_id`) REFERENCES `juegos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
=======
CREATE TABLE IF NOT EXISTS dlcs (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	videojuego_id INT UNSIGNED NOT NULL,
	nombre VARCHAR(255) NOT NULL,
	fecha_lanzamiento DATE DEFAULT NULL,
	descripcion TEXT,
	imagen_url VARCHAR(500),
	banner_url VARCHAR(500),
	dificultad_platino VARCHAR(50),
	duracion_estimada VARCHAR(100),
	trofeos_offline_oro INT DEFAULT 0,
	trofeos_offline_plata INT DEFAULT 0,
	trofeos_offline_bronce INT DEFAULT 0,
	trofeos_online_oro INT DEFAULT 0,
	trofeos_online_plata INT DEFAULT 0,
	trofeos_online_bronce INT DEFAULT 0,
	trofeos_perdibles TEXT,
	trucos_afectan TINYINT DEFAULT 0,
	dificultad_afecta TINYINT DEFAULT 0,
	comentario TEXT,
	fecha_adicionado TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

	PRIMARY KEY (id),
	INDEX idx_videojuego_id (videojuego_id),
	INDEX idx_nombre (nombre),

	CONSTRAINT dlcs_ibfk_1 FOREIGN KEY (videojuego_id) REFERENCES juegos (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a

-- --------------------------------------------------------
-- Tabla `trofeos`
-- --------------------------------------------------------
<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `trofeos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `videojuego_id` int unsigned NOT NULL,
  `nombre_trofeo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `tipo` enum('BRONCE','PLATA','ORO','PLATINO') COLLATE utf8mb4_unicode_ci NOT NULL,
  `conseguido` tinyint(1) DEFAULT '0',
  `es_online` tinyint(1) DEFAULT '0',
  `perdible` tinyint(1) DEFAULT '0',
  `icono_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instrucciones` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_videojuego_id` (`videojuego_id`),
  CONSTRAINT `fk_trofeos_juegos` FOREIGN KEY (`videojuego_id`) REFERENCES `juegos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1949 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
=======
CREATE TABLE IF NOT EXISTS trofeos (
	id INT NOT NULL AUTO_INCREMENT,
	videojuego_id INT UNSIGNED NOT NULL,
	nombre_trofeo VARCHAR(255) NOT NULL,
	descripcion TEXT,
	tipo ENUM('BRONCE','PLATA','ORO','PLATINO') NOT NULL,
	conseguido TINYINT(1) DEFAULT 0,
	`online` TINYINT(1) DEFAULT 0,
	perdible TINYINT(1) DEFAULT 0,
	icono_url VARCHAR(500),
	instrucciones TEXT,
	video_url VARCHAR(500),

	PRIMARY KEY (id),
	INDEX idx_videojuego_id (videojuego_id),
	CONSTRAINT fk_trofeos_juegos FOREIGN KEY (videojuego_id) REFERENCES juegos(id) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1408 CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a

-- --------------------------------------------------------
-- Tabla `progreso_trofeos`
-- --------------------------------------------------------
<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `progreso_trofeos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `videojuego_id` int unsigned NOT NULL,
  `total_trofeos` int DEFAULT '0',
  `bronce_conseguidos` int DEFAULT '0',
  `plata_conseguidos` int DEFAULT '0',
  `oro_conseguidos` int DEFAULT '0',
  `platino_conseguido` tinyint DEFAULT '0',
  `porcentaje_completado` decimal(5,2) DEFAULT '0.00',
  `ultima_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_videojuego_progreso` (`videojuego_id`),
  CONSTRAINT `progreso_trofeos_ibfk_1` FOREIGN KEY (`videojuego_id`) REFERENCES `juegos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3325 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
=======
CREATE TABLE IF NOT EXISTS progreso_trofeos (
	id INT NOT NULL AUTO_INCREMENT,
	videojuego_id INT UNSIGNED NOT NULL,
	total_trofeos INT DEFAULT 0,
	bronce_conseguidos INT DEFAULT 0,
	plata_conseguidos INT DEFAULT 0,
	oro_conseguidos INT DEFAULT 0,
	platino_conseguido TINYINT DEFAULT 0,
	porcentaje_completado DECIMAL(5,2) DEFAULT 0.00,
	ultima_actualizacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

	PRIMARY KEY (id),
	UNIQUE KEY unique_videojuego_progreso (videojuego_id),

	CONSTRAINT progreso_trofeos_ibfk_1 FOREIGN KEY (videojuego_id) REFERENCES juegos (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a

-- --------------------------------------------------------
-- Tabla `trofeos_dlc`
-- --------------------------------------------------------
<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `trofeos_dlc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dlc_id` int unsigned NOT NULL,
  `nombre_trofeo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `tipo` enum('BRONCE','PLATA','ORO') COLLATE utf8mb4_unicode_ci NOT NULL,
  `conseguido` tinyint DEFAULT '0',
  `es_online` tinyint DEFAULT '0',
  `perdible` tinyint DEFAULT '0',
  `icono_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instrucciones` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_dlc_id` (`dlc_id`),
  CONSTRAINT `trofeos_dlc_ibfk_1` FOREIGN KEY (`dlc_id`) REFERENCES `dlcs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=460 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabla `MAPAS`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mapas_interactivos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `juego_id` INT UNSIGNED NOT NULL,
    `nombre` VARCHAR(255) NOT NULL,
    `url` VARCHAR(500) NOT NULL,
    `orden` INT DEFAULT 0,
    FOREIGN KEY (`juego_id`) REFERENCES `juegos`(`id`) ON DELETE CASCADE
);
=======
CREATE TABLE trofeos_dlc (
	id INT NOT NULL AUTO_INCREMENT,
	dlc_id INT UNSIGNED NOT NULL,
	nombre_trofeo VARCHAR(255) NOT NULL,
	descripcion TEXT,
	tipo ENUM('BRONCE','PLATA','ORO') NOT NULL,
	conseguido TINYINT DEFAULT 0,
  `online` TINYINT(1) DEFAULT 0,
	perdible TINYINT DEFAULT 0,
	icono_url VARCHAR(500),
	instrucciones TEXT,
	video_url VARCHAR(500),

	PRIMARY KEY (id),
	INDEX idx_dlc_id (dlc_id),

	CONSTRAINT trofeos_dlc_ibfk_1 FOREIGN KEY (dlc_id) REFERENCES dlcs (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a

-- --------------------------------------------------------
-- Tabla `MEDIA JUEGOS`
-- --------------------------------------------------------
<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `media_juegos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `videojuego_id` int unsigned NOT NULL,
  `tipo` enum('image','video') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `orden` int DEFAULT (0),
  `fecha_adicionado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_videojuego_id` (`videojuego_id`),
  KEY `idx_orden` (`orden`),
  CONSTRAINT `media_juegos_ibfk_1` FOREIGN KEY (`videojuego_id`) REFERENCES `juegos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1741 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
=======
CREATE TABLE IF NOT EXISTS media_juegos (
	id int unsigned NOT NULL AUTO_INCREMENT,
	videojuego_id int unsigned NOT NULL,
	tipo enum('image','video') COLLATE utf8mb4_unicode_ci NOT NULL,
	url varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
	orden int DEFAULT (0),
	fecha_adicionado timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	KEY idx_videojuego_id (videojuego_id),
	KEY idx_orden (orden),
	CONSTRAINT media_juegos_ibfk_1 FOREIGN KEY (videojuego_id) REFERENCES juegos (id) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1208 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a

-- --------------------------------------------------------
-- Tabla `MEDIA DLCs`
-- --------------------------------------------------------
<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `media_dlcs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `dlc_id` int unsigned NOT NULL,
  `tipo` enum('image','video') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `orden` int DEFAULT (0),
  `fecha_adicionado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_dlc_id` (`dlc_id`),
  KEY `idx_orden` (`orden`),
  CONSTRAINT `media_dlcs_ibfk_1` FOREIGN KEY (`dlc_id`) REFERENCES `dlcs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

=======
CREATE TABLE IF NOT EXISTS media_dlcs (
	id int unsigned NOT NULL AUTO_INCREMENT,
	dlc_id int unsigned NOT NULL,
	tipo enum('image','video') COLLATE utf8mb4_unicode_ci NOT NULL,
	url varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
	orden int DEFAULT (0),
	fecha_adicionado timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	KEY idx_dlc_id (dlc_id),
	KEY idx_orden (orden),
	CONSTRAINT media_dlcs_ibfk_1 FOREIGN KEY (dlc_id) REFERENCES dlcs (id) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
