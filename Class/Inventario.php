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
     * Registra una bodega
     * @param string $nombre
     * @param string $responsable
     * @param string $contacto
     * @param int $localidad es el id_cotizacion de la tabla ciudad_cotizacion
     * @param int $provincia es el codigo_provincia de la tabla provincia o en su defecto el codigo_provincia_laar de la tabla ciudad_cotizacion
     * @param string $direccion
     * @param string $referencia
     * @param int $id_empresa es el id_plataforma de la tabla plataformas
     * @param bool $isFull
     * @param float $full
     * @param string $numero_casa
     * @param string $longitud son opcionales
     * @param string $latitud son opcionales
     * @return void La función no retorna nada.
     * @throws Exception Si ocurre un error al registrar la bodega se realiza un rollback y se lanza una excepción.
     */
    public function registrarBodega(string $nombre, string $responsable, string $contacto, int $localidad, int $provincia, string $direccion, string $referencia, int $id_empresa, bool $isFull, float $full, string $numero_casa = "", string $longitud = "", string $latitud = ""): void
    {
        try {
            $this->pdo->beginTransaction();
            $global = 0;
            if ($full) {
                $global = 1;
            }

            $statement = "INSERT INTO bodega (nombre, responsable, contacto, localidad, provincia, direccion, referencia, id_empresa, num_casa, id_plataforma, longitud, latitud, global, full_filme) VALUES (:nombre, :responsable, :contacto, :localidad, :provincia, :direccion, :referencia, :id_empresa, :numero_casa, :id_plataforma, :longitud, :latitud, :global, :full_filme)";
            $stmt = $this->pdo->prepare($statement);
            $stmt->execute([
                'nombre' => $nombre,
                'responsable' => $responsable,
                'contacto' => $contacto,
                'localidad' => $localidad,
                'provincia' => $provincia,
                'direccion' => $direccion,
                'referencia' => $referencia,
                'id_empresa' => $id_empresa,
                'numero_casa' => $numero_casa ?? null,
                'id_plataforma' => $this->id_plataforma,
                'longitud' => $longitud ?? 0,
                'latitud' => $latitud ?? 0,
                'global' => $global,
                'full_filme' => $full
            ]);
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al registrar la bodega: " . $e->getMessage());
        }
    }

    /**
     * Edita una bodega
     * @param int $id_bodega
     * @param string $nombre
     * @param string $responsable
     * @param string $contacto
     * @param int $localidad
     * @param int $provincia
     * @param string $direccion
     * @param string $referencia
     * @param int $id_empresa
     * @param bool $isFull
     * @param float $full
     * @param string $numero_casa
     * @param string $longitud
     * @param string $latitud
     * @throws Exception
     */
    public function editarBodega(int $id_bodega, string $nombre, string $responsable, string $contacto, int $localidad, int $provincia, string $direccion, string $referencia, int $id_empresa, bool $isFull, float $full, string $numero_casa = "", string $longitud = "", string $latitud = ""): void
    {
        try {
            $this->pdo->beginTransaction();
            $global = 0;
            if ($isFull) {
                $global = 1;
            }

            $statement = "UPDATE bodega SET nombre = :nombre, responsable = :responsable, contacto = :contacto, localidad = :localidad, provincia = :provincia, direccion = :direccion, referencia = :referencia, id_empresa = :id_empresa, num_casa = :numero_casa, id_plataforma = :id_plataforma, longitud = :longitud, latitud = :latitud, global = :global, full_filme = :full_filme WHERE id = :id_bodega";
            $stmt = $this->pdo->prepare($statement);
            $stmt->execute([
                'id_bodega' => $id_bodega,
                'nombre' => $nombre,
                'responsable' => $responsable,
                'contacto' => $contacto,
                'localidad' => $localidad,
                'provincia' => $provincia,
                'direccion' => $direccion,
                'referencia' => $referencia,
                'id_empresa' => $id_empresa,
                'numero_casa' => $numero_casa ?? null,
                'id_plataforma' => $this->id_plataforma,
                'longitud' => $longitud ?? 0,
                'latitud' => $latitud ?? 0,
                'global' => $global,
                'full_filme' => $full
            ]);
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al editar la bodega: " . $e->getMessage());
        }
    }

    /**
     * Elimina una bodega
     * @param int $id_bodega
     * @return void La función no retorna nada.
     * @throws Exception Si ocurre un error al eliminar la bodega se realiza un rollback y se lanza una excepción.
     */
    public function eliminarBodega(int $id_bodega): void
    {
        try {
            $this->pdo->beginTransaction();
            $statement = "UPDATE bodega SET eliminado = 1 WHERE id = ?";
            $stmt = $this->pdo->prepare($statement);
            $stmt->execute([$id_bodega]);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al eliminar la bodega: " . $e->getMessage());
        } finally {
            $this->pdo->commit();
        }
    }

    /**
     * Disminuye el inventario de un producto
     * @param int $id_inventario
     * @param int $cantidad
     * @return void
     * @throws Exception
     */
    public function disminuirInventario(int $id_inventario, int $cantidad): void
    {
        try {
            $this->pdo->beginTransaction();

            $statement = "UPDATE inventario_bodegas SET saldo_stock = saldo_stock - ? WHERE id_inventario = ?";

            $result = $this->pdo->prepare($statement)->execute([$cantidad, $id_inventario]);
            $this->pdo->commit();


        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al disminuir el inventario: " . $e->getMessage());
        }
    }

    /**
     * Centraliza la lógica de registro de inventario ya sea por compra o por venta.
     * @param int $id_inventario
     * @param int $id_tipo_movimiento
     * @param int $cantidad
     * @param string $fecha
     * @param string $descripcion
     * @param int $stock_previo
     * @param int $stock_actual
     * @param int $id_plataforma
     * @param int $id_usuario
     * @return void
     * @throws Exception
     */
    public function registrar_kardex(int $id_inventario, int $id_tipo_movimiento, int $cantidad, string $fecha, string $descripcion, int $stock_previo, int $stock_actual, int $id_plataforma, int $id_usuario): void
    {
        try {
            $this->pdo->beginTransaction();
            $statement = "INSERT INTO kardex (id_inventario, id_tipo_movimiento, cantidad, fecha, descripcion, stock_previo, stock_actual, id_plataforma, id_usuario, plataforma_responsable) VALUES (:id_inventario, :id_tipo_movimiento, :cantidad, :fecha, :descripcion, :stock_previo, :stock_actual, :id_plataforma, :id_usuario, :plataforma_responsable)";
            $stmt = $this->pdo->prepare($statement);
            $stmt->execute([
                'id_inventario' => $id_inventario,
                'id_tipo_movimiento' => $id_tipo_movimiento,
                'cantidad' => $cantidad,
                'fecha' => $fecha,
                'descripcion' => $descripcion,
                'stock_previo' => $stock_previo,
                'stock_actual' => $stock_actual,
                'id_plataforma' => $id_plataforma,
                'id_usuario' => $id_usuario,
                'plataforma_responsable' => $this->id_plataforma
            ]);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al registrar el kardex: " . $e->getMessage());
        }
    }
}