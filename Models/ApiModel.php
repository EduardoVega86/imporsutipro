<?php

use Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;

class ApiModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda)
    {
        $response = $this->initialResponse();
        $this->beginTransaction();
        try {

            if ($this->correoExistente($correo)) {
                throw new Exception('El correo ya se encuentra registrado');
            }

            if ($this->tiendaExistente($tienda)) {
                throw new Exception('La tienda ya se encuentra registrada');
            }

            // Registrar usuario
            $registroUsuario = $this->registrarUsuario($nombre, $correo, $contrasena);

            if ($registroUsuario != 1) {
                throw new Exception('Error al registrar el usuario en la plataforma');
            }

            // Registrar tienda
            $registroTienda = $this->registrarTienda($correo, $tienda, $nombre, $telefono, $pais);

            if ($registroTienda != 1) {
                print_r($registroTienda);
                throw new Exception('Error al registrar la tienda: ' . $registroTienda);
            }

            // Obtener id del usuario
            $id_users = $this->getIdUsuario($correo);

            // Obtener id de la plataforma
            $id_plataforma = $this->getIdPlataforma($correo);

            // Registrar perfil
            $registroPerfil = $this->registrarPerfil($tienda, $telefono, $id_plataforma);

            if ($registroPerfil != 1) {
                throw new Exception('Error al registrar el perfil: ' . $registroPerfil);
            }

            // Registrar usuario_plataforma
            $registroUsuarioPlataforma = $this->registrarUsuarioPlataforma($id_users, $id_plataforma);

            if ($registroUsuarioPlataforma != 1) {
                throw new Exception('Error al registrar el usuario en la plataforma: ' . $registroUsuarioPlataforma);
            }

            // Registrar caracteristicas
            $registroCaracteristicas = $this->registrarCaracteristicas($id_plataforma);

            if ($registroCaracteristicas < 1) {
                throw new Exception('Error al registrar las caracteristicas: ' . $registroCaracteristicas);
            }

            // Registrar bodega
            $registroBodega = $this->registrarBodega($tienda, $id_plataforma, $nombre, $telefono);

            if ($registroBodega != 1) {
                throw new Exception('Error al registrar la bodega: ' . $registroBodega);
            }

            $this->commit();
            //$this->enviarCorreo($correo, $nombre);

            $response["title"] = "Registro exitoso";
            $response['status'] = 200;
            $response['message'] = 'Registro exitoso';

            $response2 = $this->registro_imporsuit($correo, $nombre, $tienda, $telefono, $pais, $contrasena);

            return $response2;
        } catch (Exception $e) {
            $this->rollBack();
            $response['status'] = 400;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    public function registro_imporsuit($correo, $nombre, $tienda, $telefono, $pais, $contrasena)
    {
        $data = [
            'nombre' => $nombre,
            'correo' => $correo,
            'telefono' => $telefono,
            'tienda' => $tienda,
            'pais' => $pais,
            'contrasena' => $contrasena
        ];

        $ch = curl_init("https://cursos.imporsuit.app/webhook");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function asignarJWT($email, $jwt, $uuid)
    {
        $sql = "UPDATE users SET jwt = ?, uuid = ? 
         WHERE email_users = ?";
        $data = [$jwt, $uuid, $email];
        return $this->update($sql, $data);
    }

    public function uuid()
    {
        //generar UUID
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function enviarCorreo($correo, $nombre)
    {
        require_once 'PHPMailer/PHPMailer.php';
        require_once 'PHPMailer/SMTP.php';
        require_once 'PHPMailer/Exception.php';
        require_once 'PHPMailer/Mail_landing.php';

        try {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host = $smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $smtp_user;
            $mail->Password = $smtp_pass;
            $mail->Port = 465;
            $mail->SMTPSecure = $smtp_secure;
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($smtp_from, $smtp_from_name);
            $mail->addAddress($correo);
            $mail->Subject = 'Registro exitoso.';
            $mail->Body = $message_body;
            if ($mail->send()) {
                // echo "Correo enviado";
            } else {
                echo "Error al enviar correo";
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    private function correoExistente($correo)
    {
        $sql = "SELECT email_users FROM users WHERE email_users = ?";
        $data = [$correo];
        return $this->dselect($sql, $data);
    }

    private function tiendaExistente($tienda)
    {
        $sql = "SELECT nombre_tienda FROM plataformas WHERE nombre_tienda = ?";
        $data = [$tienda];
        return $this->dselect($sql, $data);
    }

    private function registrarUsuario($nombre, $correo, $contrasena)
    {
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
        $dataAdded = date('Y-m-d H:i:s');
        $sql = "INSERT INTO users (nombre_users, email_users, con_users, usuario_users, date_added, cargo_users) VALUES (?, ?, ?, ?, ?, ?)";
        $data = [$nombre, $correo, $hashedPassword, $correo, $dataAdded, 1];
        return $this->insert($sql, $data);
    }

    private function generarJWTUnico($email, $data)
    {
        $id_plataformaSQL = $this->select("SELECT id_plataforma FROM plataformas WHERE email = '$email'");
        $id_plataforma = $id_plataformaSQL[0]['id_plataforma'];
        $key = $_ENV['JWT_SECRET'];
        $payload = [
            "iat" => time(),
            "exp" => time() + 60 * 60,
            "email" => $email,
            "data" => $data,
            "id_plataforma" => $id_plataforma
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    private function existeJWT($email)
    {
        $sql = "SELECT jwt, uuid FROM users WHERE email_users = ?";
        $data = [$email];
        return $this->dselect($sql, $data);
    }

    private function getIdUsuario($correo)
    {
        $sql = "SELECT id_users FROM users WHERE email_users = ?";
        $data = [$correo];
        return $this->dselect($sql, $data)[0]["id_users"];
    }

    private function getIdPlataforma($correo)
    {
        $sql = "SELECT id_plataforma FROM plataformas WHERE email = ?";
        $data = [$correo];
        return $this->dselect($sql, $data)[0]["id_plataforma"];
    }

    private function registrarTienda($correo, $tienda, $nombre, $telefono, $pais)
    {
        $id_matriz = 1;
        $sql = "INSERT INTO plataformas (`nombre_tienda`, `contacto`, `whatsapp`, `fecha_ingreso`, `fecha_actualza`, `id_plan`, `url_imporsuit`, `carpeta_servidor`, `email`, `referido`, `token_referido`, `refiere`, `pais`, `id_matriz`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$tienda, $nombre, $telefono, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 1, 'https://' . $tienda . 'imporsuit.mx', '/public_html/' . $tienda, $correo, 0, '', '', $pais, $id_matriz];
        return $this->insert($sql, $data);
    }

    private function registrarPerfil($tienda, $telefono, $id_plataforma)
    {
        $sql = "INSERT INTO `perfil` ( `nombre_empresa`,`telefono`, `whatsapp`,  `id_plataforma`) VALUES (?,?,?,?)";
        $data = [$tienda, $telefono, $telefono, $id_plataforma];
        return $this->insert($sql, $data);
    }

    private function registrarUsuarioPlataforma($id_users, $id_plataforma)
    {
        $sql = "INSERT INTO usuario_plataforma (id_usuario, id_plataforma) VALUES (?, ?)";
        $data = [$id_users, $id_plataforma];
        return $this->insert($sql, $data);
    }

    private function registrarCaracteristicas($id_plataforma)
    {
        $sql = "INSERT INTO `caracteristicas_tienda` (`id_producto`, `texto`, `icon_text`, `enlace_icon`, `subtexto_icon`, `accion`, `id_plataforma`) VALUES
        (0, 'Envío Gratis a todo el País', 'fa-check', '', 'Llegamos a todo el País', 1, $id_plataforma),
        (0, 'Pago Contra Entrega', 'fa-lock', NULL, 'Paga cuando recibes el producto', 2, $id_plataforma),
        (0, 'Atención al cliente', 'fa-headset', NULL, 'Soporte 100% garantizado', 2, $id_plataforma);";
        return $this->simple_insert($sql);
    }

    private function registrarBodega($tienda, $id_plataforma, $nombre, $telefono)
    {
        $sql = "INSERT INTO `bodega`(`nombre`,`id_empresa`,`responsable`,`contacto`,`id_plataforma`)VALUES
        ('$tienda',$id_plataforma,'$nombre','$telefono',$id_plataforma);";
        return $this->simple_insert($sql);
    }
}
