<?php

@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

$pag = 'aberturas';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Aberturas de Caixas</title>

</head>

<body>

    <div class="mt-4" style="margin-right: 25px">


        <?php

        $query_tab = $pdo->query("SELECT * FROM caixa order by id desc");
        $res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
        $total_reg_tab = @count($res_tab);

        if ($total_reg_tab > 0) {

        ?>

            <small>
                <table id="vendas" class="table table-hover my-4" style="width:100%">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Data Abertura</th>
                            <th>Valor Vendido</th>
                            <th>Valor de Quebra</th>
                            <th>Caixa</th>
                            <th>Operador</th>

                            <th>Ações</th>


                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        for ($i = 0; $i < $total_reg_tab; $i++) {
                            foreach ($res_tab[$i] as $key => $value) {
                            } //fechamento do foreach

                            $data_abertura = implode('/', array_reverse(explode('-', $res_tab[$i]['data_ab'])));
                            $valor_vendido = "R$ " . number_format($res_tab[$i]['valor_vendido'], 2, ',', '.');
                            $valor_quebra = "R$ " . number_format($res_tab[$i]['valor_quebra'], 2, ',', '.');

                            //número do caixa
                            $id_caixa = $res_tab[$i]['caixa'];

                            $query2 = $pdo->query("SELECT * FROM caixas WHERE id = '$id_caixa'");
                            $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                            $nome_caixa = $res2[0]['nome'];

                            //nome do operador
                            $id_operador = $res_tab[$i]['operador'];

                            $query3 = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_operador'");
                            $res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
                            $nome_operador = $res3[0]['nome'];

                            if ($res_tab[$i]['status'] == 'Aberto') {
                                $classe = 'text-success';
                            } else {
                                $classe = 'text-danger';
                            }

                        ?>

                            <tr>
                                <td>
                                <i class="bi bi-square-fill <?php echo $classe; ?>"> </i> <?php echo $res_tab[$i]['status'] ?>

                                </td>
                                <td><?php echo $data_abertura; ?></td>
                                <td><?php echo $valor_vendido; ?></td>
                                <td><?php echo $valor_quebra; ?></td>
                                <td><?php echo $nome_caixa; ?></td>
                                <td><?php echo $nome_operador; ?></td>

                                <td>

                                    <a href="../rel/fluxocaixa_class.php?id=<?php echo $res_tab[$i]['id'] ?>" type="button" title="Fluxo de Caixa" style="text-decoration: none" target="_blank">
                                        <i class="bi bi-clipboard-check text-primary me-2"></i> <!-- eu estava chamando ?id=$id_caixa, porém, não é o id do caixa, e sim o id da tabela caixa-->
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