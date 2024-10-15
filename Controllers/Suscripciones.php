<?php

class Suscripciones extends Controller
{

    public function __construct()
    {
        if (!$this->isAuth()) {
            header('Location: ' . SERVERURL . 'login');
            die();
        } else {
            parent::__construct();
        }
    }

    public function tiendas()
    {
        $id_usuario = $_POST['id_usuario'];
        $tiendas = $this->model->getTiendas($id_usuario);
        echo json_encode($tiendas);
    }
}
