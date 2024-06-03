<?php
class ProductosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cargarProductos($filtro = "")
    {
        if (!empty($filtro)) {
            $sql = "SELECT * FROM productos WHERE $filtro";
            return $this->select($sql);
        } else {
            $sql = "SELECT * FROM productos";
        }
        return $this->select($sql);
    }

    public function cargarCategorias($plataforma)
    {
        $sql = "SELECT * FROM lineas WHERE id_plataforma = $plataforma";
        return $this->select($sql);
    }
}
