<?php
class GuiasModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerDestinatario($id_producto)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_producto";
        return $this->select($sql);
    }

    public function generarLaar($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras)
    {
        if ($cod == 1) {
            $cod = true;
        } else {
            $cod = false;
        }
        $numero_guia = $this->ultimaguia();
        $datos = array(
            "origen" => array(
                "identificacionO" => $identificacion,
                "nombreO" => "$nombreOrigen",
                "ciudadO" => "$ciudadOrigen",
                "direccion" => "$direccionOrigen",
                "telefono" => "$telefonoOrigen",
                "celular" => "$telefonoOrigen",
                "referenciaO" => "$referenciaOrigen",
                "celularO" => "$celularOrigen",
                "postal" => "$postal",
                "numeroCasa" => "0"
            ),
            "destino" => array(
                "identificacionD" => "0",
                "nombreD" => "$nombreDestino",
                "ciudadD" => "$ciudadDestino",
                "direccion" => "$direccionDestino",
                "telefono" => "$telefonoDestino",
                "celular" => "$celularDestino",
                "referencia" => "$referenciaDestino",
                "postal" => "$postal",
                "numeroCasa" => "0"
            ),
            "numeroGuia" => "$numero_guia",
            "tipoServicio" => "201202002002013",
            "noPiezas" => 1,
            "peso" => "$peso",
            "valorDeclarado" => "$valor_declarado",
            "contiene" => "$contiene",
            "tamanio" => "$tamanio",
            "cod" => $cod,
            "costoflete" => "$costoflete",
            "costoproducto" => $costo_producto,
            "tipoCobro" => "$tipo_cobro",
            "comentario" => "$comentario",
            "fechaPedido" => "$fecha",
            "extras" => array(
                "Campo1" => "",
                "Campo2" => "",
                "Campo3" => "",
            )
        );


        //iniciar curl
        $token = $this->laarToken();
        $ch = curl_init(LAAR_ENDPOINT);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ));

        //execute post
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function ultimaguia()
    {
        $prefijo = PREFIJOS;
        $sql = "SELECT MAX(numero_guia) as numero_guia FROM facturas_cot where numero_guia like '$prefijo%'";
        $numero_guia = $this->select($sql);
        $numero_guia = $numero_guia[0]['numero_guia'];
        if ($numero_guia == null || empty($numero_guia)) {
            $numero_guia = $prefijo . "000001";
        } else {
            $numero_guia =  $this->incrementarGuia($numero_guia);
        }
        $response = $this->update("UPDATE matriz set guia_generadas = guia_generadas + 1 WHERE idmatriz = ?", array(MATRIZ));
        return $numero_guia;
    }

    public function laarToken()
    {
        $ch = curl_init(LAAR_ENDPOINT_AUTH);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $auth = json_encode(array(
            "username" => LAAR_USER,
            "password" => LAAR_PASSWORD
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $auth);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($auth)
        ));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL para obtener el token: ' . curl_error($ch);
        }
        curl_close($ch);
        $response = json_decode($response, true);
        $response = $response['token'];
        return $response;
    }

    public function obtenerCiudadLaar($ciudad)
    {
        $sql = "SELECT codigo_ciudad_laar FROM ciudad_cotizacion WHERE id_cotizacion = '$ciudad'";
        $ciudad = $this->select($sql);
        return $ciudad[0]['codigo_ciudad_laar'];
    }

    public function actualizarGuia($numero_factura, $guia, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $cod, $costo_producto, $comentario, $usuario, $calle_principal, $calle_secundaria, $contiene, $provincia, $costo_flete, $transp)
    {
        switch ($transp) {
            case 'LAAR':
                $id_transporte = 1;
                break;
            case 'SERVIENTREGA':
                $id_transporte = 2;
                break;
            case 'GINTRACOM':
                $id_transporte = 3;
                break;
            case 'SPEED':
                $id_transporte = 4;
                break;
        }
        $sql =  "UPDATE `facturas_cot` SET `id_usuario`=?,`monto_factura`=?,`nombre`=?,`telefono`=?,`provincia`=?,`c_principal`=?,`ciudad_cot`=?,`c_secundaria`=?,`referencia`=?,`observacion`=?,`guia_enviada`=1,`transporte`='$transp',`celular`=?,`estado_guia_sistema`=2,`numero_guia`=?,`cod`=?,`contiene`=?,`comentario`=?,`id_transporte`=$id_transporte, `costo_flete` =$costo_flete WHERE `numero_factura`=?";
        $data = array($usuario, $costo_producto, $nombreDestino, $telefonoDestino, $provincia, $calle_principal, $ciudadDestino, $calle_secundaria, $referenciaDestino, $comentario, $celularDestino, $guia, $cod, $contiene, $comentario, $numero_factura);
        $response = $this->insert($sql, $data);
        return $response;
    }

    public function incrementarGuia($guia)
    {
        // Encontrar la posición del primer dígito en la cadena
        $pos = strcspn($guia, '0123456789');
        // Separar el prefijo del número de serie
        $prefijo = substr($guia, 0, $pos);
        $numero = substr($guia, $pos);

        // Incrementar el número de serie
        $numero = str_pad((int)$numero + 1, strlen($numero), '0', STR_PAD_LEFT);

        // Unir el prefijo con el número de serie
        $guia = $prefijo . $numero;

        return $guia;
    }

    public function asignarWallet($numero_factura, $guia, $fecha, $nombreDestino, $id_plataforma, $estado, $costo_producto, $cod, $precio_envio)
    {
        $buscar_detalle = "SELECT * FROM detalle_fact_cot WHERE numero_factura = '$numero_factura'";
        $respueta_detalle = $this->select($buscar_detalle);
        $id_inventario = $respueta_detalle[0]['id_inventario'];



        $buscar_inventario = "SELECT * FROM inventario_bodegas WHERE id_inventario = '$id_inventario'";
        $inventario = $this->select($buscar_inventario);
        $id_bodega = $inventario[0]['bodega'];
        $id_producto = $inventario[0]['id_producto'];

        $buscar_bodega = "SELECT * FROM bodega WHERE id = '$id_bodega'";
        $bodega = $this->select($buscar_bodega);
        $id_plataforma_bodega = $bodega[0]['id_plataforma'];
        $valor_full = $bodega[0]['full_filme'];

        $buscar_producto = "SELECT * FROM productos WHERE id_producto = '$id_producto'";
        $producto = $this->select($buscar_producto);
        $id_plataforma_producto = $producto[0]['id_plataforma'];

        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];

        if ($id_plataforma_bodega == $id_plataforma_producto) {
            $full = 0;
        } else if ($id_plataforma_producto == $id_plataforma) {
            $full = $valor_full;
        } else {
            $full = 0;
        }
        $proveedor = $id_plataforma_bodega;
        $proveedor = $this->select("SELECT url_imporsuit FROM plataformas WHERE id_plataforma = '$id_plataforma_producto'");
        $proveedor = $proveedor[0]['url_imporsuit'];

        $tienda_venta = $this->select("SELECT url_imporsuit FROM plataformas WHERE id_plataforma = '$id_plataforma'");
        $tienda_venta = $tienda_venta[0]['url_imporsuit'];

        if ($tienda_venta == $proveedor) {
            $proveedor = null;
        }

        $costo = $this->select("SELECT costo_producto FROM facturas_cot WHERE numero_factura = '$numero_factura'");
        $costo_o = $costo[0]['costo_producto'];



        $monto_recibir = $costo_producto - $precio_envio - $full - $costo_o;

        $insert_wallet = "INSERT INTO cabecera_cuenta_pagar (numero_factura, fecha, cliente, tienda, proveedor, estado_guia, total_venta, costo, precio_envio, monto_recibir, valor_cobrado, valor_pendiente, full, guia, cod, id_matriz) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = array($numero_factura, $fecha, $nombreDestino, $tienda_venta, $proveedor, $estado, $costo_producto, $costo_o, $precio_envio, $monto_recibir, 0, $monto_recibir, $full, $guia, $cod, $id_matriz);
        $response = $this->insert($insert_wallet, $data);
    }

    public function anularGuia($id)
    {
        $token = $this->laarToken();
        $ch = curl_init(LLAR_ENDPOINT_CANCEL . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token
        ));

        //execute post
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        }
        $deleteHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($deleteHttpCode == 200) {
            $response = array("status" => 200, "message" => "Guía anulada correctamente");
            $sql = "UPDATE facturas_cot SET estado_guia_sistema = 8, anulada = 1 WHERE numero_guia = ?";
            $response = $this->update($sql, array($id));
            if ($response === 1) {
                $eliminar_wallet = "DELETE FROM cabecera_cuenta_pagar WHERE guia = ?";
                $response = $this->delete($eliminar_wallet, array($id));
                $response = array("status" => 200, "message" => "Guía anulada correctamente");
                $response2 = $this->select("SELECT * FROM facturas_cot WHERE numero_guia = '$id'");
                $id_plataforma = $response2[0]['id_plataforma'];
                $response3 = $this->select("SELECT * FROM plataformas WHERE id_plataforma = '$id_plataforma'");
                $url = $response3[0]['id_matriz'];
                $response4 = $this->update("UPDATE matriz set guia_generadas = guia_generadas - 1 WHERE idmatriz = ?", array($url));
            } else {
                $response = array("status" => 500, "message" => "Error al anular la guía");
            }
        } else {
            $response = array("status" => 500, "message" => "Error al anular la guía");
        }
        curl_close($ch);

        return $response;
    }

    public function obtenerTiendas()
    {
        $sql = "SELECT * FROM tiendas";
        return $this->select($sql);
    }

    //servientrega
    public function generarServientrega($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $flete, $seguro, $comision, $otros, $impuestos)
    {
        $razon_social_remitente = "IMPORCOMEX S.A.";
        $razon_zocial_destinatario = "Entrega a Domicilio";
        $url = 'https://swservicli.servientrega.com.ec:5052/api/GuiaRecaudo';

        $datos = array(
            "ID_TIPO_LOGISTICA" => 1,
            "DETALLE_ENVIO_1" => "",
            "DETALLE_ENVIO_2" => "",
            "DETALLE_ENVIO_3" => "",
            "ID_CIUDAD_ORIGEN" => $ciudadOrigen,
            "ID_CIUDAD_DESTINO" => $ciudadDestino,
            "ID_DESTINATARIO_NE_CL" => "",
            "RAZON_SOCIAL_DESTI_NE" =>  $razon_zocial_destinatario,
            "NOMBRE_DESTINATARIO_NE" => $nombreDestino,
            "APELLIDO_DESTINATAR_NE" =>    "",
            "SECTOR_DESTINAT_NE" => "",
            "TELEFONO1_DESTINAT_NE" => $telefonoDestino,
            "TELEFONO2_DESTINAT_NE" => "",
            "CODIGO_POSTAL_DEST_NE" => "",
            "CORREO_DESTINATARIO" => "desarrollo1@imporfactoryusa.com",
            "ID_REMITENTE_CL" => "001remi",
            "RAZON_SOCIAL_REMITE" => $razon_social_remitente,
            "NOMBRE_REMITENTE" => "$nombreOrigen",
            "APELLIDO_REMITE" =>    "",
            "DIRECCION1_REMITE" => "$direccionOrigen",
            "SECTOR_REMITE" => "",
            "TELEFONO1_REMITE" => $telefonoOrigen,
            "TELEFONO2_REMITE" => "",
            "CODIGO_POSTAL_REMI" => "",
            "ID_PRODUCTO" => 2,
            "CONTENIDO" => $contiene,
            "NUMERO_PIEZAS" => 1,
            "VALOR_MERCANCIA" => $costo_producto,
            "VALOR_ASEGURADO" => 0,
            "LARGO" => 2,
            "ANCHO" => 50,
            "ALTO" => 50,
            "PESO_FISICO" => 2,
            "LOGIN_CREACION" => "integracion.api.1",
            "PASSWORD" => "54321",
            "ID_CL" => 0,
            "VERIFICAR_CONTENIDO_RECAUDO" => "",
            "VALIDADOR_RECAUDO" => "D",
            "DIRECCION_RECAUDO" => $direccionDestino . " - Referencia: " . $referenciaDestino,
            "FECHA_FACTURA" => $fecha,
            "NUMERO_FACTURA" => "002584154154",
            "VALOR_FACTURA" => $costo_producto,
            "VALOR_FLETE " => $flete,
            "VALOR_COMISION" => $comision,
            "VALOR_SEGURO" => $seguro,
            "VALOR_IMPUESTO" => $impuestos,
            "VALOR_OTROS" => $otros,
            "VALOR_A_RECAUDAR" => $costo_producto,
            "DETALLE_ITEMS_FACTURA" => "PRUEBAS SISTEMAS",
        );

        // Convertir los datos al formato JSON
        $jsonData = json_encode($datos);

        // Inicializar cURL
        $ch = curl_init($url);

        // Configura opciones de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Ignora la verificación de SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        // Configurar las opciones de cURL para la solicitud POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Omitir la verificación de SSL si es necesario (no recomendado para producción)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si ocurrió algún error durante la solicitud
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        // Cerrar la sesión cURL
        curl_close($ch);

        // Mostrar la respuesta
        return $response;
    }

    public function generarServientregaSinRecaudo($nombreOrigen, $ciudadOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $flete, $seguro, $comision, $otros, $impuestos)
    {
        $razon_social_remitente = "IMPORCOMEX S.A.";
        $razon_zocial_destinatario = "Entrega a Domicilio";
        $url = 'https://swservicli.servientrega.com.ec:5052/api/guiawebs';
        $data = array(
            "id_tipo_logistica" => 1,
            "detalle_envio_1" => "",
            "detalle_envio_2" => "",
            "detalle_envio_3" => "",
            "id_ciudad_origen" => $ciudadOrigen,
            "id_ciudad_destino" => $ciudadDestino,
            "id_destinatario_ne_cl" => "",
            "razon_social_desti_ne" =>  $razon_zocial_destinatario,
            "nombre_destinatario_ne" => $nombreDestino,
            "apellido_destinatar_ne" =>   "",
            "direccion1_destinat_ne" => $direccionDestino . " - Referencia: " . $referenciaDestino,
            "sector_destinat_ne" => "",
            "telefono1_destinat_ne" => $telefonoDestino,
            "telefono2_destinat_ne" => "",
            "codigo_postal_dest_ne" => "",
            "id_remitente_cl" => "001remi",
            "razon_social_remite" => $razon_social_remitente,
            "nombre_remitente" => $nombreOrigen,
            "apellido_remite" =>   "",
            "direccion1_remite" => $direccionOrigen,
            "sector_remite" => "",
            "telefono1_remite" => $telefonoOrigen,
            "telefono2_remite" => "",
            "codigo_postal_remi" => "",
            "id_producto" => 2,
            "contenido" => $contiene,
            "numero_piezas" => 1,
            "valor_mercancia" => $costo_producto,
            "valor_asegurado" => 0,
            "largo" => 2,
            "ancho" => 50,
            "alto" => 50,
            "peso_fisico" => 2,
            "login_creacion" => "integracion.api.1",
            "password" => "54321"

        );

        // Convertir los datos al formato JSON
        $jsonData = json_encode($data);

        // Inicializar cURL
        $ch = curl_init($url);

        // Configura opciones de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Ignora la verificación de SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        // Configurar las opciones de cURL para la solicitud POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Omitir la verificación de SSL si es necesario (no recomendado para producción)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si ocurrió algún error durante la solicitud
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        // Cerrar la sesión cURL
        curl_close($ch);

        // Mostrar la respuesta
        return $response;
    }

    public function obtenerNombre($codigo, $nombre)
    {
        // Definir la tabla y la condición base
        $table = "ciudad_cotizacion";
        $condition = ($nombre === "ciudad" || $nombre === "codigo_ciudad_servientrega" || $nombre === "codigo_ciudad_gintracom")
            ? "id_cotizacion = '$codigo'"
            : "codigo_provincia_laar = '$codigo' limit 1";

        // Construir la consulta SQL
        $sql = "SELECT $nombre FROM $table WHERE $condition";

        // Ejecutar la consulta y devolver el resultado
        $nombre = $this->select($sql);
        return $nombre;
    }

    //gintracom

    public function generarGintracom($nombreOrigen, $ciudadOrigen, $provinciaOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $provinciaDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $numero_factura, $monto_factura)
    {


        $recaudo = $cod == "1" ? true : false;

        preg_match_all('/(.*?)x(\d+)/', $contiene, $matches, PREG_SET_ORDER);

        $resultado_final = [];
        foreach ($matches as $match) {
            $nombreP = trim($match[1]);
            $cantidad = trim($match[2]);
            $resultado_final[] = "$cantidad * $nombreP";
        }

        $contiene = implode(" | ", $resultado_final);

        $url = "https://ec.gintracom.site/web/import-suite/pedido";
        $data = array(
            "remitente" => array(
                "nombre" => $nombreOrigen,
                "telefono" => $telefonoOrigen,
                "provincia" => $provinciaOrigen,
                "ciudad" => $ciudadOrigen,
                "direccion" => $direccionOrigen
            ),
            "destinatario" => array(
                "nombre" => $nombreDestino,
                "telefono" => $telefonoDestino,
                "provincia" => $provinciaDestino,
                "ciudad" => $ciudadDestino,
                "direccion" => $direccionDestino
            ),
            "cant_paquetes" => "1",
            "peso_total" => "2.00",
            "documento_venta" => $numero_factura,
            "observacion" => $_POST['observacion'],
            "contenido" => $contiene,
            "fecha" => date("Y-m-d H:i:s"),
            "declarado" => $monto_factura,
            "con_recaudo" => $recaudo
        );

        // Convertir los datos al formato JSON
        $jsonData = json_encode($data);

        // Inicializar cURL
        $ch = curl_init($url);

        // Configura opciones de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $usuario = "importsuite";
        $password = "ab5b809caf73b2c1abb0e4586a336c3a";

        $credenciales = base64_encode("$usuario:$password");
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic ' . $credenciales
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si ocurrió algún error durante la solicitud
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        // Cerrar la sesión cURL
        curl_close($ch);

        return $response;
    }

    //speed

    public function generarSpeed($nombreO, $ciudadOrigen, $direccionO, $telefonoO, $referenciaO, $nombre, $ciudadDestino, $direccion, $telefono, $celular, $referencia, $contiene, $fecha, $numero_factura, $plataforma, $observacion, $recaudo, $monto_factura)
    {
        $sql = "SELECT url_imporsuit FROM plataformas WHERE id_plataforma = '$plataforma'";
        $url = $this->select($sql);
        $url = $url[0]['url_imporsuit'];

        $url = "https://guias.imporsuitpro.com/Speed/crear";
        $data = array(
            "nombreO" => $nombreO,
            "ciudadO" => $ciudadOrigen,
            "direccionO" => $direccionO,
            "telefonoO" => $telefonoO,
            "referenciaO" => $referenciaO,
            "nombre" => $nombre,
            "ciudad" => $ciudadDestino,
            "direccion" => $direccion,
            "telefono" => $telefono,
            "referenciaD" => $referencia,
            "contiene" => $contiene,
            "fecha" => $fecha,
            "numero_factura" => $numero_factura,
            "url" => $url,
            "observacion" => $observacion,
            "recaudo" => $recaudo,
            "monto_factura" => $monto_factura
        );

        // Enviar los datos en formdata
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si ocurrió algún error durante la solicitud
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        // Cerrar la sesión cURL
        curl_close($ch);

        return $response;
    }
    public function aumentarMatriz()
    {
        $this->update("UPDATE matriz set guia_generadas = guia_generadas + 1 WHERE idmatriz = ?", array(MATRIZ));
    }
}
