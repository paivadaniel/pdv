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
    $nome = "Daniel";
    $sobrenome = "Paiva";
    $idade = 33;
    $adiciona = 10;
    $numero = 5;

    //echo $idade . "<br>";
    /* comentário em bloco */
    echo $nome . " " . $sobrenome . " tem " . $idade . " anos.";

    $total = $idade + $adiciona;

    //com <br>
    echo "<br>" . $total . " com tag br.";

    //com <p>
    echo "<p>" . $total . " com tag p.";

    //tomada de decisão com if

    if ($idade >= 30 && $idade < 34) {
        echo "<p> Idade é maior ou igual a 30 e menor que 34";
    } else if ($idade >= 34) {
        echo "<p> Idade é maior ou igual a 34";
    } else {
        echo "<p> Idade é menor que 30";
    }

    //laços de repetição (for, foreach, while)
    for ($i = 0; $i <= $numero; $i++) {
        if ($i == 3) {
            echo "<p> o contador é igual a " . $i;
        }
        echo "<p> i = " . $i;
    }

    ?>

</body>

</html>