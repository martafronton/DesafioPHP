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

    public static function validarUsuario($email, $passwd) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE email = ? AND contrasena = ?");
        $hash = md5($passwd);
        $stmt->bind_param("ss", $email, $hash);
        $stmt->execute();
        $res = $stmt->get_result();
        $usuario = $res->fetch_assoc();
        $stmt->close();
        return $usuario["id_usuario"] ?? false;
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
    
  
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
    
        if ($res->num_rows > 0) {
            $stmt->close();
            $conexion->close();
            return "duplicado";
        }
        $stmt->close();
    
  
        $stmt = $conexion->prepare("INSERT INTO usuario(nombre, email, contrasena) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $passwd);
    
        if (!$stmt->execute()) {
            $stmt->close();
            $conexion->close();
            return false; 
        }
    
        $id_usuario = $stmt->insert_id;
        $stmt->close();
    

        $stmt2 = $conexion->prepare("INSERT INTO usuario_rol(id_usuario, id_rol) VALUES(?, ?)");
        $stmt2->bind_param("ii", $id_usuario, $rol);
    
        if (!$stmt2->execute()) {
            $stmt2->close();
            $conexion->close();
            return false;
        }
    
        $stmt2->close();
        $conexion->close();
    
        return $id_usuario; 
    }
    
    
    
    public static function esAdmin($email, $passwd) {
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
        $row = $result->fetch_assoc();
            if ($row && $row['id_rol'] == 1) {
                $stmt->close();
                $conexion->close();
                return true;
            }
        
    
        $stmt->close();
        $conexion->close();
        return false;
    }

    public static function deleteUsuario($id){
        $conexion = ConexionBBDD::connect();
    
        $stmt = $conexion->prepare('SELECT * FROM usuario WHERE id_usuario = ?');
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
    
        if ($res->num_rows === 0) {
            return "noexiste";
        }
    
        $stmt = $conexion->prepare('DELETE FROM usuario WHERE id_usuario = ?');
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
    
        return $resultado;
    }
    
    

    public static function getUsuarioPorEmail($email) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
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

    public static function actualizarPassword($email, $password) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("UPDATE usuario SET contrasena = ? WHERE email = ?");
        $stmt->bind_param("ss", $password, $email);
        $resultado = $stmt->execute();
        if (!$resultado) {
            return;
        }
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public static function cambiarNombre($id, $nuevoNombre) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("UPDATE usuario SET nombre = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $nuevoNombre, $id);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public static function cambiarRol($id, $nuevoRol) {
        $conexion = ConexionBBDD::connect();
        $stmt = $conexion->prepare("UPDATE usuario_rol SET id_rol = ? WHERE id_usuario = ?");
        $stmt->bind_param("ii", $nuevoRol, $id);
        $resultado = $stmt->execute();
        if (!$resultado) {
            return;
        }
        $stmt->close();
        $conexion->close();
        return $resultado;
    }
    
    
    
    
}