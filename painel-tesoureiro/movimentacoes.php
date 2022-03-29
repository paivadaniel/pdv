<?php

@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

$pag = 'movimentacoes';
?>

<div class="mt-4" style="margin-right: 25px">

    <?php

    $query_tab = $pdo->query("SELECT * FROM movimentacoes order by id desc"); //nome é o campo da tabela
    $res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_tab = @count($res_tab);

    if ($total_reg_tab > 0) {

    ?>

        <small>
            <table id="usuarios" class="table table-hover my-4" style="width:100%">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Usuário</th>
                        <th>Data</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    for ($i = 0; $i < $total_reg_tab; $i++) {
                        foreach ($res_tab[$i] as $key => $value) {
                        } //fechamento do foreach


                        //SALVA DATA DA COMPRA  
                        $data = $res_tab[$i]['data'];

                        //BUSCAR OS DADOS DO USUÁRIO
                        $id_usuario = $res_tab[$i]['usuario'];
                        $query_usuario = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_usuario'");
                        $res_usuario = $query_usuario->fetchAll(PDO::FETCH_ASSOC);
                        $total_reg_usuario = @count($res_usuario);

                        if ($total_reg_usuario > 0) {
                            $nome_usuario = $res_usuario[0]['nome'];
                        }

                        if ($res_tab[$i]['tipo'] == 'Entrada') {
                            $classe = 'text-success';
                        } else if ($res_tab[$i]['tipo'] == 'Saída') {
                            $classe = 'text-danger';
                        }
                

                    ?>

                        <tr>
                            <td>
                                <i class="bi bi-square-fill <?php echo $classe; ?>"></i>
                                <span class="d-none"><?php echo $res_tab[$i]['tipo'] ?></span>
                                <!-- d-none é uma classe do bootstrap para o CSS display:none 
                                apenas não mostra o item na tela, mas ele continua existindo
                                isso é útil para usar em filtros com itens escondidos, nesse caso podemos,
                                por exemplo, buscar "entrada"  e "saída" no filtro de busca
                                -->
                            </td>
                            <td>
                                <?php echo $res_tab[$i]['descricao'] ?>
                            </td>

                            <td>
                                R$ <?php echo number_format($res_tab[$i]['valor'], 2, ',', '.') ?>
                            </td>

                            <td>
                                <?php echo $nome_usuario ?>
                            </td>

                            <td>
                                <?php echo implode('/', array_reverse(explode('-', $data))) ?>
                                <!--substitui '-' por '/', e array_reverse inverte a ordem da data,
                                do padrão americano com ANO, MÊS E DIA, para DIA, MÊS E ANO
                                essa conversão é feita somente para listagem, e não para salvar
                                no banco de dados
                                -->

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

<?php

$entradas = 0;
$saidas = 0;
$saldo = 0;

$query2 = $pdo->query("SELECT * FROM movimentacoes where data = curDate() order by id desc"); //nome é o campo da tabela
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

if ($total_reg2 > 0) {

    for ($i = 0; $i < $total_reg2; $i++) {
        foreach ($res2[$i] as $key => $value) {
        } //fechamento do foreach


        if ($res2[$i]['tipo'] == 'Entrada') {
            $entradas += $res2[$i]['valor'];
        } else if ($res2[$i]['tipo'] == 'Saída') {
            $saidas += $res2[$i]['valor'];
        }

        $saldo = $entradas - $saidas;

        if ($saldo > 0) {
            $classeSaldo = 'text-success';
        } else {
            $classeSaldo = 'text-danger';
        }
    }
}

?>
<small>
    <div class="row bg-light mt-4 py-2">
        <!-- o padding é para não ficar muito colocado no topo e base do bg-light -->
        <div class="col-md-8">
            <span><b>Entradas do dia:</b> <span class="text-success"> R$<?php echo number_format($entradas, 2, ',', '.') ?> </span></span>
            <span class="mx-4"><b>Saídas do dia:</b> <span class="text-danger"> R$<?php echo number_format($saidas, 2, ',', '.') ?> </span></span>
        </div>
        <div align="right" class="col-md-4">

            <!-- abaixo ele atribui uma variável php para uma classe -->
            <span class="mx-4"><b>Saldo do dia:</b> <span class="<?php echo $classeSaldo ?>"> R$<?php echo number_format($saldo, 2, ',', '.') ?> </span></span>


        </div>
    </div>
</small>

<!-- SCRIPT PARA DATATABLE -->
<script>
    $(document).ready(function() {
        $('#usuarios').DataTable({
            'ordering': false
        });
    });
</script>