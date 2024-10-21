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
        b.direccion AS direccion_bodega,
        n.solucionada, 
        n.terminado, 
        n.estado_novedad
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
        LEFT JOIN 
            novedades n ON n.guia_novedad = fc.numero_guia
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

        if ($impreso !== null && $impreso !== '') {
            if ($impreso == 0 || $impreso == 1) {
                $sql .= " AND impreso = '$impreso'";
            }
        }

        if ($despachos !== null && $despachos !== '') {
            if ($despachos == 1 || $despachos == 2 || $despachos == 3) {
                $sql .= " AND estado_factura = '$despachos'";
            }
        }

        $sql .= " ORDER BY fc.numero_factura DESC;";

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

            if ($despachos == 1 || $despachos == 2 || $despachos == 3) {
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

    public function cargarGuiasSpeed($fecha_inicio, $fecha_fin, $transportadora, $estado, $impreso, $drogshipin, $despachos, $recibo)
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
                tp.nombre_tienda AS nombre_proveedor,
                mg.id_motorizado, -- Campo de motorizado_guia
                u.nombre_users AS nombre_motorizado -- Campo del usuario (motorizado)
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
            LEFT JOIN 
                motorizado_guia mg ON mg.guia = fc.numero_guia -- Relaciona las guías
            LEFT JOIN 
                users u ON u.id_users = mg.id_motorizado -- Relaciona motorizados
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

        if ($despachos !== null && $despachos !== '') {
            if ($despachos == 1 || $despachos == 2 || $despachos == 3) {
                $sql .= " AND estado_factura = '$despachos'";
            }
        }

        $sql .= " AND (fc.numero_guia like 'SPD%' or fc.numero_guia like 'MKL%')";

        if (!empty($recibo)) {
            $sql .= " AND fc.recibo IS NOT NULL";
        }

        $sql .= " ORDER BY fc.numero_factura DESC";

        return $this->select($sql);
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

            $id_configuracion = $this->select("SELECT id FROM configuraciones WHERE id_plataforma = $id_plataforma");
            $id_configuracion = $id_configuracion[0]['id'];

            if (!empty($id_configuracion)) {

                /* consultar ciudad por medio id_cotizacion */
                $nombre_ciudad = $this->select("SELECT ciudad FROM ciudad_cotizacion WHERE id_cotizacion = $ciudad_cot");
                $nombre_ciudad = $nombre_ciudad[0]['ciudad'];

                // Ejecutamos la función que devuelve productos y categorías
                $data = $this->ejecutar_automatizador($nueva_factura);

                $data = [
                    "id_configuracion" => $id_configuracion,
                    "value_blocks_type" => "1",
                    "user_id" => "1",
                    "order_id" => $nueva_factura,
                    "nombre" => $nombre_cliente,
                    "direccion" => $c_principal . " y " . $c_secundaria,
                    "email" => "",
                    "celular" => $celular,
                    "contenido" => $contiene,
                    "costo" => $monto_factura,
                    "ciudad" => $nombre_ciudad,
                    "productos" => $data['productos'] ?? [],
                    "categorias" => $data['categorias'] ?? [],
                    "status" => [""],
                    "novedad" => [""],
                    "provincia" => [""],
                    "ciudad" => [""],
                    "user_info" => [
                        "nombre" => $nombre_cliente,
                        "direccion" => $c_principal . " y " . $c_secundaria,
                        "email" => "",
                        "celular" => $celular,
                        "order_id" => $nueva_factura,
                        "contenido" => $contiene,
                        "costo" => $monto_factura,
                        "ciudad" => $nombre_ciudad
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
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = "Pedido creado correctamente";
                $response["numero_factura"] = $nueva_factura;
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $responses['message'];
        }

        return $response;
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

    public function buscarProductosBodega($producto, $sku)
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

    public function novedadSpeed($id_pedido, $novedad, $tipo)
    {

        $sql = "SELECT * FROM facturas_cot WHERE id_factura = $id_pedido";
        $pedido = $this->select($sql);

        $numero_guia = $pedido[0]['numero_guia'];

        $sql = "INSERT INTO `novedades` (`guia_novedad`, `cliente_novedad`, `estado_novedad`, `novedad`, `solucion_novedad`, `tracking`, `fecha`, `id_plataforma`, `solucionada`, `terminado`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$numero_guia, $pedido[0]['nombre'], 14, $novedad, '', '', date('Y-m-d H:i:s'), $pedido[0]['id_plataforma'], 0, 0];

        $response = $this->insert($sql, $data);

        $sql = "SELECT id_novedad FROM novedades WHERE guia_novedad = '$numero_guia' ORDER BY id_novedad DESC LIMIT 1";
        $id_novedad = $this->select($sql);

        $sql = "INSERT INTO `detalle_novedad`(`codigo_novedad`, `guia_novedad`, `nombre_novedad`, `detalle_novedad`, `observacion`, `id_plataforma`) VALUES (?, ?, ?, ?, ?, ?)";
        $data = [14, $numero_guia, $tipo, $novedad, 'Novedad Administrativa', $pedido[0]['id_plataforma']];

        $response = $this->insert($sql, $data);

        $sql = "UPDATE facturas_cot SET novedad = ?, tipo_novedad =? WHERE id_factura = ?";

        if ($response == 1) {
            $response = [
                'status' => 200,
                'title' => 'Peticion exitosa',
                'message' => 'Novedad actualizada correctamente'
            ];
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => 'Error al actualizar la novedad'
            ];
        }

        return $response;
        /*  $sql = "UPDATE facturas_cot SET novedad = ?, tipo_novedad =? WHERE id_factura = ?";
        $response = $this->update($sql, [$novedad, $tipo, $id_pedido]);

        if ($tipo == "rechazar") {
            $sql = "SELECT * FROM facturas_cot WHERE id_factura = $id_pedido";
            $pedido = $this->select($sql);
            $numero_guia = $pedido[0]['numero_guia'];

            $url = "https://guias.imporsuitpro.com/Speed/estado/$numero_guia";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, true);
            return $response;
        }

        if ($response == 1) {
            $response = [
                'status' => 200,
                'title' => 'Peticion exitosa',
                'message' => 'Novedad actualizada correctamente'
            ];
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => 'Error al actualizar la novedad'
            ];
        }

        return $response; */
    }


    /* APIS Chat center */
    public function mensajes_clientes($id_cliente)
    {
        $sql = "SELECT * FROM `clientes_chat_center` INNER JOIN `mensajes_clientes` ON clientes_chat_center.id = mensajes_clientes.id_cliente WHERE mensajes_clientes.celular_recibe = $id_cliente
         ORDER BY mensajes_clientes.created_at ASC;";
        return $this->select($sql);
    }

    public function cambiar_vistos($id_cliente)
    {
        $response = $this->initialResponse();

        // Consulta de actualización
        $sql_update = "UPDATE `mensajes_clientes` SET `visto` = ? WHERE `celular_recibe` = ?";
        $update_data = [1, $id_cliente];

        // Ejecutar la actualización
        $actualizar_visto = $this->update($sql_update, $update_data);

        // Verificar si la actualización fue exitosa
        if ($actualizar_visto) {  // Verifica si devolvió un valor positivo
            $response['status'] = 200;
            $response['title'] = 'Petición exitosa';
            $response['message'] = 'Configuración agregada y actualizada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error en inserción';
            $response['message'] = 'No se pudo actualizar el estado de los mensajes';
        }

        return $response;
    }


    public function ultimo_mensaje_cliente($id_cliente, $ultimo_mensaje_id = null)
    {
        // Si se proporciona un ID de último mensaje, obtenemos los mensajes más recientes que ese ID
        if ($ultimo_mensaje_id) {
            $sql = "SELECT * FROM `clientes_chat_center` 
                INNER JOIN `mensajes_clientes` 
                ON clientes_chat_center.id = mensajes_clientes.id_cliente 
                WHERE mensajes_clientes.celular_recibe = $id_cliente
                AND mensajes_clientes.id > $ultimo_mensaje_id
                ORDER BY mensajes_clientes.created_at ASC;";
        } else {
            // Si no se proporciona un ID, solo obtenemos el último mensaje
            $sql = "SELECT * FROM `clientes_chat_center` 
                INNER JOIN `mensajes_clientes` 
                ON clientes_chat_center.id = mensajes_clientes.id_cliente 
                WHERE mensajes_clientes.celular_recibe = $id_cliente
                ORDER BY mensajes_clientes.created_at DESC 
                LIMIT 1;";
        }

        return $this->select($sql);
    }

    public function numero_cliente($id_cliente, $id_plataforma)
    {
        $sql = "SELECT * FROM `clientes_chat_center` WHERE id = $id_cliente;";
        return $this->select($sql);
    }

    public function numeros_clientes($id_plataforma, $palabra_busqueda)
    {
        $sql = "SELECT 
        ccc.nombre_cliente, 
        ccc.apellido_cliente, 
        ccc.celular_cliente,
        mc.id_cliente, 
        mc.texto_mensaje, 
        ecc.color_etiqueta, 
        mc.created_at,
        (SELECT COUNT(mensajes_clientes.visto) FROM mensajes_clientes 
        WHERE mensajes_clientes.rol_mensaje = 0 AND mensajes_clientes.visto = 0 AND mensajes_clientes.celular_recibe = mc.id_cliente) 
        AS  mensajes_pendientes
    FROM 
        clientes_chat_center ccc
    INNER JOIN 
        (
            SELECT 
                id_cliente, 
                texto_mensaje, 
                created_at
            FROM 
                mensajes_clientes mc1
            WHERE 
                rol_mensaje = 0
                AND created_at = (
                    SELECT MAX(created_at) 
                    FROM mensajes_clientes mc2 
                    WHERE mc2.id_cliente = mc1.id_cliente
                )
        ) mc 
        ON ccc.id = mc.id_cliente
    LEFT JOIN 
        etiquetas_chat_center ecc 
        ON ecc.id_etiqueta = ccc.id_etiqueta 
    WHERE 
        ccc.id_plataforma = $id_plataforma";

        if (!empty($palabra_busqueda)) {
            $sql .= " AND (ccc.nombre_cliente LIKE '%$palabra_busqueda%' OR ccc.apellido_cliente LIKE '%$palabra_busqueda%' OR ccc.celular_cliente LIKE '%$palabra_busqueda%')";
        }

        $sql .= " ORDER BY 
        mc.created_at DESC";
        return $this->select($sql);
    }

    public function obtener_url_video_mensaje($id_mensaje)
    {
        $sql = "SELECT ruta_archivo FROM `mensajes_clientes` WHERE id = $id_mensaje;";
        return $this->select($sql);
    }

    public function guardar_audio_Whatsapp()
    {
        if (isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
            // Ruta de destino para guardar el archivo
            $target_dir = "public/whatsapp/audios_enviados/";
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

    public function guardar_imagen_Whatsapp($imagen)
    {
        // Formatos permitidos por la API de WhatsApp
        $formatos_permitidos = ['jpeg', 'jpg', 'png', 'gif'];
        $tamano_maximo = 16 * 1024 * 1024; // 16 MB en bytes

        // Verificar si se ha recibido el archivo sin errores
        if (isset($imagen) && $imagen['error'] == 0) {
            // Extraer la extensión del archivo
            $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));

            // Validar el formato de la imagen
            if (!in_array($extension, $formatos_permitidos)) {
                $response['status'] = 400;
                $response['title'] = 'Formato no permitido';
                $response['message'] = 'Solo se permiten archivos JPEG, PNG o GIF.';
                return $response;
            }

            // Validar el tamaño del archivo
            if ($imagen['size'] > $tamano_maximo) {
                $response['status'] = 400;
                $response['title'] = 'Tamaño excedido';
                $response['message'] = 'El tamaño de la imagen no puede superar los 16 MB.';
                return $response;
            }

            // Directorio de destino para la imagen
            $target_dir = "public/whatsapp/imagenes_enviadas/";
            $file_name = uniqid() . "." . $extension; // Nombre único
            $target_file = $target_dir . $file_name;

            // Crear el directorio si no existe
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true); // Crear carpeta con permisos 0777
            }

            // Mover el archivo al directorio de destino
            if (move_uploaded_file($imagen['tmp_name'], $target_file)) {
                $response['status'] = 200;
                $response['title'] = 'Petición exitosa';
                $response['message'] = 'Imagen subida correctamente';
                $response['data'] = $target_file; // Ruta de la imagen subida
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error al mover la imagen';
                $response['message'] = 'No se pudo mover la imagen al directorio de destino.';
            }
        } else {
            // No se recibió el archivo o hubo un error en la subida
            $response['status'] = 500;
            $response['title'] = 'Error en la subida';
            $response['message'] = 'No se recibió ninguna imagen válida.';
        }

        return $response;
    }

    public function agregar_mensaje_enviado($texto_mensaje, $tipo_mensaje, $mid_mensaje, $id_recibe, $id_plataforma, $ruta_archivo)
    {
        // codigo para agregar categoria
        $response = $this->initialResponse();

        $sql = "INSERT INTO `mensajes_clientes` (`id_plataforma`,`id_cliente`,`mid_mensaje`,`tipo_mensaje`,`rol_mensaje`,`celular_recibe`,`texto_mensaje`,`ruta_archivo`,`visto`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$id_plataforma, 411, $mid_mensaje, $tipo_mensaje, 1, $id_recibe, $texto_mensaje, $ruta_archivo, 1];
        $insertar_mensaje_enviado = $this->insert($sql, $data);
        if ($insertar_mensaje_enviado == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'flotante agregada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $insertar_mensaje_enviado['message'];
        }
        return $response;
    }

    public function obtener_etiquetas($id_plataforma)
    {
        $sql = "SELECT * FROM `etiquetas_chat_center` WHERE id_plataforma = $id_plataforma;";
        return $this->select($sql);
    }

    public function eliminarEtiqueta($id_etiqueta)
    {
        $sql = "DELETE FROM etiquetas_chat_center WHERE id_etiqueta = ?";
        $data = [$id_etiqueta];

        $eliminar_etiqueta = $this->delete($sql, $data);

        if ($eliminar_etiqueta == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'etiqueta eliminada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al eliminar la etiqueta';
        }
        return $response;
    }

    public function agregar_etiqueta($nombre_etiqueta, $color_etiqueta, $id_plataforma)
    {
        // Inicializar la respuesta
        $response = $this->initialResponse();


        // Consulta de inserción con la clave única
        $sql = "INSERT INTO `etiquetas_chat_center` (`nombre_etiqueta`, `color_etiqueta`, `id_plataforma`)
           VALUES (?, ?, ?)";
        $data = [$nombre_etiqueta, $color_etiqueta, $id_plataforma];


        // Insertar configuración
        $insertar_configuracion = $this->insert($sql, $data);


        // Verificar si la inserción fue exitosa
        if ($insertar_configuracion == 1) {


            $response['status'] = 200;
            $response['title'] = 'Petición exitosa';
            $response['message'] = 'Configuración agregada y actualizada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error en inserción';
            $response['message'] = $insertar_configuracion['message'];
        }


        return $response;
    }

    public function asignar_etiqueta($idEtiqueta, $id_cliente_chat)
    {
        // Inicializar la respuesta
        $response = $this->initialResponse();


        // Consulta de inserción con la clave única
        $sql_update = "UPDATE `clientes_chat_center` SET `id_etiqueta` = ? WHERE `id` = ?";
        $update_data = [$idEtiqueta, $id_cliente_chat];
        $actualizar_configuracion = $this->update($sql_update, $update_data);

        // Verificar si la inserción fue exitosa
        if ($actualizar_configuracion == 1) {


            $response['status'] = 200;
            $response['title'] = 'Petición exitosa';
            $response['message'] = 'Configuración agregada y actualizada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error en inserción';
            $response['message'] = $actualizar_configuracion['message'];
        }


        return $response;
    }

    /* automatizador */
    public function configuraciones_automatizador($id_plataforma)
    {
        $sql = "SELECT * FROM `configuraciones` WHERE id_plataforma = $id_plataforma";
        return $this->select($sql);
    }

    public function agregar_configuracion($nombre_configuracion, $telefono, $id_telefono, $id_whatsapp, $token, $webhook_url, $id_plataforma)
    {
        // Inicializar la respuesta
        $response = $this->initialResponse();

        // Generar una clave única para la columna 'key_imporsuit'
        $key_imporsuit = $this->generarClaveUnica();

        // Consulta de inserción con la clave única
        $sql = "INSERT INTO `configuraciones` (`id_plataforma`, `nombre_configuracion`, `telefono`, `id_telefono`, `id_whatsapp`, `token`, `key_imporsuit`) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $data = [$id_plataforma, $nombre_configuracion, $telefono, $id_telefono, $id_whatsapp, $token, $key_imporsuit];

        // Insertar configuración
        $insertar_configuracion = $this->insert($sql, $data);

        // Verificar si la inserción fue exitosa
        if ($insertar_configuracion == 1) {
            $sql = "SELECT * FROM configuraciones WHERE key_imporsuit = '$key_imporsuit'";
            $configuracion = $this->select($sql);

            $id_configuracion = $configuracion[0]['id'];

            $webhook_url = "https://new.imporsuitpro.com/public/webhook_whatsapp/webhook.php?id=" . $id_configuracion . "&webhook=" . $webhook_url;

            // Realizar el update usando la clave única 'key_imporsuit'
            $sql_update = "UPDATE `configuraciones` SET `webhook_url` = ? WHERE `key_imporsuit` = ?";
            $update_data = [$webhook_url, $key_imporsuit];
            $actualizar_configuracion = $this->update($sql_update, $update_data);

            if ($actualizar_configuracion == 1) {
                $response['status'] = 200;
                $response['title'] = 'Petición exitosa';
                $response['message'] = 'Configuración agregada y actualizada correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error en actualización';
                $response['message'] = 'Hubo un problema al actualizar la configuración';
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error en inserción';
            $response['message'] = $insertar_configuracion['message'];
        }

        return $response;
    }

    // Función para generar una clave única
    private function generarClaveUnica()
    {
        return uniqid('imp_', true); // Genera una clave única con el prefijo 'imp_' y alta entropía
    }


    public function agregar_automatizador($nombre_automatizador, $id_configuracion)
    {
        // codigo para agregar categoria
        $response = $this->initialResponse();

        $sql = "INSERT INTO `automatizadores` (`id_configuracion`,`nombre`) VALUES (?, ?)";
        $data = [$id_configuracion, $nombre_automatizador];
        $insertar_automatizador = $this->insert($sql, $data);
        if ($insertar_automatizador == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'flotante agregada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $insertar_automatizador['message'];
        }
        return $response;
    }

    public function obtenerPedidoPorTelefono($telefono, $id_plataforma)
    {
        if (str_contains($telefono, '+593')) {
            $telefono = str_replace('+593', '', $telefono);
        } elseif (str_contains($telefono, '593')) {
            $telefono = str_replace('593', '', $telefono);
        }
        // Buscar el pedido por el número de teléfono independientemente si tiene +593 en el número
        $sql = "SELECT * FROM facturas_cot WHERE (telefono = '$telefono' OR telefono = '+593$telefono' OR telefono = '593$telefono'  OR telefono = '0$telefono') AND guia_enviada = 0 AND anulada =0 ORDER BY id_factura DESC LIMIT 5";
        return $this->select($sql);
    }

    public function obtenerDetallesPedido($id_factura)
    {
        $sql = "SELECT * FROM facturas_cot WHERE id_factura = $id_factura";
        $factura =  $this->select($sql);

        $numero_factura = $factura[0]['numero_factura'];

        $sql = "SELECT dfc.id_detalle, dfc.id_factura, dfc.cantidad, dfc.precio_venta, p.nombre_producto, dfc.sku, dfc.id_producto FROM detalle_fact_cot dfc INNER JOIN inventario_bodegas ib ON ib.id_inventario = dfc.id_inventario INNER JOIN productos p ON ib.id_producto = p.id_producto WHERE dfc.numero_factura = '$numero_factura'";
        $productos = $this->select($sql);

        return [
            'factura' => $factura,
            'productos' => $productos
        ];
    }

    public function cancelarPedido($id_pedido)
    {
        $sql = "UPDATE facturas_cot SET anulada = 1 WHERE id_factura = ?";
        $response = $this->update($sql, [$id_pedido]);

        if ($response == 1) {
            $response = [
                'status' => 200,
                'title' => 'Peticion exitosa',
                'message' => 'Pedido cancelado correctamente'
            ];
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => 'Error al cancelar el pedido'
            ];
        }
        return $response;
    }

    public function actualizarDetallePedido($id_detalle, $id_pedido, $cantidad, $precio, $total)
    {
        $sql = "UPDATE detalle_fact_cot SET cantidad = ?, precio_venta = ? WHERE id_detalle = ?";
        $response = $this->update($sql, [$cantidad, $precio, $id_detalle]);

        $sql = "UPDATE facturas_cot SET monto_factura = ? WHERE id_factura = ?";
        $response2 = $this->update($sql, [$total, $id_pedido]);


        if ($response == 1 && $response2 == 1) {
            $response = [
                'status' => 200,
                'title' => 'Peticion exitosa',
                'message' => 'Detalle actualizado correctamente'
            ];
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => 'Error al actualizar el detalle'
            ];
        }
        return $response;
    }
    public function agregarProductoAPedido($id_pedido, $id_producto, $cantidad, $precio, $sku, $id_inventario)
    {
        // Obtener detalles de la factura
        $sql = "SELECT * FROM facturas_cot WHERE id_factura = ?";
        $factura = $this->dselect($sql, [$id_pedido]);
        $numero_factura = $factura[0]['numero_factura'];
        // Verificar si el producto con el mismo id_producto y sku ya existe en la factura
        $sql = "SELECT * FROM detalle_fact_cot WHERE id_factura = ? AND id_producto = ? AND sku = ?";
        $detalleExistente = $this->dselect($sql, [$id_pedido, $id_producto, $sku]);
        if ($detalleExistente) {
            // Producto ya existe, actualizar cantidad y precio si es necesario
            $cantidad += $detalleExistente[0]['cantidad'];
            $precio = max($precio, $detalleExistente[0]['precio_venta']); // Tomar el mayor precio
            $sql = "UPDATE detalle_fact_cot SET cantidad = ?, precio_venta = ? WHERE id_factura = ? AND id_producto = ? AND sku = ?";
            $data = [$cantidad, $precio, $id_pedido, $id_producto, $sku];
            $response = $this->update($sql, $data);
        } else {
            // Obtener detalles del inventario, incluyendo el costo del producto (pcp)
            $sql = "SELECT * FROM inventario_bodegas WHERE id_producto = ?";
            $inventario = $this->dselect($sql, [$id_producto]);
            $costo_unitario = $inventario[0]['pcp'];
            // Insertar el detalle del producto en la factura
            $sql = "INSERT INTO detalle_fact_cot (id_factura, id_producto, cantidad, precio_venta, id_plataforma, sku, numero_factura, id_inventario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [$id_pedido, $id_producto, $cantidad, $precio, $factura[0]['id_plataforma'], $sku, $numero_factura, $id_inventario];
            $response = $this->insert($sql, $data);
        }
        // Obtener el detalle de todos los productos en la factura
        $sql = "SELECT * FROM detalle_fact_cot WHERE id_factura = ?";
        $detalle = $this->dselect($sql, [$id_pedido]);
        $total = 0;
        $total_costo = 0;
        // Calcular el total de la venta y el total de costos
        foreach ($detalle as $item) {
            $total += $item['precio_venta'] * $item['cantidad'];
            $sql = "SELECT pcp FROM inventario_bodegas WHERE id_producto = ?";
            $item_inventario = $this->dselect($sql, [$item['id_producto']]);
            $costo_unitario = $item_inventario[0]['pcp'];
            $total_costo += $costo_unitario * $item['cantidad'];
        }
        // Actualizar la factura con el monto total de la venta y el costo total
        $sql = "UPDATE facturas_cot SET monto_factura = ?, costo_producto = ? WHERE id_factura = ?";
        $response2 = $this->update($sql, [$total, $total_costo, $id_pedido]);
        // Retornar respuesta según el éxito de las operaciones
        if ($response && $response2 == 1) {
            $response = [
                'status' => 200,
                'title' => 'Peticion exitosa',
                'message' => 'Producto agregado correctamente'
            ];
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => $response['message'] ?? "Error al agregar el producto" . $response2['message'] ?? "Error al agregar el producto"
            ];
        }
        return $response;
    }
    public function eliminarProductoDePedido($id_detalle)
    {
        // Obtener el id de la factura a la que pertenece el detalle
        $sql = "SELECT id_factura FROM detalle_fact_cot WHERE id_detalle = ?";
        $id_pedido = $this->dselect($sql, [$id_detalle]);
        $id_pedido = $id_pedido[0]['id_factura'];
        // Eliminar el detalle del producto de la factura
        $sql = "DELETE FROM detalle_fact_cot WHERE id_detalle = ?";
        $response = $this->delete($sql, [$id_detalle]);
        // Obtener el detalle de todos los productos en la factura
        $sql = "SELECT * FROM detalle_fact_cot WHERE id_factura = ?";
        $detalle = $this->dselect($sql, [$id_pedido]);
        $total = 0;
        $total_costo = 0;
        // Calcular el total de la venta y el total de costos
        foreach ($detalle as $item) {
            $total += $item['precio_venta'] * $item['cantidad'];
            $sql = "SELECT pcp FROM inventario_bodegas WHERE id_producto = ?";
            $item_inventario = $this->dselect($sql, [$item['id_producto']]);
            $costo_unitario = $item_inventario[0]['pcp'];
            $total_costo += $costo_unitario * $item['cantidad'];
        }
        // Actualizar la factura con el monto total de la venta y el costo total
        $sql = "UPDATE facturas_cot SET monto_factura = ?, costo_producto = ? WHERE id_factura = ?";
        $response2 = $this->update($sql, [$total, $total_costo, $id_pedido]);
        // Retornar respuesta según el éxito de las operaciones
        if ($response && $response2 == 1) {
            $response = [
                'status' => 200,
                'title' => 'Peticion exitosa',
                'message' => 'Producto eliminado correctamente'
            ];
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => $response['message'] ?? "Error al eliminar el producto" . $response2['message'] ?? "Error al eliminar el producto"
            ];
        }
        return $response;
    }

    public function actualizarContienePedido($id_pedido, $contiene)
    {
        $sql = "UPDATE facturas_cot SET contiene = ? WHERE id_factura = ?";
        $response = $this->update($sql, [$contiene, $id_pedido]);
        if ($response == 1) {
            $response = [
                'status' => 200,
                'title' => 'Peticion exitosa',
                'message' => 'Contiene actualizado correctamente'
            ];
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => 'Error al actualizar el contiene'
            ];
        }
        return $response;
    }

    public function buscarProductosTiendas($id_plataforma)
    {
        $sql = "SELECT pt.nombre_producto_tienda, pt.id_producto, pt.id_inventario, pt.pvp_tienda as 'precio', ib.pcp, ib.sku FROM `productos_tienda` pt INNER JOIN `inventario_bodegas` ib ON ib.id_inventario = pt.id_inventario where pt.id_plataforma = $id_plataforma";
        return $this->select($sql);
    }

    public function obtener_templates($id_plataforma, $palabra_busqueda)
    {
        $sql = "SELECT * FROM `templates_chat_center` WHERE id_plataforma = $id_plataforma AND (atajo LIKE '%$palabra_busqueda%' OR mensaje LIKE '%$palabra_busqueda%')";
        return $this->select($sql);
    }
}
