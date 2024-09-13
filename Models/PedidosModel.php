<?php
class PedidosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cargarPedidosIngresados($plataforma)
    {

        $sql = "SELECT * FROM facturas_cot where numero_guia = '' or numero_guia is null and anulada = 0 and id_plataforma = '$plataforma'  ORDER BY numero_factura DESC";
        return $this->select($sql);
    }

    public function cargarGuias($plataforma, $fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $despachos)
    {
        $sql = "SELECT 
        fc.*, 
        fc.id_plataforma AS tienda_venta, 
        fc.id_propietario AS proveedor,
        cc.ciudad, 
        cc.provincia AS provinciaa, 
        p.url_imporsuit AS plataforma,
        pp.url_imporsuit AS proveedor_plataforma, 
        b.nombre AS nombre_bodega, 
        b.direccion AS direccion_bodega
            FROM 
                facturas_cot fc
            LEFT JOIN 
                ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot
            LEFT JOIN 
                plataformas p ON p.id_plataforma = fc.id_plataforma
            LEFT JOIN 
                plataformas pp ON pp.id_plataforma = fc.id_propietario 
            LEFT JOIN 
                bodega b ON b.id = fc.id_bodega
            WHERE 
                TRIM(fc.numero_guia) <> '' 
                AND fc.numero_guia IS NOT NULL 
                AND fc.numero_guia <> '0' 
                AND fc.anulada = 0  
                AND (fc.id_plataforma = $plataforma OR fc.id_propietario = $plataforma OR b.id_plataforma = $plataforma)
            ";

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND fecha_factura BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }

        if (!empty($transportadora)) {
            $sql .= " AND transporte = '$transportadora'";
        }

        if (!empty($estado)) {
            $sql .= " AND ($estado)";
        }

        if ($drogshipin == 0 || $drogshipin == 1) {
            $sql .= " AND drogshipin = $drogshipin";
        }

        //echo $impreso;
        if ($impreso !== null && $impreso !== '') {

            if ($impreso == 0 || $impreso == 1) {
                $sql .= " AND impreso = '$impreso'";
            }
        }

        if ($despachos !== null && $despachos !== '') {

            if ($despachos == 1 || $despachos == 2) {
                $sql .= " AND estado_factura = '$despachos'";
            }
        }


        $sql .= " ORDER BY fc.numero_factura DESC;";
        // echo $sql;
        return $this->select($sql);
    }


    public function cargarGuiasAnuladas($plataforma, $fecha_inicio, $fecha_fin, $transportadora)
    {
        $sql = "SELECT 
            fc.*, 
            fc.id_plataforma AS tienda_venta, 
            fc.id_propietario AS proveedor,
            cc.ciudad, 
            cc.provincia AS provinciaa, 
            p.url_imporsuit AS plataforma,
            pp.url_imporsuit AS proveedor_plataforma,
            b.nombre AS nombre_bodega, 
            b.direccion AS direccion_bodega
                FROM 
                    facturas_cot fc
                LEFT JOIN 
                    ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot
                LEFT JOIN 
                    plataformas p ON p.id_plataforma = fc.id_plataforma
                LEFT JOIN 
                    plataformas pp ON pp.id_plataforma = fc.id_propietario
                LEFT JOIN 
                    bodega b ON b.id = fc.id_bodega
                WHERE 
            TRIM(fc.numero_guia) <> '' 
            AND fc.numero_guia IS NOT NULL 
            AND fc.numero_guia <> '0' 
            AND fc.anulada = 1  
            AND (fc.id_plataforma = $plataforma OR fc.id_propietario = $plataforma OR b.id_plataforma = $plataforma)
        ";
        //echo $sql;
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND fecha_factura BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }

        if (!empty($transportadora)) {
            $sql .= " AND transportadora = '$transportadora'";
        }

        $sql .= " ORDER BY fc.numero_factura DESC;";

        return $this->select($sql);
    }

    public function cargarGuiasAnuladas_admin($fecha_inicio, $fecha_fin, $transportadora)
    {
        $sql = "SELECT 
                fc.*, 
                fc.id_plataforma AS tienda_venta, 
                fc.id_propietario AS proveedor,
                cc.ciudad, 
                cc.provincia AS provinciaa, 
                p.nombre_tienda AS tienda,
                b.nombre AS nombre_bodega, 
                b.direccion AS direccion_bodega,
                tp.nombre_tienda AS nombre_proveedor
            FROM 
                facturas_cot fc
            LEFT JOIN 
                ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot
            LEFT JOIN 
                plataformas p ON p.id_plataforma = fc.id_plataforma
            LEFT JOIN 
                plataformas tp ON tp.id_plataforma = fc.id_propietario
            LEFT JOIN 
                bodega b ON b.id = fc.id_bodega
            WHERE 
                TRIM(fc.numero_guia) <> '' 
                AND fc.numero_guia IS NOT NULL 
                AND fc.numero_guia <> '0' 
                AND fc.anulada = 1 ";

        $params = [];

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND fecha_factura BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
        }

        if (!empty($transportadora)) {
            $sql .= " AND transportadora = ?";
            $params[] = $transportadora;
        }

        // Mueve la cláusula ORDER BY al final de la consulta
        $sql .= " ORDER BY fc.numero_factura DESC;";

        //echo $sql;
        return $this->select($sql, $params);
    }

    public function cargarGuiasAdministrador($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $despachos)
    {
        $sql = "SELECT 
            fc.*, 
            fc.id_plataforma AS tienda_venta, 
            fc.id_propietario AS proveedor,
            cc.ciudad, 
            cc.provincia AS provinciaa, 
            p.nombre_tienda AS tienda,
            b.nombre AS nombre_bodega, 
            b.direccion AS direccion_bodega,
            tp.nombre_tienda AS nombre_proveedor
        FROM 
            facturas_cot fc
        LEFT JOIN 
            ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot
        LEFT JOIN 
            plataformas p ON p.id_plataforma = fc.id_plataforma
        LEFT JOIN 
            plataformas tp ON tp.id_plataforma = fc.id_propietario
        LEFT JOIN 
            bodega b ON b.id = fc.id_bodega
        WHERE 
            TRIM(fc.numero_guia) <> '' 
            AND fc.numero_guia IS NOT NULL 
            AND fc.numero_guia <> '0' 
            AND fc.anulada = 0 ";


        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND fecha_factura BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }

        if (!empty($transportadora)) {
            $sql .= " AND transporte = '$transportadora'";
        }

        if (!empty($estado)) {
            $sql .= " AND ($estado)";
        }

        if ($drogshipin == 0 || $drogshipin == 1) {
            $sql .= " AND drogshipin = $drogshipin";
        }

        if ($impreso == 0 || $impreso == 1) {
            $sql .= " AND impreso = $impreso";
        }

        if ($despachos !== null && $despachos !== '') {

            if ($despachos == 1 || $despachos == 2) {
                $sql .= " AND estado_factura = '$despachos'";
            }
        }

        // Mueve la cláusula ORDER BY al final de la consulta
        $sql .= " ORDER BY fc.numero_factura DESC";


        //$sql .= " LIMIT $inicio, $final";


        //echo $sql;
        return $this->select($sql);
    }

    public function cargarGuiasAdministrador2($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $start, $length)
    {
        $sql = "SELECT 
            fc.*, 
            fc.id_plataforma AS tienda_venta, 
            fc.id_propietario AS proveedor,
            cc.ciudad, 
            cc.provincia AS provinciaa, 
            p.nombre_tienda AS tienda,
            b.nombre AS nombre_bodega, 
            b.direccion AS direccion_bodega,
            tp.nombre_tienda AS nombre_proveedor
        FROM 
            facturas_cot fc
        LEFT JOIN 
            ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot
        LEFT JOIN 
            plataformas p ON p.id_plataforma = fc.id_plataforma
        LEFT JOIN 
            plataformas tp ON tp.id_plataforma = fc.id_propietario
        LEFT JOIN 
            bodega b ON b.id = fc.id_bodega
        WHERE 
            TRIM(fc.numero_guia) <> '' 
            AND fc.numero_guia IS NOT NULL 
            AND fc.numero_guia <> '0' 
            AND fc.anulada = 0";

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND fecha_factura BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }

        if (!empty($transportadora)) {
            $sql .= " AND transporte = '$transportadora'";
        }

        if (!empty($estado)) {
            $sql .= " AND ($estado)";
        }

        if ($drogshipin == 0 || $drogshipin == 1) {
            $sql .= " AND drogshipin = $drogshipin";
        }

        if ($impreso == 0 || $impreso == 1) {
            $sql .= " AND impreso = $impreso";
        }

        // Añadir paginación
        $sql .= " ORDER BY fc.numero_factura DESC LIMIT $start, $length";


        $response =  $this->select($sql);

        // añadir detalle con configuracion de html
        foreach ($response as $key => $value) {
            $response[$key]['detalle'] = '<div><button type="button" class="btn btn-sm btn-outline-primary"  onclick="ver_detalle_cot(' . $value['id_factura'] . ')">Ver detalle</button> <span> ' . $value['fecha_factura'] . '</span></div>';
            $response[$key]['checkbox'] = '<input type="checkbox" class="selectCheckbox" data-id="' . $value['id_factura'] . '" >';
            $response[$key]['cliente'] = '<div><strong>' . $value['nombre'] . '</strong></br><span>' . $value['c_principal'] .  ' ' . $value['c_secundaria']  . '</span></br> <span>Telf: ' . $value['telefono'] . '</span></div>';
            $response[$key]['ciudad'] = '<div><span>' . $value['provinciaa'] . '-' . $value['ciudad'] . '</span></div>';
            $color = ($value["id_transporte"] == 1) ? '#E3BC1C' : (($value["id_transporte"] == 2) ? '#28C839' : (($value["id_transporte"] == 3 || $value["id_transporte"] == 4) ? 'red' : ''));

            $response[$key]['transportadora'] = '<div><span style="background-color: ' . $color . '; color: white; padding: 5px; border-radius: 0.3rem;">' . $value['transporte'] . '</span></div>';
            $estado = $this->validarEstado($value['estado_guia_sistema'], $value['numero_guia'], $value['id_transporte']);

            $tracking = $this->enlaceTracking($value['id_transporte'], $value['numero_guia'], $value["telefono"]);


            $response[$key]['estado_guia_sistema'] = '<div><span class="badged ' . $estado['span_estado'] . '">' . $estado['estado_guia'] . '</span></br> <span> <a href="' . $estado['link'] . '" target="_blank">' . $value['numero_guia'] . '</a></span>' . $tracking . '</div>';
            $response[$key]['despachado'] = $this->despacho($value['estado_factura']);
            $response[$key]['impreso'] = $this->impreso($value['impreso']);
            $response[$key]['acciones'] = $this->acciones($value['id_transporte'], $value['id_factura']);
        }
        return $response;
    }

    public function despacho($estado)
    {
        if ($estado == 1) {
            return '<i class="bx bx-x" style="color:#E41818; font-size: 30px;"></i>';
        } else {
            return '<i class="bx bx-check" style="color:#28E418; font-size: 30px;"></i>';
        }
    }

    public function impreso($estado)
    {
        if ($estado == 0) {
            return '<box-icon name="printer" color="red"></box-icon>';
        } else {
            return '<box-icon name="printer" color="green"></box-icon>';
        }
    }

    public function acciones($transportadora, $guia)
    {
        if ($transportadora == 1) {
            return '  <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="anular_guiaLaar(' . $guia . ')">Anular</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;">Información</span></li>
                    </ul>
                </div>';
        } else if ($transportadora == 2) {
            return '  <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="anular_guiaServi(' . $guia . ')">Anular</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;">Información</span></li>
                    </ul>
                </div>';
        } else if ($transportadora == 3) {
            return '  <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="anular_guiaGintracom(' . $guia . ')">Anular</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;">Información</span></li>
                    </ul>
                </div>';
        } else if ($transportadora == 4) {
            return '  <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="anular_guiaSpeed(' . $guia . ')">Anular</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;">Información</span></li>
                    </ul>
                </div>';
        }
    }



    public function enlaceTracking($transportadora, $guia, $enlace)
    {
        $link = "";
        if ($transportadora == 1) {
            $link = '<div style="position: relative; display: inline-block;">
                      <a href="https://fenixoper.laarcourier.com/Tracking/Guiacompleta.aspx?guia=' . $guia . '" target="_blank" style="vertical-align: middle;">
                        <img src="https://new.imporsuitpro.com/public/img/tracking.png" width="40px" id="buscar_traking" alt="buscar_traking">
                      </a>
                      <a href="https://wa.me/+593999175865" target="_blank" style="font-size: 45px; vertical-align: middle; margin-left: 10px;">
                      <i class="bx bxl-whatsapp-square" style="color: green;"></i>
                      </a>
                     </div>';
        } elseif ($transportadora == 2) {
            $link = '<div style="position: relative; display: inline-block;">
            <a href="https://www.servientrega.com.ec/Tracking/?guia=' . $guia . '&tipo=GUIA" target="_blank" style="vertical-align: middle;">
              <img src="https://new.imporsuitpro.com/public/img/tracking.png" width="40px" id="buscar_traking" alt="buscar_traking">
            </a>
            <a href="https://wa.me/+593999175865" target="_blank" style="font-size: 45px; vertical-align: middle; margin-left: 10px;">
            <i class="bx bxl-whatsapp-square" style="color: green;"></i>
            </a>
           </div>';
        } elseif ($transportadora == 3) {
            $link = '<div style="position: relative; display: inline-block;">
            <a href="https://ec.gintracom.site/web/site/tracking" target="_blank" style="vertical-align: middle;">
              <img src="https://new.imporsuitpro.com/public/img/tracking.png" width="40px" id="buscar_traking" alt="buscar_traking">
            </a>
            <a href="https://wa.me/+593999175865" target="_blank" style="font-size: 45px; vertical-align: middle; margin-left: 10px;">
            <i class="bx bxl-whatsapp-square" style="color: green;"></i>
            </a>
           </div>';
        } else if ($transportadora == 4) {
            $link = '<select class="form-select select-estado-speed" style="max-width: 130px;" data-numero-guia="' . $guia . '">
            <option value="0">-- Selecciona estado --</option>
            <option value="2" selected="">Generado</option>
            <option value="3">Transito</option>
            <option value="7">Entregado</option>
            <option value="9">Devuelto</option>
        </select>';
        }
        return $link;
    }

    function validarEstado($estado, $guia, $transportadora)
    {
        $span_estado = "";
        $estado_guia = "";
        $link = "";

        if ($transportadora == 1) {
            $estado_result = $this->validar_estadoLaar($estado);
            $link = 'https://api.laarcourier.com:9727/guias/pdfs/DescargarV2?guia=' . $guia;
        } elseif ($transportadora == 2) {
            $estado_result = $this->validar_estadoServi($estado);
            $link = 'https://guias.imporsuitpro.com/Servientrega/guia/' . $guia;
        } elseif ($transportadora == 3) {
            $estado_result = $this->validar_estadoGintracom($estado);
            $link = 'https://guias.imporsuitpro.com/Gintracom/label/' . $guia;
        } elseif ($transportadora == 4) {
            $estado_result = $this->validar_estadoSpeed($estado);
            $link = 'https://guias.imporsuitpro.com/Speed/descargar/' . $guia;
        }

        // Asignar valores de resultado
        $span_estado = $estado_result['span_estado'] ?? "";
        $estado_guia = $estado_result['estado_guia'] ?? "";

        return [
            'span_estado' => $span_estado,
            'estado_guia' => $estado_guia,
            'link' => $link
        ];
    }


    function validar_estadoLaar($estado)
    {
        $span_estado = "";
        $estado_guia = "";

        if ($estado == 1) {
            $span_estado = "badge_purple";
            $estado_guia = "Generado";
        } elseif ($estado == 2 || $estado == 3 || $estado == 4) {
            $span_estado = "badge_purple";
            $estado_guia = "Por recolectar";
        } elseif ($estado == 5) {
            $span_estado = "badge_warning";
            $estado_guia = "En transito";
        } elseif ($estado == 6) {
            $span_estado = "badge_warning";
            $estado_guia = "Zona de entrega";
        } elseif ($estado == 7) {
            $span_estado = "badge_green";
            $estado_guia = "Entregado";
        } elseif ($estado == 8) {
            $span_estado = "badge_danger";
            $estado_guia = "Anulado";
        } elseif ($estado == 11 || $estado == 12) {
            $span_estado = "badge_warning";
            $estado_guia = "En transito";
        } elseif ($estado == 14) {
            $span_estado = "badge_danger";
            $estado_guia = "Con novedad";
        } elseif ($estado == 9) {
            $span_estado = "badge_danger";
            $estado_guia = "Devuelto";
        }

        return [
            'span_estado' => $span_estado,
            'estado_guia' => $estado_guia,
        ];
    }

    function validar_estadoServi($estado)
    {
        $span_estado = "";
        $estado_guia = "";

        if ($estado == 101) {
            $span_estado = "badge_danger";
            $estado_guia = "Anulado";
        } elseif ($estado == 100 || $estado == 102 || $estado == 103) {
            $span_estado = "badge_purple";
            $estado_guia = "Generado";
        } elseif ($estado == 200 || $estado == 201 || $estado == 202) {
            $span_estado = "badge_purple";
            $estado_guia = "Recolectado";
        } elseif ($estado >= 300 && $estado <= 317) {
            $span_estado = "badge_warning";
            $estado_guia = "Procesamiento";
        } elseif ($estado >= 400 && $estado <= 403) {
            $span_estado = "badge_green";
            $estado_guia = "Entregado";
        } elseif ($estado >= 318 && $estado <= 351) {
            $span_estado = "badge_danger";
            $estado_guia = "Con novedad";
        } elseif ($estado >= 500 && $estado <= 502) {
            $span_estado = "badge_danger";
            $estado_guia = "Devuelto";
        }

        return [
            'span_estado' => $span_estado,
            'estado_guia' => $estado_guia
        ];
    }

    function validar_estadoGintracom($estado)
    {
        $span_estado = "";
        $estado_guia = "";

        if ($estado == 1) {
            $span_estado = "badge_purple";
            $estado_guia = "Generada";
        } elseif ($estado == 2) {
            $span_estado = "badge_warning";
            $estado_guia = "Picking";
        } elseif ($estado == 3) {
            $span_estado = "badge_warning";
            $estado_guia = "Packing";
        } elseif ($estado == 4) {
            $span_estado = "badge_warning";
            $estado_guia = "En tránsito";
        } elseif ($estado == 5) {
            $span_estado = "badge_warning";
            $estado_guia = "En reparto";
        } elseif ($estado == 6) {
            $span_estado = "badge_purple";
            $estado_guia = "Novedad";
        } elseif ($estado == 7) {
            $span_estado = "badge_green";
            $estado_guia = "Entregada";
        } elseif ($estado == 8) {
            $span_estado = "badge_danger";
            $estado_guia = "Devolucion";
        } elseif ($estado == 9) {
            $span_estado = "badge_danger";
            $estado_guia = "Devolución Entregada a Origen";
        } elseif ($estado == 10) {
            $span_estado = "badge_danger";
            $estado_guia = "Cancelada por transportadora";
        } elseif ($estado == 11) {
            $span_estado = "badge_danger";
            $estado_guia = "Indemnización";
        } elseif ($estado == 12) {
            $span_estado = "badge_danger";
            $estado_guia = "Anulada";
        } elseif ($estado == 13) {
            $span_estado = "badge_danger";
            $estado_guia = "Devolucion en tránsito";
        }

        return [
            'span_estado' => $span_estado,
            'estado_guia' => $estado_guia
        ];
    }

    function validar_estadoSpeed($estado)
    {
        $span_estado = "";
        $estado_guia = "";

        if ($estado == 2) {
            $span_estado = "badge_purple";
            $estado_guia = "generado";
        } elseif ($estado == 3) {
            $span_estado = "badge_warning";
            $estado_guia = "En transito";
        } elseif ($estado == 7) {
            $span_estado = "badge_green";
            $estado_guia = "Entregado";
        } elseif ($estado == 9) {
            $span_estado = "badge_danger";
            $estado_guia = "Devuelto";
        }

        return [
            'span_estado' => $span_estado,
            'estado_guia' => $estado_guia
        ];
    }

    // Método para contar el total de registros
    public function contarTotalGuiasAdministrador($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin)
    {
        $sql = "SELECT COUNT(*) as total FROM facturas_cot fc
        LEFT JOIN ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot
        LEFT JOIN plataformas p ON p.id_plataforma = fc.id_plataforma
        LEFT JOIN plataformas tp ON tp.id_plataforma = fc.id_propietario
        LEFT JOIN bodega b ON b.id = fc.id_bodega
        WHERE TRIM(fc.numero_guia) <> '' 
            AND fc.numero_guia IS NOT NULL 
            AND fc.numero_guia <> '0' 
            AND fc.anulada = 0";

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $sql .= " AND fecha_factura BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }

        if (!empty($transportadora)) {
            $sql .= " AND transporte = '$transportadora'";
        }

        if (!empty($estado)) {
            $sql .= " AND ($estado)";
        }

        if ($drogshipin == 0 || $drogshipin == 1) {
            $sql .= " AND drogshipin = $drogshipin";
        }

        if ($impreso == 0 || $impreso == 1) {
            $sql .= " AND impreso = $impreso";
        }

        $result = $this->select($sql);
        return $result[0]['total'];
    }

    public function cargarAnuladas($filtro)
    {
        $sql = "SELECT * FROM facturas_cot where anulado = 1";

        return $this->select($sql);
    }

    public function nuevo_pedido($fecha_factura, $id_usuario, $monto_factura, $estado_factura, $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, $id_producto_venta, $dropshipping, $id_plataforma, $dueño_id, $importado, $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte, $provincia, $id_bodega)
    {
        $tmp = session_id();
        $response = $this->initialResponse();

        $ultima_factura = $this->select("SELECT MAX(numero_factura) as factura_numero FROM facturas_cot");
        $factura_numero = $ultima_factura[0]['factura_numero'];
        if (!$factura_numero || $factura_numero == '') {
            $factura_numero = 'COT-0000000000';
        }
        $nueva_factura = $this->incrementarNumeroFactura($factura_numero);



        $response = $this->initialResponse();
        $sql = "INSERT INTO facturas_cot (
            numero_factura, fecha_factura, id_usuario, monto_factura, estado_factura, 
            nombre, telefono, c_principal, ciudad_cot, c_secundaria, 
            referencia, observacion, guia_enviada, transporte, identificacion, celular, 
            id_propietario, drogshipin, id_plataforma, importado, 
            plataforma_importa, cod, estado_guia_sistema, impreso, facturada, 
            anulada, identificacionO, nombreO, ciudadO, provinciaO, provincia,
            direccionO, referenciaO, numeroCasaO, valor_seguro, no_piezas, tipo_servicio, 
            peso, contiene, costo_flete, costo_producto, comentario, id_transporte, telefonoO, id_bodega
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $data = array(
            $nueva_factura,
            $fecha_factura,
            $id_usuario,
            $monto_factura,
            $estado_factura,
            $nombre_cliente,
            $telefono_cliente,
            $c_principal,
            $ciudad_cot,
            $c_secundaria,
            $referencia,
            $observacion,
            $guia_enviada,
            $transporte,
            $identificacion,
            $celular,
            $dueño_id,
            $dropshipping,
            $id_plataforma,
            $importado,
            $plataforma_importa,
            $cod,
            $estado_guia_sistema,
            $impreso,
            $facturada,
            $anulada,
            $identificacionO,
            $nombreO,
            $ciudadO,
            $provinciaO,
            $provincia,
            $direccionO,
            $referenciaO,
            $numeroCasaO,
            $valor_segura,
            $no_piezas,
            $tipo_servicio,
            $peso,
            $contiene,
            $costo_flete,
            $costo_producto,
            $comentario,
            $id_transporte,
            $celularO,
            $id_bodega
        );

        if (substr_count($sql, '?') !== count($data)) {
            throw new Exception('La cantidad de placeholders en la consulta no coincide con la cantidad de elementos en el array de datos.');
        }

        $responses = $this->insert($sql, $data);


        if ($responses === 1) {

            $factura_id_result = $this->select("SELECT id_factura FROM facturas_cot WHERE numero_factura = '$nueva_factura'");
            //print_r($factura_id_result);
            $factura_id = $factura_id_result[0]['id_factura'];

            $tmp_cotizaciones = $this->select("SELECT * FROM tmp_cotizacion WHERE session_id = '$tmp'");

            // Insertar cada registro de tmp_cotizacion en detalle_cotizacion
            $detalle_sql = "INSERT INTO detalle_fact_cot (numero_factura, id_factura, id_producto, cantidad, desc_venta, precio_venta, id_plataforma , sku, id_inventario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            foreach ($tmp_cotizaciones as $tmp) {
                //  echo 'enta';
                $detalle_data = array(
                    $nueva_factura,
                    $factura_id,
                    $tmp['id_producto'],
                    $tmp['cantidad_tmp'],
                    $tmp['desc_tmp'],
                    $tmp['precio_tmp'],
                    $tmp['id_plataforma'],
                    $tmp['sku'],
                    $tmp['id_inventario']
                );
                $guardar_detalle = $this->insert($detalle_sql, $detalle_data);
                // print_r($guardar_detalle);
            }


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
    public function nuevo_pedido_shopify($fecha_factura, $id_usuario, $monto_factura, $estado_factura, $nombre_cliente, $telefono_cliente, $c_principal, $ciudad_cot, $c_secundaria, $referencia, $observacion, $guia_enviada, $transporte, $identificacion, $celular, $id_producto_venta, $dropshipping, $id_plataforma, $dueño_id, $importado, $plataforma_importa, $cod, $estado_guia_sistema, $impreso, $facturada, $factura_numero, $numero_guia, $anulada, $identificacionO, $celularO, $nombreO, $ciudadO, $provinciaO, $direccionO, $referenciaO, $numeroCasaO, $valor_segura, $no_piezas, $tipo_servicio, $peso, $contiene, $costo_flete, $costo_producto, $comentario, $id_transporte, $provincia, $productos, $id_bodega)
    {
        $tmp = session_id();
        $response = $this->initialResponse();

        $ultima_factura = $this->select("SELECT MAX(numero_factura) as factura_numero FROM facturas_cot");
        $factura_numero = $ultima_factura[0]['factura_numero'];
        if (!$factura_numero || $factura_numero == '') {
            $factura_numero = 'COT-0000000000';
        }
        $nueva_factura = $this->incrementarNumeroFactura($factura_numero);



        $response = $this->initialResponse();
        $sql = "INSERT INTO facturas_cot (
            numero_factura, fecha_factura, id_usuario, monto_factura, estado_factura, 
            nombre, telefono, c_principal, ciudad_cot, c_secundaria, 
            referencia, observacion, guia_enviada, transporte, identificacion, celular, 
            id_propietario, drogshipin, id_plataforma, importado, 
            plataforma_importa, cod, estado_guia_sistema, impreso, facturada, 
            anulada, identificacionO, nombreO, ciudadO, provinciaO, provincia,
            direccionO, referenciaO, numeroCasaO, valor_seguro, no_piezas, tipo_servicio, 
            peso, contiene, costo_flete, costo_producto, comentario, id_transporte, telefonoO, id_bodega
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $data = array(
            $nueva_factura,
            $fecha_factura,
            $id_usuario,
            $monto_factura,
            $estado_factura,
            $nombre_cliente,
            $telefono_cliente,
            $c_principal,
            $ciudad_cot,
            $c_secundaria,
            $referencia,
            $observacion,
            $guia_enviada,
            $transporte,
            $identificacion,
            $celular,
            $dueño_id,
            $dropshipping,
            $id_plataforma,
            $importado,
            $plataforma_importa,
            $cod,
            $estado_guia_sistema,
            $impreso,
            $facturada,
            $anulada,
            $identificacionO,
            $nombreO,
            $ciudadO,
            $provinciaO,
            $provincia,
            $direccionO,
            $referenciaO,
            $numeroCasaO,
            $valor_segura,
            $no_piezas,
            $tipo_servicio,
            $peso,
            $contiene,
            $costo_flete,
            $costo_producto,
            $comentario,
            $id_transporte,
            $celularO,
            $id_bodega
        );

        if (substr_count($sql, '?') !== count($data)) {
            throw new Exception('La cantidad de placeholders en la consulta no coincide con la cantidad de elementos en el array de datos.');
        }

        $responses = $this->insert($sql, $data);


        if ($responses === 1) {
            // Insertar cada registro de tmp_cotizacion en detalle_cotizacion
            $detalle_sql = "INSERT INTO detalle_fact_cot (numero_factura, id_factura, id_producto, cantidad, desc_venta, precio_venta, id_plataforma , sku, id_inventario, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $id_factura = $this->select("SELECT id_factura FROM facturas_cot WHERE numero_factura = '$nueva_factura'");
            $factura_id = $id_factura[0]['id_factura'];

            foreach ($productos as $tmp) {
                //buscar producto 
                $id_producto = $tmp['id_producto_venta'];
                $sql = "SELECT * FROM inventario_bodegas WHERE id_inventario = $id_producto";
                $id_bodegas = $this->select($sql);

                $id_bodega = $id_bodegas[0]['bodega'];
                $id_inventario = $id_bodegas[0]['id_producto'];
                echo $id_inventario;
                $id_plataforma = $id_plataforma;
                $sku = $id_bodegas[0]['sku'];
                $nombre = $tmp['nombre'];
                $cantidad = $tmp['cantidad'];
                $descuento = 0;
                $precio = $tmp['precio'];
                //  echo 'enta';
                $detalle_data = array(
                    $nueva_factura,
                    $factura_id,
                    $id_inventario,
                    $cantidad,
                    $descuento,
                    $precio,
                    $id_plataforma,
                    $sku,
                    $id_producto,
                    $nombre
                );
                $guardar_detalle = $this->insert($detalle_sql, $detalle_data);
                print_r($guardar_detalle);
                // print_r($guardar_detalle);
            }


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

    public function obtenerDestinatario($id)
    {
        $sql = "SELECT id_plataforma FROM inventario_bodegas WHERE id_producto = $id";

        $id_platafomra = $this->select($sql);
        $id_platafomra = $id_platafomra[0]['id_plataforma'];
        return $id_platafomra;
    }
    public function obtenerDestinatarioShopify($id)
    {
        $sql = "SELECT id_plataforma FROM inventario_bodegas WHERE id_inventario = $id";

        $id_platafomra = $this->select($sql);
        $id_platafomra = $id_platafomra[0]['id_plataforma'];
        return $id_platafomra;
    }

    public function buscarTmp()
    {
        $tmp = session_id();
        // echo $tmp;
        $sql = "SELECT * 
        FROM `tmp_cotizacion` tmp
        LEFT JOIN `inventario_bodegas` ib ON tmp.id_inventario = ib.id_inventario
        LEFT JOIN `productos` p ON tmp.id_producto = p.id_producto
        LEFT JOIN `variedades` v ON ib.id_variante = v.id_variedad
        WHERE tmp.session_id = '$tmp'";
        //echo $sql;
        return $this->select($sql);
    }

    public function eliminarTmp($id_tmp)
    {
        $sql = "delete FROM tmp_cotizacion WHERE id_tmp = ?";
        $data = [$id_tmp];
        //echo print_r($data);
        $eliminar_tmp = $this->delete($sql, $data);
        //print_r($eliminar_tmp);
        if ($eliminar_tmp == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al eliminar la producto';
        }
        return $response;
    }

    public function eliminarDescripcion($id_descripcion)
    {
        $sql = "delete FROM detalle_fact_cot WHERE id_detalle = ?";
        $data = [$id_descripcion];
        //echo print_r($data);
        $eliminar_tmp = $this->delete($sql, $data);
        //print_r($eliminar_tmp);
        if ($eliminar_tmp == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al eliminar la producto';
        }
        return $response;
    }

    public function buscarBodega($id_producto)
    {
        $sql = "SELECT * FROM inventario_bodegas WHERE id_producto = $id_producto limit 1";

        $responde = $this->select($sql);
        $bodega = $responde[0]['bodega'];
        $sql = "SELECT * FROM bodega WHERE id = $bodega";
        return $this->select($sql);
    }

    public function buscarProductosBodega($producto, $sku, $plataforma)
    {

        $id_bodega_buscar = $this->select("SELECT bodega FROM inventario_bodegas WHERE id_producto = $producto and sku='$sku' ");
        $id_bodega = $id_bodega_buscar[0]['bodega'];

        $sql = "SELECT * FROM inventario_bodegas , productos WHERE bodega=$id_bodega and productos.id_producto=inventario_bodegas.id_producto";
        //echo $sql;
        return $this->select($sql);
    }

    public function cambiarPrecio($id_tmp, $precio, $descuento)
    {

        $sql = "UPDATE tmp_cotizacion SET precio_tmp = ?, desc_tmp =? WHERE id_tmp = ?";
        $data = [$precio, $descuento, $id_tmp];
        $editar_tmp = $this->update($sql, $data);
        if ($editar_tmp == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Imagen subida correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al subir la imagen';
        }

        return $response;
    }

    public function recuperarOrigenBodega($producto, $sku, $plataforma)
    {

        $id_bodega_buscar = $this->select("SELECT bodega FROM inventario_bodegas WHERE id_producto = $producto and sku='$sku' ");
        $id_bodega = $id_bodega_buscar[0]['bodega'];

        $sql = "SELECT * FROM bodega WHERE id=$id_bodega";
        return $this->select($sql);
    }

    public function actualizarTmp($id_tmp, $descuento, $precio, $cantidad)
    {
        $sql_consulta = $this->select("SELECT * FROM tmp_cotizacion WHERE id_tmp=$id_tmp");
        $id = $sql_consulta[0]['id_tmp'];
        $desc_tmp = $sql_consulta[0]['desc_tmp'];
        $precio_tmp = $sql_consulta[0]['precio_tmp'];
        $cantidad_tmp = $sql_consulta[0]['cantidad_tmp'];
        if (($desc_tmp == $descuento) && ($precio_tmp == $precio) && ($cantidad_tmp == $cantidad)) {
            $responses = 1;
        } else {
            $sql = "UPDATE tmp_cotizacion SET desc_tmp = ?, precio_tmp = ? , cantidad_tmp = ?  WHERE id_tmp = ?";
            $data = [$descuento, $precio, $cantidad, $id_tmp];
            $responses = $this->update($sql, $data);
        }

        if ($responses == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto actualizado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $responses["message"];
        }
        return $response;
    }

    public function actualizarDetalle($id_detalle, $descuento, $precio, $cantidad)
    {
        $sql = "UPDATE detalle_fact_cot SET desc_venta = ?, precio_venta = ?, cantidad = ? WHERE id_detalle = ?";
        $data = [$descuento, $precio, $cantidad, $id_detalle];
        $responses = $this->update($sql, $data);
        /* print_r($sql); */
        if ($responses == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto actualizado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = "Error al actualizar";
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
    //editar pedido

    public function verPedido($id)
    {
        $sql = "SELECT * FROM facturas_cot WHERE id_factura = $id";
        return $this->select($sql);
    }

    public function pedidos($plataforma)
    {
        $sql = "SELECT *, (SELECT ciudad FROM ciudad_cotizacion where id_cotizacion = ciudad_cot) as ciudad,(SELECT provincia FROM ciudad_cotizacion where id_cotizacion = ciudad_cot) as provinciaa,(SELECT url_imporsuit from plataformas where id_plataforma = id_propietario) as plataforma FROM facturas_cot WHERE anulada = 0 AND (TRIM(numero_guia) = '' OR numero_guia IS NULL OR numero_guia = '0') and id_plataforma = '$plataforma' ORDER BY numero_factura DESC;";
        return $this->select($sql);
    }

    public function eliminarPedido($id_factura)
    {
        $sql = "DELETE FROM facturas_cot WHERE id_factura = ?";
        $data = [$id_factura];

        $eliminar_pedido = $this->delete($sql, $data);

        if ($eliminar_pedido == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Pedido eliminado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al eliminar la Pedido';
        }
        return $response;
    }

    public function datosPedido($id)
    {
        $sql = "SELECT * 
        FROM `detalle_fact_cot`
        LEFT JOIN `productos` ON detalle_fact_cot.id_producto = productos.id_producto
        LEFT JOIN `inventario_bodegas` ON detalle_fact_cot.id_inventario = inventario_bodegas.id_inventario
        LEFT JOIN `variedades` ON inventario_bodegas.id_variante = variedades.id_variedad
        WHERE detalle_fact_cot.id_factura = $id;";
        //echo $sql;
        return $this->select($sql);
    }

    public function cargarPedido($id)
    {
        $sql = "SELECT * FROM facturas_cot WHERE id_factura = $id";
        return $this->select($sql);
    }

    public function agregarDetalle($id_producto, $cantidad, $precio,  $plataforma, $sku, $id_factura)
    {
        //verificar productos
        $timestamp = session_id();
        $cantidad_tmp = $this->select("SELECT * FROM detalle_fact_cot WHERE id_factura = '$id_factura' and id_producto=$id_producto and sku=$sku");
        //print_r($cantidad_tmp);
        if (empty($cantidad_tmp)) {
            $numeroFactura = $this->select("SELECT numero_factura FROM facturas_cot WHERE id_factura = '$id_factura'");
            $numero_factura = $numeroFactura[0]['numero_factura'];
            $sql = "INSERT INTO `detalle_fact_cot` (`id_producto`, `cantidad`, `precio_venta`, `id_factura`, `id_plataforma`, `sku`, `numero_factura`) VALUES (?, ?, ?, ?, ?, ?, ?);";
            $data = [$id_producto, $cantidad, $precio, $id_factura, $plataforma, $sku, $numero_factura];
            $insertar_caracteristica = $this->insert($sql, $data);
        } else {
            $cantidad_anterior = $cantidad_tmp[0]["cantidad"];
            $cantidad_nueva = $cantidad_anterior + $cantidad;
            $id_detalle = $cantidad_tmp[0]["id_detalle"];
            $sql = "UPDATE `detalle_fact_cot` SET  `cantidad` = ? WHERE `id_detalle` = ?";
            $data = [$cantidad_nueva, $id_detalle];
            $insertar_caracteristica = $this->update($sql, $data);
            //print_r($insertar_caracteristica);
        }
        // print_r($insertar_caracteristica);

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

    public function datosPlataformas($tienda)
    {
        $sql = "SELECT * FROM plataformas WHERE url_imporsuit = '$tienda'";
        return $this->select($sql);
    }

    public function obtenerDetalleFactura($id_factura, $plataforma)
    {
        $sql = "select * from facturas_cot fc, detalle_fact_cot dfc, productos p where dfc.id_producto=p.id_producto and fc.id_factura=dfc.id_factura and fc.id_factura=$id_factura";
        return $this->select($sql);
    }

    public function obtenerDetalleWallet($numero_factura)
    {
        $sql = "select * from facturas_cot fc, detalle_fact_cot dfc, productos p where dfc.id_producto=p.id_producto and fc.id_factura=dfc.id_factura and fc.numero_factura = '$numero_factura';";
        $response = $this->select($sql);

        //obtener url de la plataforma
        $url = $this->select("SELECT url_imporsuit FROM plataformas WHERE id_plataforma = " . $response[0]['id_plataforma']);
        $response[0]['url_imporsuit'] = $url[0]['url_imporsuit'];

        return $response;
    }

    public function validaDevolucion($telefono)
    {
        $sql = "SELECT * FROM `facturas_cot` WHERE telefono like '%$telefono%'  and ((estado_guia_sistema BETWEEN 500 AND 502 and id_transporte=2)
                            OR (estado_guia_sistema in (9) and id_transporte=2)
                            OR (estado_guia_sistema in (9) and id_transporte=4)
                            OR (estado_guia_sistema in (8,9,13) and id_transporte=3))";
        //echo $sql;
        return $this->select($sql);
    }

    /* APIS Chat center */
    public function mensajes_clientes($id_cliente, $id_plataforma)
    {
        $sql = "SELECT * FROM `clientes_chat_center` INNER JOIN `mensajes_clientes` ON clientes_chat_center.id = mensajes_clientes.id_cliente WHERE clientes_chat_center.id_plataforma = $id_plataforma AND clientes_chat_center.id = $id_cliente;";
        return $this->select($sql);
    }

    public function guardar_audio_Whatsapp()
    {
        if (isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
            // Ruta de destino para guardar el archivo
            $target_dir = "public/whatsapp/audios/";
            $file_name = uniqid() . ".ogg";  // Cambiar extensión a .ogg
            $target_file = $target_dir . $file_name;

            // Verificar si la carpeta de destino existe, si no, crearla
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);  // Crear la carpeta si no existe
            }

            // Mover el archivo a la carpeta de destino
            if (move_uploaded_file($_FILES['audio']['tmp_name'], $target_file)) {
                // Retornar la ruta del archivo subido
                $response = [
                    'status' => 200,
                    'message' => 'Audio subido correctamente',
                    'data' => $target_file  // Aquí devolvemos la ruta del archivo subido
                ];
            } else {
                // Error al mover el archivo
                $response = [
                    'status' => 500,
                    'message' => 'Error al mover el archivo de audio'
                ];
            }
        } else {
            // No se recibió ningún archivo o hubo un error
            $response = [
                'status' => 500,
                'message' => 'Error al subir el archivo de audio'
            ];
        }

        // Retornar la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}
