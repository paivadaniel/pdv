<?php

require_once('../../conexao.php');

$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$id = $_POST['id'];

//SCRIPT PARA SUBIR FOTO NO BANCO

$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['imagem']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img); //['imagem'] refere-se ao elemento com name="imagem" em categorias.php, e ['name'] refere-se ao nome ("minha-foto.jpg", por exemplo) do arquivo do elemento 'imagem' 
//tudo que for espaço ou dois pontos substitui por traço

$caminho = '../../img/contas_pagar/' . $nome_img;

if (@$_FILES['imagem']['name'] == "") { //se não subiu imagem
    $imagem = "sem-foto.jpg";
} else { //se fez o upload de imagem
    $imagem = $nome_img;
}

//envio do arquivo para a pasta img

$imagem_temp = @$_FILES['imagem']['tmp_name'];
$ext = pathinfo($imagem, PATHINFO_EXTENSION);

//só pode fazer o upload de imagens em .png, .jgp, .jpeg e .gif, isso serve para evitar que o usuário suba outros tipos de arquivos maliciosos para o servidor
if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf') {
    move_uploaded_file($imagem_temp, $caminho);
} else {
    echo 'Extensão de Imagem não permitida!';
    exit();
}



if ($id == '') { //se o id não tiver sido criado, é inserção

    $query = $pdo->prepare("INSERT INTO contas_pagar SET descricao = :descricao, valor = :valor, arquivo = :arquivo");

    $query->bindValue(":descricao", $descricao);
    $query->bindValue(":valor", $valor);
    $query->bindValue(":arquivo", $imagem);

    $query->execute();
} else { //se o id já existir, é edição

    if ($imagem != 'sem-foto.jpg') {
        $query = $pdo->prepare("UPDATE categorias SET descricao = :descricao, valor = :valor, arquivo = :arquivo WHERE id = :id");

        $query->bindValue(":descricao", $descricao);
        $query->bindValue(":valor", $valor);
        $query->bindValue(":arquivo", $imagem);
    } else { //no caso de não ter imagem
        $query = $pdo->prepare("UPDATE contas_pagar SET descricao = :descricao, valor = :valor WHERE id = :id");
    }

    $query->bindValue(":nome", $nome);
    $query->bindValue(":id", $id);
    $query->execute();
}
echo "Salvo com Sucesso!";
