<?php
class TiendaModel extends Query
{
    public function crearSubdominio($nombre_tienda, $plataforma)
    {
        $cpanelUrl = 'https://administracion.imporsuitpro.com:2083/';
        $cpanelUsername = 'imporsuitpro';
        $cpanelPassword = 'd.)~Y=}+*!2vVrm5';
        $rootdomain = DOMINIO;

        $repositoryUrl = "https://github.com/DesarrolloImporfactory/tienda";
        $repositoryName = "tienda";

        $verificador = array();

        // Clonar el repositorio de GitHub
        $apiUrl = $cpanelUrl . "execute/VersionControl/create";
        $postFields = [
            'type' => 'git',
            'name' => $repositoryName,
            'repository_root' => "/home/$cpanelUsername/public_html/$nombre_tienda",
            'source_repository' => json_encode([
                "branch" => "origin",
                "url" => $repositoryUrl
            ]),
            'checkout' => 1,
        ];
        $this->cpanelRequest($apiUrl, $cpanelUsername, $cpanelPassword, http_build_query($postFields));

        $apiUrl = $cpanelUrl . 'execute/SubDomain/addsubdomain?domain=' . $nombre_tienda . '&rootdomain=' . $rootdomain;
        $this->cpanelRequest($apiUrl, $cpanelUsername, $cpanelPassword);
    }

    public function agregarDominioConSubdominioExistente($dominio, $subdominio)
    {
        // Configuración de cPanel
        $cpanelUrl = 'https://administracion.imporsuitpro.com:2083/';

        $cpanelUsername = 'imporsuitpro';
        $cpanelPassword = 'd.)~Y=}+*!2vVrm5';
        $rootdomain = 'imporsuitpro.com';

        $subdominio = str_replace(".$rootdomain", '', $subdominio);

        // El subdominio y su carpeta ya existen
        $directorio = $dominio; // Carpeta ya creada con el subdominio
        $directorio = str_replace(".com", '', $directorio);

        // URL de la API para agregar el dominio utilizando la carpeta del subdominio
        $apiUrlDominio = $cpanelUrl . 'json-api/cpanel?cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Park&cpanel_jsonapi_func=park&domain=' . $dominio .'&topdomain=' . $subdominio . '&disallowdot=0' ;

        // Ejecutar solicitud para agregar el dominio
        $response = $this->cpanelRequest($apiUrlDominio, $cpanelUsername, $cpanelPassword);

        // Analizar la respuesta y devolver true o false
        if (isset($response['cpanelresult']['data'][0]['result']) && $response['cpanelresult']['data'][0]['result'] == 1) {
            $responses = array(
                'status' => 200,
                'title' => 'Peticion exitosa',
                'message' => 'Dominio agregado correctamente'
            );
            $this->agregarDominio($dominio, $subdominio);
        } else {
            $responses = array(
                'status' => 500,
                'title' => 'Error',
                'message' => 'Error al añadir el dominio: ' . $response['cpanelresult']['data'][0]['reason']
            );
        }
        return $responses;
    }

    public function agregarDominio($dominio, $subdominio)
    {
        $sql = "SELECT * FROM plataformas WHERE url_imporsuit LIKE '%$subdominio%'";
        $plataforma = $this->select($sql);
        $plataforma = $plataforma[0]['id_plataforma'];


        $sql = "UPDATE plataformas SET dominio = ? WHERE  id_plataforma = ?";
        $data = [$dominio, $plataforma];
        $this->simple_select($sql, $data);
    }


