<?php
class Partida {
    private $id;
    private $idUsuario;
    private $tipo;
    private $estado; 
    private $fechaInicio;
    private $fechaFin;
    private $heroes;  

    public function __construct($id, $idUsuario, $tipo, $estado, $fechaInicio, $fechaFin, $tablero) {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->tipo = $tipo;
        $this->estado = $estado;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->tablero = $tablero;
        $this->heroes = [];
    }

    

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     */
    public function setIdUsuario($idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of fechaInicio
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set the value of fechaInicio
     */
    public function setFechaInicio($fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get the value of fechaFin
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set the value of fechaFin
     */
    public function setFechaFin($fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get the value of heroes
     */
    public function getHeroes()
    {
        return $this->heroes;
    }

    /**
     * Set the value of heroes
     */
    public function setHeroes($heroes): self
    {
        $this->heroes = $heroes;

        return $this;
    }
}

class BuilderPartida{
    private $id;
    private $idUsuario;
    private $estado; 
    private $fechaInicio;
    private $tablero; 
    private $heroes; 


    public function build($idUsuario, $estado = "en curso", $tablero = [], $heroes = []) {
        $this->idUsuario = $idUsuario;
        $this->estado = $estado;
        $this->fechaInicio = date('Y-m-d H:i:s');
        $this->tablero = $tablero;
        $this->heroes = $heroes;
    }

    public static function Builder(){
        return new builderPartida();
    } 

    /**
     * Get the value of estado
     */ 
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */ 
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get the value of fechaFin
     */ 
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set the value of fechaFin
     *
     * @return  self
     */ 
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get the value of tablero
     */ 
    public function getTablero()
    {
        return $this->tablero;
    }

    /**
     * Set the value of tablero
     *
     * @return  self
     */ 
    public function setTablero($tablero)
    {
        $this->tablero = $tablero;

        return $this;
    }

    /**
     * Get the value of heroes
     */
    public function getHeroes()
    {
        return $this->heroes;
    }

    /**
     * Set the value of heroes
     */
    public function setHeroes($heroes): self
    {
        $this->heroes = $heroes;

        return $this;
    }
}