<?php 
require_once __DIR__ . '/../includes/app.php';


use Controllers\LibroController;
use MVC\Router;
use Controllers\AppController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);



$router->get('/libros', [LibroController::class, 'renderizarPagina']);
$router->post('/libros/guardarAPI', [LibroController::class, 'guardarAPI']);
$router->get('/libros/buscarAPI', [LibroController::class, 'buscarAPI']);
$router->post('/libros/modificarAPI', [LibroController::class, 'modificarAPI']);
$router->get('/libros/eliminar', [LibroController::class, 'eliminarAPI']);

// Agregar estas líneas después de las rutas de libros en tu index.php

$router->get('/prestamos', [PrestamosController::class, 'renderizarPagina']);
$router->post('/prestamos/guardarAPI', [PrestamosController::class, 'guardarAPI']);
$router->get('/prestamos/buscarAPI', [PrestamosController::class, 'buscarAPI']);
$router->post('/prestamos/devolverAPI', [PrestamosController::class, 'devolverAPI']);
$router->get('/prestamos/eliminar', [PrestamosController::class, 'eliminarAPI']);
$router->get('/prestamos/librosDisponiblesAPI', [PrestamosController::class, 'librosDisponiblesAPI']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();