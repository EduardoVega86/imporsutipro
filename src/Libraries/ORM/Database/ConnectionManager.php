<?php

declare(strict_types=1);

namespace App\Libraries\ORM\Database;

use PDO;
use PDOException;

class ConnectionManager
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        $config = require __DIR__ . '/../../../Config/PDO.php';
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'] . ';charset=' . $config['db']['charset'],
                    $config['db']['user'],
                    $config['db']['password']
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new \RuntimeException('Database connection error');
            }
        }

        return self::$pdo;
    }
}
