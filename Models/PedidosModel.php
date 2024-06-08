<?php
class PedidosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cargarPedidosIngresados($filtro)
    {
        if (empty($filtro) || $filtro == "") {

            $sql = "SELECT * FROM facturas_cot where guia IS NULL and anulado = 0";
        } else {

            $separar_filtro = explode(",", $filtro);
        }
        return $this->select($sql);
    }

    public function cargarGuias($filtro)
    {
        if (empty($filtro) || $filtro == "") {

            $sql = "SELECT * FROM facturas_cot where numero_guia IS NOT NULL and anulada = 0";
        } else {
            $sql = "SELECT * FROM facturas_cot where numero_guia IS NOT NULL and $filtro";
        }
        return $this->select($sql);
    }

    public function cargarAnuladas($filtro)
    {
        $sql = "SELECT * FROM facturas_cot where anulado = 1";

        return $this->select($sql);
    }

    public function nuevo_pedido($fecha_factura, $id_usuario, $monto_factura, $estado_factura, $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, $id_producto_venta, $dropshipping, $id_plataforma, $dueño_id, $importado, $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte)
    {
        $response = $this->initialResponse();
        $sql = "INSERT INTO facturas_cot (fecha_factura, id_usuario, monto_factura, estado_factura, nombre_cliente, telefono_cliente, c_principal, ciudad_cot, c_secundaria, referencia, observacion, guia_enviada, transporte, identificacion, celular, id_producto_venta, dropshipping, id_plataforma, dueño_id, importado, plataforma_importa, cod, estado_guia_sistema, impreso, facturada, factura_numero, numero_guia, anulada, identificacionO, celularO, nombreO, ciudadO, provinciaO, direccionO, referenciaO, numeroCasaO, valor_segura, no_piezas, tipo_servicio, peso, contiene, costo_flete, costo_producto, comentario, id_transporte) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = array($fecha_factura, $id_usuario, $monto_factura, $estado_factura, $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, $id_producto_venta, $dropshipping, $id_plataforma, $dueño_id, $importado, $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte);
        $response = $this->insert($sql, $data);
        return $response;
    }

    public function obtenerDestinatario($id)
    {
        $sql = "SELECT id_plataforma FROM inventario_bodegas WHERE id_inventario = $id";

        $id_platafomra = $this->select($sql);
        $id_platafomra = $id_platafomra[0]['id_plataforma'];
        return $id_platafomra;
    }
    
    public function buscarTmp()
    {
        $tmp=session_id();
        $sql = "SELECT * FROM tmp_cotizacion, productos WHERE productos.id_producto=tmp_cotizacion.id_producto and session_id='$tmp'";
        return $this->select($sql);
    }
    
     public function eliminarTmp($id_tmp)
    {
        $sql = "delete FROM tmp_cotizacion WHERE id_tmp = ?";
        $data = [$id_tmp];
        $eliminar_tmp = $this->delete($sql, $data);
        if ($eliminar_tmp == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria eliminada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al eliminar la categoria';
        }
        return $response;
    }
    
    
    
 
    
        public function buscarProductosBodega($producto, $sku, $plataforma)
        {
            
        $id_bodega_buscar = $this->select("SELECT id_bodega FROM inventario_bodega WHERE id_producto = $producto and sku='$sku' ");
        $id_bodega = $id_bodega_buscar[0]['id_bodega'];
         
        $sql = "SELECT * FROM inventario_bodega WHERE bodega=$id_bodega and id_plataforma=$plataforma";
        return $this->select($sql);
    }
    
            public function recuperarOrigenBodega($producto, $sku, $plataforma)
        {
            
        $id_bodega_buscar = $this->select("SELECT id_bodega FROM inventario_bodega WHERE id_producto = $producto and sku='$sku' ");
        $id_bodega = $id_bodega_buscar[0]['id_bodega'];
         
        $sql = "SELECT * FROM bodega WHERE id=$id_bodega";
        return $this->select($sql);
    }
}
