<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Libros;
use MVC\Router;

class LibroController extends ActiveRecord {
    
    public static function renderizarPagina(Router $router) {
        $router->render('libros/index', []);
    }
    
    public static function guardarAPI() {
        getHeadersApi();
        
        $_POST['titulo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['titulo']))));
        $_POST['autor'] = ucwords(strtolower(trim(htmlspecialchars($_POST['autor']))));
        $_POST['persona_prestado'] = ucwords(strtolower(trim(htmlspecialchars($_POST['persona_prestado']))));
        
        if (strlen($_POST['titulo']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El título debe tener al menos 2 caracteres'
            ]);
            return;
        }
        
        if (strlen($_POST['autor']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El autor debe tener al menos 2 caracteres'
            ]);
            return;
        }
        
        try {
            $data = new Libros([
                'titulo' => $_POST['titulo'],
                'autor' => $_POST['autor'],
                'persona_prestado' => $_POST['persona_prestado']
            ]);
            
            $crear = $data->crear();
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Libro guardado correctamente'
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
    
    public static function modificarAPI() {
        getHeadersApi();
        
        $id = $_POST['id'];
        
        $_POST['titulo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['titulo']))));
        $_POST['autor'] = ucwords(strtolower(trim(htmlspecialchars($_POST['autor']))));
        $_POST['persona_prestado'] = ucwords(strtolower(trim(htmlspecialchars($_POST['persona_prestado']))));
        
        if (strlen($_POST['titulo']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El título debe tener al menos 2 caracteres'
            ]);
            return;
        }
        
        if (strlen($_POST['autor']) < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El autor debe tener al menos 2 caracteres'
            ]);
            return;
        }
        
        try {
            $data = Libros::find($id);
            
            $data->sincronizar([
                'titulo' => $_POST['titulo'],
                'autor' => $_POST['autor'],
                'persona_prestado' => $_POST['persona_prestado']
            ]);
            
            $data->actualizar();
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Libro modificado correctamente'
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar',
                'detalle' => $e->getMessage()
            ]);
        }
    }
    
    public static function eliminarAPI() {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            $ejecutar = Libros::eliminarLibro($id);
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Libro eliminado correctamente'
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
}