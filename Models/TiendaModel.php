<?php
class TiendaModel extends Query
{
    public function crearSubdominio($nombre_tienda, $plataforma)
    {
        $cpanelUrl = 'https://administracion.imporsuitpro.com:2083/';
        $cpanelUsername = 'imporsuitpro';
        $cpanelPassword = 'Mark2demasiado..';
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

    public function categoriastienda($id_plataforma)
    {
        $sql = "SELECT * FROM lineas WHERE tipo='1' AND online=1";
        
        return $this->select($sql);
    }

    public function obtener_idPlataforma($url)
    {
        $sql = "SELECT * FROM lineas WHERE tipo='1' AND online=1";
        
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
    
    public function agregarCaracteristicas($nombre , $plataforma)
    {
        // codigo para agregar categoria
        $response = $this->initialResponse();

        $sql = "INSERT INTO caracteristicas_tienda (nombre_linea, descripcion_linea, estado_linea, date_added, online, imagen, tipo, padre, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $plataforma];
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
    
    public function actualizar_tienda($nombre_tienda, $descripcion_tienda, $ruc_tienda,$telefono_tienda, $email_tienda, $direccion_tienda, $pais_tienda,$paltaforma)
    {
        $response = $this->initialResponse();
        $sql = "UPDATE `plataformas` SET `nombre_tienda` = ?, `nombre_tienda` = ? , `cedula_facturacion` = ?, `whatsapp` = ?, `email` = ?  WHERE `plataformas`.`id_plataforma` = ? ";
        $data = [$codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $id, $plataforma];
        $editar_producto = $this->update($sql, $data);

      
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
    
}
