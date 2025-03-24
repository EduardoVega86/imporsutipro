<?php
class Query extends Conexion
{
    private $pdo, $connection, $sql, $response;
    public function __construct()
    {
        $this->pdo = new Conexion();
        $this->connection = $this->pdo->connect();
        $this->response = $this->initialResponse();
    }

    /**
     * @throws Exception
     */
    public function select($sql): array
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // throw new Exception($e->getMessage(), $e->getCode());
            throw new Exception($e->getMessage(), 0);
        }
    }

    /**
     * @throws Exception
     */
    public function dselect($sql, $data): array
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
    // Devuelve numeros de filas afectadas

    /**
     * @throws Exception
     */
    public function simple_select($sql, $data)
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            return $query->rowCount();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    // Devuelve números de filas afectadas

    /**
     * @throws Exception
     */
    public function insert($sql, $data): array|int
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            return $query->rowCount();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws Exception
     */
    public function simple_insert($sql): array|int
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute();
            return $query->rowCount();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws Exception
     */
    public function update($sql, $data): int
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            return $query->rowCount();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function delete($sql, $data): int
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute($data);
            return $query->rowCount();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws Exception
     */
    public function simple_delete($sql): int
    {
        try {
            $this->sql = $sql;
            $query = $this->connection->prepare($this->sql);
            $query->execute();
            return $query->rowCount();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
    public function close()
    {
        $this->pdo->close();
    }

    public function initialResponse(): array
    {
        return [
            'status' => 500,
            'title' => 'Error',
            'message' => '',
            'data' => []
        ];
    }

    private function handleError($message, $code = 0): array
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


    public function obtenerMatriz(): array
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

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollBack(): void
    {
        $this->connection->rollBack();
    }

    public function lastInsertId(): bool|string
    {
        return $this->connection->lastInsertId();
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function setSql($sql): void
    {
        $this->sql = $sql;
    }

    public function getError(): array
    {
        return $this->connection->errorInfo();
    }

    public function getErrorCode()
    {
        return $this->connection->errorCode();
    }

    public function getErrorInfo(): array
    {
        return $this->connection->errorInfo();
    }

    // Devuelve la conexión
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @return array
     */
    public function &getResponse(): array
    {
        return $this->response;
    }
}
