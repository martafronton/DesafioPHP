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


    public function insertarUsuario($rol, $nombre, $email, $passwd) {
        return $this->usuarioDAO->insertUsuario($rol, $nombre, $email, md5($passwd));
    }
    
    public function esAdmin($email, $passwd){
        return $this->usuarioDAO->esAdmin($email, $passwd);
    }
    public function borrarUsuario($id){
        $usuario = $this->usuarioDAO->deleteUsuario($id);
        echo $usuario
            ? json_encode(["mensaje" => "Usuario borrado correctamente"])
            : json_encode(["error" => "No se ha podido borrar al usuario"]);
    }
    
}
