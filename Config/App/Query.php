<?php
class Query extends Conexion
{
    private $pdo, $connection, $sql;
    public function __construct()
    {
        $this->pdo = new Conexion();
        $this->connection = $this->pdo->connect();
    }
    // select  * from plataformas where id_plataforma = $id_plataforma;
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



    //$data = [$id_plataforma];
    // select  * from plataformas where id_plataforma = ?;
    // $response = $this->model->dselect($sql, $data);
    public function dselect($sql, $data)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }
    // Devuelve numeros de filas afectadas
    public function simple_select($sql, $data)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);

            // Obtener el número de filas afectadas directamente
            $rowCount = $query->rowCount();

            // Retornar el conteo de filas en lugar de reasignar $result
            return $rowCount;
        } catch (PDOException $e) {
            return $this->handleError($e->getMessage(), $e->getCode());
        }
    }

    // Devuelve numeros de filas afectadas
    public function insert($sql, $data)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            $result = $query->rowCount();

            // Depuración adicional
            error_log("Consulta ejecutada con éxito: " . $sql);
            error_log("Datos utilizados: " . print_r($data, true));

            return $result;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            error_log("Código del error: " . $e->getCode());
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
            'message' => '',
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

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollBack()
    {
        $this->connection->rollBack();
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function setSql($sql)
    {
        $this->sql = $sql;
    }

    public function getError()
    {
        return $this->connection->errorInfo();
    }

    public function getErrorCode()
    {
        return $this->connection->errorCode();
    }

    public function getErrorInfo()
    {
        return $this->connection->errorInfo();
    }

    // Devuelve la conexión
    public function getConnection()
    {
        return $this->connection;
    }
}
