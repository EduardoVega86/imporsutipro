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
         $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $data = [$contrasena, $id_usuario ];
        $editar_producto = $this->update($sql, $data);
        
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
    
}