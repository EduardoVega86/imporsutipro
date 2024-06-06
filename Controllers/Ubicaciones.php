<?php
class Ubucaciones extends Controller
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
}
