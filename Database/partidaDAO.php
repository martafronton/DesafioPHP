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

    public static function getPartidas() {
        $conexion = ConexionBBDD::connect();
        $result = $conexion->query("SELECT * FROM partida");
        $partidas = $result->fetch_all(MYSQLI_ASSOC);
        $conexion->close();
        return $partidas;
    }

    public static function getPartida($id) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("SELECT * FROM partida WHERE id_partida = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $partida = $result->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $partida;
    }
}
