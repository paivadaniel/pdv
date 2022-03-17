<?php

require_once('../../conexao.php');

@session_start();

$id_usuario = $_SESSION['id_usuario'];

$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg > 0) {
    $pago = $res[0]['pago'];

    if ($pago == 'Sim') {
        echo 'Essa conta já está paga, você não pode editá-la';
        /*
    fazendo isso não tem como o usuário digitar o id na url e abrir o modal da conta para editá-la,
    mesmo desabilitando mostrar o botão de edição   
    */
        exit();
    }
}

//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['arquivo']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img); //['arquivo'] refere-se ao elemento com name="arquivo" em contas_pagar.php, e ['name'] refere-se ao nome ("minha-foto.jpg", por exemplo) do arquivo do elemento 'imagem' 
//tudo que for espaço ou dois pontos substitui por traço

$caminho = '../../img/contas_pagar/' . $nome_img;

if (@$_FILES['arquivo']['name'] == "") { //se não subiu imagem
    $imagem = "sem-foto.jpg";
} else { //se fez o upload de imagem
    $imagem = $nome_img;
}

//envio do arquivo para a pasta img

$imagem_temp = @$_FILES['arquivo']['tmp_name'];
$ext = pathinfo($imagem, PATHINFO_EXTENSION);

//só pode fazer o upload de imagens em .png, .jgp, .jpeg e .gif, isso serve para evitar que o usuário suba outros tipos de arquivos maliciosos para o servidor
if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf') {
    move_uploaded_file($imagem_temp, $caminho);
} else {
    echo 'Extensão de Imagem não permitida!';
    exit();
}



if ($id == '') { //se o id não tiver sido criado, é inserção

    $query = $pdo->prepare("INSERT INTO contas_pagar SET pago = 'Não', data = curDate(), usuario = '$id_usuario', descricao = :descricao, valor = :valor, arquivo = :arquivo");

    $query->bindValue(":descricao", $descricao);
    $query->bindValue(":valor", $valor);
    $query->bindValue(":arquivo", $imagem);

    $query->execute();
} else { //se o id já existir, é edição

    if ($imagem != 'sem-foto.jpg') { //se já tiver foto
        $query = $pdo->prepare("UPDATE contas_pagar SET usuario = '$id_usuario', descricao = :descricao, valor = :valor, arquivo = :arquivo WHERE id = :id");
        //campos pago e data não entram na edição

        $query->bindValue(":descricao", $descricao);
        $query->bindValue(":valor", $valor);
        $query->bindValue(":arquivo", $imagem);
    } else { //no caso de não ter imagem (sem-foto.jpg)
        $query = $pdo->prepare("UPDATE contas_pagar SET usuario = '$id_usuario', descricao = :descricao, valor = :valor WHERE id = :id");
    }

    $query->bindValue(":descricao", $descricao);
    $query->bindValue(":valor", $valor);
    $query->bindValue(":id", $id);

    $query->execute();
}
echo "Salvo com Sucesso!";
