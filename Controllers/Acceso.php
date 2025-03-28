<?php

/**
 * Controlador de Acceso
 *
 * Este controlador administra el proceso de autenticación de usuarios.
 * Se encarga de validar credenciales, gestionar el inicio de sesión y redirigir
 * a los usuarios a las secciones correspondientes según sus permisos.
 *
 * @category   Controller
 * @package    Controllers\Acceso
 * @version    1.1-2025-03-10
 * @since      2025-03-10
 * @author     Jeimy Jara
 * @copyright  Imporcomex
 */
class Acceso extends Controller
{
    /**
     * Vista principal de la pagina
     * @return void
     */
    public function index(): void
    {
        $this->views->render($this, "index");
    }

    /**
     * Renderiza la vista de recuperación de contraseña
     * @return void
     */
    public function recovery(): void
    {
        $this->views->render($this, "recovery");
    }

    /**
     * Gestiona el inicio de sesión de los usuarios
     * @return void
     */
    public function login(): void
    {
        $this->catchAsync(function () {
            $data = $this->jsonData(['correo', 'contrasena']);
            $correo = $data['correo'];
            $contrasena = $data['contrasena'];

            $response = $this->model->login2($correo, $contrasena);
            echo json_encode($response);
        })();
    }

    /**
     * Registra un nuevo usuario
     * @return void
     */
    public function registro(): void
    {
        $this->catchAsync(function () {
            $data = $this->jsonData();
            if(empty($data)){
                throw new Exception("No se han enviado datos");
            }
            $nombre = $data['nombre'];
            $correo = $data['correo'];
            $pais = $data['pais'];
            $telefono = $data['telefono'];
            $contrasena = $data['contrasena'];
            $tienda = $data['tienda'];
            $response = $this->model->registro($nombre, $correo, $pais, $telefono, $contrasena, $tienda);
            echo json_encode($response);
        })();
    }

    /**
     * Renderiza la vista de bienvenida
     * @return void
     */
    public function bienvenida(): void
    {
        $this->model->bienvenida();
    }

    /**
     * Registra un nuevo usuario referido
     * @param int $id
     * @return void
     */
    public function referido_token($id): void
    {
        $this->catchAsync(function () use ($id) {

            $data = $this->jsonData();
            $nombre = $data['nombre'];
            $correo = $data['correo'];
            $pais = $data['pais'];
            $telefono = $data['telefono'];
            $contrasena = $data['contrasena'];
            $tienda = $data['tienda'];

            $response = $this->model->registro_referido($nombre, $correo, $pais, $telefono, $contrasena, $tienda, $id);

            echo json_encode($response);
        })();
    }

    /**
     * Cierra la sesión del usuario
     * @return void
     */
    public function logout(): void
    {
        session_start();

        session_unset();
        session_destroy();

        header("Location:  " . SERVERURL . "login");
    }

    /**
     * Valida si el ya existe el nombre de la tienda
     * @return void
     */
    public function validar_tiendas(): void
    {
        $this->catchAsync(function () {
            $tienda = $this->jsonData();
            if (empty($tienda)) {
                throw new Exception("No se han enviado datos");
            }
            if (empty($tienda['tienda'])) {
                throw new Exception("Nombre de tienda es requerido");
            }
            $exists = $this->model->validarTiendas($tienda['tienda']);
            echo json_encode($exists);
        })();
    }

    public function validar_email(): void
    {
        $this->catchAsync(function () {
            $dato_email = $this->jsonData();
            if (empty($dato_email)) {
                throw new Exception("No se han enviado datos");
            }
            if (empty($dato_email['email'])) {
                throw new Exception("Nombre de tienda es requerido");
            }
            $exists = $this->model->validarEmail($dato_email['email']);
            echo json_encode($exists);
        })();
    }

    /**
     * Enviá un correo con un token para recuperar la contraseña
     * @return void
     */
    public function recuperar_contrasena(): void
    {
        $this->catchAsync(function () {
            $data = $this->jsonData();
            $correo = $data['correo'] ?? null;
            if (empty($correo)) {
                throw new Exception("Correo es requerido");
            }
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Correo no válido");
            }
            $response = $this->model->recuperar_contrasena($correo);
            echo json_encode($response);
        })();
    }

    /**
     * Envía el correo de bienvenida
     * @return void
     */
    public function enviar_bienvenida(): void
    {
        $this->catchAsync(function () {
            $data = $this->jsonData();
            $correo = $data['correo'] ?? null;
            if (empty($correo)) {
                throw new Exception("Correo es requerido");
            }
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Correo no válido");
            }
            $response = $this->model->bienvenida($correo);
            echo json_encode($response);
        })();
    }

    /**
     * Válida el token para recuperar la contraseña
     * @return void
     */
    public function validarToken(): void
    {
        $this->catchAsync(function () {
            $token = $this->jsonData();
            if (empty($token)) {
                throw new Exception("No se han enviado datos");
            }
            if (empty($token['token'])) {
                throw new Exception("Token es requerido");
            }
            $response = $this->model->validarToken($token['token']);
            echo json_encode($response);
        })();
    }

    /**
     * Cambia la contraseña del usuario
     * @return void
     */
    public function cambiarContrasena(): void
    {
        $this->catchAsync(function () {
            $data = $this->jsonData();
            if (empty($data)) {
                throw new Exception("No se han enviado datos");
            }
            $token = $data['token'] ?? null;
            $contrasena = $data['contrasena'] ?? null;
            if (empty($token) || empty($contrasena)) {
                throw new Exception("Token y contraseña son requeridos");
            }
            $response = $this->model->cambiarContrasena($token, $contrasena);
            echo json_encode($response);
        })();
    }

    public function jwt($token): void
    {
        //separar token mediante: -||-
        $token = explode("-||-", $token);
        //verificar si el token es valido
        $response = $this->model->jwt($token[0], $token[1]);

        if ($response["status"] == 200) {
            if ($token[1] == "nuevo") {
                header("Location:  " . SERVERURL . 'Pedidos/nuevo');
                exit;
            }
            header("Location:  " . SERVERURL . 'Pedidos/editar/' . $token[1]);
            exit;
        }
        echo json_encode($response);
    }

    public function jwt_home($token): void
    {
        //separar token mediante: -||-
        $token = explode("-||-", $token);
        //verificar si el token es valido
        $response = $this->model->jwt($token[0], $token[1]);

        if ($response["status"] == 200) {
            header("Location:  " . SERVERURL . 'dashboard/home');
            exit;
        }
        echo json_encode($response);
    }
}
