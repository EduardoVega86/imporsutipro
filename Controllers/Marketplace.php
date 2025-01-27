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

    public function obtener_productos()
    {
        $nombre= $_POST['nombre'];
        $linea= $_POST['linea'];
        $plataforma= $_POST['plataforma'];
        $min= $_POST['min'];
        $max= $_POST['max'];
        $favorito= $_POST['favorito'];
        
        $response = $this->model->obtener_productos($_SESSION['id_plataforma'], $nombre, $linea, $plataforma, $min, $max, $favorito);
        echo json_encode($response);
    }

    public function obtener_productos_completos()
    {
        // 1) Recibir filtros del POST (o GET), igual que en "obtener_productos"
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
        $linea = isset($_POST['linea']) ? $_POST['linea'] : '';
        $plataforma_filtro = isset($_POST['plataforma']) ? $_POST['plataforma'] : '';
        $min = isset($_POST['min']) ? $_POST['min'] : '';
        $max = isset($_POST['max']) ? $_POST['max'] : '';
        $favorito = isset($_POST['favorito']) ? $_POST['favorito'] : 0;
    
        // 2) Llamar al modelo, pasándole tu id_plataforma y los filtros
        $response = $this->model->obtener_productos_completos(
            $_SESSION['id_plataforma'],
            $nombre,
            $linea,
            $plataforma_filtro,
            $min,
            $max,
            $favorito
        );
    
        // 3) Devolver en JSON
        echo json_encode($response);
    }

    public function getInitialData()
    {
        // Simplemente llama al modelo
        $response = $this->model->getInitialData($_SESSION['id_plataforma']);
        echo json_encode($response);
    }
    
        
    public function obtener_productos_privados()
    {
        $nombre= $_POST['nombre'];
        $linea= $_POST['linea'];
        $plataforma= $_POST['plataforma'];
        $min= $_POST['min'];
        $max= $_POST['max'];
        $favorito= $_POST['favorito'];
        
        $response = $this->model->obtener_productos_privados($_SESSION['id_plataforma'], $nombre, $linea, $plataforma, $min, $max, $favorito);
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
    
    public function obtenerProveedores ()
    {
        $response = $this->model->obtenerProveedores();
       echo json_encode($response);
    }
    
    
     public function agregarFavoritos()
    {
        $id_producto = $_POST['id_producto'];
        
         $favorito = $_POST['favorito'];
        $response = $this->model->agregarFavoritos($id_producto,$_SESSION['id_plataforma'], $favorito);
       echo json_encode($response);
    }
    
    
}
