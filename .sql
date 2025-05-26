CREATE DATABASE morataya_parcial;

CREATE TABLE libros (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(200) NOT NULL
);

CREATE TABLE personas (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL
);

CREATE TABLE prestamos (
    id SERIAL PRIMARY KEY,
    libro_id INTEGER NOT NULL,
    persona_id INTEGER NOT NULL,
    fecha_prestamo DATE TIME YEAR TO SECOND,
    fecha_devolucion DATE,
    devuelto CHAR(1) DEFAULT 'N'
);