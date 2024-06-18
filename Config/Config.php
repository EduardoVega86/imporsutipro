<?php
const HOST = '3.233.119.65';
const USER = "imporsuit_system";
const PASSWORD = "imporsuit_system";
const DB = "imporsuitpro_new";
const CHARSET = "utf8";

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define('ENVIRONMENT', 'development');
} else {
    define('ENVIRONMENT', 'production');
}
if (ENVIRONMENT == 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    define("SERVERURL", "http://localhost/imporsutipro/");
} else {
    $url_actual = $_SERVER['HTTP_HOST'];
    $mysqli = new mysqli(HOST, USER, PASSWORD, DB);
    $mysqli->set_charset(CHARSET);
    if ($mysqli->connect_errno) {
        echo "Error al conectarse con la base de datos";
        exit;
    }
    $matriz = [];

    $sql = "SELECT * FROM matriz where url_matriz = '$url_actual'";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $matriz = $row;
        }
    } else {
        echo "0 results";
    }

    $mysqli->close();

    $id_matriz = $matriz['id_matriz'];

    define("MATRIZ", $id_matriz);
    $url_matriz = $matriz['url_matriz'];
    define("SERVERURL", $url_matriz . "/");
}


const LAAR_USER = "import.uio.api";
const LAAR_PASSWORD = "Imp@rt*23";
const LAAR_ENDPOINT_AUTH = "https://api.laarcourier.com:9727/authenticate";
const LAAR_ENDPOINT = "https://api.laarcourier.com:9727/guias/contado";
const LLAR_ENDPOINT_CANCEL = 'https://api.laarcourier.com:9727/guias/anular/';

///obtener matriz 

const COLOR_FONDO = "#171931";
const IMAGEN_LOGO = "https://tiendas.imporsuitpro.com/imgs/LOGOS-IMPORSUIT.png";
