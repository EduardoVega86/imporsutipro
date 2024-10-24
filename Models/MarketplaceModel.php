<?php
class MarketplaceModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    ///productos

    public function obtener_productos($plataforma, $nombre, $linea, $plataforma_filtro, $min, $max, $favorito)
    {
        $where='';
        $favorito_filtro='';
        if (isset($nombre) and $nombre!= ''){
            $where .= " and p.nombre_producto like '%$nombre%' ";
        }
        
        if (isset($linea) and $linea!= ''){
            $where .= " and p.id_linea_producto = $linea ";
        }
        
        if (isset($plataforma_filtro) and $plataforma_filtro!= ''){
            $where .= " and p.id_plataforma = $plataforma_filtro ";
        }
        
         if (isset($min) and $min!= ''){
            $where .= " and ib.pvp >= $min ";
        }
        
        if (isset($max) and $max!= ''){
            $where .= " and ib.pvp <= $max ";
        }
        
        if ($favorito== 0){
                $favorito_filtro = " ";
        } else {
            $favorito_filtro = " AND pf.id_producto IS NOT NULL  ";
        
        }
        
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];
         
//        $sql = "SELECT DISTINCT p.nombre_producto, p.producto_variable, ib.*, plat.id_matriz,
//       CASE WHEN pf.id_producto IS NULL THEN 0 ELSE 1 END as Es_Favorito
//FROM productos p
//JOIN (
//    SELECT ib.id_producto, MIN(ib.sku) AS min_sku, ib.id_plataforma, ib.bodega, MIN(ib.id_inventario) AS min_id_inventario
//    FROM inventario_bodegas ib
//    WHERE ib.bodega != 0 AND ib.bodega != 50000
//    GROUP BY ib.id_producto, ib.id_plataforma, ib.bodega
//) ib_filtered ON p.id_producto = ib_filtered.id_producto
//JOIN inventario_bodegas ib ON ib.id_producto = ib_filtered.id_producto
//    AND ib.sku = ib_filtered.min_sku 
//    AND ib.id_inventario = ib_filtered.min_id_inventario
//JOIN plataformas plat ON ib.id_plataforma = plat.id_plataforma
//LEFT JOIN productos_favoritos pf ON pf.id_producto = p.id_producto
//WHERE (p.drogshipin = 1 OR p.id_plataforma = $plataforma) 
//    AND plat.id_matriz  =  $id_matriz $where $favorito_filtro" ;
        
         $sql = "SELECT DISTINCT 
    p.nombre_producto, 
    p.producto_variable, 
    ib.*, 
    plat.id_matriz, 
    CASE WHEN pf.id_producto IS NULL THEN 0 ELSE 1 END as Es_Favorito 
FROM 
    productos p 
JOIN 
    (
        SELECT 
            ib.id_producto, 
            MIN(ib.sku) AS min_sku, 
            ib.id_plataforma, 
            ib.bodega, 
            MIN(ib.id_inventario) AS min_id_inventario 
        FROM 
            inventario_bodegas ib 
        WHERE 
            ib.bodega != 0 
            AND ib.bodega != 50000 
            AND ib.saldo_stock > 0  -- Filtrar por saldo_stock mayor a 0
        GROUP BY 
            ib.id_producto, 
            ib.id_plataforma, 
            ib.bodega
    ) ib_filtered 
    ON p.id_producto = ib_filtered.id_producto 
JOIN 
    inventario_bodegas ib 
    ON ib.id_producto = ib_filtered.id_producto 
    AND ib.sku = ib_filtered.min_sku 
    AND ib.id_inventario = ib_filtered.min_id_inventario 
    AND ib.saldo_stock > 0  -- Asegurar que saldo_stock sea mayor a 0
JOIN 
    plataformas plat 
    ON ib.id_plataforma = plat.id_plataforma 
LEFT JOIN 
    productos_favoritos pf 
    ON pf.id_producto = p.id_producto 
    AND pf.id_plataforma = $plataforma 
WHERE 
    p.drogshipin = 1 
    AND p.producto_privado = 0 
    $where 
    $favorito_filtro 
    AND ib.id_plataforma NOT IN (
        SELECT id_plataforma 
        FROM plataforma_matriz 
        WHERE id_matriz = $id_matriz
    ) 
ORDER BY 
    RAND();
" ;
        
        
        //echo $sql;
        return $this->select($sql);
    }
    
    
    public function obtener_productos_privados($plataforma, $nombre, $linea, $plataforma_filtro, $min, $max, $favorito)
    {
        $where='';
        $favorito_filtro='';
        if (isset($nombre) and $nombre!= ''){
            $where .= " and p.nombre_producto like '%$nombre%' ";
        }
        
        if (isset($linea) and $linea!= ''){
            $where .= " and p.id_linea_producto = $linea ";
        }
        
        if (isset($plataforma_filtro) and $plataforma_filtro!= ''){
            $where .= " and p.id_plataforma = $plataforma_filtro ";
        }
        
         if (isset($min) and $min!= ''){
            $where .= " and ib.pvp >= $min ";
        }
        
        if (isset($max) and $max!= ''){
            $where .= " and ib.pvp <= $max ";
        }
        
        if ($favorito== 0){
                $favorito_filtro = " ";
        } else {
            $favorito_filtro = " AND pf.id_producto IS NOT NULL  ";
        
        }
        
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];
         
