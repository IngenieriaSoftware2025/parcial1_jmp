<?php

namespace Model;

class Libros extends ActiveRecord {
    
    public static $tabla = 'libros';
    public static $columnasDB = [
        'titulo',
        'autor'
    ];
    
    public static $idTabla = 'id';
    public $id;
    public $titulo;
    public $autor;
    
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->autor = $args['autor'] ?? '';
    }
    
    public static function eliminarLibro($id) {
        $sql = "DELETE FROM libros WHERE id = $id";
        return self::SQL($sql);
    }
}