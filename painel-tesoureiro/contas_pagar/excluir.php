<?php

require_once('../../conexao.php');

$id = $_POST['id'];

//BUSCAR IMAGEM PARA EXCLUIR DA PASTA DE CATEGORIA
//deve ser feito antes da exclusão da categoria
$query_con = $pdo->query("SELECT * FROM categorias WHERE id = '$id'");
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);

//VERIFICA SE A CATEGORIA TEM PRODUTOS CADASTRADOS ANTES DE EXCLUI-LA
$query_p = $pdo->query("SELECT * FROM produtos WHERE categoria = '$id'");
$res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);
if(@count($res_p) > 0) {
    echo "Não é possível excluir essa categoria pois há produtos associados à ela, primeiramente exclua os produtos.";
    exit();
}

//EXCLUIR A IMAGEM DA PASTA
$imagem = $res_con[0]['foto'];


if($imagem != 'sem-foto.jpg') {
    unlink('../../img/categorias/'.$imagem);
}

//EXCLUSÃO DA CATEGORIA
$query_verif = $pdo->query("DELETE FROM categorias WHERE id = '$id'"); 
/*
não preciso passar parâmetro com bindValue pois não estou usando prepare, e isso pois
os dados não estão vindo de um formulário
*/

echo "Excluído com Sucesso!";
