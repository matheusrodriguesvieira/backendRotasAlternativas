<?php
if ($api == 'supervisores') {

    if (Usuarios::verificar('supervisores')) {
        if ($metodo == 'GET') {
            require_once(realpath(dirname(__FILE__) . '/GET.php'));
        }
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'Você não está logado, ou seu token é inválido.'
        ]);
        exit;
    }

    if ($metodo == "POST") {
        if ($acao == "login" && $parametro == "") {
            Usuarios::login($api);
        }
    }
}
