<?php
class Ubicaciones extends Controller
{

    public function __construct()
    {
        parent::__construct();
        /* if (!$this->isAuth())
            header("Location:  " . SERVERURL . "login"); */
        /* if (!$this->hasPermission(2)) {
            header("Location: /dashboard");
        } */
    }

    public function obtenerProvincias()
    {
        $data = $this->model->obtener_provincias();
        echo json_encode($data);
    }

    public function obtenerCiudades($id)
    {
        $data = $this->model->obtenerUbicaciones($id);
        echo json_encode($data);
    }
    public function obtenerMatriz()
    {
        $data = $this->model->obtenerMatriz();
        echo json_encode($data);
    }

    public function obtenerCiudad($id)
    {
        $data = $this->model->obtenerCiudad($id);
        echo json_encode($data);
    }
}
