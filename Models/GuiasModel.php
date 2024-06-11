<?php
class GuiasModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerDestinatario($id_producto)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_producto";
        return $this->select($sql);
    }

    public function generarLaar($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras)
    {
        $datos = array(
            "origen" => array(
                "identificacionO" => $identificacion,
                "nombreO" => $nombreOrigen,
                "ciudadO" => $ciudadOrigen,
                "direccionO" => $direccionOrigen,
                "telefonoO" => $telefonoOrigen,
                "referenciaO" => $referenciaOrigen,
                "celularO" => $celularOrigen,
                "postal" => $postal,
                "numeroCasa" => "0"
            ),
            "destino" => array(
                "identificacionD" => "0",
                "nombreD" => $nombreDestino,
                "ciudadD" => $ciudadDestino,
                "direccionD" => $direccionDestino,
                "telefonoD" => $telefonoDestino,
                "celularD" => $celularDestino,
                "referenciaD" => $referenciaDestino,
                "postal" => $postal,
                "numeroCasa" => "0"
            ),
            "postal" => $postal,
            
        )
    }
}
