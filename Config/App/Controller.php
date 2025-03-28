<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once 'Class/Logger.php';

class Controller
{
    protected $model;
    protected Logger $logger;
    protected Views $views;
    protected mixed $secret_key;
    protected $user_data;

    public function __construct()
    {
        $this->views = new Views();
        $this->loadModel();
        $this->logger = new Logger(); // Instancia del logger
        $this->secret_key = $_ENV["JWT_SECRET"];
        // Registrar manejadores globales de errores y excepciones
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    // Maneja errores no capturados
    public function handleError($errno, $errstr, $errfile, $errline): void
    {
        $message = "Error [$errno]: $errstr in $errfile on line $errline";
        $this->logger->log($message, 'ERROR');
        // Aquí puedes decidir si mostrar o no el error al usuario
    }

    /**
     * Se encarga de validar el token JWT y devolver los datos decodificados
     * @return void
     * @throws Exception
     */
    private function validateJWT(): void
    {
        $headers = [];

        // Intentar recuperar Authorization de todas las formas posibles
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) === 'HTTP_') {
                    $headers[str_replace('_', '-', substr($name, 5))] = $value;
                }
            }
        }

        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } else {
            throw new Exception("No has iniciado sesión", 401);
        }

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            throw new Exception("Formato de token inválido", 401);
        }

        $jwt = $matches[1];

        try {
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
            $this->user_data = (array)$decoded->data;
        } catch (Exception $e) {
            throw new Exception("Token inválido o expirado", 401);
        }
    }


    /**
     * Maneja excepciones
     * @param $exception
     * @return void
     */
    public function handleException($exception): void
    {
        $message = "Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
        $this->logger->log($message, 'EXCEPTION');
        http_response_code(500);
        echo json_encode([
            "status" => 500,
            "title" => "Error",
            "message" => "Internal Server Error"
        ]);
        exit();
    }

    /**
     * Se encarga de loguear errores en el shutdown
     * @return void
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null) {
            $message = "Shutdown Error: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line'];
            $this->logger->log($message, 'SHUTDOWN');
        }
    }


    /**
     * Este método se encarga de capturar excepciones en funciones asíncronas
     * @param $fn
     * @return Closure
     */
    function catchAsync($fn): Closure
    {
        header('Content-Type: application/json');
        return function () use ($fn) {
            try {
                $fn();
            } catch (Exception $e) {
                $this->logger->log("Exception caught in catchAsync: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(), 'EXCEPTION');
                echo json_encode([
                    "status" => $e->getCode() !== 0 ? $e->getCode() : 500,
                    "title" => "Error",
                    "message" => $e->getMessage(),
                    "count" => 0,
                    "data" => []
                ]);
            }
        };
    }

    /**
     * Este método se encarga de capturar excepciones en funciones que requieren JWT
     * @param $fn
     * @return Closure
     */
    function catchJWT($fn): Closure
    {
        header('Content-Type: application/json');
        return function () use ($fn) {
            try {
                $this->validateJWT();
                $fn();
            } catch (Exception $e) {
                $this->logger->log("Exception caught in catchJWT: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(), 'EXCEPTION');
                echo json_encode([
                    "status" => $e->getCode() ?? 500,
                    "title" => "Error",
                    "message" => $e->getMessage(),
                    "count" => 0,
                    "data" => []
                ]);
            }
        };
    }

    /**
     * Se encarga de devolver los datos json ya decodificados del body.
     * Si se pasa un arreglo de campos requeridos, verifica que estén presentes y no vacíos.
     *
     * @param array $requiredFields
     * @return array
     * @throws Exception
     */
    public function jsonData(array $requiredFields = []): array
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        if (empty($data)) {
            throw new Exception("No se envió ningún dato");
        }

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("No se envió el dato requerido: " . $field);
            }
        }

        return $data;
    }

    /**
     * Se encarga de verificar si un dato no es nulo
     * @throws Exception
     */
    public function dataVerifier(string $nombre, $variable): void
    {
        if (empty($variable)) {
            throw new Exception("No se envio el dato: " . $nombre);
        }
    }

    /**
     * Se encarga de cargar el modelo correspondiente al controlador
     * @return void
     */
    public function loadModel(): void
    {
        $model = get_class($this) . "Model";
        $rute = "Models/" . $model . ".php";
        if (file_exists($rute)) {
            require_once $rute;
            $this->model = new $model();
        }
    }

    /**
     * Se encarga de verificar si el usuario está autenticado
     * @return bool
     */
    public function isAuth(): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION["user"]);
    }


    function autoloadJSFromDir(string $dirPath, string $baseUrl = ''): void
    {
        /*$fullPath = $_SERVER['DOCUMENT_ROOT'] . $dirPath;
        if (!is_dir($fullPath)) return;

        $files = scandir($fullPath);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'js') {
                echo '<script src="' . $baseUrl . $dirPath . '/' . $file . '"></script>' . PHP_EOL;
            }
        }*/
        echo "holi";
    }

}
