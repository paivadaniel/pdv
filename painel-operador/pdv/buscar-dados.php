<?php

@session_start();
$id_usuario = $_SESSION['id_usuario'];

require_once('../../conexao.php');

$codigo = $_POST['codigo'];
$quantidade = $_POST['quantidade'];

$estoque = "";
$nome = "Código não cadastrado";
$descricao = "";
$imagem = "";
$valor_venda = "";
$valor_total_item = "";

$query_con = $pdo->query("SELECT * FROM produtos WHERE codigo = '$codigo'");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_res = @count($res);

if ($total_res > 0) {
    $estoque = $res[0]['estoque'];
    $nome = $res[0]['nome'];
    $descricao = $res[0]['descricao'];
    $imagem = $res[0]['foto'];
    $valor_venda = $res[0]['valor_venda'];
    $id_produto = $res[0]['id'];

    $valor_total = $valor_venda * $quantidade;
    $valor_total_format = number_format($valor_total, 2, ',', '.'); //se colocar isso junto com total_venda_format dá erro, de acordo com o autor, é porque a variável fora do if não existirá, creio que porque só aqui tem acesso a tabela de produto

    //INSERE NA TABELA itens_venda
    $query = $pdo->prepare("INSERT INTO itens_venda SET produto = :produto, valor_unitario = :valor_venda, usuario = :usuario, venda = '0', quantidade = :quantidade, valor_total = :valor_total"); //venda=0 pois ela ainda não ocorreu, está no carrinho de compras

    $query->bindValue(":produto", $id_produto);
    $query->bindValue(":valor_venda", $valor_venda);
    $query->bindValue(":usuario", $id_usuario);
    $query->bindValue(":quantidade", $quantidade);
    $query->bindValue(":valor_total", $valor_total);


    $query->execute();
}

//TOTALIZAR A VENDA (tudo isso para chegar à $total_venda)
$total_venda = 0;

$query_con = $pdo->query("SELECT * FROM itens_venda WHERE usuario = '$id_usuario' AND venda = 0  order by id desc");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg > 0) {

    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {
        } //fechamento do foreach

        $valor_total_item = $res[$i]['valor_total'];

        $total_venda += $valor_total_item;
    }

    //formata valores
    $total_venda_format = number_format($total_venda, 2, ',', '.');
}

//DEVOLVER OS DADOS PARA PASSAREM POR SPLIT

$dados = $estoque . "&-/" . $nome . "&-/" . $descricao . "&-/" . $imagem  . "&-/" . $valor_venda . "&-/" . $valor_total . "&-/" . $valor_total_format . "&-/" . $total_venda . "&-/" . $total_venda_format;
echo $dados;
