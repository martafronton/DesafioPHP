<?php
include_once ("./Database/usuarioDAO.php");



class AdministradorController {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function mostrarUsuarios() {
        echo json_encode($this->usuarioDAO->getUsuarios());
    }

    public function mostrarUsuario($id) {
        $usuario = $this->usuarioDAO->getUsuario($id);
        echo $usuario ? json_encode($usuario) : json_encode(["error" => "No existe"]);
    }


    public function insertarUsuario($rol, $nombre, $email, $passwd) {
        return $this->usuarioDAO->insertUsuario($rol, $nombre, $email, md5($passwd));
    }
    
    public function esAdmin($email, $passwd){
        return $this->usuarioDAO->esAdmin($email, $passwd);
    }
    public function borrarUsuario($id){
        $usuario = $this->usuarioDAO->deleteUsuario($id);
        echo $usuario
            ? json_encode(["mensaje" => "Usuario borrado correctamente"])
            : json_encode(["error" => "No se ha podido borrar al usuario"]);
    }

    public function restablecerPassword($email) {
        require_once 'phpmailer/src/Exception.php';
        require_once 'phpmailer/src/PHPMailer.php';
        require_once 'phpmailer/src/SMTP.php';
    
        $usuario = $this->usuarioDAO->getUsuarioPorEmail($email);
        if (!$usuario) {
            echo json_encode(["error" => "No existe ningún usuario con ese correo"]);
            return;
        }
    
         $nuevaPass = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
        $hash = md5($nuevaPassword);
    

        $resultado = $this->usuarioDAO->actualizarPassword($email, $hash);
        if (!$resultado) {
            echo json_encode(["error" => "No se ha podido actualizar la contraseña."]);
            return;
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
                <h3>{$nuevaPass}</h3>
                <p>Por seguridad, cámbiala cuando inicies sesión.</p>
            ";
            $mail->AltBody = "Tu nueva contraseña temporal es: {$nuevaPass}";
    
            $mail->send();
            echo json_encode(["mensaje" => "Se ha enviado una nueva contraseña al correo."]);
    
        } catch (Exception $e) {
            echo json_encode(["error" => "No se pudo enviar el mensaje. Error: " . $mail->ErrorInfo]);
        }
    }
}

