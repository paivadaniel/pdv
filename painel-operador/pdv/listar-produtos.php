<?php

@session_start();
$id_usuario = $_SESSION['id_usuario'];

require_once('../../conexao.php');

echo '<ul class="order-list">';

//recuperar qual caixa está aberto
$query_con = $pdo->query("SELECT * FROM caixa WHERE operador = '$id_usuario' and status = 'Aberto'");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_res = @count($res);

if ($total_res > 0) {
    $nome_caixa = $res[0]['caixa'];
}

$total_venda = 0;

$query_con = $pdo->query("SELECT * FROM itens_venda WHERE usuario = '$id_usuario' AND venda = 0 order by id desc ");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg > 0) {

    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {
        } //fechamento do foreach

        //tabela itens
        $id_item = $res[$i]['id'];
        $id_produto = $res[$i]['produto'];
        $quantidade = $res[$i]['quantidade'];

        $valor_total_item = $res[$i]['valor_total_item'];
        $valor_total_item_format = number_format($valor_total_item, 2, ',', '.');

        $total_venda += $valor_total_item;
        $total_venda_format = number_format($total_venda, 2, ',', '.');

        //tabela produtos
        $query_p = $pdo->query("SELECT * FROM produtos WHERE id = '$id_produto'");
        $res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);
        
        $nome_produto = $res_p[0]['nome'];
        $valor_produto = $res_p[0]['valor_venda'];       
        $foto_produto = $res_p[0]['foto'];       

        echo '<li class="mb-1"><img src="../img/produtos/'.$foto_produto.'">
          <h4>' . $quantidade . ' - '. mb_strtoupper($nome_produto) . '<a href="#"         onclick="modalExcluir('.$id_item.')" title="Excluir Item" style="text-decoration:none">
          <i class="bi bi-x text-danger mx-1"></i>
          </a> </h4> 
          <h5>' . $valor_total_item_format . '</h5>
        </li>';

    }


    echo '</ul>';
    echo '<h4 class="total mt-4">Total de Itens ('.$total_reg.') - Caixa ' . $nome_caixa . '</h4>';
    echo '<div class="row"><div class="col-md-9"><h1>R$ <span id="sub_total">' . $total_venda_format . '</span></h1> </div> <div class="col-md-3" align="right"> <a style="text-decoration:none" href="index.php" title="Fechar Caixa"><i class="bi bi-x text-danger"> </i><small> Sair</small></a></div></div>';
}



