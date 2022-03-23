<?php

require_once('../../conexao.php');

$id = $_POST['id'];
@session_start();

$id_usuario = $_SESSION['id_usuario'];

//não vamos excluir a imagem da conta paga e nem a conta paga
/*
$imagem = $res[0]['arquivo'];

if($imagem != 'sem-foto.jpg') {
    unlink('../../img/contas_pagar/'.$imagem);
}


//EXCLUSÃO DA CONTA
$query_verif = $pdo->query("DELETE FROM contas_pagar WHERE id = '$id'"); 
*/

$query_verif = $pdo->query("UPDATE contas_pagar SET pago = 'Sim', usuario = $id_usuario WHERE id = '$id'");

//VERIFICA SE É UMA COMPRA DE PRODUTO, PARA ALTERAR TAMBÉM NA TABELA compras
$query = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

$total_reg = @count($res);

if ($total_reg > 0) {
    $descricao = $res[0]['descricao']; //precisa comparar a descrição também da conta, para ver se é "Compra de Produtos"

    if ($descricao == 'Compra de Produtos') {

        $id_compra = $res[0]['id_compra']; //armazena o id_compra, que é o id da compra na tabela de compras

        $query2 = $pdo->query("UPDATE compras SET pago = 'Sim' WHERE id = '$id_compra'");
    }
}

//LANÇAR NAS MOVIMENTAÇÕES
$query2 = $pdo->query("SELECT * from contas_pagar WHERE id = '$id'");
$res = $query2->fetchAll(PDO::FETCH_ASSOC);
$descricao = $res[0]['descricao'];
$valor = $res[0]['valor'];

$query3 = $pdo->query("INSERT INTO movimentacoes SET tipo = 'Saída', descricao = '$descricao', valor = '$valor', usuario = '$id_usuario', data = curDate(), id_mov = '$id'");

echo "Baixado com Sucesso!";
