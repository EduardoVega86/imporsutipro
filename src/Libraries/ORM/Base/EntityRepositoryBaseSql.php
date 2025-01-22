<?php

declare(strict_types=1);

namespace App\Libraries\ORM\Base;

use App\Libraries\ORM\Contracts\EntityInterface;

abstract class EntityRepositoryBaseSql extends EntityRepositoryBase
{
    protected string $tableName;
    private \PDO $pdo;



    public function __construct(\PDO $pdo)

    {
        $this->pdo =  $pdo;
    }

    public function save(EntityInterface $entity)
    {
        if ($entity->isExists()) {
            $this->update($entity);
        } else {
            $this->insert($entity);
        }
    }

    protected abstract function getTableName(): string;
    protected abstract function getPrimaryKeyName(): string;

    protected function insert(EntityInterface $entity): void
    {
        $changes = $entity->getChanges();

        if (!$changes) {
            return;
        }

        $columns = array_keys($changes);
        $placeholders = array_fill(0, count($columns), '?');
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->getTableName(),
            $columns,
            $placeholders
        );

        $this->pdo->prepare($sql)->execute(array_values($changes));

        if ($insertID = $this->pdo->lastInsertId()) {
            $entity->{$this->getPrimaryKeyName()} = $insertID;
        }

        $entity->flushChanges();
    }

    protected function update(EntityInterface $entity): void
    {
        $changes = $entity->getChanges();

        if (!$changes) {
            return;
        }

        $updates = [];
        foreach ($changes as $column => $value) {
            $updates[] = sprintf('%s = :%s', $column, $column);
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = :%s',
            $this->getTableName(),
            implode(', ', $updates),
            $this->getPrimaryKeyName(),
            $this->getPrimaryKeyName()
        );
    }
}
