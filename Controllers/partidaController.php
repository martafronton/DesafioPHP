<?php
require_once("./Database/partidaDAO.php");
require_once("./Database/usuarioDAO.php");
require_once("./Helper/constantes.php");

class PartidaController {
   



    public function crearPartida($email, $passwd, $tipo, $numCasillas = 20) {
        $id_usuario = UsuarioDAO::validarUsuario($email, $passwd);
        $mensaje = "";
        $ajusteMensaje = "";
        $partidas= PartidaDAO::getPartidasActivas($id_usuario);
        if(count($partidas) >= Constantes::PARTIDAS_MAX) {
            return(["error" => "Has alcanzado el número máximo de partidas activas"]);
        }else if ($tipo === "estandar") {
            if ($numCasillas != 20) {
                $numCasillas = 20;
                $ajusteMensaje = "El tipo de partida estandar solo puede tener 20 casillas, se ha ajustado el tamaño. ";
            } 
        } elseif ($tipo === "personalizada") {
            if ($numCasillas > 100) {
                $numCasillas = 100;
                $ajusteMensaje = "El número máximo de casillas para partidas personalizadas es 100, se ha ajustado el tamaño. ";
            } elseif ($numCasillas < 1) {
                $numCasillas = 1;
                $ajusteMensaje = "El número mínimo de casillas es 1, se ha ajustado el tamaño. ";
            }
        } else {
            return(["error" => "Tipo de partida no válido"]);
            
        }
        $resultado = PartidaDAO::insertPartida($id_usuario, $tipo, $numCasillas);
        if (is_array($resultado) && isset($resultado["error"])) {
            return($resultado);            
        }
    
        $id_partida = $resultado;
        $mensaje = $ajusteMensaje . "Partida creada correctamente";
    
        return([
            "mensaje" => $mensaje,
            "id_partida" => $id_partida,
            "tipo" => $tipo,
            "Casillas" => $numCasillas
        ]);
    }
    

    public function mostrarPartidas($email, $passwd) {
        $id_usuario =UsuarioDAO::validarUsuario($email, $passwd);
        if(!$id_usuario) {
            return(["error" => "Usuario o contraseña incorrectos"]);
        }
    if(count(PartidaDAO::getPartidas($id_usuario)) === 0) {
        return(["mensaje" => "No tienes partidas activas"]);
    }
    return(PartidaDAO::getPartidas( $id_usuario));
    
    }

    public  function mostrarPartida($id_partida, $email, $passwd) {
        $id_usuario = UsuarioDAO::validarUsuario($email, $passwd);
        if(!$id_usuario) {
            return(["error" => "Usuario o contraseña incorrectos"]);
        }
    $partida=(PartidaDAO::getPartida($id_partida, $id_usuario));
    if($partida==null) {
        return (["error" => "La partida no existe"]);
    }
    return $partida;
}

    public function destaparCasilla($email, $passwd, $id_partida, $posicion) {
        $id_usuario = UsuarioDAO::validarUsuario($email, $passwd);
        if(!$id_usuario) {
            return (["error" => "Usuario o contraseña incorrectos"]);
        }
        $existe = PartidaDAO::getPartida($id_partida, $id_usuario);
        if(!$existe) {
            return (["error" => "La partida no existe"]);
        }
        $estado= PartidaDAO::comprobarEstadoPartida($id_partida);
        if( $estado !== "en_curso") {
            return (["error" => "La partida está $estado"]);
        }
        $respuesta = [
            "mensaje" => "",
            "personaje" => null,
            "resultado" => null,
            "capacidad_restante" => null,
            "intentos" => null,
            "estado_partida" => null
        ];
    
        $intentos = PartidaDAO::getIntentos($id_partida);
        if($intentos >= Constantes::INTENTOS_MAX) {
            return (["error" => "Has superado el número máximo de intentos"]);
        }
    
        if(!PartidaDAO::comprobarHeroes($id_partida)) {
            return (["error" => "Te has quedado sin héroes"]);
        }
    
        $casilla = PartidaDAO::getCasilla($id_partida, $posicion);
        if (!$casilla) return (["error" => "Casilla no encontradaa o ya jugada"]);

        $personajes = PartidaDAO::getPersonajesPorPartida($id_partida);

    
        
        $heroe_enfrentado = false;
    
        foreach ($personajes as $p) {
            if ($p->getHabilidad() === $casilla["tipo_prueba"]) {
                $resultado = $p->enfrentarPrueba($casilla["esfuerzo"]);
                $estado = $resultado ? "ganada" : "perdida";
    
                PartidaDAO::actualizarCapacidadPersonaje($p, $id_partida);
                PartidaDAO::marcarCasillaDestapada($id_partida, $posicion, $estado);
    
                if(!$resultado) PartidaDAO::sumarIntentos($id_partida);
    
                $respuesta["personaje"] = $p->getNombre();
                $respuesta["resultado"] = $resultado ? "superada" : "fallada";
                $respuesta["capacidad_restante"] = $p->getCapacidad();
                $respuesta["intentos"] = PartidaDAO::getIntentos($id_partida);
    
                $heroe_enfrentado = true;
            }
        }
    
        if(!$heroe_enfrentado){
            return (["error" => "Ningún héroe puede afrontar esta prueba"]);
        }

        $intentos = PartidaDAO::getIntentos($id_partida);
        $partida_ganada = PartidaDAO::comprobarGanar($id_partida);
        $heroes_vivos =PartidaDAO::comprobarHeroes($id_partida);
    
        if($partida_ganada && $heroes_vivos){
            $respuesta["estado_partida"] = "ganada";
            $respuesta["mensaje"] = "¡Has ganado la partida!";
            PartidaDAO::resultadoPartida($id_partida, "ganada");
        } elseif(!$heroes_vivos || $intentos >= Constantes::INTENTOS_MAX){
            $respuesta["estado_partida"] = "perdida";
            $respuesta["mensaje"] = "Has perdido la partida";
            PartidaDAO::resultadoPartida($id_partida, "perdida");
        } else {
            $respuesta["estado_partida"] = "en progreso";
            $respuesta["mensaje"] = "Movimiento realizado";
        }
    
        return ($respuesta);
    }
    


    public function rendirse($email, $passwd, $id_partida) {
        $id_usuario = UsuarioDAO::validarUsuario($email, $passwd);
        if(!$id_usuario) {
            return (["error" => "Usuario o contraseña incorrectos"]);
        }
        $partida = PartidaDAO::rendirse($id_usuario, $id_partida);
        if(isset($partida["error"])) {
            return (["error" => $partida["error"]]);
        }
        $heroes  = PartidaDAO::getHeroes($id_partida);
    
        return [
            "partida" => $partida,
            "heroes"  => $heroes
        ];
    }
    
}
