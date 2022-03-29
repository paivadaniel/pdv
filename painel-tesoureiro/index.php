<?php

@session_start();
/*tem que ser antes do require_once('verifica_permissao.php'),
pois esse arquivo usa a variável de sessão $_SESSION['nivel_usuario']
*/

require_once('../conexao.php');
require_once('verifica_permissao.php');

//VARIÁVEIS DO MENU ADMINISTRATIVO
$menu1 = 'home';
$menu2 = 'contas_pagar';
$menu3 = 'contas_receber';
$menu4 = 'movimentacoes';
$menu5 = 'vendas';
$menu6 = 'compras';

//RECUPERAR DADOS DO USUÁRIO
$query = $pdo->query("SELECT * FROM usuarios WHERE id = '$_SESSION[id_usuario]'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

//ele poderia ter criado todas as variáveis de sessão em autenticar.php, e não precisar executar essa query para pegar os dados do banco de dados
/*
usei "usu" ao invés de "usuário", pois outras páginas são chamadas dentro dessa página
e podem já ter variável $nome_usuario, $email_usuario etc.
*/
$nome_usu = $res[0]['nome'];
$email_usu = $res[0]['email'];
$cpf_usu = $res[0]['cpf'];
$senha_usu = $res[0]['senha'];
$nivel_usu = $res[0]['nivel'];
$id_usu = $res[0]['id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Tesouraria</title>

    <!--bootstrap css-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!--bootstrap javascript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap Icons CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="../vendor/DataTables/datatables.min.css">

    <!-- DataTables Javascript -->
    <script type="text/javascript" src="../vendor/DataTables/datatables.min.js"></script>

    <!-- FAVICON -->
    <link rel="shortcut icon" href="../img/favicon.ico" />

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="../img/logo.png" width="50px">
                </img>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php?pagina=<?php echo $menu1; ?>">Home</a>
                    </li>




                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Contas
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?pagina=<?php echo $menu2; ?>">Contas à Pagar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?pagina=<?php echo $menu3; ?>">Contas à Receber</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?pagina=<?php echo $menu4; ?>">Movimentações</a>
                    </li>



                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Vendas / Compras
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="index.php?pagina=<?php echo $menu5; ?>">Lista de Vendas</a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="index.php?pagina=<?php echo $menu6; ?>">Lista de Compras</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>


                    <li class="nav-item dropdown">
                        <!-- pode manter o mesmo id do dropdown passado, ou seja, id="navbarDropdown" -->
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Relatórios
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#ModalRelMov">Relatório de Movimentações</a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#ModalRelContasPagar">Relatório de Contas à Pagar</a>
                            </li>


                        </ul>
                    </li>


                </ul>
                <div class="d-flex mx-3">

                    <img src="../img/icone-user.png" alt="Usuário" width="40px" height="40px">

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $nome_usu; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalPerfil">Editar Perfil</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </nav>

</body>


<div class="container-fluid mt-2 mx-3">

    <?php
    if (@$_GET['pagina'] == $menu1) {
        require_once($menu1 . '.php');
    } else if (@$_GET['pagina'] == $menu2) {
        require_once($menu2 . '.php');
    } else if (@$_GET['pagina'] == $menu3) {
        require_once($menu3 . '.php');
    } else if (@$_GET['pagina'] == $menu4) {
        require_once($menu4 . '.php');
    } else if (@$_GET['pagina'] == $menu5) {
        require_once($menu5 . '.php');
    } else if (@$_GET['pagina'] == $menu6) {
        require_once($menu6 . '.php');
    } else {
        //caso não for nenhuma das páginas do GET, e tiver algum lixo nele, carrega a home.php
        require_once($menu1 . '.php');
    }

    ?>
</div>

<!-- MODAL PARA EDIÇÃO DOS DADOS -->

<div class="modal fade" tabindex="-1" id="modalPerfil">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="form-perfil">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <!--
                                id não pode ser "nome", pois usuarios.php já tem uma modal com id="nome", e usuarios.php é chamada em index.php, portanto, haverá conflito
                                -->
                                <input type="text" class="form-control" id="nome-perfil" name="nome-perfil" placeholder="Digite seu nome" required value="<?php echo $nome_usu; ?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf-perfil" name="cpf-perfil" placeholder="Digite seu CPF" required value="<?php echo $cpf_usu; ?>">
                            </div>

                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email-perfil" name="email-perfil" placeholder="Digite seu email" required value="<?php echo $email_usu; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="text" class="form-control" id="senha-perfil" name="senha-perfil" placeholder="Digite sua senha" required value="<?php echo $senha_usu; ?>">
                    </div>

                </div>

                <small>
                    <div align="center" class="mb-3" id="mensagem-perfil">
                    </div>
                </small>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-perfil">Fechar</button>
                    <button type="submit" class="btn btn-primary" name="btn-salvar-perfil" id="btn-salvar-perfil">Salvar</button>
                    <!-- mesmo com o AJAX, o botão deve ser type="submit", e não type="button" 
                    o event.preventDefault() no script javascript abaixo, evita que a página seja carregada,
                    e em seguida o AJAX transmite os dados
                    -->

                    <input type="hidden" name="id-perfil" value="<?php echo $id_usu; ?>">

                    <input type="hidden" name="antigoPerfilEmail" value="<?php echo $email_usu; ?>">
                    <input type="hidden" name="antigoPerfilCpf" value="<?php echo $cpf_usu; ?>">


                </div>
            </form>

        </div>
    </div>
</div>

<!--  Modal Rel Mov -->

<div class="modal fade" tabindex="-1" id="ModalRelMov">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Relatório de Movimentações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <form action="../rel/relMov_class.php" method="POST" target="_blank">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control mt-1" name="dataInicial">
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control mt-1" name="dataFinal">
                            </div>


                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <label>Status</label>
                                <!-- a classe "form-select" aplicada abaixo, difere da "form-control" ao apresentar uma flecha para mudar a opção da caixa seletora  -->
                                <select class="form-select mt-1" name="status">
                                    <option value="">Todas</option>
                                    <option value="Entradas">Entradas</option>
                                    <option value="Saídas">Saídas</option>

                                </select>
                            </div>


                        </div>

                    </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </form>

        </div>
    </div>
</div>

</html>

<!-- AJAX DO EDITAR PERFIL -->
<script type="text/javascript">
    $("#form-perfil").submit(function() {

        event.preventDefault();
        /*
        toda vez que submetemos uma página por um formulário, ela atualiza,
        o event.preventDefault() evita que a página seja atualizada,
        essa é a principal função do ajax
        */
        var formData = new FormData(this);

        $.ajax({
            url: "editar-perfil.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem-perfil').removeClass()

                if (mensagem.trim() == "Salvo com Sucesso!") {

                    $('#btn-fechar-perfil').click();
                    //window.location = "index.php?pagina=" + pag; //atualiza a página
                    /*não precisou colocar $pag, e sim apenas pag, pois é javascript,
                    e não php, e acima var pag = < ?php $pag ?>
                    */

                } else { //se não devolver "Salvo com Sucesso!", ou seja, se der errado

                    $('#mensagem-perfil').addClass('text-danger')
                }

                $('#mensagem-perfil').text(mensagem)


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

<!-- os scripts js para máscaras devem ser chamados no final, pois acima é carregada a página usuarios.php dentro da index.php, e mascaras.js trabalha com o elemento id="cpf", que é criado apenas em usuarios.php, portanto, não existe no começo de index.php -->
<!-- CDN para máscaras -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<!-- mascaras.js -->
<script type="text/javascript" src="../vendor/js/mascaras.js"></script>