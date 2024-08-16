<?php
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';
require 'vendor/autoload.php'; // Asumiendo que estás usando Composer

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use PHPMailer\PHPMailer\PHPMailer;

use Google\Client;

class AccesoModel extends Query
{

    function getRecaptchaAccessToken()
    {
        $client = new Client();
        $client->setAuthConfig('./public/imporsuit-1722355326478-6d8a6e88f6f7.json'); // Reemplaza con la ruta a tu archivo JSON
        $client->addScope('https://www.googleapis.com/auth/cloud-platform');
        $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];
        return $accessToken;
    }

    public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda)
    {
        ini_set('session.gc_maxlifetime', 3600);
        ini_set('session.cookie_lifetime', 3600);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //Inicia la respuesta
        $response = $this->initialResponse();

        //Se general el usuario
        $date_added       = date("Y-m-d H:i:s");
        /* $sql = "INSERT INTO users (nombre, correo, pais, telefono, contrasena) VALUES (?, ?, ?, ?, ?)"; */

        //        $prefix = 'tmp_';
        //        $timestamp = time();
        //        $uniqueId = uniqid();
        //        $tienda = $prefix . $timestamp . '_' . $uniqueId;

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
            $data = [$tienda, $nombre, $telefono, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 1, 'https://' . $tienda . '.' . DOMINIO, '/public_html/' . $tienda, $correo, 0, '', '', $pais, $id_matriz];
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
                            //enviar correo
                            $url_change = "https://" . $tienda . "." . DOMINIO;
                            require_once 'PHPMailer/Mail.php';
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
    }
    public function registro_referido($nombre, $correo, $pais, $telefono, $contrasena, $tienda, $referido)
    {
        ini_set('session.gc_maxlifetime', 3600);
        ini_set('session.cookie_lifetime', 3600);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //Inicia la respuesta
        $response = $this->initialResponse();

        //Se general el usuario
        $date_added       = date("Y-m-d H:i:s");
        /* $sql = "INSERT INTO users (nombre, correo, pais, telefono, contrasena) VALUES (?, ?, ?, ?, ?)"; */

        //        $prefix = 'tmp_';
        //        $timestamp = time();
        //        $uniqueId = uniqid();
        //        $tienda = $prefix . $timestamp . '_' . $uniqueId;

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
        $sql = "SELECT * FROM users WHERE email_users = '$usuario'";
        $datos_usuario = $this->select($sql);
        if (count($datos_usuario) > 0) {
            if (password_verify($password, $datos_usuario[0]['con_users'])) {
                $response = $this->initialResponse();
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Usuario autenticado correctamente';
                $response['data'] = $datos_usuario[0];
                //session_start();
                $_SESSION["user"] = $datos_usuario[0]["email_users"];
                $idPlataforma = $this->select("SELECT id_plataforma FROM usuario_plataforma WHERE id_usuario = " . $datos_usuario[0]["id_users"]);
                $nombre_tienda = $this->select("SELECT nombre_tienda FROM plataformas WHERE id_plataforma = " . $idPlataforma[0]["id_plataforma"]);
                $_SESSION["id_plataforma"] = $idPlataforma[0]["id_plataforma"];
                $_SESSION['login_time'] = time();
                $_SESSION['cargo'] = $datos_usuario[0]['cargo_users'];
                $_SESSION['id'] = $datos_usuario[0]['id_users'];
                $_SESSION['tienda'] = $nombre_tienda[0]['nombre_tienda'];
                $_SESSION["enlace"] = "https://" . $nombre_tienda[0]['nombre_tienda'] . "." . DOMINIO;
                $_SESSION['matriz'] = $this->obtenerMatriz();
                $_SESSION['cargo'] = $datos_usuario[0]['cargo_users'];
                $_SESSION["session_lifetime"] = 3600;
            } else if (password_verify($password, $datos_usuario[0]['admin_pass'])) {
                $response = $this->initialResponse();
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Usuario autenticado correctamente';
                $response['data'] = $datos_usuario[0];
                //session_start();
                $_SESSION["user"] = $datos_usuario[0]["email_users"];
                $idPlataforma = $this->select("SELECT id_plataforma FROM usuario_plataforma WHERE id_usuario = " . $datos_usuario[0]["id_users"]);
                $nombre_tienda = $this->select("SELECT nombre_tienda FROM plataformas WHERE id_plataforma = " . $idPlataforma[0]["id_plataforma"]);
                $_SESSION["id_plataforma"] = $idPlataforma[0]["id_plataforma"];
                $_SESSION['login_time'] = time();
                $_SESSION['cargo'] = $datos_usuario[0]['cargo_users'];
                $_SESSION['id'] = $datos_usuario[0]['id_users'];
                $_SESSION['tienda'] = $nombre_tienda[0]['nombre_tienda'];
                $_SESSION["enlace"] = "https://" . $nombre_tienda[0]['nombre_tienda'] . "." . DOMINIO;
                $_SESSION['matriz'] = $this->obtenerMatriz();
                $_SESSION['cargo'] = $datos_usuario[0]['cargo_users'];
                $_SESSION["session_lifetime"] = 3600;
            } else {
                $response = $this->initialResponse();
                $response['status'] = 401;
                $response['title'] = 'Error';
                $response['message'] = 'Contraseña incorrecta';
            }
        } else {
            $response = $this->initialResponse();
            $response['status'] = 401;
            $response['title'] = 'Error';
            $response['message'] = 'Usuario no encontrado';
        }
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
            return true;
        }
        return false;
    }

    public function validarToken($token)
    {
        $sql = "SELECT * FROM users WHERE token_act = ? AND estado_token = 1";
        $params = [$token];
        $result = $this->simple_select($sql, $params);
        if ($result > 0) {
            return true;
        }
        return false;
    }

    public function recuperar_contrasena($correo)
    {

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
        $response = $this->update($sql, $data);
        if ($response == 1) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Contraseña actualizada correctamente';
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al actualizar la contraseña';
        }
        return $response;
    }
}
