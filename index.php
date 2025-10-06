<?php
require_once("./Controllers/administradorController.php");


//Index prueba
$adminCtrl = new AdministradorController();
$parametros = explode("/", $_SERVER["REQUEST_URI"]);

unset($parametros[0]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $adminCtrl->insertarUsuario();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (count($parametros) == 1 && $parametros[1] === "usuarios") { 
        $adminCtrl->mostrarUsuarios();
    } elseif (count($parametros) == 2 && $parametros[1] === "usuarios") {
        $adminCtrl->mostrarUsuario($parametros[2]);
    } else {
        echo json_encode(["error" => "Ruta no encontrada"]);
    }
}


