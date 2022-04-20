<?php

require_once('../../conexao.php');

$id_venda = $_POST['id'];
$id_gerente = $_POST['gerente'];
$senha_gerente = $_POST['senha_gerente'];


//verifica se a senha do gerente foi digitada corretamente
$query = $pdo->prepare("SELECT * from usuarios WHERE id = :id_gerente AND senha = :senha_gerente");
$query->bindValue(":id_gerente", $id_gerente);
$query->bindValue(":senha_gerente", $senha_gerente);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg == 0) {
    echo "A senha do gerente está incorreta! Não foi possível excluir o item.";
    exit();
}

$query_verif = $pdo->query("DELETE FROM itens_venda WHERE id = '$id_venda'"); 
/*
não preciso passar parâmetro com bindValue pois não estou usando prepare, e isso pois
os dados não estão vindo de um formulário
*/

echo "Excluído com Sucesso!";

?>