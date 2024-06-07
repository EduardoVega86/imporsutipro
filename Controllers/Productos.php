<?php
session_start();
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



    ///Funciones

    public function obtener_productos()
    {
        $response = $this->model->obtener_productos($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtener_producto($id)
    {
        $response = $this->model->obtenerProducto($id, $_SESSION['id_plataforma']);
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
        $response = $this->model->agregarCategoria($nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $_SESSION['id_plataforma']);
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
        $response = $this->model->editarCategoria($id, $nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $_SESSION['id_plataforma']);
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
        $image_path = "";
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

        $response = $this->model->editarProducto($id, $codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $_SESSION['id_plataforma'], $stock_inicial, $bodega, $pcp, $pvp, $pref);

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
}
