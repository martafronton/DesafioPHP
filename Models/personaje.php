<?php

class Personaje {
    private $id;
    private $nombre;
    private $habilidad;
    private $capacidad;

    public function __construct($nombre, $habilidad, $capacidad = 50) {
        $this->nombre = $nombre;
        $this->habilidad = $habilidad;
        $this->capacidad = $capacidad;
    }

    public function enfrentarPrueba($esfuerzo) {
        if ($this->capacidad <= 0) {
            return false; 
        }

        $azar = rand(1, 10);
        $exito = false;

        if ($this->capacidad > $esfuerzo) {
            if ($azar > 1) $exito = true;
        } elseif ($this->capacidad == $esfuerzo) {
            if ($azar > 3) $exito = true;
        } else {
            if ($azar > 5) $exito = true; 
        }

        if ($exito) {
            $this->capacidad -= $esfuerzo;
        } else {
            $this->capacidad = 0; 
        }

        return $exito;
    }

    public function getCapacidad() {
        return $this->capacidad;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getHabilidad() {
        return $this->habilidad;
    }
}
