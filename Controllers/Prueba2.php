<?php

class Prueba2 extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    /*Vistas */
    public function visor()
    {

        $this->views->render($this, "index");
    }

    public function calculadora()
    {
        $this->views->render($this, "calculadora");
    }

    public function calculadora_view()
    {
        $this->views->render($this, "calculadora_view");
    }

    // _________________
    public function mensaje_a_cliente($text)
    {
        $this->model->saludoACliente($text);
    }

    public function calcular()
    {

        // var_dump($_POST);
        // var_dump($_POST['num1']);

        $num1 = $_POST['num1'];
        $num2 = $_POST['num2'];
        $option = $_POST['option'];

        $data = [
            "num1" => $num1,
            "num2" => $num2,
            "option" => $option
        ];

        $calcular =  $this->model->recibirDatos($data);

        echo $calcular;
    }
}
