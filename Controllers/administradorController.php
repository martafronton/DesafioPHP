<?php

require_once("./Database/usuarioDAO.php");
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';



class AdministradorController {
    public function mostrarUsuarios() {
        return(UsuarioDAO::getUsuarios());
    }

    public function mostrarUsuario($id) {
        $usuario =UsuarioDAO::getUsuario($id);
    
        if ($usuario) {
            return($usuario);
        } else {
            return(["error" => "No existe"]);
        }
    }
    


    public function insertarUsuario($rol, $nombre, $email, $passwd) {
        return UsuarioDAO::insertUsuario($rol, $nombre, $email, md5($passwd));
    }
    
    public function esAdmin($email, $passwd){
        return UsuarioDAO::esAdmin($email, $passwd);
    }
    public static function borrarUsuario($id){
        $respuesta = UsuarioDAO::deleteUsuario($id);
        if ($respuesta === "noexiste") {
            return ["error" => "El usuario no existe"];
        } elseif ($respuesta) {
            return ["mensaje" => "Usuario borrado correctamente"];
        } else {
            return ["error" => "No se ha podido borrar al usuario"];
        }
    }   

    public function restablecerPassword($email) {
    
        $usuario = UsuarioDAO::getUsuarioPorEmail($email);
        if (!$usuario) {
            return(["error" => "No existe ningún usuario con ese correo"]);
        }
    
         $nuevaPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
        $encriptada = md5($nuevaPassword);
    

        $resultado = UsuarioDAO::actualizarPassword($email, $encriptada);
        if (!$resultado) {
            return(["error" => "No se ha podido actualizar la contraseña."]);
        }
    

        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer();
    
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dawmarta2@gmail.com'; 
            $mail->Password   = 'jueh somz dups ydmf';        
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
    
            $mail->setFrom('dawmarta2@gmail.com', 'Administrador');
            $mail->addAddress('martafronton@gmail.com', $usuario['nombre']);
    
            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de contraseña';
            $mail->Body    = "
                <p>Hola <b>{$usuario['nombre']}</b>,</p>
                <p>Tu nueva contraseña temporal es:</p>
                <h3>{$nuevaPassword}</h3>
                <p>Por seguridad, cámbiala cuando inicies sesión.</p>
            ";
            $mail->AltBody = "Tu nueva contraseña temporal es: {$nuevaPassword}";
    
            $mail->send();
            return(["mensaje" => "Se ha enviado una nueva contraseña al correo."]);
    
        } catch (Exception $e) {
                return (["error" => "No se pudo enviar el mensaje. Error: " . $mail->ErrorInfo]);
        }
    }
    
    public function cambiarNombre($email, $passwd, $nuevoNombre) {
        $id_usuario =UsuarioDAO::validarUsuario($email, $passwd);
        if (!$id_usuario) {
            return(["error" => "Usuario o contraseña incorrectos"]);
        }
    
        $resultado = UsuarioDAO::cambiarNombre($id_usuario, $nuevoNombre);
        if ($resultado) {
            return(["mensaje" => "Nombre actualizado correctamente"]);
        } else {
            return(["error" => "No se pudo actualizar el nombre"]);
        }
    }

    public function cambiarPassword($email, $passwd, $nuevoPassword) {
        $id_usuario =UsuarioDAO::validarUsuario($email, $passwd);
        if (!$id_usuario) {
            return(["error" => "Usuario o contraseña incorrectos"]);
        }
    
        $hash = md5($nuevoPassword);
        $resultado = UsuarioDAO::actualizarPassword($email, $hash);
        if ($resultado) {
            return(["mensaje" => "Contraseña actualizada correctamente"]);
        } else {
            return(["error" => "No se pudo actualizar la contraseña"]);
        }
    }

    public  function cambiarRol($id_usuario, $nuevoRol) {
        $resultado = UsuarioDAO::cambiarRol($id_usuario, $nuevoRol);
        if ($resultado) {
            return(["mensaje" => "Rol actualizado correctamente"]);
        } else {
            return(["error" => "No se pudo actualizar el rol"]);
        }
    }
}

