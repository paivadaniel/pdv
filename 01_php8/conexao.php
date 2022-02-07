<?php	

require_once('config.php');

//definir data e hora com base no local selecionado, no caso, São Paulo
date_default_timezone_set('America/Sao_Paulo');


try {
    $pdo = new PDO("mysql:dbname=$banco;host=$servidor",$usuario, $senha); //banco de dados, host, usuário, senha
} catch (Exception $e) {
    echo "Erro ao conectar com o banco de dados." . "<br>" . $e;
}

?>