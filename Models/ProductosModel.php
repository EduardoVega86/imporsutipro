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
        $sql = "SELECT ib.*, p.* FROM `inventario_bodegas` AS ib INNER JOIN `productos` AS p ON p.`id_producto` = ib.`id_producto` WHERE ib.`id_plataforma` = $plataforma";
        return $this->select($sql);
    }

    public function agregarProducto($codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $plataforma, $stock_inicial, $bodega, $pcp, $pvp, $pref)
    {
        $response = $this->initialResponse();
        $sql = "INSERT INTO productos (codigo_producto, nombre_producto, descripcion_producto, id_linea_producto, inv_producto, producto_variable, costo_producto, aplica_iva, estado_producto, date_added, image_path, id_imp_producto, pagina_web, formato, drogshipin, destacado, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $plataforma];
        $insertar_producto = $this->insert($sql, $data);

        $sql_id = "SELECT MAX(id_producto) as id_producto from productos where id_plataforma = $plataforma";
        $id_producto = $this->select($sql_id);
        $id_producto = $id_producto[0]['id_producto'];
        echo $inv_producto;
        if ($inv_producto == 1) {

            if ($producto_variable === 0) {
                $sql = "INSERT INTO inventario_bodegas (sku, id_producto, id_variante, bodega, pcp, pvp, pref, stock_inicial, saldo_stock, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $data = [$codigo_producto, $id_producto, 0, $bodega, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $plataforma];
            } else {
                $sql = "INSERT INTO inventario_bodegas (sku, id_producto, id_variante, bodega, pcp, pvp, pref, stock_inicial, saldo_stock, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $data = [$codigo_producto, $id_producto, $producto_variable, $bodega, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $plataforma];
            }
            $insertar_producto_ = $this->insert($sql, $data);
            print_r($insertar_producto_);
        } else {
            //bodega inicial
            $sql_bodega = "SELECT * FROM bodega WHERE id_plataforma = $plataforma limit 1";
            $bodega = $this->select($sql_bodega);
            $bodega = $bodega[0]['id'];

            $sql_insert = "INSERT INTO inventario_bodegas (sku, id_producto, id_variante, bodega, pcp, pvp, pref, stock_inicial, saldo_stock, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data_insert = [$codigo_producto, $id_producto, 0, $bodega, $pcp, $pvp, $pref, 0, 0, $plataforma];
            $insertar_producto_ = $this->insert($sql_insert, $data_insert);
        }
        if ($insertar_producto == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto agregado correctamente';
            if ($insertar_producto_ === 1) {
                $response['message'] = 'Producto y stock agregado correctamente';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al agregar el producto';
        }

        return $response;
    }

    public function editarProducto($id, $codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $plataforma, $stock_inicial, $bodega, $pcp, $pvp, $pref)
    {
        $response = $this->initialResponse();
        $sql = "UPDATE productos SET codigo_producto = ?, nombre_producto = ?, descripcion_producto = ?, id_linea_producto = ?, inv_producto = ?, producto_variable = ?, costo_producto = ?, aplica_iva = ?, estado_producto = ?, date_added = ?, image_path = ?, id_imp_producto = ?, pagina_web = ?, formato = ?, drogshipin = ?, destacado = ? WHERE id_producto = ? AND id_plataforma = ?";
        $data = [$codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $id, $plataforma];
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
        return $response;
    }

    public function obtenerProducto($id, $plataforma)
    {
        $sql = "SELECT * FROM productos p inner join inventario_bodegas ib on p.codigo_producto = ib.sku WHERE p.id_producto = $id AND p.id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function guardar_imagen_productos($imagen, $id_producto, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/productos/";
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
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
            }
        }
        return $response;
    }

    public function obtener_productos_bodegas($id_bodega, $plataforma)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_bodega = $id_bodega and id_plataforma = $plataforma";
        return $this->select($sql);
    }

    ///categorias

    public function agregarCategoria($nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $plataforma)
    {
        // codigo para agregar categoria
        $response = $this->initialResponse();

        $sql = "INSERT INTO lineas (nombre_linea, descripcion_linea, estado_linea, date_added, online, imagen, tipo, padre, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $plataforma];
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

    public function editarCategoria($id, $nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $plataforma)
    {
        // codigo para editar categoria
        $response = $this->initialResponse();

        $sql = "UPDATE lineas SET nombre_linea = ?, descripcion_linea = ?, estado_linea = ?, date_added = ?, online = ?, imagen = ?, tipo = ?, padre = ? WHERE id_linea = ? AND id_plataforma = ?";
        $data = [$nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $id, $plataforma];
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

        $sql = "UPDATE `bodega` SET `nombre` = ?, `longitud` = ?, `latitud` = ?, `direccion` = ?, `num_casa` = ?, `referencia` = ?, `responsable` = ?, `contacto` = ?, `localidad` = ?, `provincia` = ? WHERE `id` = ? AND `id_plataforma` = ?";
        $data = [$nombre, $longitud, $latitud, $direccion, $numerocasa, $referencia, $contacto, $telefono_contacto, $ciudad, $provincia, $id, $plataforma];
        $editar_categoria = $this->update($sql, $data);
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

        $sql = "DELETE FROM bodega WHERE id_bodega = ? AND id_plataforma = ?";
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
        $sql = "SELECT * FROM bodega WHERE id_plataforma in ('$plataforma') or global = 1";
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

    public function SubirMarketplace($id, $plataforma)
    {
        $sql = "UPDATE `productos` SET  `drogshipin` = ? WHERE `id_producto` = ? AND `id_plataforma` = ?";
        $data = [1, $id, $plataforma];
        $editar_producto = $this->update($sql, $data);
        if ($editar_producto == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria editada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $editar_producto['message'];
        }
        return $response;
    }

    public function listarCategoriasMarketplace()
    {
        $sql = "SELECT * FROM pla WHERE id_linea = $id AND id_plataforma = $plataforma";
        return $this->select($sql);
    }
}
