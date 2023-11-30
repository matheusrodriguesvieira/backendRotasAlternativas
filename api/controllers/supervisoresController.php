<?php
if ($api == 'supervisores') {
    if ($metodo == 'GET') {

        if ($acao == 'index' && $parametro == '') {

            // ---------------------------------------
            // PEGA TODOS OS OPERADORES
            // ---------------------------------------

            // $json = file_get_contents("php://input");
            // $dados = json_decode($json, true);

            // if (!$dados) {
            //     $response = array(
            //         "message" => 'Parâmetro \'turma\' não encontrado.'
            //     );
            //     echo json_encode($response);
            //     exit;
            // }

            // if (!array_key_exists('turma', $dados)) {
            //     $response = array(
            //         "message" => 'Parâmetro \'turma\' não encontrado.'
            //     );
            //     echo json_encode($response);
            //     exit;
            // }

            if (empty($_GET['turma'])) {
                $db = DB::connect();
                $sql = $db->prepare("SELECT matricula, nome, turma from supervisores");
                $sql->execute();
                $obj = $sql->fetchAll(PDO::FETCH_ASSOC);

                if (!$obj) {
                    $response = array(
                        "message" => "Nenhum supervisor encontrado!"
                    );
                    echo json_encode($response);
                    exit;
                }

                echo json_encode($obj);
                exit;
            } else {

                $turma = $_GET['turma'];

                $db = DB::connect();
                $sql = $db->prepare("SELECT matricula, nome, turma from supervisores WHERE operadores.turma = ?");
                $sql->execute([$turma]);
                $obj = $sql->fetchAll(PDO::FETCH_ASSOC);

                if (!$obj) {
                    $response = array(
                        "message" => "Nenhum supervisor encontrado!"
                    );
                    echo json_encode($response);
                    exit;
                }

                echo json_encode($obj);
                exit;
            }
        }

        if ($acao == 'show' && $parametro != '') {
            // ---------------------------------------
            // PEGA UM OPERADOR ESPECÍFICO
            // ---------------------------------------


            $db = DB::connect();
            $sql = $db->prepare("SELECT matricula, nome, turma from supervisores WHERE supervisores.matricula = {$parametro}");
            $sql->execute();
            $obj = $sql->fetchObject();

            if (!$obj) {
                $response = array(
                    "message" => "Nenhum supervisor encontrado!"
                );
                echo json_encode($response);
                exit;
            }

            echo json_encode($obj);
            exit;
        }


        if (Usuarios::verificar('supervisores')) {
        }
    }

    if ($metodo == "POST") {
        if ($acao == "login" && $parametro == "") {
            Usuarios::login($api);
        }
    }
}
