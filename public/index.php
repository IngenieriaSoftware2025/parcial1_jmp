<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\LibroController;
use Controllers\PrestamoController;
use MVC\Router;
use Controllers\AppController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

// Rutas de Libros
$router->get('/libros', [LibroController::class, 'renderizarPagina']);
$router->post('/libros/guardarAPI', [LibroController::class, 'guardarAPI']);
$router->get('/libros/buscarAPI', [LibroController::class, 'buscarAPI']);
$router->post('/libros/modificarAPI', [LibroController::class, 'modificarAPI']);
$router->get('/libros/eliminar', [LibroController::class, 'eliminarAPI']);

// Rutas de Préstamos
$router->get('/prestamos', [PrestamoController::class, 'renderizarPagina']);
$router->post('/prestamos/guardarAPI', [PrestamoController::class, 'guardarAPI']);
$router->get('/prestamos/buscarAPI', [PrestamoController::class, 'buscarAPI']);
$router->post('/prestamos/devolverAPI', [PrestamoController::class, 'devolverAPI']);
$router->get('/prestamos/eliminar', [PrestamoController::class, 'eliminarAPI']);
$router->get('/prestamos/librosDisponiblesAPI', [PrestamoController::class, 'librosDisponiblesAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();