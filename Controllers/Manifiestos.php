
<?php
class Manifiestos extends Controller
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

    public function generar()
    {
        $arreglo = $_POST['facturas'];
        $arreglo = json_decode($arreglo, true);
        //print_r($arreglo);
        //devuelve el html
        $html =    $this->model->generarManifiesto($arreglo);

        echo json_encode($html);
    }

    public function generarManifiesto()
    {
        $arreglo = $_POST['guias'];
        $arreglo = json_decode($arreglo, true);
        //print_r($arreglo);
        //devuelve el html
        $html =    $this->model->generarManifiestoGuias($arreglo);

        print_r($html);
    }
}
