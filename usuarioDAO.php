<?php

include_once('./Database/conexion.php');
include_once('./Models/usuario.php');
include_once('./Models/partida.php');

class UsuarioDAO {
    public static function getUsuarios() {
        $conexion = ConexionBBDD::connect();
        $query = "SELECT * FROM usuario";
        $result = $conexion->query($query);
    
        $usuarios = [];
        while ($fila = $result->fetch_assoc()) {
            $usuarios[] = $fila;
        }
    
        $conexion->close();
        return $usuarios;
    }
    

    public static function getUsuario($id) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
    
        $usuario = null;
        if ($fila = $res->fetch_assoc()) {
            $usuario = [
                "id" => $fila["id_usuario"],
                "nombre" => $fila["nombre"],
                "email" => $fila["email"],
                "contrasena" => $fila["contrasena"]
            ];
        }
    
        $stmt->close();
        $conexion->close();
        return $usuario;
    }
    


    public static function insertUsuario($nombre, $email, $passwd) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("INSERT INTO usuario(nombre, email, contrasena) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $passwd);
        $stmt->execute();
        $id = $stmt->insert_id;

        $stmt->close();
        $conexion->close();
        return $id;
    }
}