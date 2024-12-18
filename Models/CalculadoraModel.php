<?php
class CalculadoraModel extends Query
{
    public function obtenerTarifas($ciudad, $provincia, $monto_factura, $recuado, $id_plataforma)
    {

        $select = $this->select("SELECT * FROM ciudad_cotizacion WHERE id_cotizacion = '$ciudad' ");
        $tarifas = [];
        $trayecto_laar = $select[0]['trayecto_laar'];
        $trayecto_servientrega = $select[0]['trayecto_servientrega'];
        $trayecto_gintracom = $select[0]['trayecto_gintracom'];
        $ciudad = $select[0]['ciudad'];
        $precio_laar = $this->select("SELECT * from cobertura_laar WHERE tipo_cobertura = '$trayecto_laar' ");
        $precio_servientrega = $this->select("SELECT * from cobertura_servientrega WHERE tipo_cobertura = '$trayecto_servientrega' ");
        $precio_gintracom = $this->select("SELECT * from cobertura_gintracom WHERE trayecto = '$trayecto_gintracom' ");

        if (empty($precio_laar[0]['precio'])) {
            $precio_laar[0]['precio'] = 0;
        } else {
            $tarifas['laar'] = $precio_laar[0]['precio'];
        }
        if (isset($precio_servientrega[0]['precio'])) {
            if ($precio_servientrega[0]['precio'] == null || empty($precio_servientrega[0]['precio'])) {
                $precio_servientrega[0]['precio'] = 0;
            } else {
                $tarifas['servientrega'] = $precio_servientrega[0]['precio'];
            }
        }
        if (isset($precio_gintracom[0]['precio'])) {

            if ($precio_gintracom[0]['precio'] == null || empty($precio_gintracom[0]['precio'])) {
                $precio_gintracom[0]['precio'] = 0;
            } else {
                $tarifas['gintracom'] = $precio_gintracom[0]['precio'];
            }
        }

        $matriz = $this->obtenerMatriz();
        $matriz = $matriz[0]["idmatriz"];

        $previo = $monto_factura * 0.03;
        if ($trayecto_laar === "0" || $trayecto_laar === null) {
            $tarifas['laar'] = 0;
        } else {
            if ($recuado === "1") {

                $tarifas['laar'] = $tarifas['laar'] + $previo;
                if ($matriz == 2) {
                    $tarifas['laar'] = 5.99;
                }
                if ($id_plataforma == 3031) {
                    $tarifas['laar'] = 5.49;
                }
            } else {
                $tarifas['laar'] = $tarifas['laar'];
                if ($matriz == 2) {
                    $tarifas['laar'] = 5.99;
                }

                if ($id_plataforma == 3031) {
                    $tarifas['laar'] = 5.49;
                }
            }
        }
        if ($trayecto_gintracom === "0" || $trayecto_gintracom === null || empty($trayecto_gintracom)) {
            $tarifas['gintracom'] = 0;
        } else {
            if ($recuado === "1") {
                $tarifas['gintracom'] = $tarifas['gintracom'] + $previo;
                if ($id_plataforma == 1206) {
                    $tarifas['gintracom'] = $tarifas['gintracom'] - 0.5;
                }

                if ($id_plataforma == 3031) {
                    $tarifas['gintracom'] = $tarifas['gintracom'] - 0.3;
                }
            } else {
                $tarifas['gintracom'] = $tarifas['gintracom'];
                if ($id_plataforma == 1206) {
                    $tarifas['gintracom'] = $tarifas['gintracom'] - 0.5;
                }
                if ($id_plataforma == 3031) {
                    $tarifas['gintracom'] = $tarifas['gintracom'] - 0.3;
                }
            }
        }
        if ($previo < 1.35) {
            $previo = 1.35;
        }
        if ($trayecto_servientrega === "0" || $trayecto_servientrega === null || empty($trayecto_servientrega)) {
            $tarifas['servientrega'] = 0;
        } else {
            if ($recuado === "1") {
                $tarifas['servientrega'] = $tarifas['servientrega'] + $previo;
                if ($id_plataforma == 3031) {
                    $tarifas['servientrega'] = $tarifas['servientrega'] - 0.3;
                }
            } else {
                if ($ciudad == "QUITO") {
                    $tarifas['servientrega'] = 4.97;
                } else {
                    $tarifas['servientrega'] = $tarifas['servientrega'];
                    if ($id_plataforma == 3031) {
                        $tarifas['servientrega'] = $tarifas['servientrega'] - 0.3;
                    }
                }
            }
        }


        if ($ciudad == "QUITO") {
            $tarifas["speed"] = 5.5;
        } else if ($ciudad == "VALLE DE LOS CHILLOS") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "CUMBAYA") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "TUMBACO") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "SANGOLQUI") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "SAN RAFAEL") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "CONOCOTO") {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "GUAYAQUIL" && $id_plataforma == 1206) {
            $tarifas["speed"] = 5.5;
        } else if ($ciudad == "DAULE" && $id_plataforma == 1206) {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "SAMBORONDON" && $id_plataforma == 1206) {
            $tarifas["speed"] = 6.5;
        } else if ($ciudad == "LA PUNTILLA/GUAYAS" && $id_plataforma == 1206) {
            $tarifas["speed"] = 6.5;
        } else {
            $tarifas["speed"] = 0;
        }
        if ($id_plataforma == 3031 && $tarifas['speed'] > 0) {
            $tarifas['speed'] = $tarifas['speed'] - 0.3;
        }
        $tarifas['laar'] = number_format($tarifas['laar'], 2, '.', '');
        $tarifas['servientrega'] = number_format($tarifas['servientrega'], 2, '.', '');
        $tarifas['gintracom'] = number_format($tarifas['gintracom'], 2, '.', '');

        return $tarifas;
    }


    public function saldo($plataforma)
    {
        $billetera = $this->select("SELECT * FROM billeteras WHERE id_plataforma = '$plataforma'");
        if (empty($billetera)) {
            $saldo = 0;
        } else {
            $saldo = $billetera[0]['saldo'];
            $solicitado = $billetera[0]['solicito'];
            if ($solicitado > 0) {
                $saldo = $saldo - $billetera[0]['valor_solicitud'];
            }
        }
        return $saldo;
    }

    public function login($user, $password, $company)
    {
        $url = "https://api-e.figgoapp.com/api/v1/admin/login";
        $data = array(
            "username" => $user,
            "password" => $password,
            "companyPid" => $company
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        // Decodifica el JSON
        $decoded = json_decode($response, true);

        // Extrae el token
        $token = isset($decoded['token']) ? $decoded['token'] : null;

        // Retorna el token (o puedes retornarlo dentro de un array si lo prefieres)
        return $token;
    }

    public function obtenerNombre($codigo, $nombre)
    {
        if ($nombre == "ciudad") {
            $sql = "SELECT $nombre FROM ciudad_cotizacion WHERE id_cotizacion = '$codigo'";
        } else {
            $sql = "SELECT $nombre FROM ciudad_cotizacion WHERE codigo_provincia_laar = '$codigo' limit 1";
        }

        $nombre = $this->select($sql);
        return $nombre;
    }

    public function calcularGuiaDirecta($id_producto, $total, $tarifa, $id_plataforma, $costo)
    {
        // transforma todo a float
        $total = (float) $total;
        $tarifa = (float) $tarifa;
        $costo = (float) $costo;


        $producto = $this->select("SELECT * FROM productos WHERE id_producto = '$id_producto'");
        $full = $this->obtenerFull($producto[0], $id_plataforma);
        $plataforma = $producto[0]['id_plataforma'];
        if ($id_plataforma == $plataforma) {
            $costo = 0;
        }
        $resultante = $total - $costo - $tarifa - $full;
        if ($resultante < 0) {
        } else {
            $resultante = $resultante;
        }



        if ($resultante <= 0) {
            $generar = false;
        } else {
            $generar = true;
        }

        // $inventario = $this->select("SELECT * FROM inventario_bodegas WHERE id_producto = '$id_producto';");


        $data = [
            "total" => number_format($total, 2, '.', ''),
            "tarifa" => number_format($tarifa, 2, '.', ''),
            "costo" => number_format($costo, 2, '.', ''),
            "resultante" => number_format($resultante, 2, '.', ''),
            "generar" => $generar,
            "full" => $full
        ];
        return $data;
    }

    public function obtenerFull($producto, $plataforma)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_producto = '" . $producto['id_producto'] . "' limit 1";
        $fulls = $this->select($sql);

        $bodega = $fulls[0]['bodega'];
        $id_plataforma = $producto['id_plataforma'];

        $sql = "SELECT * FROM bodega WHERE id = '$bodega' limit 1";
        $respuesta = $this->select($sql);
        $id_bodega = $respuesta[0]['id_plataforma'];
        $full = $respuesta[0]['full_filme'];

        if ($producto['id_plataforma']  == $id_bodega) {
            $full = 0;
        } else
        if ($id_bodega == $id_plataforma) {
            $full = 0;
        } else if ($plataforma == $producto['id_plataforma']) {
            $full = $full;
        } else {
            $full = 0;
        }

        return $full;
    }
}
