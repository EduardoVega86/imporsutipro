<?php

namespace App\Infrastructure;

use App\Domain\Matriz\Entities\Matriz;
use App\Domain\Matriz\Repositories\IMatrizRepository;

class MatrizRepository implements IMatrizRepository
{
    public function save(Matriz $matriz): void
    {
        // Lógica para guardar la matriz en la base de datos

    }

    public function update(Matriz $matriz): void {}

    public function getById(int $idMatriz): ?Matriz
    {
        return null;
    }
}
