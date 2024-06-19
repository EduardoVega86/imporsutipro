<?php
class UsuariossModel extends Query
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
    
    public function obtener_usuarios_matriz($plataforma)
    {
        $id_matriz = $this->obtenerMatriz();
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
    
     public function importarExcelPlataformas()
    {
        
    // Obtener el ID de inventario desde el formulario
    //$id_inventario = $_POST['id_bodega'];
    
    // Verificar y manejar el archivo subido
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['archivo']['tmp_name'];
        $fileName = $_FILES['archivo']['name'];
        $fileSize = $_FILES['archivo']['size'];
        $fileType = $_FILES['archivo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Permitir solo archivos Excel
        $allowedfileExtensions = array('xlsx', 'xls');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $inputFileType = PHPExcel_IOFactory::identify($fileTmpPath);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $spreadsheet = $objReader->load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            $date_added = date("Y-m-d H:i:s");
            // Aquí puedes procesar los datos del Excel
            $fila=0;
            $agregados=0;
            //echo count($data);
            foreach ($data as $row) {
               // echo $fila;
                if($fila>0){
                
                   //print_r ($data[$fila]); 
                 //  $response = $this->model->agregarProducto($codigo_producto, $nombre_producto, $descripcion_producto, $id_linea_producto, $inv_producto, $producto_variable, $costo_producto, $aplica_iva, $estado_producto, $date_added, $image_path, $id_imp_producto, $pagina_web, $formato, $drogshipin, $destacado, $_SESSION['id_plataforma'], $stock_inicial, $bodega, $pcp, $pvp, $pref);
              $response = $this->model->registro($data[$fila][0], $data[$fila][1], $data[$fila][2], $data[$fila][3], 'import.1', $data[$fila][4]);;
             // echo $response ['status'];
              if ($response ['status']==200){
               $agregados=$agregados+1;   
              }
               //print_r($response);
               
               // echo $data[$fila][0];
                //echo 'fila';
                }
                // $row es un array que contiene todas las celdas de una fila
              //  print_r($row); // Ejemplo de impresión de la fila
                $fila++;
            }
            if ($agregados>0){
                $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = $agregados.' productos importados correctamente';
            }else{
                $response['status'] = 500;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'NO se agregaron productos, revice el archvio e inténtelo nuevamente'; 
            }
            // Puedes almacenar la información procesada en la base de datos o manejarla como desees
            //$response = $this->model->importacion_masiva($data);
           // echo json_encode($response);
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Solo se permiten archivos Excel (xlsx, xls).';
           // return json_encode(['error' => 'Solo se permiten archivos Excel (xlsx, xls).']);
        }
    } else {
         $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al subir el archivo.';
        //echo json_encode(['error' => 'Error al subir el archivo.']);
    }
    echo json_encode($response);
    }
    
     public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda)
    {
        ini_set('session.gc_maxlifetime', 3600);
        ini_set('session.cookie_lifetime', 3600);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
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
        //print_r($insertar_usuario);
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
    
}