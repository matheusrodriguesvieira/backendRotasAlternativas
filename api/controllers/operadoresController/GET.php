<?php
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
        $sql = $db->prepare("SELECT matricula, nome, turma, disponivel, d11, ehgp, dragline, cat777, matriculasupervisor from operadores");
        $sql->execute();
        $obj = $sql->fetchAll(PDO::FETCH_ASSOC);

        if (!$obj) {
            $response = array(
                "message" => "Nenhum operador encontrado!"
            );
            echo json_encode($response);
            exit;
        }

        echo json_encode($obj);
        exit;
    } else {

        $turma = $_GET['turma'];

        $db = DB::connect();
        $sql = $db->prepare("SELECT matricula, nome, turma, disponivel, d11, ehgp, dragline, cat777, matriculasupervisor from operadores WHERE operadores.turma = ?");
        $sql->execute([$turma]);
        $obj = $sql->fetchAll(PDO::FETCH_ASSOC);

        if (!$obj) {
            $response = array(
                "message" => "Nenhum operador encontrado!"
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
    $sql = $db->prepare("SELECT matricula, nome, turma, disponivel, d11, ehgp, dragline, cat777, matriculasupervisor from operadores WHERE operadores.matricula = {$parametro}");
    $sql->execute();
    $obj = $sql->fetchObject();

    if (!$obj) {
        $response = array(
            "message" => "Nenhum operador encontrado!"
        );
        echo json_encode($response);
        exit;
    }

    echo json_encode($obj);
    exit;
}
