<?php

declare(strict_types=1);

namespace App\Libraries\ORM\Base;

use App\Libraries\ORM\Contracts\EntityInterface;
use App\Libraries\ORM\Contracts\EntityRepositoryInterface;

abstract class EntityRepositoryBase implements EntityRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function buildEntity(array $data): EntityInterface
    {
        $entityClassName = $this->getEntityClassName();
        $reflectionCLass = new \ReflectionClass($entityClassName);

        $entity = $reflectionCLass->newInstanceWithoutConstructor();
        $entity->assimilateDatabaseData($data);
        /**
         * @var EntityInterface $entity
         */
        return $entity;
    }
}
