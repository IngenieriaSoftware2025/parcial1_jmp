<?php

namespace Model;

class Libros extends ActiveRecord {
    
    public static $tabla = 'libros';
    
    public static $columnasDB = [
        'titulo',
        'autor',
        'persona_prestado'
    ];
    
    public static $idTabla = 'id';
    
    public $id;
    public $titulo;
    public $autor;
    public $persona_prestado;
    
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->autor = $args['autor'] ?? '';
        $this->persona_prestado = $args['persona_prestado'] ?? '';
    }
    
    public static function eliminarLibro($id) {
        $sql = "DELETE FROM libros WHERE id = $id";
        return self::SQL($sql);
    }
}