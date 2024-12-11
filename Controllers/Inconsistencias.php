<?php

class Inconsistencias extends Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->isAuth()) {
            header('Location: ' . SERVERURL . 'login');
            exit();
        }
    }

    public function gintracom()
    {
        $this->views->render($this, 'gintracom');
    }

    public function fix()
    {
        $this->views->render($this, 'fix');
    }

    public function getInconsistencias_Gintracom()
    {
        $tipo = $_POST['tipo'] ?? null; // Asegura que exista el dato
        $fecha = $_POST['fecha'] ?? null;

        if (!$tipo) {
            echo json_encode(['error' => 'Tipo es requerido']);
            return;
        }

        // Valida las entradas y llama al modelo
        $inconsistencias = [];
        if ($tipo === "general") {
            $inconsistencias = $this->model->getInconsistencias_Gintracom('general');
        } elseif ($tipo === "mes") {
            if (!$fecha || !preg_match('/^\d{4}-\d{2}$/', $fecha)) {
                echo json_encode(['error' => 'Formato de fecha inválido para "mes". Ejemplo: 2024-12']);
                return;
            }
            $inconsistencias = $this->model->getInconsistencias_Gintracom('mes', $fecha);
        } elseif ($tipo === "dia") {
            if (!$fecha || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                echo json_encode(['error' => 'Formato de fecha inválido para "día". Ejemplo: 2024-12-11']);
                return;
            }
            $inconsistencias = $this->model->getInconsistencias_Gintracom('dia', $fecha);
        } elseif ($tipo === "rango") {
            $fechaInicio = $_POST['fechaInicio'] ?? null;
            $fechaFin = $_POST['fechaFin'] ?? null;

            if (!$fechaInicio || !$fechaFin || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
                echo json_encode(['error' => 'Fechas de inicio o fin inválidas para "rango". Ejemplo: 2024-12-01 a 2024-12-10']);
                return;
            }

            $inconsistencias = $this->model->getInconsistencias_Gintracom('rango', $fechaInicio, $fechaFin);
        } else {
            echo json_encode(['error' => 'Tipo de búsqueda no válido']);
            return;
        }

        echo json_encode($inconsistencias);
    }
}
