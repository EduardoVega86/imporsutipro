<?php

class SpeedModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function solventarNovedad($observacion, $id_factura)
    {
        $sql = "UPDATE novedades SET solucion_novedad= '$observacion', solucionada = 1 WHERE id_factura = $id_factura";
        $res = $this->simple_insert($sql);
        if ($res == 1) {
            $response = $this->initialResponse();
            $response['status'] = 200;
            $response['message'] = "Novedad solventada correctamente.";
            $response['title'] = "¡Éxito!";
            $response['sql'] = $sql;
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
}
