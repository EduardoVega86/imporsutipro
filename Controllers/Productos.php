<?php
session_start();
class Productos extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth())
            header("Location: /login");
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
        if (!$this->hasPermission(2)) {
            header("Location: /dashboard");
        }
        $data = $this->model->cargarCategorias($plataforma = $_SESSION['id_plataforma']);
        $this->views->render($this, "categorias", $data);
    }

 public function verbodegas()
    {
        $this->views->render($this, "bodegas");
    }
    
    ///Funciones

    public function agregarBodega()
    {
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $ciudad = $_POST['ciudad'];
        $provincia = $_POST['provincia'];
        $contacto = $_POST['contacto'];
        $telefono_contacto = $_POST['telefono_contacto'];
        $numerocasa = $_POST['numerocasa'];
        $referencia = $_POST['referencia'];
        $response = $this->model->agregarBodega($nombre, $direccion, $telefono, $ciudad, $provincia, $contacto, $telefono_contacto, $numerocasa, $referencia, $_SESSION['id_plataforma']);
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

    public function agregar_producto()
    {
    }
    
     public function listar_bodegas()
    {
         $response = $this->model->listarBodegas($_SESSION['id_plataforma']);
         echo json_encode($response);
    }
}
