<?php

require_once('../../conexao.php');

$id = $_POST['id'];
@session_start();

$id_usuario = $_SESSION['id_usuario'];

//altera para pago e o usuário que fez essa alteração
$query = $pdo->query("UPDATE contas_receber SET pago = 'Sim', usuario = '$id_usuario' WHERE id = '$id'");

//LANÇAR NAS MOVIMENTAÇÕES
$query2 = $pdo->query("SELECT * from contas_receber WHERE id = '$id'");
$res = $query2->fetchAll(PDO::FETCH_ASSOC);
$descricao = $res[0]['descricao'];
$valor = $res[0]['valor'];

$query3 = $pdo->query("INSERT INTO movimentacoes SET tipo = 'Entrada', descricao = '$descricao', valor = '$valor', usuario = '$id_usuario', data = curDate(), id_mov = '$id'");

echo "Baixado com Sucesso!";
