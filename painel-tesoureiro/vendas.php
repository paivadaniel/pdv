<?php

@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

$pag = 'vendas';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Vendas</title>

</head>

<body>

    <div class="mt-4" style="margin-right: 25px">


        <?php

        $query_tab = $pdo->query("SELECT * FROM vendas order by id desc");
        $res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
        $total_reg_tab = @count($res_tab);

        if ($total_reg_tab > 0) {

        ?>

            <small>
                <table id="vendas" class="table table-hover my-4" style="width:100%">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Valor</th>
                            <th>Operador</th>
                            <th>Valor Recebido
                            </th>
                            <th>Desconto</th>
                            <th>Troco</th>
                            <th>Forma de Pagamento
                            </th>
                            <th>Abertura
                            </th>
                            <th>Ações</th>


                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        for ($i = 0; $i < $total_reg_tab; $i++) {
                            foreach ($res_tab[$i] as $key => $value) {
                            } //fechamento do foreach

                            $valor = "R$ " . number_format($res_tab[$i]['valor'], 2, ',', '.');
                            $valor_recebido = "R$ " . number_format($res_tab[$i]['valor_recebido'], 2, ',', '.');
                            $troco = "R$ " . number_format($res_tab[$i]['troco'], 2, ',', '.');
                            $data = implode('/', array_reverse(explode('-', $res_tab[$i]['data'])));

                            //id da venda
                            $id_venda = $res_tab[$i]['id'];

                            //nome da forma de pagamento

                            $codigo_forma_pgto = $res_tab[$i]['forma_pgto'];

                            $query2 = $pdo->query("SELECT * FROM forma_pgtos WHERE codigo = '$codigo_forma_pgto'");
                            $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                            $nome_forma_pgto = $res2[0]['nome'];

                            //nome do operador

                            $id_operador = $res_tab[$i]['operador'];

                            $query3 = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_operador'");
                            $res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
                            $nome_operador = $res3[0]['nome'];

                            if ($res_tab[$i]['status'] == 'Concluída') {
                                $classe = 'text-success';
                            } else {
                                $classe = 'text-danger';
                            }

                        ?>

                            <tr>
                                <td>
                                    <i class="bi bi-square-fill <?php echo $classe; ?>"></i>

                                </td>
                                <td><?php echo $data; ?></td>
                                <td><?php echo $res_tab[$i]['hora']; ?></td>
                                <td><?php echo $valor ?></td>
                                <td><?php echo $nome_operador ?></td>
                                <td><?php echo $valor_recebido; ?></td>
                                <td><?php echo $res_tab[$i]['desconto']; ?></td>
                                <td><?php echo $troco; ?></td>
                                <td><?php echo $nome_forma_pgto; ?></td>
                                <td><?php echo $res_tab[$i]['abertura']; ?></td>
                                <td>

                                    <a href="../painel-operador/comprovante_class.php?id=<?php echo $id_venda; ?>" type="button" title="Gerar Comprovante" style="text-decoration: none" target="_blank">
                                        <i class="bi bi-clipboard-check text-primary me-2"></i>
                                    </a>

                                </td>
                            </tr>

                        <?php } //fechamento do for 
                        ?>

                    </tbody>

                </table>

            </small>
        <?php } else { //fechamento do if
            echo "Não existem dados para serem exibidos!";
        } ?>

    </div>

</body>

<!-- SCRIPT PARA DATATABLE -->
<script>
    $(document).ready(function() {
        $('#usuarios').DataTable({
            'ordering': false
        });
    });
</script>