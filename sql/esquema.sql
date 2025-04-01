-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `desisDB`;
USE `desisDB`;

-- Tabla: bodegas
CREATE TABLE bodegas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla: sucursales
CREATE TABLE sucursales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_bodega INT,
    FOREIGN KEY (id_bodega) REFERENCES bodegas(id) ON DELETE CASCADE
);

-- Tabla: monedas
CREATE TABLE monedas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Tabla: productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(15) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    bodega_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    moneda_id INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    materiales TEXT NOT NULL,
    descripcion TEXT NOT NULL,
    FOREIGN KEY (bodega_id) REFERENCES bodegas(id),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id),
    FOREIGN KEY (moneda_id) REFERENCES monedas(id)
);

-- Insertar datos Bodegas
INSERT INTO bodegas (nombre) VALUES ('Bodega Central'), ('Bodega Norte');

select * from bodegas;

-- Insertar datos Sucursales
INSERT INTO sucursales (nombre, id_bodega) VALUES 
('Sucursal A', 1),
('Sucursal B', 1),
('Sucursal C', 2);

select * from sucursales;

-- Insertar datos Monedas
INSERT INTO monedas (nombre) VALUES ('CLP'), ('USD'), ('EUR');

select * from monedas;
