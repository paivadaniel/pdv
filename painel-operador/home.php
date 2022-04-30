<?php

@session_start();
$id_usuario = $_SESSION['id_usuario'];

require_once('../conexao.php');
require_once('verifica_permissao.php');

//VERIFICA SE O CAIXA ESTÁ ABERTO
$query = $pdo->query("SELECT * from caixa WHERE operador = '$id_usuario' AND status = 'Aberto'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg > 0) {
    $aberto = 'Sim';
    $caixa = $res[0]['caixa'];
} else {
    $aberto = 'Não';
}

?>

<!-- MODAL PARA ABRIR CAIXA -->

<div class="modal fade" tabindex="-1" id="modalAbertura">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Abrir Caixa</h5>
            </div>

            <form method="POST" id="form-abertura">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="caixa" class="form-label">Caixa</label>
                                <select class="form-select mt-1" aria-label="Default select example" name="caixa">

                                    <?php

                                    $query = $pdo->query("SELECT * from caixas ORDER BY nome asc");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $total_reg = @count($res);

                                    if ($total_reg > 0) {

                                        for ($i = 0; $i < $total_reg; $i++) {
                                            foreach ($res[$i] as $key => $value) {
                                            } //fechamento do foreach                
                                    ?>

                                            <option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>

                                    <?php }
                                    } else { //fechamento do if seguido do fechamento do for 
                                        echo '<option value="">Cadastre um Caixa</option>'; //não consegue inserir o produto se não estiver antes cadastrado uma categoria

                                    }
                                    ?>

                                </select>

                            </div>
                        </div>


                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="caixa" class="form-label">Gerente</label>
                                <select class="form-select mt-1" aria-label="Default select example" name="gerente_ab">

                                    <?php

                                    $query = $pdo->query("SELECT * from usuarios WHERE nivel = 'Admin' ORDER BY nome asc");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $total_reg = @count($res);

                                    if ($total_reg > 0) {

                                        for ($i = 0; $i < $total_reg; $i++) {
                                            foreach ($res[$i] as $key => $value) {
                                            } //fechamento do foreach                
                                    ?>

                                            <option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>

                                    <?php }
                                    } else { //fechamento do if seguido do fechamento do for 
                                        echo '<option value="">Cadastre um Administrador</option>'; //não consegue inserir o produto se não estiver antes cadastrado uma categoria

                                    }
                                    ?>

                                </select>



                            </div>
                        </div>


                    </div> <!--  fechamento da row --->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="valor_ab" class="form-label">Valor de Abertura do Caixa</label>

                                <input type="text" class="form-control" id="valor_ab" name="valor_ab" placeholder="Digite o valor da abertura do caixa" required="">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="senha_gerente" class="form-label">Senha do Gerente</label>

                                <input type="password" class="form-control" id="senha_gerente" name="senha_gerente" placeholder="Digite a senha do gerente" required="">
                            </div>

                        </div>



                    </div>

                    <small>
                        <div align="center" class="mb-3" id="mensagem-abertura">
                        </div>
                    </small>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary" name="btn-salvar-abertura" id="btn-salvar-abertura">Abrir Caixa</button>
                        <!-- mesmo com o AJAX, o botão deve ser type="submit", e não type="button" 
                    o event.preventDefault() no script javascript abaixo, evita que a página seja carregada,
                    e em seguida o AJAX transmite os dados
                    -->

                        <input type="hidden" name="id-abertura" value="<?php echo $id_usu; ?>">

                    </div> <!--  fechamento modal-footer --->
                </div> <!--  fechamento modal-body --->

            </form>

        </div> <!--  fechamento modal-content --->
    </div> <!--  fechamento modal-dialog --->
</div> <!--  fechamento modal --->


<!-- MODAL PARA FECHAR CAIXA -->

