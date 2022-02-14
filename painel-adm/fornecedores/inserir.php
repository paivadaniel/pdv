<?php

require_once('../../conexao.php');

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$tipo_pessoa = $_POST['tipo_pessoa'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$id = $_POST['id']; //para edição de registro (ou seja, de um usuário que já foi inserido)

//edição
$antigoEmail = $_POST['antigoEmail'];
$antigoCpf = $_POST['antigoCpf'];

/* //a validação de preenchimento nos dados foi feita com required nos campos do form em usuarios.php
if($nome == '') {
    echo "Preencha o campo nome!";
    exit();
}

*/

//evitar email duplicado
if (@$antigoEmail != $email) { 
    /*quando $antigoEmail não existir, ou seja, for uma inserção, vai cair aqui também, já que $antigoEmail será diferente de $email, este último existe
    */
    
    $query_verif = $pdo->prepare("SELECT * FROM fornecedores WHERE email = :email");
    $query_verif->bindValue(":email", $email);
    $query_verif->execute();

    $res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_verif = @count($res_verif);

    if ($total_reg_verif > 0) {
        echo "Email já cadastrado";
        exit();
    }
}

//evitar cpf duplicado
if (@$antigoCpf != $cpf) {

    $query_verif = $pdo->prepare("SELECT * FROM fornecedores WHERE cpf = :cpf");
    $query_verif->bindValue(":cpf", $cpf);
    $query_verif->execute();

    $res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_verif = @count($res_verif);

    if ($total_reg_verif > 0) {
        echo "CPF / CNPJ já cadastrado";
        exit();
    }
}

//para dados vindos de formulário sempre utilize prepare() ao invés de query(), para evitar SQL injection
//$query = $pdo->prepare("INSERT INTO usuarios (nome, cpf, email, senha, nivel) VALUES (:nome, :cpf, :email, :senha, :nivel)"); 
//a instrução SQL abaixo tem o mesmo efeito da acima

if ($id == '') { //inserção

    $query = $pdo->prepare("INSERT INTO fornecedores SET nome = :nome, cpf = :cpf, email = :email, telefone = :telefone, endereco = :endereco, tipo_pessoa = :tipo_pessoa");

    $query->bindValue(":nome", $nome);
    $query->bindValue(":cpf", $cpf);
    $query->bindValue(":email", $email);
    $query->bindValue(":telefone", $telefone);
    $query->bindValue(":endereco", $endereco);
    $query->bindValue(":tipo_pessoa", $tipo_pessoa);
    $query->execute();
} else { //edição

    $query = $pdo->prepare("UPDATE fornecedores SET nome = :nome, cpf = :cpf, email = :email, telefone = :telefone, endereco = :endereco, tipo_pessoa = :tipo_pessoa WHERE id = :id");

    $query->bindValue(":nome", $nome);
    $query->bindValue(":cpf", $cpf);
    $query->bindValue(":email", $email);
    $query->bindValue(":telefone", $telefone);
    $query->bindValue(":endereco", $endereco);
    $query->bindValue(":tipo_pessoa", $tipo_pessoa);
    $query->bindValue(":id", $id);
    $query->execute();
}
echo "Salvo com Sucesso!";
