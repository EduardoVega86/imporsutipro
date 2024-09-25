<?php

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

    public function guardarMotorizado($nombre, $celular, $usuario, $contrasena, $id_plataforma)
    {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO motorizados (nombre_motorizado, numero_motorizado,usuario, contrasena, id_plataforma) VALUES (?, ?, ?, ?, ?)";
        $data = [$nombre, $celular, $usuario, $hash, $id_plataforma];
        $res = $this->insert($sql, $data);
        if ($res == 1) {
            $sql = "INSERT INTO users(nombre_users, usuario_users, con_users, email_users, cargo_users) VALUES (?, ?, ?, ?, ?)";
            $data = [$nombre, $usuario, $hash, $usuario, 35];
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
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = $res["message"];
        }
        return $response;
    }

    public function estados($estado, $imagen, $tipo, $observacion, $id_factura, $googlemaps)
    {
        if ($estado == 1) {
            $sql = "UPDATE facturas_cot SET estado_guia_sistema = 1 WHERE id_factura = $id_factura";
            $res = $this->simple_insert($sql);
            $response = $this->handleSimpleResponse($res);
        } elseif ($estado == 2) {
            $sql = "UPDATE facturas_cot SET estado_guia_sistema = 2 WHERE id_factura = $id_factura";
            $res = $this->simple_insert($sql);
            $response = $this->handleSimpleResponse($res);
        } elseif ($estado == 4 || $estado == 7 || $estado == 9 || $estado == 14) {
            $sql = "UPDATE facturas_cot SET estado_guia_sistema = $estado, googlemaps = '$googlemaps' WHERE id_factura = $id_factura";
            $res = $this->simple_insert($sql);

            if ($res == 1) {
                $response = $this->manejarImagenFactura($imagen, $id_factura, $tipo);

                if ($estado == 14 && $response['status'] == 200) {
                    // curl a pedidos/novedadSpeed
                    $url = "https://new.imporsuitpro.com/pedidos/novedadSpeed";
                    $data = [
                        'id_factura' => $id_factura,
                        'novedad' => $observacion,
                        'tipo' => $tipo
                    ];

                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

                    $curl_response = curl_exec($ch);
                    curl_close($ch);

                    $response['curl_response'] = $curl_response; // Si quieres guardar la respuesta del curl
                }
            } else {
                $response = $this->initialResponse();
                $response['status'] = 500;
                $response['message'] = "Error al actualizar la factura.";
            }
        } else {
            $response = $this->initialResponse();
            $response['status'] = 500;
            $response['message'] = "Error al actualizar la factura.";
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
                $response['message'] = "Error al subir la imagen.";
            }
        } else {
            $response['status'] = 500;
            $response['message'] = "Error al subir la imagen.";
        }

        return $response;
    }
}
