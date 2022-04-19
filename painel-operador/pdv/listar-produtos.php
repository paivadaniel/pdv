<?php

@session_start();
$id_usuario = $_SESSION['id_usuario'];

require_once('../../conexao.php');

$query_con = $pdo->query("SELECT * FROM itens_venda WHERE usuario = '$id_usuario' AND venda = 0");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

echo '<ul class="order-list">';

if ($total_reg > 0) {

    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {
        } //fechamento do foreach

        $produto = $res[$i]['produto'];

        $query_p = $pdo->query("SELECT * FROM produtos WHERE id = '$produto'");
        $res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);
        
        $nome_produto = $res_p[0]['nome'];       


        echo '<li class="mb-1"><img src="../img/produtos/sem-foto.jpg">
          <h4>' . $nome_produto . '</h4>
          <h5>50,00</h5>
        </li>';
    }

    echo '</ul>';
    echo '<h4 class="total mt-4">Total de Produtos</h4>';
    echo '<h1>R$ <span id="sub_total">200</span></h1>';
}
