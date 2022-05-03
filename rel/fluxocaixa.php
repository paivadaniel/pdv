<?php
require_once("../conexao.php");

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')));

$id = $_GET['id'];

$query_tab = $pdo->query("SELECT * FROM caixa WHERE id = '$id'");
$res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
$total_reg_tab = @count($res_tab);

//não necessita de for para armazenar as variáveis a seguir, pois são para uma única abertura de caixa

$valor_vendido = "R$ " . number_format($res_tab[0]['valor_vendido'], 2, ',', '.');
$valor_quebra = "R$ " . number_format($res_tab[0]['valor_quebra'], 2, ',', '.');

//abertura
$data_abertura = implode('/', array_reverse(explode('-', $res_tab[0]['data_ab'])));
$hora_abertura = $res_tab[0]['hora_ab'];
$id_gerente_abertura = $res_tab[0]['gerente_ab'];
$valor_abertura = "R$ " . number_format($res_tab[0]['valor_ab'], 2, ',', '.');

//fechamento
@$data_fechamento = implode('/', array_reverse(explode('-', $res_tab[0]['data_fec'])));
@$hora_fechamento = $res_tab[0]['hora_fec'];
@$id_gerente_fechamento = $res_tab[0]['gerente_fec'];
@$valor_fechamento = "R$ " . number_format($res_tab[0]['valor_fec'], 2, ',', '.');

//status do caixa
$status = $res_tab[0]['status'];

//número do caixa
$id_caixa = $res_tab[0]['caixa'];

$query2 = $pdo->query("SELECT * FROM caixas WHERE id = '$id_caixa'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_caixa = $res2[0]['nome'];

//nome do operador
$id_operador = $res_tab[0]['operador'];

$query3 = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_operador'");
$res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
$nome_operador = $res3[0]['nome'];

//nome do gerente abertura

$query4 = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_gerente_abertura'");
$res4 = $query4->fetchAll(PDO::FETCH_ASSOC);
$nome_gerente_abertura = $res4[0]['nome'];

//nome do gerente fechamento

$query5 = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_gerente_fechamento'");
$res5 = $query5->fetchAll(PDO::FETCH_ASSOC);
@$nome_gerente_fechamento = $res5[0]['nome'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Relatório de Fluxo de Caixa</title>

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
                    </div>

                </div>
            </div>


        </div>

    <?php } ?>

    <div class="container">

        <div align="center" class="">
            <span class="titulorel">Relatório de Fluxo de Caixa - <?php echo $status ?> </span>
        </div>


        <hr>

        <small>
            <div class="row" align="left">
                <div class="col-sm-6 esquerda">
                    <span class=""> <b> Caixa: </b> </span>
                    <span class=""> <?php echo $nome_caixa ?> </span>

                    <span class=""> <b> Operador: </b> </span>
                    <span class=""> <?php echo $nome_operador ?> </span>
                </div>

                <div class="col-sm-6 direita" align="right">
                    <span class=""> <b> Quebra: </b> </span>
                    <span class=""> <?php echo $valor_quebra ?> </span>

                    <span class=""><b>Valor vendido: </b><?php echo $valor_vendido ?></span>
                </div>
            </div>
        </small>

        <hr>


        <small>
            <div class="row" align="left">
                <div class="col-sm-6 esquerda">
                    <span class=""> <b> Data Abertura: </b> </span>
                    <span class=""> <?php echo $data_abertura ?> </span>

                    <span class=""> <b> Hora Abertura: </b> </span>
                    <span class=""> <?php echo $hora_abertura ?> </span>

                </div>

                <div class="col-sm-6 direita" align="right">
                    <span class=""> <b> Gerente Abertura: </b> </span>
                    <span class=""> <?php echo $nome_gerente_abertura ?> </span>

                    <span class=""><b>Valor Abertura: </b><?php echo $valor_abertura ?></span>
                </div>
            </div>
        </small>


        <hr>

        <small>
            <div class="row" align="left">
                <div class="col-sm-6 esquerda">
                    <span class=""> <b> Data Fechamento: </b> </span>
                    <span class=""> <?php echo @$data_fechamento ?> </span>

                    <span class=""> <b> Hora Fechamento: </b> </span>
                    <span class=""> <?php echo @$hora_fechamento ?> </span>

                </div>

                <div class="col-sm-6 direita" align="right">
                    <span class=""> <b> Gerente Fechamento: </b> </span>
                    <span class=""> <?php echo @$nome_gerente_fechamento ?> </span>

                    <span class=""><b>Valor Fechamento: </b><?php echo @$valor_fechamento ?></span>
                </div>
            </div>
        </small>


        <hr>

        <small>
            <table class='table' width='100%' cellspacing='0' cellpadding='3'>
                <tr bgcolor='#f9f9f9'>

                    <th>Status</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Valor</th>
                    <th>Forma de Pagamento</th>


                </tr>

                <?php

                //listar as vendas por abertura de caixa
                $query_tab = $pdo->query("SELECT * FROM vendas WHERE abertura = '$id' ORDER BY id ASC"); //é o id da tabela caixa, que na tabela vendas é passado como abertura
                $res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
                $total_reg_tab = @count($res_tab);


                for ($i = 0; $i < $total_reg_tab; $i++) {
                    foreach ($res_tab[$i] as $key => $value) {
                    } //fechamento do foreach

                    $valor = "R$ " . number_format($res_tab[$i]['valor'], 2, ',', '.');
                    $valor_recebido = "R$ " . number_format($res_tab[$i]['valor_recebido'], 2, ',', '.');
                    $troco = "R$ " . number_format($res_tab[$i]['troco'], 2, ',', '.');
                    $data = implode('/', array_reverse(explode('-', $res_tab[$i]['data'])));
                    $hora = $res_tab[$i]['hora'];

                    //status

                    $status = $res_tab[$i]['status'];
                    if ($status == 'Concluída') {
                        $foto = 'verde.jpg';
                    } else {
                        $foto = 'vermelho.jpg';
                    }

                    //nome da forma de pagamento

                    $codigo_forma_pgto = $res_tab[$i]['forma_pgto'];

                    $query2 = $pdo->query("SELECT * FROM forma_pgtos WHERE codigo = '$codigo_forma_pgto'");
                    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                    $nome_forma_pgto = $res2[0]['nome'];


                ?>

                    <tr>
                        <td> <img src="<?php echo $url_sistema ?>img/<?php echo $foto ?>" alt="Status da Venda" width="13px"></td>
                        <td><?php echo $data ?></td>
                        <td><?php echo $hora ?></td>
                        <td><?php echo $valor ?></td>
                        <td><?php echo $nome_forma_pgto ?></td>

                    </tr>

                <?php
                }

                ?>


            </table>
        </small>
        <hr>
    </div>


    <div class="footer">
        <p style="font-size:14px" align="center"><?php echo $rodape_relatorios ?></p>
    </div>




</body>

</html>