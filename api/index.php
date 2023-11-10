<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");

// Roteamento manual
$uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';


$path = explode('/', $uri);

$api = $path[0];

if (isset($path[1])) {
    $acao = $path[1];
} else {
    $acao = '';
}

if (isset($path[2])) {
    $parametro = $path[2];
} else {
    $parametro = '';
}

$metodo = $_SERVER['REQUEST_METHOD'];



require_once(realpath(dirname(__FILE__) . '/database/DB.php'));
require_once(__DIR__ . 'controllers/listaEscalas.php');
require_once(__DIR__ . 'controllers/operadoresController.php');
require_once(__DIR__ . 'controllers/equipamentosCotroller.php');
