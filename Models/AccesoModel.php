<?php
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';
require 'vendor/autoload.php'; // Asumiendo que estás usando Composer
require_once 'Class/MailSender.php';
require_once 'Class/RegisterUser.php';
require_once 'Class/LoginUser.php';

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PHPMailer\PHPMailer\PHPMailer;


/**
 * Class AccesoModel
 * Modelo para la autenticación de usuarios y generación de tokens JWT.
 * @package Models
 * @version
 */
class AccesoModel extends Query
{
    private mixed $jwt_secret;
    private mixed $jwt_expire;
    private JWT $jwt;
    private Key $key;

    /**
     * AccesoModel constructor.
     * Inicializa la conexión a la base de datos y la clave secreta para JWT.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->jwt_secret = $_ENV['JWT_SECRET'];
        $this->jwt_expire = $_ENV['JWT_EXPIRE'];
        $this->jwt = new JWT();
        $this->key = new Key($this->jwt_secret, 'HS256');
    }

    /**
     * Genera un JWT para autenticación del usuario
     *
     * @param array $userData Datos del usuario autenticado.
     * @return string Token JWT generado.
     * @throws Exception Sí falla la generación del token.
     */
    private function generaJWT(array $userData): string
    {
        try {
            if (empty($userData['id_users']) || empty($userData['email_users'])) {
                throw new Exception("Datos de usuario incompletos para generar el token.");
            }

            $issuedAt = time();
            $expirationTime = $issuedAt + $this->jwt_expire;
            $jwtId = bin2hex(random_bytes(16)); // Genera un ID único para el token

            $payload = [
                'iss' => $_SERVER['SERVER_NAME'], // Emisor del token
                'aud' => $_SERVER['SERVER_NAME'], // Audiencia
                'iat' => $issuedAt,  // Fecha de emisión
                'exp' => $expirationTime, // Expira en 1 hora
                'nbf' => $issuedAt,  // No puede usarse antes de este tiempo
                'jti' => $jwtId, // Identificador único del token
                'sub' => $userData['email_users'], // Sujeto del token
                'data' => [  // Datos encapsulados
                    'id' => (int)$userData['id_users'],
                    'nombre' => $userData['nombre_users'] ?? '',
                    'cargo' => $userData['cargo_users'] ?? '',
                    'correo' => $userData['email_users'],
                    'id_plataforma' => $userData['id_plataforma'],
                    'validar_config_chat' => $userData['validar_config_chat']
                ]
            ];

            return $this->jwt->encode($payload, $this->jwt_secret, 'HS256');
        } catch (Exception $e) {
            throw new Exception("Error al generar el token de autenticación: " . $e->getMessage());
        }
    }

