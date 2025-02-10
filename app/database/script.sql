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
(
    id,
    nombre,
    apellidos,
    direccion,
    email,
    telefono,
    password,
    rol,
    token,
    token_exp,
    confirmacion
)
VALUES (
    1,
    'juan',
    'juan juan',
    'calle juan',
    'admin@example.com',
    '998877665',
    '$2y$10$mxDUACCMlV2tnhaWXwX9ZOD1/yWNanGVSrOcY2acDX3O/3OE7f9hy',
    'admin',
    'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzkxNzczNjQsImRhdGEiOnsibmFtZSI6Imp1YW4iLCJlbWFpbCI6ImFkbWluQGV4YW1wbGUuY29tIn19.IfmWrOI5Y1_KHXk5BY_6LsfC-kse9IP0LoMDtlUmRfM',
    NULL,
    '1'
);

-- Inserts para categorías
INSERT INTO categorias (nombre) VALUES ('Camisas');
INSERT INTO categorias (nombre) VALUES ('Zapatos');

-- Inserts para productos (camisas)
INSERT INTO productos (
    categoria_id, nombre, descripcion, precio, stock, oferta, fecha, imagen
) VALUES
(
    1,
    'Camisa Clásica',
    'Camisa de algodón de manga larga',
    29.99,
    100,
    'NO',
    '2025-02-10',
    'camisa1.jpeg'
),
(
    1,
    'Camisa Casual',
    'Camisa de manga corta',
    24.99,
    80,
    'NO',
    '2025-02-10',
    'camisa2.jpeg'
),
(
    1,
    'Camisa Elegante',
    'Camisa de vestir con cuello italiano',
    39.99,
    50,
    'SI',
    '2025-02-10',
    'camisa3.jpeg'
),
(
    1,
    'Camisa Casual',
    'Camisa transpirable para actividades diarias',
    34.99,
    75,
    'NO',
    '2025-02-10',
    'camisa4.jpeg'
),
(
    1,
    'Camisa Clàsica',
    'Camisa con diseño moderno y colorido',
    27.99,
    60,
    'NO',
    '2025-02-10',
    'camisa5.jpeg'
);

-- Inserts para productos (zapatos)
INSERT INTO productos (
    categoria_id, nombre, descripcion, precio, stock, oferta, fecha, imagen
) VALUES
(
    2,
    'Zapatos Invierno',
    'Zapatos de cuero para uso diario',
    79.99,
    40,
    'NO',
    '2025-02-10',
    'zapato1.jpeg'
),
(
    2,
    'Zapatos',
    'Zapatos para uso formal',
    59.99,
    100,
    'SI',
    '2025-02-10',
    'zapato2.jpeg'
),
(
    2,
    'Zapatillas',
    'Zapatillas para uso diaria',
    49.99,
    60,
    'NO',
    '2025-02-10',
    'zapato3.jpeg'
),
(
    2,
    'Zapatos Cafés',
    'Zapatos de uso clásico',
    89.99,
    30,
    'NO',
    '2025-02-10',
    'zapato4.jpeg'
),
(
    2,
    'Zapato de Tacón',
    'Zapato para uso formal',
    29.99,
    80,
    'SI',
    '2025-02-10',
    'zapato5.jpeg'
);
