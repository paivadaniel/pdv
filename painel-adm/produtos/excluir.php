<?php

require_once('../../conexao.php');

$id = $_POST['id'];

//DELETER IMAGEM DA PASTA (DE CATEGORIA QUE SERÁ EXCLUÍDA A SEGUIR)
//deve ser feito antes da exclusão da categoria
$query_con = $pdo->query("SELECT * FROM produtos WHERE id = '$id'");
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
$imagem = $res_con[0]['foto'];
unlink('../../img/produtos/'.$imagem);

//EXCLUSÃO DA CATEGORIA
$query_verif = $pdo->query("DELETE FROM produtos WHERE id = '$id'"); 
/*
não preciso passar parâmetro com bindValue pois não estou usando prepare, e isso pois
os dados não estão vindo de um formulário
*/

echo "Excluído com Sucesso!";

?>