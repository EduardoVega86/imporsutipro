<?php
class AccesoModel extends Query
{
    public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda)
    {
        //Inicia la respuesta
        $response = $this->initialResponse();

        //Se general el usuario
 $date_added       = date("Y-m-d H:i:s");
        /* $sql = "INSERT INTO users (nombre, correo, pais, telefono, contrasena) VALUES (?, ?, ?, ?, ?)"; */
        
         $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
         $sql = "INSERT INTO users (nombre_users, email_users, con_users, usuario_users, date_added) VALUES (?, ?, ?, ?, ?)";
     //   echo $sql;
        $data = [$nombre, $correo, $contrasena, $correo, $date_added];
        $insertar_usuario=$this->insert($sql, $data);
        //print_r($insertar_usuario);
        //echo 'erro'.$insertar_usuario;;
        if ($insertar_usuario==1){
             $id = $this->select("SELECT id_users FROM users WHERE usuario_users = '$correo'");
              //print_r($id);
             //Se genera la plataforma
             $sql = "INSERT INTO plataformas (`nombre_tienda`, `contacto`, `whatsapp`, `fecha_ingreso`, `fecha_actualza`, `id_plan`, `url_imporsuit`, `carpeta_servidor`, `email`,  `referido`, `token_referido`, `refiere`, `pais`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
             $data = [$tienda, $nombre, $telefono, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 1, 'https://' . $tienda . '.imporsuitpro.com', '/public_html' . $tienda, $correo, 0, '', '', $pais];
             $insertar_plataforma=$this->insert($sql, $data);
            // print_r($insertar_plataforma);
             //si se guarda correctamente la plataforma 
             if($insertar_plataforma==1){
                   $idPlataforma = $this->select("SELECT id_plataforma FROM plataformas WHERE email = '$correo'");
                   //print_r($idPlataforma);
                    $sql = "INSERT INTO `perfil` ( `nombre_empresa`,`telefono`, `whatsapp`,  `id_plataforma`) VALUES (?,?,?,?)";
                    $data = [$tienda, $telefono, $telefono, $idPlataforma[0]['id_plataforma']];
                    $insertar_perfil=$this->insert($sql, $data);
                   // print_r($insertar_perfil);
                     if($insertar_perfil==1){
                         
                       $sql = "INSERT INTO usuario_plataforma (id_usuario, id_plataforma) VALUES (?, ?)";
                    $data = [$id[0]['id_users'], $idPlataforma[0]['id_plataforma']];
                    $this->insert($sql, $data);
                    $insertar_relacion= $this->insert($sql, $data);
                        if ($insertar_relacion==1){
                        $response['status'] = 200;
                        $response['title'] = 'Peticion exitosa';
                        $response['message'] = 'Usuario registrado correctamente';
                        $response['data'] = ['id' => $id[0]['id_users'], 'idPlataforma' => $idPlataforma[0]['id_plataforma']];   
                        }
                   
                     }else{
                       $response['message']="Error al crear el perfil";    
                     }
                   
             }else{
              $response['message']="Error al crear la plataforma! Intentelo nuevamente";   
              // $borrar_usuario=$this->insert($sql, $data);
             }
           
        //Se genera la relacion
            
        }else{
            
           $response['message']="El usuario $correo ya existe en la base de datos, intente con otro correo electrónico!";  
           //$id = $this->select("SELECT users_id FROM users WHERE correo = '$correo'");
           
        }
        
       
        return $response;
    }
    public function login($usuario, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE correo = '$usuario' AND contrasena = '$password'";
        return $this->select($sql);
    }
    public function recovery($correo)
    {
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
        return $this->select($sql);
    }
}
