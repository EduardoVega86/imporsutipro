<?php

class TarifasModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTarifas()
    {
        $sql = "SELECT * FROM tarifa_referido";
        return $this->select($sql);
    }

    public function crearTarifa($nombre, $precio, $descripcion)
    {
        $sql = "INSERT INTO tarifa_referido (nombre, precio, descripcion) VALUES (?, ?, ?)";
        $data = array($nombre, $precio, $descripcion);
        return $this->insert($sql, $data);
    }

    public function editarTarifa($id_tarifa, $nombre, $precio, $descripcion)
    {
        $sql = "UPDATE tarifa_referido SET nombre = ?, precio = ?, descripcion = ? WHERE id_tarifa = ?";
        $data = array($nombre, $precio, $descripcion, $id_tarifa);
        return $this->update($sql, $data);
    }

    public function eliminarTarifa($id_tarifa)
    {
        $sql = "DELETE FROM tarifa_referido WHERE id_tarifa = ?";
        $data = array($id_tarifa);
        return $this->delete($sql, $data);
    }
}
