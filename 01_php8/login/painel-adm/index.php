<?php

require_once('../../conexao.php');

@session_start();

if (@$_SESSION['nivel_usuario'] != "Admin") {
    echo "<script language='javascript'>window.location='../index.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Administrador</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Usuários</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Logout
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><?php echo $_SESSION['nome_usuario']; ?></a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>

                </ul>
                <form class="d-flex" method="GET">
                    <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search" name="txtBuscar">
                    <button class="btn btn-outline-success" name="btn-buscar" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- <button class="btn btn-secondary mt-4" type="button" data-bs-toggle="modal" data-bs-target="#modalCadastrar">Novo usuário</button> -->

        <a href="index.php?funcao=novo" class="btn btn-secondary mt-4" type="button">Novo usuário</a>

        <?php

        $txtBuscar = '%' . @$_GET['txtBuscar'] . '%';

        $query = $pdo->prepare("SELECT * FROM usuarios WHERE nome LIKE :nome OR email LIKE :email");

        $query->bindValue(":nome", $txtBuscar);
        $query->bindValue(":email", $txtBuscar);
        $query->execute();

        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = @count($res);

        if ($total_reg > 0) {

        ?>

            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Senha</th>
                        <th scope="col">Nível</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }
                        $nome = $res[$i]['nome'];
                        $email = $res[$i]['email'];
                        $senha = $res[$i]['senha'];
                        $nivel = $res[$i]['nivel'];
                        $id = $res[$i]['id'];

                    ?>

                        <tr>
                            <td><?php echo $nome; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $senha; ?></td>
                            <td><?php echo $nivel; ?></td>
                            <td>
                                <a href="index.php?funcao=editar&id=<?php echo $id; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square text-primary me-2" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                    </svg>
                                </a>

                                <a href="index.php?funcao=deletar&id=<?php echo $id; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square text-danger" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </a>

                            </td>




                        </tr>


                <?php
                    }
                } else {
                    echo "<p class='mt-4'> Não há dados para serem exibidos. </p>";
                }

                ?>


                </tbody>
            </table>
    </div>

</body>


