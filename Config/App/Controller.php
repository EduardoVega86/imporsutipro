<?php

require_once 'Class/Logger.php';
class Controller {
    protected $model;
    protected Logger $logger;
    protected Views $views;

    public function __construct() {
        $this->views = new Views();
        $this->loadModel();
        $this->logger = new Logger(); // Instancia del logger

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

    // Maneja excepciones no capturadas
    public function handleException($exception): void
    {
        $message = "Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
        $this->logger->log($message, 'EXCEPTION');
        http_response_code(500);
        echo json_encode([
            "status"  => 500,
            "title"   => "Error",
            "message" => "Internal Server Error"
        ]);
        exit();
    }

    // Maneja errores fatales en el shutdown
    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null) {
            $message = "Shutdown Error: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line'];
            $this->logger->log($message, 'SHUTDOWN');
        }
    }

    // Ejemplo de método catchAsync modificado para loggear excepciones
    function catchAsync($fn): Closure {
        header('Content-Type: application/json');
        return function() use ($fn) {
            try {
                $fn();
            } catch (Exception $e) {
                $this->logger->log("Exception caught in catchAsync: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine(), 'EXCEPTION');
                echo json_encode([
                    "status"  => 500,
                    "title"   => "Error",
                    "message" => $e->getMessage(),
                    "count"   => 0,
                    "data"    => []
                ]);
            }
        };
    }

    public function loadModel(): void
    {
        $model = get_class($this) . "Model";
        $rute = "Models/" . $model . ".php";
        if (file_exists($rute)) {
            require_once $rute;
            $this->model = new $model();
        }
    }

    public function generateSesion($data): void
    {
        $_SESSION['id'] = $data['id'];
        $_SESSION['plataforma'] = $data['idPlataforma'];
    }

    public function userTime(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $session_duration = 3600; // 1 hora
        if (isset($_SESSION['login_time'])) {
            $session_life = time() - $_SESSION['login_time'];
            if ($session_life > $session_duration) {
                session_unset();
                session_destroy();
                header("Location: /index.php");
                exit();
            }
        } else {
            header("Location: /index.php");
            exit();
        }
    }

    public function isAuth(): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION["user"]);
    }

    public function hasPermission($permission): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION["cargo"])) {
            if ($_SESSION["cargo"] === "1" || $_SESSION["cargo"] === $permission) {
                return true;
            }
        }
        return false;
    }

    public function logouts(): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        return true;
    }
}
