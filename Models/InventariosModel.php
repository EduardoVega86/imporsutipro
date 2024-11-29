<?php
class InventariosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    ///productos

    public function obtener_bodegas($plataforma)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_plataforma in ('0','$plataforma') ";
        return $this->select($sql);
    }

    public function agregarStock($inventario, $cantidad, $plataforma, $sku,  $referencia,  $id_producto, $id_bodega)
    {
        $response = $this->initialResponse();

        $sql_id = "SELECT saldo_stock FROM inventario_bodegas WHERE id_inventario = $inventario";
        $stock = $this->select($sql_id);
        $stock_inventario = $stock[0]['saldo_stock'];
        $saldo_stock = $stock_inventario + $cantidad;
        $sql_update = "update inventario_bodegas set saldo_stock=? where id_inventario=?";
        $data = [$saldo_stock, $inventario];
        $actualizar_stock = $this->update($sql_update, $data);



        if ($actualizar_stock == 1) {
            $id_usuario = $_SESSION['id'];
            $nota = "Se agrego $cantidad productos(s) al inventario";
            $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`, `saldo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [$id_usuario, $inventario, $plataforma, $sku,  $nota, $referencia, $cantidad, 1, $id_bodega, $id_producto, $saldo_stock];
            $insertar_historial = $this->insert($sql, $data);
            //print_r($insertar_historial);
            if ($insertar_historial == 1) {
                // echo 'entrra';
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Producto agregado correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al agregar el historial';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error stock';
        }
        return $response;
    }

    public function eliminarStock($inventario, $cantidad, $plataforma, $sku,  $referencia,  $id_producto, $id_bodega)
    {
        $response = $this->initialResponse();
        $sql_id = "SELECT saldo_stock FROM inventario_bodegas WHERE id_inventario = $inventario";
        $stock = $this->select($sql_id);
        $stock_inventario = $stock[0]['saldo_stock'];
        $saldo_stock = $stock_inventario - $cantidad;
        $sql_update = "update inventario_bodegas set saldo_stock=? where id_inventario=?";
        $data = [$saldo_stock, $inventario];
        $actualizar_stock = $this->update($sql_update, $data);

        if ($actualizar_stock == 1) {
            $id_usuario = $_SESSION['id'];
            $nota = "Se eliminó $cantidad productos(s) al inventario";
            $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`, `saldo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [$id_usuario, $inventario, $plataforma, $sku,  $nota, $referencia, $cantidad, 2, $id_bodega, $id_producto, $saldo_stock];
            $insertar_historial = $this->insert($sql, $data);
            // echo 'asd';
            //  print_r($insertar_historial);

            if ($insertar_historial == 1) {
                //  echo 'asd';
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Producto agregado correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al agregar el historial';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error stock';
        }

        return $response;
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



    public function obtener_productos_bodegas($id_bodega, $plataforma)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_bodega = $id_bodega and id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function obtenerInventario($id_inventario)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_inventario ";
        return $this->select($sql);
    }
    public function obtenerHistorial($id_inventario)
    {
        $sql = "SELECT * FROM historial_productos WHERE id_inventario = $id_inventario order by id_historial desc";
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
            $response['message'] = 'Error al agregar la categoria';
        }
        return $response;
    }






    public function listarCategoria($id, $plataforma)
    {
        $sql = "SELECT * FROM lineas WHERE id_linea = $id AND id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function guardar_imagen_categorias($imagen, $id_categoria, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/categorias";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo no es una imagen';
            $uploadOk = 0;
        }
        if ($_FILES["file"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
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

    public function listarProductoListaCompras($guias, $plataforma)
    {

        $sql = "SELECT  id_producto, (select nombre_producto from productos where id_producto=1360) as nombre, count(id_detalle) FROM `detalle_fact_cot` WHERE id_factura in (122,121) group by id_producto, sku";
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

    public function despacho_guia($num_guia, $plataforma)
    {

        $response = $this->initialResponse();

        $sql_factura = "SELECT * FROM facturas_cot WHERE numero_guia = '$num_guia'";
        //  echo $sql_factura;
        $factura = $this->select($sql_factura);
        $id_factura = $factura[0]['id_factura'];
        $estado_factura = $factura[0]['estado_factura'];

        $sql_plataforma_bodega = "SELECT b.id_plataforma FROM `detalle_fact_cot` dfc, inventario_bodegas  ib, bodega b where ib.bodega=b.id and id_factura=$id_factura and dfc.id_inventario=ib.id_inventario GROUP by bodega";
        //  echo $sql_factura;$id_factura
        $plataforma_bodega = $this->select($sql_plataforma_bodega);
        $id_plataforma_bodega = $plataforma_bodega[0]['id_plataforma'];

        if ($id_plataforma_bodega == $plataforma) {
            if ($estado_factura == 1) {
                //  echo $id_factura;

                $tmp_cotizaciones = $this->select("SELECT * FROM detalle_fact_cot WHERE id_factura = $id_factura");
                $detalle_sql_despacho = "INSERT INTO `historial_depacho` (`id_pedido`, `guia`, `id_producto`, `sku`, `cantidad`, `id_usuario`, `id_plataforma`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                // $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $detalle_sql_historial = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`, `saldo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                //print_r($tmp_cotizaciones);
                //$nota='Se descuenta'
                $id_usuario = $_SESSION['id'];
                foreach ($tmp_cotizaciones as $tmp) {
                    //  echo 'enta';
                    $despacho_data = array(
                        $id_factura,
                        $num_guia,
                        $tmp['id_producto'],
                        $tmp['sku'],
                        $tmp['cantidad'],
                        $id_usuario,
                        $plataforma
                    );
                    $guardar_detalle = $this->insert($detalle_sql_despacho, $despacho_data);
                    $nota = 'Se elimina ' . $tmp['cantidad'] . ' productos(s) del inventario -DESPACHO GUIA-';
                    $id_inventario = $tmp['id_inventario'];
                    $sql_bodega = "SELECT bodega FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                    //echo $sql_bodega;
                    $bodega = $this->select($sql_bodega);
                    //print_r($bodega);
                    $id_bodega = $bodega[0]['bodega'];

                      $sql_id = "SELECT saldo_stock FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                    $stock = $this->select($sql_id);
                    $stock_inventario = $stock[0]['saldo_stock'];
                    $saldo_stock = $stock_inventario - $tmp['cantidad'];
                    $sql_update = "update inventario_bodegas set saldo_stock=? where id_inventario=?";
                    $data = [$saldo_stock, $id_inventario];
                    $actualizar_stock = $this->update($sql_update, $data);
                    
                    $historial_data = array(
                        $id_usuario,
                        $tmp['id_inventario'],
                        $plataforma,
                        $tmp['sku'],
                        $nota,
                        $num_guia,
                        $tmp['cantidad'],
                        2,
                        $id_bodega,
                        $tmp['id_producto'],
                        $saldo_stock
                    );
                    $guardar_detalle = $this->insert($detalle_sql_historial, $historial_data);

                  


                    //print_r($guardar_detalle);
                }

                $sql = "UPDATE `facturas_cot` SET `estado_factura` = ? WHERE `id_factura` = ?";
                $data = [2, $id_factura];
                $editar_categoria = $this->update($sql, $data);

                //print_r($tmp_cotizaciones);

                if ($editar_categoria == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Despacho Exitoso';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al generar el despacho';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Esta guia ya ha sido despachada';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'La guía no pertenece a esta bodega o a esta transportadora';
        }
        return $response;
    }

    public function despacho_guia2($num_guia, $plataforma, $transportadora, $bodega)
    {

        $response = $this->initialResponse();

        $sql_factura = "SELECT * FROM facturas_cot WHERE numero_guia = '$num_guia' and id_transporte=$transportadora and id_bodega in (select id from bodega WHERE id_plataforma=$plataforma) ";
        //echo $sql_factura;
        //echo $sql_factura;
        $factura = $this->select($sql_factura);
        if (count($factura) > 0) {
            $id_factura = $factura[0]['id_factura'];
            $estado_factura = $factura[0]['estado_factura'];

            $sql_plataforma_bodega = "SELECT b.id_plataforma FROM `detalle_fact_cot` dfc, inventario_bodegas  ib, bodega b where ib.bodega=b.id and id_factura=$id_factura and dfc.id_inventario=ib.id_inventario GROUP by bodega";
         // echo $sql_plataforma_bodega;
            //echo $sql_factura;$id_factura
            $plataforma_bodega = $this->select($sql_plataforma_bodega);
            $id_plataforma_bodega = $plataforma_bodega[0]['id_plataforma'];


            //echo $plataforma;
            if ($id_plataforma_bodega == $plataforma) {
                if ($estado_factura == 1) {
                    //  echo $id_factura;
                    
                    //print_r($tmp_cotizaciones);

                    if ($estado_factura == 1) {
                        $response['status'] = 200;
                        $response['title'] = 'Peticion exitosa';
                        $response['message'] = 'Despacho Exitoso';
                    } else {
                        $response['status'] = 500;
                        $response['title'] = 'Error';
                        $response['message'] = 'Error al generar el despacho';
                    }
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Esta guia ya ha sido despachada';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'La guía no pertenece a esta bodega';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'La guía no pertenece a esta bodega';
        }
        return $response;
    }

    public function devolucion_guia($num_guia, $plataforma)
    {

        $response = $this->initialResponse();

        $sql_factura = "SELECT * FROM facturas_cot WHERE numero_guia = '$num_guia' ";
        // echo $sql_factura;
        $factura = $this->select($sql_factura);
        
           
        if (count($factura) > 0) {
            
        
        $id_factura = $factura[0]['id_factura'];
        $estado_factura = $factura[0]['estado_factura'];

        $sql_plataforma_bodega = "SELECT b.id_plataforma FROM `detalle_fact_cot` dfc, inventario_bodegas  ib, bodega b where ib.bodega=b.id and id_factura=$id_factura and dfc.id_inventario=ib.id_inventario GROUP by bodega";
        //  echo $sql_factura;$id_factura
        $plataforma_bodega = $this->select($sql_plataforma_bodega);
        $id_plataforma_bodega = $plataforma_bodega[0]['id_plataforma'];

        if ($id_plataforma_bodega == $plataforma) {
            if ($estado_factura == 2) {
                //  echo $id_factura;

                $tmp_cotizaciones = $this->select("SELECT * FROM detalle_fact_cot WHERE id_factura = $id_factura");
                $detalle_sql_despacho = "INSERT INTO `historial_depacho` (`id_pedido`, `guia`, `id_producto`, `sku`, `cantidad`, `id_usuario`) VALUES (?, ?, ?, ?, ?, ?)";
                // $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $detalle_sql_historial = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`, `saldo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                //print_r($tmp_cotizaciones);
                //$nota='Se descuenta'
                $id_usuario = $_SESSION['id'];
                foreach ($tmp_cotizaciones as $tmp) {
                    //  echo 'enta';
                    $despacho_data = array(
                        $id_factura,
                        $num_guia,
                        $tmp['id_producto'],
                        $tmp['sku'],
                        $tmp['cantidad'],
                        $id_usuario
                    );
                    $guardar_detalle = $this->insert($detalle_sql_despacho, $despacho_data);
                    $nota = 'Se agrega ' . $tmp['cantidad'] . ' productos(s) del inventario -DEVOLUCION-';
                    $id_inventario = $tmp['id_inventario'];
                    $sql_bodega = "SELECT bodega FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                    //echo $sql_bodega;
                    $bodega = $this->select($sql_bodega);
                    //print_r($bodega);
                    $id_bodega = $bodega[0]['bodega'];

                    $sql_id = "SELECT saldo_stock FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                    $stock = $this->select($sql_id);
                    $stock_inventario = $stock[0]['saldo_stock'];
                    $saldo_stock = $stock_inventario + $tmp['cantidad'];
                    $sql_update = "update inventario_bodegas set saldo_stock=? where id_inventario=?";
                    $data = [$saldo_stock, $id_inventario];
                    $actualizar_stock = $this->update($sql_update, $data);
                    
                    
                    $historial_data = array(
                        $id_usuario,
                        $tmp['id_inventario'],
                        $plataforma,
                        $tmp['sku'],
                        $nota,
                        $num_guia,
                        $tmp['cantidad'],
                        1,
                        $id_bodega,
                        $tmp['id_producto'],
                        $saldo_stock
                    );
                    $guardar_detalle = $this->insert($detalle_sql_historial, $historial_data);

                    


                    //print_r($guardar_detalle);
                }

                $sql = "UPDATE `facturas_cot` SET `estado_factura` = ? WHERE `id_factura` = ?";
                $data = [3, $id_factura];
                $editar_categoria = $this->update($sql, $data);

                //print_r($tmp_cotizaciones);

                if ($editar_categoria == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Devolución Exitosa';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al generar la devolucion';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Esta guia ya ha sido devuelta';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'La guía no pertenece a esta bodega';
        }
        }else{
           $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'No se encuentra la guía en el sistema';  
        }
        return $response;
    }

    public function devolucion_guia_despacho($num_guia, $plataforma)
    {

        $response = $this->initialResponse();

        $sql_factura = "SELECT * FROM facturas_cot WHERE numero_guia = '$num_guia' ";
        //  echo $sql_factura;
        $factura = $this->select($sql_factura);
        $id_factura = $factura[0]['id_factura'];
        $estado_factura = $factura[0]['estado_factura'];
        if ($estado_factura == 2) {
            //  echo $id_factura;

            $tmp_cotizaciones = $this->select("SELECT * FROM detalle_fact_cot WHERE id_factura = $id_factura");
            $detalle_sql_despacho = "INSERT INTO `historial_depacho` (`id_pedido`, `guia`, `id_producto`, `sku`, `cantidad`, `id_usuario`) VALUES (?, ?, ?, ?, ?, ?)";
            // $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $detalle_sql_historial = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`, `saldo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            //print_r($tmp_cotizaciones);
            //$nota='Se descuenta'
            $id_usuario = $_SESSION['id'];
            foreach ($tmp_cotizaciones as $tmp) {
                //  echo 'enta';
                $despacho_data = array(
                    $id_factura,
                    $num_guia,
                    $tmp['id_producto'],
                    $tmp['sku'],
                    $tmp['cantidad'],
                    $id_usuario
                );
                $guardar_detalle = $this->insert($detalle_sql_despacho, $despacho_data);
                $nota = 'Se agrega ' . $tmp['cantidad'] . ' productos(s) del inventario -DEVOLUCION EN DESPACHO-';
                $id_inventario = $tmp['id_inventario'];
                $sql_bodega = "SELECT bodega FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                //echo $sql_bodega;
                $bodega = $this->select($sql_bodega);
                //print_r($bodega);
                $id_bodega = $bodega[0]['bodega'];

                
                $sql_id = "SELECT saldo_stock FROM inventario_bodegas WHERE id_inventario = $id_inventario";
                $stock = $this->select($sql_id);
                $stock_inventario = $stock[0]['saldo_stock'];
                $saldo_stock = $stock_inventario + $tmp['cantidad'];
                $sql_update = "update inventario_bodegas set saldo_stock=? where id_inventario=?";
                $data = [$saldo_stock, $id_inventario];
                $actualizar_stock = $this->update($sql_update, $data);
                
                $historial_data = array(
                    $id_usuario,
                    $tmp['id_inventario'],
                    $plataforma,
                    $tmp['sku'],
                    $nota,
                    $num_guia,
                    $tmp['cantidad'],
                    1,
                    $id_bodega,
                    $tmp['id_producto'],
                    $saldo_stock
                );
                $guardar_detalle = $this->insert($detalle_sql_historial, $historial_data);

                


                //print_r($guardar_detalle);
            }

            $sql = "UPDATE `facturas_cot` SET `estado_factura` = ? WHERE `id_factura` = ?";
            $data = [1, $id_factura];
            $editar_categoria = $this->update($sql, $data);

            //print_r($tmp_cotizaciones);

            if ($editar_categoria == 1) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Devolucion Exitosa';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al generar la devolucion';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Esta guia ya ha sido devuelta';
        }
        return $response;
    }

    public function listar_despachos($num_guia, $plataforma)
    {
    }
}
