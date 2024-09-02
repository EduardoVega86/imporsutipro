<?php
require 'vendor/autoload.php';
class Wallet extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
    }
    ///vistas

    public function index()
    {
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "index");
    }

    public function billetera()
    {
        define("ENLACE", $_SESSION["id_plataforma"]);
        $this->views->render($this, "billetera");
    }

    public function editar($id)
    {
        $total_venta = $_POST['total_venta'];
        $precio_envio = $_POST['precio_envio'];
        $full = $_POST['full'];
        $costo = $_POST['costo'];

        $response = $this->model->editar($id, $total_venta, $precio_envio, $full, $costo);
        echo json_encode($response);
    }

    public function solicitudes()
    {
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "solicitudes");
    }

    public function solicitudes_referidos()
    {
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "solicitudes_referidos");
    }

    public function auditoria_guias()
    {
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $this->views->render($this, "auditoria_guias");
    }

    //funciones

    public function obtenerCabecera($id)
    {
        $response = $this->model->obtenerCabecera($id);
        echo json_encode($response);
    }

    public  function  eliminar($id)
    {
        $response = $this->model->eliminar($id);
        echo json_encode($response);
    }

    public function cambiarEstado()
    {
        $id = $_POST['id_cabecera'];
        $estado = $_POST['estado'];

        $response = $this->model->cambiarEstado($id, $estado);
        echo json_encode($response);
    }

    public function pagar()
    {
        $tienda = $_GET['id_plataforma'];
        if ($_SESSION["cargo"] != 10) {
            header("Location: /wallet/billetera");
        }
        $existe = $this->model->existeTienda($tienda);

        if (empty($existe)) {
            $this->model->crearBilletera($tienda);
        }

        $this->views->render($this, "pagar");
    }

    public function datos_bancarios()
    {
        $this->views->render($this, "datos_bancarios");
    }

    ///
    public function obtenerDatos()
    {
        $datos = $this->model->obtenerTiendas();
        echo $datos;
    }

    public function obtenerDetalles()
    {
        $tienda = $_POST['tienda'];
        $datos = $this->model->obtenerDatos($tienda);
        echo json_encode($datos);
    }

    public function obtenerFacturas()
    {
        $tienda = $_POST['tienda'];
        $filtro = $_POST['filtro'];
        $datos = $this->model->obtenerFacturas($tienda, $filtro);
        echo json_encode($datos);
    }

    public function abonarBilletera()
    {
        $id_cabecera = $_POST['id_cabecera'];
        $valor = $_POST['valor'];
        $usuario = $_SESSION['id'];

        $datos = $this->model->abonarBilletera($id_cabecera, $valor, $usuario);
        echo json_encode($datos);
    }

    public function verificarPago()
    {
        $id_solicitud = $_POST['id_solicitud'];
        $datos = $this->model->verificarPago($id_solicitud);
        echo json_encode($datos);
    }

    public function reversarAbono()
    {
        $id_cabecera = $_POST['id_cabecera'];
        $valor = $_POST['valor'];
        $usuario = $_SESSION['id'];

        $datos = $this->model->reversarAbono($id_cabecera, $valor, $usuario);
    }

    public function obtenerDatosBancarios()
    {
        $datos = $this->model->obtenerDatosBancarios($_SESSION["id_plataforma"]);
        echo json_encode($datos);
    }

    public function guardarDatosBancarios()
    {
        $banco = $_POST['banco'];
        $tipo_cuenta = $_POST['tipo_cuenta'];
        $numero_cuenta = $_POST['numero_cuenta'];
        $nombre = $_POST['nombre'];
        $cedula = $_POST['cedula'];
        $correo = $_POST['correo'];
        $telefono    = $_POST['telefono'];

        $datos = $this->model->guardarDatosBancarios($banco, $tipo_cuenta, $numero_cuenta, $nombre, $cedula, $correo, $telefono, $_SESSION["id_plataforma"]);;
        echo json_encode($datos);
    }

    public function eliminarDatoBancario()
    {
        $id_cuenta = $_POST['id_cuenta'];
        $datos = $this->model->eliminarDatoBancario($id_cuenta);
        echo json_encode($datos);
    }

    public function guardarDatosFacturacion()
    {
        $ruc = $_POST['ruc'];
        $razon_social = $_POST['razon_social'];
        $direccion = $_POST['direccion'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];

        $datos = $this->model->guardarDatosFacturacion($ruc, $razon_social, $direccion, $correo, $telefono, $_SESSION["id_plataforma"]);
        echo json_encode($datos);
    }

    public function eliminarDatoFacturacion()
    {
        $id_facturacion = $_POST['id_facturacion'];
        $datos = $this->model->eliminarDatoFacturacion($id_facturacion);
        echo json_encode($datos);
    }


    public function obtenerDatosFacturacion()
    {
        $dato_bancarios = $this->model->obtenerDatosBancarios($_SESSION["id_plataforma"]);
        $dato_facturacion = $this->model->obtenerDatosFacturacion($_SESSION["id_plataforma"]);

        $datos = [
            "datos_bancarios" => $dato_bancarios,
            "datos_facturacion" => $dato_facturacion
        ];

        echo json_encode($datos);
    }


    public function solicitarPago()
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
        if ($puedeSolicitar == false) {
            echo json_encode(["status" => 400, "message" => "No puedes solicitar un pago mayor al saldo disponible en tu billetera o ya tienes una solicitud pendiente"]);
            return;
        }
        $fecha = date("Y-m-d H:i:s");

        $response = $this->model->solicitarPago($id_cuenta, $valor, $_SESSION["id_plataforma"], $otro);
        if ($response["status"] == 200) {
            $correo = $this->model->obtenerCorreo($_SESSION["id_plataforma"]);
            $this->model->enviarMensaje("solicitud", $correo[0]["correo"] ?? '', $valor);
        }
        echo json_encode($response);
    }

    public function obtenerCodigoVerificacion()
    {
        $codigo = $_POST['codigo'];
        $response = $this->model->obtenerCodigoVerificacion($codigo, $_SESSION["id_plataforma"]);

        echo json_encode($response);
    }

    public function generarCodigoVerificacion()
    {
        $response = $this->model->generarCodigoVerificacion($_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    public function pagarFactura()
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

    public function obtenerHistorial()
    {
        $tienda     = $_POST['tienda'];
        $response = $this->model->obtenerHistorial($tienda);
        echo json_encode($response);
    }

    public function obtenerCuentas()
    {
        $id_plataforma = $_SESSION['id_plataforma'];
        $response = $this->model->obtenerCuentas($id_plataforma);
        echo json_encode($response);
    }

    public function devolucion($id)
    {
        $response = $this->model->devolucion($id);
        echo json_encode($response);
    }

    public function entregar($id)
    {
        $response = $this->model->entregar($id);
        echo json_encode($response);
    }

    public function agregarOtroPago()
    {
        $tipo = $_POST['tipo'];
        $cuenta = $_POST['cuenta'];
        $red = $_POST['red'];



        $response = $this->model->agregarOtroPago($tipo, $cuenta, $_SESSION['id_plataforma'], $red);
        echo json_encode($response);
    }

    public function buscarFull()
    {
        $numero_factura = $_POST['numero_factura'];
        $id_plataforma = $_SESSION['id_plataforma'];
        $response = $this->model->buscarFull($numero_factura, $id_plataforma);
    }

    public function eliminarOtroPago()
    {
        $id = $_POST['id'];

        $response = $this->model->eliminarMetodo($id);
        echo json_encode($response);
    }

    public function obtenerOtroPago()
    {
        $response = $this->model->obtenerOtroPago($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtenerOtroPagosReferidos()
    {
        $response = $this->model->obtenerOtroPagosReferidos();
        echo json_encode($response);
    }


    public function obtenerSolicitudes()
    {
        $response = $this->model->obtenerSolicitudes();
        echo json_encode($response);
    }

    public function eliminarSolicitudes($id)
    {
        $response = $this->model->eliminarSolicitudes($id);
        echo json_encode($response);
    }

    public function obtenerSolicitudes_otrasFormasPago()
    {
        $response = $this->model->obtenerSolicitudes_otrasFormasPago();
        echo json_encode($response);
    }

    public function obtenerSolicitudes_otrasFormasPago_Referidos()
    {
        $response = $this->model->obtenerSolicitudes_otrasFormasPagosReferidos();
        echo json_encode($response);
    }

    public function eliminarSolicitudes_referidos($id)
    {
        $response = $this->model->eliminarSolicitudes_referidos($id);
        echo json_encode($response);
    }

    public function obtenerGuiasAuditoria()
    {
        //echo $estado;
        $estado = $_POST['estado'];
        $transportadora = $_POST['transportadora'];
        $response = $this->model->obtenerGuiasAuditoria($estado, $transportadora);
        echo json_encode($response);
    }

    public function obtenerTotalGuiasAuditoria()
    {
        //echo $estado;
        $estado = $_POST['estado'];
        $transportadora = $_POST['transportadora'];
        $response = $this->model->obtenerTotalGuiasAuditoria($estado, $transportadora);
        echo json_encode($response);
    }



    public function habilitarAuditoria()
    {

        $guia = $_POST['numero_guia'];
        $estado = $_POST['estado'];


        $response = $this->model->habilitarAuditoria($guia, $estado);
        echo json_encode($response);
    }

    public function buscarTienda()
    {
        $numero_factura = $_POST['numero_factura'];
        $response = $this->model->buscarTienda($numero_factura);
        echo json_encode($response);
    }




    public function importarExcel()
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


                $guardarArchivoResponse =  $this->model->guardarArchivo($fileTmpPath, $fileName, $transportadora);

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

    public function solicitudesReferidos()
    {
        $response =  $this->model->solicitudesReferidos();
        echo json_encode($response);
    }

    public function aprobarSolicitud()
    {
        $id_solicitud = $_POST['id_solicitud'];
        $response = $this->model->aprobarSolicitud($id_solicitud);
        echo json_encode($response);
    }



    ///debugs

    public function devolucionAwallet()
    {
        $numero_guia = $_POST['numero_guia'];
        $response = $this->model->devolucionAwallet($numero_guia);
        echo json_encode($response);
    }

    public function guiasAhistorial()
    {
        $numero_guia = $_POST['numero_guia'];
        $response = $this->model->guiasAhistorial($numero_guia);
        echo json_encode($response);
    }

    public function guiasAproveedor($guia)
    {
        $response = $this->model->guiasAproveedor($guia);
        echo json_encode($response);
    }

    public function guiasAcuadre()
    {
        $response = $this->model->guiasAcuadre();
        echo json_encode($response);
    }
}
