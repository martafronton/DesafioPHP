<?php

include_once('./Database/conexion.php');
include_once('./Model/usuario.php');


class PartidaDAO {
public function crear($idUsuario) {
       
        $partida = new Partida($idUsuario, [], 'SIGUES_VIVO', 0);
        $partida->iniciarTablero($tamanio); 
      
        $tableroStr = implode("", $partida->tablero);

        $conexion = ConexionBBDD::connect();
        $insert = "INSERT INTO partida (id_usuario, tablero, estado, intentos) 
                   VALUES ($idUsuario, '$tableroStr', 'SIGUES_VIVO', 0)";
        $conexion->query($insert);
        $idPartida = $conexion->insert_id;
        $conexion->close();

        return ["mensaje" => "Partida iniciada", "idPartida" => $idPartida, "tablero" => $partida->tablero];
    }
}