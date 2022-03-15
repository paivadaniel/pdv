<?php

require_once('../../conexao.php');

$id = $_POST['id'];

//BUSCAR IMAGEM PARA EXCLUIR DA PASTA DE CATEGORIA
//deve ser feito antes da exclusão da categoria
$query_con = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$id'");
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);

//EXCLUIR A IMAGEM DA PASTA
$imagem = $res_con[0]['arquivo'];


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
