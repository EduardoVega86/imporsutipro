
<?php
class NovedadesModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cargarNovedades($plataforma)
    {
        $sql = "SELECT * FROM novedades where id_plataforma = $plataforma and solucionada = 0";
        $response = $this->select($sql);
        return $response;
    }

    public function solventarNovedad($id_novedad)
    {
        $sql = "UPDATE novedades SET solventada = 1 WHERE id_novedad = $id_novedad";
        $response = $this->select($sql);
        return $response;
    }

    public function solventarNovedadLaar($guia, $ciudad, $nombre, $cedula, $callePrincipal, $calleSecundaria, $numeracion, $referencia, $telefono, $celular, $observacion, $correo, $isDevolucion, $nombreA, $observacionA)
    {
        $data = array(
            "guia" => $guia,
            "destino" => array(
                "ciudad" => $ciudad,
                "nombre" => $nombre,
                "cedula" => $cedula,
                "callePrincipal" => $callePrincipal,
                "calleSecundaria" => $calleSecundaria,
                "numeracion" => $numeracion,
                "referencia" => $referencia,
                "telefono" => $telefono,
                "celular" => $celular,
                "observacion" => $observacion,
                "correo" => $correo
            ),
            "autorizado" => array(
                "isDevolucion" => $isDevolucion,
                "nombre" => $nombreA,
                "observacion" => $observacionA
            )
        );

        $data = json_encode($data);
        $url = "https://api.laarcourier.com:9727/guias/datos/actualizar";
    }
}
