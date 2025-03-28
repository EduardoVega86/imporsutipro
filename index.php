<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar autoload de Composer
require_once 'vendor/autoload.php';

// Ruta al directorio raíz
$rootPath = __DIR__;

// Inicializar Dotenv para cargar las variables del archivo .env
use Dotenv\Dotenv;
try {
    $dotenv = Dotenv::createImmutable($rootPath);
    $dotenv->load();
} catch (Exception $e) {
    die('Error al cargar las variables de entorno: ' . $e->getMessage());
}
// Cargar configuración de la aplicación
require_once 'Config/Config.php';


// Parsear la URL
$rute = !empty($_GET['url']) ? $_GET['url'] : 'Home/index';
if ($rute == "registro") {
    $rute = "Home/registro";
} else if ($rute == "login") {
    $rute = "Home/login";
} else if ($rute == "recovery") {
    $rute = "Acceso/recovery";
} else if (str_starts_with($rute, "refers")) {
    $rute = str_replace("refers", "Home/refers", $rute);
}
$array = explode('/', $rute);
$controller = $array[0];
// Hacer mayúscula la primera letra
$controller = ucwords($controller);

$method = "index";
$parameter = "";
if (!empty($array[1])) {
    if (!empty($array[1] != "")) {
        $method = $array[1];
    }
}
if (!empty($array[2])) {
    if (!empty($array[2] != "")) {
        for ($i = 2; $i < count($array); $i++) {
            $parameter .= $array[$i] . '-||-';
        }
        $parameter = trim($parameter, '-||-');
    }
}
function loadViewScripts(string $controller, string $view): void
{
    $funcionalidad = explode('_', $view)[0] ?? '';
    $modulo = explode('_', $view)[1] ?? '';
    $path = "Views/{$controller}/js/" . ucfirst($modulo) . "/" . $funcionalidad;
    echo $path;
    if (is_dir($path)) {
        foreach (scandir($path) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'js') {
                echo "<script src='" . SERVERURL . $path . "/" . $file . "'></script>" . PHP_EOL;
            }
        }
    }
}
// Cargar archivos necesarios
require_once 'Config/App/autoload.php';

// Resolver el controlador y método
$dirController = 'Controllers/' . $controller . '.php';
if (file_exists($dirController)) {
    require_once($dirController);
    $controller = new $controller();
    if (method_exists($controller, $method)) {
        $controller->$method($parameter);
    } else {
        echo "No existe el método";
    }
} else {
    echo "No existe el controlador";
}
