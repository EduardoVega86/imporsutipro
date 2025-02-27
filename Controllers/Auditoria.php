<?php
require_once 'Class/Auditable.php';

/**
 * Clase de auditorias
 * @package App\Controllers
 * @version 1.0.0
 * @since 2025-02-27
 * @author Jeimy Jara
 */
class Auditoria extends Controller
{
    private Auditable $auditable;

    /**
     * Auditoria constructor.
     */
    public function __construct()
    {
        session_start();
        $this->auditable = new Auditable($_SESSION['id'], 'Auditoria');
        parent::__construct();
    }

    /**
     * Muestra la vista de auditoria
     * @return void
     */
    public function wallet(): void
    {
        $wallets = $this->auditable->mostrarTodas(date('Y-m-d', strtotime('-14 days')));
        $this->views->render($this, 'wallet', $wallets);
    }

    /**
     * Muestra los datos de la auditoria
     * @return void
     */
    public function getAuditoria(): void
    {
        $this->catchAsync(function () {
            $fecha = $_GET['fecha'] ?? date('Y-m-d', strtotime('-14 days'));
            $data = $this->auditable->mostrarTodas($fecha);
            $response = [
                'status' => 200,
                'title' => 'Petición exitosa',
                'message' => 'Datos de auditoria',
                'data' => $data,
                'count' => count($data)];
            echo json_encode($response);
        })();

    }

}