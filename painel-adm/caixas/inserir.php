<?php

require_once('../../conexao.php');

$nome = $_POST['nome'];
$id = $_POST['id'];

//edição
$antigoNome = $_POST['antigoNome'];

//evitar nome categoria duplicado
if (@$antigoNome != $nome) {
    /*quando $antigoNome não existir, ou seja, for uma inserção, vai cair aqui também, já que $antigoNome será diferente de $nome, este último existe
    */

    $query_verif = $pdo->prepare("SELECT * FROM caixas WHERE nome = :nome");
    $query_verif->bindValue(":nome", $nome);
    $query_verif->execute();

    $res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_verif = @count($res_verif);

    if ($total_reg_verif > 0) {
        echo "Caixa já cadastrado";
        exit();
    }
}

if ($id == '') { //se o id não tiver sido criado, é inserção

    $query = $pdo->prepare("INSERT INTO caixas SET nome = :nome");

    $query->bindValue(":nome", $nome);

    $query->execute();
} else { //se o id já existir, é edição


    $query = $pdo->prepare("UPDATE caixas SET nome = :nome WHERE id = :id");

    $query->bindValue(":nome", $nome);
    $query->bindValue(":id", $id);
    $query->execute();
}
echo "Salvo com Sucesso!";
