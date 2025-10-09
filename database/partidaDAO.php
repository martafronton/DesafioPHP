<?php
require_once("./Database/conexion.php");

class PartidaDAO {

    public static function insertPartida($id_usuario, $tipo, $numCasillas = 20) {
        $conexion = ConexionBBDD::connect();

        $stmt = $conexion->prepare("INSERT INTO partida (id_usuario, tipo) VALUES (?, ?)");
        $stmt->bind_param("is", $id_usuario, $tipo);
        $stmt->execute();
        $id_partida = $stmt->insert_id;

        $stmt->close();

        for ($i = 1; $i <= $numCasillas; $i++) {
            $tipo_prueba= self::generarPrueba();
            $esfuerzo = self::generarEsfuerzo();
            $estado = 'oculta';

            $stmtCasilla = $conexion->prepare(
                "INSERT INTO casilla (id_partida, posicion, tipo_prueba, esfuerzo, estado) VALUES (?, ?, ?, ?, ?)"
            );
            $stmtCasilla->bind_param("iisis", $id_partida, $i, $tipo_prueba, $esfuerzo, $estado);
            $stmtCasilla->execute();
            $stmtCasilla->close();
        }
        $heroes_iniciales = [
            ["nombre" => "Gandalf", "habilidad" => "magia", "capacidad" => 50],
            ["nombre" => "Thorin",  "habilidad" => "fuerza", "capacidad" => 50],
            ["nombre" => "Bilbo",   "habilidad" => "habilidad", "capacidad" => 50],
        ];
    
        foreach ($heroes_iniciales as $h) {
            $stmtH = $conexion->prepare(
                "INSERT INTO personaje (nombre, tipo_prueba, capacidad_max, id_partida) VALUES (?, ?, ?, ?)"
            );
            $stmtH->bind_param("ssii", $h["nombre"], $h["habilidad"], $h["capacidad"], $id_partida);
            $stmtH->execute();
            $stmtH->close();
        }
        $conexion->close();
        return $id_partida;
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

    public static function getPartidas($email, $passwd) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("
            SELECT p.* 
            FROM partida p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE u.email = ? AND u.contrasena = ?
        ");
        $stmt->bind_param("ss", $email, md5($passwd));
        $stmt->execute();
        $res = $stmt->get_result();
        $partidas = $res->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        $conexion->close();
    
        return $partidas;
    }
    

    public static function getPartida($idPartida, $email) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("
            SELECT p.* 
            FROM partida p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE p.id_partida=? AND u.email=?
        ");
        $stmt->bind_param("is", $idPartida, $email);
        $stmt->execute();
        $partida = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $partida;
    }
    public static function destaparCasilla($id_partida, $posicion) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("SELECT * FROM casilla WHERE id_partida = ? AND posicion = ?");
        $stmt->bind_param("ii", $id_partida, $posicion);
        $stmt->execute();
        $res = $stmt->get_result();
        $casilla = $res->fetch_assoc();
        $stmt->close();
    
        if (!$casilla) {
            $conexion->close();
            return false;
        }
    
        $stmt = $conexion->prepare("UPDATE casilla SET estado = 'descubierta' WHERE id_partida = ? AND posicion = ?");
        $stmt->bind_param("ii", $id_partida, $posicion);
        $stmt->execute();
        $stmt->close();

        
    
        $conexion->close();
        return $casilla;
    }


    public static function getCasilla($id_partida, $posicion) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("SELECT tipo_prueba, esfuerzo FROM casilla WHERE id_partida = ? AND posicion = ?");
        $stmt->bind_param("ii", $id_partida, $posicion);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $res;
    }
    
    public static function getPersonajesPorPartida($id_partida) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("SELECT nombre, tipo_prueba, capacidad_max FROM personaje WHERE id_partida = ?");
        $stmt->bind_param("i", $id_partida);
        $stmt->execute();
        $res = $stmt->get_result();
    
        $personajes = [];
        require_once("./Models/personaje.php");
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
    
    public static function marcarCasillaDestapada($id_partida, $posicion) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("UPDATE casilla SET estado = 'destapada' WHERE id_partida = ? AND posicion = ?");
        $stmt->bind_param("ii", $id_partida, $posicion);
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

 


    
    public static function rendirse($email, $passwd, $id_partida) {
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare("
            SELECT p.id_partida 
            FROM partida p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE u.email = ? AND u.contrasena = ? AND p.id_partida = ?
        ");
        $stmt->bind_param("ssi", $email, md5($passwd), $id_partida);
        $stmt->execute();
        $res = $stmt->get_result();
    
        if ($res->num_rows === 0) {
            $stmt->close();
            $conexion->close();
            return ["error" => "Usuario o partida no encontrada"];
        }
        $stmt->close();
    
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
            SET estado = 'descubierta' 
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
    
    
    
    
    
}
