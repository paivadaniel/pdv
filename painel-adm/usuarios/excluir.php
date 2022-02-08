<?php

require_once('../../conexao.php');

$id = $_POST['id'];

$query_verif = $pdo->query("DELETE FROM usuarios WHERE id = '$id'"); 
/*
não preciso passar parâmetro com bindValue pois não estou usando prepare, e isso pois
os dados não estão vindo de um formulário
*/

echo "Excluído com Sucesso!";

?>