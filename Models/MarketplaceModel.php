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
        $sql = "SELECT * FROM `productos` where drogshipin = 1 or id_plataforma=$plataforma";
        return $this->select($sql);
    }

    public function agregarMarketplace($codigo_producto,  $plataforma)
    {
        $response = $this->initialResponse();
        $sql_update = "update productos set drogshipin=? where id_producto=?";
        $data_update = [$codigo_producto, $plataforma];
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

    public function agregarTmp($id_producto, $cantidad, $precio,  $plataforma, $sku)
    {
        $timestamp = session_id();
        $sql = "INSERT INTO `tmp_cotizacion` (`id_producto`, `cantidad_tmp`, `precio_tmp`, `desc_tmp`, `session_id`, `id_plataforma`, `sku`) VALUES ('1', '1', '1', '1', '1', '1', '1');";
        $data = [$id_producto, $cantidad, $precio, $timestamp, $plataforma, $sku];
        $insertar_caracteristica = $this->insert($sql, $data);
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
}
