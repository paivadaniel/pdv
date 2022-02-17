<?php

require_once('../../conexao.php');

$nome = $_POST['nome'];
$id = $_POST['id'];

//edição
$antigoNome = $_POST['antigoNome'];

//evitar nome categoria duplicado
if (@$antigoNome != $nome) {
    /*quando $antigoNome não existir, ou seja, for uma inserção, vai cair aqui também, já que $antigoNome será diferente de $nome, este último existe
    */

    $query_verif = $pdo->prepare("SELECT * FROM categorias WHERE nome = :nome");
    $query_verif->bindValue(":nome", $nome);
    $query_verif->execute();

    $res_verif = $query_verif->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_verif = @count($res_verif);

    if ($total_reg_verif > 0) {
        echo "Nome já cadastrado";
        exit();
    }
}



//SCRIPT PARA SUBIR FOTO NO BANCO

$nome_img = date('d-m-Y H:i:s') . '-'. @$_FILES['imagem']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img); //['imagem'] refere-se ao elemento com name="imagem" em categorias.php, e ['name'] refere-se ao nome ("minha-foto.jpg", por exemplo) do arquivo do elemento 'imagem' 
//tudo que for espaço ou dois pontos substitui por traço

$caminho = '../../img/categorias/' . $nome_img;

if (@$_FILES['imagem']['name'] == "") { //se não subiu imagem
    $imagem = "sem-foto.jpg";
} else { //se fez o upload de imagem
    $imagem = $nome_img;
}

//envio do arquivo para a pasta img

$imagem_temp = @$_FILES['imagem']['tmp_name'];
$ext = pathinfo($imagem, PATHINFO_EXTENSION);

//só pode fazer o upload de imagens em .png, .jgp, .jpeg e .gif, isso serve para evitar que o usuário suba outros tipos de arquivos maliciosos para o servidor
if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') {
    move_uploaded_file($imagem_temp, $caminho);
} else {
    echo 'Extensão de Imagem não permitida!';
    exit();
}



if ($id == '') { //se o id não tiver sido criado, é inserção

    $query = $pdo->prepare("INSERT INTO categorias SET nome = :nome, foto = :foto");

    $query->bindValue(":nome", $nome);
    $query->bindValue(":foto", $imagem);

    $query->execute();
} else { //se o id já existir, é edição

    if ($imagem != 'sem-foto.jpg') {
        $query = $pdo->prepare("UPDATE categorias SET nome = :nome, foto = :foto WHERE id = :id");

        $query->bindValue(":nome", $nome);
        $query->bindValue(":foto", $imagem);
    } else {
        $query = $pdo->prepare("UPDATE categorias SET nome = :nome WHERE id = :id");

    }

    $query->bindValue(":nome", $nome);
    $query->bindValue(":id", $id);
    $query->execute();
}
echo "Salvo com Sucesso!";
