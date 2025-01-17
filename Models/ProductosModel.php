<?php
require_once 'Class/ImageUploader.php';

use Google\Service\Docs\Response;

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

    public function obtenerProductosTodos()
    {
        $sql = "SELECT id_producto, nombre_producto FROM `productos`";
        $response = $this->select($sql);
        return [
            'status'  => 200,
            'message' => 'Productos obtenidos exitosamente',
            'data'    => $response
        ];
    }


    // public function obtenerBovedas()
    // {
    //     $sql = "SELECT * FROM bovedas";

    //     return $this->select($sql);
    // }

    //Inner join que obtiene el nombre de id plataforma y id_linea de forma correcta para mostrar en tabla bovedas

    public function obtenerBovedas()
    {
        $sql = "
            SELECT 
                productos.nombre_producto AS nombre,
                bovedas.referencia,
                lineas.nombre_linea AS categoria,
                plataformas.nombre_tienda AS proveedor,
                bovedas.img,
                bovedas.ejemplo_landing,
                bovedas.duplicar_funnel,
                bovedas.id_boveda,
                bovedas.videos,
                bovedas.fecha_create_at
            FROM 
                bovedas
            INNER JOIN 
                productos ON bovedas.id_producto = productos.id_producto    
            INNER JOIN 
                lineas ON bovedas.id_linea = lineas.id_linea
            INNER JOIN 
                plataformas ON bovedas.id_plataforma = plataformas.id_plataforma";

        $response = $this->select($sql);
        return [
            'status'  => 200,
            'message' => 'Productos obtenidos exitosamente',
            'data'    => $response
        ];
    }

    public function obtenerBoveda($id)
    {
        /* var_dump($id); */
        $sql = "SELECT * FROM bovedas WHERE id_boveda= $id";
        $response = $this->select($sql);
        return [
            'status' => 200,
            'message' => 'Boveda obtenida exitosamente',
            'data' => $response
        ];
    }

    public function editarBoveda($id_boveda, $id_linea, $id_plataforma, $id_producto, $imagen, $ejemplo_landing, $duplicar_funnel, $videos)
    {
        $response = $this->initialResponse();

        if ($imagen !== null) {
            // Instanciar ImageUploader con el directorio de destino
            $uploader = new ImageUploader("public/img/boveda/");
            $uploadResponse = $uploader->uploadImage($imagen);

            if ($uploadResponse['status'] == 200) {
                $target_file = $uploadResponse['data'];
                // Actualizar en la base de datos
                $sql = "UPDATE `bovedas` SET `id_producto` = ?, `id_linea` = ?, `id_plataforma` = ?, `ejemplo_landing` = ?, `duplicar_funnel` = ?, `videos` = ?, `img` = ? WHERE `id_boveda` = ? ";
                $data = [$id_producto, $id_linea, $id_plataforma, $ejemplo_landing, $duplicar_funnel, $videos, $target_file, $id_boveda];
                $actualizar_boveda = $this->update($sql, $data);
                if ($actualizar_boveda == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Petición exitosa';
                    $response['message'] = 'Bóveda actualizada correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al actualizar la bóveda';
                }
            } else {
                // Error al subir la imagen
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = $uploadResponse['message'] ?? "Error al subir la imagen.";
                error_log("Error al subir la imagen: " . $uploadResponse['message']);
            }
        } else {
            // Actualizar en la base de datos
            $sql = "UPDATE `bovedas` SET `id_producto` = ?, `id_linea` = ?, `id_plataforma` = ?, `ejemplo_landing` = ?, `duplicar_funnel` = ?, `videos` = ? WHERE `id_boveda` = ? ";
            $data = [$id_producto, $id_linea, $id_plataforma, $ejemplo_landing, $duplicar_funnel, $videos, $id_boveda];
            $actualizar_boveda = $this->update($sql, $data);
            if ($actualizar_boveda == 1) {
                $response['status'] = 200;
                $response['title'] = 'Petición exitosa';
                $response['message'] = 'Bóveda actualizada correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al actualizar la bóveda';
            }
        }


        return $response;
    }



    public function obtener_productos_boveda($id_plataforma)
    {
        $sql = "SELECT *
                FROM productos
                WHERE id_plataforma = '$id_plataforma'";
        return $this->select($sql);
    }

    //Funcion para ser accedida desde Swagger Model
    public function getBovedasPorPlataforma($id_plataforma)
    {
        $sql = "SELECT * FROM bovedas WHERE id_plataforma = '$id_plataforma'";
        return $this->select($sql);
    }



    public function obtenerProveedores()
    {
        $sql = "SELECT nombre_tienda, id_plataforma
                FROM plataformas
                WHERE proveedor = 1";
        $response = $this->select($sql);

        return [
            'status' => 200,
            'message' => 'Proveedores obtenidos exitosamente',
            'data' => $response

        ];
    }


    public function obtenerLineasGlobal()
    {
        $sql = "SELECT id_linea, nombre_linea FROM lineas WHERE global=1";
        return $this->select($sql);
    }

    public function getLineasPorPlataforma($id_plataforma)
    {
        $sql = "SELECT * FROM lineas WHERE id_plataforma = '$id_plataforma'";
        return $this->select($sql);
    }


    public function insertarBoveda($idProducto, $idLinea, $imagen, $idProveedor, $ejemploLanding, $duplicarFunnel, $videos)
    {
        $response = $this->initialResponse();

        if ($imagen !== null) {
            // Instanciar ImageUploader con el directorio de destino
            $uploader = new ImageUploader("public/img/boveda/");
            $uploadResponse = $uploader->uploadImage($imagen);

            if ($uploadResponse['status'] == 200) {
                $target_file = $uploadResponse['data'];
                // Insertar en la base de datos
                $sql = "INSERT INTO bovedas (id_producto, id_linea, id_plataforma, ejemplo_landing, img, duplicar_funnel, videos)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                $data = [
                    $idProducto,
                    $idLinea,
                    $idProveedor,
                    $ejemploLanding,
                    $target_file,
                    $duplicarFunnel,
                    $videos
                ];
                $insertar_boveda = $this->insert($sql, $data);
                if ($insertar_boveda == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Petición exitosa';
                    $response['message'] = 'Bóveda agregada correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al subir la bóveda';
                }
            } else {
                // Error al subir la imagen
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = $uploadResponse['message'] ?? "Error al subir la imagen.";
                error_log("Error al subir la imagen: " . $uploadResponse['message']);
            }
        } else {
            $sql = "INSERT INTO bovedas (id_producto, id_linea, id_plataforma, ejemplo_landing, duplicar_funnel, videos)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $data = [
                $idProducto,
                $idLinea,
                $idProveedor,
                $ejemploLanding,
                $duplicarFunnel,
                $videos
            ];
            $insertar_boveda = $this->insert($sql, $data);
            if ($insertar_boveda == 1) {
                $response['status'] = 200;
                $response['title'] = 'Petición exitosa';
                $response['message'] = 'Bóveda agregada correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la bóveda';
            }
        }
        return $response;
    }


    //Funcion para ser accedida desde Swagger Model
    public function getProductosPorPlataforma($id_plataforma)
    {
        // Ajusta la tabla / campos a tu realidad
        $sql = "SELECT * FROM productos WHERE id_plataforma = '$id_plataforma'";
        return $this->select($sql);
    }


    public function obtener_productos_bodega($bodega)
    {
        $sql = "SELECT * FROM productos p LEFT JOIN inventario_bodegas ib ON p.id_producto = ib.id_producto AND ib.bodega = $bodega LEFT JOIN variedades v ON ib.id_variante = v.id_variedad WHERE ib.`bodega` = $bodega;";

        return $this->select($sql);
    }

    public function obtener_productos2($plataforma, $start, $length, $search, $orderColumn, $orderDir)
    {
        $sql = "SELECT ib.*, p.*
            FROM `inventario_bodegas` AS ib
            INNER JOIN `productos` AS p ON p.`id_producto` = ib.`id_producto`
            WHERE ib.`id_plataforma` = $plataforma";

        // Aplicar búsqueda si existe
        if (!empty($search)) {
            $sql .= " AND (p.nombre_producto LIKE '%$search%' OR p.codigo_producto LIKE '%$search%')";
        }

        $sql .= " GROUP BY p.`id_producto`, ib.`id_plataforma`, ib.`bodega`";

        // Aplicar orden si existe
        if (!empty($orderColumn) && !empty($orderDir)) {
            $sql .= " ORDER BY $orderColumn $orderDir";
        }

        // Añadir paginación
        $sql .= " LIMIT $start, $length";

        $response = $this->select($sql);

        foreach ($response as $key => $value) {
            $response[$key]['checkbox'] = "-";
            $response[$key]['landing'] = '<a href="' . SERVERURL . 'productos/landing/' . $response[$key]['id_producto'] . '" role="button"><i class="fa-solid fa-laptop-code" style="font-size:25px;"></i></a>';
            $response[$key]['imagen'] = "<img src=\"$\" class=\"icon-button\" onclick=\"agregar_imagenProducto(7892, '$')\" alt=\"Agregar imagen\" width=\"50px\">";
            $response[$key]['marketplace'] = $this->marketplace($value['drogshipin'], $value['id_producto']);
            $response[$key]['enviar_cliente'] = '<i style="color:red;" class="fa-regular fa-paper-plane" onclick="abrir_modalSeleccionAtributo(1361,' . 10088 . ',30,6)"></i>';

            $response[$key]['producto_variable'] = $this->atributos($value['producto_variable'], $value['id_producto']);

            $response[$key]['acciones'] = '<button class="btn btn-primary btn-sm" onclick="editarProducto(' . $value['id_producto'] . ')">Editar</button>
            <button class="btn btn-danger btn-sm" onclick="eliminarProducto(' . $value['id_producto'] . ')">Eliminar</button>';
        }

        // Obtener el total de registros sin paginar ni filtrar
        $sqlCount = "SELECT COUNT(*) AS total FROM `inventario_bodegas` AS ib 
                 INNER JOIN `productos` AS p ON p.`id_producto` = ib.`id_producto`
                 WHERE ib.`id_plataforma` = $plataforma";
        $totalRecords = $this->select($sqlCount)[0]['total'];

        return [
            'data' => $response,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($response) // Este debería cambiar si se aplica un filtro
        ];
    }


    public function atributos($estado, $id_producto)
    {
        if ($estado == 1) {
            return '<img src="https://new.imporsuitpro.com/public/img/atributos.png" width="30px" id="buscar_traking" alt="buscar_traking" onclick="abrir_modalInventarioVariable(' . $id_producto . ')">';
        } else {
            return;
        }
    }

    public function marketplace($estado, $id_producto)
    {
        if ($estado == 1) {
            return '<div><box-icon name="cloud-download" style="cursor:pointer" color="red" id="icono_bajada_' . $id_producto . '" onclick="bajar_marketplace(' . $id_producto . ')"></box-icon> </br>Quitar</div>';
        } else {
            return '<div><box-icon name="cloud-upload" style="cursor:pointer" color="#54DD10" id="icono_subida_' . $id_producto . '" onclick="subir_marketplace(' . $id_producto . ')"></box-icon> </br>Agregar</div>';
        }
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

    public function obtener_productos_tienda_automatizador($plataforma)
    {

        if ($plataforma == 1206 /* || $plataforma == 2293 */) {
            $sql = "SELECT st.id_inventario, st.id_plataforma, p.nombre_producto as 'nombre_producto_tienda' FROM `shopify_tienda` st INNER JOIN `inventario_bodegas` ib ON ib.id_inventario = st.id_inventario INNER JOIN `productos` p ON p.id_producto = ib.id_producto where st.id_plataforma = $plataforma;";
        } else {

            $sql = "SELECT * FROM `productos_tienda` pt, productos p, inventario_bodegas ib WHERE  pt.id_producto=p.id_producto and pt.id_inventario=ib.id_inventario and pt.id_plataforma=$plataforma";
        }
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


    public function importar_productos_funnel($id_inventario, $id_funnel, $plataforma)
    {
        $response = $this->initialResponse();
        $inicial_variable = $this->select("SELECT * FROM productos_funnel WHERE id_plataforma = $plataforma AND id_producto = $id_inventario");

        if (empty($inicial_variable)) {
            $sql = "SELECT * FROM inventario_bodegas ib, productos p WHERE ib.id_inventario = $id_inventario LIMIT 1";
            $inventario = $this->select($sql);

            $detalle_sql = "INSERT INTO `productos_funnel`(`id_producto`, `id_funnel`, `sku`, `id_plataforma`) VALUES (?, ?, ?, ?)";
            $detalle_data = array(
                $inventario[0]['id_inventario'],
                $id_funnel,
                $inventario[0]['sku'],
                $plataforma
            );
            $guardar_detalle = $this->insert($detalle_sql, $detalle_data);

            $response['status'] = 200;
            $response['title'] = 'Petición exitosa';
            $response['message'] = 'Producto agregado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El producto ya existe en su configuracion.';
        }

        return $response;
    }




    public function importar_productos_shopify($id_producto, $plataforma)
    {
        $response = $this->initialResponse();
        // echo "SELECT * FROM shopify_tienda WHERE id_plataforma = $plataforma AND id_inventario = $id_producto";
        $inicial_variable = $this->select("SELECT * FROM shopify_tienda WHERE id_plataforma = $plataforma AND id_inventario = $id_producto");
        //print_r($inicial_variable);
        if (empty($inicial_variable)) {
            //$inventario = $this->select("SELECT * FROM inventario_bodegas ib, productos p WHERE p.id_producto = $id_producto AND ib.id_producto = p.id_producto");
            //echo 'entr';
            $detalle_sql = "INSERT INTO shopify_tienda (id_plataforma, id_inventario) 
                        VALUES (?, ?)";

            $detalle_data = array(
                $plataforma,
                $id_producto
            );
            $guardar_detalle = $this->insert($detalle_sql, $detalle_data);
            //print_r($guardar_detalle);
            if ($guardar_detalle == 1) {
                // Obtener el último ID insertado en productos_tienda


                // Imprimir el último ID
                //echo "El último ID de producto en la tienda es: " . $ultimo_id_producto_tienda;

                // Obtener las imágenes adicionales del producto

                $response['status'] = 200;
                $response['title'] = 'Petición exitosa';
                $response['message'] = 'Producto y sus imágenes adicionales agregados correctamente';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El producto ya existe en su configuracion.';
        }

        return $response;
    }


    public function obtener_productos_inventario($plataforma)
    {
        $sql = "SELECT * FROM productos p LEFT JOIN inventario_bodegas ib ON p.id_producto = ib.id_producto AND ib.id_plataforma = $plataforma LEFT JOIN variedades v ON ib.id_variante = v.id_variedad WHERE ib.id_plataforma = $plataforma";
        //  echo $sql;
        return $this->select($sql);
    }

    public function obtener_productos_shopify($plataforma)
    {
        $sql = "SELECT st.id_inventario, (select id_producto from inventario_bodegas WHERE id_inventario=st.id_inventario) as id_producto,  (SELect nombre_producto FROM productos WHERE id_producto=(select id_producto from inventario_bodegas WHERE id_inventario=st.id_inventario)) as nombre_producto,  (SELect image_path FROM productos WHERE id_producto=(select id_producto from inventario_bodegas WHERE id_inventario=st.id_inventario)) as image_path, (select pvp from inventario_bodegas WHERE id_inventario=st.id_inventario) as pvp  FROM shopify_tienda st  WHERE id_plataforma = $plataforma; ";
        //echo $sql;
        return $this->select($sql);
    }


    public function agregarProducto($codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $plataforma, $stock_inicial, $bodega, $pcp, $pvp, $pref, $enlace_funnelish, $envio_prioritario)
    {
        $response = $this->initialResponse();
        //  echo $descripcion_producto;
        //echo '------';

        // $descripcion_producto = "<p><strong>2 EN 1 x 100 CAPSULAS</strong></p><p>- Mejora la atención y la memoria.</p><p>- Mejora el rendimiento cerebral.</p><p>- Reduce la capacidad de concentración.</p><p>- Mejora la función cognitiva.</p>";
        // echo $descripcion_producto;
        $sql = "INSERT INTO productos (codigo_producto, nombre_producto, descripcion_producto, id_linea_producto, inv_producto, producto_variable, costo_producto, aplica_iva, estado_producto, date_added, image_path, id_imp_producto, pagina_web, formato, drogshipin, destacado, id_plataforma, funellish) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $plataforma, $enlace_funnelish];
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
                $sql = "INSERT INTO inventario_bodegas (sku, id_producto, id_variante, bodega, pcp, pvp, pref, stock_inicial, saldo_stock, id_plataforma, envio_prioritario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $data = [$codigo_producto, $id_producto, 0, $bodega, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $plataforma, $envio_prioritario];
            } else {
                // echo 'sin invetario';
                $sql = "INSERT INTO inventario_bodegas (sku, id_producto, id_variante, bodega, pcp, pvp, pref, stock_inicial, saldo_stock, id_plataforma, envio_prioritario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $data = [$codigo_producto, $id_producto, $producto_variable, 50000, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $plataforma, $envio_prioritario];
            }
            $insertar_producto = $this->insert($sql, $data);
            // print_r($insertar_producto);
        } else {
            //bodega inicial
            $sql_bodega = "SELECT * FROM bodega WHERE id_plataforma = $plataforma limit 1";
            $bodega = $this->select($sql_bodega);
            $bodega = $bodega[0]['id'];
            $sql_insert = "INSERT INTO inventario_bodegas (sku, id_producto, id_variante, bodega, pcp, pvp, pref, stock_inicial, saldo_stock, id_plataforma, envio_prioritario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data_insert = [$codigo_producto, $id_producto, 0, $bodega, $pcp, $pvp, $pref, 0, 0, $plataforma, $envio_prioritario];
            $insertar_producto = $this->insert($sql_insert, $data_insert);
        }
        // print_r($insertar_producto);
        if ($insertar_producto == 1) {
            //echo $codigo_producto;
            $id_usuario = $_SESSION['id'];
            $id_inventario = $this->buscar_inventario($id_producto, $codigo_producto);
            $referencia = 'STOCK INICIAL';
            $nota = "Se agrego $stock_inicial productos(s) al inventario";
            $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`, `saldo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [$id_usuario, $id_inventario, $plataforma, $codigo_producto,  $nota, $referencia, $stock_inicial, 1, $bodega, $id_producto, $stock_inicial];
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


    public function editarProductoTienda($id_producto_tienda, $nombre, $pvp_tienda, $id_categoria, $pref, $id_plataforma, $aplica_funnelish, $funnelish)
    {
        $response = $this->initialResponse();
        $sql = "UPDATE `productos_tienda` SET `nombre_producto_tienda`=?,"
            . "`pvp_tienda`=?,`id_categoria_tienda`=?,"
            . "`pref_tienda`=? , `funnelish`=?, `funnelish_url`=? WHERE id_producto_tienda=?";
        // echo $sql;
        $data = [$nombre, $pvp_tienda, $id_categoria,  $pref, $aplica_funnelish, $funnelish, $id_producto_tienda];
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

        $sql = "SELECT * FROM productos_tienda WHERE id_producto_tienda=$id_producto_tienda";
        $producto_tienda = $this->select($sql);
        $sku = $producto_tienda[0]['sku'];
        $id_producto = $producto_tienda[0]['id_producto'];

        //buscar si existe producto 
        $sql_tienda = "SELECT * FROM productos WHERE id_producto=$id_producto and id_plataforma = $id_plataforma";
        $producto_tienda = $this->select($sql_tienda);
        if (!empty($producto_tienda)) {
            $sql_tienda = "UPDATE productos SET nombre_producto = ? WHERE id_producto = ? AND id_plataforma = ?";
            $data_tienda = [$nombre, $id_producto_tienda, $id_plataforma];
            $editar_producto_ = $this->update($sql_tienda, $data_tienda);
            $sku = $producto_tienda[0]['codigo_producto'];
            $id_producto = $producto_tienda[0]['id_producto'];
            $id_inventario = $this->buscar_inventario($id_producto, $sku);

            if (!empty($id_inventario)) {
                $sql = "UPDATE inventario_bodegas SET pvp = ?, pref = ? WHERE id_inventario = ?";
                $data = [$pvp_tienda, $pref, $id_inventario];
                $editar_producto = $this->update($sql, $data);
            }
        }



        return $response;
    }


    public function editarProducto($id, $codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $plataforma, $stock_inicial, $bodega, $pcp, $pvp, $pref, $envio_prioritario)
    {
        $response = $this->initialResponse();
        $sql = "UPDATE productos SET codigo_producto = ?, nombre_producto = ?, descripcion_producto = ?, id_linea_producto = ?, inv_producto = ?, producto_variable = ?, costo_producto = ?, aplica_iva = ?, estado_producto = ?, date_added = ?, id_imp_producto = ?, pagina_web = ?, formato = ?, drogshipin = ?, destacado = ? WHERE id_producto = ? AND id_plataforma = ?";
        $data = [$codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $id, $plataforma];
        $editar_producto = $this->update($sql, $data);

        if ($inv_producto == 1) {
            // echo 'as'.$producto_variable;
            if ($producto_variable == 0) {

                $sql = "UPDATE inventario_bodegas SET sku = ?, id_producto = ?, id_variante = ?, bodega = ?, pcp = ?, pvp = ?, pref = ?, stock_inicial = ?, saldo_stock = ? , envio_prioritario = ? WHERE id_producto = ? AND id_plataforma = ?";
                // echo 'entra';
                $data = [$codigo_producto, $id, 0, $bodega, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $envio_prioritario, $id, $plataforma];
            } else {
                //    $sql = "UPDATE inventario_bodegas SET sku = ?, id_producto = ?, id_variante = ?, bodega = ?, pcp = ?, pvp = ?, pref = ?, stock_inicial = ?, saldo_stock = ?, envio_prioritario = ? WHERE id_producto = ? AND id_plataforma = ?";
                //   $data = [$codigo_producto, $id, $producto_variable, $bodega, $pcp, $pvp, $pref, $stock_inicial, $stock_inicial, $envio_prioritario, $id, $plataforma];
            }
            $editar_producto_ = $this->update($sql, $data);
            // print_r($editar_producto_);
        } else {
            $sql_bodega = "SELECT * FROM bodega WHERE id_plataforma = $plataforma limit 1";
            $bodega = $this->select($sql_bodega);
            $bodega = $bodega[0]['id'];

            $sql_insert = "UPDATE inventario_bodegas SET sku = ?, id_producto = ?, id_variante = ?, bodega = ?, pcp = ?, pvp = ?, pref = ?, stock_inicial = ?, saldo_stock = ?, envio_prioritario = ? WHERE id_producto = ? AND id_plataforma = ?";
            $data_insert = [$codigo_producto, $id, 0, $bodega, $pcp, $pvp, $pref, 0, 0, $envio_prioritario, $id, $plataforma];
            $insertar_producto_ = $this->update($sql_insert, $data_insert);
        }

        //buscar si existe producto en la tienda
        $sql_tienda = "SELECT * FROM productos_tienda WHERE id_producto=$id and id_plataforma=$plataforma";
        $producto_tienda = $this->select($sql_tienda);
        if (!empty($producto_tienda)) {
            $sql_tienda = "UPDATE productos_tienda SET nombre_producto_tienda = ?, pvp_tienda = ? WHERE id_producto = ? AND id_plataforma = ?";
            $data_tienda = [$nombre_producto, $pvp, $id, $plataforma];
            $editar_producto_ = $this->update($sql_tienda, $data_tienda);
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

    public function obtener_proveedores()
    {
        $sql = "select COUNT(id_producto), (SELECT nombre_tienda from plataformas WHERE id_plataforma=productos.id_plataforma) plataformas from productos WHERE id_plataforma in (SELECT id_plataforma FROM plataformas WHERE proveedor=1) and drogshipin=1 group by id_plataforma";
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

    public function cargarTemplates($plataforma)
    {
        $sql = "SELECT * FROM templates_chat_center WHERE id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function cargarEtiquetas($plataforma)
    {
        $sql = "SELECT * FROM etiquetas_chat_center WHERE id_plataforma = $plataforma";
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
        /* echo $sql; */
        return $this->select($sql);
    }

    public function cargarBodegas($plataforma)
    {
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];

        //        $sql = "SELECT DISTINCT b.*
        //FROM bodega b
        //JOIN plataformas p ON b.id_plataforma = p.id_plataforma
        //WHERE b.id_plataforma = $plataforma
        //   OR (b.global = 1 AND p.id_matriz = $id_matriz)";

        $sql_full = "select full_f from plataformas where id_plataforma=$plataforma";
        $bodega_full = $this->select($sql_full);
        $full_filme = $bodega_full[0]['full_f'];

        if ($full_filme == 0) {
            $sql = "SELECT DISTINCT b.*
            FROM bodega b
            JOIN plataformas p ON b.id_plataforma = p.id_plataforma
            WHERE b.id_plataforma = $plataforma";
        } else {
            $sql = "SELECT DISTINCT b.*
                FROM bodega b
                JOIN plataformas p ON b.id_plataforma = p.id_plataforma
                WHERE b.id_plataforma = $plataforma
                OR (b.global = 1 AND p.id_matriz = $id_matriz)";
        }
        /* echo $sql; */
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
            $sql = "INSERT INTO `historial_productos` (`id_users`, `id_inventario`, `id_plataforma`, `sku`, `nota_historial`, `referencia_historial`, `cantidad_historial`, `tipo_historial`, `id_bodega`, `id_producto`, `saldo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [$id_usuario, $id_inventario, $plataforma, $sku,  $nota, $referencia, $cantidad, 1, $bodega, $id_producto, $stock];
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
        $response =  $this->select($sql);
        if (empty($response)) {
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
            $landing = $response[0]['landing_tienda'];

            if ($landing == '') {
                return 0;
            } else {
                return 1;
            }
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

    public function obtener_productos_asignacionCombos($plataforma)
    {
        $sql = "SELECT *, (SELECT variedad FROM `variedades` WHERE variedades.id_variedad = inventario_bodegas.id_variante)AS variedad FROM `inventario_bodegas` INNER JOIN `productos` ON productos.id_producto = inventario_bodegas.id_producto WHERE productos.id_plataforma=$plataforma;";

        return $this->select($sql);
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
        $sql_producto = "SELECT * FROM inventario_bodegas WHERE id_producto = $id_producto_combo limit 1";
        $producto = $this->select($sql_producto);
        $bodega = $producto[0]['bodega'];

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

                $sql = "INSERT INTO `combos` (`id_plataforma`,`nombre`,`id_producto_combo`,`image_path`, `id_bodega`) VALUES (?, ?, ?, ?, ?)";
                $data = [$plataforma, $nombre, $id_producto_combo, $target_file, $bodega];
                $insertar_combo = $this->insert($sql, $data);

                if ($insertar_combo == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Imagen subida correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = "Error al agregar combo";
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
            }
        }
        return $response;
    }

    public function editarcombos($nombre, $id_producto_combo, $imagen, $id_combo)
    {
        $response = $this->initialResponse();

        // Verificar si se ha proporcionado una imagen
        if (isset($imagen) && isset($imagen['tmp_name']) && $imagen['tmp_name']) {
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
            }

            if ($uploadOk && !move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
                return $response;
            }
        } else {
            // Si no se proporciona una imagen, omitir el campo image_path en la actualización
            $target_file = null;
        }

        // Construir la consulta SQL en función de si hay imagen o no
        if ($target_file) {
            $sql = "UPDATE combos SET nombre = ?, id_producto_combo = ?, image_path = ? WHERE id = ?";
            $data = [$nombre, $id_producto_combo, $target_file, $id_combo];
        } else {
            $sql = "UPDATE combos SET nombre = ?, id_producto_combo = ? WHERE id = ?";
            $data = [$nombre, $id_producto_combo, $id_combo];
        }

        $editar_combo = $this->update($sql, $data);

        if ($editar_combo == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Combo actualizado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = "Error al actualizar el combo";
        }

        return $response;
    }

    public function editarcombo_estado($estado_combo, $valor, $id_combo)
    {
        $sql = "UPDATE combos SET estado_combo = ?, valor = ? WHERE id = ?";
        $data = [$estado_combo, $valor, $id_combo];

        $editar_combo = $this->update($sql, $data);

        if ($editar_combo == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Combo actualizado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = "Error al actualizar el combo";
        }

        return $response;
    }

    public function eliminarCombo($id)
    {
        $response = $this->initialResponse();
        $sql = "DELETE FROM combos WHERE id = ?";
        $data = [$id];
        $eliminar_combo = $this->delete($sql, $data);
        if ($eliminar_combo == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = "Error al eliminar el combo";
        }
        return $response;
    }

    public function agregar_detalle_combo($id_combo, $id_inventario, $cantidad)
    {
        $response = $this->initialResponse();

        $sql = "INSERT INTO `detalle_combo` (`id_combo`,`id_inventario`,`cantidad`) VALUES (?, ?, ?)";
        $data = [$id_combo, $id_inventario, $cantidad];
        $insertar_detalle_combo = $this->insert($sql, $data);

        if ($insertar_detalle_combo == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Imagen subida correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = "Error al agregar detalle combo";
        }
        return $response;
    }

    public function obtener_detalle_combo_id($id_combo)
    {
        $sql = "SELECT *, (SELECT variedad FROM `variedades` WHERE variedades.id_variedad = inventario_bodegas.id_variante)AS variedad 
        FROM `detalle_combo` INNER JOIN `inventario_bodegas` ON inventario_bodegas.id_inventario = detalle_combo.id_inventario 
        INNER JOIN `productos` ON productos.id_producto = inventario_bodegas.id_producto WHERE id_combo=$id_combo;";
        return $this->select($sql);
    }

    public function eliminar_detalleCombo($id)
    {
        $response = $this->initialResponse();
        $sql = "DELETE FROM detalle_combo WHERE id = ?";
        $data = [$id];
        $eliminar_combo = $this->delete($sql, $data);
        if ($eliminar_combo == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = "Error al eliminar el combo";
        }
        return $response;
    }

    /* oferta */
    public function actualizar_oferta($id_producto_tienda, $oferta)
    {
        $response = $this->initialResponse();

        $sql = "UPDATE `productos_tienda` SET `oferta` = ? WHERE `id_producto_tienda` = ?";
        $data = [$oferta, $id_producto_tienda];
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
    /* fin oferta */
}
