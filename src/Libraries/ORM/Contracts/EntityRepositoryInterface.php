<?php

declare(strict_types=1);

namespace App\Libraries\ORM\Contracts;

interface EntityRepositoryInterface
{
    /**
     * @internal 
     * @param EntityInterface
     */

    public function buildEntity(array $data): EntityInterface;

    public function getEntityClassName(): string;
}
