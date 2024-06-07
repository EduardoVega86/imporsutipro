<?

class Guias extends Controller
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
    /// funciones

    public function generarLaar()
    {
    }

    private function obtenerDestinatario()
    {
        $id_producto = $_POST['id_inventario'];
        $datos = $this->model->obtenerDestinatario($id_producto);
    }
}
