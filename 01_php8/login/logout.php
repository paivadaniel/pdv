<?php
@session_start();
/*
tenho que startar a sessão mesmo na página de logout
isso para depois que for redirecionado para a index.php,
se acessar a url painel-adm/index.php manualmente
não ficarem os dados com o nome do usuário e nível no cache:

echo "Nome do usuário: " . $_SESSION['nome_usuario'] . "<br> Nível do usuário: " . $_SESSION['nivel_usuario'];

isso será muito útil quando um usuário tentar acessar uma url digitando-a
na barra de endereços e não tiver permissão para acessá-la
*/

@session_destroy();

echo "<script language='javascript'>window.location='index.php'</script>";

?>