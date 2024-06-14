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
            $html = $this->generarManifiestoUnico($arreglo[0]);
            return  $html;
        }
    }

    public function generarManifiestoUnico($factura)
    {
        $datos = $this->select("SELECT * FROM facturas_cot WHERE numero_factura = '$factura' ");
        $productos = $this->select("SELECT * FROM detalle_fact_cot WHERE numero_factura = '$factura' ");

        $factura = $datos[0];
        $html = $this->generarHtmlUnico($factura, $productos);
        return $html;
    }

    public function generarHtmlUnico($factura, $productos)
    {
        $fecha_actual = date("d/m/Y");
        $manifiesto = "";
        $transporte = "";
        $guia = $factura['numero_guia'];
        $ciudad = $factura['ciudad_cot'];
        $ciudad_destino = $this->select("SELECT ciudad from ciudad_cotizacion where id_cotizacion = '$ciudad' ")[0]['ciudad'];

        $monto_factura = $factura['monto_factura'];
        $cod = $factura['cod'];
        if ($cod == 1) {
            $cod = "Con Recaudo";
        } else {
            $cod = "Sin Recaudo";
        }
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
            $nombr = $this->select("SELECT nombre_producto, codigo_producto FROM productos WHERE id_producto = '$id' ")[0];
            $nombre_producto = $nombr['nombre_producto'];
            $codigo_producto = $nombr['codigo_producto'];
            $cantidad = $producto['cantidad'];
            $html_producto = "
            <tr>
            <td> ( ID: " . $id_producto . " ) - ( SKU: " . $codigo_producto . " ) - " . $nombre_producto . " </td>
            <td> " . $cantidad . "</td>
        </tr>";
            $producto_html .= $html_producto;
        }

        $manifiesto = "
        <tr>
        <td>Nro: 1 </td>
        <td>Guia: " . $guia . " </td>
        <td>Ciudad Destino: " . $ciudad_destino . " </td>
        <td>Valor de Recaudo: " . $monto_factura . "</td>
        <td>Tipo de logistica: " . $cod . "</td>
    </tr>
        ";

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

        $html = '   <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Guia Impresas</title>

        <style>
        * {
                    margin: 0;
                    padding: 10px;
                    box-sizing: border-box;
                }

                .section1-table,
                .section2-table,
                .section3-table,
                .products-table,
                .products-table-inv {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }

                .section1-table td,
                .section2-table td,
                .section3-table td {
                    border: 1px solid black;
                    padding: 10px;
                }

                .products-table th,
                .products-table td {
                    border: 1px solid black;
                    padding: 10px;
                    text-align: left;
                }

                .products-table th {
                    width: 25%;
                }

                .products-table th:last-child {
                    width: 75%;
                }

                .products-table-inv th,
                .products-table-inv td {
                    border: 1px solid black;
                    padding: 10px;
                    text-align: left;
                }

                .products-table-inv th {
                    width: 75%;
                }

                .products-table-inv th:last-child {
                    width: 25%;
                }

                .page-break {
                    page-break-before: always;
                }
        </style>
        </head>
        <body>
            <main>';
        $html .= $manifiestoT;
        $html .= "<table class='section4-table'>
        <tr>
            <td>PRODUCTO</td>
            <td>CANTIDAD</td>
        </tr>
        " . $producto_html . "
    </table>";

        $html .= ' </main>
        </body>
        </html>';




        return $html;
    }
}
