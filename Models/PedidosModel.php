<?php
class AccesoModel extends Query
{
    public function pedidos($usuario, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE correo = '$usuario' AND contrasena = '$password'";
        return $this->select($sql);
    }
}