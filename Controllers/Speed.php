<?php

class Speed extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->views->render($this, "index");
    }

    public function solventarNovedad()
    {
        $observacion = $_POST['observacion'];
        $id_novedad = $_POST['id_novedad'];

        $response = $this->model->solventarNovedad($observacion, $id_novedad);

        echo json_encode($response);
    }

    public function cargarNovedades()
    {
        $data = $this->model->cargarNovedades();
        return $data;
    }

    public function guardarRecibo()
    {
        $recibo = $_FILES['recibo'];
        $id_factura = $_POST['id_factura'];

        $response = $this->model->guardarRecibo($recibo, $id_factura);

        echo json_encode($response);
    }

    public function guardarMotorizado()
    {
        if (!$this->isAuth()) {
            header('Location: ' . SERVERURL);
        }
        $nombre = $_POST['nombre'];
        $celular = $_POST['celular'];
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];
        $placa = $_POST['placa'];

        $response = $this->model->guardarMotorizado($nombre, $celular, $usuario, $contrasena, $placa, $_SESSION["id_plataforma"]);
        echo json_encode($response);
    }

    public function asignarMotorizado()
    {
        $id_usuario = $_POST['id_usuario'];
        $guia = $_POST['guia'];

        $response = $this->model->asignarMotorizado($id_usuario, $guia);
        echo json_encode($response);
    }

    public function existeMotorizado()
    {
        $usuario = $_POST['id_usuario'];

        $response = $this->model->existeMotorizado($usuario);
        echo json_encode($response);
    }

    public function perfil()
    {
        $id = $_POST["id_usuario"];
        $response = $this->model->perfil($id);
        echo json_encode($response);
    }

    public function editarMotorizado()
    {
        $id = $_POST['id_usuario'];
        $nombre = $_POST['nombre'];
        $celular = $_POST['celular'];
        $usuario  = $_POST['usuario'] ?? null;
        $contrasena = $_POST['contrasena'] ?? null;
        $placa = $_POST['placa'] ?? null;

        $response = $this->model->editarMotorizado($id, $nombre, $celular, $usuario, $contrasena, $placa);
        echo json_encode($response);
    }

    public function cargarMotorizados()
    {
        $response = $this->model->getMotorizados();
        echo json_encode($response);
    }

    public function subirMatriculaLicencia()
    {
        $matricula = $_FILES['matricula'];
        $licencia = $_FILES['licencia'];
        $id_usuario = $_POST['id_usuario'];

        $response = $this->model->subirMatriculaLicencia($id_usuario, $matricula, $licencia);
        echo json_encode($response);
    }


    public function estados()
    {
        $estado = $_POST['estado'];
        $imagen = $_FILES['imagen'] ?? null;
        $tipo = $_POST['tipo'] ?? null;
        $observacion = $_POST['observacion'] ?? null;
        $id_factura = $_POST['id_factura'];
        $googlemaps = $_POST['googlemaps'] ?? null;

        $response = $this->model->estados($estado, $imagen, $tipo, $observacion, $id_factura, $googlemaps);
        if ($response['status'] == 200) {
            $response2 = $this->model->verificarAutomatizacion($id_factura);

            if ($response2['status'] == 200) {
                $response3 = $this->model->automatizar($response2['data']);
            }
        }
        echo json_encode($response);
    }

    public function webhookTelefono()
    {
        $guia = $_POST['guia'];
        $id_factura = $this->model->getIdFactura($guia);
        $response = $this->model->verificarAutomatizacion($id_factura);
        if ($response['status'] == 200) {
            $response2 = $this->model->automatizar($response['data']);
        }
        echo json_encode($response);
    }

    public function buscarFactura()
    {
        $numero_factura = $_POST['numero_factura'];
        $response = $this->model->buscarFactura($numero_factura);

        echo json_encode($response);
    }


    public function automatizador()
    {
        $id_factura = $_POST['id_factura'];
        $response = $this->model->verificarAutomatizacion($id_factura);
        print_r($response);
        if ($response['status'] == 200) {
            $response2 = $this->model->automatizar($response['data']);
        }
        echo json_encode($response);
    }
}
