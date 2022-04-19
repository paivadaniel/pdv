<?php

//VARIÁVEIS GLOBAIS
$nome_sistema = "PDV Paiva";
$email_admin = "admin@gmail.com"; //caso não tenha um administrador cadastrado, cria automaticamente um com esse email
$url_sistema = "http://localhost/dashboard/www/pdv/";
/*
a variável acima tem que estar apontando para a raíz do projeto, para assim
os relatórios serem impressos corretamente, tem que ter a barra no final, pois 
é um diretório, e não um arquivo, tem que ter também http ou https de acordo com seu domínio
*/

$telefone_sistema = "(15) 99180-5895";
$endereco_sistema = "Rua X número 280 bairro centro - Sorocaba - SP";
$rodape_relatorios = "Rapidin 2022. Todos os direitos reservados";

//VARIÁVEIS SERVIDOR LOCAL
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'pdv';

//VARIÁVEIS DE CONFIGURAÇÃO DO SISTEMA
$relatorio_pdf = ''; 
/*Se você utilizar "Sim", vai gerar relatórios com a biblioteca dompdf configurada para php 8.0, caso você use outra versão do php ou do dompdf pode dar errado, caso configure a variável como "Não" (ou qualquer outra coisa diferente de "Sim"), vai gerar o relatório html.
*/

$cabecalho_img_rel = 'Sim';  /* Se você optar por "Sim", os relatórios serão exibidos com uma imagem de cabeçalho, você terá de alterar o arquivo PSD para alterar as informações referentes à sua empresa, caso não queira, basta deixar em branco, e ele pegará os valores das variáveis globais declaradas acima, como $nome_sistema e $endereco_sistema */


?>