<?php
require_once("./Database/conexion.php");
require_once("./Models/personaje.php");

class PartidaDAO {

    public static function insertPartida($id_usuario, $tipo, $numCasillas) {
        $conexion = ConexionBBDD::connect(); 
        $partidas = self::getPartidas($id_usuario);
        $stmt = $conexion->prepare("INSERT INTO partida (id_usuario, tipo) VALUES (?, ?)");
        $stmt->bind_param("is", $id_usuario, $tipo);
        $stmt->execute();
        $id_partida = $stmt->insert_id;
        $stmt->close();

        self::crearCasillas($id_partida, $numCasillas);
        self::crearPersonajes($id_partida);
        $conexion->close();
        return $id_partida;
    }


    private static function crearCasillas($id_partida, $numCasillas) {
        $conexion = ConexionBBDD::connect();
        for ($i = 1; $i <= $numCasillas; $i++) {
            $tipo_prueba = self::generarPrueba();
            $esfuerzo = self::generarEsfuerzo();
            $estado = 'oculta';
    
            $stmt = $conexion->prepare(
                "INSERT INTO casilla (id_partida, posicion, tipo_prueba, esfuerzo, estado) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("iisis", $id_partida, $i, $tipo_prueba, $esfuerzo, $estado);
            $stmt->execute();
            $stmt->close();
        }
    }

    private static function crearPersonajes($id_partida) {
        $conexion = ConexionBBDD::connect();
        $heroes_iniciales = [
            ["nombre" => "Gandalf", "habilidad" => "magia", "capacidad" => 50],
            ["nombre" => "Thorin",  "habilidad" => "fuerza", "capacidad" => 50],
            ["nombre" => "Bilbo",   "habilidad" => "habilidad", "capacidad" => 50],
        ];
    
        foreach ($heroes_iniciales as $h) {
            $stmt = $conexion->prepare(
                "INSERT INTO personaje (nombre, tipo_prueba, capacidad_max, id_partida) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssii", $h["nombre"], $h["habilidad"], $h["capacidad"], $id_partida);
            $stmt->execute();
            $stmt->close();
        }
    }

    private static function generarPrueba() {
        $tiposPrueba = ['magia', 'fuerza', 'habilidad'];
        return $tiposPrueba[array_rand($tiposPrueba)];
    }

    private static function generarEsfuerzo() {
        $prob = rand(1, 100);
        if ($prob <= 65) {
            $valores = [5,10,15,20];
        } elseif ($prob <= 95) { 
            $valores = [25,30,35,40];
        } else {
            $valores = [45,50];
        }
        return $valores[array_rand($valores)];
    }

    public static function getPartidas($id_usuario) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("
            SELECT p.* 
            FROM partida p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE u.id_usuario = ?
        ");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $partidas = $res->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $conexion->close();
    
        return $partidas;
    }

    public static function getPartidasActivas($id_usuario) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("
            SELECT p.* 
            FROM partida p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE u.id_usuario = ? and p.estado = 'en_curso'
        ");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $partidas = $res->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $conexion->close();
    
        return $partidas;
    }
    

    public static function getPartida($id_partida, $id_usuario) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("
            SELECT p.* 
            FROM partida p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE p.id_partida=? AND u.id_usuario=?
        ");
        $stmt->bind_param("is", $id_partida, $id_usuario);
        $stmt->execute();
        $partida = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $partida;
    }
  
    public static function getCasilla($id_partida, $posicion) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("SELECT tipo_prueba, esfuerzo, estado FROM casilla WHERE id_partida = ? AND posicion = ?");
        $stmt->bind_param("ii", $id_partida, $posicion);
        $stmt->execute();
    
        $res = $stmt->get_result();
        $fila = $res->fetch_assoc();
    
        $stmt->close();
        $conexion->close();
    
        if ($fila && $fila["estado"] === "oculta") {
            return $fila; 
        } else {
            return null;
        }
    }
    
    
    public static function getPersonajesPorPartida($id_partida) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("SELECT nombre, tipo_prueba, capacidad_max FROM personaje WHERE id_partida = ?");
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $res = $stmt->get_result();
    
        $personajes = [];

        while ($row = $res->fetch_assoc()) {
            $personajes[] = new Personaje($row["nombre"], $row["tipo_prueba"], $row["capacidad_max"]);
        }
    
