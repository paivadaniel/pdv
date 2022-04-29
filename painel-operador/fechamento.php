<?php

require_once('../conexao.php');

@session_start();
$id_usuario = $_SESSION['id_usuario'];

$caixa = $_POST['caixa_fechamento'];
$gerente_fechamento = $_POST['gerente_fechamento'];
$valor_fechamento = $_POST['valor_fechamento'];
$valor_fechamento = str_replace(',', '.', $valor_fechamento); //se o usuário digitar valor com vírgula, ela é substituída por ponto, pois no banco de dados só salva valores com ponto
$senha_gerente = $_POST['senha_gerente_fechamento'];


//VERIFICA SE O CAIXA ESTÁ ABERTO
$query = $pdo->query("SELECT * from caixa WHERE operador = '$id_usuario' AND status = 'Aberto'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_ab = $res[0]['valor_ab'];


//verifica se a senha do gerente foi digitada corretamente
$query = $pdo->prepare("SELECT * from usuarios WHERE id = :id_gerente AND senha = :senha_gerente");
$query->bindValue(":id_gerente", $gerente_fechamento);
$query->bindValue(":senha_gerente", $senha_gerente);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg == 0) {
    echo "A senha do gerente está incorreta! Não foi possível fechar o caixa.";
    exit();
}

//atualiza a tabela do caixa com o fechamento dele
$query5 = $pdo->prepare("UPDATE caixa SET data_fec = curDate(), hora_fec = curTime(), valor_fec = :valor_fechamento, valor_vendido = :valor_vendido, valor_quebra = :valor_quebra, gerente_fec = :gerente_fec, status = 'Fechado'"); //recupera a variável de sessão $id_usuario 

$query5->bindValue(":valor_fechamento", $valor_fechamento);
$query5->bindValue(":valor_vendido", $valor_vendido);
$query5->bindValue(":valor_quebra", $valor_quebra);
$query5->bindValue(":gerente_fec", $gerente_fechamento);


$query5->execute();

echo "Abertura feita com Sucesso!";
