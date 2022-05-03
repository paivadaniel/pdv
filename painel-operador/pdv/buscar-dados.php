<?php

@session_start();
$id_usuario = $_SESSION['id_usuario'];

require_once('../../conexao.php');

$estoque = "";
$nome = "Código não cadastrado";
$descricao = "";
$imagem = "";
$valor_venda = "";
$valor_total_item = "";

$codigo = $_POST['codigo'];
$quantidade = $_POST['quantidade'];
$desconto = $_POST['desconto'];
$desconto = str_replace(',', '.', $desconto); //se o desconto for digitado com vírgula, para efeito de cálculo substitui-a por ponto, se for digitado com ponto, nada se faz, e calcula normalmente, portanto, pode-se digitar o desconto com vírgula ou ponto que dá na mesma

$valor_recebido = $_POST['valor_recebido'];
$valor_recebido = str_replace(',', '.', $valor_recebido);

$forma_pgto_input = $_POST['forma_pgto_input'];

//definir qual o tipo de pagamento e redirecionar para API ou SERIAL



//recuperar o id da abertura
$query_con = $pdo->query("SELECT * FROM caixa WHERE operador = '$id_usuario' and status = 'Aberto'");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);
$total_res = @count($res);

if ($total_res > 0) {
    $id_abertura = $res[0]['id'];
}

//fechar a venda
if ($forma_pgto_input != "") {

    $troco = $_POST['troco'];
    $troco = str_replace('R$', '', $troco); //remove R$ se tiver no troco
    $troco = str_replace(',', '.', $troco);

    $total_venda = $_POST['total_venda'];
    $total_venda = str_replace('R$', '', $total_venda); //remove R$ se tiver no total_venda
    $total_venda = str_replace(',', '.', $total_venda);

    if($total_venda <= 0) { //não pode ser $total_venda == '', pois $total_venda é um decimal, e '' é uma string
        echo 'Não é possível efetuar uma venda sem itens!';
        exit();
    }

    if ($valor_recebido == "") {
        $valor_recebido = $total_venda;
    }

    if ($desconto != "") {
        if ($desconto_porcentagem == 'Sim') {
            $desconto = $desconto . '%';
        } else {
            $desconto = 'R$ ' . $desconto . ',00';
        }
    } else {
        $desconto = '0.00';
    }


    //INSERE NA TABELA vendas
    $query = $pdo->prepare("INSERT INTO vendas SET valor = :valor, data = curDate(), hora = curTime(), operador = :usuario, valor_recebido = :valor_recebido, desconto = :desconto, troco = :troco, forma_pgto = :forma_pgto, abertura = :abertura, status = 'Concluída'");

    $query->bindValue(":valor", $total_venda);
    $query->bindValue(":usuario", $id_usuario);
    $query->bindValue(":valor_recebido", $valor_recebido);
    $query->bindValue(":desconto", $desconto);
    $query->bindValue(":troco", $troco);
    $query->bindValue(":forma_pgto", $forma_pgto_input);
    $query->bindValue(":abertura", $id_abertura);
    $query->execute();

    //relacionar a tabela itens_venda com a tabela vendas, ou seja, mudar venda de 0 para 1 na tabela itens_venda
    $id_venda = $pdo->lastInsertId(); //pega o último id inserido no banco de dados
    $query_con = $pdo->query("UPDATE itens_venda SET venda = '$id_venda' WHERE usuario = '$id_usuario' and venda = 0"); //venda atual tem os itens no campo venda igual à zero

    echo 'Venda Salva! &-/' . $id_venda;
    exit();
}

$troco = 0;
$troco_format = 0;

if ($desconto == '') {
    $desconto = 0;
}

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

    if ($estoque < $quantidade) {
        echo 'Quantidade em estoque insuficiente. &-/ Temos ' . $estoque . ' unidades à pronta entrega.';
        exit();
    }

    //INSERE NA TABELA itens_venda
    $query = $pdo->prepare("INSERT INTO itens_venda SET produto = :produto, valor_unitario = :valor_venda, usuario = :usuario, venda = '0', quantidade = :quantidade, valor_total_item = :valor_total"); //venda=0 pois ela ainda não ocorreu, está no carrinho de compras

    $query->bindValue(":produto", $id_produto);
    $query->bindValue(":valor_venda", $valor_venda);
    $query->bindValue(":usuario", $id_usuario);
    $query->bindValue(":quantidade", $quantidade);
    $query->bindValue(":valor_total", $valor_total);

    $query->execute();

    //DECREMENTAR ESTOQUE APÓS PRODUTO SER INSERIDO EM NA TABELA ITENS_VENDA

    $novo_estoque = $estoque - $quantidade;

    $query_con2 = $pdo->prepare("UPDATE produtos SET estoque = :estoque WHERE id = '$id_produto'"); //todo update tem que colocar WHERE, tem que especificar, senão vai alterar todos os produtos
    //pode ser WHERE  codigo = '$codigo'' dá na mesma que WHERE id = '$id_produto', pois são identificadores únicos para cada produto
    $query_con2->bindValue(":estoque", $novo_estoque);
    $query_con2->execute();
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

        $valor_total_item = $res[$i]['valor_total_item'];

        $total_venda += $valor_total_item;
    }

    if ($desconto_porcentagem == "Sim") {
        $desconto = str_replace('%', '', $desconto); //caso a pessoa digite o sinal de porcentagem, por exemplo, 5%, desconsidera o %
        $total_venda = $total_venda * (1 - ($desconto / 100));
    } else {
        //aplica desconto em R$
        $total_venda = $total_venda - $desconto;
    }


    //formata valores
    $total_venda_format = number_format($total_venda, 2, ',', '.');

    if ($valor_recebido == "") {
        $valor_recebido = 0;
    } else {
        $troco = $valor_recebido - $total_venda;
        $troco_format = number_format($troco, 2, ',', '.');
    }
}

//DEVOLVE OS DADOS PARA pdv.php (passarão por split)
$dados = $novo_estoque . "&-/" . $nome . "&-/" . $descricao . "&-/" . $imagem  . "&-/" . $valor_venda . "&-/" . $valor_total . "&-/" . $valor_total_format . "&-/" . $total_venda . "&-/" . $total_venda_format . "&-/" . $troco . "&-/" . $troco_format;

echo $dados;
