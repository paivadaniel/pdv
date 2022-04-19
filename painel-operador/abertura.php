<?php

require_once('../conexao.php');

@session_start();
$id_usuario = $_SESSION['id_usuario'];

$caixa = $_POST['caixa'];
$gerente_ab = $_POST['gerente_ab'];
$valor_ab = $_POST['valor_ab'];
$valor_ab = str_replace(',', '.', $valor_ab); //se o usuário digitar valor com vírgula, ela é substituída por ponto, pois no banco de dados só salva valores com ponto
$senha_gerente = $_POST['senha_gerente'];

//verifica se a senha do gerente foi digitada corretamente
$query = $pdo->prepare("SELECT * from usuarios WHERE id = :id_gerente AND senha = :senha_gerente");
$query->bindValue(":id_gerente", $gerente_ab);
$query->bindValue(":senha_gerente", $senha_gerente);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg == 0) {
    echo "A senha do gerente está incorreta! Não foi possível abrir o caixa.";
    exit();
}

//verifica se o caixa já está aberto
$query2 = $pdo->prepare("SELECT * from caixa WHERE caixa = :caixa AND status = 'Aberto'");
$query2->bindValue(":caixa", $caixa);
$query2->execute();
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

if ($total_reg2 > 0) {
    $id_gerente = $res2[0]['gerente_ab'];
    $id_operador = $res2[0]['operador'];

    $query3 = $pdo->query("SELECT * from usuarios WHERE id = '$id_gerente'");
    $res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
    $nome_gerente = $res3[0]['nome'];

    $query4 = $pdo->query("SELECT * from usuarios WHERE id = '$id_operador'");
    $res4 = $query4->fetchAll(PDO::FETCH_ASSOC);
    $nome_operador = $res4[0]['nome'];

    echo "Caixa aberto pelo gerente " . $nome_gerente . " e em uso pelo operador" . $nome_operador . "!";
    exit();
}

//se a senha do gerente foi digitada corretamente o caixa não está aberto
$query5 = $pdo->prepare("INSERT INTO caixa SET caixa = :caixa, gerente_ab = :gerente_ab, valor_ab = :valor_ab, status = 'Aberto', data_ab = curDate(), hora_ab = curTime(), operador = '$id_usuario'"); //recupera a variável de sessão $id_usuario 

$query5->bindValue(":caixa", $caixa);
$query5->bindValue(":gerente_ab", $gerente_ab);
$query5->bindValue(":valor_ab", $valor_ab);

$query5->execute();

echo "Abertura feita com Sucesso!";
