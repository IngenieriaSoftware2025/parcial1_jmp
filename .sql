CREATE DATABASE morataya_parcial;

CREATE TABLE libros (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(200) NOT NULL,
    persona_prestado VARCHAR(200) DEFAULT NULL
);

CREATE TABLE prestamos (
    id SERIAL PRIMARY KEY,
    libro_id INTEGER NOT NULL,
    persona_prestado VARCHAR(200) NOT NULL,
    fecha_prestamo DATETIME YEAR TO SECOND,
    fecha_devolucion DATETIME YEAR TO SECOND DEFAULT NULL,
    devuelto CHAR(1) DEFAULT 'N'
);