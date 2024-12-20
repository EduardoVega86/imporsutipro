<?php
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

use PHPMailer\PHPMailer\PHPMailer;

class GestionModel extends Query
{
    public function capturador($json)
    {
        $this->insert("INSERT INTO laar (json) VALUES (?)", [$json]);
    }

    public function fix()
    {
        $sql = "WITH gw_data AS (
  SELECT 
    JSON_EXTRACT(valor, '$.data[0].guia') AS numero_guia,
    CAST(JSON_EXTRACT(valor, '$.data[0].estado') AS UNSIGNED) AS estado,
    valor
  FROM gintracom_webhook
  WHERE CAST(JSON_EXTRACT(valor, '$.data[0].estado') AS UNSIGNED) BETWEEN 2 AND 9
),
max_estados AS (
  SELECT 
    numero_guia, estado, valor,
    ROW_NUMBER() OVER (PARTITION BY numero_guia ORDER BY estado DESC) AS rn
  FROM gw_data
)
SELECT 
    fc.numero_guia,
    me.estado AS estado_webhook,
    fc.estado_guia_sistema AS estado_facturas,
    me.valor,
    CASE
        WHEN me.estado IS NULL THEN 'Sin estado en webhook'
        WHEN me.estado != fc.estado_guia_sistema THEN 'Inconsistencia'
        ELSE 'Correcto'
    END AS resultado
FROM facturas_cot fc
LEFT JOIN max_estados me ON fc.numero_guia = me.numero_guia AND me.rn = 1
WHERE fc.numero_guia LIKE 'I%' 
  AND fc.numero_guia IN (
    'I000003245', 'I000003248', 'I000003253', 'I000003255', 'I000003256', 
    'I000003257', 'I000003258', 'I000003259', 'I000003260', 'I000003261', 
    'I000003262', 'I000003263', 'I000003265', 'I000003266', 'I000003267', 
    'I000003268', 'I000003270', 'I000003271', 'I000003272', 'I000003273', 
    'I000003274', 'I000003275', 'I000003276', 'I000003277', 'I000003279', 
    'I000003280', 'I000003281', 'I000003282', 'I000003283', 'I000003284', 
    'I000003285', 'I000003286', 'I000003287', 'I000003288', 'I000003289', 
    'I000003290', 'I000003291', 'I000003292', 'I000003293', 'I000003294', 
    'I000003295', 'I000003296', 'I000003297', 'I000003298', 'I000003299', 
    'I000003300', 'I000003301', 'I000003302', 'I000003304', 'I000003305', 
    'I000003306', 'I000003307', 'I000003308', 'I000003309', 'I000003311', 
    'I000003312', 'I000003314', 'I000003315', 'I000003316', 'I000003317', 
    'I000003318', 'I000003319', 'I000003320', 'I000003321', 'I000003322', 
    'I000003323', 'I000003324', 'I000003325', 'I000003326', 'I000003329', 
    'I000003330', 'I000003331', 'I000003332', 'I000003333', 'I000003335', 
    'I000003336', 'I000003337', 'I000003338', 'I000003339', 'I000003340', 
    'I000003342', 'I000003343', 'I000003344', 'I000003345', 'I000003346', 
    'I000003349', 'I000003350', 'I000003351', 'I000003352', 'I000003353', 
    'I000003354', 'I000003355', 'I000003356', 'I000003357', 'I000003367', 
    'I000003368', 'I000003369', 'I000003370', 'I000003371', 'I000003372', 
    'I000003374', 'I000003375', 'I000003376', 'I000003377', 'I000003378', 
    'I000003379', 'I000003380', 'I000003382', 'I000003383', 'I000003384', 
    'I000003385', 'I000003386', 'I000003388', 'I000003389', 'I000003390', 
    'I000003391', 'I000003392', 'I000003393', 'I000003395', 'I000003396', 
    'I000003397', 'I000003398', 'I000003399', 'I000003400', 'I000003402', 
    'I000003404', 'I000003405', 'I000003406', 'I000003408', 'I000003411', 
    'I000003412', 'I000003413', 'I000003417', 'I000003420', 'I000003421', 
    'I000003422', 'I000003423', 'I000003424', 'I000003426', 'I000003427', 
    'I000003428', 'I000003429', 'I000003431', 'I000003433', 'I000003436', 
    'I000003437', 'I000003438', 'I000003439', 'I000003440', 'I000003441', 
    'I000003442', 'I000003443'
)
GROUP BY fc.numero_guia;";
        $response = $this->select($sql);
        return $response;
    }

    public function actualizarEstado($estado, $guia)
    {
        $sql = "UPDATE facturas_cot set estado_guia_sistema = '$estado' WHERE numero_guia = '$guia' ";
        $response =  $this->select($sql);
        $update = "UPDATE cabecera_cuenta_pagar set estado_guia = '$estado' WHERE guia = '$guia' ";
        $response =  $this->select($update);
    }

    public function entregada($estado, $guia)
    {
        $datos = "SELECT * FROM facturas_cot WHERE numero_guia = '$guia' ";
        $select = $this->select($datos);
        $data_factura = $select[0];
    }

    public function EnviarWalletEntrega($estado, $guia)
    {
        $datos = "SELECT * FROM facturas_cot WHERE numero_guia = '$guia' ";
        $select = $this->select($datos);
    }

    public function notificar($novedades, $guia)
    {
        $id_plataforma = $this->select("SELECT id_plataforma FROM facturas_cot WHERE numero_guia = '$guia' ")[0]['id_plataforma'];
        echo $id_plataforma;


        $avisar = false;
        $nombre = "";
        $sql = "SELECT * FROM facturas_cot WHERE numero_guia = '$guia' ";
        $response = $this->select($sql);
        $nombreC = $response[0]['nombre'];

        foreach ($novedades as $novedad) {
            if ($novedad['codigoTipoNovedad'] == 42 || $novedad['codigoTipoNovedad'] == 43 || $novedad['codigoTipoNovedad'] == 92 || $novedad['codigoTipoNovedad'] == 96) {
                $avisar = false;
                break;
            }

            $sql = "SELECT * FROM detalle_novedad WHERE guia_novedad = '$guia' AND codigo_novedad = '" . $novedad['codigoTipoNovedad'] . "' ";
            $response = $this->select($sql);
            print_r($response);

            if (count($response) == 0) {
                echo "entre";

                $avisar = true;
                $codigo = $novedad["codigoTipoNovedad"];
                $nombre = $novedad['nombreDetalleNovedad'];
                $detalle = $novedad['nombreTipoNovedad'];
                $observacion = $novedad['observacion'];

                $response = $this->insert("INSERT INTO detalle_novedad (codigo_novedad, guia_novedad, nombre_novedad, detalle_novedad, observacion, id_plataforma) VALUES (?, ?, ?, ?, ?, ?)", [$codigo, $guia, $nombre, $detalle, $observacion, $id_plataforma]);
                print_r($response);
            }
        }
        echo $avisar;
        if ($avisar) {

            $sql = "INSERT INTO novedades (guia_novedad, cliente_novedad, estado_novedad, novedad, tracking, fecha, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if (strpos($guia, 'IMP') == 0) {
                $tracking = "https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=" . $guia;
            } else if (strpos($guia, 'I00') == 0) {
                $tracking = "https://ec.gintracom.site/web/site/tracking";
            } else if (is_numeric($guia)) {
                $tracking = "https://www.servientrega.com.ec/Tracking/?guia=" . $guia . "&tipo=GUI";
            }
            $response = $this->insert($sql, [$guia, $nombreC, $codigo, $detalle, $tracking, $novedad["fechaNovedad"], $id_plataforma]);
            print_r($response);
            if ($avisar) {
                //$this->enviarCorreo($guia);
            }
        } else {
            echo "No hay novedades";
        }
    }

    public function enviarCorreo($guia)
    {
        $datos = "SELECT * FROM facturas_cot WHERE numero_guia = '$guia' ";
        $select = $this->select($datos);
        $data_factura = $select[0];
        $id_plataforma = $data_factura['id_plataforma'];
        $id_usuario = $data_factura['id_usuario'];

        $datos = "SELECT * FROM users WHERE id_users = '$id_usuario' ";
        $select = $this->select($datos);
        $data_usuario = $select[0];
        $correo = $data_usuario['email_users'];

        require_once 'PHPMailer/Mail_devolucion.php';
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
        $mail->Subject = 'Novedad de pedido en Imporsuitpro';
        $mail->Body = $message_body_pedido;
        // $this->crearSubdominio($tienda);

        if ($mail->send()) {
            echo "Correo enviado";
        } else {
            //  echo "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    }
    public function masivo()
    {
        $sql = "SELECT * FROM facturas_cot WHERE numero_guia like 'IMP%' and estado_guia_sistema != 8 ORDER BY `numero_factura` DESC";
        $guias = $this->select($sql);

        // Procesar en lotes
        $batch_size = 10; // Tamaño del lote
        $delay = 1; // Retraso en segundos entre lotes
        $total_guias = count($guias);
        $batches = ceil($total_guias / $batch_size);

        for ($i = 0; $i < $batches; $i++) {
            $batch_guias = array_slice($guias, $i * $batch_size, $batch_size);
            foreach ($batch_guias as $guia) {
                $this->verificar($guia['numero_guia']);
            }
            // Añadir un retraso entre lotes
            sleep($delay);
        }
    }

    public function verificar($guia)
    {
        // Inicializar cURL para la primera solicitud
        $ch = curl_init("https://api.laarcourier.com:9727/guias/" . $guia);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            // Manejar errores en la solicitud
            $error_msg = curl_error($ch);
            curl_close($ch);
            echo "Error en la solicitud: $error_msg";
            return;
        }

        // Inicializar cURL para la segunda solicitud
        $url = "https://new.imporsuitpro.com/gestion/laar";
        $ch2 = curl_init($url);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

        $response2 = curl_exec($ch2);

        if ($response2 === false) {
            // Manejar errores en la segunda solicitud
            $error_msg2 = curl_error($ch2);
            curl_close($ch2);
            curl_close($ch);
            echo "Error en la segunda solicitud: $error_msg2";
            return;
        }

        curl_close($ch2);
        curl_close($ch);

        echo $response2;
    }
}
