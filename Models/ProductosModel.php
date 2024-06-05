<?php
class ProductosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cargarProductos($filtro = "")
    {
        if (!empty($filtro)) {
            $sql = "SELECT * FROM productos WHERE $filtro";
            return $this->select($sql);
        } else {
            $sql = "SELECT * FROM productos";
        }
        return $this->select($sql);
    }

    public function cargarCategorias($plataforma)
    {
        $sql = "SELECT * FROM lineas WHERE id_plataforma = $plataforma";
        return $this->select($sql);
    }

    public function agregarBodega($nombre, $direccion, $telefono, $ciudad, $provincia, $contacto, $telefono_contacto, $numerocasa, $referencia, $plataforma)
    {
        // codigo para agregar bodega
        $response = $this->initialResponse();

        $sql = "INSERT INTO bodega (nombre_users, email_users, con_users, usuario_users, date_added, cargo_users) VALUES (?, ?, ?, ?, ?, ?)";
        //   echo $sql;
        $data = [$nombre, $correo, $contrasena, $correo, $date_added, 1];
        $insertar_usuario = $this->insert($sql, $data);
        if ($insertar_usuario == 1) {
        }
        return $response;
    }

    public function agregarCategoria($nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $plataforma)
    {
        // codigo para agregar categoria
        $response = $this->initialResponse();

        $sql = "INSERT INTO lineas (nombre_linea, descripcion_linea, estado_linea, date_added, online, imagen, tipo, padre, id_plataforma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $data = [$nombre_linea, $descripcion_linea, $estado_linea, $date_added, $online, $imagen, $tipo, $padre, $plataforma];
        $insertar_categoria = $this->insert($sql, $data);
        if ($insertar_categoria == 1) {
            $response['status'] = 200;
            $response['title'] = 'Peticion exitosa';
            $response['message'] = 'Categoria agregada correctamente';
        } else {
            $response['status'] = 500;
            $response['title'] = 'Error';
            $response['message'] = 'Error al agregar la categoria';
        }
        return $response;
    }
    public function listarBodegas($plataforma)
    {
        $sql = "SELECT * FROM bodega WHERE id_plataforma in ('$plataforma',0) ";
        return $this->select($sql);
    }
}
