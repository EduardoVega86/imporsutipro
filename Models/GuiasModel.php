<?php
class GuiasModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerDestinatario($id_producto)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_producto";
        return $this->select($sql);
    }
}
