<?php
require_once 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');

use Dompdf\Dompdf;
use setasign\Fpdi\Fpdi;
use Picqer\Barcode\BarcodeGeneratorHTML;

class ManifiestosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }


    public function generarManifiestoDevolucion($arreglo)
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


        $sql_bodega = "SELECT  b.nombre as bodega, b.contacto, b.responsable, b.direccion FROM `facturas_cot` fc, detalle_fact_cot dfc, inventario_bodegas ib, bodega b WHERE numero_guia in $string and fc.id_factura=dfc.id_factura  and ib.id_inventario=dfc.id_inventario and ib.bodega=b.id limit 1;";
        //echo $sql_bodega;
        //  echo $sql_factura;$id_factura
        $bodega = $this->select($sql_bodega);
        $bodega_nombre = $bodega[0]['bodega'];
        $telefono = $bodega[0]['contacto'];
        $responsable = $bodega[0]['responsable'];
        $direccion = $bodega[0]['direccion'];


        // $html ='<h3 style="text-align: center;>tecto</h3>';
        $html = $this->generarTablaManifiestoDev($resumen, $bodega_nombre, $direccion, $telefono, $responsable);
        //echo $html;
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

        if (URL_MATRIZ == "https://app.merkapro.ec/") {
            $new_url = "https://new.imporsuitpro.com" . $new_url;
        } else {

            $new_url = URL_MATRIZ . $new_url;
        }

        $reponse = [
            "url" => $combinedPdfPath,
            "download" => $new_url,
            "status" => "200"
        ];


        return $reponse;
    }

    public function generarManifiestoGuias($arreglo, $id_cabecera, $transportadora)
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


        $sql_bodega = "SELECT b.id as id, b.nombre as bodega, b.contacto, b.responsable, b.direccion FROM `facturas_cot` fc, detalle_fact_cot dfc, inventario_bodegas ib, bodega b WHERE numero_guia in $string and fc.id_factura=dfc.id_factura  and ib.id_inventario=dfc.id_inventario and ib.bodega=b.id limit 1;";
        //echo $sql_bodega;
        //  echo $sql_factura;$id_factura
        $bodega = $this->select($sql_bodega);
        $bodega_nombre = $bodega[0]['bodega'];
        $telefono = $bodega[0]['contacto'];
        $responsable = $bodega[0]['responsable'];
        $direccion = $bodega[0]['direccion'];
        $id_bodega = $bodega[0]['id'];



        // $html ='<h3 style="text-align: center;>tecto</h3>';
        $html = $this->generarTablaManifiesto($resumen, $bodega_nombre, $direccion, $telefono, $responsable, $transportadora);
        //echo $html;
        // Generar el PDF con Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Ruta del archivo PDF
        $combinedPdfPath = $this->generateUniqueFilename('RelacionDespacho-', __DIR__ . '/manifiestos');
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
        $new_url = URL_MATRIZ . $new_url;

        $reponse = [
            "url" => $combinedPdfPath,
            "download" => $new_url,
            "status" => "200"
        ];

        $update = "UPDATE cabecera_relacion_despacho SET url_documento = '$new_url' WHERE id_relacion_despacho = $id_cabecera";
        $this->select($update);

        return $reponse;
    }


    public function cambiarImpreso($arreglo)
    {
        $string = "('" . implode("','", $arreglo) . "')";
        if (count($arreglo) == 0) return;
        if (count($arreglo) > 0) {
            $update = "UPDATE facturas_cot SET impreso = 1 WHERE id_factura IN $string";
            $this->select($update);
        }
    }
    public function generarManifiesto($arreglo)
    {

        if (count($arreglo) == 0) return;
        if (count($arreglo) > 0) {


            $string = "('" . implode("','", $arreglo) . "')";
            // echo $string;
            $sql = "SELECT dfc.id_producto, 
                p.nombre_producto, 
                SUM(dfc.cantidad) AS cantidad, 
                ib.*, 
                v.*, 
                b.nombre 
            FROM detalle_fact_cot dfc
            LEFT JOIN productos p ON dfc.id_producto = p.id_producto
            LEFT JOIN inventario_bodegas ib ON dfc.id_inventario = ib.id_inventario
            LEFT JOIN variedades v ON ib.id_variante = v.id_variedad
            LEFT JOIN bodega b ON ib.bodega = b.id 
            WHERE dfc.id_factura IN $string
            GROUP BY dfc.id_producto, 
                    p.nombre_producto, 
                    ib.id_inventario, 
                    v.id_variedad, 
                    b.id ";
            // echo $sql;

            $sql_guias = "SELECT numero_guia FROM facturas_cot WHERE id_factura IN $string";
            $guias = $this->select($sql_guias);
            $guias = array_map(function ($guia) {
                return $guia['numero_guia'];
            }, $guias);

            //            $update = "UPDATE facturas_cot SET impreso = 1 WHERE numero_factura IN $string";
            //            $this->select($update);


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


$local_path = "public/repositorio/guias/guia_$guia.pdf";
    
    // Verifica si el archivo existe en el repositorio local
    if (file_exists($local_path)) {
        // Lee el archivo local
        $pdf_content = file_get_contents($local_path);
       // echo 'si existe'.$pdf_content;
       // echo $guia;
    } else {


                    if (strpos($guia, "IMP") === 0 || strpos($guia, "MKP") === 0 || strpos($guia, "EIZ") === 0) {
                        $pdf_content = file_get_contents("https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=" . $guia);
                       //  echo 'no existe'. $pdf_content;
                    } else if (is_numeric($guia)) {
                        $pdf_content = file_get_contents("https://guias.imporsuitpro.com/Servientrega/Guia/" . $guia);
                    } else if (strpos($guia, "I00") === 0) {
                        $pdf_content = file_get_contents("https://guias.imporsuitpro.com/Gintracom/label/" . $guia);
                    } else if (strpos($guia, "SPD") === 0 || strpos($guia, "MKL") === 0) {
                        $pdf_content = file_get_contents("https://guias.imporsuitpro.com/Speed/descargar/" . $guia);
                    }
                    if ($pdf_content === false) {
                        exit("No se pudo obtener el PDF de la guía: $guia");
                    }
                    
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
            if (URL_MATRIZ == "https://app.merkapro.ec/") {
                $new_url = "https://new.imporsuitpro.com" . $new_url;
            } else {

                $new_url = URL_MATRIZ . $new_url;
            }

            if (URL_MATRIZ == "https://desarrollo.imporsuitpro.com/") {
                $new_url =  str_replace("/home/imporsuitpro/public_html/desarrollo", "", $combinedPdfPath);
            }

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
        $id_usuario = $_SESSION['id'];
        $sql_usuario = "SELECT nombre_users FROM users WHERE id_users = $id_usuario";
        $usuario = $this->select($sql_usuario);
        $nombre_usuario = $usuario[0]['nombre_users'];
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
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            width: 100%;
            text-align: center;
        }
        hr {
            width: 200px;
            border: 1px solid #000;
            margin: 10px auto;
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
    
    <p style="text-align: center; font-size: 20px;"><strong> LISTA DE COMPRAS</strong></p>
    <p style="text-align: center; font-size: 12px;">IMPRESO POR ' . strtoupper($nombre_usuario) . '</p>
    <p style="text-align: center; font-size: 12px;">' . $fecha . '</p>
    
    <table>
        <tr>
            <th>ID Producto</th>
            <th>Nombre Producto</th>
            <th>Cantidad</th>
             <th>Bodega</th>
            <th>Variedad</th>
        </tr>';

        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td data-label="ID Producto">' . htmlspecialchars($row['id_producto']) . '</td>';
            $html .= '<td data-label="Nombre Producto">' . htmlspecialchars($row['nombre_producto']) . '</td>';
            $html .= '<td data-label="Cantidad">' . htmlspecialchars($row['cantidad']) . '</td>';
            $html .= '<td data-label="Bodega">' . htmlspecialchars($row['nombre']) . '</td>';
            $html .= '<td data-label="Variedad">' . htmlspecialchars($row['variedad'] ?? "Sin variedad") . '</td>';
            $html .= '</tr>';
        }

        $html .= '
    </table>
    <div class="footer">
        <hr>
        <p>' . $nombre_usuario . '</p>
    </div>';

        return $html;
    }



    public function generarTablaManifiesto($data, $bodega_nombre, $direccion, $telefono, $responsable, $transportadora)
    {
        $fecha = date('Y-m-d H:i:s'); // Obtén la fecha y hora actual
        $generator = new BarcodeGeneratorHTML();

        $id_usuario = $_SESSION['id'];
        $sql_usuario = "SELECT nombre_users FROM users WHERE id_users = $id_usuario";
        $usuario = $this->select($sql_usuario);
        $nombre_usuario = $usuario[0]['nombre_users'];
        switch ($transportadora) {
            case 1:
                $transportadora_nombre = 'LARR COURRIER';
                break;
            case 2:
                $transportadora_nombre = 'SERVIENTREGA';
                break;
            case 3:
                $transportadora_nombre = 'GINTRACOM';
                break;
            case 4:
                $transportadora_nombre = 'SPEED';
                break;
            default:
                $transportadora_nombre = 'DESCONOCIDA';
                break;
        }

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
            font-size: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .barcode img {
            width: 100px; /* Ajusta el tamaño según sea necesario */
            height: auto;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            width: 100%;
            text-align: center;
        }
        hr {
            width: 200px;
            border: 1px solid #000;
            margin: 10px auto;
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
    <p style="text-align: center; font-size: 20px;"><strong>' . strtoupper($bodega_nombre) . '</strong></p>
    <p style="text-align: center; font-size: 12px;">' . strtoupper($direccion) . '</p>
    <p style="text-align: center; font-size: 12px;">' . strtoupper($responsable) . ' / ' . strtoupper($telefono) . '</p>
    <table>
        <tr>
         <th>' . $transportadora_nombre . '</th>
            <th>Responsable</th>
            <th>' . $nombre_usuario . '</th>
            <th>Fecha</th>
            <th>' . $fecha . '</th>
        </tr>
    </table>
    <table>
        <tr>
            <th style="width:5%">#</th>
            <th style="width:25%">Guia</th>
            <th style="width:20%">Cliente</th>
            <th style="width:20%">Contiene</th>
            <th style="width:10%">Productos</th>
            <th style="width:20%">Monto a cobrar</th>
        </tr>';

        $numero = 1;
        foreach ($data as $row) {
            $codigoBarras = $generator->getBarcode($row['numero_guia'], $generator::TYPE_CODE_128);
            $html .= '<tr>';
            $html .= '<td data-label="ID Producto">' . $numero . '</td>';
            $html .= '<td data-label="Documento"><div class="barcode">' . $codigoBarras . '</div><br>' . htmlspecialchars($row['numero_guia']) . '</td>';
            $html .= '<td data-label="Cliente">' . htmlspecialchars($row['nombre']) . '</td>';
            $html .= '<td data-label="Contiene">' . htmlspecialchars($row['contiene']) . '</td>';
            $html .= '<td data-label="No Productos">' . htmlspecialchars($row['numero_productos']) . '</td>';
            if ($row['cod'] == 1) {
                $monto_cobrar = htmlspecialchars($row['monto_factura']);
            } else {
                $monto_cobrar = 0;
            }
            $html .= '<td data-label="Monto a Cobrar">$ ' . number_format($monto_cobrar, 2) . '</td>';
            $html .= '</tr>';
            $numero++;
        }

        $html .= '
    </table>
    <div class="footer">
        <hr>
        <p>' . $nombre_usuario . '</p>
    </div>';

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

    public function despacho_guia($num_guia, $plataforma, $id_cabecera)
    {

        $response = $this->initialResponse();


        $sql = "INSERT INTO detalle_relacion_despacho (numero_guia, id_cabecera_despacho) VALUES (?, ?)";
        $data = [$num_guia, $id_cabecera];
        // Ejecuta la inserción
        $insertar_detalle_rd = $this->insert($sql, $data);

        $sql_factura = "SELECT * FROM facturas_cot WHERE numero_guia = '$num_guia'";
        //  echo $sql_factura;
        $factura = $this->select($sql_factura);
        $id_factura = $factura[0]['id_factura'];
        $estado_factura = $factura[0]['estado_factura'];

        $sql_plataforma_bodega = "SELECT b.id_plataforma FROM `detalle_fact_cot` dfc, inventario_bodegas  ib, bodega b where ib.bodega=b.id and id_factura=$id_factura and dfc.id_inventario=ib.id_inventario GROUP by bodega";
        //echo $sql_factura;$id_factura
        $plataforma_bodega = $this->select($sql_plataforma_bodega);
        $id_plataforma_bodega = $plataforma_bodega[0]['id_plataforma'];

        if ($id_plataforma_bodega == $plataforma) {
            if ($estado_factura == 1) {
                //  echo $id_factura;

                $tmp_cotizaciones = $this->select("SELECT * FROM detalle_fact_cot WHERE id_factura = $id_factura");
                $detalle_sql_despacho = "INSERT INTO `historial_depacho` (`id_pedido`, `guia`, `id_producto`, `sku`, `cantidad`, `id_usuario`, `id_plataforma`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                // $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $detalle_sql_historial = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`, `saldo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                //print_r($tmp_cotizaciones);
                //$nota='Se descuenta'
                $id_usuario = $_SESSION['id'];
                foreach ($tmp_cotizaciones as $tmp) {
                    //  echo 'enta';
                    $despacho_data = array(
                        $id_factura,
                        $num_guia,
                        $tmp['id_producto'],
                        $tmp['sku'],
                        $tmp['cantidad'],
                        $id_usuario,
                        $plataforma
                    );
                    $guardar_detalle = $this->insert($detalle_sql_despacho, $despacho_data);
                    $nota = 'Se elimina ' . $tmp['cantidad'] . ' productos(s) del inventario -DESPACHO GUIA-';
                    $id_inventario = $tmp['id_inventario'];
                    $sql_bodega = "SELECT bodega FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                    //echo $sql_bodega;
                    $bodega = $this->select($sql_bodega);
                    //print_r($bodega);
                    $id_bodega = $bodega[0]['bodega'];

                    $sql_id = "SELECT saldo_stock FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                    $stock = $this->select($sql_id);
                    $stock_inventario = $stock[0]['saldo_stock'];
                    $saldo_stock = $stock_inventario - $tmp['cantidad'];
                    $sql_update = "update inventario_bodegas set saldo_stock=? where id_inventario=?";
                    $data = [$saldo_stock, $id_inventario];
                    $actualizar_stock = $this->update($sql_update, $data);
                    
                    $historial_data = array(
                        $id_usuario,
                        $tmp['id_inventario'],
                        $plataforma,
                        $tmp['sku'],
                        $nota,
                        $num_guia,
                        $tmp['cantidad'],
                        2,
                        $id_bodega,
                        $tmp['id_producto'],
                        $saldo_stock
                    );
                    $guardar_detalle = $this->insert($detalle_sql_historial, $historial_data);

                    


                    //print_r($guardar_detalle);
                }

                $sql = "UPDATE `facturas_cot` SET `estado_factura` = ? WHERE `id_factura` = ?";
                $data = [2, $id_factura];
                $editar_categoria = $this->update($sql, $data);

                //print_r($tmp_cotizaciones);

                if ($editar_categoria == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Despacho Exitoso';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al generar el despacho';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Esta guia ya ha sido despachada';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'La guía no pertenece a esta bodega';
        }
        return $response;
    }


    public function despacho_guia_devolucion($num_guia, $plataforma, $id_cabecera)
    {

        $response = $this->initialResponse();


        $sql = "INSERT INTO detalle_devolucion (numero_guia, id_cabecera_devolucion) VALUES (?, ?)";
        $data = [$num_guia, $id_cabecera];
        // Ejecuta la inserción
        $insertar_detalle_rd = $this->insert($sql, $data);

        //print_r($insertar_detalle_rd);
        $sql_factura = "SELECT * FROM facturas_cot WHERE numero_guia = '$num_guia'";
        //  echo $sql_factura;
        $factura = $this->select($sql_factura);
        $id_factura = $factura[0]['id_factura'];
        $estado_factura = $factura[0]['estado_factura'];

        $sql_plataforma_bodega = "SELECT b.id_plataforma FROM `detalle_fact_cot` dfc, inventario_bodegas  ib, bodega b where ib.bodega=b.id and id_factura=$id_factura and dfc.id_inventario=ib.id_inventario GROUP by bodega";
        //echo $sql_factura;$id_factura
        $plataforma_bodega = $this->select($sql_plataforma_bodega);
        $id_plataforma_bodega = $plataforma_bodega[0]['id_plataforma'];

        if ($id_plataforma_bodega == $plataforma) {
            if ($estado_factura == 1) {
                //  echo $id_factura;

                $tmp_cotizaciones = $this->select("SELECT * FROM detalle_fact_cot WHERE id_factura = $id_factura");
                $detalle_sql_despacho = "INSERT INTO `historial_depacho` (`id_pedido`, `guia`, `id_producto`, `sku`, `cantidad`, `id_usuario`, `id_plataforma`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                // $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $detalle_sql_historial = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`, `saldo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                //print_r($tmp_cotizaciones);
                //$nota='Se descuenta'
                $id_usuario = $_SESSION['id'];
                foreach ($tmp_cotizaciones as $tmp) {
                    //  echo 'enta';
                    $despacho_data = array(
                        $id_factura,
                        $num_guia,
                        $tmp['id_producto'],
                        $tmp['sku'],
                        $tmp['cantidad'],
                        $id_usuario,
                        $plataforma
                    );
                    $guardar_detalle = $this->insert($detalle_sql_despacho, $despacho_data);
                    $nota = 'Se elimina ' . $tmp['cantidad'] . ' productos(s) del inventario -DESPACHO GUIA-';
                    $id_inventario = $tmp['id_inventario'];
                    $sql_bodega = "SELECT bodega FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                    //echo $sql_bodega;
                    $bodega = $this->select($sql_bodega);
                    //print_r($bodega);
                    $id_bodega = $bodega[0]['bodega'];

                    
                    $sql_id = "SELECT saldo_stock FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                    $stock = $this->select($sql_id);
                    $stock_inventario = $stock[0]['saldo_stock'];
                    $saldo_stock = $stock_inventario - $tmp['cantidad'];
                    $sql_update = "update inventario_bodegas set saldo_stock=? where id_inventario=?";
                    $data = [$saldo_stock, $id_inventario];
                    $actualizar_stock = $this->update($sql_update, $data);
                    
                    $historial_data = array(
                        $id_usuario,
                        $tmp['id_inventario'],
                        $plataforma,
                        $tmp['sku'],
                        $nota,
                        $num_guia,
                        $tmp['cantidad'],
                        2,
                        $id_bodega,
                        $tmp['id_producto'],
                        $saldo_stock
                    );
                    $guardar_detalle = $this->insert($detalle_sql_historial, $historial_data);

                    


                    //print_r($guardar_detalle);
                }

                $sql = "UPDATE `facturas_cot` SET `estado_factura` = ? WHERE `id_factura` = ?";
                $data = [2, $id_factura];
                $editar_categoria = $this->update($sql, $data);

                //print_r($tmp_cotizaciones);

                if ($editar_categoria == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Despacho Exitoso';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al generar el despacho';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Esta guia ya ha sido despachada';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'La guía no pertenece a esta bodega';
        }
        return $response;
    }

    public function guardarCabecera($bodega, $transportadora, $plataforma)
    {

        $id_usuario = $_SESSION['id'];
        $fecha_actual = date('Y-m-d H:i:s');

        $sql = "INSERT INTO cabecera_relacion_despacho (id_usuario, id_plataforma, id_transportadora, id_bodega, fecha_hora) VALUES (?, ?, ?, ?, ?)";
        $data = [$id_usuario, $plataforma, $transportadora, $bodega, $fecha_actual];
        // Ejecuta la inserción
        $insertar_producto = $this->insert($sql, $data);

        //print_r($insertar_producto);

        $sql_id = "SELECT id_relacion_despacho FROM cabecera_relacion_despacho WHERE id_usuario = $id_usuario "
            . "and id_transportadora = $transportadora and id_bodega = $bodega and fecha_hora = '$fecha_actual' ";
        $stock = $this->select($sql_id);
        //echo $sql_id;
        $lastInsertId = $stock[0]['id_relacion_despacho'];

        // Obtiene el ID del último registro insertado

        // Devuelve el ID generado
        return $lastInsertId;
    }

    public function guardarCabeceraDevolucion($plataforma)
    {

        $id_usuario = $_SESSION['id'];
        $fecha_actual = date('Y-m-d H:i:s');

        $sql_id_bodega = "SELECT * FROM bodega WHERE id_plataforma = $plataforma ";
        //echo $sql_id_bodega; 
        $bodega_select = $this->select($sql_id_bodega);
        //echo $sql_id;
        $id_bodega = $bodega_select[0]['id'];


        $sql = "INSERT INTO cabecera_devolucion (id_usuario, id_plataforma, fecha_hora, id_bodega) VALUES (?, ?, ?, ?)";
        $data = [$id_usuario, $plataforma, $fecha_actual, $id_bodega];
        // Ejecuta la inserción
        $insertar_producto = $this->insert($sql, $data);

        //print_r($insertar_producto);

        $sql_id = "SELECT id_devolucion FROM cabecera_devolucion WHERE id_usuario = $id_usuario "
            . " and fecha_hora = '$fecha_actual' ";
        $stock = $this->select($sql_id);
        //echo $sql_id;
        $lastInsertId = $stock[0]['id_devolucion'];

        // Obtiene el ID del último registro insertado

        // Devuelve el ID generado
        return $lastInsertId;
    }
    public function generarManifiestoGuiasDevolucion($arreglo, $id_cabecera)
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


        $sql_bodega = "SELECT b.id as id, b.nombre as bodega, b.contacto, b.responsable, b.direccion FROM `facturas_cot` fc, detalle_fact_cot dfc, inventario_bodegas ib, bodega b WHERE numero_guia in $string and fc.id_factura=dfc.id_factura  and ib.id_inventario=dfc.id_inventario and ib.bodega=b.id limit 1;";
        //echo $sql_bodega;
        //  echo $sql_factura;$id_factura
        $bodega = $this->select($sql_bodega);
        $bodega_nombre = $bodega[0]['bodega'];
        $telefono = $bodega[0]['contacto'];
        $responsable = $bodega[0]['responsable'];
        $direccion = $bodega[0]['direccion'];
        $id_bodega = $bodega[0]['id'];



        // $html ='<h3 style="text-align: center;>tecto</h3>';
        $html = $this->generarTablaManifiestoDevolucion($resumen, $bodega_nombre, $direccion, $telefono, $responsable);
        //echo $html;
        // Generar el PDF con Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Ruta del archivo PDF
        $combinedPdfPath = $this->generateUniqueFilename('Devolucion-', __DIR__ . '/manifiestos');
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
        $new_url = URL_MATRIZ . $new_url;

        $reponse = [
            "url" => $combinedPdfPath,
            "download" => $new_url,
            "status" => "200"
        ];

        $update = "UPDATE cabecera_devolucion SET url_documento = '$new_url' WHERE id_devolucion = $id_cabecera";
        $this->select($update);

        return $reponse;
    }
    public function generarTablaManifiestoDevolucion($data, $direccion, $telefono, $responsable)
    {
        $fecha = date('Y-m-d H:i:s'); // Obtén la fecha y hora actual
        $generator = new BarcodeGeneratorHTML();

        $id_usuario = $_SESSION['id'];
        $sql_usuario = "SELECT nombre_users FROM users WHERE id_users = $id_usuario";
        $usuario = $this->select($sql_usuario);
        $nombre_usuario = $usuario[0]['nombre_users'];


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
            font-size: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .barcode img {
            width: 100px; /* Ajusta el tamaño según sea necesario */
            height: auto;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            width: 100%;
            text-align: center;
        }
        hr {
            width: 200px;
            border: 1px solid #000;
            margin: 10px auto;
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
    <p style="text-align: center; font-size: 12px;">DEVOLUCIÓN</p>
    <p style="text-align: center; font-size: 12px;">' . strtoupper($direccion) . '</p>
    <p style="text-align: center; font-size: 12px;">' . strtoupper($responsable) . ' / ' . strtoupper($telefono) . '</p>
    <table>
        <tr>
         <th>Responsable</th>
            <th>' . $nombre_usuario . '</th>
            <th>Fecha</th>
            <th>' . $fecha . '</th>
        </tr>
    </table>
    <table>
        <tr>
            <th style="width:5%">#</th>
            <th style="width:25%">Guia</th>
            <th style="width:20%">Cliente</th>
            <th style="width:20%">Contiene</th>
            <th style="width:10%">Productos</th>
            <th style="width:20%">Monto a cobrar</th>
        </tr>';

        $numero = 1;
        foreach ($data as $row) {
            $codigoBarras = $generator->getBarcode($row['numero_guia'], $generator::TYPE_CODE_128);
            $html .= '<tr>';
            $html .= '<td data-label="ID Producto">' . $numero . '</td>';
            $html .= '<td data-label="Documento"><div class="barcode">' . $codigoBarras . '</div><br>' . htmlspecialchars($row['numero_guia']) . '</td>';
            $html .= '<td data-label="Cliente">' . htmlspecialchars($row['nombre']) . '</td>';
            $html .= '<td data-label="Contiene">' . htmlspecialchars($row['contiene']) . '</td>';
            $html .= '<td data-label="No Productos">' . htmlspecialchars($row['numero_productos']) . '</td>';
            if ($row['cod'] == 1) {
                $monto_cobrar = htmlspecialchars($row['monto_factura']);
            } else {
                $monto_cobrar = 0;
            }
            $html .= '<td data-label="Monto a Cobrar">$ ' . number_format($monto_cobrar, 2) . '</td>';
            $html .= '</tr>';
            $numero++;
        }

        $html .= '
    </table>
    <div class="footer">
        <hr>
        <p>' . $nombre_usuario . '</p>
    </div>';

        return $html;
    }
}
