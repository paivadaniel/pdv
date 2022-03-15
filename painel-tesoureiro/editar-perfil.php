<?php

require_once('../conexao.php');

$nome = $_POST['nome-perfil'];
$cpf = $_POST['cpf-perfil'];
$email = $_POST['email-perfil'];
$senha = $_POST['senha-perfil'];
//nãõ tem $_POST['nivel'] pois em EDITAR PERFIL não tem porque o admin mudar o nível dele
$id = $_POST['id-perfil']; //para edição de registro (ou seja, de um usuário que já foi inserido)

//edição
$antigoEmail = $_POST['antigoPerfilEmail'];
$antigoCpf = $_POST['antigoPerfilCpf'];

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
    
    $query_verif = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
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

    $query_verif = $pdo->prepare("SELECT * FROM usuarios WHERE cpf = :cpf");
    $query_verif->bindValue(":cpf", $cpf);
    $query_verif->execute();

    $res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_verif = @count($res_verif);

    if ($total_reg_verif > 0) {
        echo "CPF já cadastrado";
        exit();
    }
}

    $query = $pdo->prepare("UPDATE usuarios SET nome = :nome, cpf = :cpf, email = :email, senha = :senha WHERE id = :id");

    $query->bindValue(":nome", $nome);
    $query->bindValue(":cpf", $cpf);
    $query->bindValue(":email", $email);
    $query->bindValue(":senha", $senha);
    $query->bindValue(":id", $id);
    $query->execute();

echo "Salvo com Sucesso!";
