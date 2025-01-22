<?php

declare(strict_types=1);

use App\Domain\Matriz\Entities\Matriz;
use App\Domain\Matriz\ValueObjects\Colores;
use App\Domain\Matriz\ValueObjects\Empresa;
use App\Domain\Matriz\ValueObjects\Imagenes;
use App\Infrastructure\MatrizRepository;
use App\Libraries\ORM\Database\ConnectionManager;

class MatrizModel extends MatrizRepository
{
    protected $pdo = null;
    public function __construct()
    {
        $this->pdo = ConnectionManager::getConnection();
        parent::__construct($this->pdo);
    }

    public function agregar($data)
    {
        try {
            $this->pdo->beginTransaction();

            $this->validarDatos($data);

            $fecha = date("Y-m-d");
            $fechaDate = new DateTime($fecha);
            $empresa = new Empresa($data['marca'], $data['responsable'], $fechaDate, 0, $data["url_matriz"], $data["prefijo"], $data["dominio"]);
            $colores = new Colores($data['color_fondo_login'], $data['color_letras'], $data['color_hover'], $data['color_letra_hover'], $data['color_hover_login'], $data['color_boton_login'], $data['color_favorito']);
            $imagenes = new Imagenes($data['logo'], $data['favicon'], $data['imagen_fondo_inicio'], $data['banner_inicio'], $data['login_image'], $data['transportadora_imagen']);
            $matriz = new Matriz(null, $empresa, $colores, $imagenes);

            $this->save($matriz);

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => 'Matriz creada con éxito',
                'id' => $matriz->getIdMatriz(),
            ];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
