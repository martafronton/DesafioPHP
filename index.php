<?php
require_once("./Controllers/administradorController.php");
require_once("./Controllers/partidaController.php");

$adminCtrl = new AdministradorController();
$partidaCtrl = new PartidaController();

$parametros = explode("/", $_SERVER["REQUEST_URI"]);
$d = json_decode(file_get_contents("php://input"), true);
unset($parametros[0]);

header("Content-Type: application/json; charset=UTF-8");

$respuesta = ["error" => "ruta no encontrada"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ----- ADMIN -----
    if (($parametros[1] ?? '') === "admin") {

        // Insertar usuario nuevo
        if (($parametros[2] ?? '') === "insertar") {
            $admin = $d["admin"] ?? null;
            $nuevo = $d["nuevo"] ?? null;

            if ($admin && $nuevo && $adminCtrl->esAdmin($admin["email"], $admin["passwd"])) {
                $resultado = $adminCtrl->insertarUsuario(
                    $nuevo["rol"],
                    $nuevo["nombre"],
                    $nuevo["email"],
                    $nuevo["passwd"]
                );

                if ($resultado === "duplicado") {
                    $respuesta = ["error" => "El correo ya existe"];
                    http_response_code(409);
                } elseif ($resultado === false) {
                    $respuesta = ["error" => "Error al insertar el usuario"];
                    http_response_code(500);
                } else {
                    $respuesta = ["success" => true, "id_usuario" => $resultado];
                    http_response_code(201);
                }
            } else {
                $respuesta = ["error" => "No autorizado"];
                http_response_code(401);
            }

        // Mostrar usuarios
        } elseif (($parametros[2] ?? '') === "usuarios") {
            $admin = $d ?? null;

            if ($admin && $adminCtrl->esAdmin($admin["email"], $admin["passwd"])) {
                $respuesta = $adminCtrl->mostrarUsuarios();
                if (isset($respuesta["error"])) {
                    http_response_code(404);
                } else {
                    http_response_code(200);
                }
            } else {
                $respuesta = ["error" => "No autorizado"];
                http_response_code(401);
            }

        // Eliminar usuario
        } elseif (($parametros[2] ?? '') === "eliminar") {
            $admin = $d["admin"] ?? null;
            $eliminado = $d["eliminado"] ?? null;

            if ($admin && $eliminado && $adminCtrl->esAdmin($admin["email"], $admin["passwd"])) {
                $respuesta = $adminCtrl->borrarUsuario($eliminado["id"]);
                if (isset($respuesta["error"])) {
                    http_response_code(404); 
                } else {
                    http_response_code(200);
                }
            } else {
                $respuesta = ["error" => "No autorizado"];
                http_response_code(401);
            }
        } elseif (($parametros[2] ?? '') === "rol") {
            $admin = $d["admin"] ?? null;
            $id_usuario = $d["id_usuario"] ?? null;
            $nuevo_rol = $d["nuevo_rol"] ?? null;

            if ($admin && $id_usuario && $nuevo_rol && $adminCtrl->esAdmin($admin["email"], $admin["passwd"])) {
                $respuesta = $adminCtrl->cambiarRol($id_usuario, $nuevo_rol);
                if (isset($respuesta["error"])) {
                    http_response_code(404); 
                } else {
                    http_response_code(200);
                }
            } else {
                $respuesta = ["error" => "No autorizado"];
                http_response_code(401);
            }
        }

    // ----- PARTIDAS -----
    }elseif (($parametros[1] ?? '') === "gamer") {
        if (($parametros[2] ?? '') === "partidas") {
            if (count($parametros) == 2) {
                $respuesta = $partidaCtrl->mostrarPartidas($d["email"], $d["passwd"]);
                if (isset($respuesta["error"])) {
                    http_response_code(401);
                } else {
                    http_response_code(200);
                }
            } else {
                $respuesta = ["error" => "Solicitud incorrecta"];
                http_response_code(400);
            }

        // ----- CREAR PARTIDA -----
        } elseif (($parametros[2] ?? '') === "partida") {
            $usuario = $d["usuario"] ?? null;
            $partida = $d["partida"] ?? null;

            if (!$usuario || !$partida) {
                $respuesta = ["error" => "Faltan parámetros obligatorios"];
                http_response_code(400);
            } else {
                $email = $usuario["email"] ?? null;
                $passwd = $usuario["passwd"] ?? null;
                $tipo = $partida["tipo"] ?? null;
                $tamanio = $partida["tamanio"] ?? 20;

                if (!$email || !$passwd || !$tipo) {
                    $respuesta = ["error" => "Faltan datos para crear la partida"];
                    http_response_code(400);
                } else {
                    $partida = $partidaCtrl->crearPartida($email, $passwd, $tipo, $tamanio);
                    $respuesta = [ "partida" => $partida];
                    if (isset($respuesta["partida"]["error"])) {
                        http_response_code(403);
                    } else {
                        http_response_code(201);
                    }
                }
            }

        // ----- DESTAPAR CASILLA -----
        } elseif (($parametros[2] ?? '') === "destapar") {
            $usuario = $d["usuario"] ?? null;
            $movimiento = $d["movimiento"] ?? null;

            if ($usuario && $movimiento) {
                $email = $usuario["email"] ?? null;
                $passwd = $usuario["passwd"] ?? null;
                $id_partida = $movimiento["id_partida"] ?? null;
                $posicion = $movimiento["posicion"] ?? null;

                if ($email && $passwd && $id_partida && $posicion !== null) {
                    $respuesta = $partidaCtrl->destaparCasilla($email, $passwd, $id_partida, $posicion);
                    if (isset($respuesta["error"])) {
                        http_response_code(403); 
                    } else {
                        http_response_code(200);
                    }
                } else {
                    $respuesta = ["error" => "Faltan parámetros para destapar la casilla"];
                    http_response_code(400);
                }
            } else {
                $respuesta = ["error" => "Faltan datos del usuario o movimiento"];
                http_response_code(400);
            }

        // ----- RENDIRSE -----
        } elseif (($parametros[2] ?? '') === "rendirse") {
            $email = $d["email"] ?? null;
            $passwd = $d["passwd"] ?? null;
            $id_partida = $d["id_partida"] ?? null;

            if ($email && $passwd && $id_partida) {
                $respuesta = $partidaCtrl->rendirse($email, $passwd, $id_partida);
                if (isset($respuesta["partida"]["error"])) {
                    http_response_code(403);                
                } else {
                    http_response_code(200);
                }
            } else {
                $respuesta = ["error" => "Faltan parámetros para rendirse"];
                http_response_code(400);
            }
    }elseif (($parametros[1] ?? '') === "user") {


    // ----- RESTABLECER PASSWORD -----
    
        if (($parametros[2] ?? '') === "restablecer") {
            $email = $d["email"] ?? null;

            if ($email) {
                $respuesta = $adminCtrl->restablecerPassword($email);
                if (isset($respuesta["error"])) {
                    http_response_code(404);
                } else {
                    http_response_code(200);
                };
            } else {
                $respuesta = ["error" => "Falta el email"];
                http_response_code(400);
            }
        } elseif (($parametros[2] ?? '') === "nombre") {
            $email = $d["email"] ?? null;
            $passwd = $d["passwd"] ?? null;
            $nuevo_nombre = $d["nuevo_nombre"] ?? null;
            if ($email && $passwd) {
                $respuesta = $adminCtrl->cambiarNombre($email, $passwd, $nuevo_nombre);
                if (isset($respuesta["error"])) {
                    http_response_code(403); 
                } else {
                    http_response_code(200);
                }
            } else {
                $respuesta = ["error" => "Faltan parámetros para obtener el nombre"];
                http_response_code(400);
            }
        } elseif (($parametros[2] ?? '') === "password") {
            $email = $d["email"] ?? null;
            $passwd = $d["passwd"] ?? null;
            $nuevo_passwd = $d["nuevo_passwd"] ?? null;
            if ($email && $passwd && $nuevo_passwd) {
                $respuesta = $adminCtrl->cambiarPassword($email, $passwd, $nuevo_passwd);
                if (isset($respuesta["error"])) {
                    http_response_code(403);
                } else {
                    http_response_code(200);
                }
            } else {
                $respuesta = ["error" => "Faltan parámetros para cambiar la contraseña"];
                http_response_code(400);
            }
        }
    }
}

} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (($parametros[1] ?? '') === "gamer") {
        // ----- MOSTRAR PARTIDA -----
        if (($parametros[2] ?? '') === "partida" && isset($parametros[3])) {
            $email = $d["email"] ?? null;
            $passwd = $d["passwd"] ?? null;
            $id_partida = intval($parametros[3]);

            if ($email && $passwd) {
                $respuesta = $partidaCtrl->mostrarPartida($id_partida, $email, $passwd);
                if (isset($respuesta["partida"]["error"])) {
                    http_response_code(403); 
                } else {
                    http_response_code(200);
                }
            } else {
                $respuesta = ["error" => "Faltan parámetros para mostrar la partida"];
                http_response_code(400);
            }
        }
    }
}
if ($respuesta === ["error" => "ruta no encontrada"]) {
    http_response_code(404);
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    







