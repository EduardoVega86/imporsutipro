<?php

namespace App\Domain\Matriz\ValueObjects;

use DateTime;

class Empresa
{
    private string $marca;
    private string $responsable;
    private DateTime $fecha_creacion;
    private int $guias_generadas;
    private string $url_matriz;
    private string $prefijo;
    private string $dominio;

    public function __construct(
        string $marca,
        string $responsable,
        DateTime $fecha_creacion,
        int $guias_generadas,
        string $url_matriz,
        string $prefijo,
        string $dominio
    ) {
        $this->marca = $marca;
        $this->responsable = $responsable;
        $this->fecha_creacion = $fecha_creacion;
        $this->guias_generadas = $guias_generadas;
        $this->url_matriz = $url_matriz;
        $this->prefijo = $prefijo;
        $this->dominio = $dominio;
    }

    public function getMarca(): string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): void
    {
        $this->marca = $marca;
    }

    public function getResponsable(): string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): void
    {
        $this->responsable = $responsable;
    }

    public function getFechaCreacion(): DateTime
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(DateTime $fecha_creacion): void
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getGuiasGeneradas(): int
    {
        return $this->guias_generadas;
    }

    public function setGuiasGeneradas(int $guias_generadas): void
    {
        $this->guias_generadas = $guias_generadas;
    }

    public function getUrlMatriz(): string
    {
        return $this->url_matriz;
    }

    public function setUrlMatriz(string $url_matriz): void
    {
        $this->url_matriz = $url_matriz;
    }

    public function getPrefijo(): string
    {
        return $this->prefijo;
    }

    public function setPrefijo(string $prefijo): void
    {
        $this->prefijo = $prefijo;
    }

    public function getDominio(): string
    {
        return $this->dominio;
    }

    public function setDominio(string $dominio): void
    {
        $this->dominio = $dominio;
    }
}
