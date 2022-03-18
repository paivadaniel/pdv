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
                        <th>Excluir</th>
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

                            <td>

                                <?php
                                if ($res_tab[$i]['pago'] != 'Sim') {
                                ?>
                                    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=deletar&id=<?php echo $res_tab[$i]['id']; ?>" type="button" title="Excluir Registro" style="text-decoration: none">
                                        <i class="bi bi-archive text-danger me-2"></i>
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

<!-- MODAL PARA DELEÇÃO DOS DADOS -->

<div class="modal fade" tabindex="-1" id="modalDeletar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deletar Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="form-excluir">

                <div class="modal-body">

                    <p>Deseja realmente excluir o registro?</p>

                    <small>
                        <div align="center" class="mb-3" id="mensagem-excluir">
                        </div>
                    </small>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                        <button type="submit" class="btn btn-danger" name="btn-excluir" id="btn-excluir">Excluir</button>

                        <input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">

                    </div>
            </form>

        </div>
    </div>
</div>

<!--SCRIPT QUE CHAMA A MODAL DELETAR -->
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

                if (mensagem.trim() == "Excluído com Sucesso!") {

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