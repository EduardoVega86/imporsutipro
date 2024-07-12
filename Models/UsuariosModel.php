<?php
class UsuariosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    ///userGroup

    public function obtener_userGroup($plataforma)
    {
        $sql = "SELECT ib.*, p.* FROM `inventario_bodegas` AS ib INNER JOIN `productos` AS p ON p.`id_producto` = ib.`id_producto` WHERE ib.`id_plataforma` = $plataforma";
        return $this->select($sql);
    }

    public function obtener_usuarios_matriz()
    {
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];
        // echo $id_matriz;
        $sql = "SELECT * FROM  usuario_plataforma, users, plataformas WHERE usuario_plataforma.id_usuario=users.id_users AND plataformas.id_plataforma=usuario_plataforma.id_plataforma and plataformas.id_matriz=$id_matriz;";
        return $this->select($sql);
    }

    public function obtener_usuarios_plataforma($plataforma)
    {
        // $id_matriz = $this->obtenerMatriz();
        $sql = "SELECT * FROM  usuario_plataforma, users, plataformas WHERE usuario_plataforma.id_usuario=users.id_users AND plataformas.id_plataforma=usuario_plataforma.id_plataforma and plataformas.id_plataforma=$plataforma;";
        return $this->select($sql);
    }

    //ususarios

    public function resetearContrasena($id_usuario, $contrasena)
    {
        $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = " UPDATE `users` SET `con_users` =? WHERE `id_users` = ?";
        $data = [$contrasena, $id_usuario];
        $editar_producto = $this->update($sql, $data);
        if ($editar_producto == 1) {
            $responses = array('status' => 200, 'title' => 'Peticion exitosa', 'message' => 'Contraseña actualizada correctamente');
        } else {
            $responses = array('status' => 500, 'title' => 'Error', 'message' => $editar_producto['message']);
        }
        return $responses;
    }
    public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda)
    {
        //echo 'asd';
        //Inicia la respuesta
        $response = $this->initialResponse();

        //Se general el usuario
        $date_added       = date("Y-m-d H:i:s");
        /* $sql = "INSERT INTO users (nombre, correo, pais, telefono, contrasena) VALUES (?, ?, ?, ?, ?)"; */

        $prefix = 'tmp_';
        $timestamp = time();
        $uniqueId = uniqid();
        $tienda = $prefix . $timestamp . '_' . $uniqueId;

        $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (nombre_users, email_users, con_users, usuario_users, date_added, cargo_users) VALUES (?, ?, ?, ?, ?, ?)";
        //   echo $sql;
        $data = [$nombre, $correo, $contrasena, $correo, $date_added, 1];
        $insertar_usuario = $this->insert($sql, $data);
        print_r($insertar_usuario);
        //echo 'erro'.$insertar_usuario;;
        if ($insertar_usuario == 1) {
            $id = $this->select("SELECT id_users FROM users WHERE usuario_users = '$correo'");

            $id_matriz = $this->obtenerMatriz();
            //print_r($id_matriz);
            $id_matriz = $id_matriz[0]['idmatriz'];
            //print_r($id);
            //Se genera la plataforma
            $sql = "INSERT INTO plataformas (`nombre_tienda`, `contacto`, `whatsapp`, `fecha_ingreso`, `fecha_actualza`, `id_plan`, `url_imporsuit`, `carpeta_servidor`, `email`,  `referido`, `token_referido`, `refiere`, `pais`, `id_matriz`) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $data = [$tienda, $nombre, $telefono, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 1, 'https://' . $tienda . '.' . DOMINIO, '/public_html/' . $tienda, $correo, 0, '', '', $pais, $id_matriz];
            $insertar_plataforma = $this->insert($sql, $data);
            //  print_r($insertar_plataforma);
            //si se guarda correctamente la plataforma 
            if ($insertar_plataforma == 1) {
                $idPlataforma = $this->select("SELECT id_plataforma FROM plataformas WHERE email = '$correo'");
                //print_r($idPlataforma);
                $sql = "INSERT INTO `perfil` ( `nombre_empresa`,`telefono`, `whatsapp`,  `id_plataforma`) VALUES (?,?,?,?)";
                $data = [$tienda, $telefono, $telefono, $idPlataforma[0]['id_plataforma']];
                $insertar_perfil = $this->insert($sql, $data);
                // print_r($insertar_perfil);
                if ($insertar_perfil == 1) {

                    $sql = "INSERT INTO usuario_plataforma (id_usuario, id_plataforma) VALUES (?, ?)";
                    $data = [$id[0]['id_users'], $idPlataforma[0]['id_plataforma']];
                    $insertar_relacion = $this->insert($sql, $data);


                    if ($insertar_relacion == 1) {
                        $id_plataforma = $idPlataforma[0]['id_plataforma'];
                        $sql_caracteristicas = "INSERT INTO `caracteristicas_tienda` (`id_producto`, `texto`, `icon_text`, `enlace_icon`, `subtexto_icon`, `accion`, `id_plataforma`) VALUES
                        (0, 'Envío Gratis a todo el País', 'fa-check', '', 'Llegamos a todo el País', 1, $id_plataforma),
                        (0, 'Pago Contra Entrega', 'fa-lock', NULL, 'Paga cuando recibes el producto', 2, $id_plataforma),
                        (0, 'Atención al cliente', 'fa-headset', NULL, 'Soporte 100% garantizado', 2, $id_plataforma);";

                        $registro_caracteristicas = $this->simple_insert($sql_caracteristicas);

                        $sql_bodega = "INSERT INTO `bodega`(`nombre`,`id_empresa`,`responsable`,`contacto`,`id_plataforma`)VALUES
                       ('$tienda',$id_plataforma,'$nombre','$telefono',$id_plataforma);";

                        $registro_bodega = $this->simple_insert($sql_bodega);

                        //echo $registro_caracteristicas;
                        // print_r($registro_caracteristicas);
                        if ($registro_caracteristicas > 0 && $registro_bodega > 0) {
                            $response['status'] = 200;
                            $response['title'] = 'Peticion exitosa';
                            $response['message'] = 'Usuario registrado correctamente';
                            $response['data'] = ['id' => $id[0]['id_users'], 'idPlataforma' => $idPlataforma[0]['id_plataforma']];
                            //session_start();


                        }
                    }
                } else {
                    $response['message'] = "Error al crear el perfil";
                }
            } else {
                $response['message'] = "Error al crear la plataforma! Intentelo nuevamente";
                //$borrar_usuario=$this->delete($sql, $data);
            }

            //Se genera la relacion

        } else {

            $response['message'] = "El usuario $correo ya existe en la base de datos, intente con otro correo electrónico!";
            //$id = $this->select("SELECT users_id FROM users WHERE correo = '$correo'");

        }


        return $response;
    }

    public function agregarProveedor($plataforma, $proveedor)
    {
        $response = $this->initialResponse();

        $sql = "UPDATE `plataformas` SET `proveedor` = ? WHERE `plataformas`.`id_plataforma` = ?";
        $data = [$proveedor, $plataforma];
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


    public function guardar_imagen_logo($imagen, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/logos_tienda/";
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

                $sql = "UPDATE perfil SET logo_url  = ? WHERE id_plataforma = ?";
                $data = [$target_file,  $plataforma];
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


    public function guardar_imagen_favicon($imagen, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/favicon/";
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

                $sql = "UPDATE perfil SET favicon  = ? WHERE id_plataforma = ?";
                $data = [$target_file,  $plataforma];
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

    /* tienda online */
    public function obtener_infoTiendaOnline($plataforma)
    {
        $sql = "SELECT * FROM plataformas pl, perfil pe WHERE pl.id_plataforma=$plataforma and pe.id_plataforma=pl.id_plataforma;";

        return $this->select($sql);
    }

    public function obtener_bannertienda($plataforma)
    {
        $sql = "SELECT * FROM banner_adicional WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function obtener_bannertiendaID($id, $plataforma)
    {
        $sql = "SELECT * FROM banner_adicional WHERE id_plataforma = $plataforma AND id = $id";
        return $this->select($sql);
    }

    public function agregarBanner($titulo, $texto_banner, $texto_boton, $enlace_boton, $alineacion, $imagen, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/banner/";
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

                $sql = "INSERT INTO `banner_adicional` (`fondo_banner`,`titulo`,`texto_banner`,`texto_boton`,`enlace_boton`,`alineacion`, `id_plataforma`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $data = [$target_file, $titulo, $texto_banner, $texto_boton, $enlace_boton, $alineacion, $plataforma];
                $insertar_banner = $this->insert($sql, $data);
                if ($insertar_banner == 1) {
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

    public function editarBanner($id, $titulo, $texto_banner, $texto_boton, $enlace_boton, $alineacion, $imagen, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/banner/";
        $target_file = "";
        $uploadOk = 1;
        $imageFileType = "";

        if ($imagen && $imagen["tmp_name"]) {
            $target_file = $target_dir . basename($imagen["name"]);
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
        }

        if ($uploadOk == 1 && $imagen && $imagen["tmp_name"]) {
            if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida correctamente';
                $response['data'] = $target_file;

                $sql = "UPDATE `banner_adicional` SET `fondo_banner`=?, `titulo`=?, `texto_banner`=?, `texto_boton`=?, `enlace_boton`=?, `alineacion`=?, `id_plataforma`=? WHERE `id`=?";
                $data = [$target_file, $titulo, $texto_banner, $texto_boton, $enlace_boton, $alineacion, $plataforma, $id];
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
                return $response;
            }
        } else {
            $sql = "UPDATE `banner_adicional` SET `titulo`=?, `texto_banner`=?, `texto_boton`=?, `enlace_boton`=?, `alineacion`=?, `id_plataforma`=? WHERE `id`=?";
            $data = [$titulo, $texto_banner, $texto_boton, $enlace_boton, $alineacion, $plataforma, $id];
        }

        $actualizar_banner = $this->update($sql, $data);

        if ($actualizar_banner == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Banner actualizado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al actualizar el banner';
        }

        return $response;
    }



    public function eliminarBanner($id, $plataforma)
    {
        // codigo para eliminar categoria
        $response = $this->initialResponse();

        $sql = "DELETE FROM banner_adicional WHERE id = ? AND id_plataforma = ?";
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
    /* Fin tienda online */
    
    
   public function crearSubdominio($nombre_tienda, $plataforma)
{
    $cpanelUrl = 'https://administracion.imporsuitpro.com:2083/';
    $cpanelUsername = 'imporsuitpro';
    $cpanelPassword = 'Mark2demasiado..';
    $rootdomain = DOMINIO;

    $repositoryUrl = "https://github.com/DesarrolloImporfactory/tienda";
    $repositoryName = "tienda";

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
    
    $url_repositorio="/home/$cpanelUsername/public_html/$nombre_tienda";
    
    // Verifica que el método `cpanelRequest` esté definido y maneja errores
    if (method_exists($this, 'cpanelRequest')) {
        $response = $this->cpanelRequest($apiUrl, $cpanelUsername, $cpanelPassword, http_build_query($postFields));
        if ($response === false) {
            throw new Exception("Error al clonar el repositorio de GitHub.");
        }
    } else {
        throw new Exception("El método cpanelRequest no está definido.");
    }

    // Crear subdominio
    $apiUrl = $cpanelUrl . 'execute/SubDomain/addsubdomain?domain=' . $nombre_tienda . '&rootdomain=' . $rootdomain;
    $response = $this->cpanelRequest($apiUrl, $cpanelUsername, $cpanelPassword);
    if ($response === false) {
        throw new Exception("Error al crear el subdominio.");
    }
}

 public function cpanelRequest($url, $username, $password, $postFields = null)
    {
     
        global $verificador;
        
        echo $url;
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
        
        $file = __DIR__ . '/config/config.php';

    // Verifica si el archivo existe antes de intentar leerlo
    if (file_exists($file)) {
        // Lee el contenido del archivo
        $content = file_get_contents($file);

        // Reemplaza el texto específico
        $newContent = str_replace('@', 'nuevo_texto', $content);

        // Escribe el contenido modificado de nuevo en el archivo
        file_put_contents($file, $newContent);

        echo "El archivo ha sido actualizado.";
    } else {
        echo "El archivo config.php no se encuentra en la carpeta config.";
    }
    
        curl_close($ch);
    }
    

}
