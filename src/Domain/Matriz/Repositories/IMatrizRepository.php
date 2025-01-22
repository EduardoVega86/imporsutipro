<?php

namespace App\Domain\Matriz\Repositories;

use App\Domain\Matriz\Entities\Matriz;

interface IMatrizRepository
{
    public function save(Matriz $matriz): void;
    public function update(Matriz $matriz): void;
    //public function delete(int $idMatriz): void;
    public function getById(int $idMatriz): ?Matriz;
}
