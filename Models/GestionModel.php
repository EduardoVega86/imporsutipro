<?php
class GestionModel extends Query
{
    public function actualizarEstado($estado, $guia)
    {
        $sql = "UPDATE facturas_cot set estado_guia = '$estado' WHERE numero_guia = '$guia' ";
        $response =  $this->select($sql);
        if ($estado == 7) {
            $this->EnviarWalletEntrega($estado, $guia);
        }
    }

    public function entregada($estado, $guia)
    {
        $datos = "SELECT * FROM facturas_cot WHERE numero_guia = '$guia' ";
        $select = $this->select($datos);
        $data_factura = $select[0];
    }

    public function EnviarWalletEntrega($estado, $guia)
    {
        $datos = "SELECT * FROM facturas_cot WHERE numero_guia = '$guia' ";
        $select = $this->select($datos);
    }
}
