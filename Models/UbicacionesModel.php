<?php

class UbicacionesModel extends Query
{
    public function obtener_provincias($pais)
    {
        if ($pais == 0) {
            $sql = "SELECT * FROM provincia_laar";
        } else {
            $sql = "SELECT * FROM provincia_laar WHERE id_pais = $pais";
        }
        return $this->select($sql);
    }

    public function obtenerUbicaciones($id)
    {
        $sql = "SELECT * FROM ciudad_cotizacion WHERE codigo_provincia_laar = $id";
        return $this->select($sql);
    }

    public function obtenerCiudad($id)
    {
        $sql = "SELECT ciudad FROM ciudad_cotizacion where id_cotizacion = $id";
        return $this->select($sql);
    }
}
