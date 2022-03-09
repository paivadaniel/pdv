<?php

require_once('../../conexao.php');

$fornecedor = $_POST['fornecedor'];
$valor_compra = $_POST['valor_compra'];
$valor_compra = str_replace(',', '.', $valor_compra);
$quantidade = $_POST['quantidade'];
$id = $_POST['id'];

$query = $pdo->prepare("UPDATE produtos SET estoque = :quantidade, fornecedor = :fornecedor, valor_compra = :valor_compra WHERE id = :id");

$query->bindValue(":quantidade", $quantidade);
$query->bindValue(":fornecedor", $fornecedor);
$query->bindValue(":valor_compra", $valor_compra);
$query->bindValue(":id", $id);
$query->execute();

echo "Salvo com Sucesso!";
