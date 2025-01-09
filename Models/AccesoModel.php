<?php
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';
require 'vendor/autoload.php'; // Asumiendo que estás usando Composer

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use PHPMailer\PHPMailer\PHPMailer;

use Google\Client;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AccesoModel extends Query
{
    private $jwt_secret = 'semeljxAFrbOvDCHQ98jRHuwhLRdPw6GY0hhhvJdQ6rbkc5SMsXVCcgUTtzsLQyR'; // Cambia 'your_secret_key' por una clave secreta segura

    /**
     * Genera un JWT con los datos de usuario.
     */
    private function generaJWT(array $userData)
    {
        $payload = [
            'id'      => $userData['id_users'],
            'nombre'  => $userData['nombre_users'],
            'cargo'   => $userData['cargo_users'],
            'correo'  => $userData['email_users'],
            'iat'     => time(),               // tiempo de creación
            'exp'     => time() + 3600,        // token expira en 1 hora
            'iss'     => $_SERVER['SERVER_NAME'],
            'aud'     => $_SERVER['SERVER_NAME'],
            'sub'     => $userData['email_users'],
            'jti'     => $userData['id_users'],
            'nbf'     => time()
        ];

        // Codificamos con la clave secreta que definiste en $this->jwt_secret
        return JWT::encode($payload, $this->jwt_secret, 'HS256');
    }



    public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda)
    {
        try {
            // Inicializa respuesta base
            $response = $this->initialResponse();

            // Nueva validación: 'pais' debe tener exactamente 2 caracteres
            if (strlen($pais) !== 2) {
                $response['status'] = 400;
                $response['title'] = 'Petición inválida';
                $response['message'] = 'El campo país solo admite 2 caracteres';
                return $response; // Abortamos aquí
            }

            // Configuración de sesión
            ini_set('session.gc_maxlifetime', 3600);
            ini_set('session.cookie_lifetime', 3600);
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Generamos fecha de creación
            $date_added = date("Y-m-d H:i:s");

            // Hasheamos contraseña
            $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

            // Insertamos en la tabla 'users'
            $sql = "INSERT INTO users (nombre_users, email_users, con_users, usuario_users, date_added, cargo_users) 
                VALUES (?, ?, ?, ?, ?, ?)";
            $data = [$nombre, $correo, $contrasena, $correo, $date_added, 1];
            $insertar_usuario = $this->insert($sql, $data);

            // Verificamos si se insertó correctamente
            if ($insertar_usuario == 1) {

                // Obtenemos el ID del usuario recién insertado
                $id = $this->select("SELECT id_users FROM users WHERE usuario_users = '$correo'");

                // Obtenemos la matriz
                $id_matriz = $this->obtenerMatriz();
                $id_matriz = $id_matriz[0]['idmatriz'];

                // Insertamos en la tabla 'plataformas'
                $sql = "INSERT INTO plataformas
                   (`nombre_tienda`, `contacto`, `whatsapp`, `fecha_ingreso`, `fecha_actualza`, `id_plan`, 
                    `url_imporsuit`, `carpeta_servidor`, `email`, `referido`, `token_referido`, `refiere`, 
                    `pais`, `id_matriz`) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                $data = [
                    $tienda,
                    $nombre,
                    $telefono,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                    1,
                    'https://' . $tienda . '.' . DOMINIO,
                    '/public_html/' . $tienda,
                    $correo,
                    0,
                    '',
                    '',
                    $pais,
                    $id_matriz
                ];

                $insertar_plataforma = $this->insert($sql, $data);

                // Verificamos si se insertó la plataforma
                if ($insertar_plataforma == 1) {
                    // Obtenemos el ID de la plataforma recién insertada
                    $idPlataforma = $this->select("SELECT id_plataforma FROM plataformas WHERE email = '$correo'");

                    // Insertamos en la tabla 'perfil'
                    $sql = "INSERT INTO `perfil` ( `nombre_empresa`,`telefono`, `whatsapp`,  `id_plataforma`) 
                        VALUES (?,?,?,?)";
                    $data = [
                        $tienda,
                        $telefono,
                        $telefono,
                        $idPlataforma[0]['id_plataforma']
                    ];
                    $insertar_perfil = $this->insert($sql, $data);

                    if ($insertar_perfil == 1) {
                        // Relacionamos usuario con plataforma
                        $sql = "INSERT INTO usuario_plataforma (id_usuario, id_plataforma) VALUES (?, ?)";
                        $data = [$id[0]['id_users'], $idPlataforma[0]['id_plataforma']];
                        $insertar_relacion = $this->insert($sql, $data);

                        if ($insertar_relacion == 1) {
                            $id_plataforma = $idPlataforma[0]['id_plataforma'];

                            // Características iniciales
                            $sql_caracteristicas = "INSERT INTO `caracteristicas_tienda` 
                          (`id_producto`, `texto`, `icon_text`, `enlace_icon`, `subtexto_icon`, `accion`, `id_plataforma`) 
                          VALUES
                          (0, 'Envío Gratis a todo el País', 'fa-check', '', 'Llegamos a todo el País', 1, $id_plataforma),
                          (0, 'Pago Contra Entrega', 'fa-lock', NULL, 'Paga cuando recibes el producto', 2, $id_plataforma),
                          (0, 'Atención al cliente', 'fa-headset', NULL, 'Soporte 100% garantizado', 2, $id_plataforma);";

                            $registro_caracteristicas = $this->simple_insert($sql_caracteristicas);

                            // Bodega por defecto
                            $sql_bodega = "INSERT INTO `bodega`
                          (`nombre`, `id_empresa`, `responsable`, `contacto`, `id_plataforma`)
                          VALUES
                          ('$tienda', $id_plataforma, '$nombre', '$telefono', $id_plataforma);";
                            $registro_bodega = $this->simple_insert($sql_bodega);

                            if ($registro_caracteristicas > 0 && $registro_bodega > 0) {
                                // Todo salió bien
                                $response['status'] = 200;
                                $response['title'] = 'Petición exitosa';
                                $response['message'] = 'Usuario registrado correctamente';
                                $response['data'] = [
                                    'id' => $id[0]['id_users'],
                                    'idPlataforma' => $idPlataforma[0]['id_plataforma']
                                ];

                                // Guardamos en sesión
                                $_SESSION["user"]           = $correo;
                                $_SESSION["id_plataforma"]  = $idPlataforma[0]['id_plataforma'];
                                $_SESSION['login_time']     = time();
                                $_SESSION['cargo']          = 1;
                                $_SESSION['id']             = $id[0]['id_users'];
                                $_SESSION['tienda']         = $tienda;
                                $_SESSION['matriz']         = $id_matriz;
                                $_SESSION["enlace"]         = "https://" . $tienda . "." . DOMINIO;
                                $_SESSION["session_lifetime"] = 3600;

                                // Compartir cookie con subdominio
                                setcookie("user", $correo, time() + 3600, "/", "." . DOMINIO);
                                setcookie("id_plataforma", $idPlataforma[0]['id_plataforma'], time() + 3600, "/", "." . DOMINIO);
                                setcookie("login_time", time(), time() + 3600, "/", "." . DOMINIO);
                                setcookie("cargo", 1, time() + 3600, "/", "." . DOMINIO);
                                setcookie("id", $id[0]['id_users'], time() + 3600, "/", "." . DOMINIO);

                                // Enviamos correo
                                $url_change = "https://" . $tienda . "." . DOMINIO;
                                require_once 'PHPMailer/Mail.php';
                                $mail = new PHPMailer();
                                $mail->isSMTP();
                                $mail->SMTPDebug  = 0;
                                $mail->Host       = $smtp_host;
                                $mail->SMTPAuth   = true;
                                $mail->Username   = $smtp_user;
                                $mail->Password   = $smtp_pass;
                                $mail->Port       = 465;
                                $mail->SMTPSecure = $smtp_secure;
                                $mail->isHTML(true);
                                $mail->CharSet    = 'UTF-8';
                                $mail->setFrom($smtp_from, $smtp_from_name);
                                $mail->addAddress($correo);
                                $mail->Subject = 'Registro en ' . MARCA;
                                $mail->Body    = $message_body;

                                // Intentar enviar correo
                                if ($mail->send()) {
                                    if (MATRIZ == 1) {
                                        // Enviar correo de bienvenida, si aplica
                                        $this->bienvenida($correo);
                                    }
                                }
                                // else { echo "Error al enviar el correo: " . $mail->ErrorInfo; }
                            }
                        }
                    } else {
                        $response['message'] = "Error al crear el perfil";
                    }
                } else {
                    // Error en la plataforma
                    $response['message'] = $insertar_plataforma["message"];
                }
            } else {
                // Error en la inserción de usuario (correo duplicado, etc.)
                $response['message'] = "El usuario $correo ya existe en la base de datos, intente con otro correo electrónico!";
            }

            // Retornamos la respuesta final (sea éxito o error)
            return $response;
        } catch (Exception $e) {
            // Manejo de la excepción
            // Puedes agregar un log o un response más detallado
            $response['status'] = 500;
            $response['title']  = 'Error interno del servidor';
            $response['message'] = $e->getMessage();
            return $response;
        }
    }




    public function registro_referido($nombre, $correo, $pais, $telefono, $contrasena, $tienda, $referido)
    {
        try {
            //Inicia la respuesta
            $response = $this->initialResponse();

            ini_set('session.gc_maxlifetime', 3600);
            ini_set('session.cookie_lifetime', 3600);
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (strlen($pais) !== 2) {
                $response['status'] = 400;
                $response['title'] = 'Petición inválida';
                $response['message'] = 'El campo país solo admite 2 caracteres';
                return $response;
            }


            //Se general el usuario
            $date_added       = date("Y-m-d H:i:s");

            $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (nombre_users, email_users, con_users, usuario_users, date_added, cargo_users) VALUES (?, ?, ?, ?, ?, ?)";
            //   echo $sql;
            $data = [$nombre, $correo, $contrasena, $correo, $date_added, 1];
            $insertar_usuario = $this->insert($sql, $data);
            //print_r($insertar_usuario);
            //echo 'erro'.$insertar_usuario;;
            if ($insertar_usuario == 1) {
                $id = $this->select("SELECT id_users FROM users WHERE usuario_users = '$correo'");

                $id_matriz = $this->obtenerMatriz();
                //print_r($id_matriz);
                $id_matriz = $id_matriz[0]['idmatriz'];
                //print_r($id);
                //Se genera la plataforma
                $sql = "INSERT INTO plataformas (`nombre_tienda`, `contacto`, `whatsapp`, `fecha_ingreso`, `fecha_actualza`, `id_plan`, `url_imporsuit`, `carpeta_servidor`, `email`,  `referido`, `token_referido`, `refiere`, `pais`, `id_matriz`) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $data = [$tienda, $nombre, $telefono, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 1, 'https://' . $tienda . '.' . DOMINIO, '/public_html/' . $tienda, $correo, 1, '', $referido, $pais, $id_matriz];
                $insertar_plataforma = $this->insert($sql, $data);
                //  print_r($insertar_plataforma);
                //si se guarda correctamente la plataforma 
                if ($insertar_plataforma == 1) {
                    $idPlataforma = $this->select("SELECT id_plataforma FROM plataformas WHERE email = '$correo'");
                    //print_r($idPlataforma);
                    $sql = "INSERT INTO `perfil` ( `nombre_empresa`,`telefono`, `whatsapp`,  `id_plataforma`) VALUES (?,?,?,?)";
                    $data = [$tienda, $telefono, $telefono, $idPlataforma[0]['id_plataforma']];
                    $insertar_perfil = $this->insert($sql, $data);
                    // print_r($insertar_perfil);
                    if ($insertar_perfil == 1) {

                        $sql = "INSERT INTO usuario_plataforma (id_usuario, id_plataforma) VALUES (?, ?)";
                        $data = [$id[0]['id_users'], $idPlataforma[0]['id_plataforma']];
                        $insertar_relacion = $this->insert($sql, $data);


                        if ($insertar_relacion == 1) {
                            $id_plataforma = $idPlataforma[0]['id_plataforma'];
                            $sql_caracteristicas = "INSERT INTO `caracteristicas_tienda` (`id_producto`, `texto`, `icon_text`, `enlace_icon`, `subtexto_icon`, `accion`, `id_plataforma`) VALUES
                        (0, 'Envío Gratis a todo el País', 'fa-check', '', 'Llegamos a todo el País', 1, $id_plataforma),
                        (0, 'Pago Contra Entrega', 'fa-lock', NULL, 'Paga cuando recibes el producto', 2, $id_plataforma),
                        (0, 'Atención al cliente', 'fa-headset', NULL, 'Soporte 100% garantizado', 2, $id_plataforma);";

                            $registro_caracteristicas = $this->simple_insert($sql_caracteristicas);

                            $sql_bodega = "INSERT INTO `bodega`(`nombre`,`id_empresa`,`responsable`,`contacto`,`id_plataforma`)VALUES
                       ('$tienda',$id_plataforma,'$nombre','$telefono',$id_plataforma);";

                            $registro_bodega = $this->simple_insert($sql_bodega);

                            //echo $registro_caracteristicas;
                            // print_r($registro_caracteristicas);
                            if ($registro_caracteristicas > 0 && $registro_bodega > 0) {
                                $response['status'] = 200;
                                $response['title'] = 'Peticion exitosa';
                                $response['message'] = 'Usuario registrado correctamente';
                                $response['data'] = ['id' => $id[0]['id_users'], 'idPlataforma' => $idPlataforma[0]['id_plataforma']];
                                //session_start();
                                $_SESSION["user"] = $correo;
                                $_SESSION["id_plataforma"] = $idPlataforma[0]['id_plataforma'];
                                $_SESSION['login_time'] = time();
                                $_SESSION['cargo'] = 1;
                                $_SESSION['id'] = $id[0]['id_users'];
                                $_SESSION['tienda'] = $tienda;
                                $_SESSION['matriz'] = $id_matriz;
                                $_SESSION["enlace"] = "https://" . $tienda . "." . DOMINIO;
                                $_SESSION['cargo'] = 1;
                                $_SESSION["session_lifetime"] = 3600;

                                //compartir cookie con subdominio
                                setcookie("user", $correo, time() + 3600, "/", "." . DOMINIO);
                                setcookie("id_plataforma", $idPlataforma[0]['id_plataforma'], time() + 3600, "/", "." . DOMINIO);
                                setcookie("login_time", time(), time() + 3600, "/", "." . DOMINIO);
                                setcookie("cargo", 1, time() + 3600, "/", "." . DOMINIO);
                                setcookie("id", $id[0]['id_users'], time() + 3600, "/", "." . DOMINIO);
                                //enviar correo
                                $url_change = "https://" . $tienda . "." . DOMINIO;
                                require_once 'PHPMailer/Mail.php';
                                $mail = new PHPMailer();
                                $mail->isSMTP();
                                $mail->SMTPDebug = $smtp_debug;
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
                                $mail->Subject = 'Registro en ' . MARCA;
                                $mail->Body = $message_body;
                                // $this->crearSubdominio($tienda);

                                if ($mail->send()) {
                                    //echo "Correo enviado";
                                    if (MATRIZ == 1) {
                                        $this->bienvenida($correo);
                                    }
                                } else {
                                    //  echo "Error al enviar el correo: " . $mail->ErrorInfo;
                                }
                            }
                        }
                    } else {
                        $response['message'] = "Error al crear el perfil";
                    }
                } else {
                    $response['message'] = $insertar_plataforma["message"];
                    //$borrar_usuario=$this->delete($sql, $data);
                }

                //Se genera la relacion

            } else {
                $response['message'] = "El usuario $correo ya existe en la base de datos, intente con otro correo electrónico!";
                //$id = $this->select("SELECT users_id FROM users WHERE correo = '$correo'");
            }


            return $response;
        } catch (Exception $e) {
            $response['status'] = 500;
            $response['title']  = 'Error interno del servidor';
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    public function bienvenida($correo)
    {
        require_once 'PHPMailer/Mail_bienvenida.php';
        $mail2 = new PHPMailer();
        $mail2->isSMTP();
        $mail2->SMTPDebug = $smtp_debug;
        $mail2->Host = $smtp_host;
        $mail2->SMTPAuth = true;
        $mail2->Username = $smtp_user;
        $mail2->Password = $smtp_pass;
        $mail2->Port = 465;
        $mail2->SMTPSecure = $smtp_secure;
        $mail2->isHTML(true);
        $mail2->CharSet = 'UTF-8';
        $mail2->setFrom($smtp_from, $smtp_from_name);
        $mail2->addAddress($correo);
        $mail2->Subject = 'Bienvenido a ' . MARCA;
        $mail2->Body = $message_body;
        if ($mail2->send()) {
            //echo "Correo enviado";
        } else {
            //echo "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    }

    public function login($usuario, $password)
    {
        ini_set('session.gc_maxlifetime', 3600);
        ini_set('session.cookie_lifetime', 3600);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Buscamos al usuario en la tabla 'users'
        $sql = "SELECT * FROM users WHERE email_users = '$usuario'";
        $datos_usuario = $this->select($sql);

        // 2. Si no encontró nada, devolvemos un error (usuario no encontrado)
        if (empty($datos_usuario)) {
            $response = $this->initialResponse();
            $response['status']  = 401;
            $response['title']   = 'Error';
            $response['message'] = 'Usuario no encontrado';

            return $response;
        }

        // 3. Verificamos contraseña (password_verify en con_users o admin_pass)
        $password_verified = password_verify($password, $datos_usuario[0]['con_users']) ||
            password_verify($password, $datos_usuario[0]['admin_pass']);

        if (!$password_verified) {
            // Contraseña incorrecta
            $response = $this->initialResponse();
            $response['status']  = 401;
            $response['title']   = 'Error';
            $response['message'] = 'Contraseña incorrecta';
            return $response;
        }

        // 4. El usuario existe y la contraseña es correcta.  
        //    Buscamos la plataforma a la que está asociado (usuario_plataforma)
        $sqlPlataforma = "SELECT id_plataforma 
                          FROM usuario_plataforma 
                          WHERE id_usuario = " . $datos_usuario[0]['id_users'];
        $idPlataforma = $this->select($sqlPlataforma);

        // 4.1 Verificamos que efectivamente haya una plataforma asociada
        if (empty($idPlataforma) || !isset($idPlataforma[0]['id_plataforma'])) {
            $response = $this->initialResponse();
            $response['status']  = 401;
            $response['title']   = 'Error';
            $response['message'] = 'No se encontró la plataforma asociada al usuario.';
            return $response;
        }

        // 5. Buscamos el nombre de la tienda en la tabla plataformas
        $id_plataforma = $idPlataforma[0]['id_plataforma'];
        $sqlTienda     = "SELECT nombre_tienda 
                          FROM plataformas 
                          WHERE id_plataforma = $id_plataforma";
        $nombre_tienda = $this->select($sqlTienda);

        if (empty($nombre_tienda) || !isset($nombre_tienda[0]['nombre_tienda'])) {
            $response = $this->initialResponse();
            $response['status']  = 401;
            $response['title']   = 'Error';
            $response['message'] = 'No se encontró la tienda asociada a la plataforma.';
            return $response;
        }

        // 6. Si llegamos aquí, todo está correcto: generamos el JWT
        $jwt = $this->generaJWT($datos_usuario[0]);

        // 7. Creamos la respuesta exitosa
        $response = $this->initialResponse();
        $response['status']  = 200;
        $response['title']   = 'Peticion exitosa';
        $response['message'] = 'Usuario autenticado correctamente';
        $response['token']   = $jwt;
        // Añadimos la data del usuario
        $response['data']    = $datos_usuario[0];

        // Añadimos información adicional
        $response['ultimo_punto']['url'] = $datos_usuario[0]['ultimo_punto'];
        $response['cargo']               = $datos_usuario[0]['cargo_users'];

        // 8. Creamos sesiones y cookies
        $_SESSION["user"]          = $datos_usuario[0]["email_users"];
        $_SESSION["id_plataforma"] = $id_plataforma;
        $_SESSION['login_time']    = time();
        $_SESSION['cargo']         = $datos_usuario[0]['cargo_users'];
        $_SESSION['id']            = $datos_usuario[0]['id_users'];
        $_SESSION['tienda']        = $nombre_tienda[0]['nombre_tienda'];
        $_SESSION["enlace"]        = "https://" . $nombre_tienda[0]['nombre_tienda'] . "." . DOMINIO;
        $_SESSION['matriz']        = $this->obtenerMatriz();  // ojo, en tu código devuelves array, ponle guardas si es necesario
        $_SESSION['ultimo_punto']  = $datos_usuario[0]['ultimo_punto'];
        $_SESSION['token']         = $jwt;

        // Compartir cookie con subdominio
        setcookie("user",          $datos_usuario[0]["email_users"], time() + 3600, "/", "." . DOMINIO);
        setcookie("id_plataforma", $id_plataforma,                   time() + 3600, "/", "." . DOMINIO);
        setcookie("login_time",    time(),                            time() + 3600, "/", "." . DOMINIO);
        setcookie("cargo",         $datos_usuario[0]['cargo_users'],  time() + 3600, "/", "." . DOMINIO);
        setcookie("id",            $datos_usuario[0]['id_users'],     time() + 3600, "/", "." . DOMINIO);
        setcookie("token",         $jwt,                              time() + 3600, "/", "." . DOMINIO);

        // Opcional: cookie extra (jwt_token) con más flags
        setcookie('jwt_token', $jwt, [
            'expires'  => time() + 3600,
            'path'     => '/',
            'domain'   => '.' . DOMINIO,
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'None',
        ]);

        // 9. Retornamos la respuesta final
        return $response;
    }


    public function recovery($correo)
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
                'exists' => true,
            ];
        }
        return [
            'exists' => false,
        ];
    }


    public function validarToken($token)
    {
        $sql = "SELECT * FROM users WHERE token_act = ? AND estado_token = 1";
        $params = [$token];
        $result = $this->simple_select($sql, $params);

        if ($result > 0) {
            return [
                'exists' => true,
            ];
        }
        return [
            'exists' => false,
        ];
    }

    public function recuperar_contrasena($correo)
    {
        if (str_contains($correo, '@dev')) {
            //error
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Correo no valido';
            return $response;
        }
        $usuario = $this->select("SELECT * FROM users WHERE email_users = '$correo'");
        $token = null;
        if (count($usuario) > 0) {
            $token = md5(uniqid(mt_rand(), true)); // Asegurarse de que el token se genere aquí
        }

        // Verificar que el token está definido
        if (isset($token)) {
            $sql = "UPDATE users SET token_act = ?, estado_token = 1 WHERE email_users = ?";
            $data = [$token, $correo];
            $response = $this->update($sql, $data);
            $validador = new EmailValidator();
            if ($validador->isValid($correo, new DNSCheckValidation())) {
                if ($response == 1) {
                    $url_change = URL_MATRIZ . "/acceso/recovery/" . $token;
                    require_once 'PHPMailer/Mail_recuperar.php';
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
                    $mail->Subject = 'Recuperación de contraseña';
                    $mail->Body = $message_body;
                    if ($mail->send()) {
                        $response = $this->initialResponse();
                        $response['status'] = 200;
                        $response['title'] = 'Peticion exitosa';
                        $response['message'] = 'Correo enviado correctamente';
                    } else {
                        $response = $this->initialResponse();
                        $response['status'] = 500;
                        $response['title'] = 'Error';
                        $response['message'] = 'Error al enviar el correo: ' . $mail->ErrorInfo;
                        print_r($mail);
                    }
                } else {
                    $response = $this->initialResponse();
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al actualizar el token';
                }
            } else {
                $response = $this->initialResponse();
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'El correo no es valido';
            }
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al generar el token';
        }


        return $response;
    }

    public function cambiarContrasena($token, $contrasena)
    {
        $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET con_users = ?, token_act = '', estado_token = 0 WHERE token_act = ?";
        $data = [$contrasena, $token];
        $response1 = $this->update($sql, $data);
        if ($response1 == 1) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Contraseña actualizada correctamente';
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al actualizar la contraseña';
            print_r($response1);
        }
        return $response;
    }

    public function guardaUltimoPunto($url, $id)
    {
        $sql = "UPDATE users SET ultimo_punto = ? WHERE id_users = ?";
        $data = [$url, $id];
        $response = $this->update($sql, $data);
        if ($response == 1) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Ultimo punto guardado correctamente';
        } else {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['title'] = 'Error';
            $response['message'] = "No se actualizo";
        }
        return $response;
    }
    public function jwt($token)
    {
        try {
            // Decodificar el token usando el algoritmo 'HS256'
            $decoded = JWT::decode($token, new Key($this->jwt_secret, 'HS256'));

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
