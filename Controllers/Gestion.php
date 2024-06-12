<?php

class Gestion extends Controller
{
    public function laar()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $estadoActualCodigo = $data['estadoActualCodigo'];
        $novedades = $data['novedades'];
        $noGuia = $data['noGuia'];

        $esEntregado = false;
        $esDevolucion = false;

        // Regla para Entregado
        if ($estadoActualCodigo == 7) {
            foreach ($novedades as $novedad) {
                if ($novedad['codigoTipoNovedad'] == 43) {
                    $esEntregado = true;
                    break;
                }
            }
        }

        // Regla para Devolución
        if ($estadoActualCodigo != 7 && $estadoActualCodigo != 9) {
            foreach ($novedades as $novedad) {
                if ($novedad['codigoTipoNovedad'] == 42 || $novedad['codigoTipoNovedad'] == 92) {
                    $esDevolucion = true;
                    break;
                }
            }
        }

        // Regla para Devolución cuando entregado
        if ($estadoActualCodigo == 7) {
            foreach ($novedades as $novedad) {
                if ($novedad['codigoTipoNovedad'] == 42 || $novedad['codigoTipoNovedad'] == 96) {
                    $esDevolucion = true;
                    break;
                }
            }
        }

        if ($esEntregado) {
            return 'Entregado';
        } elseif ($esDevolucion) {
            return 'Devolución';
        } else {
            return 'Otro estado';
        }
        $response = $this->model->actualizarEstado($estadoActualCodigo, $noGuia);
    }
}
