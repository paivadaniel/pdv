<?php

@session_start();
$id_usuario = $_SESSION['id_usuario'];

require_once('../../conexao.php');

$codigo = $_POST['codigo'];
$quantidade = $_POST['quantidade'];

$estoque = "";
$nome = "Código não cadastrado";
$descricao = "";
$imagem = "";
$valor_venda = "";
$valor_total_item = "";

$query_con = $pdo->query("SELECT * FROM produtos WHERE codigo = '$codigo'"); 
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_res = @count($res);

if($total_res > 0) {
    $estoque = $res[0]['estoque'];
    $nome = $res[0]['nome'];
    $descricao = $res[0]['descricao'];
    $imagem = $res[0]['foto'];
    $valor_venda = $res[0]['valor_venda'];
    $id_produto = $res[0]['id'];

    $valor_total_item = $valor_venda * $quantidade;

    //INSERE NA TABELA itens_venda
    $query = $pdo->prepare("INSERT INTO itens_venda SET produto = :produto, valor_unitario = :valor_venda, usuario = :usuario, venda = '0', quantidade = :quantidade, valor_total_item = :valor_total_item"); //venda=0 pois ela ainda não ocorreu, está no carrinho de compras

    $query->bindValue(":produto", $id_produto);
    $query->bindValue(":valor_venda", $valor_venda);
    $query->bindValue(":usuario", $id_usuario);
    $query->bindValue(":quantidade", $quantidade);
    $query->bindValue(":valor_total_item", $valor_total_item);


    $query->execute();

}

$dados = $estoque . "&-/" . $nome . "&-/" . $descricao . "&-/" . $imagem  . "&-/" . $valor_venda . "&-/" . $valor_total_item;
echo $dados;  

?>