    public function cpanelRequest($url, $username, $password, $postFields = null)
    {
        global $verificador;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        if ($postFields !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        }
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        } else {
            $responseData = json_decode($response, true);
        }
        curl_close($ch);
        return $responseData;
    }

    function addAddonDomain($domain, $directory)
    {
        // Configuración de la API de cPanel
        $cpanelUrl = 'https://imporsuit.com:2083/';
        $cpanelUsername = 'imporsuit';
        $cpanelPassword = '09992631072demasiado.';

        // Configurar los parámetros para la solicitud
        $apiUrl = $cpanelUrl . 'json-api/cpanel?cpanel_jsonapi_user=' . $cpanelUsername . '&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=AddonDomain&cpanel_jsonapi_func=addaddondomain&newdomain=' . $domain . '&dir=' . $directory . '&subdomain=' . $domain;

        // Inicializar cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desactivar en producción, habilita la verificación SSL
        curl_setopt($ch, CURLOPT_USERPWD, $cpanelUsername . ':' . $cpanelPassword);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si hubo errores
        if (curl_errno($ch)) {
            throw new Exception('Error en la solicitud cURL: ' . curl_error($ch));
        }

        // Analizar la respuesta JSON
        $responseData = json_decode($response, true);
        curl_close($ch);

        // Verificar el estado de la respuesta
        if (isset($responseData['cpanelresult']['data'][0]['result']) && $responseData['cpanelresult']['data'][0]['result'] == 1) {
            return true;
        } else {
            throw new Exception('Error al añadir el dominio: ' . $responseData['cpanelresult']['data'][0]['reason']);
        }
    }

    public function informaciontienda($plataforma)
    {
        $sql = "SELECT * FROM plataformas pl, perfil pe WHERE pl.id_plataforma=$plataforma and pe.id_plataforma=pl.id_plataforma";

        return $this->select($sql);
    }

    public function caracteristicastienda($plataforma)
    {
        $sql = "SELECT * FROM caracteristicas_tienda WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function bannertienda($plataforma)
    {
        $sql = "SELECT * FROM banner_adicional WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function testimoniostienda($plataforma)
    {
        $sql = "SELECT * FROM testimonios WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function profesionales($plataforma)
    {
        $sql = "SELECT * FROM profesionales WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function categoriastienda($id_plataforma)
    {
        $sql = "SELECT * FROM productos_tienda pt, lineas l WHERE pt.id_plataforma = $id_plataforma AND pt.id_categoria_tienda = l.id_linea  GROUP BY id_categoria_tienda ORDER BY l.orden ASC;";

        return $this->select($sql);
    }

    public function destacadostienda($id_plataforma)
    {
        $sql = "SELECT * FROM productos_tienda WHERE destacado_tienda=1 AND id_plataforma = $id_plataforma;";

        return $this->select($sql);
    }

    public function iconostienda($id_plataforma)
    {
        $sql = "SELECT * FROM caracteristicas_tienda WHERE (accion=1 or accion=2 or accion=3) AND id_plataforma = $id_plataforma;";

        return $this->select($sql);
    }

    public function obtener_productos_tienda($plataforma, $id_producto_tienda)
    {
        $sql = "SELECT * FROM `productos_tienda` WHERE id_plataforma=$plataforma AND id_producto_tienda = $id_producto_tienda";

        return $this->select($sql);
    }

    public function listar_imagenAdicional_productosTienda($id_producto, $plataforma)
    {
        $sql = "SELECT * FROM `imagens_adicionales_productoTienda` WHERE id_plataforma = $plataforma AND id_producto = $id_producto";
        // echo $sql;
        return $this->select($sql);
    }

    public function obtener_productos_tienda_filtro($plataforma, $id_categoria, $precio_maximo, $precio_minimo)
    {

        $where = '';

        if (isset($id_categoria) and $id_categoria != '') {
            $where .= " and pt.id_categoria_tienda = $id_categoria ";
        }


        if (isset($precio_minimo) and $precio_minimo != '') {
            $where .= " and pt.pvp_tienda >= $precio_minimo ";
        }

        if (isset($precio_maximo) and $precio_maximo != '') {
            $where .= " and pt.pvp_tienda <= $precio_maximo ";
        }




        $sql = "SELECT * FROM `productos_tienda` pt, productos p, inventario_bodegas ib WHERE  pt.id_producto=p.id_producto and pt.id_inventario=ib.id_inventario and pt.id_plataforma=$plataforma $where";
        return $this->select($sql);
    }


    public function horizontaltienda($plataforma)
    {
        $sql = "SELECT * FROM horizontal WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }


    public function insertarCatacteristica($plataforma)
    {
        $sql = "SELECT * FROM caracteristicas_tienda WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    /*   public function agregarCaracteristicas($nombre, $plataforma)
    {
        // codigo para agregar categoria
        $response = $this->initialResponse();

        $sql = "INSERT INTO caracteristicas_tienda (nombre_linea, descripcion_linea, estado_linea, date_added, online, imagen, tipo, padre, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //$data = [$nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $plataforma];
        //$insertar_categoria = $this->insert($sql, $data);
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
    } */

    public function actualizar_tienda($ruc_tienda, $telefono_tienda, $email_tienda, $direccion_tienda, $pais_tienda, $plataforma, $facebook, $instagram, $tiktok)
    {
        $response = $this->initialResponse();
        $sql = "UPDATE `plataformas` SET  `cedula_facturacion` = ?, `correo_facturacion` = ?, `direccion_facturacion` = ?, `whatsapp` = ?   WHERE `plataformas`.`id_plataforma` = ? ";
        $data = [$ruc_tienda, $email_tienda, $direccion_tienda, $telefono_tienda, $plataforma];
        $editar_plataforma = $this->update($sql, $data);
        echo $sql;
        $sql = "UPDATE `perfil` SET  `facebook` = ?, `instagram` = ?, `tiktok` = ?   WHERE `perfil`.`id_plataforma` = ? ";
        $data = [$facebook, $instagram, $tiktok, $plataforma];
        $editar_perfil = $this->update($sql, $data);


        // print_r($insertar_producto_);
        if ($editar_plataforma == 1 || $editar_perfil == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto editado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'error';
        }
        return $response;
    }

    public function obtener_informacion_tienda($plataforma)
    {
        $sql = "SELECT * FROM plataformas pl, perfil pe WHERE pl.id_plataforma=$plataforma and pl.id_plataforma= pe.id_plataforma";

        return $this->select($sql);
    }

    public function obtener_horizontalTienda($plataforma)
    {
        $sql = "SELECT * FROM horizontal WHERE id_plataforma = $plataforma ORDER BY id_horizontal ASC;";

        return $this->select($sql);
    }

    public function guardar_pedido($id_plataforma, $id_producto, $precio_producto, $nombre, $telefono, $provincia, $ciudad, $calle_principal, $calle_secundaria, $referencia, $observacion, $id_inventario)
    {
        // $tmp = session_id();
        //$response = $this->initialResponse();

        $sql_producto = "SELECT * FROM productos_tienda WHERE id_producto_tienda = $id_producto limit 1";
        $producto = $this->select($sql_producto);
        $producto = $producto[0]['id_producto'];
        //echo $producto;

        $sql_datos_producto = "SELECT * FROM productos WHERE id_producto = $producto ";
        // echo $sql_datos_producto;
        $datos_producto = $this->select($sql_datos_producto);
        $producto_plataforma = $datos_producto[0]['id_plataforma'];


        $sql_datos_bodega = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_inventario ";

        // echo $sql_datos_bodega;
        $datos_bodega = $this->select($sql_datos_bodega);
        $bodega = $datos_bodega[0]['bodega'];
        $sku = $datos_bodega[0]['sku'];

        // echo $sql_datos_bodega;
        $sql_datos_origen = "SELECT * FROM bodega WHERE id = $bodega ";
        $datos_origen = $this->select($sql_datos_origen);
        $ciudadO = $datos_origen[0]['localidad'];
        $nombreO = $datos_origen[0]['nombre'];
        $direccionO = $datos_origen[0]['direccion'];

        //echo $bodega.'-'.$direccionO.'--'.$ciudadO.'---'.$nombreO;


        $date_added     = date("Y-m-d H:i:s");

        $ultima_factura = $this->select("SELECT MAX(numero_factura) as factura_numero FROM facturas_cot");
        $factura_numero = $ultima_factura[0]['factura_numero'];
        if (!$factura_numero || $factura_numero == '') {
            $factura_numero = 'COT-0000000000';
        }
        $nueva_factura = $this->incrementarNumeroFactura($factura_numero);


        if ($producto_plataforma == $id_plataforma) {
            $drop = 0;
        } else {
            $drop = 1;
        }

        $response = $this->initialResponse();
        $sql = "INSERT INTO facturas_cot (
            numero_factura, fecha_factura, monto_factura, estado_factura, 
            nombre, telefono, c_principal, ciudad_cot, c_secundaria, 
            referencia, observacion, guia_enviada, transporte, identificacion, celular, 
            id_propietario, drogshipin, id_plataforma, importado, 
            plataforma_importa, cod, estado_guia_sistema, impreso, facturada, 
            anulada, identificacionO, nombreO, ciudadO,  provincia,
            direccionO, referenciaO, numeroCasaO, valor_seguro, no_piezas, tipo_servicio, 
            peso, contiene, costo_flete, costo_producto, comentario, id_transporte, telefonoO, id_bodega
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $data = array(
            $nueva_factura,
            $date_added,
            $precio_producto,
            1,
            $nombre,
            $telefono,
            $calle_principal,
            $ciudad,
            $calle_secundaria,
            $referencia,
            $observacion,
            0,
            0,
            0,
            $telefono,
            $producto_plataforma,
            $drop,
            $id_plataforma,
            0,
            'tienda_online',
            0,
            0,
            0,
            0,
            0,
            '',
            $nombreO,
            $ciudadO,
            $provincia,
            $direccionO,
            0,
            0,
            0,
            0,
            0,
            0,
            '',
            0,
            0,
            0,
            0,
            0,
            $bodega
        );

        if (substr_count($sql, '?') !== count($data)) {
            throw new Exception('La cantidad de placeholders en la consulta no coincide con la cantidad de elementos en el array de datos.');
        }

        $responses = $this->insert($sql, $data);

        // print_r($responses);
        if ($responses === 1) {
            // Insertar cada registro de tmp_cotizacion en detalle_cotizacion
            $detalle_sql = "INSERT INTO detalle_fact_cot (numero_factura, id_factura, id_producto, cantidad, desc_venta, precio_venta, id_plataforma , sku, id_inventario, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $id_factura = $this->select("SELECT id_factura FROM facturas_cot WHERE numero_factura = '$nueva_factura'");
            $factura_id = $id_factura[0]['id_factura'];


            //buscar producto 

            $sku = $sku;

            //  echo 'enta';
            $detalle_data = array(
                $nueva_factura,
                $factura_id,
                $producto,
                1,
                0,
                $precio_producto,
                $id_plataforma,
                $sku,
                $id_inventario,
                $nombre
            );
            $guardar_detalle = $this->insert($detalle_sql, $detalle_data);
            // print_r($guardar_detalle);
            // print_r($guardar_detalle);



            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = "Pedido creado correctamente";
            $response["numero_factura"] = $nueva_factura;
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $responses['message'];
        }

        return $response;
    }

    function incrementarNumeroFactura($factura)
    {
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

    /* pedido carrito */
    public function guardar_pedido_carrito($id_plataforma, $id_producto, $total, $nombre, $telefono, $provincia, $ciudad, $calle_principal, $calle_secundaria, $referencia, $observacion, $tmp, $combo_selected, $combo_id, $oferta_selected, $id_producto_oferta, $id_configuracion)
    {
        // Obtener los productos en el carrito temporal
        $tmp_cotizaciones = $this->select("SELECT * FROM tmp_cotizacion WHERE session_id = '$tmp'");

        // Agrupar los productos por bodega
        $productos_por_bodega = [];

        foreach ($tmp_cotizaciones as $tmp_session) {
            $id_inventario = $tmp_session['id_inventario'];
            $datos_bodega = $this->select("SELECT * FROM inventario_bodegas WHERE id_inventario = $id_inventario");
            $bodega = $datos_bodega[0]['bodega'];

            // Agrupar los productos por bodega
            if (!isset($productos_por_bodega[$bodega])) {
                $productos_por_bodega[$bodega] = [];
            }
            $productos_por_bodega[$bodega][] = $tmp_session;
        }

        $response = $this->initialResponse();

        // Crear una factura para cada bodega
        foreach ($productos_por_bodega as $bodega => $productos) {

            // Obtener la información de la bodega y el id_plataforma desde inventario_bodegas
            $sql_datos_origen = "SELECT bodega.*, inventario_bodegas.id_plataforma as plataforma_bodega FROM bodega 
                             JOIN inventario_bodegas ON bodega.id = inventario_bodegas.bodega 
                             WHERE bodega.id = $bodega LIMIT 1";
            $datos_origen = $this->select($sql_datos_origen);
            $ciudadO = $datos_origen[0]['localidad'];
            $nombreO = $datos_origen[0]['nombre'];
            $direccionO = $datos_origen[0]['direccion'];
            $plataforma_bodega = $datos_origen[0]['plataforma_bodega'];  // El id_plataforma de la bodega

            if ($plataforma_bodega == $id_plataforma) {
                $drop = 0;
            } else {
                $drop = 1;
            }

            $date_added = date("Y-m-d H:i:s");

            // Generar el número de factura
            $ultima_factura = $this->select("SELECT MAX(numero_factura) as factura_numero FROM facturas_cot");
            $factura_numero = $ultima_factura[0]['factura_numero'];
            if (!$factura_numero || $factura_numero == '') {
                $factura_numero = 'COT-0000000000';
            }
            $nueva_factura = $this->incrementarNumeroFactura($factura_numero);

            // Calcular el total por bodega (suma de los precios de los productos en esta bodega)
            $total_bodega = 0;
            $costo_total_bodega = 0; // Nueva variable para almacenar el costo del producto

            foreach ($productos as $producto) {
                $total_bodega += $producto['precio_tmp'] * $producto['cantidad_tmp'];

                // Obtener el precio de costo (pcp) desde inventario_bodegas
                $datos_producto = $this->select("SELECT pcp FROM inventario_bodegas WHERE id_inventario = " . $producto['id_inventario']);

                if (!empty($datos_producto)) {
                    $costo_producto = $datos_producto[0]['pcp'];
                } else {
                    $costo_producto = 0; // En caso de que no haya datos, evita errores
                }

                // Sumar al costo total de la bodega
                $costo_total_bodega += $costo_producto * $producto['cantidad_tmp'];
            }

            if ($combo_selected == 1) {
                $datos_combo = $this->select("SELECT * FROM combos INNER JOIN detalle_combo ON detalle_combo.id_combo = combos.id 
                INNER JOIN inventario_bodegas ON inventario_bodegas.id_inventario = detalle_combo.id_inventario WHERE combos.id = $combo_id");
                $valor = $datos_combo[0]['valor'];
                $estado_combo = $datos_combo[0]['estado_combo'];
                $totalPvp = 0;

                foreach ($datos_combo as $producto) {
                    $totalPvp += $producto['pvp'] * $producto['cantidad'];
                }

                if ($estado_combo == 1) {
                    $total_bodega = $totalPvp * (1 - $valor / 100);
                } else if ($estado_combo == 2) {
                    $total_bodega = $totalPvp - $valor;
                }
            }


            if ($oferta_selected == 1) {
                $datos_oferta = $this->select("SELECT * FROM productos_tienda INNER JOIN inventario_bodegas ON productos_tienda.id_inventario = inventario_bodegas.id_inventario 
                WHERE id_producto_tienda = $id_producto_oferta;");

                $bodega_oferta = $datos_oferta[0]['bodega'];
                if ($bodega_oferta == $bodega) {
                    $precio_oferta = $datos_oferta[0]['pvp_tienda'];

                    $total_bodega = $total_bodega + $precio_oferta;
                }
            }

            // Insertar la factura para esta bodega con el id_plataforma correcto desde inventario_bodegas
            $sql = "INSERT INTO facturas_cot (
                numero_factura, fecha_factura, monto_factura, estado_factura, 
                nombre, telefono, c_principal, ciudad_cot, c_secundaria, 
                referencia, observacion, guia_enviada, transporte, identificacion, celular, 
                id_propietario, drogshipin, id_plataforma, importado, 
                plataforma_importa, cod, estado_guia_sistema, impreso, facturada, 
                anulada, identificacionO, nombreO, ciudadO,  provincia,
                direccionO, referenciaO, numeroCasaO, valor_seguro, no_piezas, tipo_servicio, 
                peso, contiene, costo_flete, costo_producto, comentario, id_transporte, telefonoO, id_bodega
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";

            // Insertar la factura con el id_plataforma de la bodega
            $data = array(
                $nueva_factura,
                $date_added,
                $total_bodega, // Total de los productos de esta bodega
                1,
                $nombre,
                $telefono,
                $calle_principal,
                $ciudad,
                $calle_secundaria,
                $referencia,
                $observacion,
                0,
                0,
                0,
                $telefono,
                $plataforma_bodega, // Se inserta en id_propietario el id_plataforma de la bodega
                $drop,
                $id_plataforma, // Esto puede seguir siendo el id_plataforma original, ya que es la plataforma de origen de la compra
                0,
                'tienda_online',
                0,
                0,
                0,
                0,
                0,
                '',
                $nombreO,
                $ciudadO,
                $provincia,
                $direccionO,
                0,
                0,
                0,
                0,
                0,
                0,
                '',
                0,
                $costo_total_bodega, // Agregar el costo total del producto aquí
                0,
                0,
                0,
                $bodega
            );

            if (substr_count($sql, '?') !== count($data)) {
                throw new Exception('La cantidad de placeholders en la consulta no coincide con la cantidad de elementos en el array de datos.');
            }

            $responses = $this->insert($sql, $data);

            if ($responses == 1) {
                $contenido_factura = "";
                if ($combo_selected == 1) {
                    $id_factura = $this->select("SELECT id_factura FROM facturas_cot WHERE numero_factura = '$nueva_factura'");
                    $factura_id = $id_factura[0]['id_factura'];

                    // Insertar el detalle de la factura
                    $detalle_sql = "INSERT INTO detalle_fact_cot (numero_factura, id_factura, id_producto, cantidad, desc_venta, precio_venta, id_plataforma, sku, id_inventario, combo, id_combo) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $detalle_combo = $this->select("SELECT * FROM detalle_combo INNER JOIN inventario_bodegas ON inventario_bodegas.id_inventario = detalle_combo.id_inventario WHERE id_combo = $combo_id");

                    // Inicializamos la variable que llevará el acumulado del nuevo total
                    $totalDistribuido = 0;
                    $ultimoProductoIndex = count($detalle_combo) - 1;

                    foreach ($detalle_combo as $index => $tmp_session) {
                        if ($estado_combo == 1) {
                            // Proporción del precio del producto en relación al total
                            $proporcion = ($tmp_session['pvp'] * $tmp_session['cantidad']) / $totalPvp;

                            // Calcular el nuevo precio proporcionalmente como porcentaje
                            $nuevoPvp = $total_bodega * $proporcion;
                        } elseif ($estado_combo == 2) {
                            // Si el estado del combo es 2, entonces valor es un descuento fijo que se debe distribuir
                            $descuentoProporcional = ($tmp_session['pvp'] * $tmp_session['cantidad']) / $totalPvp * $valor;

                            // Calcular el nuevo precio restando el descuento proporcional
                            $nuevoPvp = ($tmp_session['pvp'] * $tmp_session['cantidad']) - $descuentoProporcional;
                        }

                        // Si es el último producto, ajusta para que el total distribuido sea exacto
                        if ($index == $ultimoProductoIndex) {
                            $nuevoPvp = $total_bodega - $totalDistribuido;
                        }

                        $nuevoPvpUnitario = $nuevoPvp / $tmp_session['cantidad']; // Precio unitario ajustado

                        $detalle_data = array(
                            $nueva_factura,
                            $factura_id,
                            $tmp_session['id_producto'],
                            $tmp_session['cantidad'],
                            0,
                            $nuevoPvpUnitario, // Guardar el pvp unitario ajustado
                            $tmp_session['id_plataforma'],
                            $tmp_session['sku'],
                            $tmp_session['id_inventario'],
                            1, // Indicador de que este producto pertenece a un combo
                            $combo_id
                        );

                        // Insertar el detalle
                        $guardar_detalle = $this->insert($detalle_sql, $detalle_data);

                        /* tomar nombre productos */
                        $nombre_producto = $this->select("SELECT nombre_producto FROM productos WHERE id_producto = '" . $tmp_session['id_producto'] . "'");
                        $nombre_producto = $nombre_producto[0]['nombre_producto'];
                        $contenido_factura .= $nombre_producto . " X " . $tmp_session['cantidad'] . ", ";

                        // Acumular el total distribuido
                        $totalDistribuido += $nuevoPvp;
                    }

                    if ($oferta_selected == 1) {
                        // Corregimos la consulta SQL y verificamos que se obtuvieron resultados
                        $datos_oferta = $this->select("SELECT * FROM productos_tienda 
                                                        INNER JOIN inventario_bodegas ON productos_tienda.id_inventario = inventario_bodegas.id_inventario 
                                                        WHERE id_producto_tienda = $id_producto_oferta");

                        // Asegurarse de que $datos_oferta no esté vacío
                        if (!empty($datos_oferta)) {
                            $datos_oferta = $datos_oferta[0]; // Obtener el primer resultado

                            // Definir el array para insertar el detalle de la oferta
                            $detalle_data_oferta = array(
                                $nueva_factura,
                                $factura_id,
                                $datos_oferta['id_producto'],
                                1, // Cantidad de la oferta (puedes cambiar si es diferente)
                                0,
                                $datos_oferta['pvp_tienda'], // Guardar el pvp unitario ajustado
                                $datos_oferta['id_plataforma'],
                                $datos_oferta['sku'],
                                $datos_oferta['id_inventario'],
                                0, // Indicador de que no pertenece a un combo
                                $combo_id
                            );

                            // Insertar el detalle
                            $guardar_detalle = $this->insert($detalle_sql, $detalle_data_oferta);

                            /* tomar nombre productos */
                            $nombre_producto = $this->select("SELECT nombre_producto FROM productos WHERE id_producto = '" . $tmp_session['id_producto'] . "'");
                            $nombre_producto = $nombre_producto[0]['nombre_producto'];
                            $contenido_factura .= $nombre_producto . " X 1, ";

                            /* print_r($guardar_detalle); */
                        }
                    }

                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = "Pedido creado correctamente";
                    $response["numero_factura"] = $nueva_factura;
                } else {
                    // Código para insertar los productos sin combo
                    $id_factura = $this->select("SELECT id_factura FROM facturas_cot WHERE numero_factura = '$nueva_factura'");

                    if (!empty($id_factura)) {
                        $factura_id = $id_factura[0]['id_factura'];
                    } else {
                        // Manejar el error si no se encuentra la factura
                        $response['status'] = 500;
                        $response['title'] = 'Error';
                        $response['message'] = "No se encontró la factura para el número: $nueva_factura";
                        return $response;
                    }

                    // Insertar el detalle de la factura
                    $detalle_sql = "INSERT INTO detalle_fact_cot (numero_factura, id_factura, id_producto, cantidad, desc_venta, precio_venta, id_plataforma, sku, id_inventario) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    foreach ($productos as $tmp_session) {
                        $detalle_data = array(
                            $nueva_factura,
                            $factura_id,
                            $tmp_session['id_producto'],
                            $tmp_session['cantidad_tmp'],
                            $tmp_session['desc_tmp'],
                            $tmp_session['precio_tmp'],
                            $tmp_session['id_plataforma'],
                            $tmp_session['sku'],
                            $tmp_session['id_inventario']
                        );
                        $guardar_detalle = $this->insert($detalle_sql, $detalle_data);

                        /* tomar nombre productos */
                        $nombre_producto = $this->select("SELECT nombre_producto FROM productos WHERE id_producto = '" . $tmp_session['id_producto'] . "'");
                        $nombre_producto = $nombre_producto[0]['nombre_producto'];
                        $contenido_factura .= $nombre_producto . " X " . $tmp_session['cantidad_tmp'] . ", ";
                    }

                    if ($oferta_selected == 1) {
                        // Corregimos la consulta SQL y verificamos que se obtuvieron resultados
                        $datos_oferta = $this->select("SELECT * FROM productos_tienda 
                                                        INNER JOIN inventario_bodegas ON productos_tienda.id_inventario = inventario_bodegas.id_inventario 
                                                        WHERE id_producto_tienda = $id_producto_oferta");

                        $bodega_oferta = $datos_oferta[0]['bodega'];
                        if ($bodega_oferta == $bodega) {

                            // Asegurarse de que $datos_oferta no esté vacío
                            if (!empty($datos_oferta)) {
                                $datos_oferta = $datos_oferta[0]; // Obtener el primer resultado

                                // Definir el array para insertar el detalle de la oferta
                                $detalle_data_oferta = array(
                                    $nueva_factura,
                                    $factura_id,
                                    $datos_oferta['id_producto'],
                                    1, // Cantidad de la oferta (puedes cambiar si es diferente)
                                    0,
                                    $datos_oferta['pvp_tienda'], // Guardar el pvp unitario ajustado
                                    $datos_oferta['id_plataforma'],
                                    $datos_oferta['sku'],
                                    $datos_oferta['id_inventario']
                                );

                                // Insertar el detalle
                                $guardar_detalle = $this->insert($detalle_sql, $detalle_data_oferta);

                                /* tomar nombre productos */
                                $nombre_producto = $this->select("SELECT nombre_producto FROM productos WHERE id_producto = '" . $tmp_session['id_producto'] . "'");
                                $nombre_producto = $nombre_producto[0]['nombre_producto'];
                                $contenido_factura .= $nombre_producto . " X 1, ";

                                /* print_r($guardar_detalle); */
                            }
                        }
                    }
                    if ($id_configuracion != "") {

                        /* consultar ciudad por medio id_cotizacion */
                        $nombre_ciudad = $this->select("SELECT ciudad FROM ciudad_cotizacion WHERE id_cotizacion = $ciudad");
                        $nombre_ciudad = $nombre_ciudad[0]['ciudad'];

                        // Ejecutamos la función que devuelve productos y categorías
                        $data = $this->ejecutar_automatizador($nueva_factura);

                        $data = [
                            "id_configuracion" => $id_configuracion,
                            "value_blocks_type" => "1",
                            "user_id" => "1",
                            "order_id" => $nueva_factura,
                            "nombre" => $nombre,
                            "direccion" => $calle_principal . " y " . $calle_secundaria,
                            "email" => "",
                            "celular" => $telefono,
                            "contenido" => $contenido_factura,
                            "costo" => $total_bodega,
                            "ciudad" => $nombre_ciudad,
                            "tracking" => "",
                            "transportadora" => "",
                            "numero_guia" => "",
                            "productos" => $data['productos'] ?? [],
                            "categorias" => $data['categorias'] ?? [],
                            "status" => [""],
                            "novedad" => [""],
                            "provincia" => [""],
                            "ciudad" => [""],
                            "user_info" => [
                                "nombre" => $nombre,
                                "direccion" => $calle_principal . " y " . $calle_secundaria,
                                "email" => "",
                                "celular" => $telefono,
                                "order_id" => $nueva_factura,
                                "contenido" => $contenido_factura,
                                "costo" => $total_bodega,
                                "ciudad" => $nombre_ciudad,
                                "tracking" => "",
                                "transportadora" => "",
                                "numero_guia" => ""
                            ]
                        ];

                        // Llamamos a la función para enviar los datos a la API usando cURL
                        $response_api = $this->enviar_a_api($data);

                        // Comprobamos si hubo un error en cURL
                        if (!$response_api['success']) {
                            // Si hubo un error, lo añadimos al response
                            $response['status'] = 500;
                            $response['title'] = 'Error';
                            $response['message'] = "Error al enviar los datos a la API: " . $response_api['error'];
                        } else {
                            // Si la llamada a la API fue exitosa
                            $response['status'] = 200;
                            $response['title'] = 'Peticion exitosa';
                            $response['message'] = "Pedido creado correctamente y datos enviados";
                            $response["numero_factura"] = $nueva_factura;
                            $response['data'] = $data;
                            $response['respuesta_curl'] = $response_api['response'];
                        }
                    } else {
                        // Si la llamada a la API fue exitosa
                        $response['status'] = 200;
                        $response['title'] = 'Peticion exitosa';
                        $response['message'] = "Pedido creado correctamente y datos enviados";
                        $response["numero_factura"] = $nueva_factura;
                    }
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = $responses['message'];
                return $response;
            }
        }

        return $response;
    }

    public function crearPixel($plataforma, $nombre, $pixel, $tipo)
    {
        $response = $this->initialResponse();
        $sql = "INSERT INTO pixel (nombre, pixel, id_plataforma, tipo) VALUES (?, ?, ?, ?)";
        $data = [$nombre, $pixel, $plataforma, $tipo];
        $insertar_pixel = $this->insert($sql, $data);
        if ($insertar_pixel == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Pixel agregado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] =  $insertar_pixel['message'];
        }
        return $response;
    }

    public function obtenerPixeles($plataforma)
    {
        $sql = "SELECT * FROM pixel WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function obtenerPixel($tipo, $plataforma)
    {
        $sql = "SELECT * FROM pixel WHERE tipo = $tipo AND id_plataforma = $plataforma";
        $response =  $this->select($sql);
        if (empty($response)) {
            return 0;
        } else {
            return $response;
        }
    }

    public function actualizarPixel($nombre, $pixel, $tipo, $plataforma)
    {
        $response = $this->initialResponse();
        $sql = "UPDATE pixel SET nombre = ?, pixel = ?, tipo = ? WHERE id_plataforma = ? and tipo = ?";
        $data = [$nombre, $pixel, $tipo, $plataforma, $tipo];
        $actualizar_pixel = $this->update($sql, $data);
        if ($actualizar_pixel == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Pixel actualizado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] =  $actualizar_pixel['message'];
        }
        return $response;
    }

    public function eliminarPixel($id_pixel)
    {
        $response = $this->initialResponse();
        $sql = "DELETE FROM pixel WHERE id_pixel = ?";
        $data = [$id_pixel];
        $eliminar_pixel = $this->delete($sql, $data);
        if ($eliminar_pixel == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Pixel eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] =  $eliminar_pixel['message'];
        }
        return $response;
    }

    /* Plantilla 2 */
    public function obtener_ofertas_plantilla2($plataforma)
    {
        $sql = "SELECT * FROM plantilla_2 WHERE id_plataforma=$plataforma";
        return $this->select($sql);
    }
    /* Fin plantilla 2 */

    /* Plantilla 3 */
    public function obtener_informacion_plantilla3($plataforma)
    {
        //   echo $sql;
        $sql = "SELECT * FROM plantilla_3 WHERE id_plataforma=$plataforma";
        return $this->select($sql);
    }
    /* Fin plantilla 3 */

    public function agregar_carrito($id_producto, $cantidad, $precio,  $plataforma, $id_invetario, $timestamp)
    {

        $sql_producto = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_invetario limit 1";
        $producto_consulta = $this->select($sql_producto);
        $sku = $producto_consulta[0]['sku'];

        //echo "SELECT * FROM tmp_cotizacion WHERE session_id = '$timestamp' and id_producto=$id_producto and sku=$sku";
        $cantidad_tmp = $this->select("SELECT * FROM tmp_cotizacion WHERE session_id = '$timestamp' and id_inventario=$id_invetario");

        //print_r($cantidad_tmp);
        if (empty($cantidad_tmp)) {

            $sql = "INSERT INTO `tmp_cotizacion` (`id_producto`, `cantidad_tmp`, `precio_tmp`, `session_id`, `id_plataforma`, `id_inventario`, `sku`) VALUES (?, ?, ?, ?, ?, ?, ?);";
            $data = [$id_producto, $cantidad, $precio, $timestamp, $plataforma, $id_invetario, $sku];
            $insertar_caracteristica = $this->insert($sql, $data);
            //print_r($insertar_caracteristica);
        } else {
            $cantidad_anterior = $cantidad_tmp[0]["cantidad_tmp"];
            $cantidad_nueva = $cantidad_anterior + $cantidad;
            $id_tmp = $cantidad_tmp[0]["id_tmp"];
            $sql = "UPDATE `tmp_cotizacion` SET  `cantidad_tmp` = ? WHERE `id_tmp` = ?";
            $data = [$cantidad_nueva, $id_tmp];
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
            $response['message'] = 'Error al agregar al carrito';
        }
        return $response;
    }

    public function obtenerBodegaProducto($id_producto, $sku)
    {
        // echo $sku;
        $sql_invetario = "SELECT * FROM inventario_bodegas WHERE id_producto = $id_producto and sku='$sku'";
        //echo $sql_invetario;
        $invetario = $this->select($sql_invetario);
        $id_invetario = $invetario[0]['id_inventario'];
        return $id_invetario;
    }


    public function buscar_carrito($tmp)
    {
        // echo $tmp;
        $sql = "SELECT * FROM `tmp_cotizacion` tmp LEFT JOIN `inventario_bodegas` ib ON tmp.id_inventario = ib.id_inventario 
        LEFT JOIN `productos` p ON tmp.id_producto = p.id_producto LEFT JOIN `variedades` v ON ib.id_variante = v.id_variedad WHERE tmp.session_id = '$tmp'";
        //echo $sql;
        return $this->select($sql);
    }

    public function sumar_carrito($id_tmp, $cantidad_nueva)
    {
        $sql = "UPDATE `tmp_cotizacion` SET  `cantidad_tmp` = ? WHERE `id_tmp` = ?";
        $data = [$cantidad_nueva, $id_tmp];
        $insertar_caracteristica = $this->update($sql, $data);

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

    public function eliminar_carrito($id_tmp)
    {
        $response = $this->initialResponse();
        $sql = "DELETE FROM tmp_cotizacion WHERE id_tmp = ?";
        $data = [$id_tmp];
        $eliminar_pixel = $this->delete($sql, $data);
        if ($eliminar_pixel == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Pixel eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] =  $eliminar_pixel['message'];
        }
        return $response;
    }

    public function limpiar_carrito($tmp)
    {
        $sql_delete_carrito = "DELETE FROM tmp_cotizacion WHERE session_id = ?";
        $data = [$tmp];
        $eliminar_pixel = $this->delete($sql_delete_carrito, $data);

        if ($eliminar_pixel > 0) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Pixel eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'No hay productos en su carrito';
        }
        return $response;
    }

    public function obtener_combo_id($id_producto)
    {
        $sql = "SELECT * FROM `combos` WHERE id_producto_combo=$id_producto;";
        return $this->select($sql);
    }

    public function obtener_combo_idCombo($id_combo)
    {
        $sql = "SELECT * FROM `combos` WHERE id=$id_combo;";
        return $this->select($sql);
    }

    public function obtener_detalle_combo_id($id_combo)
    {
        $sql = "SELECT *, (SELECT variedad FROM `variedades` WHERE variedades.id_variedad = inventario_bodegas.id_variante)AS variedad 
        FROM `detalle_combo` INNER JOIN `inventario_bodegas` ON inventario_bodegas.id_inventario = detalle_combo.id_inventario 
        INNER JOIN `productos` ON productos.id_producto = inventario_bodegas.id_producto WHERE id_combo=$id_combo;";
        return $this->select($sql);
    }

    public function obtener_oferta($id_plataforma)
    {
        $sql = "SELECT * FROM `productos_tienda` WHERE id_plataforma = $id_plataforma AND oferta = 1;";
        return $this->select($sql);
    }

    ///////////////////////////  FUNCIONES DE LA TIENDA  ///////////////////////////
    public function crearTienda($tienda, $plataforma)
    {
        $maxRetries = 5;  // Número máximo de intentos
        $retryDelay = 50;  // Tiempo de espera entre intentos en segundos

        ///crear tienda
        $url_crear = "https://activador.comprapor.com/activar/" . $tienda;
        $response = $this->initialResponse();
        $response1 = $this->retryRequest($url_crear, $maxRetries, $retryDelay);

        if ($response1 && isset($response1['status']) && $response1['status'] == 200) {
            /// reiniciar servidor
            $url_reiniciar = "https://activador.comprapor.com/reinciarServidor";
            $response2 = $this->retryRequest($url_reiniciar, $maxRetries, $retryDelay);

            if ($response2 && isset($response2['status']) && $response2['status'] == 200) {
                /// aplicar SSL
                $url_ssl = "https://activador.comprapor.com/activarSSL/" . $tienda . '.comprapor.com';
                $response3 = $this->retryRequest($url_ssl, $maxRetries, $retryDelay, 300);  // Timeout ajustado para SSL

                if ($response3 && isset($response3['status']) && $response3['status'] == 200) {

                    $tienda = 'https://' . $tienda . '.comprapor.com';
                    $update = "UPDATE plataformas SET tienda_creada = 1, url_imporsuit = ? WHERE id_plataforma = ?";
                    $data = [$tienda, $plataforma];
                    $this->simple_select($update, $data);



                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Tienda creada correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = $response3['message'] ?? 'Error desconocido al activar SSL';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al reiniciar el servidor';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $response1['message'] ?? 'Error desconocido al crear la tienda';
        }

        return $response;
    }

    public function modificarTienda($nombre, $antiguo, $plataforma)
    {
        $response = $this->initialResponse();
        $maxRetries = 5;
        $retryDelay = 50;

        // Modificar tienda (envío del parámetro "antiguo" como POST)
        $url_modificar = "https://activador.comprapor.com/cambiarNombre/" . $nombre;
        $response1 = $this->retryRequest($url_modificar, $maxRetries, $retryDelay, 300, ['antiguo' => $antiguo]);

        if ($response1 && isset($response1['status']) && $response1['status'] == 200) {
            // Reiniciar servidor
            $url_reiniciar = "https://activador.comprapor.com/reinciarServidor";
            $response2 = $this->retryRequest($url_reiniciar, $maxRetries, $retryDelay, 300);

            if ($response2 && isset($response2['status']) && $response2['status'] == 200) {
                // Activar SSL
                $url_ssl = "https://activador.comprapor.com/activarSSL/" . $nombre . '.comprapor.com';
                $response3 = $this->retryRequest($url_ssl, $maxRetries, $retryDelay, 300);

                if ($response3 && isset($response3['status']) && $response3['status'] == 200) {

                    // Desactivar SSL de la tienda anterior

                    $url_ssl_antiguo = "https://activador.comprapor.com/eliminarSSL/" . $antiguo . '.comprapor.com';
                    $response4 = $this->retryRequest($url_ssl_antiguo, $maxRetries, $retryDelay, 300);

                    if ($response4 && isset($response4['status']) && $response4['status'] == 200) {


                        $tienda = 'https://' . $nombre . '.comprapor.com';
                        $update = "UPDATE plataformas SET url_imporsuit = ? WHERE id_plataforma = ?";
                        $data = [$tienda, $plataforma];
                        $this->simple_select($update, $data);


                        $response['status'] = 200;
                        $response['title'] = 'Peticion exitosa';
                        $response['message'] = 'Tienda modificada correctamente';
                    } else {

                        $response['status'] = 500;
                        $response['title'] = 'Error';
                        $response['message'] = $response4['message'] ?? 'Error desconocido al desactivar SSL de la tienda anterior';
                    }
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = $response3['message'] ?? 'Error desconocido al activar SSL';
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al reiniciar el servidor';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $response1['message'] ?? 'Error desconocido al modificar el nombre de la tienda';
        }

        return $response;
    }

    public function agregarDominioConSubdominioExistenteTienda($dominio, $tienda, $plataforma)
    {
        $response = $this->initialResponse();
        $maxRetries = 5;
        $retryDelay = 50;

        // Agregar dominio con subdominio existente
        $url_agregar_dominio = "https://activador.comprapor.com/anadirDominio/" . $dominio;
        $response1 = $this->retryRequest($url_agregar_dominio, $maxRetries, $retryDelay, 300, ['tienda' => $tienda]);

        if ($response1 && isset($response1['status']) && $response1['status'] == 200) {
            // Activar SSL
            $url_ssl = "https://activador.comprapor.com/activarSSL/" . $dominio;
            $response2 = $this->retryRequest($url_ssl, $maxRetries, $retryDelay, 300);

            if ($response2 && isset($response2['status']) && $response2['status'] == 200) {
                $tienda = 'https://' . $dominio;
                $update = "UPDATE plataformas SET url_imporsuit = ? WHERE id_plataforma = ?";
                $data = [$tienda, $plataforma];
                $this->simple_select($update, $data);

                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Dominio agregado correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = $response2['message'] ?? 'Error desconocido al activar SSL';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $response1['message'] ?? 'Error desconocido al agregar el dominio';
        }

        return $response;
    }

    private function retryRequest($url, $maxRetries, $retryDelay, $timeout = 300, $postData = null)
    {
        $attempt = 0;
        $output = null;
        $response = null;

        while ($attempt < $maxRetries) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  // Timeout predeterminado o ajustado

            // Verificar si se están enviando datos POST
            if ($postData !== null) {
                curl_setopt($ch, CURLOPT_POST, true);
                // Asegurarse de que los datos se envían como un arreglo asociativo
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));  // Usar http_build_query para formato correcto
            }

            $output = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($output, true);

            // Verificar si se recibió un JSON válido con el estado
            if ($response && isset($response['status'])) {
                // Si el estado es 500 y el mensaje contiene el error específico, seguir intentando
                if ($response['status'] == 500 && isset($response['message']) && strpos($response['message'], 'SQLSTATE[HY000] [2002] Operation now in progress') !== false) {
                    // Si se detecta el error específico, continuar con el siguiente intento
                    $attempt++;
                    sleep($retryDelay);
                    continue;
                }

                // Si no hay errores específicos, devolver la respuesta
                return $response;
            }

            // Si no se recibió un JSON válido o hay otros errores, esperar antes de reintentar
            $attempt++;
            sleep($retryDelay);
        }

        return $response;  // Devuelve la última respuesta recibida (aunque sea null o incompleta)
    }

    /* automatizador */
    public function ejecutar_automatizador($numero_factura)
    {
        // Consulta para obtener los productos asociados a la factura
        $sql_factura = "SELECT * FROM detalle_fact_cot WHERE numero_factura = '$numero_factura'";
        $resultados = $this->select($sql_factura);

        // Arrays para almacenar los productos y categorías
        $productos = [];
        $categorias = [];

        // Si $resultados no es null o vacío, verificamos si es un array
        if ($resultados && is_array($resultados)) {
            // Recorremos los resultados y extraemos los ids de productos
            foreach ($resultados as $fila) {
                $productos[] = (string)$fila['id_inventario'];

                $id_inventario = $fila['id_inventario'];
                $id_plataforma = $fila['id_plataforma'];

                // Consulta para obtener la categoría del producto (limit 1)
                $sql_categorias = "SELECT id_categoria_tienda FROM productos_tienda WHERE id_inventario = $id_inventario 
            AND id_plataforma = $id_plataforma LIMIT 1";

                // Ejecutar la consulta de categorías (esperamos solo una fila debido al LIMIT 1)
                $resultado_categoria = $this->select($sql_categorias)[0];

                // Agregamos la categoría al array
                $categorias[] = (string)$resultado_categoria['id_categoria_tienda'] ?? null;
            }
        } else if ($resultados && isset($resultados['id_inventario'])) {
            // Si solo es una fila, también agregamos ese único id de producto al array
            $productos[] = (string)$resultados['id_inventario'];
        }

        // Retornamos el array con los ids de productos y categorías
        return [
            'productos' => $productos,
            'categorias' => $categorias
        ];
    }

    public function enviar_a_api($data)
    {
        // La URL del endpoint a donde enviar los datos
        $url = 'https://new.imporsuitpro.com/public/webhook_whatsapp/webhook_automatizador.php';

        // Inicializar cURL
        $ch = curl_init($url);

        // Configurar cURL para enviar los datos como una solicitud POST
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        // Codificar el array $data a formato JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Habilitar el seguimiento de redirecciones
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Esto permite seguir redirecciones

        // Ejecutar la solicitud cURL
        $response = curl_exec($ch);

        // Verificar si hubo errores en la ejecución
        if (curl_errno($ch)) {
            // Si hay un error, obtén el mensaje de error de cURL
            $error_msg = curl_error($ch);
            curl_close($ch);

            // Retornar el mensaje de error en lugar de la respuesta
            return [
                'success' => false,
                'error' => $error_msg
            ];
        }

        // Obtener información sobre la ejecución
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Si el código HTTP no es 200, retornar error
        if ($http_code !== 200) {
            return [
                'success' => false,
                'error' => "La API devolvió un código de estado HTTP no exitoso: $http_code"
            ];
        }

        // Si todo fue bien, retornar la respuesta
        return [
            'success' => true,
            'response' => $response
        ];
    }

    public function obtener_configuracion($id_plataforma)
    {
        $sql = "SELECT id FROM `configuraciones` WHERE id_plataforma = $id_plataforma";
        return $this->select($sql);
    }
    /* Fin automatizador */
}
