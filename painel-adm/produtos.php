<?php
$pag = 'produtos';
@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

?>

<a href="index.php?pagina=<?php echo $pag ?>&funcao=novo" type="button" class="btn btn-secondary mt-2">Novo Produto</a>

<div class="mt-4" style="margin-right: 25px">

    <?php

    $query = $pdo->query("SELECT * from produtos order by id desc");
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
                                <img src="../img/<?php echo $pag ?>/<?php echo $res[$i]['foto'] ?>" width="50px">
                            </td>

                            <!-- AÇÕES -->
                            <td>

                                <a href="index.php?pagina=<?php echo $pag ?>&funcao=editar&id=<?php echo $res[$i]['id']; ?>" title="Editar Registro" style="text-decoration:none">
                                    <i class="bi bi-pencil-square text-primary me-2"></i>
                                </a>

                                <a href="index.php?pagina=<?php echo $pag ?>&funcao=deletar&id=<?php echo $res[$i]['id']; ?>" title="Excluir Registro" style="text-decoration:none">
                                    <i class="bi bi-archive text-danger me-2"></i>
                                </a>


								<a href="#" onclick="mostrarDados('<?php echo $res[$i]['nome'] ?>', '<?php echo $res[$i]['descricao'] ?>', '<?php echo $res[$i]['foto'] ?>', '<?php echo $nome_cat ?>', '<?php echo $nome_forn ?>', '<?php echo $tel_forn ?>')" title="Ver Descriçao" style="text-decoration: none">
									<i class="bi bi-card-text text-dark me-2"></i>
								</a>


                                <a href="#" onclick="comprarProdutos('<?php echo $res[$i]['id']; ?>')" title="Fazer Pedido" style="text-decoration:none">
                                    <i class="bi bi-bag text-success me-2"></i>
                                </a>

                                <a href="../rel/barras_class.php?id=<?php echo $res[$i]['id']?>" target="_blank" title="Gerar Etiquetas" style="text-decoration:none">
                                    <i class="bi bi-clipboard-check text-dark"></i>
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


<?php

if (@$_GET['funcao'] == 'editar') {
    $titulo_modal = "Editar Registro";

    $query_ed = $pdo->query("SELECT * FROM produtos WHERE id = '$_GET[id]'");
    $res_ed = $query_ed->fetchAll(PDO::FETCH_ASSOC);
    $total_res_ed = @count($res_ed);

    if ($total_res_ed > 0) {
        //recupera dados do usuário, os quais foram inseridos no banco de dados
        $codigo = $res_ed[0]['codigo'];
        $nome = $res_ed[0]['nome'];
        $descricao = $res_ed[0]['descricao'];
        $estoque = $res_ed[0]['estoque'];
        $valor_compra = $res_ed[0]['valor_compra'];
        $valor_venda = $res_ed[0]['valor_venda'];
        $fornecedor = $res_ed[0]['fornecedor'];
        $categoria = $res_ed[0]['categoria']; //armazena o id da categoria

        $foto = $res_ed[0]['foto'];
    }
} else {
    $titulo_modal = "Inserir Registro";
}


?>

<!-- MODAL PARA INSERÇÃO/EDIÇÃO DOS DADOS -->

