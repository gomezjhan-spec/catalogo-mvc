SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS catalogo_mvc
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE catalogo_mvc;

CREATE TABLE IF NOT EXISTS productos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    sku VARCHAR(20) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    categoria ENUM('Electrónica', 'Accesorios', 'Hogar', 'Papelería', 'Otro') NOT NULL,
    descripcion VARCHAR(500) NOT NULL DEFAULT '',
    precio DECIMAL(12, 2) UNSIGNED NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_productos_sku (sku),
    KEY idx_productos_nombre (nombre),
    KEY idx_productos_categoria (categoria),
    KEY idx_productos_estado (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO productos (sku, nombre, categoria, descripcion, precio, stock, activo)
VALUES
    ('AUD-204', 'Audífonos inalámbricos', 'Electrónica', 'Audio Bluetooth con estuche de carga y micrófono integrado.', 189900.00, 24, 1),
    ('TEC-118', 'Teclado compacto', 'Accesorios', 'Teclado de perfil bajo para estudio y oficina.', 129500.00, 16, 1),
    ('LAM-032', 'Lámpara de escritorio', 'Hogar', 'Luz LED regulable con brazo flexible.', 84500.00, 9, 1),
    ('LIB-410', 'Cuaderno de notas', 'Papelería', 'Tapa dura, papel de 100 g y 160 páginas.', 32000.00, 42, 1),
    ('MOU-076', 'Mouse ergonómico', 'Accesorios', 'Diseño ergonómico, conexión inalámbrica y sensor óptico.', 97900.00, 0, 0)
ON DUPLICATE KEY UPDATE id = id;
