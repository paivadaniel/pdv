<?php

require_once('../../conexao.php');

$id_venda = $_POST['id_item_venda'];
$id_gerente = $_POST['gerente'];
$senha_gerente = $_POST['senha_gerente'];

//RECUPERAR O PRODUTO DO ITEM DESTA VENDA
$query_1 = $pdo->query("SELECT * FROM itens_venda WHERE id = '$id_venda'");
$res_1 = $query_1->fetchAll(PDO::FETCH_ASSOC);
$total_reg_1 = @count($res_1);

if ($total_reg_1 > 0) {
    $id_produto = $res_1[0]['produto'];
    $quantidade = $res_1[0]['quantidade'];

}

//DEVOLVER OS PRODUTOS AO ESTOQUE
$query_2 = $pdo->query("SELECT * FROM produtos WHERE id = '$id_produto'");
$res_2 = $query_2->fetchAll(PDO::FETCH_ASSOC);
$total_reg_2 = @count($res_2);

if ($total_reg_2 > 0) {
$estoque = $res_2[0]['estoque'];

$novo_estoque = $estoque + $quantidade;
        
$query_3 = $pdo->prepare("UPDATE produtos SET estoque = :estoque WHERE id = '$id_produto'");
$query_3->bindValue(":estoque", $novo_estoque);
$query_3->execute();

}

//verifica se a senha do gerente foi digitada corretamente
$query = $pdo->prepare("SELECT * from usuarios WHERE id = :id_gerente AND senha = :senha_gerente");
$query->bindValue(":id_gerente", $id_gerente);
$query->bindValue(":senha_gerente", $senha_gerente);
$query->execute();
$res2 = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

if ($total_reg2 == 0) {
    echo "A senha do gerente está incorreta! Não foi possível excluir o item.";
    exit();
}

$query_verif = $pdo->query("DELETE FROM itens_venda WHERE id = '$id_venda'"); 
/*
não preciso passar parâmetro com bindValue pois não estou usando prepare, e isso pois
os dados não estão vindo de um formulário
*/

echo "Excluído com Sucesso!";
