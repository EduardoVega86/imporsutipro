<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'Config/Config.php';

$rute = !empty($_GET['url']) ? $_GET['url'] : 'Home/index';
if ($rute == "registro") {
    $rute = "Home/registro";
} else if ($rute == "login") {
    $rute = "Home/login";
} else if ($rute == "recovery") {
    $rute = "Acceso/recovery";
} else if (strpos($rute, "refers") === 0) {
    $rute = str_replace("refers", "Home/refers", $rute);
}
$array = explode('/', $rute);
$controller = $array[0];
// hacer mayuscula la primera letra
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
            $parameter .= $array[$i] . ',';
        }
        $parameter = trim($parameter, ',');
    }
}
require_once 'Config/App/autoload.php';
$dirController = 'Controllers/' . $controller . '.php';
if (file_exists($dirController)) {
    require_once($dirController);
    $controller = new $controller();
    if (method_exists($controller, $method)) {
        $controller->$method($parameter);
    } else {
        echo "No existe el metodo";
    }
} else {
    echo "No existe el controlador";
}
