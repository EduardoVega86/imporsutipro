<?php
session_start();
class Inventarios extends Controller
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

    public function inventario()
    {
        $this->views->render($this, "inventario");
    }



    ///Funciones

   



    public function obtenerInventario()
    {
       // echo $id;
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
    
     public function agregarStockInventario()
    {
        //echo $id_producto;
        $inventario = $_POST['id_inventario'];
        $cantidad= $_POST['cantidad'];
        $referencia=  $_POST['referencia'];
        
        $sku=  $_POST['sku'];
        $id_producto=  $_POST['id_producto'];
        $id_bodega=  $_POST['id_bodega'];
        
        
        $response = $this->model->agregarStock($inventario, $cantidad, $_SESSION['id_plataforma'], $sku,  $referencia,  $id_producto, $id_bodega);
        //print_r($response);
        echo json_encode($response);
    }
    
     public function generarDespacho($num_guia)
    {
       
       $transportadora= $_POST['transportadora'];
        $bodega= $_POST['bodega'];
       $response = $this->model->despacho_guia2($num_guia, $_SESSION['id_plataforma'], $transportadora, $bodega);
        // print_r($response);
        echo json_encode($response);
    }

    public function generarDespachoProducto($num_guia)
    {
       
      
        $bodega= $_POST['bodega'];
       $response = $this->model->despacho_producto($num_guia, $_SESSION['id_plataforma'], $bodega);
        // print_r($response);
        echo json_encode($response);
    }

    public function generarIngresoProducto($num_guia)
    {
       
      
        //$bodega= $_POST['bodega'];
       $response = $this->model->ingreso_producto($num_guia, $_SESSION['id_plataforma']);
        // print_r($response);
        echo json_encode($response);
    }

     public function generarDevolucion($num_guia)
    {
       
       
        $response = $this->model->devolucion_guia($num_guia, $_SESSION['id_plataforma']);
        // print_r($response);
        echo json_encode($response);
    }
    
       public function devolverDespacho($num_guia)
    {
       
       
        $response = $this->model->devolucion_guia_despacho($num_guia, $_SESSION['id_plataforma']);
        // print_r($response);
        echo json_encode($response);
    }
    
    
    public function eliminarStockInventario()
    {
        //echo $id_producto;
        $inventario = $_POST['id_inventario'];
        $cantidad= $_POST['cantidad'];
        $referencia=  $_POST['referencia'];
        
        $sku=  $_POST['sku'];
        $id_producto=  $_POST['id_producto'];
        $id_bodega=  $_POST['id_bodega'];
        
        
        $response = $this->model->eliminarStock($inventario, $cantidad, $_SESSION['id_plataforma'], $sku,  $referencia,  $id_producto, $id_bodega);
        // print_r($response);
         echo json_encode($response);
    }
    
    public function generarlistacompra(){
       $arreglo_guias=$_POST['guias'];
        $response = $this->model->listarProductoListaCompras($arreglo_guias);
         print_r($response);
        echo json_encode($response);  
    }
    
    public function listar_despachos()
    {
     // $id_inventario = $_POST['id_inventario'];
      $response = $this->model->listar_despachos($_SESSION['id_plataforma']);
        // print_r($response);
      echo json_encode($response);  
    }
           
}
