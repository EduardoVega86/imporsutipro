<?php
require "Controllers/Pedidos.php";
require "Models/GuiasModel.php";

use Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;

class ApiModel extends Query
{
    private $pedidos;
    private $guiasM;
    public function __construct()
    {
        parent::__construct();
        $this->pedidos = new Pedidos();
        $this->guiasM = new GuiasModel();
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
            $response["log_imporsuit"] = $response2;
            return $response;
        } catch (Exception $e) {
            $this->rollBack();
            $response['status'] = 400;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    public function agregarProducto($data)
    {
        $response = $this->initialResponse();
        try {
            $data_factura = $this->getDataFactura($data['id_factura']);
            if ($data_factura == null) {
                throw new Exception('Factura no encontrada');
            }
            $numero_factura = $data_factura['numero_factura'];
            $id_plataforma = $data_factura['id_plataforma'];
            $responses = $this->agregarProductoBase($data['id_inventario'], $data['id_producto'], $data['sku'], $data['precio'], $data['cantidad'], $data['id_factura'], $numero_factura, $id_plataforma);
            if ($responses != 1) {
                print_r($responses);
                throw new Exception('Error al agregar el producto: ');
            }
            $response['title'] = "Exito";
            $response['status'] = 200;
            $response['message'] = 'Producto agregado correctamente';

            return $response;
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    public function eliminarProducto($data)
    {
        $response = $this->initialResponse();
        try {
            $sql = "DELETE FROM detalle_fact_cot WHERE id_detalle = ?";
            $data = [$data['id_detalle']];
            $responses = $this->delete($sql, $data);
            if ($responses != 1) {
                throw new Exception('Error al eliminar el producto');
            }
            $response['title'] = "Exito";
            $response['status'] = 200;
            $response['message'] = 'Producto eliminado correctamente';
            return $response;
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    private function getDataFactura($id_pedido)
    {
        $sql = "SELECT * FROM facturas_cot WHERE id_factura  = $id_pedido";
        return $this->select($sql)[0] ?? null;
    }

    private function agregarProductoBase($id_inventario, $id_producto, $sku, $precio, $cantidad, $id_factura, $numero_factura, $id_plataforma)
    {
        $sql = "INSERT INTO `detalle_fact_cot` (`id_inventario`, `id_producto`, `sku`, `precio_venta`, `cantidad`, `id_factura`, `numero_factura`, `id_plataforma`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$id_inventario, $id_producto, $sku, $precio, $cantidad, $id_factura, $numero_factura, $id_plataforma];
        return $this->insert($sql, $data);
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

    public function login($correo, $contrasena)
    {
        $response = $this->initialResponse();
        try {
            $usuario = $this->correoExistente($correo);
            if (!$usuario) {
                throw new Exception('El correo no se encuentra registrado');
            }

            if (!password_verify($contrasena, $usuario[0]['con_users'])) {
                throw new Exception('Contraseña incorrecta');
            }

            $jwtSQL = $this->existeJWT($correo)[0];
            if ($jwtSQL['jwt'] != null && $jwtSQL['uuid'] != null) {
                $jwt = $jwtSQL['jwt'];
                $uuid = $jwtSQL['uuid'];
            } else {
                $jwt = $this->generarJWTUnico($correo, $usuario);
                $uuid = $this->uuid();
                $validar = $this->validarUUIDUnico($uuid);
                while ($validar) {
                    $uuid = $this->uuid();
                    $validar = $this->validarUUIDUnico($uuid);
                }
                $this->asignarJWT($correo, $jwt, $uuid);
            }
            $response['title'] = "Exito";
            $response['status'] = 200;
            $response['message'] = 'Inicio de sesión exitoso';
            $response['uuid'] = $uuid;
            return $response;
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    public function pedido($uuid, $datos)
    {
        $response = $this->initialResponse();
        try {
            if ($uuid === null) {
                throw new Exception('Faltan datos requeridos');
            }

            $usuario = $this->validarUUID($uuid);
            if (!$usuario) {
                throw new Exception('UUID no válido');
            }
            $id_usuario = $usuario[0]['id_users'];

            $id_plataforma = $this->getIdPlataforma($usuario[0]['email_users']);
            $response = $this->pedidos->nuevo_pedido_webhook($id_usuario, $datos['monto_factura'], $datos['cliente'], $datos['telefono'], $datos['calle_principal'], $datos['calle_secundaria'], $datos['ciudad'], $datos['provincia'], $datos['referencia'], $datos['observacion'], $datos['id_inventario'], $id_plataforma, $datos['productos'], 1);


            return $response;
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    public function getPedidos($uuid)
    {
        $response = $this->initialResponse();
        try {
            if ($uuid === null) {
                throw new Exception('Faltan datos requeridos');
            }
            $usuario = $this->validarUUID($uuid);
            if (!$usuario) {
                throw new Exception('UUID no válido');
            }
            $id_plataforma = $this->getIdPlataforma($usuario[0]['email_users']);
            $response['title'] = "Exito";

            $response['status'] = 200;
            $response['message'] = 'Pedidos obtenidos';
            $response['data'] = $this->pedidos->cargarPedidos($id_plataforma);

            return $response;
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    private function validarUUID($uuid)
    {
        $sql = "SELECT id_users, uuid, email_users FROM users WHERE uuid = ?";
        $data = [$uuid];
        return $this->dselect($sql, $data);
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

    private function validarUUIDUnico($uuid)
    {
        $sql = "SELECT uuid FROM users WHERE uuid = ?";
        $data = [$uuid];
        $response = $this->dselect($sql, $data);
        if (count($response) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getCiudades($uuid)
    {
        $response = $this->initialResponse();
        try {
            if ($uuid === null) {
                throw new Exception('Faltan datos requeridos');
            }
            $usuario = $this->validarUUID($uuid);
            if (!$usuario) {
                throw new Exception('UUID no válido');
            }

            $sql = "SELECT ciudad, provincia, id_cotizacion as 'codigo_ciudad', codigo_provincia_laar as 'codigo_provincia' FROM ciudad_cotizacion ORDER BY provincia asc;";
            $resultado = $this->select($sql);
            $response['title'] = "Exito";


            $response['status'] = 200;
            $response['message'] = 'Ciudades obtenidas';
            $response['data'] = $resultado;

            return $response;
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    public function obtenerDestinatarioWebhook($id)
    {
        $sql = "SELECT bodega FROM inventario_bodegas WHERE id_inventario = $id";

        $id_platafomra = $this->select($sql);
        $id_platafomra = $id_platafomra[0]['bodega'];

        $sql2 = "SELECT * FROM bodega where id = $id_platafomra";
        $id_platafomra = $this->select($sql2)[0];

        return $id_platafomra;
    }

    private function correoExistente($correo)
    {
        $sql = "SELECT email_users, con_users FROM users WHERE email_users = ?";
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
        $data = [$nombre, $correo, $hashedPassword, $correo, $dataAdded, 15];
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

    public function anularGuias($uuid, $guia, $transportadora, $numero_factura = null)
    {
        $response = $this->initialResponse();
        try {
            $ch = curl_init();
            if ($transportadora == "LAAR") {
                $this->guiasM->anularGuia($guia);
            } else if ($transportadora == "GINTRACOM") {
                // https://guias.imporsuitpro.com/Gintracom/anular/$%7Bnumero_guia%7D
                $url = "https://guias.imporsuitpro.com/Gintracom/anular/$guia";
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
            } else if ($transportadora == "SERVIENTREGA") {
                //https://guias.imporsuitpro.com/Servientrega/Anular/${numero_guia}
                $url = "https://guias.imporsuitpro.com/Servientrega/Anular/$guia";
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
            } else if ($transportadora == "SPEED") {
                /// https://guias.imporsuitpro.com/Speed/anular/$%7Bnumero_guia%7D
                $url = "https://guias.imporsuitpro.com/Speed/anular/$guia";
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
            } else if ($transportadora == "MANUAL") {
                $this->guiasM->anularFactura($numero_factura);
            }
            return $response;
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }
}
