<?php
$pag = 'estoque';
@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

?>

<div class="mt-4" style="margin-right: 25px">

    <?php

    $query = $pdo->query("SELECT * from produtos WHERE estoque < $estoque_minimo order by id desc");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);

    if ($total_reg > 0) {

    ?>

        <small>
            <table id="usuarios" class="table table-hover my-4" style="width:100%">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Estoque</th>
                        <th>Valor Compra</th>
                        <th>Valor Venda</th>
                        <th>Fornecedor</th>
                        <th>Foto</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        } //fechamento do foreach

                        $id_cat = $res[$i]['categoria']; //o campo categoria na tabela produtos registra o id da categoria, semelhante ao que que faz o campo fornecedores dessa mesma tabela, que registra o id do fornecedor

                        $query_2 = $pdo->query("SELECT * from categorias WHERE id = '$id_cat'"); //procura na tabela categoria
                        $res_2 = $query_2->fetchAll(PDO::FETCH_ASSOC);
                        $nome_cat = $res_2[0]['nome']; //nome da categoria, só vai ter uma linha, por isso usou posição [0]


                        $id_fornecedor = $res[$i]['fornecedor'];
                        //BUSCAR OS DADOS DO FORNECEDOR
                        $query_f = $pdo->query("SELECT * from fornecedores WHERE id = '$id_fornecedor'");
                        $res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
                        $total_reg_f = @count($res_f);

                        if ($total_reg_f > 0) {
                            $nome_forn = $res_f[0]['nome'];
                            $tel_forn = $res_f[0]['telefone'];
                        } else {
                            $nome_forn = '';
                            $tel_forn = '';
                        }

                    ?>

                        <tr>
                            <td><?php echo $res[$i]['nome'] ?></td>
                            <td><?php echo $res[$i]['codigo'] ?></td>
                            <td><?php echo $res[$i]['estoque'] ?></td>
                            <td>R$ <?php echo number_format($res[$i]['valor_compra'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($res[$i]['valor_venda'], 2, ',', '.'); ?></td>
                            <td><?php echo @$nome_fornecedor; ?></td>

                            <!-- FOTO -->

                            <td>
                                <img src="../img/produtos/<?php echo $res[$i]['foto'] ?>" width="50px">
                            </td>

                            <!-- AÇÕES -->
                            <td>

                                <a href="#" onclick="mostrarDados('<?php echo $res[$i]['nome'] ?>', '<?php echo $res[$i]['descricao'] ?>', '<?php echo $res[$i]['foto'] ?>', '<?php echo $nome_cat ?>', '<?php echo $nome_forn ?>', '<?php echo $tel_forn ?>')" title="Ver Descriçao" style="text-decoration: none">
                                    <i class="bi bi-card-text text-dark me-2"></i>
                                </a>


                                <a href="#" onclick="comprarProdutos('<?php echo $res[$i]['id']; ?>')" title="Fazer Pedido" style="text-decoration:none">
                                    <i class="bi bi-bag text-success me-2"></i>
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

<!-- MODAL PARA MOSTRAR OS DADOS -->

<div class="modal fade" tabindex="-1" id="modalDados">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="nome-registro"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body mb-4">

                <b>Categoria: </b>
                <span id="categoria-registro"></span>
                <hr>

                <div id="div-forn">

                    <b>Fornecedor: </b>
                    <span class="mr-4" id="nome-forn-registro">
                    </span>
                    <hr>
                    <b>Telefone: </b>
                    <span class="mr-4" id="tel-forn-registro">

                    </span>
                    <hr>

                </div>

                <b>Descrição: </b>
                <span id="descricao-registro"></span>
                <hr>

                <img id="imagem-registro" src="" class="mt-4" width="200px">

            </div>
        </div>
    </div>

</div>

<!-- MODAL PARA COMPRAR PRODUTOS -->

<div class="modal fade" tabindex="-1" id="modalComprar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fazer Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="form-comprar">

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="categoria">Fornecedor</label>
                        <select class="form-select mt-1" aria-label="Default select example" name="fornecedor">

                            <?php

                            $query_c = $pdo->query("SELECT * FROM fornecedores ORDER BY nome asc");
                            $res_c = $query_c->fetchAll(PDO::FETCH_ASSOC);
                            $total_reg_c = @count($res_c);

                            if ($total_reg_c > 0) {

                                for ($i = 0; $i < $total_reg_c; $i++) {
                                    foreach ($res_c[$i] as $key => $value) {
                                    }

                            ?>

                                    <option value="<?php echo $res_c[$i]['id'] ?>"><?php echo $res_c[$i]['nome'] ?></option>

                            <?php }
                            } else { //fechamento do if seguido do fechamento do for 
                                echo '<option value="">Cadastre um Fornecedor</option>'; //caso não haja fornecedor cadastrado
                            }
                            ?>

                        </select>

                    </div>

                    <div class="row">
                        <div class="col-6">


                            <div class="mb-3">
                                <label for="valor_compra" class="form-label">Valor unitário</label>
                                <input type="text" class="form-control" id="valor_compra" name="valor_compra" placeholder="Valor unitário" required>
                                <!-- alterei de type="number" para "text" para poder receber valores quebrados, como 14,50, que tem vírgula -->
                            </div>


                        </div>
                        <div class="col-6">

                            <div class="mb-3">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="Quantidade" required>
                            </div>

                        </div>
                    </div>


                    <small>
                        <div align="center" class="mb-3" id="mensagem-comprar">
                        </div>
                    </small>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-comprar">Fechar</button>
                        <button type="submit" class="btn btn-success" name="btn-salvar-comprar" id="btn-salvar-comprar">Comprar</button>

                        <input type="hidden" name="id-comprar" id="id-comprar">

                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- SCRIPT PARA DATATABLE -->
<script>
    $(document).ready(function() {
        $('#usuarios').DataTable({
            'ordering': false
        });
    });
</script>

<!-- SCRIPT PARA MOSTRAR DADOS -->
<script type="text/javascript">
    function mostrarDados(nome, descricao, foto, categoria, nome_forn, tel_forn) {
        event.preventDefault();

        if (nome_forn.trim() === "") {
            /* se o nome do fornecedor não existir, a div-forn, em que aparecem as variáveis $nome_forn e $tel_forn irá ter display = 'none', ou seja, não irá aparecer na modalDados, caso contrário, o nome do fornecedor e o telefone dele terão display = 'block', ou seja, irão aparecer */
            document.getElementById("div-forn").style.display = 'none';
        } else {
            document.getElementById("div-forn").style.display = 'block';
        }

        $('#nome-registro').text(nome);
        $('#categoria-registro').text(categoria);
        $('#descricao-registro').text(descricao);
        $('#nome-forn-registro').text(nome_forn);
        $('#tel-forn-registro').text(tel_forn);

        $('#imagem-registro').attr('src', '../img/produtos/' + foto); //atribui o segundo argumento ao primeiro, ou seja, salva em src o caminho digitado no segundo argumento


        var myModal = new bootstrap.Modal(document.getElementById('modalDados'), {

        })

        myModal.show();
    }
</script>

<!-- CHAMA MODAL PARA COMPRAR PRODUTO -->

<script type="text/javascript">
    function comprarProdutos(id) {

        event.preventDefault();

        $('#id-comprar').val(id);
        //coloca o id do produto, ou seja, res[$i]['id'] no elemento que tem id="id-comprar"
        //tem que ser com val(), com text não dá certo, $('#id-comprar').text(id);

        var myModal = new bootstrap.Modal(document.getElementById('modalComprar'), {})

        myModal.show();

    }
</script>

<!--AJAX PARA COMPRAR PRODUTO -->
<script type="text/javascript">
    $("#form-comprar").submit(function() {
        var pag = "<?= $pag ?>";
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "produtos/comprar-produto.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem-comprar').removeClass()

                if (mensagem.trim() == "Salvo com Sucesso!") {

                    $('#nome').val('');
                    $('#cpf').val('');
                    $('#btn-fechar').click();
                    window.location = "index.php?pagina=" + pag;
                } else {
                    $('#mensagem-comprar').addClass('text-danger')
                }

                $('#mensagem-comprar').text(mensagem)

            },

            cache: false,
            contentType: false,
            processData: false,
            xhr: function() { // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                    myXhr.upload.addEventListener('progress', function() {
                        /* faz alguma coisa durante o progresso do upload */
                    }, false);
                }
                return myXhr;
            }
        });
    });
</script>