<?php
require_once 'Config/App/Query.php';

/**
 * Clase de auditorias
 */
class Auditable
{
    private Query $query;
    private string $lugar;
    private int $user_id;

    /**
     * @param int $user_id
     * @param string $lugar
     */
    public function __construct(int $user_id = 0, string $lugar = "")
    {
        $this->user_id = $user_id;
        $this->lugar = $lugar;
        $this->query = new Query();
    }

    /**
     * @param string $action
     * @param string $servidor
     * @return void
     */
    public function auditar(string $action, string $servidor): void
    {
        $sql = "INSERT INTO auditables(id_users, lugar, accion, servidor) VALUES(?,?,?,?);";
        $this->query->insert($sql, [$this->user_id, $this->lugar, $action, $servidor]);
    }

    /**
     * @param string $fecha
     * @return array
     */
    public function mostrarTodas(string $fecha): array
    {
        $sql = "SELECT auditables.id_auditorio, COALESCE(users.nombre_users, 'Usuario eliminado'), auditables.lugar, auditables.accion, auditables.servidor, auditables.fecha FROM auditables LEFT JOIN users ON auditables.id_users = users.id_users WHERE auditables.fecha between ? and NOW();";
        return $this->query->dselect($sql, [$fecha]);
    }

    /**
     * @param string $accion
     * @param string $fecha
     * @return array
     */
    public function mostrarPorAccion(string $accion, string $fecha): array
    {
        $sql = "SELECT auditables.id_auditorio, COALESCE(users.nombre_users, 'Usuario eliminado'), auditables.lugar, auditables.accion, auditables.servidor, auditables.fecha FROM auditables LEFT JOIN users ON auditables.id_users = users.id_users WHERE auditables.accion = ? AND auditables.fecha between ? and NOW();";
        return $this->query->dselect($sql, [$accion, $fecha]);
    }

}