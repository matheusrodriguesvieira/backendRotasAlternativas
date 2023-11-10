<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");

// Roteamento manual
// $uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';


if (isset($_GET['path'])) {
    $path = explode('/', $_GET['path']);
} else {

    echo 'caminho não existe';
    exit;
}

var_dump($path);
exit;

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

$response = array(
    "path 0" => "{$path[0]}",
    "path 1" => "{$path[1]}",
    "path 2" => "{$path[2]}",
    "method" => "{$metodo}"
);
echo json_encode($response);
exit;



require_once(realpath(dirname(__FILE__) . '/database/DB.php'));
require_once(realpath(dirname(__FILE__) . '/controllers/listaEscalas.php'));
require_once(realpath(dirname(__FILE__) . '/controllers/operadoresController.php'));
require_once(realpath(dirname(__FILE__) . '/controllers/equipamentosCotroller.php'));
