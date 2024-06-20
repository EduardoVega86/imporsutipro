<?php
class Query extends Conexion
{
    private $pdo, $connection, $sql;
    public function __construct()
    {
        $this->pdo = new Conexion();
        $this->connection = $this->pdo->connect();
    }

    public function select($sql)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }

    public function simple_select($sql, $data)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            $result = $query->rowCount();
            return $result;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }

    public function insert($sql, $data)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            $result = $query->rowCount();
            return $result;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }

    public function simple_insert($sql)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute();
            $result = $query->rowCount();
            return $result;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }

    public function update($sql, $data)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            $result = $query->rowCount();
            return $result;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }

    public function delete($sql, $data)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            $result = $query->rowCount();
            return $result;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }

    public function simple_delete($sql)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute();
            $result = $query->rowCount();
            return $result;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }
    public function close()
    {
        $this->pdo->close();
    }

    public function initialResponse()
    {
        return [
            'status' => 500,
            'title' => 'Error',
            'data' => []
        ];
    }

    public function auditor($information)
    {
        try {
            $ip = $this->obtenerIpUsuario();
            $sql = "INSERT INTO auditoria (id_usuario, fecha, hora, informacion, ip) VALUES (:id_usuario, :fecha, :hora, :informacion, :ip)";
            $data = [
                'id_usuario' => $information['id_usuario'],
                'fecha' => $information['fecha'],
                'hora' => $information['hora'],
                'informacion' => $information['informacion'],
                'ip' => $ip
            ];
            $result = $this->insert($sql, $data);
            return $result;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }

    private function handleError($message, $code = 0)
    {
        //si se genera un error de SQLException agarrar el codigo de error y el mensaje

        return [
            'status' => 'error',
            'message' => $message,
            'code' => $code

        ];
    }

    function obtenerIpUsuario()
    {
        $ip = '';

        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED']) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR']) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED']) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = 'UNKNOWN';
        }

        return $ip;
    }


    public function obtenerMatriz()
    {
        $host = SERVERURL;
        // quitar el https://
        $host = str_replace("https://", "", $host);
        // quitar el http://
        $host = str_replace("http://", "", $host);
        $host = str_replace("/imporsutipro/", "", $host);
        $sql = "SELECT idmatriz FROM matriz WHERE url_matriz like '%$host%'";
        return $this->select($sql);
    }
}