<div class="modal fade" id="modalCadastrar" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <?php

                if (@$_GET['funcao'] == 'editar') {
                    $titulo_modal = "Editar Registro";
                    $botao_modal = "btn-editar";


                    $query = $pdo->query("SELECT * FROM usuarios WHERE id = '$_GET[id]'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    $nome_ed = $res[0]['nome'];
                    $email_ed = $res[0]['email'];
                    $senha_ed = $res[0]['senha'];
                    $nivel_ed = $res[0]['nivel'];
                } else if (@$_GET['funcao'] == 'novo') {
                    $titulo_modal = "Inserir Registro";
                    $botao_modal = "btn-cadastrar";
                }
                ?>

                <h5 class="modal-title"><?php echo $titulo_modal; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST">

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="exampleInputEmail1">Nome</label>
                        <input type="text" class="form-control mt-1" id="exampleInputEmail1" name="nomeCad" aria-describedby="emailHelp" value="<?php echo @$nome_ed; ?>" required>

                    </div>

                    <div class="form-group mb-3">
                        <label for="exampleInputEmail1">Email </label>
                        <input type="email" class="form-control mt-1" id="exampleInputEmail1" name="emailCad" aria-describedby="emailHelp" value="<?php echo @$email_ed; ?>" required>
                        <small id="emailHelp" class="form-text text-muted">Nós não iremos compartilhar seu email com ninguém.</small>

                    </div>

                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1">Senha</label>
                        <input type="text" class="form-control mt-1" name="senhaCad" id="exampleInputPassword1" value="<?php echo @$senha_ed; ?>" required>
                        <!-- correto é type="password", com text dá para ver a senha sendo digitada -->
                    </div>

                    <div class="form-group mb-3">
                        <label for="nivelCad">Nível</label>
                        <select class="form-select mt-1" aria-label="Default select example" name="nivelCad" id="nivelCad">

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

                            <option <?php if (@$nivel_ed == 'Cliente') { ?> selected <?php } ?> value="Cliente">Cliente</option>
                            <option <?php if (@$nivel_ed == 'Admin') { ?> selected <?php } ?> value="Admin">Admin</option>
                            <option <?php if (@$nivel_ed == 'Vendedor') { ?> selected <?php } ?> value="Vendedor">Vendedor</option>
                            <option <?php if (@$nivel_ed == 'Tesoureiro') { ?> selected <?php } ?> value="Tesoureiro">Tesoureiro</option>
                        </select>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" name="<?php echo $botao_modal; ?>">Salvar</button>

                    <input type="hidden" value="<?php echo $email_ed ?>" name="emailAntigo">

                </div>

            </form>
        </div>
    </div>
</div>


<div class="modal" tabindex="-1" id="modalDeletar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Deseja Realmente Excluir Esse Registro?</p>
            </div>

            <div align="center">
                Cadastrado com sucesso!
            </div>

            <div class="modal-footer">
                <form method="POST">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-danger" name="btn-deletar">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>


</html>

<?php
if (isset($_POST['btn-cadastrar'])) {
//verifica se o email já existe no banco de dados
    $query_verif = $pdo->prepare("SELECT * FROM usuarios WHERE email=:email");

    $query_verif->bindValue(":email", $_POST['emailCad']);
    $query_verif->execute();

    $res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_verif = @count($res_verif);

    if ($total_reg_verif > 0) { //se o email já existir no banco de dados
        echo "<script language='javascript'>window.alert('Usuário já cadastrado!')</script>";
        exit();
    }

    $query = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, nivel) VALUES (:nome, :email, :senha, :nivel)");
    $query->bindValue(":nome", $_POST['nomeCad']);
    $query->bindValue(":email", $_POST['emailCad']);
    $query->bindValue(":senha", $_POST['senhaCad']);
    $query->bindValue(":nivel", $_POST['nivelCad']);
    $query->execute();

    echo "<script language='javascript'>window.alert('Cadastrado com Sucesso')</script>";
    echo "<script language='javascript'>window.location='index.php'</script>";
}
?>

<?php
if (isset($_POST['btn-editar'])) {

    if ($_POST['emailAntigo'] != $_POST['emailCad']) {

        $query_verif = $pdo->prepare("SELECT * FROM usuarios WHERE email=:email");

        $query_verif->bindValue(":email", $_POST['emailCad']);
        $query_verif->execute();

        $res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
        $total_reg_verif = @count($res_verif);

        if ($total_reg_verif > 0) {
            echo "<script language='javascript'>window.alert('Usuário já cadastrado!')</script>";
            exit();
        }
    }

    $query = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, nivel = :nivel WHERE id = :id");
    $query->bindValue(":nome", $_POST['nomeCad']);
    $query->bindValue(":email", $_POST['emailCad']);
    $query->bindValue(":senha", $_POST['senhaCad']);
    $query->bindValue(":nivel", $_POST['nivelCad']);
    $query->bindValue(":id", $_GET['id']);
    $query->execute();

    echo "<script language='javascript'>window.alert('Editado com Sucesso')</script>";
    echo "<script language='javascript'>window.location='index.php'</script>";
}
?>


<?php
if (isset($_POST['btn-deletar'])) {

    $query = $pdo->query("DELETE from usuarios WHERE id = '$_GET[id]'");

    echo "<script language='javascript'>window.location='index.php'</script>";
}
?>




<?php

if (@$_GET['funcao'] == 'novo') {
?>

    <script>
        var myModal = new bootstrap.Modal(document.getElementById('modalCadastrar'), {
            keyboard: false
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
            keyboard: false
        })
        //a modalCadastrar serve tanto para cadastrar quanto para deletar, não foi criada uma modalEditar
        myModal.show();
    </script>


<?php
}

?>


<?php

if (@$_GET['funcao'] == 'deletar') {
?>

    <script>
        var myModal = new bootstrap.Modal(document.getElementById('modalDeletar'), {
            keyboard: false
        })

        myModal.show();
    </script>


<?php
}

?>