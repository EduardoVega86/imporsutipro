<?php

namespace App\Domain\Matriz\ValueObjects;

class Colores
{
    private string $color_fondo_login;
    private string $color_letras;
    private string $color_hover;
    private string $color_letra_hover;
    private string $color_hover_login;
    private string $color_boton_login;
    private string $color_favorito;

    public function __construct(
        string $color_fondo_login,
        string $color_letras,
        string $color_hover,
        string $color_letra_hover,
        string $color_hover_login,
        string $color_boton_login,
        string $color_favorito
    ) {
        $this->color_fondo_login = $color_fondo_login;
        $this->color_letras = $color_letras;
        $this->color_hover = $color_hover;
        $this->color_letra_hover = $color_letra_hover;
        $this->color_hover_login = $color_hover_login;
        $this->color_boton_login = $color_boton_login;
        $this->color_favorito = $color_favorito;
    }

    public function getColorFondoLogin(): string
    {
        return $this->color_fondo_login;
    }

    public function setColorFondoLogin(string $color_fondo_login): void
    {
        $this->color_fondo_login = $color_fondo_login;
    }

    public function getColorLetras(): string
    {
        return $this->color_letras;
    }

    public function setColorLetras(string $color_letras): void
    {
        $this->color_letras = $color_letras;
    }

    public function getColorHover(): string
    {
        return $this->color_hover;
    }

    public function setColorHover(string $color_hover): void
    {
        $this->color_hover = $color_hover;
    }

    public function getColorLetraHover(): string
    {
        return $this->color_letra_hover;
    }

    public function setColorLetraHover(string $color_letra_hover): void
    {
        $this->color_letra_hover = $color_letra_hover;
    }

    public function getColorHoverLogin(): string
    {
        return $this->color_hover_login;
    }

    public function setColorHoverLogin(string $color_hover_login): void
    {
        $this->color_hover_login = $color_hover_login;
    }

    public function getColorBotonLogin(): string
    {
        return $this->color_boton_login;
    }

    public function setColorBotonLogin(string $color_boton_login): void
    {
        $this->color_boton_login = $color_boton_login;
    }

    public function getColorFavorito(): string
    {
        return $this->color_favorito;
    }

    public function setColorFavorito(string $color_favorito): void
    {
        $this->color_favorito = $color_favorito;
    }
}
