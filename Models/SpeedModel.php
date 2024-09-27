<?php
require_once 'Class/ImageUploader.php';


class SpeedModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function solventarNovedad($observacion, $id_factura)
    {
        $sql = "UPDATE novedades SET solucion_novedad= '$observacion', solucionada = 1 WHERE id_novedad = $id_factura";
        $res = $this->simple_insert($sql);
        if ($res == 1) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['message'] = "Novedad solventada correctamente.";
            $response['title'] = "¡Éxito!";
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = "Error al solventar la novedad.";
        }
        return $response;
    }

    public function guardarRecibo($recibo, $id_factura)
    {
        $response = $this->initialResponse();
        $target_dir = "public/recibos/";
        $imageFileType = strtolower(pathinfo($recibo["name"], PATHINFO_EXTENSION));

        // Generar un nombre de archivo único
        $unique_name = uniqid('', true) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Verificar si el archivo existe y agregar un diferenciador si es necesario
        $original_target_file = $target_file;
        $counter = 1;
        while (file_exists($target_file)) {
            $target_file = $target_dir . uniqid('', true) . '.' . $imageFileType;
            $counter++;
        }

        $uploadOk = 1;
        $check = getimagesize($recibo["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['status'] = 500;
            $response['message'] = "El archivo no es una imagen.";
            $uploadOk = 0;
        }

        if ($recibo["size"] > 5000000) { // Tamaño máximo permitido
            $response['status'] = 500;
            $response['message'] = "Lo siento, su archivo es demasiado grande.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $response['status'] = 500;
            $response['message'] = "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $response['status'] = 500;
            $response['message'] = "Lo siento, hubo un error al subir el archivo.";
        } else {
            if (move_uploaded_file($recibo["tmp_name"], $target_file)) {
                $response['status'] = 200;
                $response['message'] = "El recibo se ha guardado correctamente.";
                $response['dir'] = $target_file;

                // Actualizar la base de datos con la ruta del recibo
                $sql = "UPDATE facturas_cot SET recibo = '$target_file' WHERE id_factura = $id_factura";
                $res = $this->simple_insert($sql);
                if ($res == 1) {
                    $response['status'] = 200;
                    $response['message'] = "El recibo se ha guardado y actualizado correctamente en la base de datos.";
                    $response['title'] = "¡Éxito!";
                    $response['sql'] = $sql;
                } else {
                    $response['status'] = 500;
                    $response['message'] = "Error al actualizar la base de datos.";
                }
            } else {
                $response['status'] = 500;
                $response['message'] = "Lo siento, hubo un error al mover el archivo.";
            }
        }

        return $response;
    }

    public function existeMotorizado($usuario)
    {
        $sql = "SELECT * FROM motorizados WHERE usuario = '$usuario'";
        $res = $this->select($sql);
        if (!empty($res)) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['message'] = "Motorizado encontrado.";
            $response['data'] = $res['data'][0];
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = "Motorizado no encontrado.";
        }
        return $response;
    }

    public function guardarMotorizado($nombre, $celular, $usuario, $contrasena, $id_plataforma)
    {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO motorizados (nombre_motorizado, numero_motorizado,usuario, contrasena, id_plataforma) VALUES (?, ?, ?, ?, ?)";
        $data = [$nombre, $celular, $usuario, $hash, $id_plataforma];
        $res = $this->insert($sql, $data);
        if ($res == 1) {
            $sql = "INSERT INTO users(nombre_users, usuario_users, con_users, email_users, cargo_users, date_added) VALUES (?, ?, ?, ?, ?, ?)";
            $data = [$nombre, $usuario, $hash, $usuario, 35, date("Y-m-d H:i:s")];
            $res = $this->insert($sql, $data);
            if ($res == 1) {
                //buscar el id del usuario
                $sql = "SELECT id_users FROM users WHERE usuario_users = '$usuario'";
                $res = $this->select($sql);
                if (!empty($res)) {
                    $id_usuario = $res[0]['id_users'];
                    $sql = "INSERT INTO `usuario_plataforma` (`id_usuario`, `id_plataforma`) VALUES (?,?);";
                    $data = [$id_usuario, $id_plataforma];
                    $res = $this->insert($sql, $data);
                    if ($res == 1) {
                        $sql = "UPDATE motorizados SET id_usuario = ? WHERE usuario = ?";
                        $data = [$id_usuario, $usuario];
                        $res = $this->insert($sql, $data);
                        if ($res == 1) {
                            $response = $this->initialResponse();
                            $response['status'] = 200;
                            $response['message'] = "Motorizado guardado correctamente.";
                            $response['title'] = "¡Éxito!";
                        } else {
                            $response = $this->initialResponse();
                            $response['status'] = 500;
                            $response['message'] = $res["message"];
                        }
                        $response = $this->initialResponse();
                        $response['status'] = 200;
                        $response['message'] = "Motorizado guardado correctamente.";
                        $response['title'] = "¡Éxito!";
                    } else {
                        $response = $this->initialResponse();
                        $response['status'] = 500;
                        $response['message'] = $res["message"];
                    }
                } else {
                    $response = $this->initialResponse();
                    $response['status'] = 500;
                    $response['message'] = $res["message"];
                }
            } else {
                $response = $this->initialResponse();
                $response['status'] = 500;
                $response['message'] = $res["message"];
            }
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = $res["message"];
        }
        return $response;
    }

    public function estados($estado, $imagen, $tipo, $observacion, $id_factura, $googlemaps)
    {
        $guias_connect = mysqli_connect("localhost", "imporsuit_system", "imporsuit_system", "imporsuitpro_guias");
        $sql = "SELECT * FROM facturas_cot WHERE id_factura = $id_factura";
        $numero_factura = $this->select($sql)[0]['numero_factura'];
        if ($estado == 1) {
            $sql = "UPDATE facturas_cot SET estado_guia_sistema = 1 WHERE id_factura = $id_factura";
            $res = $this->simple_insert($sql);
            $response = $this->handleSimpleResponse($res);

            // datos a guias_speed
            $sql = "UPDATE guias_speed SET estado = 1 WHERE factura = '$numero_factura'";
            $res = mysqli_query($guias_connect, $sql);
        } elseif ($estado == 2) {
            $sql = "UPDATE facturas_cot SET estado_guia_sistema = 2 WHERE id_factura = $id_factura";
            $res = $this->simple_insert($sql);
            $response = $this->handleSimpleResponse($res);

            // datos a guias_speed
            $sql = "UPDATE guias_speed SET estado = 2 WHERE factura = '$numero_factura'";
            $res = mysqli_query($guias_connect, $sql);
        } else if ($estado == 3) {
            $sql = "UPDATE facturas_cot SET estado_guia_sistema = 3, googlemaps='$googlemaps' WHERE id_factura = $id_factura";
            $res = $this->simple_insert($sql);

            $response = $this->handleSimpleResponse($res);

            // datos a guias_speed
            $sql = "UPDATE guias_speed SET estado = 3, url = '$googlemaps' WHERE factura = '$numero_factura'";
            $res = mysqli_query($guias_connect, $sql);
        } elseif ($estado == 7 || $estado == 9 || $estado == 14) {
            $sql = "UPDATE facturas_cot SET estado_guia_sistema = '$estado' WHERE id_factura = '$id_factura'";
            $res = $this->simple_insert($sql);

            // datos a guias_speed
            $sql = "UPDATE guias_speed SET estado = $estado WHERE factura = '$numero_factura'";
            $res = mysqli_query($guias_connect, $sql);

            if ($res == 1) {
                $response = $this->manejarImagenFactura($imagen, $id_factura, $estado);

                if ($estado == 14 && $response['status'] == 200) {
                    // curl a pedidos/novedadSpeed
                    $url = "https://new.imporsuitpro.com/pedidos/novedadSpeed";
                    $data = [
                        'id_factura' => $id_factura,
                        'novedad' => $observacion,
                        'tipo' => $tipo
                    ];

                    // Inicializar cURL
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

                    // Configurar POSTFIELDS con los datos como multipart/form-data
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                    // No es necesario establecer el encabezado para multipart/form-data
                    // ya que cURL lo configurará automáticamente al usar POSTFIELDS

                    $curl_response = curl_exec($ch);
                    curl_close($ch);

                    $response['curl_response'] = $curl_response; // Si quieres guardar la respuesta del curl
                }
            } else {
                $response = $this->initialResponse();
                $response['status'] = 500;
                $response['message'] = $res["message"];
            }
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = "Error al actualizar la factura. status: $estado";
        }

        return $response;
    }

    private function handleSimpleResponse($res)
    {
        $response = $this->initialResponse();
        if ($res == 1) {
            $response['status'] = 200;
            $response['message'] = "Factura actualizada correctamente.";
            $response['title'] = "¡Éxito!";
        } else {
            $response['status'] = 500;
            $response['message'] = "Error al actualizar la factura.";
        }
        return $response;
    }


    public function manejarImagenFactura($imagen, $id_factura, $tipo)
    {
        $uploader = new ImageUploader("public/img/speed/");
        $response = $uploader->uploadImage($imagen);

        if ($response['status'] == 200) {
            $sql = "INSERT INTO imagenes_pedidos (id_factura, imagen, estado) VALUES (?, ?, ?)";
            $data = [$id_factura, $response['data'], $tipo];
            $res = $this->insert($sql, $data);
            if ($res == 1) {
                $response['status'] = 200;
                $response['message'] = "Imagen subida correctamente.";
                $response['title'] = "¡Éxito!";
            } else {
                $response['status'] = 500;
                $response['message'] = $res["message"];
            }
        } else {
            $response['status'] = 500;
            $response['message'] = "Error al subir la imagen.";
        }

        return $response;
    }

    public function buscarFactura($numero_factura)
    {
        $sql = "SELECT * FROM facturas_cot WHERE numero_factura = '$numero_factura'";
        $res = $this->select($sql);
        if (!empty($res)) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['message'] = "Factura encontrada.";
            $response['data'] = $res[0];
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = "Factura no encontrada.";
        }
        return $response;
    }

    public function asignarMotorizado($id_usuario, $guia)
    {
        $sql = "INSERT INTO motorizado_guia (id_motorizado, guia) VALUES (?, ?)";
        $data = [$id_usuario, $guia];
        $res = $this->insert($sql, $data);
        if ($res == 1) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['message'] = "Guía asignada correctamente.";
            $response['title'] = "¡Éxito!";
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = $res["message"];
        }
        return $response;
    }

    public function perfil($id)
    {
        $sql = "SELECT * FROM users WHERE id_users = $id";
        $res = $this->select($sql);

        $email_users = $res[0]['email_users'];



        $sql = "SELECT `id_motorizado`, `nombre_motorizado`, `numero_motorizado`, `placa_motorizado`, `usuario`, `id_plataforma`, `id_usuario`, `licencia`, `matricula` FROM motorizados WHERE usuario ='" . $email_users . "'";
        $res = $this->select($sql);
        if (!empty($res)) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['message'] = "Perfil encontrado.";
            $response['data'] = $res[0];
            $response['title'] = "¡Éxito!";
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = "Perfil no encontrado.";
        }
        return $response;
    }

    public function getMotorizados()
    {

        $sql = "SELECT * FROM motorizados";
        $res = $this->select($sql);
        if (!empty($res)) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['message'] = "Motorizados encontrados.";
            $response['data'] = $res;
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = "Motorizados no encontrados.";
        }
        return $response;
    }

    public function editarMotorizado($id, $nombre, $celular, $usuario, $contrasena)
    {
        // Empezamos la consulta base
        $sql = "UPDATE motorizados SET nombre_motorizado = ?, numero_motorizado = ?";
        $data = [$nombre, $celular];

        // Si se envía el usuario, agregamos ese campo a la consulta
        if ($usuario !== null) {
            $sql .= ", usuario = ?";
            $data[] = $usuario;
        }

        // Si se envía la contraseña, la agregamos a la consulta
        if ($contrasena !== null) {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql .= ", contrasena = ?";
            $data[] = $hash;
        }

        // Finalizamos la consulta agregando la condición WHERE
        $sql .= " WHERE id_motorizado = ?";
        $data[] = $id;

        // Ejecutamos la consulta
        $res = $this->insert($sql, $data);

        if ($res == 1) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['message'] = "Motorizado actualizado correctamente.";
            $response['title'] = "¡Éxito!";
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = $res["message"];
        }

        return $response;
    }


    public function subirMatriculaLicencia($id_usuario, $matricula, $licencia)
    {
        $response = $this->initialResponse();
        $uploader = new ImageUploader("public/img/speed/matr_lic/");
        $response_matricula = $uploader->uploadImage($matricula);
        $response_licencia = $uploader->uploadImage($licencia);

        if ($response_matricula['status'] == 200 && $response_licencia['status'] == 200) {
            $sql = "UPDATE motorizados SET matricula = ?, licencia = ? WHERE id_motorizado = ?";
            $data = [$response_matricula['data'], $response_licencia['data'], $id_usuario];
            $res = $this->insert($sql, $data);
            if ($res == 1) {
                $response['status'] = 200;
                $response['message'] = "Imágenes subidas correctamente.";
                $response['title'] = "¡Éxito!";
            } else {
                $response['status'] = 500;
                $response['message'] = "Error al subir las imágenes.";
            }
        } else {
            $response['status'] = 500;
            $response['message'] = "Error al subir las imágenes.";
        }

        return $response;
    }
}
