<?php

declare(strict_types=1);

namespace App\Libraries\ORM\Contracts;

interface EntityInterface
{
    /**
     * @internal
     * @return array
     */
    public function getChanges(): array;

    /**
     * @internal
     */
    public function flushChanges(): void;

    /**
     * @internal
     * @param array $data
     */
    public function assimilateDatabaseData(array $data): void;

    /**
     * @internal
     * @return bool
     */
    public function isExists(): bool;
}
