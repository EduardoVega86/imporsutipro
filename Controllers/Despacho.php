<?php

class Despacho extends Controller
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
        $this->views->render($this, "despacho");
    }
    public function despacho()
    {
        $this->views->render($this, "despacho");
    }

    public function despacho_producto()
    {
        $this->views->render($this, "despacho_producto");
    }

    public function devolucion_producto()
    {
        $this->views->render($this, "devolucion_producto");
    }
    
     public function lista_despachos()
    {
        $this->views->render($this, "lista_despachos");
    }

    public function lista_despachos_producto()
    {
        $this->views->render($this, "lista_despachos_producto");
    }
    
     public function lista_devoluciones()
    {
        $this->views->render($this, "lista_devoluciones");
    }

    public function lista_devoluciones_producto()
    {
        $this->views->render($this, "lista_devoluciones_producto");
    }

    public function devoluciones()
    {
        $this->views->render($this, "devoluciones");
    }


    /// 
    public function agregar()
    {
        $guia = $_POST['guia']; // guia
        $data = $this->model->agregar($guia);
        echo json_encode($data);
    }

    public function agregarDevolucion()
    {
        $guia = $_POST['guia']; // guia
        $data = $this->model->agregarDevolucion($guia);
        echo json_encode($data);
    }

    public function listar($despacho)
    {
        $data = $this->model->listar($despacho);
        echo json_encode($data);
    }

    public function listarDespachos()
    {
        $data = $this->model->listarDespachos($_SESSION['id_plataforma']);
        echo json_encode($data);
    }
    
    public function listarDespachosProductos()
    {
        $data = $this->model->listarDespachosProductos($_SESSION['id_plataforma']);
        echo json_encode($data);
    }
    

    public function listarDevoluciones()
    {
        $data = $this->model->listarDevoluciones($_SESSION['id_plataforma']);
        echo json_encode($data);
    }

    public function listarDevolucionesProducto()
    {
        $data = $this->model->listarDevolucionesProducto($_SESSION['id_plataforma']);
        echo json_encode($data);
    }

    public function listarDevolucionesDespacho($despacho)
    {
        $data = $this->model->listarDevolucionesDespacho($despacho);
        echo json_encode($data);
    }
    
     public function obtenerBodega($bodega)
    {
        $data = $this->model->obtenerbodega($bodega);
        echo json_encode($data);
    }
}
