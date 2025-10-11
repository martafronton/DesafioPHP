<?php
require_once("./Database/partidaDAO.php");
require_once("./Helper/constantes.php");

class PartidaController {
    private $partidaDAO;

    public function __construct() {
        $this->partidaDAO = new PartidaDAO();
    }

    public function crearPartida($email, $passwd, $tipo, $numCasillas = 20) {
        $mensaje="";
        if ($tipo === "estandar") {
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
            echo json_encode(["error" => "Tipo de partida no válido"]);
            return;
        }
        $resultado = $this->partidaDAO->insertPartida($email, $passwd, $tipo, $numCasillas);
        if (is_array($resultado) && isset($resultado["error"])) {
            echo json_encode($resultado);
            return;
        }
    
        $id_partida = $resultado;
        $mensaje = $ajusteMensaje . "Partida creada correctamente";
    
        echo json_encode([
            "mensaje" => $mensaje,
            "id_partida" => $id_partida,
            "tipo" => $tipo,
            "Casillas" => $numCasillas
        ]);
    }
    

    public function mostrarPartidas($email, $passwd) {
        $id_usuario = $this->partidaDAO->validarUsuario($email, $passwd);
        if(!$id_usuario) {
            echo json_encode(["error" => "Usuario o contraseña incorrectos"]);
            return;
        }
    echo json_encode($this->partidaDAO->getPartidas( $id_usuario));
    
    }

    public function mostrarPartida($id_partida, $email, $passwd) {
        $id_usuario = $this->partidaDAO->validarUsuario($email, $passwd);
        if(!$id_usuario) {
            echo json_encode(["error" => "Usuario o contraseña incorrectos"]);
            return;
        }
    echo json_encode($this->partidaDAO->getPartida($id_partida, $id_usuario));
    }

    public function destaparCasilla($email, $passwd, $id_partida, $posicion) {
        $estado= $this->partidaDAO->comprobarEstadoPartida($id_partida);
        if( $estado !== "en progreso") {
            return json_encode(["error" => "La partida está $estado"]);
        }
        $respuesta = [
            "mensaje" => "",
            "personaje" => null,
            "resultado" => null,
            "capacidad_restante" => null,
            "intentos" => null,
            "estado_partida" => null
        ];
    
        $intentos = $this->partidaDAO->getIntentos($id_partida);
        if($intentos >= Constantes::INTENTOS_MAX) {
            return json_encode(["error" => "Has superado el número máximo de intentos"]);
        }
    
        if(!$this->partidaDAO->comprobarHeroes($id_partida)) {
            return json_encode(["error" => "Te has quedado sin héroes"]);
        }
    
        $casilla = $this->partidaDAO->getCasilla($id_partida, $posicion);
        if (!$casilla) return json_encode(["error" => "Casilla no encontradaa"]);

        $personajes = $this->partidaDAO->getPersonajesPorPartida($id_partida);

    
        
        $heroe_enfrentado = false;
    
        foreach ($personajes as $p) {
            if ($p->getHabilidad() === $casilla["tipo_prueba"]) {
                $resultado = $p->enfrentarPrueba($casilla["esfuerzo"]);
                $estado = $resultado ? "ganada" : "perdida";
    
                $this->partidaDAO->actualizarCapacidadPersonaje($p, $id_partida);
                $this->partidaDAO->marcarCasillaDestapada($id_partida, $posicion, $estado);
    
                if(!$resultado) $this->partidaDAO->sumarIntentos($id_partida);
    
                $respuesta["personaje"] = $p->getNombre();
                $respuesta["resultado"] = $resultado ? "superada" : "fallada";
                $respuesta["capacidad_restante"] = $p->getCapacidad();
                $respuesta["intentos"] = $this->partidaDAO->getIntentos($id_partida);
    
                $heroe_enfrentado = true;
            }
        }
    
        if(!$heroe_enfrentado){
            return json_encode(["error" => "Ningún héroe puede afrontar esta prueba"]);
        }

        $intentos = $this->partidaDAO->getIntentos($id_partida);
        $partida_ganada = $this->partidaDAO->comprobarGanar($id_partida);
        $heroes_vivos = $this->partidaDAO->comprobarHeroes($id_partida);
    
        if($partida_ganada && $heroes_vivos){
            $respuesta["estado_partida"] = "ganada";
            $respuesta["mensaje"] = "¡Has ganado la partida!";
            $this->partidaDAO->resultadoPartida($id_partida, "ganada");
        } elseif(!$heroes_vivos || $intentos >= Constantes::INTENTOS_MAX){
            $respuesta["estado_partida"] = "perdida";
            $respuesta["mensaje"] = "Has perdido la partida";
            $this->partidaDAO->resultadoPartida($id_partida, "perdida");
        } else {
            $respuesta["estado_partida"] = "en progreso";
            $respuesta["mensaje"] = "Movimiento realizado";
        }
    
        return json_encode($respuesta);
    }
    


    public function rendirse($email, $passwd, $id_partida) {
        $id_usuario = $this->partidaDAO->validarUsuario($email, $passwd);
        if(!$id_usuario) {
            echo json_encode(["error" => "Usuario o contraseña incorrectos"]);
            return;
        }
        echo json_encode($this->partidaDAO->rendirse($id_usuario, $id_partida));
    }
    
}
