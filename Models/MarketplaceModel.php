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
        $sql = "SELECT p.nombre_producto, p.producto_variable ,ib.*
FROM productos p
JOIN (
    SELECT 
        ib.id_producto, 
        ib.sku, 
        ib.id_plataforma, 
        ib.bodega, 
        MIN(ib.id_inventario) as min_id_inventario
    FROM inventario_bodegas ib
    WHERE ib.bodega != 0 AND ib.bodega != 50000
    GROUP BY ib.id_producto, ib.sku, ib.id_plataforma, ib.bodega
) ib_filtered
ON p.id_producto = ib_filtered.id_producto 
JOIN inventario_bodegas ib
ON ib.id_producto = ib_filtered.id_producto AND ib.sku = ib_filtered.sku AND ib.id_inventario = ib_filtered.min_id_inventario
WHERE (p.drogshipin = 1 OR p.id_plataforma = 1168)
  AND ((p.drogshipin = 1 AND ib.id_plataforma = p.id_plataforma) 
   OR (ib.id_plataforma = p.id_plataforma));
";
        return $this->select($sql);
    }

    public function agregarMarketplace($codigo_producto,  $plataforma)
    {
        $response = $this->initialResponse();
        $sql_update = "update productos set drogshipin=? where id_producto=?";
        $data_update = [1, $codigo_producto];
        $actualizar_stock = $this->obtenerBodegaProducto($sql_update, $data_update);
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
    public function agregarTmp($id_producto, $cantidad, $precio,  $plataforma, $sku, $id_invetario)
    {
        //verificar productos
         $timestamp = session_id();
         //echo "SELECT * FROM tmp_cotizacion WHERE session_id = '$timestamp' and id_producto=$id_producto and sku=$sku";
          $cantidad_tmp = $this->select("SELECT * FROM tmp_cotizacion WHERE session_id = '$timestamp' and id_inventario=$id_invetario" );
                   
          //print_r($cantidad_tmp);
          if (empty($cantidad_tmp)){
              $id_inventario=$this->obtenerBodegaProducto($id_producto, $sku);
                      
              $sql = "INSERT INTO `tmp_cotizacion` (`id_producto`, `cantidad_tmp`, `precio_tmp`, `session_id`, `id_plataforma`, `sku`, `id_inventario`) VALUES (?, ?, ?, ?, ?, ?, ?);";
        $data = [$id_producto, $cantidad, $precio, $timestamp, $plataforma, $sku, $id_inventario];
        $insertar_caracteristica = $this->insert($sql, $data);
        //print_r($insertar_caracteristica);
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

    public function obtenerBodegaProducto($id_producto, $sku) {
       // echo $sku;
        $sql_invetario = "SELECT * FROM inventario_bodegas WHERE id_producto = $id_producto and sku='$sku'";
        //echo $sql_invetario;
        $invetario = $this->select($sql_invetario);
        $id_invetario = $invetario[0]['id_inventario'];
        return $id_invetario;
        
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
