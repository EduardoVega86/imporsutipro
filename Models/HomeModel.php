<?php

class HomeModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function validarRefiere($id)
    {
        if ($id == "") {
            return false;
        }
        $sql = "SELECT * FROM plataformas WHERE token_referido = ?";
        $params = [$id];
        $result = $this->simple_select($sql, $params);
        if ($result > 0) {
            return true;
        }
        return false;
    }
    
    
    


}
