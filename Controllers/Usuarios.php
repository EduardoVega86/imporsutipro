<?php
require 'vendor/autoload.php';

class Usuarios extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header("Location:  " . SERVERURL . "login");
        }
    }

    public function index()
    {
        $this->views->render($this, "index");
    }

    public function roles()
    {
        $this->views->render($this, "roles");
    }

    public function importacion()
    {
        $this->views->render($this, "importacion");
    }

    public function listamatriz()
    {
        $this->views->render($this, "listamatriz");
    }

    public function listado()
    {
        $this->views->render($this, "listado");
    }

    public function tienda_online()
    {
        $this->views->render($this, "tienda_online");
    }




    public function cargarUsuarios()
    {
        $data = $this->model->cargarUsuarios();
        return $data;
    }

    public function cargarRoles()
    {
        $data = $this->model->cargarRoles();
        return $data;
    }

    public function guardarUsuario()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $response = $this->model->guardarUsuario($data);
        echo json_encode($response);
    }

    public function actualizarUsuario()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $response = $this->model->actualizarUsuario($data);
        echo json_encode($response);
    }

    public function eliminarUsuario()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $response = $this->model->eliminarUsuario($data);
        echo json_encode($response);
    }

    //obtener datos de user_group
    public function obtener_userGroup()
    {
        $response = $this->model->obtener_userGroup($_SESSION['id_plataforma']);
        https: //
        echo json_encode($response);
    }


    public function resetearContrasena()
    {
        $contrasena = $_POST['contrasena'];
        $id_usuario = $_POST['id_usuario'];
        $response = $this->model->resetearContrasena($id_usuario,  $contrasena);

        echo json_encode($response);
    }

    public function obtener_usuarios_matriz()
    {
        $response = $this->model->obtener_usuarios_matriz();
        echo json_encode($response);
    }

    public function obtener_usuarios_plataforma()
    {
        $response = $this->model->obtener_usuarios_plataforma($_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    public function agregarProveedor()
    {
        $id_plataforma = $_POST['id_plataforma'];
        $proveedor = $_POST['proveedor'];
        $response = $this->model->agregarProveedor($id_plataforma, $proveedor);
        echo json_encode($response);
    }

    public function agregarFull()
    {
        $id_plataforma = $_POST['id_plataforma'];
        $proveedor = $_POST['proveedor'];
        $response = $this->model->agregarFull($id_plataforma, $proveedor);
        echo json_encode($response);
    }


    public function importarExcel()
    {

        // Obtener el ID de inventario desde el formulario
        //$id_inventario = $_POST['id_bodega'];

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
                $prefix = 'tmp_';
                $timestamp = time();
                $uniqueId = uniqid();
                $tienda = $prefix . $timestamp . '_' . $uniqueId;

                foreach ($data as $row) {
                    // echo $fila;
                    if ($fila > 0) {

                        //print_r ($data[$fila]); 
                        //  $response = $this->model->agregarProducto($codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $_SESSION['id_plataforma'], $stock_inicial, $bodega, $pcp, $pvp, $pref);
                        $response = $this->model->registro($data[$fila][0], $data[$fila][1], $data[$fila][2], $data[$fila][3], $data[$fila][4], $tienda);;
                        // echo $response ['status'];
                        if ($response['status'] == 200) {
                            $agregados = $agregados + 1;
                        } else {
                            $duplicados = $duplicados + 1;
                        }
                        //print_r($response);

                        // echo $data[$fila][0];
                        //echo 'fila';
                    }
                    // $row es un array que contiene todas las celdas de una fila
                    //  print_r($row); // Ejemplo de impresión de la fila
                    $fila++;
                }
                if ($agregados > 0) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = $agregados . ' productos importados correctamente';
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

    public function guardar_imagen_logo()
    {

        $response = $this->model->guardar_imagen_logo($_FILES['imagen'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }
    public function guardar_imagen_favicon()
    {

        $response = $this->model->guardar_imagen_favicon($_FILES['imagen'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function sigue_logeado()
    {
        if (isset($_SESSION['login_time']) && isset($_SESSION['session_lifetime'])) {
            $tiempo_transcurrido = time() - $_SESSION['login_time'];
            $tiempo_restante = $_SESSION['session_lifetime'] - $tiempo_transcurrido;

            if ($tiempo_restante <= 0) {
                echo json_encode(['status' => 400, 'message' => 'Sesion expirada']);
            } else {
                echo json_encode(['status' => 200, 'message' => 'Sesion activa', 'tiempo_restante' => $tiempo_restante]);
            }
        } else {
            echo json_encode(['status' => 500, 'message' => 'Sesion no iniciada']);
        }
    }

    /* tienda online */
    public function obtener_infoTiendaOnline()
    {
        $response = $this->model->obtener_infoTiendaOnline($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_bannertienda()
    {
        $response = $this->model->obtener_bannertienda($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_caracteristica()
    {
        $response = $this->model->obtener_caracteristicas($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_bannertiendaID()
    {
        $id = $_POST['id'];
        $response = $this->model->obtener_bannertiendaID($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function agregarBanner()
    {
        $titulo = $_POST['titulo'];
        $texto_banner = $_POST['texto_banner'];
        $texto_boton = $_POST['texto_boton'];
        $enlace_boton = $_POST['enlace_boton'];
        $alineacion = $_POST['alineacion'];
        $imagen = $_FILES['imagen'];
        $response = $this->model->agregarBanner($titulo, $texto_banner, $texto_boton, $enlace_boton, $alineacion, $imagen, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function editarBanner()
    {
        $id_banner = $_POST['id_banner'];
        $titulo = $_POST['titulo'];
        $texto_banner = $_POST['texto_banner'];
        $texto_boton = $_POST['texto_boton'];
        $enlace_boton = $_POST['enlace_boton'];
        $alineacion = $_POST['alineacion'];
        $imagen = isset($_FILES['imagen']) ? $_FILES['imagen'] : null;

        $response = $this->model->editarBanner($id_banner, $titulo, $texto_banner, $texto_boton, $enlace_boton, $alineacion, $imagen, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    public function eliminarBanner()
    {
        $id = $_POST['id'];
        $response = $this->model->eliminarBanner($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_testimonios()
    {

        $response = $this->model->obtener_testimonios($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function agregarTestimonios()
    {
        $nombre = $_POST['nombre'];
        $testimonio = $_POST['testimonio'];
        $fecha = date('Y-m-d H:i:s');
        $imagen = $_FILES['imagen'];
        $response = $this->model->agregarTestimonios($nombre, $testimonio, $fecha, $imagen, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }
    /* fin tienda online */

    public function registro()
    {
        $nombre = $_POST['nombre'];
        $response = $this->model->crearSubdominio($nombre, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function cambiarcolor()
    {
        $campo = $_POST['campo'];
        $valor = $_POST['valor'];
        $response = $this->model->cambiarcolortienda($campo, $valor, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }
}
