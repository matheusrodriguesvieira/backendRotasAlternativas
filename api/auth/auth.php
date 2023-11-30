<?php

class Usuarios
{
    public static function login($api)
    {
        $json = file_get_contents("php://input");
        $dados = json_decode($json, true);

        if (!array_key_exists("login", $dados) || !array_key_exists("senha", $dados)) {
            echo json_encode([
                'error' => true,
                "message" => "faltam informações de login ou senha."
            ]);
            exit;
        }

        $login = addslashes(htmlspecialchars($dados['login'])) ?? '';
        $senha = addslashes(htmlspecialchars($dados['senha'])) ?? '';
        $secretJWT = $GLOBALS['secretJWT'];

        $db = DB::connect();
        $rs = $db->prepare("SELECT * FROM $api WHERE matricula = ?");
        $exec = $rs->execute([$login]);
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
            $expire_in = time() + 3600;
            $token     = JWT::encode([
                'id'         => $idDB,
                'name'       => $nameDB,
                'expires_in' => $expire_in,
            ], $GLOBALS['secretJWT']);

            $sql = $db->prepare("UPDATE $api SET token = ? WHERE matricula = ?");
            $sql->execute([$token, $idDB]);
            echo json_encode(['token' => $token, 'data' => JWT::decode($token, $secretJWT)]);
        } else {
            if (!$validPassword) {
                echo json_encode([
                    'error' => true,
                    'message' => 'Invalid user name or password.'
                ]);
            }
        }
    }

    public static function verificar($api)
    {
        $headers = apache_request_headers();
        if (isset($headers['authorization'])) {
            $token = $headers['authorization'];
        } else {
            echo json_encode([
                'error' => true,
                'message' => 'Você não está logado, ou seu token é inválido.'
            ]);
            exit;
        }

        $db   = DB::connect();
        $rs   = $db->prepare("SELECT * FROM $api WHERE token = ?");
        $exec = $rs->execute([$token]);
        $obj  = $rs->fetchObject();
        $rows = $rs->rowCount();
        $secretJWT = $GLOBALS['secretJWT'];

        if ($rows > 0) :
            $idDB    = $obj->matricula;
            $tokenDB = $obj->token;

            $decodedJWT = JWT::decode($tokenDB, $secretJWT);
            if ($decodedJWT->expires_in > time()) {
                return true;
            } else {
                $sql = $db->query("UPDATE $api SET token = '' WHERE matricula = ?");
                $sql->execute([$token, $idDB]);
                return false;
            }
        else :
            return false;
        endif;
    }
}
