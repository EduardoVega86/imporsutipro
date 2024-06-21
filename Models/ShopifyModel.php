<?php
class ShopifyModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function iniciarPlataforma($id_plataforma)
    {
        $sql = "INSERT INTO shopify (id_plataforma) VALUES (:id_plataforma)";
    }

    public function existenciaPlataforma($id_plataforma)
    {
        $sql = "SELECT id_plataforma FROM configuracion_shopify WHERE id_plataforma =?";
        $params = array($id_plataforma);
        $response = $this->simple_select($sql, $params);
        if ($response > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function generarEnlace($id_plataforma)
    {
        $sql = "SELECT url_imporsuit from plataformas where id_plataforma = $id_plataforma";
        $response = $this->select($sql);
        $url = $response[0]["url_imporsuit"];
        $responses = array(
            "url_imporsuit" => $url . "/shopify/index/" . $id_plataforma,
        );
        return $responses;
    }

    public function guardarConfiguracion($id_plataforma, $data)
    {
        $sql = "INSERT INTO configuracion_shopify (id_plataforma, nombre, apellido, principal, secundario, provincia, ciudad, codigo_postal, pais, telefono, email, total, descuento) VALUES (?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $response = $this->insert($sql, $data);
        if ($response == 1) {
            $response["status"] = "200";
            $response["message"] = "Configuracion guardada correctamente";
        } else {
            $response["status"] = "500";
            $response["message"] = "Error al guardar la configuracion";
        }
        return $response;
    }

    public function verificarConfiguracion($id_plataforma)
    {
        $sql = "SELECT * FROM configuracion_shopify WHERE id_plataforma = ?";
        $params = array($id_plataforma);
        $response = $this->simple_select($sql, $params);
        if ($response > 0) {
            $response = array(
                "status" => "200",
                "message" => "Configuracion encontrada",
            );
        } else {
            $response = array(
                "status" => "500",
                "message" => "No se ha encontrado la configuracion",
            );
        }
        return $response;
    }
}
