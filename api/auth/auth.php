<?php

class Usuarios
{
    public static function login($api)
    {
        if ($_POST) {
            if (!$_POST['login'] or !$_POST['senha']) {
                echo json_encode(['ERRO' => 'Falta informacoes!']);
                exit;
            } else {
                $login = addslashes(htmlspecialchars($_POST['login'])) ?? '';
                $senha = addslashes(htmlspecialchars($_POST['senha'])) ?? '';
                $secretJWT = $GLOBALS['secretJWT'];

                $db = DB::connect();
                $rs = $db->prepare("SELECT * FROM ? WHERE matricula = ?");
                $exec = $rs->execute([$api, $login]);
                $obj = $rs->fetchObject();
                $rows = $rs->rowCount();

                if ($rows > 0) {
                    $idDB          = $obj->matricula;
                    $nameDB        = $obj->nome;
                    $passDB        = $obj->senha;
                    $validUsername = true;
                    // $validPassword = password_verify($senha, $passDB) ? true : false;
                    $validPassword = $passDB == $senha ? true : false;
                } else {
                    $validUsername = false;
                    $validPassword = false;
                }

                if ($validUsername and $validPassword) {
                    //$nextWeek = time() + (7 * 24 * 60 * 60);
                    $expire_in = time() + 60000;
                    $token     = JWT::encode([
                        'id'         => $idDB,
                        'name'       => $nameDB,
                        'expires_in' => $expire_in,
                    ], $GLOBALS['secretJWT']);

                    $sql = $db->prepare("UPDATE ? SET token = ? WHERE matricula = ?");
                    $sql->execute([$api, $token, $idDB]);
                    echo json_encode(['token' => $token, 'data' => JWT::decode($token, $secretJWT)]);
                } else {
                    if (!$validPassword) {
                        echo json_encode(['ERROR', 'invalid password']);
                    }
                }
            }
        } else {
            echo json_encode(['ERRO' => 'Falta informacoes!']);
            exit;
        }
    }

    public function verificar($api)
    {
        $headers = apache_request_headers();
        if (isset($headers['authorization'])) {
            $token = $headers['authorization'];
        } else {
            echo json_encode(['ERRO' => 'Você não está logado, ou seu token é inválido.']);
            exit;
        }

        $db   = DB::connect();
        $rs   = $db->prepare("SELECT * FROM ? WHERE token = ?");
        $exec = $rs->execute([$api, $token]);
        $obj  = $rs->fetchObject();
        $rows = $rs->rowCount();
        $secretJWT = $GLOBALS['secretJWT'];

        if ($rows > 0) :
            $idDB    = $obj->id;
            $tokenDB = $obj->token;

            $decodedJWT = JWT::decode($tokenDB, $secretJWT);
            if ($decodedJWT->expires_in > time()) {
                return true;
            } else {
                $sql = $db->query("UPDATE ? SET token = '' WHERE matricula = ?");
                $sql->execute([$api, $token, $idDB]);
                return false;
            }
        else :
            return false;
        endif;
    }
}
