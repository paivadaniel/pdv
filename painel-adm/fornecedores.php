<?php

@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

$pag = 'fornecedores';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Fornecedores</title>

</head>

<body>

    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=novo" type="button" class="btn btn-secondary mt-2">Novo Fornecedor</a>

    <div class="mt-4" style="margin-right: 25px">


        <?php

        $query_tab = $pdo->query("SELECT * FROM fornecedores order by id desc"); //nome é o campo da tabela

        $res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
        $total_reg_tab = @count($res_tab);

        if ($total_reg_tab > 0) {

        ?>

            <small>
                <table id="usuarios" class="table table-hover my-4" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo Pessoa</th>
                            <th>Email</th>
                            <th>CPF / CNPJ</th>
                            <th>Telefone</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        for ($i = 0; $i < $total_reg_tab; $i++) {
                            foreach ($res_tab[$i] as $key => $value) {
                            } //fechamento do foreach

                        ?>

                            <tr>
                                <td><?php echo $res_tab[$i]['nome']; ?></td>
                                <td><?php echo $res_tab[$i]['tipo_pessoa']; ?></td>
                                <td><?php echo $res_tab[$i]['email']; ?></td>
                                <td><?php echo $res_tab[$i]['cpf']; ?></td>
                                <td><?php echo $res_tab[$i]['telefone']; ?></td>
                                <td>

                                    <ahref="index.php?pagina=<?php echo $pag; ?>&funcao=editar&id=<?php echo $res_tab[$i]['id']; ?>" type="button" title="Editar Registro">
                                        <i class="bi bi-pencil-square text-primary me-2"></i>
                                    </a>

                                    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=deletar&id=<?php echo $res_tab[$i]['id']; ?>" type="button" title="Excluir Registro">
                                        <i class="bi bi-archive text-danger"></i>
                                    </a>

                                    <!-- informações serão passadas via script, e não com o GET como nos links trabalhados acima -->
                                    <!--
                                        se não colocar # no href não irá funcionar, e irá dar um refresh (atualizar) a página
                                     -->
                                    <a href="#" onclick="mostrarDados('<?php echo $res_tab[$i]['endereco']; ?>, <?php echo $res_tab[$i]['nome']; ?>')" title="Ver Endereço">
                                        <i class="bi bi-house text-dark ms-2"></i>

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


<?php

if (@$_GET['funcao'] == 'editar') {
    $titulo_modal = "Editar Registro";

    $query_ed = $pdo->query("SELECT * FROM fornecedores WHERE id = '$_GET[id]'");
    $res_ed = $query_ed->fetchAll(PDO::FETCH_ASSOC);
    $total_res_ed = @count($res_ed);

    if ($total_res_ed > 0) {
        //recupera dados do usuário, os quais foram inseridos no banco de dados
        $nome = $res_ed[0]['nome'];
        $tipo_pessoa = $res_ed[0]['tipo_pessoa'];
        $email = $res_ed[0]['email'];
        $cpf = $res_ed[0]['cpf'];
        $telefone = $res_ed[0]['telefone'];
        $endereco = $res_ed[0]['endereco'];
    }
} else {
    $titulo_modal = "Inserir Registro";
}


?>

<!-- MODAL PARA INSERÇÃO DOS DADOS -->

<div class="modal fade" tabindex="-1" id="modalCadastrar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $titulo_modal; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="form">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome" required value="<?php echo @$nome; ?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tipo_pessoa">Tipo Pessoa</label>
                                <select class="form-select mt-1" aria-label="Default select example" name="tipo_pessoa">

                                    <option <?php if (@$tipo_pessoa == 'Fisica') { ?> selected <?php } ?> value="Física">Física</option>

                                    <option <?php if (@$tipo_pessoa == 'Juridica') { ?> selected <?php } ?> value="Jurídica">Jurídica</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Digite seu nome" required value="<?php echo @$telefone; ?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF / CNPJ</label>
                                <input type="text" class="form-control" id="doc" name="cpf" placeholder="Digite seu CPF / CNPJ" value="<?php echo @$cpf; ?>"> <!-- id é doc pois a máscara (mascara.js) referencia um id -->
                            </div>

                        </div>
                    </div>

                    <div class="row">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" value="<?php echo @$email; ?>">
                        </div>

                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Digite seu endereço" value="<?php echo @$endereco; ?>">
                        </div>

                    </div>



                </div>

                <small>
                    <div align="center" class="mb-3" id="mensagem">
                    </div>
                </small>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                    <button type="submit" class="btn btn-primary" name="btn-salvar" id="btn-salvar">Salvar</button>
                    <!-- mesmo com o AJAX, o botão deve ser type="submit", e não type="button" 
                    o event.preventDefault() no script javascript abaixo, evita que a página seja carregada,
                    e em seguida o AJAX transmite os dados
                    -->

                    <input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">

                    <input type="hidden" name="antigoEmail" value="<?php echo @$email; ?>">
                    <input type="hidden" name="antigoCpf" value="<?php echo @$cpf; ?>">


                </div>
            </form>

        </div>
    </div>
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

<!-- MODAL PARA MOSTRAR O ENDEREÇO -->

<div class="modal fade" tabindex="-1" id="modalDados">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dados do Fornecedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">

             <b>Nome: </b>
             <span id="nome-registro"></span>

             <hr>
             <b>Endereço: </b>
             <span id="endereco-registro"></span>


            </div>
        </div>
    </div>

</div>

</html>

<?php

if (@$_GET['funcao'] == 'novo') {
?>

    <script>
        var myModal = new bootstrap.Modal(document.getElementById('modalCadastrar'), {
            backdrop: 'static'
        })

        myModal.show();
    </script>

<?php

}
?>

<?php

if (@$_GET['funcao'] == 'editar') {
?>

    <script>
        var myModal = new bootstrap.Modal(document.getElementById('modalCadastrar'), {
            backdrop: 'static'
        })

        myModal.show();
    </script>

<?php

}
?>

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



<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM IMAGEM -->
<script type="text/javascript">
    $("#form").submit(function() {
        var pag = "<?= $pag ?>"; //não sei porque não colocou < ?php $pag ?>, ou seja trocou php por =
        event.preventDefault();
        /*
        toda vez que submetemos uma página por um formulário, ela atualiza,
        o event.preventDefault() evita que a página seja atualizada,
        essa é a principal função do ajax
        */
        var formData = new FormData(this);

        $.ajax({
            url: pag + "/inserir.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem').removeClass()

                if (mensagem.trim() == "Salvo com Sucesso!") {

                    $('#nome').val('');
                    $('#cpf').val('');
                    $('#btn-fechar').click();
                    window.location = "index.php?pagina=" + pag; //atualiza a página
                    /*não precisou colocar $pag, e sim apenas pag, pois é javascript,
                    e não php, e acima var pag = < ?php $pag ?>
                    */

                } else { //se não devolver "Salvo com Sucesso!", ou seja, se der errado

                    $('#mensagem').addClass('text-danger')
                }

                $('#mensagem').text(mensagem)

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

<!-- SCRIPT PARA MOSTRAR ENDEREÇO -->
<script type="text/javascript">
    function mostrarDados(endereco, nome) {

        event.preventDefault();

        $('#endereco-registro').text(endereco);
        $('#nome-registro').text(nome);

        var myModal = new bootstrap.Modal(document.getElementById('modalDados'), {})

        myModal.show();

    }
</script>