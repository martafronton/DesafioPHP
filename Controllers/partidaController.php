<?php
require_once("./Database/partidaDAO.php");

class PartidaController {
    private $partidaDAO;

    public function __construct() {
        $this->partidaDAO = new PartidaDAO();
    }

    public function crearPartida($id_usuario, $tipo, $numCasillas=20) {
        $id = $this->partidaDAO->insertPartida($id_usuario, $tipo, $numCasillas);
        echo json_encode([
            "mensaje" => "Iniciando partida",
            "id_partida" => $id,
            "tipo" => $tipo
        ]);
    }

    public function mostrarPartidas($email, $passwd) {
        echo json_encode($this->partidaDAO->getPartidas($email, $passwd));
    }

    public function mostrarPartida($id) {
        echo json_encode($this->partidaDAO->getPartida($id));
    }

    public function destaparCasilla($id_partida, $posicion) {
        $casilla = $this->partidaDAO->getCasilla($id_partida, $posicion);
        if (!$casilla) return json_encode(["error" => "Casilla no encontrada"]);
    
        $personajes = $this->partidaDAO->getPersonajesPorPartida($id_partida);
        if(!$personajes) return json_encode(["error"=> "No se encuentran los personajes"]);
    
        foreach ($personajes as $p) {
            if ($p->getHabilidad() === $casilla["tipo_prueba"]) {
                $resultado = $p->enfrentarPrueba($casilla["esfuerzo"]);
                $this->partidaDAO->actualizarCapacidadPersonaje($p, $id_partida);
                $this->partidaDAO->marcarCasillaDestapada($id_partida, $posicion);
                if(!$resultado){
                    $this->partidaDAO->sumarIntentos($id_partida);
                }
    
                return json_encode([
                    "personaje" => $p->getNombre(),
                    "resultado" => $resultado ? "superada" : "fallada",
                    "capacidad_restante" => $p->getCapacidad(),
                    "intentos" => $this->partidaDAO->getIntentos($id_partida)
                ]);
            }
        }
    }
    public function rendirse($email, $passwd, $id_partida) {
        echo json_encode($this->partidaDAO->rendirse($email, $passwd, $id_partida));
    }
    
}
