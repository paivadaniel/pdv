<?php

require_once('../../conexao.php');

$codigo = $_POST['codigo'];

$dados = 0;
$query_con = $pdo->query("SELECT * FROM produtos WHERE codigo = '$codigo'"); 
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_res = @count($res);

if($total_res > 0) {
    $estoque = $res[0]['estoque'];
    $nome = $res[0]['nome'];
    $descricao = $res[0]['descricao'];
    $imagem = $res[0]['foto'];
}

$dados = $estoque . "&-/" . $nome . "&-/" . $descricao . "&-/" . $imagem;
echo $dados;


?>
