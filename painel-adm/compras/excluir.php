<?php

require_once('../../conexao.php');

$id = $_POST['id'];

//VERIFICA SE PODE APAGAR
$query = $pdo->query("SELECT * FROM compras WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg > 0) {
    $pago = $res[0]['pago'];

    if ($pago == 'Sim') {
        echo 'Essa compra já está paga, você não pode excluí-la.';
        /*
    fazendo isso não tem como o usuário digitar o id na url e abrir o modal da conta para editá-la,
    mesmo desabilitando mostrar o botão de edição   
    */
        exit();
    }

}

$query2 = $pdo->query("DELETE FROM contas_pagar WHERE id_compra = '$id'");
//deve excluir primeiro da tabela contas_pagar, que recebe o id da tabela de compras

$query1 = $pdo->query("DELETE FROM compras WHERE id = '$id'"); 
//em seguida, exclui da tabela de compras

/*
não preciso passar parâmetro com bindValue pois não estou usando prepare, e isso pois
os dados não estão vindo de um formulário
*/

echo "Excluído com Sucesso!";

?>