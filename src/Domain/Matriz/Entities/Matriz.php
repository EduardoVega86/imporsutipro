<?php

namespace App\Domain\Matriz\Entities;

use App\Domain\Matriz\ValueObjects\Empresa;
use App\Domain\Matriz\ValueObjects\Colores;
use App\Domain\Matriz\ValueObjects\Imagenes;

class Matriz
{
    private int $idmatriz;
    private Empresa $empresa;
    private Colores $colores;
    private Imagenes $imagenes;

    public function __construct(
        int $idmatriz,
        Empresa $empresa,
        Colores $colores,
        Imagenes $imagenes
    ) {
        $this->idmatriz = $idmatriz;
        $this->empresa = $empresa;
        $this->colores = $colores;
        $this->imagenes = $imagenes;
    }

    // Getters
    public function getIdMatriz(): int
    {
        return $this->idmatriz;
    }

    public function getEmpresa(): Empresa
    {
        return $this->empresa;
    }

    public function getColores(): Colores
    {
        return $this->colores;
    }

    public function getImagenes(): Imagenes
    {
        return $this->imagenes;
    }

    // Lógica de negocio específica
    public function cambiarColores(Colores $nuevosColores): void
    {
        // Validación adicional si aplica
        $this->colores = $nuevosColores;
    }

    public function actualizarImagenes(Imagenes $nuevasImagenes): void
    {
        // Validación adicional si aplica
        $this->imagenes = $nuevasImagenes;
    }

    public function actualizarEmpresa(Empresa $nuevaEmpresa): void
    {
        // Validación adicional si aplica
        $this->empresa = $nuevaEmpresa;
    }

    public function obtenerResumen(): string
    {
        return sprintf(
            "Matriz #%d para la empresa %s con URL %s.",
            $this->idmatriz,
            $this->empresa->getMarca(),
            $this->empresa->getUrlMatriz()
        );
    }
}
