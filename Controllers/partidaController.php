<?php
require_once("./Database/partidaDAO.php");

class PartidaController {
    private $partidaDAO;

    public function __construct() {
        $this->partidaDAO = new PartidaDAO();
    }

    public function crearPartida() {
        $data = json_decode(file_get_contents("php://input"), true);
        $tipo = $data["tipo"];
        $id_usuario = $data["id_usuario"];
        $numCasillas = 20;
        if ($tipo === "personalizada" && isset($data["num_casillas"])) {
            $numCasillas = (int)$data["num_casillas"];
        }
        $id = $this->partidaDAO->insertPartida($id_usuario, $tipo, $numCasillas);
        echo json_encode([
            "mensaje" => "Iniciando partida",
            "id_partida" => $id,
            "tipo" => $tipo
        ]);
    }

    public function mostrarPartidas() {
        echo json_encode($this->partidaDAO->getPartidas());
    }

    public function mostrarPartida($id) {
        echo json_encode($this->partidaDAO->getPartida($id));
    }
}
