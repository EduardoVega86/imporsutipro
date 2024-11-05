<?php
class Conexion
{
    private $connection;
    private $marketplace;

    public function __construct()
    {
        date_default_timezone_set('America/Guayaquil');
        $pdo = "mysql:host=" . HOST . ";dbname=" . DB . ";charset=" . CHARSET;
        try {
            $this->connection = new PDO($pdo, USER, PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
                PDO::ATTR_PERSISTENT => true,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
            ]);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            // Establecer la zona horaria para la sesión actual de MySQL
            $this->connection->exec("SET time_zone = '-05:00';");
        } catch (PDOException $e) {
            $this->handleError($e->getMessage());
        }
    }

    public function connect()
    {
        return $this->connection;
    }

    public function close()
    {
        $this->connection = null;
    }

    private function handleError($message)
    {
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
}
