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
$query_q = $pdo->query("SELECT * from produtos WHERE id = '$id'");
$res_q = $query_q->fetchAll(PDO::FETCH_ASSOC);
$estoque = $res_q[0]['estoque'];
$quantidade += $estoque;

$query = $pdo->prepare("UPDATE produtos SET estoque = :quantidade, fornecedor = :fornecedor, valor_compra = :valor_compra WHERE id = :id");
$query->bindValue(":quantidade", $quantidade);
$query->bindValue(":fornecedor", $fornecedor);
$query->bindValue(":valor_compra", $valor_compra);
$query->bindValue(":id", $id);
$query->execute();

//inserir na tabela compras
$query = $pdo->prepare("INSERT INTO compras SET total = :total, data = curDate(), usuario = :usuario, fornecedor = :fornecedor, pago = 'Não'");
$query->bindValue(":total", $total_compra);
$query->bindValue(":usuario", $id_usuario);
$query->bindValue(":fornecedor", $fornecedor);
$query->execute();
$id_compra = $pdo->lastInsertId(); //o id do último registro inserido no banco de dados é armazenado na variável $id_compra

//inserir na tabela contas_pagar
$query = $pdo->prepare("INSERT INTO contas_pagar SET vencimento = curDate(), descricao = 'Compra de Produtos', valor = :valor, data = curDate(), usuario = :usuario, pago = 'Não', arquivo = 'sem-foto.jpg', id_compra = '$id_compra'");
$query->bindValue(":valor", $total_compra);
$query->bindValue(":usuario", $id_usuario);
$query->execute();

echo "Salvo com Sucesso!";

