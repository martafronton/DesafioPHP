<?php
require_once("./Controllers/administradorController.php");
require_once("./Controllers/partidaController.php");
$adminCtrl = new AdministradorController();
$partidaCtrl=new PartidaController();
$parametros = explode("/", $_SERVER["REQUEST_URI"]);
$d = json_decode(file_get_contents("php://input"), true); 
unset($parametros[0]);
header("Content-Type: application/json; charset=UTF-8");

$respuesta = ["error" => "No se pudo procesar la solicitud"];


    switch ($parametros[1] ?? '') {
        case "admin":
            if ($_SERVER["REQUEST_METHOD"] === "POST" && $parametros[2] === "insertar") {
                $admin = $d["admin"]; 
                $nuevo = $d["nuevo"]; 
                
                if($adminCtrl->esAdmin($admin["email"], $admin["passwd"])) {
                    $id = $adminCtrl->insertarUsuario(
                        $nuevo["rol"], 
                        $nuevo["nombre"], 
                        $nuevo["email"], 
                        $nuevo["passwd"] 
                    );
                    $respuesta = ["success" => true, "id_usuario" => $id];
                    http_response_code(200);
                } else {
                    $respuesta = ["error" => "No autorizado"];
                    http_response_code(401);
                }
            }elseif ($_SERVER["REQUEST_METHOD"] === "POST" && $parametros[2] === "usuarios"){
                if($adminCtrl->esAdmin($d["email"], $d["passwd"])) {
                    $respuesta=$adminCtrl->mostrarUsuarios();
                    http_response_code(200);
                } else {
                    $respuesta = ["error" => "No autorizado"];
                    http_response_code(401);
                }
            }elseif ($_SERVER["REQUEST_METHOD"] === "POST" && $parametros[2] === "eliminar"){
                $admin = $d["admin"]; 
                $eliminado = $d["eliminado"]; 
                
                if($adminCtrl->esAdmin($admin["email"], $admin["passwd"])) {
                    $respuesta=$adminCtrl->borrarUsuario($eliminado["id"]);
                    http_response_code(200);
                } else {
                    $respuesta = ["error" => "No autorizado"];
                    http_response_code(401);
                }
            }
            break;
            
    }
    
    
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    







