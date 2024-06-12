<?php
class GestionModel extends Query
{
    public function actualizarEstado($estado, $guia)
    {
        $sql = "UPDATE facturas_cot set estado_guia = '$estado' WHERE numero_guia = '$guia' ";
        return $this->select($sql);
    }

    public function entregada($estado, $guia)
    {
        $datos = "SELECT * FROM facturas_cot WHERE numero_guia = '$guia' ";
        $select = $this->select($datos);
        $data_factura = $select[0];
    }
}
