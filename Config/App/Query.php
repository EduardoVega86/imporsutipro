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
        $this->sql = $sql;
        $query = $this->connection->prepare($this->sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert($sql, $data)
    {
        $this->sql = $sql;
        $query = $this->connection->prepare($this->sql);
        $query->execute($data);
        $result = $query->rowCount();
        return $result;
    }

    public function update($sql, $data)
    {
        $this->sql = $sql;
        $query = $this->connection->prepare($this->sql);
        $query->execute($data);
        $result = $query->rowCount();
        return $result;
    }

    public function delete($sql)
    {
        $this->sql = $sql;
        $query = $this->connection->prepare($this->sql);
        $query->execute();
        $result = $query->rowCount();
        return $result;
    }

    public function close()
    {
        $this->pdo->close();
    }

    public function initialResponse()
    {
        $response = [
            'status' => 500,
            'message' => 'Petición exitosa',
            'data' => []
        ];
        return $response;
    }
}
