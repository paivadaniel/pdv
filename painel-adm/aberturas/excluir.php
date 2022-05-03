<?php

require_once('../../conexao.php');

$id = $_POST['id'];

//altera status da venda para "cancelada"
$query_verif = $pdo->query("UPDATE vendas SET status = 'Cancelada' WHERE id = '$id'"); 

//exclui todos os itens da venda e devolve-os no estoque
$query = $pdo->query("SELECT * FROM itens_venda WHERE venda = '$id'"); //id passado via GET
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

$novo_estoque = 0;

for ($i = 0; $i < $total_reg; $i++) {
    foreach ($res[$i] as $key => $value) {
    }

    $id_produto = $res[$i]['produto'];
    $quantidade_produto = $res[$i]['quantidade'];
    $id_item_venda = $res[$i]['id'];

    //atualizar estoque
    $query2 = $pdo->query("SELECT * FROM produtos WHERE id = '$id_produto'");
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);

    $estoque = $res2[0]['estoque'];
    $novo_estoque = $estoque + $quantidade_produto;

    $query3 = $pdo->query("UPDATE produtos SET estoque = '$novo_estoque' WHERE id = '$id_produto'");

    //deleter venda da tabela itens_venda
    $query_del = $pdo->query("DELETE FROM itens_venda WHERE id = '$id_item_venda'"); //não posso excluir com WHERE venda = '$id', pois senão vai excluir todos os registros dessa venda de uma vez só, e tem que passar de um em um por cada item para devolvê-los ao estoque
    

}

echo "Venda Cancelada com Sucesso!";
