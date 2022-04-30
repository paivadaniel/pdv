<?php

require_once('../conexao.php');

@session_start();
$id_usuario = $_SESSION['id_usuario'];

$caixa = $_POST['caixa_fechamento'];
$gerente_fechamento = $_POST['gerente_fechamento'];
$valor_fechamento = $_POST['valor_fechamento'];
$valor_fechamento = str_replace(',', '.', $valor_fechamento); //se o usuário digitar valor com vírgula, ela é substituída por ponto, pois no banco de dados só salva valores com ponto
$senha_gerente = $_POST['senha_gerente_fechamento'];


//totaliza as vendas do caixa aberto, soma com o valor abertura, para depois subtrair do valor de fechamento e obter o valor de quebra
$query = $pdo->query("SELECT * from caixa WHERE operador = '$id_usuario' AND status = 'Aberto'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_abertura = $res[0]['valor_ab'];
$id_abertura = $res[0]['id'];

$valor_vendido = 0;
$query = $pdo->query("SELECT * from vendas WHERE operador = '$id_usuario' AND abertura = '$id_abertura'");
$res2 = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res2);

if ($total_reg > 0) {
    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res2[$i] as $key => $value) {
        } //fechamento do foreach
        $valor_vendido += $res2[$i]['valor'];
    }
}

$valor_quebra = $valor_fechamento - ($valor_abertura + $valor_vendido);

echo $valor_quebra . ' - ' . $valor_vendido;
exit();

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

echo "Fechamento feito com Sucesso!";
