<?php

//VERIFICA PERMISSÃO DO USUÁRIO PARA ACESSAR painel-adm/index.php
//para evitar que alguém digite esse endereço direto na barra do navegador e entre
if($_SESSION['nivel_usuario'] != "Tesoureiro") {
    echo "<script language='javascript'>window.location='../index.php'</script>";
}

?>