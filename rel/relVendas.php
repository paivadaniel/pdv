<?php
require_once("../conexao.php");

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')));
//strtoupper() deixa todo o conteúdo em maíusculo, em caixa alta


$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$status = $_GET['status'];

$status_like = '%' . $status . '%';
/*busca aproximada, não preciso digitar o nome todo, isso pois inicialmente quando
selecionar TODOS e não passar nada lá, ele buscará por qualquer status 
*/
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

if ($status == 'Concluída') {
    $status_serv = 'Concluídas ';
} else if ($status == 'Cancelada') {
    $status_serv = 'Canceladas';
} else {
    $status_serv = ''; //para o caso de mostrar tanto as pagas quanto as não pagas
}


if ($dataInicial != $dataFinal) {
    $apuracao = $dataInicialF . ' até ' . $dataFinalF; //para datas inicial e final diferentes
} else {
    $apuracao = $dataInicialF; //para mesma data inicial e final
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Relatório de Vendas</title>

    <link rel="shortcut icon" href="../img/favicon.ico" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- bootstrap não vai funcionar para relatórios em pdf, apenas para relatórios em html -->

    <style>
        @page {
            margin: 0px;

        }

        .footer {
            margin-top: 20px;
            width: 100%;
            background-color: #ebebeb;
            padding: 10px;
            position: absolute;
            bottom: 0;
        }

        .cabecalho {
            background-color: #ebebeb;
            padding: 10px;
            margin-bottom: 30px;
            width: 100%;
            height: 100px;
        }

        .titulo {
            margin: 0;
            font-size: 28px;
            font-family: Arial, Helvetica, sans-serif;
            color: #6e6d6d;

        }

        .subtitulo {
            margin: 0;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            color: #6e6d6d;
        }

        .areaTotais {
            border: 0.5px solid #bcbcbc;
            padding: 15px;
            border-radius: 5px;
            margin-right: 25px;
            margin-left: 25px;
            position: absolute;
            right: 20;
        }

        .areaTotal {
            border: 0.5px solid #bcbcbc;
            padding: 15px;
            border-radius: 5px;
            margin-right: 25px;
            margin-left: 25px;
            background-color: #f9f9f9;
            margin-top: 2px;
        }

        .pgto {
            margin: 1px;
        }

        .fonte13 {
            font-size: 13px;
        }

        .esquerda {
            display: inline;
            width: 50%;
            float: left;
        }

        .direita {
            display: inline;
            width: 50%;
            float: right;
        }

        .table {
            padding: 15px;
            font-family: Verdana, sans-serif;
            margin-top: 20px;
        }

        .texto-tabela {
            font-size: 12px;
        }


        .esquerda_float {

            margin-bottom: 10px;
            float: left;
            display: inline;
        }


        .titulos {
            margin-top: 10px;
        }

        .image {
            margin-top: -10px;
        }

        .margem-direita {
            margin-right: 80px;
        }

        .margem-direita50 {
            margin-right: 50px;
        }

        hr {
            margin: 8px;
            padding: 1px;
        }


        .titulorel {
            margin: 0;
            font-size: 28px;
            font-family: Arial, Helvetica, sans-serif;
            color: #6e6d6d;

        }

        .margem-superior {
            margin-top: 30px;
        }

        .areaSubtituloCabecalho {
            margin-top: 15px;
            margin-bottom: 15px;
        }
    </style>


</head>

<body>

    <?php if ($cabecalho_img_rel == 'Sim') { ?>

        <div class="img-cabecalho">
            <img src="<?php echo $url_sistema ?>img/topo-relatorio.jpg" width="100%">

        </div>

    <?php } else { ?>

        <div class="cabecalho">

            <div class="row titulos">
                <div class="col-sm-2 esquerda_float image">
                    <img src="<?php echo $url_sistema ?>img/logo.jpg" width="92px">
                </div>
                <div class="col-sm-10 esquerda_float">
                    <h2 class="titulo"><b><?php echo strtoupper($nome_sistema) ?></b></h2>

                    <div class="areaSubtituloCabecalho">

                        <h6 class="subtitulo"><?php echo $endereco_sistema . ' Tel: ' . $telefone_sistema ?></h6>

                        <h6 class="subtitulo"><?php echo $data_hoje ?></h6>
                    </div>

                </div>
            </div>

        <?php } ?>

        </div>

        <div class="container">

            <div align="center" class="">
                <span class="titulorel">Relatório de Vendas <?php echo $status_serv ?></span>
            </div>
        </div>


        <hr>

        <small>
            <table class='table' width='100%' cellspacing='0' cellpadding='3'>
                <tr bgcolor='#f9f9f9'>
                    <th>Status</th>
                    <th>Valor</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Operador</th>
                    <th>Forma de Pagamento</th>
                </tr>
                <?php
                $saldo = 0;

                $query = $pdo->query("SELECT * FROM vendas WHERE data >= '$dataInicial' AND data <= '$dataFinal' and status LIKE '$status_like' ORDER BY id desc");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);

                for ($i = 0; $i < @count($res); $i++) {
                    foreach ($res[$i] as $key => $value) {
                    }

                    $valor = $res[$i]['valor'];
                    $valor_format = number_format($valor, 2, ',', '.'); //"F" vem de formatado

                    $data = $res[$i]['data'];
                    $data = implode('/', array_reverse(explode('-', $data)));

                    $hora = $res[$i]['hora'];
                    $status = $res[$i]['status'];

                    //nome do operador
                    $id_operador = $res[$i]['operador']; //id do usuário

                    $query2 = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_operador'");
                    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                    $nome_operador = $res2[0]['nome'];

                    //nome da forma de pagamento
                    $codigo_forma_pgto = $res[$i]['forma_pgto'];

                    $query3 = $pdo->query("SELECT * FROM forma_pgtos WHERE codigo = '$codigo_forma_pgto'");
                    $res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
                    $nome_forma_pgto = $res3[0]['nome'];

                    $saldo += $valor;
                    $saldo_format = number_format($saldo, 2, ',', '.'); //"F" vem de formatado

                    if ($status == 'Concluída') {
                        $foto = 'verde.jpg';
                    } else {
                        $foto = 'vermelho.jpg';
                    }

                ?>

                    <tr>

                        <td><img src="<?php echo $url_sistema ?>img/<?php echo $foto ?>" alt="Status" width="10px"> <?php echo $status ?> </td>
                        <td><?php echo $valor_format ?> </td>
                        <td><?php echo $data ?> </td>
                        <td><?php echo $hora ?> </td>
                        <td><?php echo $nome_operador ?> </td>
                        <td><?php echo $nome_forma_pgto ?> </td>

                    </tr>
                <?php } ?>



            </table>
        </small>

        <hr>


        <div class="row" align="left">
            <div class="col-sm-8 esquerda">
                <span class=""> <b> Período da Apuração: </b> </span>
                <span class=""> <?php echo $apuracao ?> </span>
            </div>

            <div class="col-sm-4 direita" align="right">
                <span class=""> <b> Total: R$ <?php echo $saldo_format ?> </b> </span>
            </div>
        </div>

        <hr>



        <div class="footer">
            <p style="font-size:14px" align="center"><?php echo $rodape_relatorios ?></p>
        </div>




</body>

</html>