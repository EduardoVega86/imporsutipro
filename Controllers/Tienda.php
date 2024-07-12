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
    {        //$nombre = $_POST['nombre'];
        $response = $this->model->bannertienda($_SESSION['id_plataforma']);
       echo json_encode($response);
       
    }
    
    public function testimoniostienda()
    {        //$nombre = $_POST['nombre'];
        $response = $this->model->testimoniostienda($_SESSION['id_plataforma']);
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
    
      public function horizontaltienda()
    {        //$nombre = $_POST['nombre'];
        $response = $this->model->horisontaltienda($_SESSION['id_plataforma']);
       echo json_encode($response);
       
    }
    
    public function actualizar_plataforma()
    {        
//$nombre = $_POST['nombre'];
      $nombre_tienda=   $_POST['nombre_tienda'];
      $descripcion_tienda=    $_POST['descripcion_tienda'];
      $ruc_tienda=     $_POST['ruc_tienda'];
       $telefono_tienda=     $_POST['telefono_tienda'];
      $email_tienda=    $_POST['email_tienda'];
       $direccion_tienda=   $_POST['direccion_tienda'];
      $pais_tienda=    $_POST['pais_tienda'];  
      $response = $this->model->actualizar_tienda($nombre_tienda, $descripcion_tienda, $ruc_tienda,$telefono_tienda, $email_tienda, $direccion_tienda, $pais_tienda, $_SESSION['id_plataforma']);
       echo json_encode($response);
       
    }
    
    
}
