<?php

require_once('../conexao.php');
@session_start();

$email = $_POST['email'];
$senha = $_POST['senha'];

$query = $pdo->prepare("SELECT * FROM usuarios WHERE email=:email and senha=:senha");

/*
ao invés de usar email=:email e senha=:senha
poderia ter feito email=$email e senha=$senha
porém, essa não seria uma forma segura, e estaria dando abertura para sql injection
o primeiro email e senha é o nome que está no banco de dados, já o :email e o :senha,
é o que irá receber $email e $senha pelo bindValue, como afirmado acima
faz isso para evitar sql injection
*/

$query->bindValue(":email", $email);
$query->bindValue(":senha", $senha);
/*prepare() precisa de execute() para executar o código SQL. 
o prepare() apenas prepara o SQL para execução.
*/
$query->execute();

$res=$query->fetchAll(PDO::FETCH_ASSOC);
$total_reg=@count($res);


if($total_reg > 0) {

//quando logar são criadas as variáveis de sessão
//$nome_usuario = $res[0]['nome'] // essa seria uma variável comum, abaixo uma de sessão
$_SESSION['nome_usuario'] = $res[0]['nome'];
$_SESSION['nivel_usuario'] = $res[0]['nivel'];

//o nível deve ficar dentro do if, pois se $res não tiver resultados no banco de dados, o nível do usuário não existe, pois nõo existe usuário
    $nivel = $res[0]['nivel'];

    if($nivel == "Admin") {
        echo "<script language='javascript'>window.location='painel-adm/index.php'</script>";
    } else if ($nivel == "Cliente") {
        echo "<script language='javascript'>window.location='painel-cliente/index.php'</script>";
    } else {
        echo "<script language='javascript'>window.alert('Usuário sem permissão de acesso!')</script>";
        echo "<script language='javascript'>window.location='index.php'</script>";
    
    }

    } else {
    echo "<script language='javascript'>window.alert('Dados incorretos!')</script>";
    echo "<script language='javascript'>window.location='index.php'</script>";

}
?>