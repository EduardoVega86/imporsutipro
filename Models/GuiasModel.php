<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

class GuiasModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function buscarStock($numero_factura)
    {
        $sql = "SELECT * FROM detalle_fact_cot WHERE  numero_factura = '$numero_factura'";

        $response = $this->select($sql);

        foreach ($response as $key => $value) {
            $id_inventario = $value['id_inventario'];
            $cantidad = $value['cantidad'];
            $stock = $this->stock_actual($id_inventario);
            if ($stock < $cantidad) {
                return false;
            }
        }
        return true;
    }

    public function stock_actual($id_inventario)
    {
        $sql = "SELECT saldo_stock FROM inventario_bodegas WHERE id_inventario = '$id_inventario'";
        $response = $this->select($sql);
        return $response[0]['saldo_stock'];
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
            "costoflete" => 0,
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

    public function existeGuia($guia)
    {
        $sql = "SELECT * FROM facturas_cot WHERE numero_guia = ?";
        $response = $this->simple_select($sql, array($guia));
        if ($response > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function ultimaguia()
    {
        $prefijo = PREFIJOS;
        // Iniciar una transacción
        $this->beginTransaction();

        try {
            // Bloquear la tabla para evitar conflictos
            $sql = "SELECT MAX(numero_guia) as numero_guia FROM facturas_cot WHERE numero_guia LIKE '$prefijo%' FOR UPDATE";
            $numero_guia = $this->select($sql);
            $numero_guia = $numero_guia[0]['numero_guia'];

            if ($numero_guia == null || empty($numero_guia)) {
                $numero_guia = $prefijo . "000001";
            } else {
                $numero_guia = $this->incrementarGuia($numero_guia);
            }

            // Actualizar la cantidad de guías generadas
            $response = $this->update("UPDATE matriz SET guia_generadas = guia_generadas + 1 WHERE idmatriz = ?", array(MATRIZ));

            // Confirmar la transacción
            $this->commit();

            return $numero_guia;
        } catch (Exception $e) {
            // En caso de error, revertir la transacción
            $this->rollBack();
            throw $e; // Re-lanzar la excepción para manejarla fuera
        }
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

    public function actualizarGuia($numero_factura, $guia, $nombreDestino, $ciudadDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $cod, $costo_producto, $comentario, $usuario, $calle_principal, $calle_secundaria, $contiene, $provincia, $costo_flete, $transp, $estado_guia)
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
        $fecha_guia = date("Y-m-d H:i:s");
        $sql =  "UPDATE `facturas_cot` SET `id_usuario`=?,`monto_factura`=?,`nombre`=?,`telefono`=?,`provincia`=?,`c_principal`=?,`ciudad_cot`=?,`c_secundaria`=?,`referencia`=?,`observacion`=?,`guia_enviada`=1,`transporte`='$transp',`celular`=?,`estado_guia_sistema`=$estado_guia,`numero_guia`=?,`cod`=?,`contiene`=?,`comentario`=?,`id_transporte`='$id_transporte', `costo_flete` =$costo_flete, `fecha_guia` = '$fecha_guia' WHERE `numero_factura`=?";
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
        $cantidad = $respueta_detalle[0]['cantidad'];



        $buscar_inventario = "SELECT * FROM inventario_bodegas WHERE id_inventario = '$id_inventario'";
        $inventario = $this->select($buscar_inventario);
        $id_bodega = $inventario[0]['bodega'];
        $id_producto = $inventario[0]['id_producto'];

        $buscar_bodega = "SELECT * FROM bodega WHERE id = '$id_bodega'";
        $bodega = $this->select($buscar_bodega);
        $id_plataforma_bodega = $bodega[0]['id_plataforma'];
        $valor_full = $bodega[0]['full_filme'];

        $existe_full = 0;
        if ($valor_full > 0) {
            $existe_full = 1;
        }

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


        $costo = $this->select("SELECT costo_producto FROM facturas_cot WHERE numero_factura = '$numero_factura'");
        $costo_o = $costo[0]['costo_producto'];

        if ($id_plataforma == $id_plataforma_producto) {
            $costo_o = 0;
        }


        if ($tienda_venta == $proveedor) {
            $proveedor = null;
            $id_plataforma_producto = null;
        }


        $costo_producto = $cod == 1 ? $costo_producto : 0;

        $monto_recibir = $costo_producto - $precio_envio - $full - $costo_o;


        //buscar si es referido 
        $buscar_referido = "SELECT * FROM plataformas WHERE id_plataforma = '$id_plataforma'";
        $refiere = $this->select($buscar_referido);
        $id_referido = $refiere[0]['refiere'];
        if (!empty($id_referido) && $id_referido != null) {
            $id_referido = $id_referido;
        } else {
            $id_referido = 0;
        }

        if ($existe_full == 1) {
        } else {
            $id_plataforma_bodega = 0;
        }

        /*  if ($id_plataforma_bodega != $id_plataforma_producto) {
            $this->notificarGuia($id_plataforma_producto, $numero_factura, $guia);
            if ($existe_full == 1) {
                $this->notificarGuia($id_plataforma_bodega, $numero_factura, $guia);
            }
        }
 */

        $insert_wallet = "INSERT INTO cabecera_cuenta_pagar (numero_factura, fecha, cliente, tienda, proveedor, estado_guia, total_venta, costo, precio_envio, monto_recibir, valor_cobrado, valor_pendiente, full, guia, cod, id_matriz, id_plataforma, id_proveedor, id_full, id_referido) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = array($numero_factura, $fecha, $nombreDestino, $tienda_venta, $proveedor, $estado, $costo_producto, $costo_o, $precio_envio, $monto_recibir, 0, $monto_recibir, $full, $guia, $cod, $id_matriz, $id_plataforma, $id_plataforma_producto, $id_plataforma_bodega, $id_referido);
        $response = $this->insert($insert_wallet, $data);
    }

    public function notificarGuia($id_plataforma, $numero_factura, $guia)
    {
        $buscar_plataforma = "SELECT * FROM plataformas WHERE id_plataforma = '$id_plataforma'";
        $plataforma = $this->select($buscar_plataforma);
        $correo = $plataforma[0]['email'];

        require_once 'PHPMailer/Mail_guia.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = $smtp_debug;
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_pass;
        $mail->Port = 465;
        $mail->SMTPSecure = $smtp_secure;
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($smtp_from, $smtp_from_name);
        $mail->addAddress($correo);
        $mail->Subject = 'Generación de guía';
        $mail->Body = $message_body;


        if (!$mail->send()) {
            /*     echo 'El mensaje no pudo ser enviado.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
         */
        } else {
            //    echo 'El mensaje ha sido enviado';
        }
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
            "ID_REMITENTE_CL" => "",
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
            "LOGIN_CREACION" => "imp.1793168264001",
            "PASSWORD" => "Ecuador24",
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
            "id_remitente_cl" => "",
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
            "login_creacion" => "imp.1793168264001",
            "password" => "Ecuador24"

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
        /* echo $sql; */
        $nombre = $this->select($sql);
        return $nombre;
    }

    public function anularServi_temporal($id)
    {
        $sql = "UPDATE `facturas_cot` SET  `estado_guia_sistema` = ?, `anulada` = ? WHERE `numero_guia` = ? ";
        $data = [8, 1, $id];
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

    public function anularSpeed_temporal($id)
    {
        $sql = "UPDATE `facturas_cot` SET  `estado_guia_sistema` = ?, `anulada` = ? WHERE `numero_guia` = ? ";
        $data = [101, 1, $id];
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

    //gintracom

    public function generarGintracom($nombreOrigen, $ciudadOrigen, $provinciaOrigen, $direccionOrigen, $telefonoOrigen, $referenciaOrigen, $celularOrigen, $nombreDestino, $ciudadDestino, $provinciaDestino, $direccionDestino, $telefonoDestino, $celularDestino, $referenciaDestino, $postal, $identificacion, $contiene, $peso, $valor_seguro, $valor_declarado, $tamanio, $cod, $costoflete, $costo_producto, $tipo_cobro, $comentario, $fecha, $extras, $numero_factura, $monto_factura)
    {


        $recaudo = $cod == "1" ? true : false;

        preg_match_all('/(.*?)X(\d+)/', $contiene, $matches, PREG_SET_ORDER);

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
            "con_recaudo" => $recaudo,
            "apertura" => false,
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

    public function generarSpeed($nombreO, $ciudadOrigen, $direccionO, $telefonoO, $referenciaO, $nombre, $ciudadDestino, $direccion, $telefono, $celular, $referencia, $contiene, $fecha, $numero_factura, $plataforma, $observacion, $recaudo, $monto_factura, $matriz)
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
            "monto_factura" => $monto_factura,
            "matriz" => $matriz
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
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        }

        // Cerrar la sesión cURL
        curl_close($ch);

        return $response;
    }
    public function aumentarMatriz()
    {
        $this->update("UPDATE matriz set guia_generadas = guia_generadas + 1 WHERE idmatriz = ?", array(MATRIZ));
    }

    public function descargarGuia($guia)
    {
        $url = "";
        if (str_contains($guia, "IMP") || str_contains($guia, "MKP") || str_contains($guia, "RCK")) {
            $url = "https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=$guia";
        } else if (is_numeric($guia)) {
            $url = "https://guias.imporsuitpro.com/Servientrega/guia/$guia";
        }
        // Inicializar cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones si las hay
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar la verificación SSL si es necesario

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
            curl_close($ch);
            return false;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            echo "Error al descargar la guía, código de respuesta HTTP: $httpCode";
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        // Definir la ruta donde se guardará la guía
        $rutaCarpeta = 'public/repositorio/guias/';
        $nombreArchivo = "guia_$guia.pdf";
        $rutaCompleta = $rutaCarpeta . $nombreArchivo;

        // Asegurarse de que la carpeta existe
        if (!file_exists($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0777, true);
        }

        // Guardar el archivo en el servidor
        file_put_contents($rutaCompleta, $response);

        // Verificar si se guardó correctamente
        if (file_exists($rutaCompleta)) {
            return $rutaCompleta; // Devuelve la ruta completa del archivo guardado
        } else {
            echo "Error al guardar la guía en el servidor.";
            return false;
        }
    }

    public function pesosLaar()
    {
        $sql = "SELECT * FROM cabecera_cuenta_pagar where peso is null and (guia like 'IMP%' or guia like 'MKP%') ORDER BY `cabecera_cuenta_pagar`.`guia` ASC";

        $response = $this->select($sql);

        foreach ($response as $key => $value) {
            $ch = curl_init("https://api.laarcourier.com:9727/guias/" . $value['guia']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $result = json_decode($result, true);
            $peso = $result['pesoKilos'];
            $xd =    $this->update("UPDATE cabecera_cuenta_pagar set peso = ? where guia = ?", array($peso, $value['guia']));

            print_r($xd);

            if (curl_errno($ch)) {
                echo 'Error en la solicitud cURL: ' . curl_error($ch);
            }
            curl_close($ch);
        }
    }

    public function getIdFactura($numero_factura)
    {
        $sql = "SELECT id_factura FROM facturas_cot WHERE numero_factura = ?";
        $response = $this->select($sql, array($numero_factura));
        return $response[0]['id_factura'];
    }
}
