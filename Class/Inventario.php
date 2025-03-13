<?php

class Inventario
{
    private int $id_plataforma;
    private PDO $pdo;

    /**
     * @param PDO $pdo
     * @param int $id_plataforma
     */
    public function __construct(PDO $pdo, int $id_plataforma)
    {
        $this->pdo = $pdo;
        $this->id_plataforma = $id_plataforma;
    }

    /**
     * @param int $id_inventario
     * @param int $cantidad
     * @return void
     * @throws Exception
     */
    public function disminuirInventario(int $id_inventario, int $cantidad): void
    {
        try {

            $statement = "UPDATE inventario_bodegas SET saldo_stock = saldo_stock - ? WHERE id_inventario = ?";

            $result = $this->pdo->prepare($statement)->execute([$cantidad, $id_inventario]);
            throw new Exception("Error al disminuir el inventario");
        } catch (PDOException $e) {
            throw new Exception("Error al disminuir el inventario: " . $e->getMessage());
        }
    }
}