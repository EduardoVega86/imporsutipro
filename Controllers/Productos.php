<?php
session_start();
require 'vendor/autoload.php';
//use PHPExcel_IOFactory;


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



    ///Funciones

    public function obtener_productos()
    {
        $response = $this->model->obtener_productos($_SESSION['id_plataforma']);
        echo json_encode($response);
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

    public function obtener_productos_categoria($id)
    {
        $response = $this->model->obtenerProductosCategoria($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_productos_favoritos($id)
    {
        $response = $this->model->obtenerProductosFavoritos($id, $_SESSION['id_plataforma']);
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




    /// Funciones de productos

    public function agregar_producto()
    {
        $codigo_producto = $_POST['codigo_producto'];
        $nombre_producto = $_POST['nombre_producto'];
        $descripcion_producto = $_POST['descripcion_producto'];
        $id_linea_producto = $_POST["id_linea_producto"];
        $inv_producto = $_POST['inv_producto'];
        $producto_variable = $_POST['producto_variable'];
        $costo_producto = $_POST['costo_producto'];
        $aplica_iva = $_POST['aplica_iva'] ?? 0;
        $estado_producto = $_POST['estado_producto'];
        $date_added = date("Y-m-d H:i:s");
        $image_path = "";
        $id_imp_producto = $_POST['id_imp_producto'] ?? 12;
        $pagina_web = $_POST['pagina_web'] ?? 0;
        $formato = $_POST['formato'];
        $drogshipin = $_POST['drogshipin'] ?? 0;
        $destacado = $_POST['destacado'] ?? 0;
        /// stocks
        $stock_inicial = $_POST['stock_inicial'] ?? 0;
        $bodega = $_POST['bodega'] ?? 0;

        $pcp = $_POST['pcp'] ?? 0;
        $pvp = $_POST['pvp'] ?? 0;
        $pref = $_POST['pref'] ?? 0;


        $response = $this->model->agregarProducto($codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $_SESSION['id_plataforma'], $stock_inicial, $bodega, $pcp, $pvp, $pref);
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
        $costo_producto = $_POST['costo_producto'];
        $aplica_iva = $_POST['aplica_iva'] ?? 0;
        $estado_producto = $_POST['estado_producto'] ?? 1;
        $date_added = date("Y-m-d H:i:s");
        $id_imp_producto = $_POST['id_imp_producto'] ?? 0;
        $pagina_web = $_POST['pagina_web'] ?? 0;
        $formato = $_POST['formato'];
        $drogshipin = $_POST['drogshipin'] ?? 0;
        $destacado = $_POST['destacado'] ?? 0;
        /// stocks
        $stock_inicial = $_POST['stock_inicial'] ?? 0;
        $bodega = $_POST['bodega'] ?? 0;

        $pcp = $_POST['pcp'] ?? 0;
        $pvp = $_POST['pvp'] ?? 0;
        $pref = $_POST['pref'] ?? 0;


        $response = $this->model->editarProducto($id, $codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $_SESSION['id_plataforma'], $stock_inicial, $bodega, $pcp, $pvp, $pref);

        echo json_encode($response);
    }

    public function editarProductoTienda()
    {
        $id_producto_tienda = $_POST['id_producto_tienda'];
        $nombre = $_POST['nombre'];
        $pvp_tienda = $_POST['pvp_tienda'];
        $id_categoria = $_POST['id_categoria'];

        if ($_POST['pref'] == null || $_POST['pref'] == '') {
            $pref = 0;
        } else {
            $pref = $_POST['pref'] ?? 0;
        }



        $response = $this->model->editarProductoTienda($id_producto_tienda, $nombre, $pvp_tienda, $id_categoria, $pref);

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
                        $response = $this->model->agregarProducto($data[$fila][0], $data[$fila][1], $data[$fila][2], $data[$fila][3], $data[$fila][4], $data[$fila][5], $data[$fila][6], '1', '1', $date_added, $data[$fila][7], '1', '1', '1', '0', '0', $_SESSION['id_plataforma'], $data[$fila][8], $id_inventario, $data[$fila][9], $data[$fila][10], $data[$fila][11]);
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


    public function importar_productos_tienda()
    {

        // Obtener el ID de inventario desde el formulario
        $id_producto = $_POST['id_producto'];

        $response = $this->model->importar_productos_tienda($id_producto, $_SESSION['id_plataforma']);
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
        $imagen = $_FILES['imagen'];

        $response = $this->model->editarcombos(
            $nombre,
            $id_producto_combo,
            $imagen,
            $_SESSION['id_plataforma']
        );

        echo json_encode($response);
    }
}
