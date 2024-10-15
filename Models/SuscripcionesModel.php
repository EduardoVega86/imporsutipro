<?php

class SuscripcionesModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getTiendas($id_usuario)
    {
        $sql = "SELECT p.nombre_tienda, p.id_plataforma, u.id_users, u.cargo_users
                FROM `usuario_plataforma` up 
                inner join plataformas p on up.id_plataforma = p.id_plataforma
                inner join users u on u.id_users = up.id_usuario
                where id_usuario = ?;";
        $params = [$id_usuario];
        return $this->dselect($sql, $params);
    }
}
