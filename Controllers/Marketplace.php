<?php

class Marketplace extends Controller
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

    public function obtenerProveedoresConProductosCategorias()
    {
        $response = $this->model->obtenerProveedoresConProductosCategorias();

        echo json_encode($response);
    }

    public function obtener_productos()
    {
        $nombre = $_POST['nombre'];
        $linea = $_POST['linea'];
        $plataforma = $_POST['plataforma'];
        $min = $_POST['min'];
        $max = $_POST['max'];
        $favorito = $_POST['favorito'];
        $vendido = $_POST['vendido'];
        $id = $_POST['id'] ?? null;

        $response = $this->model->obtener_productos($_SESSION['id_plataforma'], $nombre, $linea, $plataforma, $min, $max, $favorito, $vendido, $id);
        echo json_encode($response);
    }

    public function obtener_productos_paginados()
    {
        // Nota: usar ?? para evitar notices en caso de keys no enviadas
        $nombre     = $_POST['nombre'];
        $linea      = $_POST['linea'];
        $plataforma = $_POST['plataforma'];
        $min        = $_POST['min'];
        $max        = $_POST['max'];
        $favorito   = $_POST['favorito'];
        $vendido    = $_POST['vendido'];
        $id         = $_POST['id'] ?? null;

        // Paginación
        $page  = isset($_POST['page'])  ? (int)$_POST['page']  : 1;
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 35;

        // Llamamos al modelo pasando todos los filtros
        $response = $this->model->obtenerProductosPaginados(
            $_SESSION['id_plataforma'], // El "plataforma" de la sesión
            $nombre,
            $linea,
            $plataforma,
            $min,
            $max,
            $favorito,
            $vendido,
            $id,
            $page,
            $limit
        );

        // Devolvemos en JSON
        echo json_encode($response);
    }

    public function obtener_productos_privados()
    {
        // Recibimos los parámetros desde POST
        $nombre     = $_POST['nombre'];
        $linea      = $_POST['linea'];
        $plataforma = $_POST['plataforma'];
        $min        = $_POST['min'];
        $max        = $_POST['max'];
        $favorito   = $_POST['favorito'];
        $id         = $_POST['id'] ?? null;

        // Paginación
        $page  = isset($_POST['page'])  ? (int)$_POST['page']  : 1;
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 35;

        // Llamamos al modelo con los parámetros
        $response = $this->model->obtener_productos_privados(
            $_SESSION['id_plataforma'], // Plataforma desde sesión
            $nombre,
            $linea,
            $plataforma,
            $min,
            $max,
            $favorito,
            $id,
            $page,
            $limit
        );

        // Devolvemos el resultado en formato JSON
        echo json_encode($response);
    }


    public function obtener_producto($id)
    {
        $response = $this->model->obtener_producto($id, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function agregarMarketplace()
    {
        $codigo_producto = $_POST['codigo_producto'];
        $response = $this->model->agregarMarketplace($codigo_producto, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function agregarTmp()
    {
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $id_producto = $_POST['id_producto'];
        $sku = $_POST['sku'];
        $id_inventario = $_POST['id_inventario'];
        $response = $this->model->agregarTmp($id_producto, $cantidad, $precio, $_SESSION['id_plataforma'], $sku, $id_inventario);
        echo json_encode($response);
    }
    public function agregarTmpMuestra()
    {
        $cantidad = $_POST['cantidad'];
        $id_producto = $_POST['id_producto'];
        $sku = $_POST['sku'];
        $id_inventario = $_POST['id_inventario'];
        $plataforma = $_SESSION['id_plataforma'];

        $response = $this->model->agregarTmpMuestra($id_producto, $cantidad, $plataforma, $sku, $id_inventario);

        echo json_encode($response);
    }

    public function vaciarTmp()
    {
        $response = $this->model->vaciarTmp();
        echo json_encode($response);
    }

    public function obtener_precios_productos($id, $sku)
    {
        $response = $this->model->obtenerPreciosProductos($id, $sku, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtenerMaximo()
    {
        $response = $this->model->obtenerMaximo();
        echo $response;
    }

    public function obtenerProveedores()
    {
        $response = $this->model->obtenerProveedores();
        echo json_encode($response);
    }


    public function agregarFavoritos()
    {
        $id_producto = $_POST['id_producto'];

        $favorito = $_POST['favorito'];
        $response = $this->model->agregarFavoritos($id_producto, $_SESSION['id_plataforma'], $favorito);
        echo json_encode($response);
    }
}
