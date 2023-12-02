<?php
if ($api == 'listaEscalas') {


    if ((Usuarios::verificar('supervisores')) || (Usuarios::verificar('operadores'))) {
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

    if (Usuarios::verificar('supervisores')) {
        if ($metodo == 'PUT') {
            require_once(realpath(dirname(__FILE__) . '/PUT.php'));
        }

        if ($metodo == "POST") {
            require_once(realpath(dirname(__FILE__) . '/POST.php'));
        }

        if ($metodo == 'DELETE') {
            require_once(realpath(dirname(__FILE__) . '/DELETE.php'));
        }
    } else {
        echo json_encode([
            'error' => true,
            'message' => 'Você não está logado, ou seu token é inválido.'
        ]);
        exit;
    }
}
