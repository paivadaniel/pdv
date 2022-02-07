<?php

require_once('../conexao.php');

$pag = 'usuarios';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Usuários</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="../DataTables/datatables.min.css">

    <!-- DataTables Javascript -->
    <script type="text/javascript" src="../DataTables/datatables.min.js"></script>

</head>

<body>

    <a href="index.php?pagina=<?php echo $pag; ?>&funcao=novo" class="btn btn-secondary mt-2">Criar Usuário</a>

    <div class="mt-4" style="margin-right: 25px">


        <?php

        $query_tab = $pdo->query("SELECT * FROM usuarios WHERE id=50");

        $res_tab = $query_tab->fetchAll(PDO::FETCH_ASSOC);
        $total_reg_tab = @count($res_tab);

        if ($total_reg_tab > 0) {

        ?>

            <table id="usuarios" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Senha</th>
                        <th>Nível</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    for ($i = 0; $i < $total_reg_tab; $i++) {
                        foreach ($res_tab[$i] as $key => $value) {
                        }

                    ?>

                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>System Architect</td>
                            <td>System Architect</td>
                            <td>System Architect</td>
                            <td>System Architect</td>
                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        <?php } else {
            echo "Não existem dados para serem exibidos!";
        } ?>

    </div>

</body>



<div class="modal fade" tabindex="-1" id="modalCadastrar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inserir Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="form">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome" required>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite seu CPF" required>
                            </div>

                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="text" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="nivelCad">Nível</label>
                        <select class="form-select mt-1" aria-label="Default select example" name="nivel" id="nivel" required>

                            <?php
                            /*usou selected logo abaixo, e não precisou usar o if abaixo, que aliás mantinha $nivel_ed
                        aparecendo duas vezes nos options
                        */
                            /*
                            if(@$_GET['funcao'] == 'editar') {
                                echo '<option value="' . $nivel_ed. '">'. $nivel_ed .'</option>';
                            }

                            */
                            ?>

                            <option <?php if (@$nivel_ed == 'Operador') { ?> selected <?php } ?> value="Operador">Operador</option>
                            <option <?php if (@$nivel_ed == 'Admin') { ?> selected <?php } ?> value="Admin">Admin</option>
                            <option <?php if (@$nivel_ed == 'Tesoureiro') { ?> selected <?php } ?> value="Tesoureiro">Tesoureiro</option>
                        </select>
                    </div>
                </div>

                <small>
                    <div align="center" class="mb-3" id="mensagem">
                        Cadastrado com sucesso!
                    </div>
                </small>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                    <button type="submit" class="btn btn-primary" name="btn-salvar" id="btn-salvar">Salvar</button>
                    <!-- mesmo com o AJAX, o botão deve ser type="submit", e não type="button" 
                    o event.preventDefault() no script javascript abaixo, evita que a página seja carregada,
                    e em seguida o AJAX transmite os dados
                    -->
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

        <?php

    }
        ?>
    </script>

    <!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM IMAGEM -->
    <script type="text/javascript">
        $("#form").submit(function() {
            var pag = "<?= $pag ?>";
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
                        //window.location = "index.php?pagina="+pag; //atualiza a página

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

    <script>
        $(document).ready(function() {
            $('#usuarios').DataTable();
        });
    </script>