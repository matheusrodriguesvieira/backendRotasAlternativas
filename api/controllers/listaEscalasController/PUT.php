<?php if ($acao == 'update') {
    if ($parametro != "") {

        // 1 - VERIFICAR SE O OPERADOR A ADICIONAR EXISTE NA LISTA DE OPERADORES FORA DE ESCALA
        // 2 - SE EXISTE, REMOVER O OPERADOR DA LISTA DE FORA DE ESCALA
        // 3 - SE O OPERADOR ESCALADO FOR VÁLIDO, MANDA-LO PARA A LISTA FORA DE ESCALA
        // 4 - MODIFICAR A ESCALA, ADICIONANDO O OPERADOR

        // MÉTODO RECEBE UM JSON NO SEGUINTE FORMATO:
        // {
        //     "escala" : [
        //         {
        //             "matricula": MATRICULA,
        //             "tag": TAG,
        //             "localizacao": LOCALIZAÇÃO
        //             "atividade": ATIVIDADE
        //             "transporte": TRANSPORTE   
        //         },
        //     ]
        // }

        $json = file_get_contents("php://input");
        $dados = json_decode($json, true);

        if (!$dados) {
            var_dump($dados);
            exit;
        }

        if (!array_key_exists('escala', $dados)) {
            $response = array(
                "error" => true,
                "message" => 'Parâmetro \'escala\' não encontrado.'
            );
            echo json_encode($response);
            exit;
        }

        foreach ($dados['escala'] as $escala) {
            if (!array_key_exists('matricula', $escala) || !array_key_exists('tag', $escala) || !array_key_exists('localizacao', $escala) || !array_key_exists('atividade', $escala) || !array_key_exists('transporte', $escala)) {
                echo json_encode([
                    "error" => true,
                    "message" => "Parâmetros incompletos."
                ]);

                exit;
            }
        }

        // ---------------------------------------
        // VERIFICANDO SE ESTÁ TENTANDO ATUALIZAR O MESMO OPERADOR EM DOIS LUGARES DISTINTOS
        // ---------------------------------------
        for ($i = 0; $i < count($dados['escala']); $i++) {
            if ($dados['escala'][$i]['matricula'] > 5) {
                if (count(array_values(array_filter($dados['escala'], fn ($element) => $element['matricula'] == $dados['escala'][$i]['matricula']))) > 1) {
                    echo json_encode([
                        "error" => true,
                        "message" => "Tentando inserir operador válido em múltiplos equipamentos"
                    ]);
                    exit;
                }
            }
        }

        for ($i = 0; $i < count($dados['escala']); $i++) {
            if (count(array_values(array_filter($dados['escala'], fn ($element) => $element['tag'] == $dados['escala'][$i]['tag']))) > 1) {
                echo json_encode([
                    "error" => true,
                    "message" => "Equipamento escalado em múltiplos campos."
                ]);
                exit;
            }
        }




        try {
            $db = DB::connect();
            $db->beginTransaction();

            // ---------------------------------------
            // VERIFICANDO SE O OPERADOR EXISTE
            // ---------------------------------------

            for ($i = 0; $i < count($dados['escala']); $i++) {
                $sql = $db->prepare('SELECT * from operadores where operadores.matricula = ?');
                $sql->execute([$dados['escala'][$i]['matricula']]);
                $operador = $sql->fetch(PDO::FETCH_ASSOC);

                if (!$operador) {
                    echo json_encode([
                        "error" => true,
                        "message" => "Operador {$dados['escala'][$i]['matricula']} não encontrado.",
                    ]);
                    exit;
                }


                // ---------------------------------------
                // VERIFICANDO SE O equipamento existe
                // ---------------------------------------
                $sql = $db->prepare('SELECT * from equipamentos where equipamentos.tag = ?');
                $sql->execute([$dados['escala'][$i]['tag']]);
                $equipamento = $sql->fetch(PDO::FETCH_ASSOC);


                if (!$equipamento) {
                    echo json_encode([
                        "error" => true,
                        "message" => "Equipamento {$dados['escala'][$i]['tag']} não encontrado.",
                    ]);
                    exit;
                }

                // ---------------------------------------
                // VERIFICANDO SE O OPERADOR é autorizado a operar
                // ---------------------------------------

                $categoria = $equipamento['categoria'];

                if (!$operador[$categoria]) {
                    echo json_encode([
                        "error" => true,
                        "message" => "Operador {$operador['nome']} - {$operador['matricula']} não é autorizado a operar {$equipamento['tag']}",
                    ]);
                    exit;
                }

                // ---------------------------------------
                // VERIFICANDO SE O OPERADOR já esta escalado
                // ---------------------------------------
                $sql = $db->prepare('SELECT * from operadorequipamento where operadorequipamento.matricula = ? and operadorequipamento.idlista = ? and operadorequipamento.tag != ? and operadorequipamento.matricula not between 1 and 5');
                $sql->execute([$dados['escala'][$i]['matricula'], $parametro, $dados['escala'][$i]['tag']]);
                $operador = $sql->fetch(PDO::FETCH_ASSOC);

                if ($operador) {
                    echo json_encode([
                        "error" => true,
                        "message" => "Operador {$dados['escala'][$i]['matricula']} já está escalado.",
                    ]);
                    exit;
                }
            }


            foreach ($dados['escala'] as $valor) {

                $sql = $db->prepare("SELECT * FROM operadorequipamento WHERE operadorequipamento.idlista = ? and operadorequipamento.tag = ?");
                $sql->execute([$parametro, $valor['tag']]);
                $obj = $sql->fetch(PDO::FETCH_ASSOC);

                if (!$obj) {
                    throw new Exception('Escala não encontrada');
                }

                // ----------------------------------------
                // VERIFICAR SE O OPERADOR QUE ESTÁ SAINDO DA ESCALA É VÁLIDO, SE SIM, ADICIONÁLO A LISTA FORA DE ESCALA
                // ----------------------------------------
                if ($obj['matricula'] > 5) {
                    $sql = $db->prepare("INSERT INTO operadorforaescala (matricula, idLista) VALUES (?,?)");
                    $sql->execute([$obj['matricula'], $parametro]);
                }

                // ----------------------------------------
                // VERIFICAR SE O OPERADOR QUE ESTÁ SENDO ADICIONADO EXISTE NA LISTA FORA DE ESCALA
                // ----------------------------------------
                $sql = $db->prepare("SELECT * FROM operadorforaescala WHERE operadorforaescala.idlista = ? and operadorforaescala.matricula = ?");
                $sql->execute([$parametro, $valor['matricula']]);
                $obj = $sql->fetch(PDO::FETCH_ASSOC);

                if ($obj) {
                    $sql = $db->prepare("DELETE FROM operadorforaescala WHERE operadorforaescala.idlista = ? and operadorforaescala.matricula = ?");
                    $sql->execute([$parametro, $valor['matricula']]);
                }

                $sql = $db->prepare("UPDATE operadorequipamento SET  matricula = ?, localizacao = ?, atividade = ?, transporte = ? WHERE operadorequipamento.idlista = ? and operadorequipamento.tag = ?");
                $sql->execute([$valor['matricula'], $valor['localizacao'], $valor['atividade'], $valor['transporte'], $parametro, $valor['tag']]);
            }

            $db->commit();
            echo json_encode([
                "error" => false,
                "message" => "Dados atualizados com sucesso."
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "message" => "Erro ao inserir os dados. " . $e->getMessage(),
                "error" => true,
            ]);

            $db->rollBack();
        }

        exit;
    }
}
