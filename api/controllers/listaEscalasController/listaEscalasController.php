<?php
if ($api == 'listaEscalas') {


    if ((Usuarios::verificar('supervisores')) || (Usuarios::verificar('operadores'))) {
        if ($metodo == 'GET') {
            require_once(realpath(dirname(__FILE__) . '/GET.php'));
        }
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
    }
}
