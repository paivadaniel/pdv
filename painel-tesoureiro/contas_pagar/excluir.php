<?php

require_once('../../conexao.php');

$id = $_POST['id'];

//BUSCAR IMAGEM PARA EXCLUIR DA PASTA DE CATEGORIA
//deve ser feito antes da exclusão da categoria
$query = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

$total_reg = @count($res);

if ($total_reg > 0) {
    $pago = $res[0]['pago'];

    if ($pago == 'Sim') {
        echo 'Essa conta já está paga, você não pode excluí-la';
        /*
    fazendo isso não tem como o usuário digitar o id na url e abrir o modal da conta para editá-la,
    mesmo desabilitando mostrar o botão de edição   
    */
        exit();
    }
}

//EXCLUIR A IMAGEM DA PASTA
$imagem = $res[0]['arquivo'];


if($imagem != 'sem-foto.jpg') {
    unlink('../../img/contas_pagar/'.$imagem);
}

//EXCLUSÃO DA CATEGORIA
$query_verif = $pdo->query("DELETE FROM contas_pagar WHERE id = '$id'"); 
/*
não preciso passar parâmetro com bindValue pois não estou usando prepare, e isso pois
os dados não estão vindo de um formulário
*/

echo "Excluído com Sucesso!";
