<?php
class Controller
{
    protected $views, $model;
    public function __construct()
    {
        $this->views = new Views();
        $this->loadModel();
    }

    public function loadModel()
    {
        $model = get_class($this) . "Model";
        $rute = "Models/" . $model . ".php";
        if (file_exists($rute)) {
            require_once $rute;
            $this->model = new $model(); // Assign the new instance to the $model property
        }
    }

    public function generateSesion($data)
    {
        $_SESSION['id'] = $data['id'];
        $_SESSION['plataforma'] = $data['idPlataforma'];
    }


    public function userTime()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Duración máxima de la sesión en segundos (24 horas)
        $session_duration = 3600;

        // Verificar si la sesión ha expirado
        if (isset($_SESSION['login_time'])) {
            $session_life = time() - $_SESSION['login_time'];
            if ($session_life > $session_duration) {
                // La sesión ha expirado
                session_unset();
                session_destroy();
                header("Location: /index.php"); // Redirigir al login o mostrar mensaje de sesión expirada
                exit();
            }
        } else {
            // No hay sesión activa, redirigir al login
            header("Location: /index.php");
            exit();
        }
    }
    public function isAuth()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION["user"])) {
            return true;
        }
        return false;
    }
    public function hasPermission($permission)
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
    public function logouts()
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();

        return true;
    }
}
