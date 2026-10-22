-- =========================================================
-- BASE DE DATOS: TIENDA VIRTUAL BÁSICA TECNOFÁCIL
-- =========================================================

CREATE DATABASE IF NOT EXISTS tienda_virtual_basica
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE tienda_virtual_basica;

-- Tabla que almacena los productos ofrecidos.
CREATE TABLE IF NOT EXISTS productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(150) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1
);

-- Tabla que almacena cada compra realizada.
CREATE TABLE IF NOT EXISTS compras (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    nombre_comprador VARCHAR(100) NOT NULL,
    documento VARCHAR(20) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha_compra DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_compra_producto
        FOREIGN KEY (id_producto)
        REFERENCES productos(id_producto)
);

-- Insertar los productos solo si la tabla está vacía.
INSERT INTO productos
    (id_producto, nombre, descripcion, categoria, precio, imagen, activo)
VALUES
    (1, 'Teclado inalámbrico', 'Teclado compacto con conexión estable y distribución en español.', 'Accesorios', 85000, 'img/teclado.png', 1),
    (2, 'Audífonos inalámbricos', 'Audio cómodo para clases virtuales, música y videollamadas.', 'Audio', 120000, 'img/audifonos.png', 1),
    (3, 'Cámara web HD', 'Cámara compacta para reuniones, tutorías y clases virtuales.', 'Video', 95000, 'img/camara.png', 1),
    (4, 'Mouse inalámbrico', 'Control preciso y diseño ergonómico para uso diario.', 'Accesorios', 48000, 'img/mouse.png', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

SELECT * FROM productos;
