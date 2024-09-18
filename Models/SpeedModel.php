<?php

class SpeedModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }


    public function guardarRecibo($recibo, $id_factura)
    {
        $response = $this->initialResponse();
        $target_dir = "public/recibos/";
        $target_file = $target_dir . basename($recibo["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($recibo["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['message'] = "El archivo no es una imagen.";
            $uploadOk = 0;
        }
        if ($recibo["size"] > 5000000) {
            $response['message'] = "Lo siento, su archivo es demasiado grande.";
            $uploadOk = 0;
        }
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            $response['message'] = "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
            $uploadOk = 0;
        } else {
            if (move_uploaded_file($recibo["tmp_name"], $target_file)) {
                $response["dir"] = $target_file;
                $response['status'] = 200;
                $response['message'] = "El recibo se ha guardado correctamente";
            } else {
                $response['message'] = "Lo siento, hubo un error al subir el archivo.";
            }
        }
        $sql = "UPDATE facturas SET recibo = '$target_file' WHERE id_factura = $id_factura";
        $res = $this->simple_insert($sql);
        if (count($res) > 0) {
            $response['status'] = 200;
            $response['message'] = "El recibo se ha guardado correctamente";
        } else {
            $response['message'] = "Lo siento, hubo un error al subir el archivo.";
        }

        return $response;
    }
}
