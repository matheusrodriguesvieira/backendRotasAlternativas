<?php
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, DELETE, POST, PUT, OPTIONS, PATCH");
header("Access-Control-Allow-Headers: X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version");

// Roteamento manual
// $uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    http_response_code(200);
    die();
}


$path = explode('/', $_GET['path']);

if (isset($path[0])) {
    $api = $path[0];
} else {
    echo 'caminho não existe';
    exit;
}



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

// $response = array(
//     "api" => "{$api}",
//     "acao" => "{$acao}",
//     "parametro" => "{$parametro}",
//     "method" => "{$metodo}"
// );
// echo json_encode($response);
// exit;



require_once(realpath(dirname(__FILE__) . '/database/DB.php'));
require_once(realpath(dirname(__FILE__) . '/controllers/listaEscalas.php'));
require_once(realpath(dirname(__FILE__) . '/controllers/operadoresController.php'));
require_once(realpath(dirname(__FILE__) . '/controllers/equipamentosCotroller.php'));
