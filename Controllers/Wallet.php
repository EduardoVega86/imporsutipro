<?php
require 'vendor/autoload.php';

class Wallet extends Controller
{
    public function __construct()
    {
        // si se busca el controlador y el metodo guias_reporte saltar la validacion de sesion
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
            exit;
        }
    }
    ///vistas

    /**
     * @return void
     */
    public function index(): void
    {
        if ($_SESSION["cargo"] != 10 && $_SESSION["cargo"] != 25) {
            header("Location: ". SERVERURL . "wallet/billetera");
        }
        $this->views->render($this, "index");
    }

    /**
     * @return void
     */
    public function masivo(): void
    {
        $this->views->render($this, "masivo");
    }

    /**
     * @return void
     */
    public function masivo2(): void
    {
        $this->views->render($this, "masivo2");
    }

    /**
     * @return void
     */
    public function billetera(): void
    {
        define("ENLACE", $_SESSION["id_plataforma"]);
        $this->views->render($this, "billetera");
    }

    /**
     * @param $id
     * @return void
     */
    public function editar($id): void
    {
        $total_venta = $_POST['total_venta'];
        $precio_envio = $_POST['precio_envio'];
        $full = $_POST['full'];
        $costo = $_POST['costo'];

        $response = $this->model->editar($id, $total_venta, $precio_envio, $full, $costo);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function solicitudes(): void
    {
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "solicitudes");
    }

    /**
     * @return void
     */
    public function solicitudes_referidos(): void
    {
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "solicitudes_referidos");
    }

    /**
     * @return void
     */
    public function historial_solicitudes(): void
    {
        $this->views->render($this, "historial_solicitudes");
    }

    /**
     * @return void
     */
    public function auditoria_guias(): void
    {
        if ($_SESSION["cargo"] != 10 && $_SESSION["cargo"] != 25) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "auditoria_guias");
    }

    /**
     * @return void
     */
    public function auditoria_guias_total(): void
    {
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "auditoria_guias_total");
    }

    /**
     * @return void
     */
    public function pagar(): void
    {
        $tienda = $_GET['id_plataforma'];
        if ($_SESSION["cargo"] != 10 && $_SESSION["cargo"] != 25) {
            header("Location: /wallet/billetera");
        }
        $existe = $this->model->existeTienda($tienda);

        if (empty($existe)) {
            $this->model->crearBilletera($tienda);
        }

        $this->views->render($this, "pagar");
    }

    /**
     * @return void
     */
    public function datos_bancarios(): void
    {
        $this->views->render($this, "datos_bancarios");
    }

    //funciones

    /**
     * @param $id
     * @return void
     */
    public function obtenerCabecera($id): void
    {
        $response = $this->model->obtenerCabecera($id);
        echo json_encode($response);
    }

    /**
     * @param $id
     * @return void
     */
    public function eliminar($id): void
    {
        $response = $this->model->eliminar($id);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function cambiarEstado(): void
    {
        $id = $_POST['id_cabecera'];
        $estado = $_POST['estado'];

        $response = $this->model->cambiarEstado($id, $estado);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerDatos(): void
    {
        $datos = $this->model->obtenerTiendas();
        echo $datos;
    }

    /**
     * @return void
     */
    public function obtenerBilleteraTienda(): void
    {
        $datos = $this->model->obtenerBilleteraTienda($_SESSION["id_plataforma"]);
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function obtenerBilleteraTienda_plataforma(): void
    {
        $id_plataforma = $_POST['id_plataforma'];
        $datos = $this->model->obtenerBilleteraTienda_plataforma($id_plataforma);
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function obtenerDetalles(): void
    {
        $tienda = $_POST['tienda'];

        $datos = $this->model->obtenerDatos($tienda);
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function obtenerFacturas(): void
    {
        $tienda = $_POST['tienda'];
        $estado = $_POST['estado'] ?? 0;
        $transportadora = $_POST['transportadora'] ?? 0;
        $filtro = $_POST['filtro'];
        $datos = $this->model->obtenerFacturas($tienda, $filtro, $estado, $transportadora);
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function abonarBilletera(): void
    {
        $id_cabecera = $_POST['id_cabecera'];
        $valor = $_POST['valor'];
        $usuario = $_SESSION['id'];

        $datos = $this->model->abonarBilletera($id_cabecera, $valor, $usuario);
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function verificarPago(): void
    {
        $id_solicitud = $_POST['id_solicitud'];
        $datos = $this->model->verificarPago($id_solicitud);
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function reversarAbono(): void
    {
        $id_cabecera = $_POST['id_cabecera'];
        $valor = $_POST['valor'];
        $usuario = $_SESSION['id'];

        $datos = $this->model->reversarAbono($id_cabecera, $valor, $usuario);
    }

    /**
     * @return void
     */
    public function obtenerDatosBancarios(): void
    {
        $datos = $this->model->obtenerDatosBancarios($_SESSION["id_plataforma"]);
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function guardarDatosBancarios(): void
    {
        $banco = $_POST['banco'];
        $tipo_cuenta = $_POST['tipo_cuenta'];
        $numero_cuenta = $_POST['numero_cuenta'];
        $nombre = $_POST['nombre'];
        $cedula = $_POST['cedula'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];

        $datos = $this->model->guardarDatosBancarios($banco, $tipo_cuenta, $numero_cuenta, $nombre, $cedula, $correo, $telefono, $_SESSION["id_plataforma"]);;
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function eliminarDatoBancario(): void
    {
        $id_cuenta = $_POST['id_cuenta'];
        $datos = $this->model->eliminarDatoBancario($id_cuenta);
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function guardarDatosFacturacion(): void
    {
        $ruc = $_POST['ruc'];
        $razon_social = $_POST['razon_social'];
        $direccion = $_POST['direccion'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];

        $datos = $this->model->guardarDatosFacturacion($ruc, $razon_social, $direccion, $correo, $telefono, $_SESSION["id_plataforma"]);
        echo json_encode($datos);
    }

    /**
     * @return void
     */
    public function eliminarDatoFacturacion(): void
    {
        $id_facturacion = $_POST['id_facturacion'];
        $datos = $this->model->eliminarDatoFacturacion($id_facturacion);
        echo json_encode($datos);
    }


    /**
     * @return void
     */
    public function obtenerDatosFacturacion(): void
    {
        $dato_bancarios = $this->model->obtenerDatosBancarios($_SESSION["id_plataforma"]);
        $dato_facturacion = $this->model->obtenerDatosFacturacion($_SESSION["id_plataforma"]);

        $datos = [
            "datos_bancarios" => $dato_bancarios,
            "datos_facturacion" => $dato_facturacion
        ];

        echo json_encode($datos);
    }


    /**
     * @return void
     */
    public function solicitarPago(): void
    {
        $id_cuenta = $_POST['id_cuenta'] ?? 0;
        $valor = $_POST['valor'] ?? 0;
        $otro = $_POST['otro'] ?? 0;

        if ($valor == 0) {
            echo json_encode(["status" => 400, "message" => "El valor a solicitar no puede ser 0"]);
            return;
        }

        if ($id_cuenta == 0) {
            echo json_encode(["status" => 400, "message" => "Debes seleccionar una cuenta o agregar una nueva"]);
            return;
        }


        $puedeSolicitar = $this->model->puedeSolicitar($_SESSION["id_plataforma"], $valor);
        if (!$puedeSolicitar) {
            echo json_encode(["status" => 400, "message" => "No puedes solicitar un pago mayor al saldo disponible en tu billetera o ya tienes una solicitud pendiente"]);
            return;
        }
        $fecha = date("Y-m-d H:i:s");

        $response = $this->model->solicitarPago($id_cuenta, $valor, $_SESSION["id_plataforma"], $otro, $_SESSION["id"]);
        if ($response["status"] == 200) {
            $correo = $this->model->obtenerCorreo($_SESSION["id_plataforma"]);
            $this->model->enviarMensaje("solicitud", $correo[0]["correo"] ?? '', $valor);
        }
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerCodigoVerificacion(): void
    {
        $codigo = $_POST['codigo'];
        $response = $this->model->obtenerCodigoVerificacion($codigo, $_SESSION["id_plataforma"]);

        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function generarCodigoVerificacion(): void
    {
        $response = $this->model->generarCodigoVerificacion($_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function pagarFactura(): void
    {
        $valor = $_POST['valor'];
        $documento = $_POST['documento'];
        $forma_pago = $_POST['forma_pago'];
        $fecha = date("Y-m-d H:i:s");
        $imagen = $_FILES['imagen'];
        $id_plataforma = $_POST['id_plataforma'];

        $subirImagen = $this->model->subirImagen($imagen);

        if ($subirImagen['status'] == 1) {
            $response = $this->model->pagarFactura($valor, $documento, $forma_pago, $fecha, $subirImagen['dir'], $id_plataforma);
            echo json_encode($response);
        } else {
            echo json_encode($subirImagen);
        }
    }

    /**
     * @return void
     */
    public function obtenerHistorial(): void
    {
        $tienda = $_POST['tienda'];
        $response = $this->model->obtenerHistorial($tienda);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerCuentas(): void
    {
        $id_plataforma = $_SESSION['id_plataforma'];
        $response = $this->model->obtenerCuentas($id_plataforma);
        echo json_encode($response);
    }

    /**
     * @param $id
     * @return void
     */
    public function devolucion($id): void
    {
        $response = $this->model->devolucion($id);
        echo json_encode($response);
    }

    /**
     * @param $id
     * @return void
     */
    public function entregar($id): void
    {
        $response = $this->model->entregar($id);
        echo json_encode($response);
    }

    /**
     * @param $id
     * @return void
     */
    public function transito($id): void
    {
        $response = $this->model->transito($id);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function agregarOtroPago(): void
    {
        $tipo = $_POST['tipo'];
        $cuenta = $_POST['cuenta'];
        $red = $_POST['red'];

        $response = $this->model->agregarOtroPago($tipo, $cuenta, $_SESSION['id_plataforma'], $red);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function buscarFull(): void
    {
        $numero_factura = $_POST['numero_factura'];
        $id_plataforma = $_SESSION['id_plataforma'];
        $response = $this->model->buscarFull($numero_factura, $id_plataforma);
    }

    /**
     * @return void
     */
    public function eliminarOtroPago(): void
    {
        $id = $_POST['id'];

        $response = $this->model->eliminarMetodo($id);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerOtroPago(): void
    {
        $response = $this->model->obtenerOtroPago($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerOtroPagosReferidos(): void
    {
        $response = $this->model->obtenerOtroPagosReferidos();
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerSolicitudes(): void
    {
        $response = $this->model->obtenerSolicitudes();
        echo json_encode($response);
    }

    /**
     * @param $id
     * @return void
     */
    public function eliminarSolicitudes($id): void
    {
        $response = $this->model->eliminarSolicitudes($id);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerSolicitudes_otrasFormasPago(): void
    {
        $response = $this->model->obtenerSolicitudes_otrasFormasPago();
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerSolicitudes_otrasFormasPago_Referidos(): void
    {
        $response = $this->model->obtenerSolicitudes_otrasFormasPagosReferidos();
        echo json_encode($response);
    }

    /**
     * @param $id
     * @return void
     */
    public function eliminarSolicitudes_referidos($id): void
    {
        $response = $this->model->eliminarSolicitudes_referidos($id);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerGuiasAuditoria(): void
    {
        //echo $estado;
        $estado = $_POST['estado'];
        $transportadora = $_POST['transportadora'];
        $response = $this->model->obtenerGuiasAuditoria($estado, $transportadora);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerTotalGuiasAuditoria(): void
    {
        //echo $estado;
        $estado = $_POST['estado'];
        $transportadora = $_POST['transportadora'];
        $response = $this->model->obtenerTotalGuiasAuditoria($estado, $transportadora);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function habilitarAuditoria(): void
    {
        $guia = $_POST['numero_guia'];
        $estado = $_POST['estado'];
        $response = $this->model->habilitarAuditoria($guia, $estado);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function buscarTienda(): void
    {
        $numero_factura = $_POST['numero_factura'];
        $response = $this->model->buscarTienda($numero_factura);
        echo json_encode($response);
    }

    /**
     * @return void
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function importarExcel(): void
    {
        // Obtener el ID de inventario desde el formulario
        $transportadora = $_POST['id_transportadora'];
        // Verificar y manejar el archivo subido
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['archivo']['tmp_name'];
            $fileName = $_FILES['archivo']['name'];
            $fileSize = $_FILES['archivo']['size'];
            $fileType = $_FILES['archivo']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            // Permitir solo archivos Excel

            $allowedfileExtensions = array('xlsx', 'xls');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $inputFileType = PHPExcel_IOFactory::identify($fileTmpPath);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $spreadsheet = $objReader->load($fileTmpPath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();
                $date_added = date("Y-m-d H:i:s");
                // Aquí puedes procesar los datos del Excel
                $fila = 0;
                $agregados = 0;
                //echo count($data);
                foreach ($data as $row) {
                    // echo $fila;
                    if ($fila > 0) {

                        //print_r ($data[$fila]); 
                        //  $response = $this->model->agregarProducto($codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $_SESSION['id_plataforma'], $stock_inicial, $bodega, $pcp, $pvp, $pref);
                        $response = $this->model->agregarAuditoria($data[$fila][0], $data[$fila][1], $data[$fila][2], $data[$fila][3], $transportadora);
                        // echo $response ['status'];
                        if ($response['status'] == 200) {
                            $agregados = $agregados + 1;
                        }
                        //print_r($response);

                        // echo $data[$fila][0];
                        //echo 'fila';
                    }
                    // $row es un array que contiene todas las celdas de una fila
                    //  print_r($row); // Ejemplo de impresión de la fila
                    $fila++;
                }


                $guardarArchivoResponse = $this->model->guardarArchivo($fileTmpPath, $fileName, $transportadora);

                if ($guardarArchivoResponse['status'] === 200) {
                    // El archivo se guardó correctamente, puedes continuar con la importación de datos
                    // $spreadsheet = ... (tu código de importación de Excel)

                    // Aquí puedes continuar con la lógica para procesar el archivo Excel y agregar registros en otras tablas
                    // $response = tu lógica para importar el archivo Excel

                    // Finalmente, enviar respuesta exitosa
                    $response['status'] = 200;
                    $response['title'] = 'Petición exitosa';
                    $response['message'] = 'El archivo fue subido y procesado correctamente.';
                    $response['file_url'] = $guardarArchivoResponse['url'];
                } else {
                    $response = $guardarArchivoResponse; // Error al guardar el archivo
                }

                if ($agregados > 0) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = $agregados . ' registros importados correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'NO se agregaron productos, revise el archvio e inténtelo nuevamente';
                }
                // Puedes almacenar la información procesada en la base de datos o manejarla como desees
                //$response = $this->model->importacion_masiva($data);
                // echo json_encode($response);
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Solo se permiten archivos Excel (xlsx, xls).';
                // return json_encode(['error' => 'Solo se permiten archivos Excel (xlsx, xls).']);
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al subir el archivo.';
            //echo json_encode(['error' => 'Error al subir el archivo.']);
        }
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function solicitudesReferidos(): void
    {
        $response = $this->model->solicitudesReferidos();
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function aprobarSolicitud(): void
    {
        $id_solicitud = $_POST['id_solicitud'];
        $response = $this->model->aprobarSolicitud($id_solicitud);
        echo json_encode($response);
    }

    /**
     * @param $tipo
     * @param $cantidad
     * @param $id_plataforma
     * @return void
     */
    public function historialSolicitud($tipo, $cantidad, $id_plataforma): void
    {
        $response = $this->model->historialSolicitud($tipo, $cantidad, $id_plataforma);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerHistorialSolicitudes(): void
    {
        $response = $this->model->obtenerHistorialSolicitudes();
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function historialSolicitudes(): void
    {
        $response = $this->model->historialSolicitudes($_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    ///debugs

    /**
     * @return void
     */
    public function devolucionAwallet(): void
    {
        $this->catchAsync(function () {

            $numero_guia = $_POST['numero_guia'];
            $response = $this->model->devolucionAwallet($numero_guia);
            echo json_encode($response);
        })();
    }

    /**
     * @return void
     */
    public function entregaAWallet(): void
    {
        $numero_guia = $_POST['numero_guia'];
        $response = $this->model->entregaAWallet($numero_guia);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function guiasAhistorial(): void
    {
        $numero_guia = $_POST['numero_guia'];
        $response = $this->model->guiasAhistorial($numero_guia);
        echo json_encode($response);
    }

    /**
     * @param $guia
     * @return void
     */
    public function guiasAproveedor($guia): void
    {
        $response = $this->model->guiasAproveedor($guia);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function guiasAcuadre(): void
    {
        $response = $this->model->guiasAcuadre();
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function retener(): void
    {
        $response = $this->model->retener($_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    // Procesos de PAGO AUTOMATICO NO COLOCAR CODIGO AQUI NI MODIFICAR ABSOLUTAMENTE NADA

    /**
     * @return void
     */
    public function pagar_laar(): void
    {
        $response = $this->model->pagar_laar();
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function guias_reporte(): void
    {

        $mes = $_POST['mes'] ?? date('m');
        $dia = $_POST['dia'] ?? 0;
        $rango = $_POST['rango'] ?? 0;
        $id_plataforma = $_POST['id_plataforma'] ?? $_SESSION['id_plataforma'];

        $response = $this->model->guias_reporte($mes, $dia, $rango, $id_plataforma);
        echo json_encode($response);
    }

    /**
     * @return void
     */
    public function obtenerCabeceras(): void
    {
        $limit = $_POST['limit'] ?? 10;
        $page = $_POST['page'] ?? 1;
        $offset = ($page - 1) * $limit;
        $transportadora = $_POST['transportadora'] ?? 0;
        $estado = $_POST['estado'] ?? 0;
        $fecha = $_POST['fecha'] ?? 0;
        $search = $_POST['search'] ?? "";

        $response = $this->model->obtenerCabeceras($limit, $offset, $transportadora, $estado, $fecha, $search, $page);

        echo json_encode($response);
    }

    public function manejarFullNega(): void
    {
        $this->catchJWT(function () {
            $data = $this->jsonData();
            if (empty($data)) {
                throw new Exception("No se recibieron datos", 400);
            }
            $this->dataVerifier("numero_factura", $data["numero_factura"]);

            $response = $this->model->manejarFullfilmentNegativo(["numero_factura" => $data["numero_factura"], $data["full"]], 0);
            echo json_encode($response);
        })();
    }
}
