<?php
$pag = 'contas_pagar';
@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

?>

<div class="mt-4" style="margin-right:25px">
    <?php
    $query = $pdo->query("SELECT * from contas_pagar where vencimento < curDate() and pago != 'Sim' order by vencimento asc"); //podia ser pago = 'Não", porém, para evitar problema com caracteres, usei pago != 'Sim'
    
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
    ?>

        <small>
            <table id="usuarios" class="table table-hover my-4" style="width:100%">
                <thead>
                    <tr>
                        <th>Pago</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Usuário</th>
                        <th>Vencimento</th>
                        <th>Arquivo</th>
                        <th>Dar Baixa</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        } //fechamento do foreach

                        $id_usu = $res[$i]['usuario'];

                        //encontra o usuário responsável pela compra
                        $query2 = $pdo->query("SELECT * from usuarios where id = '$id_usu'");
                        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                        $nome_usu = $res2[0]['nome'];

                        $vencimento = $res[$i]['vencimento'];

                        if ($res[$i]['pago'] == 'Sim') {
                            $classe = 'text-success';
                        } else {
                            $classe = 'text-danger';
                        }


                        $extensao = strchr($res[$i]['arquivo'], '.'); //separa a partir do ponto para frente, por exemplo, "teste.pdf" e "foto.jpg" vai guardar ".pdf" e ".jpg"
                        if ($extensao == '.pdf') {
                            $arquivo_pasta = 'pdf.png';
                        } else {
                            $arquivo_pasta = $res[$i]['arquivo'];
                        }

                    ?>

                        <tr>
                            <td>
                                <i class="bi bi-square-fill <?php echo $classe; ?>"></i>
                            </td>

                            <td>
                                <?php echo $res[$i]['descricao']; ?>
                            </td>

                            <td>
                                R$ <?php echo number_format($res[$i]['valor'], 2, ',', '.'); ?>
                            </td>

                            <td><?php echo $nome_usu ?></td>

                            <td>
                                <?php echo implode('/', array_reverse(explode('-', $vencimento)));
                                ?>
                            </td>


                            <td>
                                <a href="../img/<?php echo $pag ?>/<?php echo $res[$i]['arquivo'] ?>" type="button" title="Ver Arquivo" style="text-decoration: none" target="_blank">
                                    <img src="../img/<?php echo $pag ?>/<?php echo $arquivo_pasta ?>" width="50px">
                                </a>
                            </td>
                            <td>
                   
                                    <a href="index.php?pagina=contas_pagar_vencidas&funcao=baixar&id=<?php echo $res[$i]['id']; ?>" title="Baixar Registro">
                                        <i class="bi bi-check-square-fill text-success" style="text-decoration: none"></i>
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

<!-- MODAL PARA DAR BAIXA NA CONTA PAGA -->

<div class="modal fade" tabindex="-1" id="modalBaixar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Baixar Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="form-baixar">

                <div class="modal-body">

                    <p>Deseja realmente confirmar o pagamento dessa conta?</p>

                    <small>
                        <div align="center" class="mb-3" id="mensagem-baixar">
                        </div>
                    </small>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-baixar">Fechar</button>
                        <button type="submit" class="btn btn-success" name="btn-baixar" id="btn-baixar">Salvar</button>

                        <input type="hidden" name="id" value="<?php echo @$_GET['id'] ?>">

                    </div>
            </form>

        </div>
    </div>
</div>

<?php

if (@$_GET['funcao'] == 'baixar') {
?>

    <script type="text/javascript">
        var myModal = new bootstrap.Modal(document.getElementById('modalBaixar'), {})

        myModal.show();
    </script>

<?php

}
?>

<!--AJAX PARA DAR BAIXA EM CONTA PAGA -->
<script type="text/javascript">
    $("#form-baixar").submit(function() {
        var pag = "<?= $pag ?>";
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: pag + "/baixar.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem-baixar').removeClass()

                if (mensagem.trim() == "Baixado com Sucesso!") {

                    $('#mensagem-baixar').addClass('text-success');

                    $('#btn-fechar-baixar').click();
                    window.location = "index.php?pagina=contas_pagar_vencidas";



                } else { //se não devolver "Baixado com Sucesso!", ou seja, se der errado o baixar

                    $('#mensagem-baixar').addClass('text-danger')
                }

                $('#mensagem-baixar').text(mensagem)

            },
            cache: false,
            contentType: false,
            processData: false,

        });
    });
</script>

<!-- SCRIPT PARA DATATABLE -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#usuarios').DataTable({
            'ordering': false
        });
    });
</script>