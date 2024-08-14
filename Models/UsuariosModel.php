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

    public function obtener_plataformas()
    {
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];
        $sql = "SELECT 
    p.id_plataforma, 
    p.id_matriz, 
    p.nombre_tienda,
    p.url_imporsuit,
    p.whatsapp,
    p.email,
    p.contacto,
    (select marca from matriz WHERE idmatriz=p.id_matriz) as matriz,
    CASE 
        WHEN pm.id_plataforma IS NOT NULL THEN 1 
        ELSE 0 
    END AS Existe
FROM 
    plataformas p
LEFT JOIN 
    plataforma_matriz pm 
ON 
    p.id_plataforma = pm.id_plataforma 
    AND pm.id_matriz = $id_matriz WHERE 
    p.proveedor = 1";
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

                            $webhookData = [
                                'tienda' => $tienda,
                                'nombre' => $nombre,
                                'telefono' => $telefono,
                                'fecha' => date('Y-m-d H:i:s')
                            ];

                            // URL del webhook
                            $webhookUrl = 'https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjUwNTY0MDYzNTA0M2M1MjY0NTUzZDUxMzUi_pc';

                            // Inicializar cURL
                            $ch = curl_init($webhookUrl);

                            // Configurar opciones de cURL
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($webhookData));
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Content-Type: application/x-www-form-urlencoded'
                            ]);

                            // Ejecutar la solicitud cURL
                            $response = curl_exec($ch);

                            // Verificar si hubo errores
                            if ($response === false) {
                                $error = curl_error($ch);
                                // Manejar el error
                                echo "Error al enviar la solicitud: $error";
                            } else {
                                // Manejar la respuesta
                                echo "Solicitud enviada correctamente. Respuesta: $response";
                            }

                            // Cerrar cURL
                            curl_close($ch);

                            //fin webhook


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

    public function quitarTienda($plataforma)
    {
        $response = $this->initialResponse();

        // Obtén el id_matriz
        $id_matriz = $this->obtenerMatriz();
        $id_matriz = $id_matriz[0]['idmatriz'];

        // Verifica si el registro ya existe en la tabla plataforma_matriz
        $registroExistente = $this->select(
            "SELECT * FROM plataforma_matriz WHERE id_plataforma = $plataforma AND id_matriz = $id_matriz"
        );

        if (!empty($registroExistente)) {
            // El registro existe, entonces elimínalo
            $sql = "DELETE FROM plataforma_matriz WHERE id_plataforma = ? AND id_matriz = ?";
            $data = [$plataforma, $id_matriz];
            $eliminar = $this->delete($sql, $data);

            if ($eliminar = 1) {
                $response['status'] = 200;
                $response['title'] = 'Petición exitosa';
                $response['message'] = 'Registro eliminado correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'No se pudo eliminar el registro';
            }
        } else {
            // El registro no existe, entonces agrégalo
            $sql = "INSERT INTO plataforma_matriz (id_plataforma, id_matriz) VALUES (?, ?)";
            $data = [$plataforma, $id_matriz];
            $insertar_relacion = $this->insert($sql, $data);

            if ($insertar_relacion == 1) {
                $response['status'] = 200;
                $response['title'] = 'Petición exitosa';
                $response['message'] = 'Registro agregado correctamente';
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'No se pudo agregar el registro';
            }
        }

        return $response;
    }


    public function guardar_imagen_logo($imagen, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/logos_tienda/";
        $imageFileType = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));

        // Generar un nombre de archivo único
        $unique_name = uniqid('', true) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Verificar si el archivo existe y agregar un diferenciador si es necesario
        $original_target_file = $target_file;
        $counter = 1;
        while (file_exists($target_file)) {
            $target_file = $target_dir . uniqid('', true) . '.' . $imageFileType;
            $counter++;
        }

        $uploadOk = 1;
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
            if ($uploadOk == 1 && move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida correctamente';
                $response['data'] = $target_file;

                $sql = "UPDATE perfil SET logo_url = ? WHERE id_plataforma = ?";
                $data = [$target_file, $plataforma];
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
        $target_dir = "public/img/favicon_tienda/";
        $imageFileType = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));

        // Generar un nombre de archivo único
        $unique_name = uniqid('', true) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        // Verificar si el archivo existe y agregar un diferenciador si es necesario
        $original_target_file = $target_file;
        $counter = 1;
        while (file_exists($target_file)) {
            $target_file = $target_dir . uniqid('', true) . '.' . $imageFileType;
            $counter++;
        }

        $uploadOk = 1;
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
            if ($uploadOk == 1 && move_uploaded_file($imagen["tmp_name"], $target_file)) {
                $response['status'] = 200;
                $response['title'] = 'Peticion exitosa';
                $response['message'] = 'Imagen subida correctamente';
                $response['data'] = $target_file;

                $sql = "UPDATE perfil SET favicon = ? WHERE id_plataforma = ?";
                $data = [$target_file, $plataforma];
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


    public function obtener_tiendas()
    {
        $sql = "SELECT id_plataforma, nombre_tienda FROM plataformas";

        return $this->select($sql);
    }

    /* tienda online */
    public function obtener_infoTiendaOnline($plataforma)
    {
        $sql = "SELECT * FROM plataformas pl, perfil pe WHERE pl.id_plataforma=$plataforma and pe.id_plataforma=pl.id_plataforma;";

        return $this->select($sql);
    }

    public function obtener_ofertas_plantilla2($plataforma)
    {
        $sql = "SELECT * FROM plantilla_2 WHERE id_plataforma=$plataforma";
        return $this->select($sql);
    }

    public function obtener_bannertienda($plataforma)
    {
        $sql = "SELECT * FROM banner_adicional WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function obtener_caracteristicas($plataforma)
    {
        $sql = "SELECT * FROM caracteristicas_tienda WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function obtener_caracteristica_ids($id, $plataforma)
    {
        $sql = "SELECT * FROM caracteristicas_tienda WHERE id_plataforma = $plataforma AND id = $id";

        return $this->select($sql);
    }

    public function editarIcono($id, $texto, $subtexto_icon, $enlace_icon, $icon_text, $id_plataforma)
    {
        $response = $this->initialResponse();
        $sql = "UPDATE `caracteristicas_tienda` SET `texto`=?,"
            . "`subtexto_icon`=?,`enlace_icon`=?,"
            . "`icon_text`=? WHERE id_plataforma = ? AND id = ?;";
        // echo $sql;
        $data = [$texto, $subtexto_icon, $enlace_icon,  $icon_text, $id_plataforma, $id];
        //  print_r($data);
        $editar_icono = $this->update($sql, $data);

        // print_r($editar_icono);
        if ($editar_icono == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Icono editado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $editar_icono['message'];
        }
        return $response;
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


    public function obtener_testimonios($plataforma)
    {
        $sql = "SELECT * FROM testimonios WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function obtener_testimoniotiendaID($id, $plataforma)
    {
        $sql = "SELECT * FROM testimonios WHERE id_plataforma = $plataforma AND id_testimonio = $id";
        return $this->select($sql);
    }

    public function agregarTestimonios($nombre, $testimonio, $imagen, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/testimonios/";
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

                $sql = "INSERT INTO `testimonios` (`imagen`,`nombre`,`testimonio`,`id_plataforma`,`status`, `id_producto`) VALUES (?, ?, ?, ?, ?, ?)";
                $data = [$target_file, $nombre, $testimonio, $plataforma, 1, 0];
                $insertar_testimonio = $this->insert($sql, $data);

                if ($insertar_testimonio == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Imagen subida correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = $insertar_testimonio["message"];
                }
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
            }
        }
        return $response;
    }

    public function eliminarTestimonio($id, $plataforma)
    {
        // codigo para eliminar categoria
        $response = $this->initialResponse();

        $sql = "DELETE FROM testimonios WHERE id_testimonio = ? AND id_plataforma = ?";
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

    public function editarTestimonio($id_testimonio, $nombre, $testimonio, $imagen, $plataforma)
    {
        $response = $this->initialResponse();
        $target_dir = "public/img/testimonios/";
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

                $sql = "UPDATE `testimonios` SET `imagen`=?, `nombre`=?, `testimonio`=? WHERE `id_testimonio`=?";
                $data = [$target_file, $nombre, $testimonio, $id_testimonio];
            } else {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al subir la imagen';
                return $response;
            }
        } else {
            $sql = "UPDATE `testimonios` SET `nombre`=?, `testimonio`=? WHERE `id_testimonio`=?";
            $data = [$nombre, $testimonio, $id_testimonio];
        }

        $actualizar_testimonio = $this->update($sql, $data);

        if ($actualizar_testimonio == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'testimonio actualizado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al actualizar el testimonio';
        }

        return $response;
    }

    public function obtener_horizontalTienda($plataforma)
    {
        $sql = "SELECT * FROM horizontal WHERE id_plataforma = $plataforma";

        return $this->select($sql);
    }

    public function obtener_horizontaltiendaID($id, $plataforma)
    {
        $sql = "SELECT * FROM horizontal WHERE id_plataforma = $plataforma AND id_horizontal = $id";
        return $this->select($sql);
    }

    public function agregarHorizontal($texto, $estado, $posicion, $plataforma)
    {
        // codigo para agregar categoria
        $response = $this->initialResponse();

        $sql = "INSERT INTO `horizontal` (`texto`,`estado`,`posicion`,`id_plataforma`) VALUES (?, ?, ?, ?)";
        $data = [$texto, $estado, $posicion, $plataforma];
        $insertar_flotante = $this->insert($sql, $data);
        if ($insertar_flotante == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'flotante agregada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = $insertar_flotante['message'];
        }
        return $response;
    }

    public function editarHorizontal($id_horizontal, $texto, $estado, $posicion, $plataforma)
    {
        // codigo para editar categoria
        $response = $this->initialResponse();

        $sql = "UPDATE horizontal SET texto = ?, estado = ?, posicion = ? WHERE id_horizontal = ? AND id_plataforma = ?";
        $data = [$texto, $estado, $posicion, $id_horizontal, $plataforma];
        $editar_horizontal = $this->update($sql, $data);
        if ($editar_horizontal == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'horizontal editada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al editar la horizontal';
        }
        return $response;
    }

    public function eliminarHorizontal($id, $plataforma)
    {
        // codigo para eliminar categoria
        $response = $this->initialResponse();

        $sql = "DELETE FROM horizontal WHERE id_horizontal = ? AND id_plataforma = ?";
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

    public function agregarOfertas(
        $titulo_oferta1,
        $oferta1,
        $descripcion_oferta1,
        $texto_btn_oferta1,
        $enlace_oferta1,
        $imagen1,
        $titulo_oferta2,
        $oferta2,
        $descripcion_oferta2,
        $texto_btn_oferta2,
        $enlace_oferta2,
        $imagen2,
        $plataforma
    ) {
        $response = $this->initialResponse();
        $target_dir = "public/img/ofertas_plantilla2/";

        // Función para procesar cada imagen
        function procesarImagen($imagen, $target_dir, &$response)
        {
            if (empty($imagen) || $imagen['error'] == UPLOAD_ERR_NO_FILE) {
                return null; // Retornar null si no se envía una imagen
            }

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
                    $response['data'][] = $target_file; // Guardamos la ruta de la imagen en el response
                    return $target_file; // Retornamos la ruta para su uso en la inserción SQL
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al subir la imagen';
                    return false;
                }
            }
            return false;
        }

        // Procesar ambas imágenes
        $imagen1_url = procesarImagen($imagen1, $target_dir, $response);
        $imagen2_url = procesarImagen($imagen2, $target_dir, $response);

        if ($imagen1_url !== false && $imagen2_url !== false) {
            // Verificar si ya existe un registro con el id_plataforma dado
            $sql_select = "SELECT * FROM `plantilla_2` WHERE `id_plataforma` = ?";
            $existing_entry = $this->select($sql_select, [$plataforma]);

            if ($existing_entry) {
                // Si existe, construir la consulta UPDATE dinámicamente
                $sql_update = "UPDATE `plantilla_2` SET 
                                `titulo_oferta1` = ?, `oferta1` = ?, `descripcion_oferta1` = ?, `texto_btn_oferta1` = ?, `enlace_oferta1` = ?, 
                                `titulo_oferta2` = ?, `oferta2` = ?, `descripcion_oferta2` = ?, `texto_btn_oferta2` = ?, `enlace_oferta2` = ?";

                $data_update = [
                    $titulo_oferta1, $oferta1, $descripcion_oferta1, $texto_btn_oferta1, $enlace_oferta1,
                    $titulo_oferta2, $oferta2, $descripcion_oferta2, $texto_btn_oferta2, $enlace_oferta2
                ];

                if ($imagen1_url !== null) {
                    $sql_update .= ", `imagen_oferta1` = ?";
                    $data_update[] = $imagen1_url;
                }

                if ($imagen2_url !== null) {
                    $sql_update .= ", `imagen_oferta2` = ?";
                    $data_update[] = $imagen2_url;
                }

                $sql_update .= " WHERE `id_plataforma` = ?";
                $data_update[] = $plataforma;

                $actualizar_ofertas = $this->update($sql_update, $data_update);
                if ($actualizar_ofertas == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Datos actualizados correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al actualizar los datos en la base de datos';
                }
            } else {
                // Si no existe, realizar un INSERT
                $sql_insert = "INSERT INTO `plantilla_2` (
                                `id_plataforma`, `titulo_oferta1`, `oferta1`, `descripcion_oferta1`, `texto_btn_oferta1`, 
                                `enlace_oferta1`, `imagen_oferta1`, `titulo_oferta2`, `oferta2`, `descripcion_oferta2`, 
                                `texto_btn_oferta2`, `enlace_oferta2`, `imagen_oferta2`
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $data_insert = [
                    $plataforma, $titulo_oferta1, $oferta1, $descripcion_oferta1, $texto_btn_oferta1,
                    $enlace_oferta1, $imagen1_url, $titulo_oferta2, $oferta2, $descripcion_oferta2,
                    $texto_btn_oferta2, $enlace_oferta2, $imagen2_url
                ];

                $insertar_ofertas = $this->insert($sql_insert, $data_insert);
                if ($insertar_ofertas == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Datos insertados correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al insertar los datos en la base de datos';
                }
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al procesar las imágenes';
        }

        return $response;
    }

    public function agregarPromocion(
        $titulo_promocion,
        $precio_promocion,
        $descripcion_promocion,
        $texto_btn_promocion,
        $enlace_btn_promocion,
        $imagen_promocion,
        $plataforma
    ) {
        $response = $this->initialResponse();
        $target_dir = "public/img/promocion/";

        // Función para procesar la imagen
        function procesarImagenPromocion($imagen, $target_dir, &$response)
        {
            if (empty($imagen) || $imagen['error'] == UPLOAD_ERR_NO_FILE) {
                return null; // Retornar null si no se envía una imagen
            }

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
                    $response['data'][] = $target_file; // Guardamos la ruta de la imagen en el response
                    return $target_file; // Retornamos la ruta para su uso en la inserción SQL
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al subir la imagen';
                    return false;
                }
            }
            return false;
        }

        // Procesar la imagen de la promoción
        $imagen_promocion_url = procesarImagenPromocion($imagen_promocion, $target_dir, $response);

        if ($imagen_promocion_url !== false) {
            // Verificar si ya existe un registro con el id_plataforma dado
            $sql_select = "SELECT * FROM `plantilla_2` WHERE `id_plataforma` = ?";
            $existing_entry = $this->select($sql_select, [$plataforma]);

            if ($existing_entry) {
                // Si existe, construir la consulta UPDATE dinámicamente
                $sql_update = "UPDATE `plantilla_2` SET 
                                `titulo_promocion` = ?, `precio_promocion` = ?, `descripcion_promocion` = ?, `texto_btn_promocion` = ?, `enlace_btn_promocion` = ?";

                $data_update = [
                    $titulo_promocion, $precio_promocion, $descripcion_promocion, $texto_btn_promocion, $enlace_btn_promocion
                ];

                if ($imagen_promocion_url !== null) {
                    $sql_update .= ", `imagen_promocion` = ?";
                    $data_update[] = $imagen_promocion_url;
                }

                $sql_update .= " WHERE `id_plataforma` = ?";
                $data_update[] = $plataforma;

                $actualizar_promocion = $this->update($sql_update, $data_update);
                if ($actualizar_promocion == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Datos actualizados correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al actualizar los datos en la base de datos';
                }
            } else {
                // Si no existe, realizar un INSERT
                $sql_insert = "INSERT INTO `plantilla_2` (
                                `id_plataforma`, `titulo_promocion`, `precio_promocion`, `descripcion_promocion`, `texto_btn_promocion`, 
                                `enlace_btn_promocion`, `imagen_promocion`
                            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

                $data_insert = [
                    $plataforma, $titulo_promocion, $precio_promocion, $descripcion_promocion, $texto_btn_promocion,
                    $enlace_btn_promocion, $imagen_promocion_url
                ];

                $insertar_promocion = $this->insert($sql_insert, $data_insert);
                if ($insertar_promocion == 1) {
                    $response['status'] = 200;
                    $response['title'] = 'Peticion exitosa';
                    $response['message'] = 'Datos insertados correctamente';
                } else {
                    $response['status'] = 500;
                    $response['title'] = 'Error';
                    $response['message'] = 'Error al insertar los datos en la base de datos';
                }
            }
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al procesar la imagen';
        }

        return $response;
    }
    /* Fin tienda online */


    public function crearSubdominio($nombre_tienda, $plataforma)
    {
        $cpanelUrl = 'https://administracion.imporsuitpro.com:2083/';
        $cpanelUsername = 'imporsuitpro';
        $cpanelPassword = 'd.)~Y=}+*!2vVrm5';
        $rootdomain = DOMINIO;

        $repositoryUrl = "https://github.com/DesarrolloImporfactory/tienda";
        $repositoryName = "tienda";

        $direccion = "/home/$cpanelUsername/public_html/$nombre_tienda";

        // Crear el directorio si no existe
        if (!file_exists($direccion)) {
            mkdir($direccion, 0777, true);
        }

        // Clonar el repositorio de GitHub usando cPanel API
        $apiUrl = $cpanelUrl . "execute/VersionControl/create";
        $postFields = [
            'type' => 'git',
            'name' => $repositoryName,
            'repository_root' => $direccion,
            'source_repository' => json_encode([
                "url" => $repositoryUrl,
                "branch" => "main"
            ]),
        ];

        if (method_exists($this, 'cpanelRequest')) {
            $response = $this->cpanelRequest($apiUrl, $cpanelUsername, $cpanelPassword, http_build_query($postFields));

            // Depuración: Verificar la respuesta de la API
            error_log("Respuesta de cPanel API para clonar el repositorio: " . json_encode($response));

            if ($response === false) {
                throw new Exception("Error al clonar el repositorio de GitHub: La solicitud cURL falló.");
            } elseif (isset($response['errors'])) {
                throw new Exception("Error al clonar el repositorio de GitHub: " . implode(", ", $response['errors']));
            }
        } else {
            throw new Exception("El método cpanelRequest no está definido.");
        }

        // Depuración: Listar los archivos en el directorio clonado
        $files = scandir($direccion);
        error_log("Archivos en el directorio $direccion después de clonar: " . implode(", ", $files));

        // Crear subdominio
        $apiUrl = $cpanelUrl . 'execute/SubDomain/addsubdomain?domain=' . $nombre_tienda . '&rootdomain=' . $rootdomain;
        $response = $this->cpanelRequest($apiUrl, $cpanelUsername, $cpanelPassword);
        if ($response === false) {
            throw new Exception("Error al crear el subdominio.");
        }

        $file = $direccion . '/Config/Config.php';

        // Depuración: Verificar la existencia del directorio y sus permisos
        if (!file_exists($direccion)) {
            throw new Exception("El directorio $direccion no existe.");
        } elseif (!is_readable($direccion)) {
            throw new Exception("El directorio $direccion no es accesible.");
        }

        // Depuración: Verificar la existencia del archivo y sus permisos
        if (!file_exists($file)) {
            throw new Exception("El archivo $file no existe.");
        } elseif (!is_readable($file)) {
            throw new Exception("El archivo $file no es accesible.");
        }

        // Lee el contenido del archivo
        $content = file_get_contents($file);

        // Reemplaza el texto específico
        $newContent = str_replace('tony', $nombre_tienda, $content);

        // Escribe el contenido modificado de nuevo en el archivo
        file_put_contents($file, $newContent);

        $url_tienda = 'https://' . $nombre_tienda . '.imporsuitpro.com';

        $sql = "UPDATE `plataformas` SET `url_imporsuit` = ?, `tienda_creada` = ?, `nombre_tienda` = ? WHERE `id_plataforma` = ?";
        $data = [$url_tienda, 1, $nombre_tienda, $plataforma];
        $editar_producto = $this->update($sql, $data);

        if ($editar_producto == 1) {
            $responses = array('status' => 200, 'title' => 'Peticion exitosa', 'message' => 'Subdominio creado y configurado correctamente');
        } else {
            $responses = array('status' => 500, 'title' => 'Error', 'message' => $editar_producto['message']);
        }

        return $responses;
    }

    public function cpanelRequest($url, $username, $password, $postFields = null)
    {
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
            error_log('Error en la solicitud cURL: ' . curl_error($ch));
        } else {
            $responseData = json_decode($response, true);
            return $responseData;
        }
        curl_close($ch);
        return false;
    }


    public function cambiarcolortienda($campo, $valor, $plataforma)
    {
        // $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "UPDATE `perfil` SET $campo =? WHERE `id_plataforma` = ?";
        //echo $sql;
        $data = [$valor, $plataforma];
        $editar_perfil = $this->update($sql, $data);
        print_r($editar_perfil);
        if ($editar_perfil == 1) {
            $responses = array('status' => 200, 'title' => 'Peticion exitosa', 'message' => 'Contraseña actualizada correctamente');
        } else {
            $responses = array('status' => 500, 'title' => 'Error', 'message' => $editar_perfil['message']);
        }
        return $responses;
    }

    public function cambiarcolor_oferta_plantilla2($campo, $valor, $plataforma)
    {
        // Primero, comprobar si existe un registro con la plataforma dada
        $sql_select = "SELECT * FROM `plantilla_2` WHERE `id_plataforma` = ?";
        $existing_entry = $this->select($sql_select, [$plataforma]);

        if ($existing_entry) {
            // Si existe, realizar un UPDATE
            $sql_update = "UPDATE `plantilla_2` SET $campo = ? WHERE `id_plataforma` = ?";
            $data_update = [$valor, $plataforma];
            $editar_plantilla_2 = $this->update($sql_update, $data_update);

            if ($editar_plantilla_2 == 1) {
                $responses = array('status' => 200, 'title' => 'Peticion exitosa', 'message' => 'Actualización realizada correctamente');
            } else {
                $responses = array('status' => 500, 'title' => 'Error', 'message' => 'Error al actualizar la base de datos');
            }
        } else {
            // Si no existe, realizar un INSERT
            $sql_insert = "INSERT INTO `plantilla_2` (`id_plataforma`, $campo) VALUES (?, ?)";
            $data_insert = [$plataforma, $valor];
            $insertar_plantilla_2 = $this->insert($sql_insert, $data_insert);

            if ($insertar_plantilla_2 == 1) {
                $responses = array('status' => 200, 'title' => 'Peticion exitosa', 'message' => 'Inserción realizada correctamente');
            } else {
                $responses = array('status' => 500, 'title' => 'Error', 'message' => 'Error al insertar en la base de datos');
            }
        }

        return $responses;
    }


    public function actualizar_tienda($ruc_tienda, $telefono_tienda, $email_tienda, $direccion_tienda, $pais_tienda, $plataforma, $facebook, $instagram, $tiktok)
    {
        $response = $this->initialResponse();

        // Actualizar información en la tabla `plataformas`
        $sql = "UPDATE `plataformas` SET `cedula_facturacion` = ?, `correo_facturacion` = ?, `direccion_facturacion` = ?, `whatsapp` = ? WHERE `id_plataforma` = ?";
        $data = [$ruc_tienda, $email_tienda, $direccion_tienda, $telefono_tienda, $plataforma];

        $editar_plataforma = $this->update($sql, $data);

        // Actualizar información en la tabla `perfil`
        $sql = "UPDATE `perfil` SET `facebook` = ?, `instagram` = ?, `tiktok` = ?, `whatsapp` = ? WHERE `perfil`.`id_plataforma` = ?";
        $data = [$facebook, $instagram, $tiktok, $telefono_tienda, $plataforma];
        $editar_perfil = $this->update($sql, $data);

        // Verificar si ambas actualizaciones fueron exitosas
        if ($editar_plataforma == 1 || $editar_perfil == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Producto editado correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Hubo un error al editar el producto';
        }

        return $response;
    }

    function crear_json($idplataforma)
    {
        $response = $this->initialResponse();
        // Crear el nombre del archivo usando el $idplataforma
        $nombreArchivo = $idplataforma . '_modal.json';

        // Crear la ruta completa para el archivo en la carpeta modales
        $carpetaModales = __DIR__ . '/modales';
        $rutaArchivo = $carpetaModales . '/' . $nombreArchivo;

        // Mensaje de depuración
        /* echo 'Ruta del archivo JSON: ' . $rutaArchivo . '<br>'; */

        // Verificar si el archivo ya existe
        if (file_exists($rutaArchivo)) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo ya existe';
            return $response;
        }

        // Ruta del archivo JSON fuente
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/Views/Usuarios/json/checkout.json';

        // Verificar si el archivo fuente existe
        if (!file_exists($filename)) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'El archivo fuente no existe';
            return $response;
        }

        // Leer el contenido del archivo JSON fuente
        $jsonData = file_get_contents($filename);
        if ($jsonData === false) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al leer el archivo fuente';
            return $response;
        }

        // Decodificar el contenido JSON como array
        $datos = json_decode($jsonData, true);
        if ($datos === null || !is_array($datos)) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al decodificar el JSON';
            return $response;
        }

        // Verificar si la carpeta 'modales' existe, si no, crearla
        if (!is_dir($carpetaModales)) {
            if (!mkdir($carpetaModales, 0755, true)) {
                $response['status'] = 500;
                $response['title'] = 'Error';
                $response['message'] = 'Error al crear el directorio: ' . $carpetaModales;
                return $response;
            }
        }

        // Guardar el archivo en el sistema de archivos
        if (file_put_contents($rutaArchivo, json_encode($datos, JSON_PRETTY_PRINT)) === false) {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al escribir el archivo: ' . $rutaArchivo;
            return $response;
        }

        $response['status'] = 200;
        $response['title'] = 'Éxito';
        $response['message'] = 'Archivo JSON creado';

        return $response;
    }

    public function actualizar_checkout($items, $id_plataforma)
    {
        // Define la ruta de archivo correcta en el sistema de archivos del servidor
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/Models/modales/' . $id_plataforma . '_modal.json';

        if (file_put_contents($filename, json_encode($items))) {
            return ['success' => true, 'message' => "Estado guardado correctamente. ID Plataforma: $id_plataforma"];
        } else {
            http_response_code(500);
            return ['success' => false, 'message' => 'Error al guardar el estado'];
        }
    }

    public function actualizacionMasivaTiendas()
    {
        $tmp_plataformas = $this->select("SELECT * FROM plataformas WHERE tienda_creada = 1");
        $respuestas_exitosas = []; // Arreglo para almacenar las respuestas exitosas

        foreach ($tmp_plataformas as $tmp) {
            $url = $tmp['url_imporsuit'] . '/Utils/actualizar';

            // Realizar la solicitud GET a la URL
            $response = @file_get_contents($url); // El '@' suprime los errores para manejarlos manualmente

            if ($response !== false) {
                // La solicitud fue exitosa, almacenar la respuesta
                $respuestas_exitosas[] = $response;
                echo "Solicitud exitosa a $url<br>";
            } else {
                // La solicitud falló, manejar el error según sea necesario
                echo "Error al solicitar $url<br>";
            }

            // Puedes ajustar el tiempo de espera entre solicitudes si es necesario
            // usleep(500000); // Espera 500ms (0.5 segundos) antes de la siguiente solicitud
        }

        // Ahora $respuestas_exitosas contiene solo las respuestas exitosas
        var_dump($respuestas_exitosas);
    }

    public function agregar_usuario($nombre, $correo, $contrasena, $id_plataforma)
    {
        //Inicia la respuesta
        $response = $this->initialResponse();

        //Se general el usuario
        $date_added       = date("Y-m-d H:i:s");

        $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (nombre_users, email_users, con_users, usuario_users, date_added, cargo_users) VALUES (?, ?, ?, ?, ?, ?)";
        //   echo $sql;
        $data = [$nombre, $correo, $contrasena, $correo, $date_added, 1];
        $insertar_usuario = $this->insert($sql, $data);
        //print_r($insertar_usuario);
        //echo 'erro'.$insertar_usuario;;
        if ($insertar_usuario == 1) {
            $id = $this->select("SELECT id_users FROM users WHERE usuario_users = '$correo'");

            $sql = "INSERT INTO usuario_plataforma (id_usuario, id_plataforma) VALUES (?, ?)";
            $data = [$id[0]['id_users'], $id_plataforma];
            $insertar_relacion = $this->insert($sql, $data);

            if ($insertar_relacion == 1) {
                $response = array('status' => 200, 'title' => 'Peticion exitosa', 'message' => 'Usuario agregado correctamente');
            } else {
                $response = array('status' => 500, 'title' => 'Error', 'message' => 'Error al agregar usuario');
            }
        } else {

            $response['message'] = "El usuario $correo ya existe en la base de datos, intente con otro correo electrónico!";
            //$id = $this->select("SELECT users_id FROM users WHERE correo = '$correo'");

        }

        return $response;
    }
}
