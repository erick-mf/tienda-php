DROP DATABASE tienda;
SET NAMES utf8;
CREATE DATABASE IF NOT EXISTS tienda;
USE tienda;

DROP TABLE IF EXISTS usuarios;
CREATE TABLE IF NOT EXISTS usuarios (
    id int(255) AUTO_INCREMENT NOT NULL,
    nombre varchar(100) NOT NULL,
    apellidos varchar(255),
    direccion varchar(255),
    email varchar(255) NOT NULL,
    telefono varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    rol varchar(20),
    token varchar(255),
    token_exp DATETIME,
    confirmacion boolean,
    CONSTRAINT pk_usuarios PRIMARY KEY (id),
    CONSTRAINT uq_email UNIQUE (email)
) ENGINE = InnoDb DEFAULT CHARSET = utf8 COLLATE = utf8_bin;


DROP TABLE IF EXISTS categorias;
CREATE TABLE IF NOT EXISTS categorias (
    id int(255) AUTO_INCREMENT NOT NULL,
    nombre varchar(100) NOT NULL,
    CONSTRAINT pk_categorias PRIMARY KEY (id)
) ENGINE = InnoDb DEFAULT CHARSET = utf8 COLLATE = utf8_bin;


DROP TABLE IF EXISTS productos;
CREATE TABLE IF NOT EXISTS productos (
    id int(255) AUTO_INCREMENT NOT NULL,
    categoria_id int(255) NOT NULL,
    nombre varchar(100) NOT NULL,
    descripcion text,
    precio float(100, 2) NOT NULL,
    stock int(255) NOT NULL,
    oferta varchar(2),
    fecha date NOT NULL,
    imagen varchar(255),
    CONSTRAINT pk_categorias PRIMARY KEY (id),
    CONSTRAINT fk_producto_categoria FOREIGN KEY (
        categoria_id
    ) REFERENCES categorias (id)
) ENGINE = InnoDb DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

DROP TABLE IF EXISTS pedidos;
CREATE TABLE IF NOT EXISTS pedidos (
    id int(255) AUTO_INCREMENT NOT NULL,
    usuario_id int(255) NOT NULL,
    provincia varchar(100) NOT NULL,
    localidad varchar(100) NOT NULL,
    direccion varchar(255) NOT NULL,
    coste float(200, 2) NOT NULL,
    estado varchar(20) NOT NULL,
    fecha date,
    hora time,
    CONSTRAINT pk_pedidos PRIMARY KEY (id),
    CONSTRAINT fk_pedido_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios (
        id
    )
) ENGINE = InnoDb DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

DROP TABLE IF EXISTS lineas_pedidos;
CREATE TABLE IF NOT EXISTS lineas_pedidos (
    id int(255) AUTO_INCREMENT NOT NULL,
    pedido_id int(255) NOT NULL,
    producto_id int(255) NOT NULL,
    unidades int(255) NOT NULL,
    CONSTRAINT pk_lineas_pedidos PRIMARY KEY (id),
    CONSTRAINT fk_linea_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos (id),
    CONSTRAINT fk_linea_producto FOREIGN KEY (
        producto_id
    ) REFERENCES productos (id)
) ENGINE = InnoDb DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

INSERT INTO tienda.usuarios
(id, nombre, apellidos, direccion, email, telefono, password, rol)
VALUES (
    1,
    'juan',
    'juan juan',
    'calle juan',
    'admin@example.com',
    '998877665',
    '$2y$10$mxDUACCMlV2tnhaWXwX9ZOD1/yWNanGVSrOcY2acDX3O/3OE7f9hy',
    'admin'
);
