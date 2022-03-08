<?php

@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

$pag = 'categorias';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Categorias</title>

</head>

<body>

    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=novo" type="button" class="btn btn-secondary mt-2">Nova Categoria</a>

    <div class="mt-4" style="margin-right: 25px">


        <?php

        $query_tab = $pdo->query("SELECT * FROM categorias order by id desc"); //nome é o campo da tabela

        $res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
        $total_reg_tab = @count($res_tab);

        if ($total_reg_tab > 0) {

        ?>

            <small>
                <table id="usuarios" class="table table-hover my-4" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Produtos</th>
                            <th>Foto</th>
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
                                <td></td>
                                <td>
                                    <img src="../img/<?php echo $pag ?>/<?php echo $res_tab[$i]['foto'] ?>" width="50px">
                                </td>
                                <td>

                                    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=editar&id=<?php echo $res_tab[$i]['id']; ?>" type="button" title="Editar Registro">
                                        <i class="bi bi-pencil-square text-primary me-2"></i>
                                    </a>

                                    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=deletar&id=<?php echo $res_tab[$i]['id']; ?>" type="button" title="Excluir Registro">
                                        <i class="bi bi-archive text-danger"></i>
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

    $query_ed = $pdo->query("SELECT * FROM categorias WHERE id = '$_GET[id]'");
    $res_ed = $query_ed->fetchAll(PDO::FETCH_ASSOC);
    $total_res_ed = @count($res_ed);

    if ($total_res_ed > 0) {
        //recupera dados do usuário, os quais foram inseridos no banco de dados
        $nome = $res_ed[0]['nome'];
        $foto = $res_ed[0]['foto'];
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

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome" required value="<?php echo @$nome; ?>">
                    </div>

                    <!-- INPUT PARA UPLOAD DE IMAGEM -->
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" value="<?php echo @$foto ?>" class="form-control-file" id="imagem" name="imagem" onChange="carregarImg();">
                    </div>

                    <div id="divImgConta" class="mt-4">
                        <?php if (@$foto != "") { //se a imagem já existir 
                        ?>
                            <img src="../img/<?php echo $pag ?>/<?php echo $foto ?>" width="200px" id="target">
                        <?php  } else { //se for a primeira inserção da categoria, e não tiver sido escolhida uma imagem 
                        ?>
                            <img src="../img/<?php echo $pag ?>/sem-foto.jpg" width="200px" id="target">
                        <?php } ?>
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

                    <input type="hidden" name="antigoNome" value="<?php echo @$nome; ?>">

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


<!--SCRIPT PARA MOSTRAR TROCA DE IMAGEM -->
<script type="text/javascript">
    function carregarImg() {

        var target = document.getElementById('target');
        var file = document.querySelector("input[type=file]").files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);


        } else {
            target.src = "";
        }
    }
</script>