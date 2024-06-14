<?php

class ManifiestosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function generarManifiesto($arreglo)
    {
        if (count($arreglo) == 0) return;
        if (count($arreglo) == 1) {
            $this->generarManifiestoUnico($arreglo[0]);
        }
    }

    public function generarManifiestoUnico($factura)
    {
        $datos = $this->select("SELECT * FROM facturas_cot WHERE numero_factura = '$factura' ");
        $productos = $this->select("SELECT * FROM detalle_fact_cot WHERE numero_factura = '$factura' ");

        $factura = $datos[0];
        $html = $this->generarHtml($factura, $productos);
    }

    public function generarHtml($factura, $productos)
    {
        $fecha_actual = date("d/m/Y");
        $manifiesto = "";
        $transporte = "";
        $guia = $factura['numero_guia'];
        if (strpos($guia, "IMP") === 0 || strpos($guia, "MKP") === 0) {
            $transporte = "LAAR";
        } else {
            $transporte = "";
        }
        $producto_html = "";
        $productos_id = [];
        foreach ($productos as $producto) {
            $id_producto = $producto['id_producto'];
            array_push($productos_id, ["id" => $id_producto, "cantidad" => $producto['cantidad']]);
        }

        ///si se repite un id dejar solo uno y sumar la cantidad
        $productos_id = array_map("unserialize", array_unique(array_map("serialize", $productos_id)));

        foreach ($productos_id as $producto) {
            $id = $producto['id'];
            $nombre_producto = $this->select("SELECT nombre FROM productos WHERE id = '$id' ")[0]['nombre'];


        $manifiestoT = "
            <table class='section1-table'>
                <tr>
                    <td>
                    TRANSPORTADORA
                    </td>
                    <td>
                    TRANSPORTADORA: " . $transporte . "
                    </td>
                </tr>
                <tr>
                    <td>
                    RELACION DE GUIAS IMPRESAS
                    </td>
                    <td>
                    FECHA MANIFIESTO (DD/MM/YYYY): " . $fecha_actual . "
                    </td>
                </tr>
            </table>
            <table class='section2-table'>
            " . $manifiesto
            . "
            </table>
            "
            . "
                <table class='section3-table'>
            <tr>
                <td>NOMBRE DE ENCARGADO DEL MANIFIESTO:</td>
            </tr>
            <tr>
                <td>PLACA DEL VEHICULO:</td>
            </tr>
            <tr>
                <td>FIRMA DEL ENCARGADO DEL MANIFIESTO:</td>
            </tr>
        </table>";


        return $html;
    }
}
