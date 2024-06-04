<?php

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
    define("SERVERURL", "https://new.imporsuitpro.com/");
}

const HOST = '3.233.119.65';
const USER = "imporsuit_system";
const PASSWORD = "imporsuit_system";
const DB = "imporsuitpro_new";
const CHARSET = "utf8";

const LAAR_USER = "";
const LAAR_PASSWORD = "";
const LAAR_ENDPOINT = "";
