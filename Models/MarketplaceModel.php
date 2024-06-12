<?php
class MarketplaceModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    ///productos

    public function obtener_productos($plataforma)
    {
        $sql = "SELECT * FROM `productos`, inventario_bodegas where (productos.drogshipin=1 and inventario_bodegas.id_plataforma=productos.id_plataforma "
                . "and inventario_bodegas.id_producto and productos.id_producto and inventario_bodegas.sku=productos.codigo_producto ) or "
                . "(inventario_bodegas.id_plataforma=$plataforma and productos.id_plataforma=$plataforma and inventario_bodegas.id_producto and productos.id_producto and inventario_bodegas.sku=productos.codigo_producto) and bodega !=0";
        return $this->select($sql);
    }

    public function agregarMarketplace($codigo_producto,  $plataforma)
    {
        $response = $this->initialResponse();
        $sql_update = "update productos set drogshipin=? where id_producto=?";
        $data_update = [1, $codigo_producto];
        $actualizar_stock = $this->simple_select($sql_update, $data_update);
        if ($actualizar_stock == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto agregado correctamente al Marketplace';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al agregar a Marketplace';
        }
        return $response;
    }

    public function obtenerPreciosProductos($id_producto, $sku, $plataforma)
    {
         $sql = "SELECT * FROM `invetario_bodegas` , plataformas where inventario_bodegas.id_plataforma=plataformas.id_plataforma and id_producto = $id_producto and sku='$sku' and inventario_bodegas.id_plataforma=$plataforma";
        return $this->select($sql);
    }
    public function agregarTmp($id_producto, $cantidad, $precio,  $plataforma, $sku)
    {
        //verificar productos
         $timestamp = session_id();
          $cantidad_tmp = $this->select("SELECT * FROM tmp_cotizacion WHERE session_id = '$timestamp' and id_producto=$id_producto and sku=$sku" );
          //print_r($cantidad_tmp);
          if (empty($cantidad_tmp)){
              $sql = "INSERT INTO `tmp_cotizacion` (`id_producto`, `cantidad_tmp`, `precio_tmp`, `session_id`, `id_plataforma`, `sku`) VALUES (?, ?, ?, ?, ?, ?);";
        $data = [$id_producto, $cantidad, $precio, $timestamp, $plataforma, $sku];
        $insertar_caracteristica = $this->insert($sql, $data);
        
          }else{
              $cantidad_anterior = $cantidad_tmp[0]["cantidad_tmp"];
              $cantidad_nueva=$cantidad_anterior+$cantidad;
              $id_tmp = $cantidad_tmp[0]["id_tmp"];
              $sql = "UPDATE `tmp_cotizacion` SET  `cantidad_tmp` = ? WHERE `id_tmp` = ?";
        $data = [$cantidad_nueva,$id_tmp];
        $insertar_caracteristica = $this->update($sql, $data);
        //print_r($insertar_caracteristica);
          }
         
     
        
        if ($insertar_caracteristica == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto agregado al carrito';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al agregar la caracteristica';
        }
        return $response;
    }

    public function obtener_producto($id, $plataforma)
    {
        $sql = "SELECT ib.*, p.*, pl.* FROM `inventario_bodegas` AS ib INNER JOIN `productos` AS p ON p.`id_producto` = ib.`id_producto` inner join `plataformas` pl on p.id_plataforma = pl.id_plataforma WHERE `ib`.`id_producto` = $id;";;
        $data = [$id];
        return $this->select($sql, $data);
    }
    
    public function vaciarTmp()
    {
        $timestamp = session_id();       
        $sql = "delete FROM tmp_cotizacion WHERE session_id='$timestamp'";
        return $this->select($sql);
    }
}