//        $sql = "SELECT DISTINCT p.nombre_producto, p.producto_variable, ib.*, plat.id_matriz,
//       CASE WHEN pf.id_producto IS NULL THEN 0 ELSE 1 END as Es_Favorito
//FROM productos p
//JOIN (
//    SELECT ib.id_producto, MIN(ib.sku) AS min_sku, ib.id_plataforma, ib.bodega, MIN(ib.id_inventario) AS min_id_inventario
//    FROM inventario_bodegas ib
//    WHERE ib.bodega != 0 AND ib.bodega != 50000
//    GROUP BY ib.id_producto, ib.id_plataforma, ib.bodega
//) ib_filtered ON p.id_producto = ib_filtered.id_producto
//JOIN inventario_bodegas ib ON ib.id_producto = ib_filtered.id_producto
//    AND ib.sku = ib_filtered.min_sku 
//    AND ib.id_inventario = ib_filtered.min_id_inventario
//JOIN plataformas plat ON ib.id_plataforma = plat.id_plataforma
//LEFT JOIN productos_favoritos pf ON pf.id_producto = p.id_producto
//WHERE (p.drogshipin = 1 OR p.id_plataforma = $plataforma) 
//    AND plat.id_matriz  =  $id_matriz $where $favorito_filtro" ;
        
         $sql = "SELECT DISTINCT 
    p.nombre_producto, 
    p.producto_variable, 
    ib.*, 
    plat.id_matriz, 
    CASE WHEN pf.id_producto IS NULL THEN 0 ELSE 1 END as Es_Favorito 
FROM 
    productos p 
JOIN (
    SELECT 
        ib.id_producto, 
        MIN(ib.sku) AS min_sku, 
        ib.id_plataforma, 
        ib.bodega, 
        MIN(ib.id_inventario) AS min_id_inventario 
    FROM 
        inventario_bodegas ib 
    WHERE 
        ib.bodega != 0 
        AND ib.bodega != 50000 
    GROUP BY 
        ib.id_producto, 
        ib.id_plataforma, 
        ib.bodega 
) ib_filtered 
    ON p.id_producto = ib_filtered.id_producto 
JOIN 
    inventario_bodegas ib 
    ON ib.id_producto = ib_filtered.id_producto 
    AND ib.sku = ib_filtered.min_sku 
    AND ib.id_inventario = ib_filtered.min_id_inventario 
JOIN 
    plataformas plat 
    ON ib.id_plataforma = plat.id_plataforma 
JOIN 
    producto_privado pp 
    ON p.id_producto = pp.id_producto 
    AND pp.id_plataforma = $plataforma
LEFT JOIN 
    productos_favoritos pf 
    ON pf.id_producto = p.id_producto 
    AND pf.id_plataforma = $plataforma 
WHERE 
    p.drogshipin = 1 
    AND p.producto_privado = 1 
    $where 
    $favorito_filtro;
" ;
        
        
      //echo $sql;
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
       
          }
         
      //print_r($insertar_caracteristica);
        
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
        $sql = "SELECT ib.*, p.*, pl.* FROM `inventario_bodegas` AS ib INNER JOIN `productos` AS p ON p.`id_producto` = ib.`id_producto` inner join `plataformas` pl on p.id_plataforma = pl.id_plataforma WHERE `ib`.`id_producto` = $id;";
       // echo $sql;
        $data = [$id];
        return $this->select($sql, $data);
    }
    
    public function vaciarTmp()
    {
        $timestamp = session_id();       
        $sql = "delete FROM tmp_cotizacion WHERE session_id='$timestamp'";
        return $this->select($sql);
    }
    
    public function obtenerMaximo() {
        
         $sql = "SELECT MAX(pvp) precio_maximo FROM inventario_bodegas ib, productos p WHERE p.id_producto= ib.id_producto and p.drogshipin=1;";
         
        $id_producto = $this->select($sql);
        $maximo = $id_producto[0]['precio_maximo'];
        return $maximo;
        
    }
    
    
    public function obtenerProveedores()
    {
        
         $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];
        
        $sql = "SELECT * FROM `plataformas` where proveedor=1 AND id_plataforma NOT IN (
        SELECT id_plataforma 
        FROM plataforma_matriz 
        where id_matriz=$id_matriz )";
        
       // echo $sql;
        return $this->select($sql);
    }
    
     public function agregarFavoritos($id_producto, $plataforma, $favorito)
    {
          $response = $this->initialResponse();
         if($favorito==0){
         $sql = "INSERT INTO `productos_favoritos` (`id_producto`, `id_plataforma`) VALUES (?, ?);";
        $data = [$id_producto, $plataforma];
        $favorito = $this->insert($sql, $data);  
         }else{
            
        $sql = "DELETE FROM productos_favoritos WHERE id_producto = ? AND id_plataforma = ?";
        $data = [$id_producto, $plataforma];   
          $favorito = $this->delete($sql, $data); 
            
         }
         
       //  print_r($favorito);
         if ($favorito == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto agregado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $favorito['message'];
        }
             
          return $response;  
     
    }
      
}
