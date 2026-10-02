<?php

header("Content-Type: application/json");

require "conexao.php";

$metodo = $_SERVER ["REQUEST_METHOD"];

// para capturar os dados enviados via json, veio antes do insert into para servir pra todos os metodos de uma vez
$json = file_get_contents("php://input");
$dados = json_decode($json,true);

if($metodo == "POST"){

    // se esses campos forem vazios (empty), retorna um erro, pq todos precisam ser preenchidos 
if (empty($dados['equipamento']) || empty($dados['setor']) || empty($dados['descricao']) || empty($dados['prioridade']) || empty($dados['status'])) {
echo json_encode(['Mensagem' => 'Erro: Todos os campos são obrigatórios.']); exit;}

                //comparando cada campo individualmente para nao dar erro
if ($dados ["prioridade"] != "baixa" && $dados["prioridade"] != "media" && $dados["prioridade"] != "alta"){
echo json_encode(["Mensagem"=>"Erro: Prioridade inválida (Tente uma dessas opções: baixa, media ou alta)."]);exit;}

if ($dados ["status"] != "aberto" && $dados ["status"] != "em andamento" && $dados ["status"] != "concluido"){
echo json_encode(["Mensagem"=>"Erro: Status inválido (Tente uma dessas opções: aberto, em andamento ou concluido)."]);exit;}
// 

    $sql = "INSERT INTO chamados (equipamento,setor,descricao,prioridade,status) VALUES(?,?,?,?,?)";
    $comando = $pdo -> prepare($sql);
    $comando -> execute ([
        $dados ["equipamento"],
        $dados ["setor"],
        $dados ["descricao"],
        $dados ["prioridade"],
        $dados ["status"]
    ]);
// se passar por todas as validações, ai sim ele mostra a mensgaem de sucesso
    echo json_encode(["Mensagem"=>"Chamado cadastrado com sucesso!"]);exit;

};

if($metodo == "GET"){
    $sql = "SELECT * FROM chamados ORDER BY id";

    $comando = $pdo -> prepare($sql);

    $comando = $pdo -> query($sql);

    $chamados = $comando -> fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($chamados);exit;
    
}


if($metodo == "PUT"){

if (empty($dados['id']) || empty($dados['equipamento']) || empty($dados['setor']) || empty($dados['descricao']) || empty($dados['prioridade']) || empty($dados['status'])) {
echo json_encode(['Mensagem' => 'Erro: id e todos os campos são obrigatórios.']);exit;
}

if ($dados["prioridade"] != "baixa" && $dados["prioridade"] != "media" && $dados["prioridade"] != "alta") {
echo json_encode(["Mensagem" => "Erro: Prioridade inválida (Tente uma dessas opções: baixa, media ou alta)."]);exit; 
}

if ($dados["status"] != "aberto" && $dados["status"] != "em andamento" && $dados["status"] != "concluido") {
echo json_encode(["Mensagem" => "Erro: Status inválido (Tente uma dessas opções: aberto, em andamento ou concluido)."]);exit;
}
$sql = "UPDATE chamados SET equipamento=?,setor=?,descricao=?,prioridade=?,status=? WHERE id=?";

$comando = $pdo -> prepare($sql);

     $comando -> execute ([
        $dados ["equipamento"],
        $dados ["setor"],
        $dados ["descricao"],
        $dados ["prioridade"],
        $dados ["status"],
        $dados ["id"] ]);

    echo json_encode(["Mensagem"=>"Chamado atualizado com sucesso!"]);exit;

}


if($metodo == "DELETE"){

if (empty($dados['id'])){
    echo json_encode(['Mensagem' => 'Erro: O id é obrigatório.']);exit;
}

$sql = "DELETE FROM chamados WHERE id=?";

    $comando = $pdo -> prepare($sql);

    $comando -> execute ([
        $dados["id"]
    ]);

    echo json_encode(["Mensagem"=>"Chamado deletado com sucesso!"]);exit;

}


// CREATE TABLE chamados(
//     id SERIAL PRIMARY KEY,
//     equipamento VARCHAR (50),
//     setor VARCHAR (50),
//     descricao VARCHAR (350),
//     prioridade VARCHAR (5),
//     status VARCHAR (13)
// );






