<div class="modal fade" tabindex="-1" id="modalCadastrar" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $titulo_modal ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="form">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4">

                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código</label>
                                <input type="number" class="form-control" id="codigo" name="codigo" placeholder="Código" required value="<?php echo @$codigo ?>"> <!-- input do código de barras deve ser do tipo number, e não text, o tipo number aceita letras, mas não permite apertar o botão salvar, ou seja, precisa ser número -->
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required value="<?php echo @$nome ?>">
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="mb-3">
                                <label for="valor_venda" class="form-label">Valor venda</label>
                                <input type="text" class="form-control" id="valor_venda" name="valor_venda" placeholder="Valor da Venda" required value="<?php echo @$valor_venda ?>">
                            </div>

                        </div>
                    </div>



                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea type="text" class="form-control" id="descricao" name="descricao" maxlength="200"> <?php echo @$descricao ?> </textarea> <!-- textarea não tem value, tem que colocar entre a abertura e fechamento das tags -->
                    </div>


                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group mb-3">
                                <label for="categoria">Categoria</label>
                                <select class="form-select mt-1" aria-label="Default select example" name="categoria">

                                    <?php

                                    $query = $pdo->query("SELECT * from categorias ORDER BY nome asc");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $total_reg = @count($res);

                                    if ($total_reg > 0) {

                                        for ($i = 0; $i < $total_reg; $i++) {
                                            foreach ($res[$i] as $key => $value) {
                                            } //fechamento do foreach                

                                            //$categoria armazena o id da categoria conforme query realizada lá em cima
                                    ?>

                                            <option <?php
                                                    /*
                                        para edição, se a categoria que já estava lá for igual ao id que está vindo, é para deixar ela selecionada, pois o produto que está sendo editado pertence àquela categoria
                                        */
                                                    if (@$categoria == $res[$i]['id']) { //chave de abertura do if 
                                                    ?> selected <?php } ?> value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>

                                    <?php }
                                    } else { //fechamento do if seguido do fechamento do for 
                                        echo '<option value="">Cadastre uma Categoria</option>'; //não consegue inserir o produto se não estiver antes cadastrado uma categoria

                                    }
                                    ?>

                                </select>

                            </div>

                        </div>

                        <div class="col-md-4">
                            <!-- INPUT PARA UPLOAD DE IMAGEM -->
                            <div class="form-group">
                                <label>Foto</label>
                                <input type="file" value="<?php echo @$foto ?>" class="form-control-file" id="imagem" name="imagem" onChange="carregarImg();">
                            </div>

                        </div>


                        <div class="col-md-4">
                            <div id="divImgConta" class="mt-4">
                                <?php if (@$foto != "") { //se a imagem já existir 
                                ?>
                                    <img src="../img/<?php echo $pag ?>/<?php echo $foto ?>" width="150px" id="target">
                                <?php  } else { //se for a primeira inserção da categoria, e não tiver sido escolhida uma imagem 
                                ?>
                                    <img src="../img/<?php echo $pag ?>/sem-foto.jpg" width="150px" id="target">
                                <?php } ?>
                            </div>

                        </div>

                    </div>

                </div>

                <div id="codigoBarra">

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

                    <input type="hidden" name="antigoCodigo" value="<?php echo @$codigo; ?>">

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
                <h5 class="modal-title">Excluir Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="form-excluir">

                <div class="modal-body">

                    <p>Deseja realmente excluir o registro?</p>

                    <small>
                        <div align="center" class="mb-3" id="mensagem-excluir">
                        </div>
                    </small>

                </div>
                <!-- a falta da div acima, embolando o modal-body com o modal-footer me custou cerca de 2 horas para saber porque vários dos meus modais não estavam abrindo, simples, todos os modais abaixo do código da modalDeletar tiveram problema por conta da falta dessa div -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                    <button type="submit" class="btn btn-danger" name="btn-excluir" id="btn-excluir">Excluir</button>

                    <input type="hidden" name="id" value="<?php echo @$_GET['id'] ?>">

                </div>
            </form>
        </div> <!-- demorei cerca de 40 minutos para corrigir o erro a seguir: essa div que é da modal-content estava dentro do form, ou seja, o formulário que faz parte do conteúdo, estava sem fechamento, acabando depois dele, o que deu problema em todas as modais após essa 
        o erro estava inclusive fazendo com que o dataTable não fosse exibido (a paginação) ao clicar na modalDeletar e nas após ela -->


    </div>
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
    $("#form").submit(function() { //executa uma função com base na submissão (envio) do formulário
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
        gerarCodigo();
        /*o código de barras se clicar em editar o registro, tem que aparecer, por isso adiciona a função que o chama aqui, e fazendo dessa forma, ao clicar em inserir um novo registro, ele irá gerar um código de barras representando o vazio
         */
        $('#usuarios').DataTable({
            'ordering': false
        });
    });
</script>


<!--SCRIPT PARA MOSTRAR TROCA DE IMAGEM -->
<script type="text/javascript">
    function carregarImg() {

        var target = document.getElementById('target'); //pega o caminho do img com id="target"
        var file = document.querySelector("input[type=file]").files[0]; //não entendi, porém, creio que armazena a imagem para troca, ou seja, a nova imagem (do tipo file) selecionada no explorador de arquivos para troca
        var reader = new FileReader(); //criou um objeto, ou seja, uma instância de uma classe, no caso a FileReader

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

<!-- SCRIPT PARA MOSTRAR DADOS -->
<script type="text/javascript">
	function mostrarDados(nome, descricao, foto, categoria, nome_forn, tel_forn){
		event.preventDefault();

		if(nome_forn.trim() === ""){ 
            /* se o nome do fornecedor não existir, a div-forn, em que aparecem as variáveis $nome_forn e $tel_forn irá ter display = 'none', ou seja, não irá aparecer na modalDados, caso contrário, o nome do fornecedor e o telefone dele terão display = 'block', ou seja, irão aparecer */
			document.getElementById("div-forn").style.display = 'none';
		}else{
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

<!--AJAX PARA MOSTRAR CÓDIGO DE BARRAS AO DIGITAR CAMPO CÓDIGO EM NOVO PRODUTO -->
<script type="text/javascript">
    $('#codigo').keyup(function() {
        /*
        função change só funciona para campo select, se for para input tem que ser keyup
        */
        gerarCodigo();
    });
</script>

<script type="text/javascript">
    var pag = "<?= $pag ?>";

    function gerarCodigo() {
        $.ajax({
            url: pag + '/barras.php',
            method: 'POST',
            data: $('#form').serialize(),
            dataType: "html",

            success: function(result) {
                $('#codigoBarra').html(result); //mostra no campo com id=codigoBarra, o resultado html do ajax gerarCodigo
            }

        });

    }
</script>

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
            url: pag + "/comprar-produto.php",
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