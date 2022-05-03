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


                                    <?php if ($res_tab[$i]['status'] == 'Concluída') { ?>

                                        <a href="index.php?pagina=<?php echo $pag; ?>&funcao=deletar&id=<?php echo $res_tab[$i]['id']; ?>" type="button" title="Cancelar Venda">
                                            <i class="bi bi-archive text-danger"></i>
                                        </a>

                                    <?php } ?>

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

<!-- MODAL PARA DELEÇÃO DOS DADOS -->

<div class="modal fade" tabindex="-1" id="modalDeletar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancelar Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="form-excluir">

                <div class="modal-body">

                    <p>Deseja realmente cancelar esta venda?</p>

                    <small>
                        <div align="center" class="mb-3" id="mensagem-excluir">
                        </div>
                    </small>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                    <button type="submit" class="btn btn-danger" name="btn-excluir" id="btn-excluir">Cancelar Venda</button>

                    <input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">

                </div>
            </form>

        </div>
    </div>
</div>



</html>

<?php

if (@$_GET['funcao'] == 'deletar') {
?>

    <script>
        var myModal = new bootstrap.Modal(document.getElementById('modalDeletar'), {})

        myModal.show();
    </script>

<?php

}
?>

<!--AJAX PARA DELEÇÃO DOS DADOS -->
<script type="text/javascript">
    $("#form-excluir").submit(function() {
        var pag = "<?= $pag ?>"; //não sei porque não colocou < ?php $pag ?>, ou seja trocou php por =
        event.preventDefault();
        /*
        toda vez que submetemos uma página por um formulário, ela atualiza,
        o event.preventDefault() evita que a página seja atualizada,
        essa é a principal função do ajax
        */
        var formData = new FormData(this);

        $.ajax({
            url: pag + "/excluir.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem-excluir').removeClass()

                if (mensagem.trim() == "Venda Cancelada com Sucesso!") {

                    $('#mensagem-excluir').addClass('text-success');

                    $('#btn-fechar').click();
                    window.location = "index.php?pagina=" + pag;



                } else { //se não devolver "Excluído com Sucesso!", ou seja, se der errado a deleção

                    $('#mensagem-excluir').addClass('text-danger')
                }

                $('#mensagem-excluir').text(mensagem)

            },
            cache: false,
            contentType: false,
            processData: false,

        });
    });
</script>

<!-- SCRIPT PARA DATATABLE -->
<script>
    $(document).ready(function() {
        $('#usuarios').DataTable({
            'ordering': false
        });
    });
</script>