<?php
class AccesoModel extends Query
{
    public function registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda)
    {
        //Inicia la respuesta
        $response = $this->initialResponse();

        //Se general el usuario
        $sql = "INSERT INTO usuarios (nombre, correo, pais, telefono, contrasena) VALUES (?, ?, ?, ?, ?)";
        $data = [$nombre, $correo, $pais, $telefono, $contrasena];
        $this->insert($sql, $data);
        //Se obtiene el id del usuario
        $id = $this->select("SELECT id FROM usuarios WHERE correo = '$correo'");
        //Se genera la plataforma
        $sql = "INSERT INTO plataformas (`nombre_tienda`, `contacto`, `whatsapp`, `fecha_ingreso`, `fecha_actualza`, `id_plan`, `url_imporsuit`, `carpeta_servidor`, `email`,  `referido`, `token_referido`, `refiere`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$tienda, $nombre, $telefono, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 1, 'https://' . $tienda . '.imporsuitpro.com', '/public_html' . $tienda, $correo, '', '', ''];
        $this->insert($sql, $data);
        //se obtiene el id de la plataforma
        $idPlataforma = $this->select("SELECT id FROM plataformas WHERE email = '$correo'");
        //Se genera la relacion
        $sql = "INSERT INTO usuario_plataforma (id_usuario, id_plataforma) VALUES (?, ?)";
        $data = [$id[0]['id'], $idPlataforma[0]['id']];
        $this->insert($sql, $data);
        //Se genera la respuesta
        $response['status'] = 200;
        $response['message'] = 'Usuario registrado correctamente';
        $response['data'] = ['id' => $id[0]['id'], 'idPlataforma' => $idPlataforma[0]['id']];
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
