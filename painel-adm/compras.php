<?php

@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

$pag = 'compras';
?>

<div class="mt-4" style="margin-right: 25px">


    <?php

    $query_tab = $pdo->query("SELECT * FROM compras order by id desc"); //nome é o campo da tabela

    $res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_tab = @count($res_tab);

    if ($total_reg_tab > 0) {

    ?>

        <small>
            <table id="usuarios" class="table table-hover my-4" style="width:100%">
                <thead>
                    <tr>
                        <th>Pago</th>
                        <th>Total</th>
                        <th>Data</th>
                        <th>Comprador</th>
                        <th>Fornecedor</th>
                        <th>Tel. Fornecedor</th>
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

                        //BUSCAR OS DADOS DO FORNECEDOR
                        $id_fornecedor = $res_tab[$i]['fornecedor'];
                        $query_fornecedor = $pdo->query("SELECT * FROM fornecedores WHERE id = '$id_fornecedor'");
                        $res_fornecedor = $query_fornecedor->fetchAll(PDO::FETCH_ASSOC);
                        $total_reg_fornecedor = @count($res_fornecedor);

                        if ($total_reg_fornecedor > 0) {
                            $nome_fornecedor = $res_fornecedor[0]['nome'];
                            $telefone_fornecedor = $res_fornecedor[0]['telefone'];
                        }

                        if ($res_tab[$i]['pago'] == 'Sim') {
                            $classe = 'text-sucess';
                        } else {
                            $classe = 'text-danger';
                        }

                    ?>

                        <tr>
                            <td>

                                <i class="bi bi-square-fill <?php echo $classe; ?>"></i>

                            </td>
                            <td>
                                R$ <?php echo number_format($res_tab[$i]['total'], 2, ',', '.'); ?>
                            </td>

                            <td>
                                <?php echo implode('/', array_reverse(explode('-', $data))); 
                                /*substitui '-' por '/', e array_reverse inverte a ordem da data,
                                do padrão americano com ANO, MÊS E DIA, para DIA, MÊS E ANO
                                essa conversão é feita somente para listagem, e não para salvar
                                no banco de dados
                                */
                                ?>
                            </td>

                            <td>
                                <?php echo $nome_usuario; ?>
                            </td>

                            <td>
                                <?php echo $nome_fornecedor; ?>
                            </td>


                            <td>
                                <?php echo $telefone_fornecedor; ?>
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

<!-- SCRIPT PARA DATATABLE -->
<script>
    $(document).ready(function() {
        $('#usuarios').DataTable({
            'ordering': false
        });
    });
</script>