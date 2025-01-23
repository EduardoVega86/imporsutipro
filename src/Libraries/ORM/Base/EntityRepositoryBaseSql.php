<?php

declare(strict_types=1);

namespace App\Libraries\ORM\Base;

use App\Libraries\ORM\Contracts\EntityInterface;
use PDO;

abstract class EntityRepositoryBaseSql extends EntityRepositoryBase
{
    protected string $tableName;
    protected PDO $pdo;

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
            implode(', ', $columns),
            implode(', ', $placeholders)
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

        $sql = 'UPDATE ' . $this->getTableName() . ' SET ' . implode(', ', $updates) . ' WHERE ' . $this->getPrimaryKeyName() . ' = :primary_key';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':primary_key', $entity->{$this->getPrimaryKeyName()});

        foreach ($changes as $column => $value) {
            $statement->bindValue(':' . $column, $value);
        }

        $statement->execute();

        $entity->flushChanges();
    }

    public function delete(EntityInterface $entity): void
    {
        $sql = sprintf(
            'DELETE FROM %s WHERE %s = ?',
            $this->getTableName(),
            $this->getPrimaryKeyName()
        );

        $this->pdo->prepare($sql)->execute([$entity->{$this->getPrimaryKeyName()}]);
    }

    public function getById(int $id): ?EntityInterface
    {
        $sql = sprintf(
            'SELECT * FROM %s WHERE %s = ?',
            $this->getTableName(),
            $this->getPrimaryKeyName()
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);

        return $statement->rowCount() ? $this->buildEntity($statement->fetch(\PDO::FETCH_ASSOC)) : null;
    }
}
