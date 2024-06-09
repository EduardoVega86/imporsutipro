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

        $ultima_factura = $this->select("SELECT MAX(numero_factura) as factura_numero FROM facturas_cot");
        $factura_numero = $ultima_factura[0]['factura_numero'];
        
        $nueva_factura = $this->incrementarNumeroFactura($factura_numero);

        $response = $this->initialResponse();
        $sql = "INSERT INTO facturas_cot (
            numero_factura, fecha_factura, id_usuario, monto_factura, estado_factura, 
            nombre_cliente, telefono_cliente, c_principal, ciudad_cot, c_secundaria, 
            referencia, observacion, guia_enviada, transporte, identificacion, celular, 
            id_producto_venta, dropshipping, id_plataforma, dueño_id, importado, 
            plataforma_importa, cod, estado_guia_sistema, impreso, facturada, factura_numero, 
            numero_guia, anulada, identificacionO, celularO, nombreO, ciudadO, provinciaO, 
            direccionO, referenciaO, numeroCasaO, valor_segura, no_piezas, tipo_servicio, 
            peso, contiene, costo_flete, costo_producto, comentario, id_transporte
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";
        
        $data = array(
            $nueva_factura, $fecha_factura, $id_usuario, $monto_factura, $estado_factura, 
            $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, 
            $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, 
            $id_producto_venta, $dropshipping, $id_plataforma, $dueño_id, $importado, 
            $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, 
            $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, 
            $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, 
            $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte
        );
        
        if (substr_count($sql, '?') !== count($data)) {
            throw new Exception('La cantidad de placeholders en la consulta no coincide con la cantidad de elementos en el array de datos.');
        }
        
        $response = $this->insert($sql, $data);
        return $response;
        
    }

    public function obtenerDestinatario($id)
    {
        $sql = "SELECT id_plataforma FROM inventario_bodegas WHERE id_producto = $id";

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
        //echo print_r($data);
        $eliminar_tmp = $this->delete($sql, $data);
        //print_r($eliminar_tmp);
        if ($eliminar_tmp == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al eliminar la producto';
        }
        return $response;
    }
    
    public function buscarBodega($id_producto){
        $sql = "SELECT * FROM inventario_bodegas WHERE id_producto = $id_producto limit 1";

        $responde = $this->select($sql);
        $bodega = $responde[0]['bodega'];
        $sql = "SELECT * FROM bodega WHERE id = $bodega";
        return $this->select($sql);
    }
    
    

 
    
        public function buscarProductosBodega($producto, $sku, $plataforma)
        {
            
        $id_bodega_buscar = $this->select("SELECT bodega FROM inventario_bodegas WHERE id_producto = $producto and sku='$sku' ");
        $id_bodega = $id_bodega_buscar[0]['bodega'];
         
        $sql = "SELECT * FROM inventario_bodegas , productos WHERE bodega=$id_bodega and productos.id_producto=inventario_bodegas.id_producto";
        return $this->select($sql);
        }
    
        
         public function cambiarPrecio($id_tmp, $precio, $descuento)
        {
            
       $sql = "UPDATE tmp_cotizacion SET precio_tmp = ?, desc_tmp =? WHERE id_tmp = ?";
                $data = [$precio, $descuento, $id_tmp];
                $editar_tmp = $this->update($sql, $data);
                if ($editar_tmp == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Imagen subida correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al subir la imagen';
                }
                
                return $response;
         }
                
        
        
            public function recuperarOrigenBodega($producto, $sku, $plataforma)
        {
            
        $id_bodega_buscar = $this->select("SELECT bodega FROM inventario_bodegas WHERE id_producto = $producto and sku='$sku' ");
        $id_bodega = $id_bodega_buscar[0]['bodega'];
         
        $sql = "SELECT * FROM bodega WHERE id=$id_bodega";
        return $this->select($sql);
    }

    public function actualizarTmp($id_tmp, $descuento, $precio){
        $sql = "UPDATE tmp_cotizacion SET desc_tmp = ?, precio_tmp = ? WHERE id_tmp = ?";
        $data = [$descuento, $precio, $id_tmp];
       $responses = $this->update($sql, $data);
        if($responses == 1){
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto actualizado correctamente';
        }else{
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al actualizar el producto';
        }
            return $response;
    }

    function incrementarNumeroFactura($factura) {
        // Separar el prefijo del número de serie
        $partes = explode('-', $factura);
        $prefijo = $partes[0];
        $serial = $partes[1];
        
        // Convertir el número de serie a un entero, incrementarlo, y formatearlo con ceros a la izquierda
        $nuevoSerial = str_pad((int)$serial + 1, strlen($serial), '0', STR_PAD_LEFT);
        
        // Unir el prefijo con el nuevo número de serie
        $nuevaFactura = $prefijo . '-' . $nuevoSerial;
        
        return $nuevaFactura;
    }
}
