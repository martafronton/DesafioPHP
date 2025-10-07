<?php
include_once ("./Database/usuarioDAO.php");



class AdministradorController {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function mostrarUsuarios() {
        echo json_encode($this->usuarioDAO->getUsuarios());
    }

    public function mostrarUsuario($id) {
        $usuario = $this->usuarioDAO->getUsuario($id);
        echo $usuario ? json_encode($usuario) : json_encode(["error" => "No existe"]);
    }


    public function insertarUsuario() {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $this->usuarioDAO->insertUsuario($data["nombre"], $data["email"], md5($data["passwd"]));
        echo json_encode(["Insertado correctamente" => $id]);
    }
    
}