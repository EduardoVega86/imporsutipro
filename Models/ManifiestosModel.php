<?php

class ManifiestosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function generarManifiesto($arreglo)
    {
        if (count($arreglo) == 0) return;
        if (count($arreglo) == 1) {
            $this->generarManifiestoUnico($arreglo[0]);
        }
    }

    public function generarManifiestoUnico($factura)
    {
        $datos = $this->select("SELECT * FROM facturas_cot WHERE numero_factura = '$factura' ");
        $productos = $this->select("SELECT * FROM detalle_fact_cot WHERE numero_factura = '$factura' ");
        print_r($datos . " - " . $productos);
    }
}
