<?php

require_once('config.php');

date_default_timezone_set('America/Sao_Paulo');

try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", "$usuario", "$senha");
/*tanto faz a ordem de host, dbname e utf 8, por exemplo:
mysql:dbname=$banco;host=$servidor;charset=utf8
*/
} catch (Exception $e) {
    echo "Não foi possivel conectar com o banco de dados! <p>" . $e;
}
