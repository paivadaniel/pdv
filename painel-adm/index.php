<?php

require_once('../conexao.php');
@session_start();

//VERIFICA PERMISSÃO DO USUÁRIO PARA ACESSAR painel-adm/index.php
//para evitar que alguém digite esse endereço direto na barra do navegador e entre
if($_SESSION['nivel_usuario'] != "Admin") {
    echo "<script language='javascript'>window.location='../index.php'</script>";
}

//VARIÁVEIS DO MENU ADMINISTRATIVO
$menu1 = 'home';
$menu2 = 'usuarios';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin</title>

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

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php?pagina=<?php echo $menu1; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?pagina=<?php echo $menu2; ?>">Usuários</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
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
                                    <?php echo $_SESSION['nome_usuario'];                                                                                                                                                            ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                    <li><a class="dropdown-item" href="#">Editar Perfil</a></li>
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
    } else {
        //caso não for nenhuma das páginas do GET, e tiver algum lixo nele, carrega a home.php
        require_once($menu1 . '.php');
    }

    ?>
</div>


</html>


<!-- os scripts js para máscaras devem ser chamados no final, pois acima é carregada a página usuarios.php dentro da index.php, e mascaras.js trabalha com o elemento id="cpf", que é criado apenas em usuarios.php, portanto, não existe no começo de index.php -->
    <!-- CDN para máscaras --> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

    <!-- mascaras.js -->
    <script type="text/javascript" src="../vendor/js/mascaras.js"></script>