    /**
     * Función para registrar un usuario
     * @param $nombre
     * @param $correo
     * @param $pais
     * @param $telefono
     * @param $contrasena
     * @param $tienda
     * @return array
     * @throws Exception
     */
    public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda): array
    {
        try {
            $auth = new RegisterUser($this->getConnection(), $nombre, $correo, $pais, $telefono, $contrasena, $tienda, 0, 1);

            $auth->register_user();
            $this->getResponse()['status'] = 200;
            $this->getResponse()['title'] = 'Petición exitosa';
            $this->getResponse()['message'] = 'Usuario registrado correctamente';
            $this->getResponse()['data'] = [
                'id' => $auth->getIdUsers(),
                'idPlataforma' => $auth->getIdPlataforma()
            ];
            $auth->session_cookies(1);
            $this->getResponse()['token'] = $this->generaJWT($auth->getUserData());
        } catch (Exception $e) {
            $this->rollBack();
            throw new Exception($e->getMessage());
        }
        return $this->getResponse();

    }

    /**
     * Función para registrar un usuario referido
     * @param $nombre
     * @param $correo
     * @param $pais
     * @param $telefono
     * @param $contrasena
     * @param $tienda
     * @param $referido
     * @return array
     * @throws Exception
     */
    public function registro_referido($nombre, $correo, $pais, $telefono, $contrasena, $tienda, $referido): array
    {
        try {
            $auth = new RegisterUser($this->getConnection(), $nombre, $correo, $pais, $telefono, $contrasena, $tienda, $referido, 1);
            $this->getResponse()['status'] = 200;
            $this->getResponse()['title'] = 'Petición exitosa';
            $this->getResponse()['message'] = 'Usuario registrado correctamente';
            $this->getResponse()['data'] = [
                'id' => $auth->getIdUsers(),
                'idPlataforma' => $auth->getIdPlataforma()
            ];
            $auth->session_cookies(1);
            $this->getResponse()['token'] = $this->generaJWT($auth->getUserData());

        } catch (Exception $e) {
            $this->rollBack();
            throw new Exception($e->getMessage());
        }
        return $this->getResponse();
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function bienvenida($correo): void
    {
        global $message_body, $smtp_debug, $smtp_host, $smtp_user, $smtp_pass, $smtp_secure, $smtp_from, $smtp_from_name;
        require_once 'PHPMailer/Mail_bienvenida.php';
        $mail = new MailSender('smtp');
        $mail->sendMail($correo, 'Bienvenido a ' . MARCA, $message_body);
    }

    /**
     * Función para actualizar UUID y JWT en la tabla 'users'
     * @param $id_usuario
     * @param $jwt
     * @param $uuid
     * @return int
     * @throws Exception
     */
    private function actualizaJWTyUUID($id_usuario, $jwt, $uuid): int
    {
        $sql = "UPDATE users
            SET jwt = ?, uuid = ?
            WHERE id_users = ?";
        $params = [$jwt, $uuid, $id_usuario];
        return $this->update($sql, $params);
    }

    /**
     * Genera un UUID versión 4
     * @throws \Random\RandomException
     */
    private function generaUUIDv4(): string
    {
        $data = random_bytes(16);

        // Ajuste de versión (4) y bits
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Armamos la cadena con formato 8-4-4-4-12
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * @param $usuario
     * @param $password
     * @return array
     * @throws Exception
     */
    public function login2($usuario, $password): array
    {
        $auth = new LoginUser($this->getConnection(), $usuario, $password);
        $auth->login();
        $auth->setValidarConfigChat($this->validar_configuracion($auth->getIdPlataforma()));
        // 1. Si llegamos aquí, todo está correcto: generamos el JWT
        $jwt = $this->generaJWT($auth->getUserData());

        //2 Generamos un UUID v4
        $uuid = $this->generaUUIDv4();

        //3 Actualizamos la tabla users con JWT y UUID
        $this->actualizaJWTyUUID($auth->getIdUsers(), $jwt, $uuid);

        //4. Creamos la respuesta exitosa
        $this->getResponse()['status'] = 200;
        $this->getResponse()['title'] = 'Peticion exitosa';
        $this->getResponse()['message'] = 'Usuario autenticado correctamente';
        $this->getResponse()['token'] = $jwt;
        $this->getResponse()['data'] = $auth->getUserData();
        $this->getResponse()['cargo'] = $auth->getCargoUsers();

        $_SESSION['token'] = $jwt;

        //5. Validar la configuración y asignar a la sesión
        $_SESSION['validar_config_chat'] = $this->validar_configuracion($auth->getIdPlataforma());

        setcookie("token", $jwt, time() + 3600, "/", "." . DOMINIO);

        // Opcional: cookie extra (jwt_token) con más flags
        setcookie('jwt_token', $jwt, [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => '.' . DOMINIO,
            'secure' => true,
            'httponly' => true,
            'samesite' => 'None',
        ]);

        // 6. Retornamos la respuesta final
        return $this->getResponse();
    }

    /**
     * @param $id_plataforma
     * @return bool
     * @throws Exception
     */
    public function validar_configuracion($id_plataforma): bool
    {
        // Asegurarse de que $id_plataforma sea seguro
        $id_plataforma = intval($id_plataforma);

        // Consulta para validar si hay configuraciones
        $sql = "SELECT * FROM `configuraciones` WHERE id_plataforma = $id_plataforma";
        $resultado = $this->select($sql);

        // Retornar el estado directamente
        return !empty($resultado); // Retorna true si hay datos, false si no
    }


    /**
     * @param $uuid
     * @return array
     * @throws Exception
     */
    public function getUserByUUID($uuid): array
    {
        $sql = "SELECT * FROM users WHERE uuid = '$uuid'";
        return $this->select($sql);
    }

    /**
     * @param $id_user
     * @return array
     * @throws Exception
     */
    public function getPlatformByUserId($id_user): array
    {
        $sql = "SELECT id_plataforma 
            FROM usuario_plataforma 
            WHERE id_usuario = '$id_user'";
        return $this->select($sql);
    }

    /**
     * @param $correo
     * @return array
     * @throws Exception
     */
    public function recovery($correo): array
    {
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
        return $this->select($sql);
    }

    public function validarTiendas($tienda)
    {
        $sql = "SELECT * FROM plataformas WHERE nombre_tienda = ?";
        $params = [$tienda];
        $result = $this->simple_select($sql, $params);

        if ($result > 0) {
            return [
                "status" => 400,
                "message" => "Tienda encontrada",
                'exists' => true,
                'count' => $result
            ];
        }
        return [
            "status" => 200,
            "message" => "Tienda no encontrada",
            'exists' => false,
            'count' => 0
        ];
    }


    /**
     * @param $token
     * @return array
     * @throws Exception
     */
    public function validarToken($token): array
    {
        $sql = "SELECT token_fecha FROM users WHERE token_act = ? AND estado_token = 1";
        $result = $this->dselect($sql, [$token]);
        if (empty($result)) {
            throw new Exception("Token no encontrado");
        }
        // Validar tiempo de expiración
        if ((strtotime(date('Y-m-d H:i:s')) - strtotime($result[0]['token_fecha'])) > 3600) {
            throw new Exception("Token expirado");
        }
        return ['exists' => true, 'status' => 200, 'message' => 'Token válido', 'count' => 1];
    }

    /**
     * Recupera la contraseña del usuario generando un token y enviando un correo.
     *
     * @param string $correo Correo del usuario.
     * @return array Respuesta con el estado de la operación.
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
    public function recuperar_contrasena(string $correo): array
    {
        if (str_contains($correo, '@dev')) {
            throw new Exception("No se puede recuperar la contraseña de un correo de desarrollo");
        }
        $usuario = $this->select("SELECT * FROM users WHERE email_users = ?", [$correo]);
        if (empty($usuario)) {
            throw new Exception("Usuario no encontrado");
        }
        // Generar y almacenar el token
        $token = md5(uniqid(mt_rand(), true));
        $sql = "UPDATE users SET token_act = ?, estado_token = 1, token_fecha = NOW() WHERE email_users = ?";
        $responseDB = $this->update($sql, [$token, $correo]);
        if ($responseDB !== 1) {
            throw new Exception("Error al generar el token");
        }
        // Validar el correo
        $validador = new EmailValidator();
        if (!$validador->isValid($correo, new DNSCheckValidation())) {
            throw new Exception("Correo no válido");
        }
        // Enviar correo de recuperación
        $url_change = URL_MATRIZ . "/acceso/recovery/" . $token;
        global $message_body, $smtp_debug, $smtp_host, $smtp_user, $smtp_pass, $smtp_secure, $smtp_from, $smtp_from_name;

        require_once 'PHPMailer/Mail_recuperar.php';
        try {
            $mail = new MailSender("smtp");
            if ($mail->sendMail($correo, "Recuperar contraseña", $message_body)) {
                return [
                    "status" => 200,
                    "message" => "Correo enviado correctamente",
                    "token" => $token
                ];
            }
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: " . $e->getMessage());
        }
        throw new Exception("Error al enviar el correo");
    }

    /**
     * @throws Exception
     */
    public function cambiarContrasena($token, $contrasena): array
    {
        $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET con_users = ?, token_act = '', estado_token = 0 WHERE token_act = ?";
        $data = [$contrasena, $token];
        //print_r($data);
        $response1 = $this->update($sql, $data);
        if ($response1 == 1) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['title'] = 'Petición exitosa';
            $response['message'] = 'Contraseña actualizada correctamente';
        } else {
            throw new Exception("Error al actualizar la contraseña");
        }
        return $response;
    }

    /**
     * @param $token
     * @return array
     */
    public function jwt($token): array
    {
        try {
            // Decodificar el token usando el algoritmo 'HS256'
            $decoded = $this->jwt->decode($token, $this->key);

            // Iniciar sesión si aún no está iniciada
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // asignar los datos del token a la sesión
            $sql = "SELECT * FROM users WHERE email_users = ?";
            $params = [$decoded->correo];
            $result = $this->dselect($sql, $params);

            if (count($result) > 0) {
                $sql = "SELECT * FROM plataformas WHERE email = ?";
                $params = [$decoded->correo];
                $result = $this->dselect($sql, $params);
                $_SESSION["id_plataforma"] = $result[0]["id_plataforma"];
                $_SESSION["user"] = $decoded->correo;
                $_SESSION["id"] = $decoded->id;
                $_SESSION["cargo"] = $decoded->cargo;
                $_SESSION["tienda"] = $result[0]["nombre_tienda"];
                $_SESSION["enlace"] = "https://" . $result[0]["nombre_tienda"] . "." . DOMINIO;
                $_SESSION["matriz"] = $this->obtenerMatriz();
                $_SESSION["login_time"] = time();
                $_SESSION["session_lifetime"] = 3600;
                $_SESSION["token"] = $token;
            }

            // Construir la respuesta de éxito
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['title'] = 'Petición exitosa';
            $response['message'] = 'Token válido, sesión iniciada';
            $response['data'] = $decoded;
        } catch (Exception $e) {
            // Manejar errores de decodificación del token
            $response = $this->initialResponse();
            $response['status'] = 401;
            $response['title'] = 'Error';
            $response['message'] = 'Token no válido: ' . $e->getMessage();
        }

        return $response;
    }


}
