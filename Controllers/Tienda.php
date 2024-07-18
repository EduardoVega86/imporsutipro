<?php
class Tienda extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->views->render($this, "index");
    }

    public function registro()
    {
        $nombre = $_POST['nombre'];
        $response = $this->model->crearSubdominio($nombre, $_SESSION['id_plataforma']);
    }


    public function dominio()
    {
        $nombre = $_POST['nombre'];
        $response = $this->model->crearDominio($nombre, $_SESSION['id_plataforma']);
    }
    
    
    
    public function informaciontienda()
    {        //$nombre = $_POST['nombre'];
        $response = $this->model->informaciontienda($_SESSION['id_plataforma']);
       echo json_encode($response);
       
    }
    
     public function caracteristicastienda()
    {        //$nombre = $_POST['nombre'];
        $response = $this->model->caracteristicastienda($_SESSION['id_plataforma']);
       echo json_encode($response);
       
    }
    
    public function bannertienda()
    {
        $id_plataforma = $_POST['id_plataforma'];
        $response = $this->model->bannertienda($id_plataforma);
       echo json_encode($response);
       
    }
    
    public function testimoniostienda()
    {        
        $id_plataforma = $_POST['id_plataforma'];
        $response = $this->model->testimoniostienda($id_plataforma);
       echo json_encode($response);
       
    }

    public function categoriastienda()
    {   
        $id_plataforma = $_POST['id_plataforma'];
        $response = $this->model->categoriastienda($id_plataforma);
        echo json_encode($response);
       
    }

    public function destacadostienda()
    {   
        $id_plataforma = $_POST['id_plataforma'];
        $response = $this->model->destacadostienda($id_plataforma);
        echo json_encode($response);
       
    }

    public function iconostienda()
    {   
        $id_plataforma = $_POST['id_plataforma'];
        $response = $this->model->iconostienda($id_plataforma);
        echo json_encode($response);
       
    }

    public function obtener_productos_tienda()
    {
        $id_plataforma = $_POST['id_plataforma'];
        $id_producto_tienda = $_POST['id_producto_tienda'];
        $response = $this->model->obtener_productos_tienda($id_plataforma, $id_producto_tienda);
        echo json_encode($response);
    }
    
    public function obtener_productos_tienda_filtro()
    {
        $id_plataforma = $_POST['id_plataforma'];
        $id_categoria = $_POST['id_categoria'];
        $precio_minimo = $_POST['precio_minimo'];
        $precio_maximo = $_POST['precio_maximo'];
        
        $response = $this->model->obtener_productos_tienda_filtro($id_plataforma, $id_categoria, $precio_maximo, $precio_minimo);
        echo json_encode($response);
    }
    
     public function obtener_informacion_tienda()
    {
      
        $id_plataforma = $_POST['id_plataforma'];
        $response = $this->model->obtener_informacion_tienda($id_plataforma);
        echo json_encode($response);
    }
    
      public function horizontaltienda()
    {        //$nombre = $_POST['nombre'];
        $response = $this->model->horisontaltienda($_SESSION['id_plataforma']);
       echo json_encode($response);
       
    }
    
    public function actualizar_plataforma()
    {        
//$nombre = $_POST['nombre'];
     // $nombre_tienda=   $_POST['nombre_tienda'];
     
      $ruc_tienda=     $_POST['ruc'];
       $telefono_tienda=     $_POST['telefono_tienda'];
      $email_tienda=    $_POST['email_tienda'];
       $direccion_tienda=   $_POST['direccion_tienda'];
      $pais_tienda=    $_POST['pais_tienda'];  
      $facebook=    $_POST['facebook'];  
      $instagram=    $_POST['instagram'];  
      $tiktok=    $_POST['tiktok'];  
      $response = $this->model->actualizar_tienda($nombre_tienda,  $ruc_tienda,$telefono_tienda, $email_tienda, $direccion_tienda, $pais_tienda, $_SESSION['id_plataforma'], $facebook, $instagram, $tiktok);
       echo json_encode($response);
       
    }
    
    
    
    
    public function guardar_pedido()
    {        //$nombre = $_POST['nombre'];
        $id_plataforma=   $_POST['id_plataforma'];
      $id_producto=    $_POST['id_producto'];
      $precio_producto=     $_POST['precio_producto'];
       $nombre=     $_POST['nombre'];
      $telefono=    $_POST['telefono'];
       $provincia=   $_POST['provincia'];
      $ciudad=    $_POST['ciudad'];  
      
          $calle_principal=   $_POST['calle_principal'];
      $calle_secundaria=    $_POST['calle_secundaria']; 
      
          $referencia=   $_POST['referencia'];
      $observacion=    $_POST['observacion']; 
      $id_inventario=    $_POST['id_inventario']; 
      
        $response = $this->model->guardar_pedido($id_plataforma,$id_producto, $precio_producto, $nombre, $telefono, $provincia, $ciudad, $calle_principal, $calle_secundaria, $referencia, $observacion, $id_inventario);
       echo json_encode($response);
       
    }
    
    
}
