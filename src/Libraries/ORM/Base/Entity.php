<?php

declare(strict_types=1);

namespace App\Libraries\ORM\Base;

use App\Libraries\ORM\Contracts\EntityInterface;

abstract class Entity implements EntityInterface
{
    private array $changes = [];
    private array $data = [];
    private bool $exists = false;

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function flushChanges(): void
    {
        foreach ($this->changes as $column => $value) {
            $this->data[$column] = $value;
        }

        $this->changes = [];
    }

    public function assimilateDatabaseData(array $data): void
    {
        $this->data = $data;

        $this->exists = true;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function __set($name, $value): void
    {
        $this->changes[$name] = $value;
    }

    public function __get($name)
    {
        $value = null;

        if (array_key_exists($name, $this->changes)) {
            $value = $this->changes[$name];
        } else if (array_key_exists($name, $this->data)) {
            $value = $this->data[$name];
        }

        return $value;
    }
}
