<?php
require_once 'vendor/autoload.php';


use Dompdf\Dompdf;
use setasign\Fpdi\Fpdi;

class ManifiestosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function generarManifiestoGuias($arreglo)
    {
        if (count($arreglo) == 0) return;

        $string = "('" . implode("','", $arreglo) . "')";

        // Consulta de facturas con el número de productos
        $sql = "SELECT fc.*, 
                   (SELECT SUM(cantidad) 
                    FROM detalle_fact_cot dfc 
                    WHERE dfc.id_factura = fc.id_factura) AS numero_productos 
            FROM facturas_cot fc 
            WHERE fc.numero_guia IN $string";

        $resumen = $this->select($sql);

        // Verificar que se haya obtenido el resumen
        if (empty($resumen)) {
            return ['status' => '500', 'message' => 'No se encontraron datos para generar el PDF.'];
        }

        $html = $this->generarTablaManifiesto($resumen);

        // Generar el PDF con Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Ruta del archivo PDF
        $combinedPdfPath = $this->generateUniqueFilename('Manifiesto-', __DIR__ . '/manifiestos');
        $tempName = explode('-', $combinedPdfPath);
        $tempName[0] = str_replace(__DIR__ . '/manifiestos/', '', $tempName[0]);
        $lastNumber = glob(__DIR__ . '/manifiestos/' . $tempName[0] . '-*');
        if (count($lastNumber) > 0) {
            $lastNumber = explode('-', $lastNumber[count($lastNumber) - 1]);
            $lastNumber = $lastNumber[1];
            $lastNumber = explode('.', $lastNumber);
            $lastNumber = $lastNumber[0];
            $lastNumber = intval($lastNumber) + 1;
            $combinedPdfPath = __DIR__ . '/manifiestos/' . $tempName[0] . '-' . $lastNumber . '.pdf';
        } else {
            $combinedPdfPath = __DIR__ . '/manifiestos/' . $tempName[0] . '-1000.pdf';
        }

        // Guardar el PDF en el servidor
        file_put_contents($combinedPdfPath, $dompdf->output());

        // Devolver la respuesta
         $new_url = str_replace("/home/imporsuitpro/public_html/new", "", $combinedPdfPath);
            $new_url = "https://new.imporsuitpro.com" . $new_url;
            
        $reponse = [
            "url" => $combinedPdfPath,
                "download" => $new_url,
                "status" => "200"
        ];
        

        return $reponse;
    }

    public function generarManifiesto($arreglo)
    {

        if (count($arreglo) == 0) return;
        if (count($arreglo) > 0) {


            $string = "('" . implode("','", $arreglo) . "')";
            // echo $string;
            $sql = "SELECT dfc.id_producto, p.nombre_producto, sum(dfc.cantidad) AS cantidad, ib.*, v.* FROM detalle_fact_cot dfc LEFT JOIN productos p ON dfc.id_producto = p.id_producto LEFT JOIN inventario_bodegas ib ON dfc.id_inventario = ib.id_inventario LEFT JOIN variedades v ON ib.id_variante = v.id_variedad "
                . "WHERE dfc.numero_factura IN $string GROUP BY dfc.id_producto, p.nombre_producto, ib.id_inventario, v.id_variedad;  ";
            // echo $sql;

            $sql_guias = "SELECT numero_guia FROM facturas_cot WHERE numero_factura IN $string";
            $guias = $this->select($sql_guias);
            $guias = array_map(function ($guia) {
                return $guia['numero_guia'];
            }, $guias);



            $resumen = $this->select($sql);
            $html = $this->generarTablaHTML($resumen);

            $combinedPdfPath = $this->generateUniqueFilename('Lista-Compras-', __DIR__ . '/manifiestos');
            $tempName = explode('-', $combinedPdfPath);
            $tempName[0] = str_replace(__DIR__ . '/manifiestos/', '', $tempName[0]);
            $lastNumber = glob(__DIR__ . '/manifiestos/' . $tempName[0] . '-*');
            if (count($lastNumber) > 0) {
                $lastNumber = explode('-', $lastNumber[count($lastNumber) - 1]);
                $lastNumber = $lastNumber[1];
                $lastNumber = explode('.', $lastNumber);
                $lastNumber = $lastNumber[0];
                $lastNumber = intval($lastNumber) + 1;
                $combinedPdfPath = __DIR__ . '/manifiestos/' . $tempName[0] . '-' . $lastNumber . '.pdf';
            } else {
                $combinedPdfPath = __DIR__ . '/manifiestos/' . $tempName[0] . '-1000.pdf';
            }

            $first = $this->generateFirstPdf($html);
            if (is_array($guias)) {
                $downloadedPdfs = [$first];
                foreach ($guias as $guia) {
                    if (strpos($guia, "IMP") === 0 || strpos($guia, "MKP") === 0) {
                        $pdf_content = file_get_contents("https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=" . $guia);
                    }
                    if ($pdf_content === false) {
                        exit("No se pudo obtener el PDF de la guía: $guia");
                    }
                    $tempPdfPath = $this->generateUniqueFilename('Temp-', __DIR__ . '/temporales');
                    file_put_contents($tempPdfPath, $pdf_content);
                    $downloadedPdfs[] = $tempPdfPath;
                }
                $this->combinePdfs($downloadedPdfs, $combinedPdfPath);
                foreach ($downloadedPdfs as $pdf) {
                    if (file_exists($pdf)) {
                        unlink($pdf);
                    }
                }
            }

            $new_url = str_replace("/home/imporsuitpro/public_html/new", "", $combinedPdfPath);
            $new_url = "https://new.imporsuitpro.com" . $new_url;

            // Devolver la respuesta
            $reponse = [
                "url" => $combinedPdfPath,
                "download" => $new_url,
                "status" => "200"
            ];
            return $reponse;
        }
    }

    public function generateFirstPdf($html)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfPath = $this->generateUniqueFilename('Lista-Compras-', __DIR__ . '/temporales');
        file_put_contents($pdfPath, $dompdf->output());
        return $pdfPath;
    }
    public function generateUniqueFilename($prefix, $directory = '.')
    {
        $tempFile = tempnam($directory, $prefix);
        unlink($tempFile); // Eliminar el archivo temporal creado por tempnam

        return $tempFile . '.pdf'; // Devolver el nombre de archivo con extensión .pdf
    }
    public function combinePdfs($pdfPaths, $outputPath)
    {
        $pdf = new Fpdi();
        foreach ($pdfPaths as $filePath) {
            $pageCount = $pdf->setSourceFile($filePath);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }
        $pdf->Output('F', $outputPath);
    }

    public function generarTablaHTML($data)
    {
        $html = '
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            @media screen and (max-width: 600px) {
                table, thead, tbody, th, td, tr {
                    display: block;
                    width: 100%;
                }
                th, td {
                    box-sizing: border-box;
                    width: 100%;
                    text-align: right;
                }
                tr {
                    margin-bottom: 15px;
                }
                td {
                    text-align: right;
                    padding-left: 50%;
                    position: relative;
                }
                td:before {
                    content: attr(data-label);
                    position: absolute;
                    left: 10px;
                    width: calc(50% - 10px);
                    padding-right: 10px;
                    white-space: nowrap;
                    text-align: left;
                }
            }
        </style>
        <table>
            <tr>
                <th>ID Producto</th>
                <th>Nombre Producto</th>
                <th>Cantidad</th>
                <th>Variedad</th>
            </tr>';
        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td data-label="ID Producto">' . htmlspecialchars($row['id_producto']) . '</td>';
            $html .= '<td data-label="Nombre Producto">' . htmlspecialchars($row['nombre_producto']) . '</td>';
            $html .= '<td data-label="Cantidad">' . htmlspecialchars($row['cantidad']) . '</td>';
            $html .= '<td data-label="Variedad">' . htmlspecialchars($row['variedad'] ?? "Sin variedad") . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    public function generarTablaManifiesto($data)
    {
        $fecha = date('Y-m-d H:i:s'); // Obtén la fecha y hora actual
        $html = '
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            @media screen and (max-width: 600px) {
                table, thead, tbody, th, td, tr {
                    display: block;
                    width: 100%;
                }
                th, td {
                    box-sizing: border-box;
                    width: 100%;
                    text-align: right;
                }
                tr {
                    margin-bottom: 15px;
                }
                td {
                    text-align: right;
                    padding-left: 50%;
                    position: relative;
                }
                td:before {
                    content: attr(data-label);
                    position: absolute;
                    left: 10px;
                    width: calc(50% - 10px);
                    padding-right: 10px;
                    white-space: nowrap;
                    text-align: left;
                }
            }
        </style>
        <table>
         <tr>
                <th>Fecha</th>
                <th>' . $fecha . '</th>
               
            </tr>
          </table>
        <table>
            <tr>
                <th>Numero</th>
                <th>Guia</th>
                <th>Nombre</th>
                <th>Productos</th>
                <th>Monto a cobrar</th>
            </tr>';
        $numero = 1;
        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td data-label="ID Producto">' . $numero . '</td>';
            $html .= '<td data-label="Nombre Producto">' . htmlspecialchars($row['numero_guia']) . '</td>';
            $html .= '<td data-label="Cantidad">' . htmlspecialchars($row['c_principal']) . ' ' . htmlspecialchars($row['c_secundaria']) . '</td>';
            $html .= '<td data-label="Variedad"> ' . htmlspecialchars($row['numero_productos']) . '</td>';
            $html .= '<td data-label="Variedad">$ ' . htmlspecialchars($row['monto_factura']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    public function generarTablaDescripcion($facturas)
    {
        //      echo 'asd'.$facturas;
        $datos = $this->select("SELECT * FROM facturas_cot WHERE numero_factura = '$facturas' ");
        $productos = $this->select("SELECT * FROM detalle_fact_cot WHERE numero_factura = '$facturas' ");

        $factura = $datos[0];
        $html = $this->generarHtmlUnico($factura, $productos);
        return $html;
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
        $producto = "";
        $productoT = "";
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

        $productoT .= "
        <div class='page-break'></div>

            <table class='products-table'>
                
                <tr>
                    <th>Productos</th>
                    <th>FECHA MANIFIESTO (DD/MM/YYYY) " . $fecha_actual . "
                    </th>
                </tr>
                
            </table>
        
        <table class='products-table-inv'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
            " . $producto_html . "
            </tbody>
        </table>
        </section>
        ";

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
        $html .= $productoT;

        $html .= ' </main>
        </body>
        </html>';




        return $html;
    }
}