<div class="modal fade" tabindex="-1" id="modalFechamento">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fechar Caixa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <form method="POST" id="form-fechamento">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="caixa_fechamento" class="form-label">Caixa</label>
                                <input type="text" class="form-control" id="caixa_fechamento" name="caixa_fechamento" value="<?php echo $caixa ?>" readonly required=""> <!-- não pode usar disabled, pois faz com que $_POST`['caixa_fechamento'] que é chamada em fechamento.php, não funcione, então tem que trabalhar com readonly -->

                                


                            </div>
                        </div>


                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label for="caixa" class="form-label">Gerente</label>
                                <select class="form-select mt-1" aria-label="Default select example" name="gerente_fechamento" id="gerente_fechamento">

                                    <?php

                                    $query = $pdo->query("SELECT * from usuarios WHERE nivel = 'Admin' ORDER BY nome asc");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $total_reg = @count($res);

                                    if ($total_reg > 0) {

                                        for ($i = 0; $i < $total_reg; $i++) {
                                            foreach ($res[$i] as $key => $value) {
                                            } //fechamento do foreach                
                                    ?>

                                            <option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>

                                    <?php }
                                    } else { //fechamento do if seguido do fechamento do for 
                                        echo '<option value="">Cadastre um Administrador</option>'; //não consegue inserir o produto se não estiver antes cadastrado uma categoria

                                    }
                                    ?>

                                </select>



                            </div>
                        </div>


                    </div> <!--  fechamento da row --->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="valor_ab" class="form-label">Valor de Fechamento do Caixa</label>

                                <input type="text" class="form-control" id="valor_fechamento" name="valor_fechamento" placeholder="Digite o valor de fechamento do caixa" required="">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="senha_gerente" class="form-label">Senha do Gerente</label>

                                <input type="password" class="form-control" id="senha_gerente_fechamento" name="senha_gerente_fechamento" placeholder="Digite a senha do gerente" required="">
                            </div>

                        </div>



                    </div>

                    <small>
                        <div align="center" class="mb-3" id="mensagem-fechamento">
                        </div>
                    </small>

                    <div class="modal-footer">

                        <a href="pdv.php" class="btn btn-primary">Voltar PDV</a>

                        <button type="submit" class="btn btn-danger" name="btn-salvar-fechamento" id="btn-salvar-fechamento">Fechar Caixa</button>
                        <!-- mesmo com o AJAX, o botão deve ser type="submit", e não type="button" 
                    o event.preventDefault() no script javascript abaixo, evita que a página seja carregada,
                    e em seguida o AJAX transmite os dados
                    -->

                        <input type="hidden" name="id-fechamento" value="<?php echo $id_usu; ?>">

                    </div> <!--  fechamento modal-footer --->
                </div> <!--  fechamento modal-body --->

            </form>

        </div> <!--  fechamento modal-content --->
    </div> <!--  fechamento modal-dialog --->
</div> <!--  fechamento modal --->



<!-- SCRIPT PARA MODAL DE ABERTURA E FECHAMENTO -->
<script>
    $(document).ready(function() {
        var aberto = "<?= $aberto ?>";

        if (aberto === 'Sim') {
            var myModal = new bootstrap.Modal(document.getElementById('modalFechamento'), {
                backdrop: 'static'

            })

            myModal.show();

        } else {
            var myModal = new bootstrap.Modal(document.getElementById('modalAbertura'), {
                backdrop: 'static'

            })

            myModal.show();

        }

    });
</script>



<!-- AJAX PARA ABRIR O CAIXA -->
<script type="text/javascript">
    $("#form-abertura").submit(function() {

        event.preventDefault();
        /*
        toda vez que submetemos uma página por um formulário, ela atualiza,
        o event.preventDefault() evita que a página seja atualizada,
        essa é a principal função do ajax
        */
        var formData = new FormData(this);

        $.ajax({
            url: "abertura.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem-abertura').removeClass()

                if (mensagem.trim() == "Abertura feita com Sucesso!") {

                    window.location = "pdv.php";

                } else { //se não devolver "Abertura feita com Sucesso!", ou seja, se der errado

                    $('#mensagem-abertura').addClass('text-danger')
                }

                $('#mensagem-abertura').text(mensagem)


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

<!-- AJAX PARA FECHAR O CAIXA -->
<script type="text/javascript">
    $("#form-fechamento").submit(function() {

        event.preventDefault();
        /*
        toda vez que submetemos uma página por um formulário, ela atualiza,
        o event.preventDefault() evita que a página seja atualizada,
        essa é a principal função do ajax
        */
        var formData = new FormData(this);

        $.ajax({
            url: "fechamento.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem-fechamento').removeClass()

                if (mensagem.trim() == "Fechamento feito com Sucesso!") {

                    window.location = "pdv.php";

                } else { //se não devolver "Abertura feita com Sucesso!", ou seja, se der errado

                    $('#mensagem-fechamento').addClass('text-danger')
                }

                $('#mensagem-fechamento').text(mensagem)


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