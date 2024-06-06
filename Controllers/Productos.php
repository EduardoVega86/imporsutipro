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

   public function agregarBodega()
    {
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion_completa'];
        $telefono = $_POST['telefono'];
        $ciudad = $_POST['ciudad_entrega'];
        $provincia = $_POST['provinica'];
        $contacto = $_POST['nombre_contacto'];
        $telefono_contacto = $_POST['telefono_contacto'];
        $numerocasa = $_POST['numero_casa'];
        $referencia = $_POST['referencia'];
        $longitud = $_POST['longitud'];
        $latitud = $_POST['latitud'];
        $response = $this->model->agregarBodega($nombre, $direccion, $telefono, $ciudad, $provincia, $contacto, $telefono_contacto, $numerocasa, $referencia, $_SESSION['id_plataforma'], $longitu, $latitud);
        echo json_encode($response);
    }


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

    public function agregar_producto()
    {
    }

    public function listar_bodegas()
    {
        $response = $this->model->listarBodegas($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function listar_marketplace()
    {
        $response = $this->model->listarMarketplace();
    }

    public function cargar_categorias()
    {
        $response = $this->model->cargarCategorias($_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function agregar_caracteristica()
    {
    }

    public function guardar_imagen()
    {
        $response = $this->model->guardarImagen($_FILES['imagen'], $_POST['id_linea'], $_SESSION['id_plataforma']);
        echo json_encode($response);
    }
}
