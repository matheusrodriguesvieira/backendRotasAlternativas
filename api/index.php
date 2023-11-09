<?php

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



include_once './database/DB.php';
include_once './controllers/listaEscalas.php';
include_once './controllers/operadoresController.php';
include_once './controllers/equipamentosCotroller.php';
