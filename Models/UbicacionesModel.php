<?php

class UbicacionesModel extends Query
{
    public function obtener_provincias()
    {
        $sql = "SELECT * FROM provincia_laar";
        return $this->select($sql);
    }

    public function obtenerUbicaciones($id)
    {
        $sql = "SELECT * FROM ciudad_cotizacion WHERE codigo_provincia_laar = $id";
        return $this->select($sql);
    }
}
