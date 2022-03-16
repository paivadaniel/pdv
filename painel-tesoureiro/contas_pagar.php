<?php

@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

$pag = 'contas_pagar';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Contas à Pagar</title>

</head>

<body>

    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=novo" type="button" class="btn btn-secondary mt-2">Nova Conta</a>

    <div class="mt-4" style="margin-right: 25px">


        <?php

        $query_tab = $pdo->query("SELECT * FROM contas_pagar order by id desc"); //nome é o campo da tabela

        $res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
        $total_reg_tab = @count($res_tab);

        if ($total_reg_tab > 0) {

        ?>

            <small>
                <table id="usuarios" class="table table-hover my-4" style="width:100%">
                    <thead>
                        <tr>
                            <th>Pago</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Usuário</th>
                            <th>Data</th>
                            <th>Arquivo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        for ($i = 0; $i < $total_reg_tab; $i++) {
                            foreach ($res_tab[$i] as $key => $value) {
                            } //fechamento do foreach

                            $id_usu = $res_tab[0]['usuario'];
                            $data = $res_tab[$i]['data'];

                            if ($res_tab[$i]['pago'] == 'Sim') {
                                $classe = 'text-success';
                            } else {
                                $classe = 'text-danger';
                            }


                            $extensao = strchr($res_tab[$i]['arquivo'], '.'); //separa a partir do ponto para frente, por exemplo, "teste.pdf" e "foto.jpg" vai guardar ".pdf" e ".jpg"
                            if($extensao == '.pdf') {
                                $arquivo_pasta = 'pdf.png';
                            } else {
                                $arquivo_pasta = $res_tab[$i]['arquivo'];
                            }

                            //encontra o usuário responsável pela compra
                            $query_p = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_usu'");
                            $res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);
                            $nome_usu = $res_p[0]['nome'];

                        ?>

                            <tr>
                                <td>
                                    <i class="bi bi-square-fill <?php echo $classe; ?>"></i>
                                </td>

                                <td>
                                <?php echo $res_tab[$i]['descricao']; ?>
                                </td>

                                <td>
                                    R$ <?php echo number_format($res_tab[$i]['valor'], 2, ',', '.'); ?>
                                </td>

                                <td><?php echo $nome_usu ?></td>
                                <td>
                                    <?php echo implode('/', array_reverse(explode('-', $data)));
                                    ?>
                                </td>


                                <td>
                                    <img src="../img/<?php echo $pag ?>/<?php echo $arquivo_pasta ?>" width="50px">
                                </td>
                                <td>

                                    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=editar&id=<?php echo $res_tab[$i]['id']; ?>" type="button" title="Editar Registro" style="text-decoration: none">
                                        <i class="bi bi-pencil-square text-primary me-2"></i>
                                    </a>

                                    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=deletar&id=<?php echo $res_tab[$i]['id']; ?>" type="button" title="Excluir Registro" style="text-decoration: none">
                                        <i class="bi bi-archive text-danger me-2"></i>
                                    </a>

                                    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=baixar&id=<?php echo $res_tab[$i]['id']; ?>" type="button" title="Baixar Registro">
                                        <i class="bi bi-check-square-fill text-success me-2" style="text-decoration: none"></i>
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

    $query_ed = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$_GET[id]'");
    $res_ed = $query_ed->fetchAll(PDO::FETCH_ASSOC);
    $total_res_ed = @count($res_ed);

    if ($total_res_ed > 0) { 
        //recupera dados do usuário, os quais foram inseridos no banco de dados
        $valor = $res_ed[0]['valor'];
        $descricao = $res_ed[0]['descricao'];
        $arquivo = $res_ed[0]['arquivo'];
        //usuário, data e pago não precisam ser recuperados, pois não poderão ser alterados

        //mesmo tratamento aplicado na inserção, para mostrar imagem de um pdf se o arquivo que foi feito o upload for um pdf, ou a própria imagem, se for uma imagem
        $extensao2 = strchr($arquivo, '.');

        if($extensao2 == '.pdf') {
            $arquivo_pasta2 = 'pdf.png';
        } else {
            $arquivo_pasta2 = $arquivo;
        }

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
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Digite a descrição" required value="<?php echo @$descricao; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="valor" class="form-label">Valor</label>
                        <input type="text" class="form-control" id="valor" name="valor" placeholder="Digite o valor" required value="<?php echo @$valor; ?>">
                    </div>


                    <!-- INPUT PARA UPLOAD DE IMAGEM -->
                    <div class="form-group">
                        <label>Arquivo</label>
                        <input type="file" value="<?php echo @$arquivo ?>" class="form-control-file" id="arquivo" name="arquivo" onChange="carregarImg();">
                    </div>

                    <div id="divImgConta" class="mt-4">
                        <?php if (@$arquivo != "") { //se a imagem já existir 
                        ?>
                            <img src="../img/<?php echo $pag ?>/<?php echo @$arquivo_pasta2;?>" width="200px" id="target"> <!-- arquivo_pasta2 é para mostrar imagem de um pdf se for feito o upload de um arquivo pdf ou a própria imagem se for feito o upload de uma imagem -->
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

        //o código a seguir, até o if, serve para mostrar a imagem de um pdf quando for feito o upload de um arquivo pdf
		var arquivo = file['name'];
		resultado = arquivo.split(".", 2); //quebra o arquivo em dois vetores, separando pelo ponto, por exemplo, minhafoto.pdf, vira "minhafoto" e "pdf"
        
        if(resultado[1] === 'pdf'){ //na posição 1 terá "pdf" do split acima
        	$('#target').attr('src', "../img/contas_pagar/pdf.png"); //atribui ao campo source a imagem "pdf.png"
        	return;
        }


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