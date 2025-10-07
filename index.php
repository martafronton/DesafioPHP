<?php
require_once("./Controllers/administradorController.php");
require_once("./Controllers/partidaController.php");
$adminCtrl = new AdministradorController();
$partidaCtrl=new PartidaController();
$parametros = explode("/", $_SERVER["REQUEST_URI"]);

unset($parametros[0]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $partidaCtrl->crearPartida();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (count($parametros) == 1 && $parametros[1] === "usuarios") { 
        $adminCtrl->mostrarUsuarios();
    } elseif (count($parametros) == 2 && $parametros[1] === "usuario") {
        $adminCtrl->mostrarUsuario($parametros[2]);
    } elseif (count($parametros) == 1 && $parametros[1] === "partidas") { 
        $partidaCtrl->mostrarPartidas();
    } elseif (count($parametros) == 2 && $parametros[1] === "partida") {
        $partidaCtrl->mostrarPartida($parametros[2]);
    } else {
        echo json_encode(["error" => "Ruta no encontrada"]);
    }
}






