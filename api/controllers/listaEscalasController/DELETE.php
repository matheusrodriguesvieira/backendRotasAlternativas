<?php
if ($acao == 'delete') {
    if ($parametro != "") {
        $db = DB::connect();

        $sql = 'SELECT * FROM listaescalas where listaescalas.idlista = ?';
        $sql = $db->prepare($sql);
        $sql->execute([$parametro]);
        $obj = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$obj) {
            echo json_encode([
                "message" => "Não foi possível encontrar a lista"
            ]);
            exit;
        }


        // -----------------------------
        // VERIFICA SE EXISTE REFERENCIA NA TABELA DE OPERADOREQUIPAMENTO
        // -----------------------------
        $sql = 'SELECT * FROM operadorequipamento where operadorequipamento.idlista = ?';
        $sql = $db->prepare($sql);
        $sql->execute([$parametro]);
        $obj = $sql->fetch(PDO::FETCH_ASSOC);

        if ($obj) {
            $sql = 'DELETE FROM operadorequipamento WHERE operadorequipamento.idlista = ?';
            $sql = $db->prepare($sql);
            $sql->execute([$parametro]);
        }


        // -----------------------------
        // VERIFICA SE EXISTE REFERENCIA NA TABELA DE OPERADORFORAESCALA
        // -----------------------------
        $sql = 'SELECT * FROM operadorforaescala where operadorforaescala.idlista = ?';
        $sql = $db->prepare($sql);
        $sql->execute([$parametro]);
        $obj = $sql->fetch(PDO::FETCH_ASSOC);

        if ($obj) {
            $sql = 'DELETE FROM operadorforaescala WHERE operadorforaescala.idlista = ?';
            $sql = $db->prepare($sql);
            $sql->execute([$parametro]);
        }


        $sql = 'DELETE FROM listaescalas WHERE listaescalas.idlista = ?';
        $sql = $db->prepare($sql);
        $sql->execute([$parametro]);

        echo json_encode(["message" => "Dados apagados com sucesso!"]);
    }
}
