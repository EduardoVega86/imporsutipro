<?php
session_start();
require 'vendor/autoload.php';
//use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Productos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        /* if (!$this->hasPermission(2)) {
            header("Location: /dashboard");
        } */
    }
    ///Vistas
    public function index()
    {
        $this->views->render($this, "index");
    }
    public function bodegas()
    {
        $this->views->render($this, "bodegas");
    }
    public function agregar_bodegas()
    {
        $this->views->render($this, "agregar_bodegas");
    }

    public function editar_bodegas()
    {
        $this->views->render($this, "editar_bodega");
    }
    public function locales()
    {
        $data = $this->model->cargarLocales();
        $this->views->render($this, "locales", $data);
    }

    public function categorias()
    {
        $this->views->render($this, "categorias");
    }

    public function verbodegas()
    {
        $this->views->render($this, "bodegas");
    }

    public function marketplace()
    {
        $this->views->render($this, "marketplace");
    }

    public function marketplace_privado()
    {
        $this->views->render($this, "marketplace_privado");
    }

    public function inventario()
    {
        $this->views->render($this, "inventario");
    }

    public function gestion_privados()
    {
        $this->views->render($this, "gestion_privados");
    }

    public function inventario_bodega()
    {
        $this->views->render($this, "inventario_bodega");
    }

    public function importacion_masiva()
    {
        $this->views->render($this, "importacion_masiva");
    }

    public function productos_tienda()
    {
        $this->views->render($this, "productos_tienda");
    }

    public function combos()
    {
        $this->views->render($this, "combos");
    }

    public function ofertas()
    {
        $this->views->render($this, "ofertas");
    }

    public function productos_2()
    {
        $this->views->render($this, "productos2");
    }

    public function landing($id)
    {
        $existe_producto = $this->model->verificarProducto($id);

        $this->views->render($this, "landing", $existe_producto);
    }

    public function landing_tienda($id)
    {
        $existe_producto = $this->model->verificarProductoTienda($id);

        $this->views->render($this, "landing_tienda", $existe_producto);
    }

    public function bovedas()
    {
        $this->views->render($this, "bovedas");
    }




    ///Funciones

    public function obtener_productos()
    {
        $response = $this->model->obtener_productos($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_productos_todos()
    {
        $response = $this->model->obtenerProductosTodos();
        echo json_encode($response);
    }

    public function obtener_productos_boveda()
    {
        $response = $this->model->obtener_productos_boveda($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_bovedas()
    {
        $response = $this->model->obtenerBovedas();
        echo json_encode($response);
    }
    //agregarswagger
    public function agregar_boveda()
    {
        // Verificar que el usuario tiene permisos (cargo 20)
        if ($_SESSION['cargo'] != 20) {
            $response = [
                "status" => 403,
                "title" => "Acceso denegado",
                "message" => "No tienes permisos para realizar esta acción."
            ];
            echo json_encode($response);
            return;
        }
        if (isset($_POST['id_producto'], $_POST['categoria'], $_POST['proveedor'])) {
            $idProducto = $_POST['id_producto'];
            $idLinea = $_POST['categoria'];
            $idProveedor = $_POST['proveedor'];
            $ejemploLanding = $_POST['ejemploLanding'] ?? '';
            $duplicarFunnel = $_POST['duplicarFunnel'] ?? '';
            $videos = $_POST['videosBoveda'] ?? '';

            // Verificar si se ha subido una imagen sin errores
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $_FILES['imagen'];
            } else {
                $imagen = null; // Puedes establecer un valor por defecto o null
            }

            // Insertar
            $response = $this->model->insertarBoveda($idProducto, $idLinea, $imagen, $idProveedor, $ejemploLanding, $duplicarFunnel, $videos);
        } else {
            $response = [
                "status" => 400,
                "title" => "Datos incompletos",
                "message" => "Faltan campos obligatorios."
            ];
        }
        echo json_encode($response);
    }


    public function obtenerBoveda($id)
    {
        $response = $this->model->obtenerBoveda($id);
        echo json_encode($response);
    }

    public function editar_boveda()
    {
        // Verificar que el usuario tiene permisos (cargo 20)
        if ($_SESSION['cargo'] != 20) {
            $response = [
                "status" => 403,
                "title" => "Acceso denegado",
                "message" => "No tienes permisos para realizar esta acción."
            ];
            echo json_encode($response);
            return;
        }
        // Obtener los datos del formulario
        $id_boveda = $_POST['id_boveda'];
        $id_producto = $_POST['id_producto'];
        $id_linea = $_POST['id_linea'];
        $id_plataforma = $_POST['id_plataforma'];
        $ejemplo_landing = $_POST['ejemplo_landing'];
        $duplicar_funnel = $_POST['duplicar_funnel'];
        $videos = $_POST['videos'];

        // Verificar si se ha subido una imagen sin errores
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = $_FILES['imagen'];
        } else {
            $imagen = null; // Puedes establecer un valor por defecto o null
        }

        // Llamar al modelo con los datos procesados
        $response = $this->model->editarBoveda(
            $id_boveda,
            $id_linea,
            $id_plataforma,
            $id_producto,
            $imagen,
            $ejemplo_landing,
            $duplicar_funnel,
            $videos
        );
        // Devolver la respuesta en formato JSON
        echo json_encode($response);
    }



    public function obtenerProveedores()
    {
        $proveedores = $this->model->obtenerProveedores();
        echo json_encode($proveedores);
    }


    public function obtener_lineas_global()
    {
        $response = $this->model->obtenerLineasGlobal();
        echo json_encode($response);
    }

    public function obtener_productos_bodega($bodega)
    {
        $response = $this->model->obtener_productos_bodega($bodega);
        echo json_encode($response);
    }


    public function obtener_productos2()
    {
        // Recoger los parámetros enviados por DataTables
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 25;
        $search = $_POST['search']['value'] ?? '';
        $orderColumnIndex = $_POST['order'][0]['column'] ?? 0; // Default order column
        $orderDir = $_POST['order'][0]['dir'] ?? 'desc';

        // Mapear el índice de la columna al nombre de la columna en la base de datos
        $columns = [
            0 => 'p.id_producto',
            1 => 'p.codigo_producto',
            2 => 'p.nombre_producto',
            3 => 'p.destacado',
            4 => 'ib.saldo_stock',
            5 => 'p.costo_producto',
            6 => 'p.pcp',
            7 => 'p.pvp',
            8 => 'p.pref',
        ];
        $orderColumn = $columns[$orderColumnIndex] ?? 'p.id_producto';

        $response = $this->model->obtener_productos2(
            $_SESSION['id_plataforma'],
            $start,
            $length,
            $search,
            $orderColumn,
            $orderDir
        );

        // Preparar la respuesta en el formato que espera DataTables
        echo json_encode([
            'draw' => $_POST['draw'],
            'recordsTotal' => $response['recordsTotal'],
            'recordsFiltered' => $response['recordsFiltered'],
            'data' => $response['data']
        ]);
    }


    public function obtener_productos_privados()
    {
        $response = $this->model->obtener_productos_privados($_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    public function obtener_productos_tienda()
    {
        $response = $this->model->obtener_productos_tienda($_SESSION['id_plataforma']);
        echo json_encode($response);
    }
    public function obtener_productos_tienda_automatizador()
    {
        $response = $this->model->obtener_productos_tienda_automatizador($_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    public function obtener_productos_inventario()

    {
        $response = $this->model->obtener_productos_inventario($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_producto($id)
    {
        $response = $this->model->obtenerProducto($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_bodegas()
    {
        $response = $this->model->obtener_bodegas($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_producto_tienda($id)
    {
        $response = $this->model->obtenerProductoTienda($id);
        echo json_encode($response);
    }

    public function eliminar_producto_tienda($id)
    {
        $response = $this->model->eliminar_producto_tienda($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    /// Funciones de bodegas

    public function agregarBodega()
    {
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion_completa'];
        $telefono = $_POST['telefono'];
        $ciudad = $_POST['ciudad_entrega'];
        $provincia = $_POST['provincia'];
        $contacto = $_POST['nombre_contacto'];
        $telefono_contacto = $telefono;
        $numerocasa = $_POST['numero_casa'];
        $referencia = $_POST['referencia'];
        $longitud = $_POST['longitud'];
        $latitud = $_POST['latitud'];
        $response = $this->model->agregarBodega($nombre, $direccion, $telefono, $ciudad, $provincia, $contacto, $telefono_contacto, $numerocasa, $referencia, $_SESSION['id_plataforma'], $longitud, $latitud);
        echo json_encode($response);
    }

    public function obtenerBodega($id)
    {
        $response = $this->model->obtenerBodega($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function editarBodega()
    {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion_completa'];
        $telefono = $_POST['telefono'];
        $ciudad = $_POST['ciudad_entrega'];
        $provincia = $_POST['provincia'];
        $contacto = $_POST['nombre_contacto'];
        $telefono_contacto = $telefono;
        $numerocasa = $_POST['numero_casa'];
        $referencia = $_POST['referencia'];
        $longitud = $_POST['longitud'];
        $latitud = $_POST['latitud'];
        $response = $this->model->editarBodega($id, $nombre, $direccion, $telefono, $ciudad, $provincia, $contacto, $telefono_contacto, $numerocasa, $referencia, $_SESSION['id_plataforma'], $longitud, $latitud);
        echo json_encode($response);
    }

    public function eliminarBodega()
    {
        $id = $_POST['id'];
        $response = $this->model->eliminarBodega($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function listar_bodegas()
    {
        $response = $this->model->listarBodegas($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function cargarBodegas()
    {
        $response = $this->model->cargarBodegas($_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    /// Funciones de categorias
    public function agregarCategoria()
    {
        $nombre_linea = $_POST['nombre_linea'];
        $descripcion_linea = $_POST['descripcion_linea'];
        $estado_linea = 1;
        $date_added = date("Y-m-d H:i:s");
        $online = $_POST['online'];
        $imagen = $_POST['imagen'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $padre = $_POST['padre'] ?? 0;
        $orden = $_POST['orden'];

        $response = $this->model->agregarCategoria($nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $_SESSION['id_plataforma'], $orden);
        echo json_encode($response);
    }

    public function editarCategoria()
    {
        $id = $_POST['id'];
        $nombre_linea = $_POST['nombre_linea'];
        $descripcion_linea = $_POST['descripcion_linea'];
        $estado_linea = 1;
        $date_added = date("Y-m-d H:i:s");
        $online = $_POST['online'];
        $imagen = $_POST['imagen'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $padre = $_POST['padre'] ?? 0;
        $orden = $_POST['orden'];
        $response = $this->model->editarCategoria($id, $nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $_SESSION['id_plataforma'], $orden);
        echo json_encode($response);
    }


    public function listarCategoria()
    {
        $id = $_POST['id'];
        $response = $this->model->listarCategoria($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function eliminarCategoria()
    {
        $id = $_POST['id'];
        $response = $this->model->eliminarCategoria($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function guardar_imagen_categorias()
    {

        $response = $this->model->guardar_imagen_categorias($_FILES['imagen'], $_POST['id_linea'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function guardar_imagen_productos()
    {

        $response = $this->model->guardar_imagen_productos($_FILES['imagen'], $_POST['id_producto'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function guardar_imagenAdicional_productos()
    {

        $response = $this->model->guardar_imagenAdicional_productos($_POST['num_imagen'], $_FILES['imagen'], $_POST['id_producto'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function guardar_imagen_productosTienda()
    {

        $response = $this->model->guardar_imagen_productosTienda($_FILES['imagen'], $_POST['id_producto'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function guardar_imagenAdicional_productosTienda()
    {

        $response = $this->model->guardar_imagenAdicional_productosTienda($_POST['num_imagen'], $_FILES['imagen'], $_POST['id_producto'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function listar_imagenAdicional_productos()
    {
        $response = $this->model->listar_imagenAdicional_productos($_POST['id_producto'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function listar_imagenAdicional_productosTienda()
    {
        $response = $this->model->listar_imagenAdicional_productosTienda($_POST['id_producto'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function cargar_categorias()
    {
        $response = $this->model->cargarCategorias($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function cargar_templates()
    {
        $response = $this->model->cargarTemplates($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function cargar_etiquetas()
    {
        $response = $this->model->cargarEtiquetas($_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    /// Funciones de productos

    public function agregar_producto()
    {
        $codigo_producto = $_POST['codigo_producto'];
        $nombre_producto = $_POST['nombre_producto'];
        $descripcion_producto = $_POST['descripcion_producto'];
        $id_linea_producto = $_POST["id_linea_producto"];
        $inv_producto = $_POST['inv_producto'];
        $producto_variable = $_POST['producto_variable'];
        $costo_producto = 0;
        $aplica_iva = $_POST['aplica_iva'] ?? 0;
        $estado_producto = $_POST['estado_producto'];
        $date_added = date("Y-m-d H:i:s");
        $image_path = "";
        $id_imp_producto = $_POST['id_imp_producto'] ?? 12;
        $pagina_web = $_POST['pagina_web'] ?? 0;
        $formato = $_POST['formato'];
        $drogshipin = $_POST['drogshipin'] ?? 0;
        $destacado = $_POST['destacado'] ?? 0;
        $envio_prioritario = $_POST['envio_prioritario'] ?? 0;

        $enlace_funnelish = $_POST['enlace_funnelish'] ?? "";
        /// stocks
        $stock_inicial = $_POST['stock_inicial'] ?? 0;
        $bodega = $_POST['bodega'] ?? 0;

        $pcp = $_POST['pcp'] ?? 0;
        $pvp = $_POST['pvp'] ?? 0;
        $pref = 0;


        $response = $this->model->agregarProducto($codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $_SESSION['id_plataforma'], $stock_inicial, $bodega, $pcp, $pvp, $pref, $enlace_funnelish, $envio_prioritario);
        //echo 'asds';
        echo json_encode($response);
    }

    public function editar_producto()
    {
        $id = $_POST['id_producto'];
        $codigo_producto = $_POST['codigo_producto'];
        $nombre_producto = $_POST['nombre_producto'];
        $descripcion_producto = $_POST['descripcion_producto'] ?? '';
        $id_linea_producto = $_POST["id_linea_producto"];
        $inv_producto = $_POST['inv_producto'] ?? 0;
        $producto_variable = $_POST['producto_variable'] ?? 0;
        $costo_producto = 0;
        $aplica_iva = $_POST['aplica_iva'] ?? 0;
        $estado_producto = $_POST['estado_producto'] ?? 1;
        $date_added = date("Y-m-d H:i:s");
        $id_imp_producto = $_POST['id_imp_producto'] ?? 0;
        $pagina_web = $_POST['pagina_web'] ?? 0;
        $formato = $_POST['formato'];
        $drogshipin = $_POST['drogshipin'] ?? 0;
        $destacado = $_POST['destacado'] ?? 0;
        $envio_prioritario = $_POST['envio_prioritario'] ?? 0;

        /// stocks
        $stock_inicial = $_POST['stock_inicial'] ?? 0;
        $bodega = $_POST['bodega'] ?? 0;

        $pcp = $_POST['pcp'] ?? 0;
        $pvp = $_POST['pvp'] ?? 0;
        $pref = 0;


        $response = $this->model->editarProducto($id, $codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $_SESSION['id_plataforma'], $stock_inicial, $bodega, $pcp, $pvp, $pref, $envio_prioritario);

        echo json_encode($response);
    }

    public function editarProductoTienda()
    {
        $id_producto_tienda = $_POST['id_producto_tienda'];
        $nombre = $_POST['nombre'];
        $pvp_tienda = $_POST['pvp_tienda'];
        $id_categoria = $_POST['id_categoria'];
        $aplica_funnelish = $_POST['aplica_funnelish'];
        $funnelish = $_POST['funnelish'];

        if ($_POST['pref'] == null || $_POST['pref'] == '') {
            $pref = 0;
        } else {
            $pref = $_POST['pref'] ?? 0;
        }



        $response = $this->model->editarProductoTienda($id_producto_tienda, $nombre, $pvp_tienda, $id_categoria, $pref, $_SESSION['id_plataforma'], $aplica_funnelish, $funnelish);

        echo json_encode($response);
    }

    public function eliminar_producto()
    {
        $id = $_POST['id'];
        $response = $this->model->eliminarProducto($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    public function subir_marketplace()
    {
        $id = $_POST['id'];
        $response = $this->model->SubirMarketplace($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function bajar_marketplace()
    {
        $id = $_POST['id'];
        $response = $this->model->BajarMarketplace($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }





    public function listar_marketplace()
    {
        $response = $this->model->listarMarketplace();
    }


    /// Funciones de caracteristicas
    public function agregar_caracteristica()
    {
        $variedad = $_POST['variedad'];
        $id_atributo = $_POST['id_atributo'];
        $response = $this->model->agregarCaracteristica($variedad, $id_atributo, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function listar_caracteristicas()
    {
        $response = $this->model->listarCaracteristicas($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function listar_atributos()
    {
        $response = $this->model->listarAtributos(0, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function eliminar_caracteristica()
    {
        $id = $_POST['id'];
        $response = $this->model->eliminarCaracteristica($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    //FUNCIONES PARA AGREGAR VARIABLES


    public function agregarVariable()
    {

        $id_variedad = $_POST['id_variedad'];
        $id_producto = $_POST['id_producto'];
        $sku = $_POST['sku'];
        $bodega = $_POST["id_bodega"];
        $pcp = $_POST['pcp'];
        $pvp = $_POST['pvp'];
        $stock = $_POST['stock'];
        $pref = $_POST['pref'];
        $response = $this->model->agregarVariable($id_variedad, $id_producto, $sku, $bodega, $pcp, $pvp, $stock, $_SESSION['id_plataforma'], $pref);

        echo json_encode($response);
    }

    function consultarMaximo($id_producto)
    {
        //echo $id_producto;
        $response = $this->model->consultarMaximo($id_producto);
        //echo $response;
        return $response;
    }

    function mostrarVariedades($id_producto)
    {
        //echo $id_producto;
        $response = $this->model->mostrarVariedades($id_producto);
        // print_r($response);
        echo json_encode($response);
    }


    public function obtenerInventario()
    {
        //echo $id_producto;
        $id_inventario = $_POST['id_inventario'];
        $response = $this->model->obtenerInventario($id_inventario);
        // print_r($response);
        echo json_encode($response);
    }

    public function obtenerHistorial()
    {
        //echo $id_producto;
        $id_inventario = $_POST['id_inventario'];
        $response = $this->model->obtenerHistorial($id_inventario);
        // print_r($response);
        echo json_encode($response);
    }

    public function importarExcel()
    {
        // Obtener el ID de inventario desde el formulario
        $id_inventario = $_POST['id_bodega'];

        // Verificar y manejar el archivo subido
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['archivo']['tmp_name'];
            $fileName = $_FILES['archivo']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Permitir solo archivos Excel
            $allowedfileExtensions = ['xlsx', 'xls'];
            if (in_array($fileExtension, $allowedfileExtensions)) {
                try {
                    // Cargar el archivo Excel
                    $spreadsheet = IOFactory::load($fileTmpPath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $data = $sheet->toArray();

                    $date_added = date("Y-m-d H:i:s");
                    $fila = 0;
                    $agregados = 0;

                    foreach ($data as $row) {
                        if ($fila > 0) {
                            $response = $this->model->agregarProducto(
                                $row[0],
                                $row[1],
                                $row[2],
                                $row[3],
                                $row[4],
                                $row[5],
                                $row[6],
                                '1',
                                '1',
                                $date_added,
                                $row[7],
                                '1',
                                '1',
                                '1',
                                '0',
                                '0',
                                $_SESSION['id_plataforma'],
                                $row[8],
                                $id_inventario,
                                $row[9],
                                $row[10],
                                $row[11],
                                '',
                                '0'
                            );

                            if ($response['status'] == 200) {
                                $agregados++;
                            }
                        }
                        $fila++;
                    }

                    if ($agregados > 0) {
                        $response = [
                            'status' => 200,
                            'title' => 'Petición exitosa',
                            'message' => $agregados . ' productos importados correctamente'
                        ];
                    } else {
                        $response = [
                            'status' => 500,
                            'title' => 'Petición fallida',
                            'message' => 'No se agregaron productos. Revise el archivo e inténtelo nuevamente.'
                        ];
                    }
                } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                    $response = [
                        'status' => 500,
                        'title' => 'Error',
                        'message' => 'Error al procesar el archivo: ' . $e->getMessage()
                    ];
                }
            } else {
                $response = [
                    'status' => 500,
                    'title' => 'Error',
                    'message' => 'Solo se permiten archivos Excel (xlsx, xls).'
                ];
            }
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => 'Error al subir el archivo.'
            ];
        }

        echo json_encode($response);
    }


    public function importar_productos_tienda()
    {

        // Obtener el ID de inventario desde el formulario
        $id_producto = $_POST['id_producto'];

        $response = $this->model->importar_productos_tienda($id_producto, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function importar_productos_funnel()
    {
        // Obtener el ID de inventario desde el formulario
        $id_inventario = $_POST['id_inventario'];
        $id_funnel = $_POST['id_funnel'];
        $response = $this->model->importar_productos_funnel($id_inventario, $id_funnel, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    public function importar_productos_shopify()
    {

        // Obtener el ID de inventario desde el formulario
        $id_inventario = $_POST['id_inventario'];

        $response = $this->model->importar_productos_shopify($id_inventario, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }


    public function agregarDestacado()
    {
        $id_producto_tienda = $_POST['id_producto_tienda'];
        $destacado = $_POST['destacado'];
        $response = $this->model->agregarDestacado($id_producto_tienda, $destacado);
        echo json_encode($response);
    }


    public function verificarProducto($id)
    {
        $response = $this->model->verificarProducto($id);
        echo json_encode($response);
    }

    public function verificarProductoTienda($id)
    {
        $response = $this->model->verificarProductoTienda($id);
        echo json_encode($response);
    }

    public function existeLanding($id)
    {
        $response = $this->model->existeLanding($id);
        echo json_encode($response);
    }

    public function existeLandingTienda($id)
    {
        $response = $this->model->existeLandingTienda($id);
        echo json_encode($response);
    }

    public function existeLandingTienda2($id)
    {
        $response = $this->model->existeLandingTienda2($id);
        echo json_encode($response);
    }

    public function habilitarPrivado()
    {
        $id = $_POST['id_producto'];
        $estado = $_POST['estado'];
        $response = $this->model->habilitarPrivado($id, $estado);
        echo json_encode($response);
    }

    public function agregarPrivadoPlataforma()
    {
        $id = $_POST['id_producto'];
        $plataforma = $_POST['id_plataforma'];
        $response = $this->model->agregarPrivadoPlataforma($id, $plataforma);
        echo json_encode($response);
    }

    public function obtener_productosPrivados_tienda()
    {
        $response = $this->model->obtener_productosPrivados_tienda($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_proveedores()
    {
        $response = $this->model->obtener_proveedores();
        echo json_encode($response);
    }



    public function obtener_productos_shopify()
    {
        $response = $this->model->obtener_productos_shopify($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_tiendas_productosPrivados()
    {
        $id = $_POST['id_producto'];
        $response = $this->model->obtener_tiendas_productosPrivados($id);
        echo json_encode($response);
    }

    public function eliminarPrivadoPlataforma()
    {
        $id = $_POST['id_privado'];
        $response = $this->model->eliminarPrivadoPlataforma($id);
        echo json_encode($response);
    }

    public function obtener_productos_asignacionCombos()
    {
        $response = $this->model->obtener_productos_asignacionCombos($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_combos()
    {
        $response = $this->model->obtener_combos($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_combo_id()
    {
        $id = $_POST['id'];
        $response = $this->model->obtener_combo_id($id);
        echo json_encode($response);
    }

    public function agregarcombos()
    {
        $nombre = $_POST['nombre'];
        $id_producto_combo = $_POST['id_producto_combo'];
        $imagen = $_FILES['imagen'];

        $response = $this->model->agregarcombos(
            $nombre,
            $id_producto_combo,
            $imagen,
            $_SESSION['id_plataforma']
        );

        echo json_encode($response);
    }

    public function editarcombos()
    {
        $nombre = $_POST['nombre'];
        $id_producto_combo = $_POST['id_producto_combo'];
        $id_combo = $_POST['id_combo'];

        // Verificar si se ha subido una imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $imagen = $_FILES['imagen'];
        } else {
            $imagen = null; // No se subió ninguna imagen
        }

        $response = $this->model->editarcombos(
            $nombre,
            $id_producto_combo,
            $imagen,
            $id_combo
        );

        echo json_encode($response);
    }

    public function editarcombo_estado()
    {
        $estado_combo = $_POST['estado_combo'];
        $valor = $_POST['valor'];
        $id_combo = $_POST['id_combo'];

        $response = $this->model->editarcombo_estado(
            $estado_combo,
            $valor,
            $id_combo
        );

        echo json_encode($response);
    }

    public function eliminarCombo()
    {
        $id = $_POST['id_combo'];
        $response = $this->model->eliminarCombo($id);
        echo json_encode($response);
    }

    public function agregar_detalle_combo()
    {
        $id_combo = $_POST['id_combo'];
        $id_inventario = $_POST['id_inventario'];
        $cantidad = $_POST['cantidad'];

        $response = $this->model->agregar_detalle_combo($id_combo, $id_inventario, $cantidad);

        echo json_encode($response);
    }

    public function obtener_detalle_combo_id()
    {
        $id_combo = $_POST['id_combo'];
        $response = $this->model->obtener_detalle_combo_id($id_combo);
        echo json_encode($response);
    }

    public function eliminar_detalleCombo()
    {
        $id = $_POST['id_detalle_combo'];
        $response = $this->model->eliminar_detalleCombo($id);
        echo json_encode($response);
    }

    /* ofertas */
    public function actualizar_oferta()
    {
        $id_producto_tienda = $_POST['id_producto_tienda'];
        $oferta = $_POST['oferta'];
        $response = $this->model->actualizar_oferta($id_producto_tienda, $oferta);
        echo json_encode($response);
    }
    /* Fin ofertas */
}
