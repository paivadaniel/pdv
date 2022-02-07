<?php

require_once('../../conexao.php');

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$nivel = $_POST['nivel'];

/* //a validação de preenchimento nos dados foi feita com required nos campos do form em usuarios.php
if($nome == '') {
    echo "Preencha o campo nome!";
    exit();
}

*/

//evitar email duplicado
$query_verif = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
$query_verif->bindValue(":email", $email);
$query_verif->execute();

$res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
$total_reg_verif = @count($res_verif);

if ($total_reg_verif > 0) {
    echo "Email já cadastrado";
    exit();
}

//evitar cpf duplicado
$query_verif = $pdo->prepare("SELECT * FROM usuarios WHERE cpf = :cpf");
$query_verif->bindValue(":cpf", $cpf);
$query_verif->execute();

$res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
$total_reg_verif = @count($res_verif);

if ($total_reg_verif > 0) {
    echo "CPF já cadastrado";
    exit();
}

//para dados vindos de formulário sempre utilize prepare() ao invés de query(), para evitar SQL injection
//$query = $pdo->prepare("INSERT INTO usuarios (nome, cpf, email, senha, nivel) VALUES (:nome, :cpf, :email, :senha, :nivel)"); 
//a instrução SQL abaixo tem o mesmo efeito da acima

$query = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, cpf = :cpf, email = :email, senha = :senha, nivel = :nivel");

$query->bindValue(":nome", $nome);
$query->bindValue(":cpf", $cpf);
$query->bindValue(":email", $email);
$query->bindValue(":senha", $senha);
$query->bindValue(":nivel", $nivel);
$query->execute();

echo "Salvo com Sucesso!";
?>