<?php

namespace App\Infrastructure;

use App\Domain\Matriz\Entities\Matriz;
use App\Domain\Matriz\Repositories\IMatrizRepository;
use App\Domain\Matriz\ValueObjects\Colores;
use App\Domain\Matriz\ValueObjects\Empresa;
use App\Domain\Matriz\ValueObjects\Imagenes;
use InvalidArgumentException;
use PDO;

class MatrizRepository implements IMatrizRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function validarDatos(array $data): void
    {
        $requeridos = [
            'marca',
            'responsable',
            'url_matriz',
            'prefijo',
            'dominio',
            'color_fondo_login',
            'color_letras',
            'color_hover',
            'color_letra_hover',
            'color_hover_login',
            'color_boton_login',
            'color_favorito',
            'logo',
            'favicon',
            'imagen_fondo_inicio',
            'banner_inicio',
            'login_image',
            'transportadora_imagen'
        ];

        foreach ($requeridos as $campo) {
            if (empty($data[$campo])) {
                throw new InvalidArgumentException("El campo '$campo' es obligatorio.");
            }
        }
    }


    public function save(Matriz $matriz): void
    {
        $sql = 'INSERT INTO `matriz`(`marca`, `responsable`, `fecha_creacion`, `guia_generadas`, `url_matriz`, `color_fondo_login`, `imagen_fondo_inicio`, `logo`, `prefijo`, `dominio`, `favicon`, `color_letras`, `color_hover`, `color_letra_hover`, `banner_inicio`, `login_image`, `color_boton_login`, `color_hover_login`, `color_favorito`, `transportadora_imagen`) 
        VALUES  (:marca, :responsable, :fecha_creacion, :guia_generadas, :url_matriz, :color_fondo_login, :imagen_fondo_inicio, :logo, :prefijo, :dominio, :favicon, :color_letras, :color_hover, :color_letra_hover, :banner_inicio, :login_image, :color_boton_login, :color_hover_login, :color_favorito, :transportadora_imagen)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'marca' => $matriz->getEmpresa()->getMarca(),
            'responsable' => $matriz->getEmpresa()->getResponsable(),
            'fecha_creacion' => $matriz->getEmpresa()->getFechaCreacion(),
            'guia_generadas' =>   $matriz->getEmpresa()->getGuiasGeneradas(),
            'url_matriz' => $matriz->getEmpresa()->getUrlMatriz(),
            'color_fondo_login' => $matriz->getColores()->getColorFondoLogin(),
            'imagen_fondo_inicio' => $matriz->getImagenes()->getImagenFondoInicio(),
            'logo' => $matriz->getImagenes()->getLogo(),
            'prefijo' => $matriz->getEmpresa()->getPrefijo(),
            'dominio' => $matriz->getEmpresa()->getDominio(),
            'favicon' => $matriz->getImagenes()->getFavicon(),
            'color_letras' => $matriz->getColores()->getColorLetras(),
            'color_hover' => $matriz->getColores()->getColorHover(),
            'color_letra_hover' => $matriz->getColores()->getColorLetraHover(),
            'banner_inicio' => $matriz->getImagenes()->getBannerInicio(),
            'login_image' => $matriz->getImagenes()->getLoginImage(),
            'color_boton_login' => $matriz->getColores()->getColorBotonLogin(),
            'color_hover_login' => $matriz->getColores()->getColorHoverLogin(),
            'color_favorito' => $matriz->getColores()->getColorFavorito(),
            'transportadora_imagen' => $matriz->getImagenes()->getTransportadoraImagen()
        ]);
    }


    public function update(Matriz $matriz): void
    {
        $sql = 'UPDATE `matriz` SET `marca` = :marca, `responsable` = :responsable, `fecha_creacion` = :fecha_creacion, `guia_generadas` = :guia_generadas, `url_matriz` = :url_matriz, `color_fondo_login` = :color_fondo_login, `imagen_fondo_inicio` = :imagen_fondo_inicio, `logo` = :logo, `prefijo` = :prefijo, `dominio` = :dominio, `favicon` = :favicon, `color_letras` = :color_letras, `color_hover` = :color_hover, `color_letra_hover` = :color_letra_hover, `banner_inicio` = :banner_inicio, `login_image` = :login_image, `color_boton_login` = :color_boton_login, `color_hover_login` = :color_hover_login, `color_favorito` = :color_favorito, `transportadora_imagen` = :transportadora_imagen WHERE `idmatriz` = :idmatriz';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'idmatriz' => $matriz->getIdMatriz(),
            'marca' => $matriz->getEmpresa()->getMarca(),
            'responsable' => $matriz->getEmpresa()->getResponsable(),
            'fecha_creacion' => $matriz->getEmpresa()->getFechaCreacion(),
            'guia_generadas' =>   $matriz->getEmpresa()->getGuiasGeneradas(),
            'url_matriz' => $matriz->getEmpresa()->getUrlMatriz(),
            'color_fondo_login' => $matriz->getColores()->getColorFondoLogin(),
            'imagen_fondo_inicio' => $matriz->getImagenes()->getImagenFondoInicio(),
            'logo' => $matriz->getImagenes()->getLogo(),
            'prefijo' => $matriz->getEmpresa()->getPrefijo(),
            'dominio' => $matriz->getEmpresa()->getDominio(),
            'favicon' => $matriz->getImagenes()->getFavicon(),
            'color_letras' => $matriz->getColores()->getColorLetras(),
            'color_hover' => $matriz->getColores()->getColorHover(),
            'color_letra_hover' => $matriz->getColores()->getColorLetraHover(),
            'banner_inicio' => $matriz->getImagenes()->getBannerInicio(),
            'login_image' => $matriz->getImagenes()->getLoginImage(),
            'color_boton_login' => $matriz->getColores()->getColorBotonLogin(),
            'color_hover_login' => $matriz->getColores()->getColorHoverLogin(),
            'color_favorito' => $matriz->getColores()->getColorFavorito(),
            'transportadora_imagen' => $matriz->getImagenes()->getTransportadoraImagen()
        ]);
    }

    public function getById(int $idMatriz): ?Matriz
    {
        $sql = 'SELECT * FROM `matriz` WHERE `idmatriz` = :idmatriz';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idmatriz' => $idMatriz]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        // Crear los ValueObjects a partir de los datos obtenidos
        $empresa = new Empresa(
            $data['marca'],
            $data['responsable'],
            new \DateTime($data['fecha_creacion']),
            (int) $data['guia_generadas'],
            $data['url_matriz'],
            $data['prefijo'],
            $data['dominio']
        );

        $colores = new Colores(
            $data['color_fondo_login'],
            $data['color_letras'],
            $data['color_hover'],
            $data['color_letra_hover'],
            $data['color_hover_login'],
            $data['color_boton_login'],
            $data['color_favorito']
        );

        $imagenes = new Imagenes(
            $data['logo'],
            $data['favicon'],
            $data['imagen_fondo_inicio'],
            $data['banner_inicio'],
            $data['login_image'],
            $data['transportadora_imagen']
        );

        // Crear la entidad Matriz con los ValueObjects
        return new Matriz(
            (int) $data['idmatriz'],
            $empresa,
            $colores,
            $imagenes
        );
    }
}
