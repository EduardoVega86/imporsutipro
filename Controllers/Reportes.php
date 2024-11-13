<?php

class Reportes extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header('Location: ' . SERVERURL . 'login');
            exit();
        }
    }

    public function billetera()
    {
        $this->views->render($this, 'billetera');
    }
}
