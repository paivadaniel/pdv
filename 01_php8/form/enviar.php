<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php

    $nome = $_POST['name'];
    $email = $_POST['email'];
    $msg = $_POST['msg'];
    $assunto = "Email do site";
    $destinatario = "danielantunespaiva@gmail.com";

    $conteudo = utf8_decode('Nome: ' . $nome . "\r\n" .  "\r\n" . 'Email: ' . $email . "\r\n" .  "\r\n" . 'Mensagem: ' . $msg . "\r\n" .  "\r\n");

    $cabecalho = "From: " . $email;

    @mail($destinatario, $assunto, $conteudo, $cabecalho);

/*
try catch não funciona com a função mail, pois ela não resulta em um erro, e sim em um warning
e no try catch, tratamos erros, não warnings

    try {
        @mail($destinatario, $assunto, $conteudo, $cabecalho);
    } catch (Exception $e) {
        echo "Email não pode ser enviado em servidor local, hospede seu projeto e tente novamente. <br>" . $e;
    }

*/

    ?>
    <script>
        alert('Enviado com sucesso!');
    </script>
    <meta http-equiv="refresh" content="0; url=index.php">

</body>

</html>