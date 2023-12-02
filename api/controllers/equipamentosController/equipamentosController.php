<?php
if ($api == 'equipamentos') {

    if (Usuarios::verificar('supervisores')) {
        if ($metodo == 'GET') {
            require_once(realpath(dirname(__FILE__) . '/GET.php'));
        }

        if ($metodo == 'PUT') {
            require_once(realpath(dirname(__FILE__) . '/PUT.php'));
        }
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'Você não está logado, ou seu token é inválido.'
        ]);
        exit;
    }
}
