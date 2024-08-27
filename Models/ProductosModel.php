<?php
class ProductosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    ///productos

    public function obtener_productos($plataforma)
    {
        $sql = "SELECT ib.*, p.*
FROM `inventario_bodegas` AS ib
INNER JOIN `productos` AS p ON p.`id_producto` = ib.`id_producto`
WHERE ib.`id_plataforma` = $plataforma
GROUP BY p.`id_producto`, ib.`id_plataforma`, ib.`bodega`;";

        return $this->select($sql);
    }

    public function obtener_productos_privados($plataforma)
    {
        $sql = "SELECT ib.*, p.*
FROM `inventario_bodegas` AS ib
INNER JOIN `productos` AS p ON p.`id_producto` = ib.`id_producto`
WHERE ib.`id_plataforma` = $plataforma and producto_privado=1
GROUP BY p.`id_producto`, ib.`id_plataforma`, ib.`bodega`;";

        return $this->select($sql);
    }

    public function obtener_productos_tienda($plataforma)
    {
        $sql = "SELECT * FROM `productos_tienda` pt, productos p, inventario_bodegas ib WHERE  pt.id_producto=p.id_producto and pt.id_inventario=ib.id_inventario and pt.id_plataforma=$plataforma";

        return $this->select($sql);
    }

    public function importar_productos_tienda($id_producto, $plataforma)
    {
        $response = $this->initialResponse();
        $inicial_variable = $this->select("SELECT * FROM productos_tienda WHERE id_plataforma = $plataforma AND id_producto = $id_producto");

        if (empty($inicial_variable)) {
            $inventario = $this->select("SELECT * FROM inventario_bodegas ib, productos p WHERE p.id_producto = $id_producto AND ib.id_producto = p.id_producto");

            $detalle_sql = "INSERT INTO productos_tienda (id_plataforma, id_producto, nombre_producto_tienda, imagen_principal_tienda, pvp_tienda, id_inventario, id_categoria_tienda, descripcion_tienda, landing_tienda) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            foreach ($inventario as $inv) {
                $detalle_data = array(
                    $plataforma,
                    $id_producto,
                    $inv['nombre_producto'],
                    $inv['image_path'],
                    $inv['pvp'],
                    $inv['id_inventario'],
                    $inv['id_linea_producto'],
                    $inv['descripcion_producto'],
                    $inv['landing']
                );
                $guardar_detalle = $this->insert($detalle_sql, $detalle_data);

                if ($guardar_detalle == 1) {
                    // Obtener el último ID insertado en productos_tienda
                    $ultimo_id = $this->select("SELECT MAX(id_producto_tienda) as id FROM productos_tienda");
                    $ultimo_id_producto_tienda = $ultimo_id[0]['id'];

                    // Imprimir el último ID
                    //echo "El último ID de producto en la tienda es: " . $ultimo_id_producto_tienda;

                    // Obtener las imágenes adicionales del producto
                    $imagenes_adicionales = $this->select("SELECT * FROM imagenes_adicionales_producto WHERE id_producto = $id_producto");
                    //print_r($imagenes_adicionales);
                    // Preparar la consulta de inserción para las imágenes adicionales
                    $insert_img_sql = "INSERT INTO imagens_adicionales_productoTienda (id_producto, num_imagen, url, id_plataforma) VALUES (?, ?, ?, ?)";

                    // Insertar cada imagen adicional en la tabla imagenes_adicionales_productoTienda
                    foreach ($imagenes_adicionales as $img) {

                        $img_data = [
                            $ultimo_id_producto_tienda,  // Reemplazar id_producto con el último ID obtenido
                            $img['num_imagen'],
                            $img['url'],
                            $plataforma
                        ];
                        $guardar_imagenes = $this->insert($insert_img_sql, $img_data);
                        // print_r($guardar_imagenes);
                    }

                    $response['status'] = 200;
                    $response['title'] = 'Petición exitosa';
                    $response['message'] = 'Producto y sus imágenes adicionales agregados correctamente';
                }
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El producto ya existe en su tienda.';
        }

        return $response;
    }


    public function obtener_productos_inventario($plataforma)
    {
        $sql = "SELECT * FROM productos p LEFT JOIN inventario_bodegas ib ON p.id_producto = ib.id_producto AND ib.id_plataforma = $plataforma LEFT JOIN variedades v ON ib.id_variante = v.id_variedad WHERE ib.id_plataforma = $plataforma";
        //  echo $sql;
        return $this->select($sql);
    }


    public function agregarProducto($codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $plataforma, $stock_inicial, $bodega, $pcp, $pvp, $pref)
    {
        $response = $this->initialResponse();
        //  echo $descripcion_producto;
        //echo '------';

        // $descripcion_producto = "<p><strong>2 EN 1 x 100 CAPSULAS</strong></p><p>- Mejora la atención y la memoria.</p><p>- Mejora el rendimiento cerebral.</p><p>- Reduce la capacidad de concentración.</p><p>- Mejora la función cognitiva.</p>";
        // echo $descripcion_producto;
        $sql = "INSERT INTO productos (codigo_producto, nombre_producto, descripcion_producto, id_linea_producto, inv_producto, producto_variable, costo_producto, aplica_iva, estado_producto, date_added, image_path, id_imp_producto, pagina_web, formato, drogshipin, destacado, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $plataforma];
        $insertar_producto = $this->insert($sql, $data);

        /* print_r($insertar_producto); */
        if (!$pref) {
            $pref = 0;
        }
        $sql_id = "SELECT MAX(id_producto) as id_producto from productos where id_plataforma = $plataforma";
        $id_producto = $this->select($sql_id);
        $id_producto = $id_producto[0]['id_producto'];
        if ($inv_producto == 1) {
            //echo $producto_variable;

            if ($producto_variable == 0) {

                // echo 'con invetario';
                $sql = "INSERT INTO inventario_bodegas (sku, id_producto, id_variante, bodega, pcp, pvp, pref, stock_inicial, saldo_stock, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $data = [$codigo_producto, $id_producto, 0, $bodega, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $plataforma];
            } else {
                // echo 'sin invetario';
                $sql = "INSERT INTO inventario_bodegas (sku, id_producto, id_variante, bodega, pcp, pvp, pref, stock_inicial, saldo_stock, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $data = [$codigo_producto, $id_producto, $producto_variable, 50000, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $plataforma];
            }
            $insertar_producto = $this->insert($sql, $data);
            // print_r($insertar_producto);
        } else {
            //bodega inicial
            $sql_bodega = "SELECT * FROM bodega WHERE id_plataforma = $plataforma limit 1";
            $bodega = $this->select($sql_bodega);
            $bodega = $bodega[0]['id'];
            $sql_insert = "INSERT INTO inventario_bodegas (sku, id_producto, id_variante, bodega, pcp, pvp, pref, stock_inicial, saldo_stock, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data_insert = [$codigo_producto, $id_producto, 0, $bodega, $pcp, $pvp, $pref, 0, 0, $plataforma];
            $insertar_producto = $this->insert($sql_insert, $data_insert);
        }
        // print_r($insertar_producto);
        if ($insertar_producto == 1) {
            //echo $codigo_producto;
            $id_usuario = $_SESSION['id'];
            $id_inventario = $this->buscar_inventario($id_producto, $codigo_producto);
            $referencia = 'STOCK INICIAL';
            $nota = "Se agrego $stock_inicial productos(s) al inventario";
            $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [$id_usuario, $id_inventario, $plataforma, $codigo_producto,  $nota, $referencia, $stock_inicial, 1, $bodega, $id_producto];
            $insertar_historial = $this->insert($sql, $data);
            //$response['data']=
            // print_r($insertar_historial);

            if ($insertar_historial === 1) {
                $response['message'] = 'Producto y stock agregado correctamente';


                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Producto agregado correctamente';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al agregar el producto';
        }

        return $response;
    }

    public function buscar_inventario($id, $sku)
    {

        $sql_invetario = "SELECT * FROM inventario_bodegas WHERE id_producto = $id and sku='$sku'";
        //echo $sql_invetario;
        $invetario = $this->select($sql_invetario);
        $id_invetario = $invetario[0]['id_inventario'];
        return $id_invetario;
    }


    public function editarProductoTienda($id_producto_tienda, $nombre, $pvp_tienda, $id_categoria, $pref)
    {
        $response = $this->initialResponse();
        $sql = "UPDATE `productos_tienda` SET `nombre_producto_tienda`=?,"
            . "`pvp_tienda`=?,`id_categoria_tienda`=?,"
            . "`pref_tienda`=? WHERE id_producto_tienda=?";
        // echo $sql;
        $data = [$nombre, $pvp_tienda, $id_categoria,  $pref, $id_producto_tienda];
        //  print_r($data);
        $editar_producto = $this->update($sql, $data);
        $pref = $pref ?? 0;
        // print_r($editar_producto);
        if ($editar_producto == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto editado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $editar_producto['message'];
        }
        return $response;
    }


    public function editarProducto($id, $codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $plataforma, $stock_inicial, $bodega, $pcp, $pvp, $pref)
    {
        $response = $this->initialResponse();
        $sql = "UPDATE productos SET codigo_producto = ?, nombre_producto = ?, descripcion_producto = ?, id_linea_producto = ?, inv_producto = ?, producto_variable = ?, costo_producto = ?, aplica_iva = ?, estado_producto = ?, date_added = ?, id_imp_producto = ?, pagina_web = ?, formato = ?, drogshipin = ?, destacado = ? WHERE id_producto = ? AND id_plataforma = ?";
        $data = [$codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $id, $plataforma];
        $editar_producto = $this->update($sql, $data);

        if ($inv_producto == 1) {
            if ($producto_variable === 0) {
                $sql = "UPDATE inventario_bodegas SET sku = ?, id_producto = ?, id_variante = ?, bodega = ?, pcp = ?, pvp = ?, pref = ?, stock_inicial = ?, saldo_stock = ? WHERE id_producto = ? AND id_plataforma = ?";
                $data = [$codigo_producto, $id, 0, $bodega, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $id, $plataforma];
            } else {
                $sql = "UPDATE inventario_bodegas SET sku = ?, id_producto = ?, id_variante = ?, bodega = ?, pcp = ?, pvp = ?, pref = ?, stock_inicial = ?, saldo_stock = ? WHERE id_producto = ? AND id_plataforma = ?";
                $data = [$codigo_producto, $id, $producto_variable, $bodega, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $id, $plataforma];
            }
            $editar_producto_ = $this->update($sql, $data);
        } else {
            $sql_bodega = "SELECT * FROM bodega WHERE id_plataforma = $plataforma limit 1";
            $bodega = $this->select($sql_bodega);
            $bodega = $bodega[0]['id'];

            $sql_insert = "UPDATE inventario_bodegas SET sku = ?, id_producto = ?, id_variante = ?, bodega = ?, pcp = ?, pvp = ?, pref = ?, stock_inicial = ?, saldo_stock = ? WHERE id_producto = ? AND id_plataforma = ?";
            $data_insert = [$codigo_producto, $id, 0, $bodega, $pcp, $pvp, $pref, 0, 0, $id, $plataforma];
            $insertar_producto_ = $this->update($sql_insert, $data_insert);
        }
        // print_r($insertar_producto_);
        if ($editar_producto == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto editado correctamente';
            if ($editar_producto_ === 1) {
                $response['message'] = 'Producto y stock editado correctamente';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $editar_producto['message'];
        }
        return $response;
    }

    public function eliminarProducto($id, $plataforma)
    {
        $response = $this->initialResponse();

        $inicial_prod = $this->select("SELECT * from detalle_fact_cot  where id_producto= $id");

        //print_r($inicial_prod);
        $ingreso_tienda = 0;
        if (empty($inicial_prod)) {


            $sql = "DELETE FROM productos WHERE id_producto = ? AND id_plataforma = ?";
            $data = [$id, $plataforma];
            $eliminar_producto = $this->delete($sql, $data);
            if ($eliminar_producto == 1) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Producto eliminado correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = $eliminar_producto['message'];
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El producto ya tienes pedidos vinculados';
        }
        return $response;
    }

    public function obtenerProducto($id, $plataforma)
    {
        $sql = "SELECT * FROM `productos` p, inventario_bodegas ib where p.id_producto=$id and p.id_producto=ib.id_producto limit 1;";
        // echo $sql;
        return $this->select($sql);
    }

    public function obtener_bodegas($plataforma)
    {
        $sql = "SELECT * FROM `bodega` WHERE id_plataforma = $plataforma;";
        // echo $sql;
        return $this->select($sql);
    }

    public function obtenerProductoTienda($id)
    {
        $sql = "SELECT * FROM `productos_tienda` pt, productos p, inventario_bodegas ib WHERE  pt.id_producto=p.id_producto and pt.id_inventario=ib.id_inventario and pt.id_producto_tienda=$id";
        //$sql = "SELECT * FROM `productos` p, inventario_bodegas ib where p.id_producto=$id and p.id_producto=ib.id_producto limit 1;";
        // echo $sql;
        return $this->select($sql);
    }

    public function eliminar_producto_tienda($id, $plataforma)
    {
        $response = $this->initialResponse();
        $sql = "DELETE FROM productos_tienda WHERE id_producto_tienda = ? AND id_plataforma = ?";
        $data = [$id, $plataforma];
        $eliminar_producto = $this->delete($sql, $data);
        if ($eliminar_producto == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $eliminar_producto['message'];
        }
        return $response;
    }


    public function obtenerProductoFavoritos($id, $plataforma)
    {
        $sql = "SELECT * FROM productos p inner join inventario_bodegas ib on p.codigo_producto = ib.sku WHERE p.id_producto = $id AND p.id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function guardar_imagen_productos($imagen, $id_producto, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/productos/";
        $imageFileType = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));

        // Generar un nombre de archivo único
        $unique_name = uniqid('', true) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Verificar si el archivo existe y agregar un diferenciador si es necesario
        $original_target_file = $target_file;
        $counter = 1;
        while (file_exists($target_file)) {
            $target_file = $target_dir . uniqid('', true) . '.' . $imageFileType;
            $counter++;
        }

        $uploadOk = 1;
        $check = getimagesize($imagen["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo no es una imagen';
            $uploadOk = 0;
        }

        if ($imagen["size"] > 50000000) { // Tamaño máximo permitido
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo es muy grande';
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Solo se permiten archivos JPG, JPEG, PNG';
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al subir la imagen';
        } else {
            if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida correctamente';
                $response['data'] = $target_file;

                $sql = "UPDATE productos SET image_path = ? WHERE id_producto = ? AND id_plataforma = ?";
                $data = [$target_file, $id_producto, $plataforma];
                $editar_imagen = $this->update($sql, $data);

                if ($editar_imagen == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Imagen subida correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al actualizar la base de datos';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al mover el archivo';
            }
        }

        return $response;
    }


    public function guardar_imagenAdicional_productos($num_imagen, $imagen, $id_producto, $plataforma)
    {
        // Verificar si ya existe un registro con el id_plataforma dado
        $sql_select = "SELECT * FROM `imagenes_adicionales_producto` WHERE `id_producto` = ? AND `id_plataforma` = ? AND `num_imagen` = ?";
        $existing_entry = $this->simple_select($sql_select, [$id_producto, $plataforma, $num_imagen]);

        $response = $this->initialResponse();

        $target_dir = "public/img/productos/";
        $target_file = $target_dir . basename($imagen["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($imagen["tmp_name"]);

        if ($check === false) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo no es una imagen';
            return $response;
        }

        if ($imagen["size"] > 500000) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo es muy grande';
            return $response;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Solo se permiten archivos JPG, JPEG, PNG';
            return $response;
        }

        if (!move_uploaded_file($imagen["tmp_name"], $target_file)) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al subir la imagen';
            return $response;
        }

        // Si ya existe, actualizar el registro
        if ($existing_entry > 0) {
            $sql = "UPDATE imagenes_adicionales_producto SET url = ? WHERE id_producto = ? AND id_plataforma = ? AND num_imagen = ?";
            $data = [$target_file, $id_producto, $plataforma, $num_imagen];
            $editar_imagen = $this->update($sql, $data);

            if ($editar_imagen == 1) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida y actualizada correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al actualizar la imagen';
            }
        } else {
            // Si no existe, insertar el nuevo registro
            $sql_insert = "INSERT INTO imagenes_adicionales_producto (id_plataforma, id_producto, num_imagen, url) VALUES (?, ?, ?, ?)";
            $data = [$plataforma, $id_producto, $num_imagen, $target_file];
            $insertar_imagen = $this->insert($sql_insert, $data);

            if ($insertar_imagen == 1) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida e insertada correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al insertar la imagen';
            }
        }

        return $response;
    }

    public function guardar_imagen_productosTienda($imagen, $id_producto, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/productos_tienda/";
        $imageFileType = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));

        // Generar un nombre de archivo único
        $unique_name = uniqid('', true) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Verificar si el archivo existe y agregar un diferenciador si es necesario
        $original_target_file = $target_file;
        $counter = 1;
        while (file_exists($target_file)) {
            $target_file = $target_dir . uniqid('', true) . '.' . $imageFileType;
            $counter++;
        }

        $uploadOk = 1;
        $check = getimagesize($imagen["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo no es una imagen';
            $uploadOk = 0;
        }

        if ($imagen["size"] > 50000000) { // Tamaño máximo permitido
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo es muy grande';
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Solo se permiten archivos JPG, JPEG, PNG';
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al subir la imagen';
        } else {
            if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida correctamente';
                $response['data'] = $target_file;

                $sql = "UPDATE productos_tienda SET imagen_principal_tienda = ? WHERE id_producto_tienda = ? AND id_plataforma = ?";
                $data = [$target_file, $id_producto, $plataforma];
                $editar_imagen = $this->update($sql, $data);

                if ($editar_imagen == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Imagen subida correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al actualizar la base de datos';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al mover el archivo';
            }
        }

        return $response;
    }

    public function guardar_imagenAdicional_productosTienda($num_imagen, $imagen, $id_producto, $plataforma)
    {
        // Verificar si ya existe un registro con el id_plataforma dado
        $sql_select = "SELECT * FROM `imagens_adicionales_productoTienda` WHERE `id_producto` = ? AND `id_plataforma` = ? AND `num_imagen` = ?";
        $existing_entry = $this->simple_select($sql_select, [$id_producto, $plataforma, $num_imagen]);

        $response = $this->initialResponse();

        $target_dir = "public/img/productos_tienda/";
        $target_file = $target_dir . basename($imagen["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($imagen["tmp_name"]);

        if ($check === false) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo no es una imagen';
            return $response;
        }

        if ($imagen["size"] > 500000) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo es muy grande';
            return $response;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Solo se permiten archivos JPG, JPEG, PNG';
            return $response;
        }

        if (!move_uploaded_file($imagen["tmp_name"], $target_file)) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al subir la imagen';
            return $response;
        }

        // Si ya existe, actualizar el registro
        if ($existing_entry > 0) {
            $sql = "UPDATE imagens_adicionales_productoTienda SET url = ? WHERE id_producto = ? AND id_plataforma = ? AND num_imagen = ?";
            $data = [$target_file, $id_producto, $plataforma, $num_imagen];
            $editar_imagen = $this->update($sql, $data);

            if ($editar_imagen == 1) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida y actualizada correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al actualizar la imagen';
            }
        } else {
            // Si no existe, insertar el nuevo registro
            $sql_insert = "INSERT INTO imagens_adicionales_productoTienda (id_plataforma, id_producto, num_imagen, url) VALUES (?, ?, ?, ?)";
            $data = [$plataforma, $id_producto, $num_imagen, $target_file];
            $insertar_imagen = $this->insert($sql_insert, $data);

            if ($insertar_imagen == 1) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida e insertada correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al insertar la imagen';
            }
        }

        return $response;
    }

    public function listar_imagenAdicional_productos($id_producto, $plataforma)
    {
        $sql = "SELECT * FROM `imagenes_adicionales_producto` WHERE id_plataforma = $plataforma AND id_producto = $id_producto";
        // echo $sql;
        return $this->select($sql);
    }

    public function listar_imagenAdicional_productosTienda($id_producto, $plataforma)
    {
        $sql = "SELECT * FROM `imagens_adicionales_productoTienda` WHERE id_plataforma = $plataforma AND id_producto = $id_producto";
        // echo $sql;
        return $this->select($sql);
    }

    public function obtener_productos_bodegas($id_bodega, $plataforma)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_bodega = $id_bodega and id_plataforma = $plataforma";
        return $this->select($sql);
    }

    ///categorias

    public function agregarCategoria($nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $plataforma, $orden)
    {
        // codigo para agregar categoria
        $response = $this->initialResponse();

        $sql = "INSERT INTO lineas (nombre_linea, descripcion_linea, estado_linea, date_added, online, imagen, tipo, padre, orden, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $orden, $plataforma];
        $insertar_categoria = $this->insert($sql, $data);
        if ($insertar_categoria == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria agregada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] =  $insertar_categoria['message'];
        }
        return $response;
    }


    public function cargarCategorias($plataforma)
    {
        $sql = "SELECT * FROM lineas WHERE id_plataforma = $plataforma or global=1";
        return $this->select($sql);
    }

    public function editarCategoria($id, $nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $plataforma, $orden)
    {
        // codigo para editar categoria
        $response = $this->initialResponse();

        $sql = "UPDATE lineas SET nombre_linea = ?, descripcion_linea = ?, estado_linea = ?, date_added = ?, online = ?, tipo = ?, padre = ?, orden = ? WHERE id_linea = ? AND id_plataforma = ?";
        $data = [$nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online,  $tipo, $padre, $orden, $id, $plataforma];
        $editar_categoria = $this->update($sql, $data);
        if ($editar_categoria == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria editada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al editar la categoria';
        }
        return $response;
    }

    public function eliminarCategoria($id, $plataforma)
    {
        // codigo para eliminar categoria
        $response = $this->initialResponse();

        $sql = "DELETE FROM lineas WHERE id_linea = ? AND id_plataforma = ?";
        $data = [$id, $plataforma];
        $eliminar_categoria = $this->delete($sql, $data);
        if ($eliminar_categoria == 1) {
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


    public function listarCategoria($id, $plataforma)
    {
        $sql = "SELECT * FROM lineas WHERE id_linea = $id AND id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function listarPlataformas()
    {
        $sql = "SELECT * FROM pla WHERE id_linea = $id AND id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function guardar_imagen_categorias($imagen, $id_categoria, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/categorias/";
        $imageFileType = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));

        // Generar un nombre de archivo único
        $unique_name = uniqid('', true) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Verificar si el archivo existe y agregar un diferenciador si es necesario
        $original_target_file = $target_file;
        $counter = 1;
        while (file_exists($target_file)) {
            $target_file = $target_dir . uniqid('', true) . '.' . $imageFileType;
            $counter++;
        }

        $uploadOk = 1;
        $check = getimagesize($imagen["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo no es una imagen';
            $uploadOk = 0;
        }
        if ($imagen["size"] > 50000000) { // Asegúrate de que este límite de tamaño sea correcto para tu caso de uso
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo es muy grande';
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Solo se permiten archivos JPG, JPEG, PNG';
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al subir la imagen';
        } else {
            if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida correctamente';
                $response['data'] = $target_file;

                $sql = "UPDATE lineas SET imagen = ? WHERE id_linea = ? AND id_plataforma = ?";
                $data = [$target_file, $id_categoria, $plataforma];
                $editar_imagen = $this->update($sql, $data);
                if ($editar_imagen == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Imagen subida correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al subir la imagen';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
            }
        }
        return $response;
    }


    ///bodegas

    public function agregarBodega($nombre, $direccion, $telefono, $ciudad, $provincia, $contacto, $telefono_contacto, $numerocasa, $referencia, $plataforma, $longitud, $latitud)
    {
        // codigo para agregar categoria
        $response = $this->initialResponse();

        $sql = "INSERT INTO `bodega` (`nombre`,`id_empresa`,`longitud`,`latitud`,`direccion`,`num_casa`,`referencia`,`responsable`,`contacto`,`localidad`,`provincia`,`id_plataforma`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$nombre, $plataforma, $longitud, $latitud, $direccion, $numerocasa, $referencia, $contacto, $telefono_contacto, $ciudad, $provincia, $plataforma];
        $insertar_categoria = $this->insert($sql, $data);
        if ($insertar_categoria == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria agregada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $insertar_categoria['message'];
        }
        return $response;
    }

    public function obtenerBodega($id, $plataforma)
    {
        $sql = "SELECT * FROM bodega WHERE id= $id AND id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function editarBodega($id, $nombre, $direccion, $telefono, $ciudad, $provincia, $contacto, $telefono_contacto, $numerocasa, $referencia, $plataforma, $longitud, $latitud)
    {
        // codigo para editar categoria
        $response = $this->initialResponse();

        $sql = "UPDATE `bodega` SET `nombre` = ?, `longitud` = ?, `latitud` = ?, `direccion` = ?, `num_casa` = ?, `referencia` = ?, `responsable` = ?, `contacto` = ?, `localidad` = ?, `provincia` = ? WHERE `id` = ? ";
        $data = [$nombre, $longitud, $latitud, $direccion, $numerocasa, $referencia, $contacto, $telefono_contacto, $ciudad, $provincia, $id];
        $editar_categoria = $this->update($sql, $data);
        //print_r($editar_categoria);
        if ($editar_categoria == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria editada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $editar_categoria['message'];
        }
        return $response;
    }

    public function eliminarBodega($id, $plataforma)
    {
        // codigo para eliminar categoria
        $response = $this->initialResponse();

        $sql = "DELETE FROM bodega WHERE id = ? AND id_plataforma = ?";
        $data = [$id, $plataforma];
        $eliminar_categoria = $this->delete($sql, $data);
        if ($eliminar_categoria == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria eliminada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $eliminar_categoria['message'];
        }
        return $response;
    }




    public function listarBodegas($plataforma)
    {
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];

        //        $sql = "SELECT DISTINCT b.*, p.*
        //FROM bodega b
        //JOIN plataformas p ON b.id_plataforma = p.id_plataforma
        //WHERE b.id_plataforma = $plataforma
        //   OR (b.global = 1 AND p.id_matriz = $id_matriz)";

        $sql_full = "select full_f from plataformas where id_plataforma=$plataforma";
        $bodega_full = $this->select($sql_full);
        $full_filme = $bodega_full[0]['full_f'];

        if ($full_filme == 0) {
            $sql = "SELECT DISTINCT b.*, p.*
FROM bodega b
JOIN plataformas p ON b.id_plataforma = p.id_plataforma
WHERE b.id_plataforma = $plataforma";
        } else {
            $sql = "SELECT DISTINCT b.*, p.*
                FROM bodega b
                JOIN plataformas p ON b.id_plataforma = p.id_plataforma
                WHERE b.id_plataforma = $plataforma
                OR (b.global = 1 AND p.id_matriz = $id_matriz)";
        }


        //echo $sql;
        return $this->select($sql);
    }



    ///caracteristicas

    public function agregarCaracteristica($variedad, $id_atributo, $plataforma)
    {
        $response = $this->initialResponse();
        $sql = "INSERT INTO variedades (variedad, id_atributo, id_plataforma) VALUES (?, ?, ?)";
        $data = [$variedad, $id_atributo, $plataforma];
        $insertar_caracteristica = $this->insert($sql, $data);
        if ($insertar_caracteristica == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Caracteristica agregada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al agregar la caracteristica';
        }
        return $response;
    }

    public function agregarVariable($id_variedad, $id_producto, $sku, $bodega, $pcp, $pvp, $stock, $plataforma, $pref)
    {

        //  echo $bodega;
        $response = $this->initialResponse();
        $inicial_variable = $this->select("SELECT id_inventario FROM inventario_bodegas WHERE id_producto = '$id_producto' and bodega=50000");

        //print_r($cantidad_tmp);
        if (empty($inicial_variable)) {
            $sql = "INSERT INTO inventario_bodegas (id_producto, sku, id_variante, bodega, pcp, pvp, stock_inicial, saldo_stock, id_plataforma, pref) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [$id_producto, $sku, $id_variedad, $bodega, $pcp, $pvp, $stock, $stock, $plataforma, $pref];
            $insertar_caracteristica = $this->insert($sql, $data);
        } else {
            // print_r($inicial_variable);
            $id_inventario = $inicial_variable[0]["id_inventario"];
            $sql = "UPDATE `inventario_bodegas` SET  `sku`=?, `bodega`=? ,`pcp`=? ,`pvp`=? ,`stock_inicial`=? ,`saldo_stock`=?, `id_variante`=?  WHERE `id_inventario` = ?";
            $data = [$sku, $bodega, $pcp, $pvp, $stock, $stock, $id_variedad, $id_inventario];
            $insertar_caracteristica = $this->update($sql, $data);
        }


        // print_r($insertar_caracteristica);
        if ($insertar_caracteristica == 1) {
            $id_usuario = $_SESSION['id'];
            $cantidad = $stock;
            $referencia = 'STOCK INICIAL';
            $nota = "Se agrego $stock productos(s) al inventario";
            $id_inventario = $this->buscar_inventario($id_producto, $sku);
            $nota = "Se agrego $cantidad productos(s) al inventario";
            $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [$id_usuario, $id_inventario, $plataforma, $sku,  $nota, $referencia, $cantidad, 1, $bodega, $id_producto];
            $insertar_historial = $this->insert($sql, $data);

            if ($insertar_historial == 1) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Producto agregado correctamente';
                if ($insertar_producto_ === 1) {
                    $response['message'] = 'Producto y stock agregado correctamente';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al agregar el historial';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al agregar la caracteristica';
        }
        return $response;
    }

    public function listarCaracteristicas($plataforma)
    {

        $sql = "SELECT * FROM variedades WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function listarAtributos($id, $plataforma)
    {
        $sql = "SELECT * FROM atributos WHERE id_plataforma in (0, $plataforma)";
        return $this->select($sql);
    }

    public function eliminarCaracteristica($id, $plataforma)
    {
        $response = $this->initialResponse();
        $sql = "DELETE FROM variedades WHERE id_variedad = ? AND id_plataforma = ?";
        $data = [$id, $plataforma];
        $eliminar_caracteristica = $this->delete($sql, $data);
        if ($eliminar_caracteristica == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Caracteristica eliminada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al eliminar la caracteristica';
        }
        return $response;
    }

    public function BajarMarketplace($id, $plataforma)
    {
        $sql = "UPDATE `productos` SET  `drogshipin` = ? WHERE `id_producto` = ? AND `id_plataforma` = ?";
        $data = [0, $id, $plataforma];
        $editar_producto = $this->update($sql, $data);
        //print_r($editar_producto);
        if ($editar_producto == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria editada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            // $response['message'] = $editar_producto['message'];
        }
        return $response;
    }

    public function SubirMarketplace($id, $plataforma)
    {
        $sql = "UPDATE `productos` SET  `drogshipin` = ? WHERE `id_producto` = ? AND `id_plataforma` = ?";
        $data = [1, $id, $plataforma];
        $editar_producto = $this->update($sql, $data);
        //print_r($editar_producto);
        if ($editar_producto == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria editada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            // $response['message'] = $editar_producto['message'];
        }
        return $response;
    }

    public function listarCategoriasMarketplace()
    {
        $sql = "SELECT * FROM pla WHERE id_linea = $id AND id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function mostrarVariedades($id_producto)
    {
        // echo $id_producto;
        $sql = "select * from inventario_bodegas ib, productos p, variedades v, atributos a, bodega b where ib.id_producto=$id_producto and ib.id_producto=p.id_producto and ib.id_variante=v.id_variedad and v.id_atributo=a.id_atributo and ib.bodega=b.id";

        return $this->select($sql);
    }

    public function consultarMaximo($id_producto)
    {

        $sql = "SELECT sku FROM inventario_bodegas WHERE id_producto = $id_producto ORDER BY  CASE  WHEN sku LIKE '%-%' THEN CAST(SUBSTRING_INDEX(sku, '-', -1) AS UNSIGNED)
        ELSE 0 END DESC, sku DESC LIMIT 1;";



        $id_producto = $this->select($sql);
        $codigo_producto = $id_producto[0]['sku'];

        $codigo_nuevo = $this->aumentarCodigo($codigo_producto);
        echo $codigo_nuevo;
        //return $codigo_nuevo;
    }

    function aumentarCodigo($codigo)
    {
        // Verificar si el código contiene un guion
        //echo $codigo.'ad';
        if (strpos($codigo, '-') !== false) {
            // Si contiene un guion, extraer la parte numérica después del guion
            $partes = explode('-', $codigo);
            $numero = intval(end($partes)) + 1;
            // Unir las partes con el nuevo número
            array_pop($partes);
            $nuevoCodigo = implode('-', $partes) . '-' . $numero;
        } else {
            // Si no contiene un guion, agregar -1 al final
            $nuevoCodigo = $codigo . '-1';
        }
        //echo $nuevoCodigo;
        return $nuevoCodigo;
    }

    public function importacion_masiva($plataforma)
    {
        //print_r($plataforma);
    }


    public function agregarDestacado($id_producto_tienda, $destacado)
    {
        $response = $this->initialResponse();

        $sql = "UPDATE `productos_tienda` SET `destacado_tienda` = ? WHERE `id_producto_tienda` = ?";
        $data = [$destacado, $id_producto_tienda];
        $editar_plataforma = $this->update($sql, $data);
        //print_r($editar_producto);
        if ($editar_plataforma == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria editada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            // $response['message'] = $editar_producto['message'];
        }
        return $response;
    }
    public function verificarProducto($id)
    {
        $sql = "SELECT * FROM `productos` WHERE id_producto = $id";
        $response =  $this->select($sql);
        if (empty($response)) {
            return 0;
        } else {
            return 1;
        }
    }

    public function verificarProductoTienda($id)
    {

        $sql = "SELECT * FROM `productos_tienda` WHERE id_producto_tienda = $id";
        // echo $sql;
        $response =  $this->select($sql);
        if (empty($response)) {
            return 0;
        } else {
            return 1;
        }
    }

    public function existeLanding($id)
    {
        $sql = "SELECT * FROM `landing` WHERE id_producto = $id";
        $response =  $this->select($sql);
        if (empty($response)) {
            return 0;
        } else {
            return 1;
        }
    }

    public function existeLandingTienda($id)
    {

        $sql = "SELECT * FROM `productos_tienda` WHERE id_producto_tienda = $id";
        //echo $sql;
        $landing = $this->select($sql);
        $landing = $landing[0]['landing_tienda'];

        if ($landing == '') {
            return 0;
        } else {
            return 1;
        }
    }
    public function existeLandingTienda2($id)
    {

        $sql = "SELECT * FROM `productos_tienda` WHERE id_producto_tienda = $id and landing_propia=1";
        $response =  $this->select($sql);
        if (empty($response)) {
            return 0;
        } else {
            return 1;
        }
    }

    public function habilitarPrivado($id, $estado)
    {

        $sql = "UPDATE `productos` SET  `producto_privado` = ? WHERE `id_producto` = ? ";
        $data = [$estado, $id];
        $editar_producto = $this->update($sql, $data);
        //print_r($editar_producto);
        if ($editar_producto == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Habilitado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            // $response['message'] = $editar_producto['message'];
        }
        return $response;
    }

    public function agregarPrivadoPlataforma($id, $plataforma)
    {

        $sql = "INSERT INTO producto_privado (id_producto, id_plataforma) VALUES (?, ?)";
        $data = [$id, $plataforma];
        $insertar_caracteristica = $this->insert($sql, $data);
        //print_r($editar_producto);
        if ($insertar_caracteristica == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto agregado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            // $response['message'] = $editar_producto['message'];
        }
        return $response;
    }

    public function obtener_productosPrivados_tienda($plataforma)
    {
        $sql = "SELECT * FROM `productos` WHERE producto_privado=1 AND id_plataforma=$plataforma;";
        return $this->select($sql);
    }

    public function obtener_tiendas_productosPrivados($id)
    {
        $sql = "SELECT * FROM plataformas INNER JOIN producto_privado ON plataformas.id_plataforma = producto_privado.id_plataforma AND producto_privado.id_producto = $id;";
        return $this->select($sql);
    }

    public function eliminarPrivadoPlataforma($id)
    {
        $response = $this->initialResponse();
        $sql = "DELETE FROM producto_privado WHERE id_producto_privado = ?";
        $data = [$id];
        $eliminar_producto = $this->delete($sql, $data);
        if ($eliminar_producto == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $eliminar_producto['message'];
        }
        return $response;
    }

    public function obtener_combos($plataforma)
    {
        $sql = "SELECT *, (SELECT nombre_producto FROM `productos` WHERE id_producto = combos.id_producto_combo) as nombre_producto FROM `combos` WHERE id_plataforma=$plataforma;";
        return $this->select($sql);
    }

    public function obtener_combo_id($id)
    {
        $sql = "SELECT * FROM `combos` WHERE id=$id;";
        return $this->select($sql);
    }

    public function agregarcombos($nombre, $id_producto_combo, $imagen, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/combos/";
        $target_file = $target_dir . basename($imagen["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($imagen["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo no es una imagen';
            $uploadOk = 0;
        }
        if ($imagen["size"] > 500000) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo es muy grande';
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Solo se permiten archivos JPG, JPEG, PNG';
            $uploadOk = 0;
        } else {
            if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida correctamente';
                $response['data'] = $target_file;

                $sql = "INSERT INTO `combos` (`id_plataforma`,`nombre`,`id_producto_combo`,`image_path`) VALUES (?, ?, ?, ?)";
                $data = [$plataforma, $nombre, $id_producto_combo, $target_file];
                $insertar_testimonio = $this->insert($sql, $data);

                if ($insertar_testimonio == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Imagen subida correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = $insertar_testimonio["message"];
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
            }
        }
        return $response;
    }

    public function editarcombos($nombre, $id_producto_combo, $imagen, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/combos/";
        $target_file = $target_dir . basename($imagen["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($imagen["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo no es una imagen';
            $uploadOk = 0;
        }
        if ($imagen["size"] > 500000) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo es muy grande';
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Solo se permiten archivos JPG, JPEG, PNG';
            $uploadOk = 0;
        } else {
            if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida correctamente';
                $response['data'] = $target_file;

                $sql = "UPDATE lineas SET nombre_linea = ?, descripcion_linea = ?, estado_linea = ?, date_added = ?, online = ?, tipo = ?, padre = ?, orden = ? WHERE id_linea = ? AND id_plataforma = ?";
                $data = [$nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online,  $tipo, $padre, $orden, $id, $plataforma];
                $editar_categoria = $this->update($sql, $data);

                if ($editar_categoria == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Imagen subida correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = $editar_categoria["message"];
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
            }
        }
        return $response;
    }
}
