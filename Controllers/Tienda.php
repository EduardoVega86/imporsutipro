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

    public function pixeles()
    {
        $this->views->render($this, "pixeles");
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

    public function obtener_horizontalTienda()
    {
        $id_plataforma = $_POST['id_plataforma'];
        $response = $this->model->obtener_horizontalTienda($id_plataforma);
        echo json_encode($response);
    }

    public function crearPixel()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        $id_plataforma = $_SESSION['id_plataforma'];
        $nombre = $_POST['nombre'];
        $pixel = $_POST['pixel'];
        $tipo = $_POST['tipo'];
        $response = $this->model->crearPixel($id_plataforma, $nombre, $pixel, $tipo);
        echo json_encode($response);
    }

    public function obtenerPixeles()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");

        $id_plataforma = $_SESSION['id_plataforma'];
        $response = $this->model->obtenerPixeles($id_plataforma);
        echo json_encode($response);
    }

    public function actualizarPixel()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        $id_pixel = $_POST['id_pixel'];
        $nombre = $_POST['nombre'];
        $pixel = $_POST['pixel'];
        $tipo = $_POST['tipo'];
        $response = $this->model->actualizarPixel($nombre, $pixel, $tipo, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function obtenerPixel()
    {
        if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login");
        $tipo = $_POST['tipo'];
        $response = $this->model->obtenerPixel($tipo, $_SESSION['id_plataforma']);
        echo json_encode($response);
    }

    public function eliminarPixel()
    {
        $id_pixel = $_POST['id_pixel'];
        $response = $this->model->eliminarPixel($id_pixel);
        echo json_encode($response);
    }

    public function guardar_pedido()
    {        //$nombre = $_POST['nombre'];
        $id_plataforma =   $_POST['id_plataforma'];
        $id_producto =    $_POST['id_producto'];
        $precio_producto =     $_POST['precio_producto'];
        $nombre =     $_POST['nombre'];
        $telefono =    $_POST['telefono'];
        $provincia =   $_POST['provincia'];
        $ciudad =    $_POST['ciudad'];

        $calle_principal =   $_POST['calle_principal'];
        $calle_secundaria =    $_POST['calle_secundaria'];

        $referencia =   $_POST['referencia'];
        $observacion =    $_POST['observacion'];
        $id_inventario =    $_POST['id_inventario'];

        $response = $this->model->guardar_pedido($id_plataforma, $id_producto, $precio_producto, $nombre, $telefono, $provincia, $ciudad, $calle_principal, $calle_secundaria, $referencia, $observacion, $id_inventario);
        echo json_encode($response);
    }
}
