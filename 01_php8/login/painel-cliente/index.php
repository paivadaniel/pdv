<?php

@session_start();

echo "Painel Cliente <br>";

if(@$_SESSION['nivel_usuario']!="Cliente") {
    echo "<script language='javascript'>window.location='../index.php'</script>";
}

?>

<a href="../logout.php">Sair</a>