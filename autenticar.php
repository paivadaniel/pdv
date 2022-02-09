<?php

require_once('conexao.php');
@session_start();

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$query = $pdo->prepare("SELECT * FROM usuarios WHERE (email = :usuario OR cpf = :usuario) AND senha = :senha");

$query->bindValue(":usuario", $usuario);
$query->bindValue(":senha", $senha);

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC); //posso acessar todos os campos da tabela de usuários, como id, nome, email, cpf, senha e nivel
$total_reg = @count($res);

if ($total_reg > 0) {
    $nivel = $res[0]['nivel'];

    $_SESSION['nome_usuario'] = $res[0]['nome'];
    $_SESSION['cpf_usuario'] = $res[0]['cpf'];
    $_SESSION['nivel_usuario'] = $res[0]['nivel'];


    if($nivel == "Admin") {
        echo "<script language='javascript'>window.location='painel-adm'</script>";
    } else if ($nivel == "Operador") {
        echo "<script language='javascript'>window.location='painel-operador'</script>";
    } else if ($nivel == "Tesoureiro") {
        echo "<script language='javascript'>window.location='painel-tesoureiro'</script>";
    }
} else {
    echo "<script language='javascript'>window.alert('Dados Incorretos')</script>";
    echo "<script language='javascript'>window.location='index.php'</script>";
}


