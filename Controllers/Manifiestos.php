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

    //    public function generarManifiesto()
    //    {
    //        $arreglo = $_POST['guias'];
    //        $arreglo = json_decode($arreglo, true);
    //        //print_r($arreglo);
    //        //devuelve el html
    //        $html =    $this->model->generarManifiestoGuias($arreglo);
    //
    //       echo json_encode($html);
    //    }

    public function cambiarImpreso()
    {
        $arreglo = $_POST['guias'];
        $arreglo = json_decode($arreglo, true);
        $resultado = $this->model->cambiarImpreso($arreglo);
        // Puedes almacenar los resultados si es necesario
        $resultados[] = $resultado;
    }
    public function generarManifiesto()
    {
        $arreglo = $_POST['guias'];
        // $transportadora = $_POST['transportadora'];
        $arreglo = json_decode($arreglo, true);
        $transportadora = $_POST['transportadora'];
        $bodega = $_POST['bodega'];
        // echo count($arreglo);
        if (count($arreglo) > 0) {
            $id_cabecera =    $this->model->guardarCabecera($bodega, $transportadora, $_SESSION['id_plataforma']);
            // echo $id_cabecera;
            $resultados = [];
            foreach ($arreglo as $guia) {
                // Llama a la función del modelo para cada guía
                $resultado = $this->model->despacho_guia($guia, $_SESSION['id_plataforma'],  $id_cabecera);
                // Puedes almacenar los resultados si es necesario
                $resultados[] = $resultado;
            }
            // print_r($resultados);


            $html =    $this->model->generarManifiestoGuias($arreglo, $id_cabecera, $transportadora);
        }
        //print_r($arreglo);
        //devuelve el html
        // Itera sobre cada elemento del arreglo


        echo json_encode($html);
    }
    
    
    public function generarManifiestoDevolucion()
    {
        $arreglo = $_POST['guias'];
        // $transportadora = $_POST['transportadora'];
        $arreglo = json_decode($arreglo, true);
//        $transportadora = $_POST['transportadora'];
//        $bodega = $_POST['bodega'];
        // echo count($arreglo);
        if (count($arreglo) > 0) {
            $id_cabecera =    $this->model->guardarCabeceraDevolucion($_SESSION['id_plataforma']);
            // echo $id_cabecera;
            $resultados = [];
            foreach ($arreglo as $guia) {
                // Llama a la función del modelo para cada guía
                $resultado = $this->model->despacho_guia_devolucion($guia, $_SESSION['id_plataforma'],  $id_cabecera);
                // Puedes almacenar los resultados si es necesario
                $resultados[] = $resultado;
            }
            // print_r($resultados);


            $html =    $this->model->generarManifiestoGuiasDevolucion($arreglo, $id_cabecera);
        }
        //print_r($arreglo);
        //devuelve el html
        // Itera sobre cada elemento del arreglo


        echo json_encode($html);
    }
    

    public function generarDevolucion()
    {
        $arreglo = $_POST['guias'];
        $arreglo = json_decode($arreglo, true);
        //print_r($arreglo);
        //devuelve el html
        $html =    $this->model->generarDevolucionGuias($arreglo);

        echo json_encode($html);
    }
}
