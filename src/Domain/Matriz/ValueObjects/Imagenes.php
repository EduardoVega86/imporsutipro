<?php

namespace App\Domain\Matriz\ValueObjects;

class Imagenes
{
    private string $logo;
    private string $favicon;
    private string $imagen_fondo_inicio;
    private string $banner_inicio;
    private string $login_image;
    private string $transportadora_imagen;

    public function __construct(
        string $logo,
        string $favicon,
        string $imagen_fondo_inicio,
        string $banner_inicio,
        string $login_image,
        string $transportadora_imagen
    ) {
        $this->logo = $logo;
        $this->favicon = $favicon;
        $this->imagen_fondo_inicio = $imagen_fondo_inicio;
        $this->banner_inicio = $banner_inicio;
        $this->login_image = $login_image;
        $this->transportadora_imagen = $transportadora_imagen;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    public function getFavicon(): string
    {
        return $this->favicon;
    }

    public function setFavicon(string $favicon): void
    {
        $this->favicon = $favicon;
    }

    public function getImagenFondoInicio(): string
    {
        return $this->imagen_fondo_inicio;
    }

    public function setImagenFondoInicio(string $imagen_fondo_inicio): void
    {
        $this->imagen_fondo_inicio = $imagen_fondo_inicio;
    }

    public function getBannerInicio(): string
    {
        return $this->banner_inicio;
    }

    public function setBannerInicio(string $banner_inicio): void
    {
        $this->banner_inicio = $banner_inicio;
    }

    public function getLoginImage(): string
    {
        return $this->login_image;
    }

    public function setLoginImage(string $login_image): void
    {
        $this->login_image = $login_image;
    }

    public function getTransportadoraImagen(): string
    {
        return $this->transportadora_imagen;
    }

    public function setTransportadoraImagen(string $transportadora_imagen): void
    {
        $this->transportadora_imagen = $transportadora_imagen;
    }
}
