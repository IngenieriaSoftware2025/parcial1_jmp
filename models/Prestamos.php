<?php

namespace Model;

class Prestamos extends ActiveRecord {
    
    public static $tabla = 'prestamos';
    
    public static $columnasDB = [
        'libro_id',
        'persona_prestado',
        'fecha_prestamo',
        'fecha_devolucion',
        'devuelto'
    ];
    
    public static $idTabla = 'id';
    
    public $id;
    public $libro_id;
    public $persona_prestado;
    public $fecha_prestamo;
    public $fecha_devolucion;
    public $devuelto;
    
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->libro_id = $args['libro_id'] ?? '';
        $this->persona_prestado = $args['persona_prestado'] ?? '';
        $this->fecha_prestamo = $args['fecha_prestamo'] ?? '';
        $this->fecha_devolucion = $args['fecha_devolucion'] ?? null;
        $this->devuelto = $args['devuelto'] ?? 'N';
    }
    
    public static function eliminarPrestamo($id) {
        $sql = "DELETE FROM prestamos WHERE id = $id";
        return self::SQL($sql);
    }
}