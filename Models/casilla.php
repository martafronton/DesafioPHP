<?php
class Casilla {
    public $posicion;
    public $tipo_prueba;
    public $esfuerzo;
    public $estado; 

    public function __construct($posicion, $tipo_prueba, $esfuerzo) {
        $this->posicion = $posicion;
        $this->tipo_prueba = $tipo_prueba;
        $this->esfuerzo = $esfuerzo;
        $this->estado = 'oculta';
    }
}