<?php

require_once('../../conexao.php');
@session_start(); //para recuperar a variável de sessão id_usuario, verifique autenticar.php para ver todas as variáveis de sessão

$id_usuario = $_SESSION['id_usuario'];

$fornecedor = $_POST['fornecedor'];
$valor_compra = $_POST['valor_compra'];
$valor_compra = str_replace(',', '.', $valor_compra);
$quantidade = $_POST['quantidade'];
$id = $_POST['id-comprar']; //id do produto

if($quantidade == 0) {
    echo "A quantidade de itens comprados precisa ser maior que zero";
    exit();
}

$total_compra = $quantidade * $valor_compra;

//INCREMENTA ESTOQUE APÓS UM NOVO PEDIDO CONSIDERANDO O QUE JÁ TEM DE ESTOQUE DESSE PRODUTO
$query_q = $pdo->query("SELECT * from produtos WHERE id = :id");
$res_q = $query_q->fetchAll(PDO::FETCH_ASSOC);
$estoque = $res_q[0]['estoque'];
$quantidade += $estoque;

$query = $pdo->prepare("UPDATE produtos SET estoque = :quantidade, fornecedor = :fornecedor, valor_compra = :valor_compra WHERE id = :id");
$query->bindValue(":quantidade", $quantidade);
$query->bindValue(":fornecedor", $fornecedor);
$query->bindValue(":valor_compra", $valor_compra);
$query->bindValue(":id", $id);
$query->execute();

$query = $pdo->prepare("INSERT INTO compras SET total = :total, data = curDate(), usuario = :usuario, valor = :valor_compra, fornecedor = :fornecedor, pago = 'Não'");
$query->bindValue(":total", $total_compra);
$query->bindValue(":usuario", $id_usuario);
$query->bindValue(":valor_compra", $valor_compra);
$query->bindValue(":fornecedor", $fornecedor);
$query->execute();



echo "Salvo com Sucesso!";
