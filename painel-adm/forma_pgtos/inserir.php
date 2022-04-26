<?php

require_once('../../conexao.php');

$codigo = $_POST['codigo'];
$nome = $_POST['nome'];
$id = $_POST['id'];

//edição
$antigoCodigo = $_POST['antigoCodigo'];

//evitar codigo forma de pagamento duplicado (inserir um novo código, ou alterar um existente, e utilizar um código que já exista na tabela)
if (@$antigoCodigo != $codigo) {
    /*quando $antigoCodigo não existir, ou seja, for uma inserção, vai cair aqui também, já que $antigoCodigo será diferente de $codigo, este último existe
    */

    $query_verif = $pdo->prepare("SELECT * FROM forma_pgtos WHERE codigo = :codigo");
    $query_verif->bindValue(":codigo", $codigo);
    $query_verif->execute();

    $res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_verif = @count($res_verif);

    if ($total_reg_verif > 0) {
        echo "Forma de pagamento já cadastrada";
        exit();
    }
}

if ($id == '') { //se o id não tiver sido criado, é inserção

    $query = $pdo->prepare("INSERT INTO forma_pgtos SET codigo = :codigo, nome = :nome");

    $query->bindValue(":codigo", $codigo);
    $query->bindValue(":nome", $nome);

    $query->execute();
} else { //se o id já existir, é edição


    $query = $pdo->prepare("UPDATE forma_pgtos SET codigo = :codigo, nome = :nome WHERE id = :id");

    $query->bindValue(":codigo", $codigo);
    $query->bindValue(":nome", $nome);
    $query->bindValue(":id", $id);
    $query->execute();
}
echo "Salvo com Sucesso!";
