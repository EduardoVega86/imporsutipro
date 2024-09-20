<?php

class HomeModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function validarRefiere($id)
    {
        if ($id == "") {
            return false;
        }
        $sql = "SELECT * FROM plataformas WHERE token_referido = ?";
        $params = [$id];
        $result = $this->simple_select($sql, $params);
        if ($result > 0) {
            return true;
        }
        return false;
    }
    
    
    public function consulta_notificaciones($plataforma) 
{
    // Consulta para guías atrasadas
    $sql1 = "SELECT  COUNT(fc.numero_guia) AS cantidad_guias
    FROM facturas_cot fc 
    LEFT JOIN ciudad_cotizacion cc ON cc.id_cotizacion = fc.ciudad_cot 
    LEFT JOIN plataformas p ON p.id_plataforma = fc.id_plataforma
    LEFT JOIN plataformas tp ON tp.id_plataforma = fc.id_propietario
    LEFT JOIN bodega b ON b.id = fc.id_bodega 
    LEFT JOIN paises_hispanos ph ON ph.codigo = tp.pais
    WHERE (fc.id_propietario=1185 or fc.id_plataforma=1185)  
    AND TRIM(fc.numero_guia) <> '' 
    AND fc.numero_guia IS NOT NULL 
    AND fc.numero_guia <> '0' 
    AND fc.anulada = 0  
    AND ((estado_guia_sistema IN (100, 102, 103) AND id_transporte = 2) 
    OR (estado_guia_sistema IN (1, 2, 3, 4) AND id_transporte = 1) 
    OR (estado_guia_sistema IN (1, 2, 3) AND id_transporte = 3) 
    OR (estado_guia_sistema IN (2) AND id_transporte = 4)) 
    AND TIMESTAMPDIFF(HOUR, fc.fecha_factura, NOW()) > 24
    GROUP BY fc.id_propietario, tp.nombre_tienda, tp.whatsapp, tp.contacto, ph.prefijo_telefono 
    ORDER BY cantidad_guias DESC;"; 

    $result1 = $this->select($sql1);
    $cantidad_guias = isset($result1[0]['cantidad_guias']) ? $result1[0]['cantidad_guias'] : 0;

    // Consulta para novedades
    $sql2 = "SELECT COUNT(*) AS cantidad_novedades 
    FROM novedades 
    WHERE id_plataforma=2324 AND solucionada=0 AND terminado=0;"; 

    $result2 = $this->select($sql2);
    $cantidad_novedades = isset($result2[0]['cantidad_novedades']) ? $result2[0]['cantidad_novedades'] : 0;

    // Crear el JSON con la estructura deseada
    $response = [
        ['nombre' => 'GUIAS ATRASADAS', 'cantidad' => $cantidad_guias],
        ['nombre' => 'NOVEDADES', 'cantidad' => $cantidad_novedades]
    ];

    return json_encode($response);
}


}
