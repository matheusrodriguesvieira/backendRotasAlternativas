<?php if ($acao == 'index' && $parametro == '') {

    $db = DB::connect();
    $sql = $db->prepare("SELECT equipamentos.tag, equipamentos.categoria, equipamentos.disponivel FROM equipamentos");
    $sql->execute();
    $obj = $sql->fetchAll(PDO::FETCH_ASSOC);

    if (!$obj) {
        $response = array(
            "message" => "Nenhum equipamento encontrado!"
        );
        echo json_encode($response);
        exit;
    }

    echo json_encode($obj);
    exit;
}

if ($acao == 'show' && $parametro != '') {
    $db = DB::connect();
    $sql = $db->prepare("SELECT equipamentos.tag, equipamentos.categoria, equipamentos.disponivel FROM equipamentos WHERE equipamentos.tag = '{$parametro}'");
    $sql->execute();
    $obj = $sql->fetchObject();

    if ($obj) {
        echo json_encode($obj);
    } else {
        $response = array(
            "message" => "Nenhum equipamento encontrado!"
        );
        echo json_encode($response);
    }
    exit;
}
