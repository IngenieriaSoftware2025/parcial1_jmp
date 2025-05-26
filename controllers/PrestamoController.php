<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Prestamos;
use MVC\Router;

class PrestamoController extends ActiveRecord {
    
    public static function renderizarPagina(Router $router) {
        $router->render('prestamos/index', []);
    }
    
    public static function guardarAPI() {
        getHeadersApi();
        
        $_POST['libro_id'] = trim(htmlspecialchars($_POST['libro_id']));
        $_POST['persona_prestado'] = trim(htmlspecialchars($_POST['persona_prestado']));
        
        if (strlen($_POST['persona_prestado']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la persona debe tener al menos 2 caracteres'
            ]);
            return;
        }
        
        if (empty($_POST['libro_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un libro'
            ]);
            return;
        }
        
        try {
            $data = new Prestamos([
                'libro_id' => $_POST['libro_id'],
                'persona_prestado' => $_POST['persona_prestado'],
                'fecha_prestamo' => date('Y-m-d H:i:s'),
                'devuelto' => 'N'
            ]);
            
            $crear = $data->crear();
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Préstamo guardado correctamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    
    public static function buscarAPI() {
        try {
            $sql = "SELECT p.*, l.titulo, l.autor 
                    FROM prestamos p 
                    INNER JOIN libros l ON p.libro_id = l.id 
                    ORDER BY p.fecha_prestamo DESC";
            $data = self::fetchArray($sql);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Préstamos obtenidos correctamente',
                'data' => $data
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener préstamos',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    
    public static function devolverAPI() {
        getHeadersApi();
        
        $id = $_POST['id'];
        
        try {
            $data = Prestamos::find($id);
            
            $data->sincronizar([
                'fecha_devolucion' => date('Y-m-d H:i:s'),
                'devuelto' => 'S'
            ]);
            
            $data->actualizar();
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Libro devuelto correctamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al devolver',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    
    public static function eliminarAPI() {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            $ejecutar = Prestamos::eliminarPrestamo($id);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Préstamo eliminado correctamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    
    public static function librosDisponiblesAPI() {
        try {
            $sql = "SELECT * FROM libros ORDER BY titulo";
            $data = self::fetchArray($sql);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Libros obtenidos correctamente',
                'data' => $data
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener libros',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}