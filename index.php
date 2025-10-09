<?php
require_once("./Controllers/administradorController.php");
require_once("./Controllers/partidaController.php");

$adminCtrl = new AdministradorController();
$partidaCtrl = new PartidaController();

$parametros = explode("/", $_SERVER["REQUEST_URI"]);
$d = json_decode(file_get_contents("php://input"), true); 
unset($parametros[0]);

header("Content-Type: application/json; charset=UTF-8");

$respuesta = ["error" => "No se pudo procesar la solicitud"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (($parametros[1] ?? '') === "admin") {

        if (($parametros[2] ?? '') === "insertar") {
            $admin = $d["admin"] ?? null; 
            $nuevo = $d["nuevo"] ?? null; 

            if ($admin && $nuevo && $adminCtrl->esAdmin($admin["email"], $admin["passwd"])) {
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

        } elseif (($parametros[2] ?? '') === "usuarios") {
            $admin = $d ?? null;
            if ($admin && $adminCtrl->esAdmin($admin["email"], $admin["passwd"])) {
                $respuesta = $adminCtrl->mostrarUsuarios();
                http_response_code(200);
            } else {
                $respuesta = ["error" => "No autorizado"];
                http_response_code(401);
            }

        } elseif (($parametros[2] ?? '') === "eliminar") {
            $admin = $d["admin"] ?? null; 
            $eliminado = $d["eliminado"] ?? null;

            if ($admin && $eliminado && $adminCtrl->esAdmin($admin["email"], $admin["passwd"])) {
                $respuesta = $adminCtrl->borrarUsuario($eliminado["id"]);
                http_response_code(200);
            } else {
                $respuesta = ["error" => "No autorizado"];
                http_response_code(401);
            }
        }

    } elseif (($parametros[1] ?? '') === "partidas") {
        if (count($parametros) == 1) { 
            $respuesta = $partidaCtrl->mostrarPartidas($d["email"], $d["passwd"]);
        }

    } elseif (($parametros[1] ?? '') === "partida") {
        if (!isset($d["id_usuario"]) || !isset($d["tipo"])) {
            echo json_encode(["error" => "Faltan parámetros obligatorios"]);
            exit;
        }
        $id_usuario = $d["id_usuario"];
        $tipo = $d["tipo"];
        $id_partida = $partidaCtrl->crearPartida($id_usuario, $tipo);
        echo json_encode([
            "mensaje" => "Partida creada correctamente",
            "id_partida" => $id_partida
        ]);
        exit;

    } elseif (($parametros[1] ?? '') === "destapar") {
        $id_partida = $d["id_partida"] ?? null;
        $posicion = $d["posicion"] ?? null;

        if ($id_partida && $posicion !== null) {
            echo $partidaCtrl->destaparCasilla($id_partida, $posicion);
        } else {
            echo json_encode(["error" => "Faltan parámetros para destapar la casilla"]);
        }
        exit;
    } elseif (($parametros[1] ?? '') === "rendirse") {
        $email = $d["email"] ?? null;
        $passwd = $d["passwd"] ?? null;
        $id_partida = $d["id_partida"] ?? null;

        if ($id_partida && $email && $passwd!== null) {
            echo $partidaCtrl->rendirse($email, $passwd, $id_partida);
        } else {
            echo json_encode(["error" => "Faltan parámetros para rendirte"]);
        }
        exit;
    }
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);

    







