<?php
if ($api == 'supervisores') {

    if (Usuarios::verificar('supervisores')) {
        if ($metodo == 'GET') {
            require_once(realpath(dirname(__FILE__) . '/GET.php'));
        }
    }

    if ($metodo == "POST") {
        if ($acao == "login" && $parametro == "") {
            Usuarios::login($api);
        }
    }
}