        $stmt->close();
        $conexion->close();
        return $personajes;
    }
    
    public static function actualizarCapacidadPersonaje($p, $id_partida) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("UPDATE personaje SET capacidad_max = ? WHERE nombre = ? AND id_partida = ?");
        $cap = $p->getCapacidad();
        $nom = $p->getNombre();
        $stmt->bind_param("isi", $cap, $nom, $id_partida);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }
    
    public static function marcarCasillaDestapada($id_partida, $posicion, $estado) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("UPDATE casilla SET estado = ? WHERE id_partida = ? AND posicion = ?");
        $stmt->bind_param("sii", $estado, $id_partida, $posicion);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }

    public static function getIntentos($id_partida) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("SELECT intentos FROM partida WHERE id_partida = ?");
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res->fetch_assoc();
        $stmt->close();
        $conexion->close();
    
        return $fila ? (int)$fila["intentos"] : 0;
    }
    
    public static function sumarIntentos($id_partida) {
        $conexion = ConexionBBDD::connect();
        $actuales = self::getIntentos($id_partida);
    
        $nuevoIntento = $actuales + 1;
        $stmt2 = $conexion->prepare("UPDATE partida SET intentos = ? WHERE id_partida = ?");
        $stmt2->bind_param("ii", $nuevoIntento, $id_partida);
        $stmt2->execute();
        $stmt2->close();
        $conexion->close();
    }

 


    
    public static function rendirse($id_usuario, $id_partida) {
        $conexion = ConexionBBDD::connect();
        $estado_partida = self::comprobarEstadoPartida($id_partida);
        if($estado_partida !== "en_curso"){
            return (["error" => "La partida ya ha finalizado"]);
        }
        $stmt2 = $conexion->prepare("
            SELECT posicion, tipo_prueba, esfuerzo, estado 
            FROM casilla 
            WHERE id_partida = ?
        ");
        $stmt2->bind_param("i", $id_partida);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        $mapa = $res2->fetch_all(MYSQLI_ASSOC);
        $stmt2->close();
    
        $stmt3 = $conexion->prepare("
            UPDATE casilla 
            SET estado = 'perdida' 
            WHERE id_partida = ?
        ");
        $stmt3->bind_param("i", $id_partida);
        $stmt3->execute();
        $stmt3->close();
    
        $stmt4 = $conexion->prepare("
            UPDATE partida 
            SET estado = 'rendida' 
            WHERE id_partida = ?
        ");
        $stmt4->bind_param("i", $id_partida);
        $stmt4->execute();
        $stmt4->close();
    
        $conexion->close();
    
        return $mapa;
    }

    public static function getHeroes($id_partida) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("SELECT nombre, tipo_prueba, capacidad_max FROM personaje WHERE id_partida = ?");
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $res = $stmt->get_result();
    
        $heroes = [];
        while ($fila = $res->fetch_assoc()) {
            $heroes[] = $fila;
        }
    
        $stmt->close();
        $conexion->close();
    
        return $heroes;
    }

    public static function eliminarPartida($id_partida) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("DELETE FROM personaje WHERE id_partida = ?");
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $stmt->close();
    
        $stmt2 = $conexion->prepare("DELETE FROM casilla WHERE id_partida = ?");
        $stmt2->bind_param("i", $id_partida);
        $stmt2->execute();
        $stmt2->close();
    
        $stmt3 = $conexion->prepare("DELETE FROM partida WHERE id_partida = ?");
        $stmt3->bind_param("i", $id_partida);
        $stmt3->execute();
        $stmt3->close();
    
        $conexion->close();
    }

    public static function comprobarEstadoPartida($id_partida) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("SELECT estado FROM partida WHERE id_partida = ?");
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res->fetch_assoc();
        $stmt->close();
        $conexion->close();
    
        return  $fila["estado"] ;
    }

    public static function comprobarGanar($id_partida) {
        $conexion = ConexionBBDD::connect();
    

        $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM casilla WHERE id_partida = ?");
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res->fetch_assoc();
        $totalCasillas = (int)$fila['total'];
        $stmt->close();
        
        $stmt = $conexion->prepare("SELECT COUNT(*) AS ganadas FROM casilla WHERE id_partida = ? AND estado = 'ganada'");
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res->fetch_assoc();
        $casillasGanadas = (int)$fila['ganadas'];
        $stmt->close();
        
    
        $conexion->close();
    
        return $casillasGanadas > round($totalCasillas / 2);
    }
    

    public static function comprobarHeroes($id_partida){
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("
            SELECT COUNT(*) as heroes
            FROM personaje
            WHERE id_partida = ? AND capacidad_max > 0
        ");
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res->fetch_assoc();
    
        $stmt->close();
        $conexion->close();
    
        $numHeroesVivos = (int)$fila['heroes'];
        return $numHeroesVivos > 0;
    }
    

    public static function resultadoPartida($id_partida, $estado){
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("UPDATE partida SET estado = ? WHERE id_partida = ?");
        $stmt->bind_param("si", $estado, $id_partida);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }
    
   
    
    
    
    
}
