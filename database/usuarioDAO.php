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
    


    public static function insertUsuario($rol, $nombre, $email, $passwd) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("INSERT INTO usuario(nombre, email, contrasena) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $passwd);
        if(!$stmt->execute()) {
            die("Error insert usuario: " . $stmt->error);
        }
        $id_usuario = $stmt->insert_id;
        $stmt->close();
        $stmt2 = $conexion->prepare("INSERT INTO usuario_rol(id_usuario, id_rol) VALUES(?, ?)");
        $stmt2->bind_param("ii", $id_usuario, $rol);
        if(!$stmt2->execute()) {
            die("Error insert usuario_rol: " . $stmt2->error);
        }
        $stmt2->close();
    
        $conexion->close();
        return $id_usuario;
    }
    
    function esAdmin($email, $passwd) {
        $passwd_md5 = md5($passwd);
    
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("
            SELECT ur.id_rol 
            FROM usuario u 
            JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
            WHERE u.email = ? AND u.contrasena = ?;
        ");
    
        $stmt->bind_param("ss", $email, $passwd_md5);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            if ($row['id_rol'] == 1) {
                $stmt->close();
                $conexion->close();
                return true;
            }
        }
    
        $stmt->close();
        $conexion->close();
        return false;
    }

    function deleteUsuario($id){
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare('DELETE FROM usuario WHERE id_usuario = ?');
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        if (!$resultado) {
            die("Error borrando al usuario: " . $stmt->error);
        }
        $stmt->close();
        return $resultado;
    }
    
    
    
    
}