<?php

//para projeto hospedado altera $url_sistema (por exemplo, pdv.hugocursos.com.br) e as variáveis de servidor (que não é mais o local)

//VARIÁVEIS GLOBAIS
$nome_sistema = "PDV Paiva";
$email_admin = "admin@gmail.com"; //caso não tenha um administrador cadastrado, cria automaticamente um com esse email
$url_sistema = "http://localhost/dashboard/www/pdv/";
/*
a variável acima tem que estar apontando para a raíz do projeto, para assim
os relatórios serem impressos corretamente, tem que ter a barra no final, pois 
é um diretório, e não um arquivo, tem que ter também http ou https de acordo com seu domínio
*/
$cnpj_sistema = "22.684.705/0001-08";
$url_site = "https://www.rapidim.shop";

$telefone_sistema = "(15) 99180-5895";
$endereco_sistema = "Rua X número 280 bairro centro - Sorocaba - SP";
$rodape_relatorios = "Rapidin 2022. Todos os direitos reservados";

//VARIÁVEIS SERVIDOR LOCAL
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'pdv';

//VARIÁVEIS DE CONFIGURAÇÃO DO SISTEMA

$estoque_minimo = 10; //menos de 10 unidades de um produto aponta para estoque baixo dele

$relatorio_pdf = 'Sim'; 
/*Se você utilizar "Sim", vai gerar relatórios com a biblioteca dompdf configurada para php 8.0, caso você use outra versão do php ou do dompdf pode dar errado, caso configure a variável como "Não" (ou qualquer outra coisa diferente de "Sim"), vai gerar o relatório html.
*/

$cabecalho_img_rel = 'Sim';  /* Se você optar por "Sim", os relatórios serão exibidos com uma imagem de cabeçalho, você terá de alterar o arquivo PSD para alterar as informações referentes à sua empresa, caso não queira, basta deixar em branco, e ele pegará os valores das variáveis globais declaradas acima, como $nome_sistema e $endereco_sistema */
$desconto_porcentagem = 'Sim'; /* Se essa variável receber "Sim", o desconto na tela do pdv será em porcentagem, caso contrário será em R$ */

$cupom_fiscal = 'Não'; /* Se você utilizar "Sim", ele vai apontar para a API que irá gerar o cupom fiscal (não configurado nesse curso, e necessita de API externa, um dos modelos pode ser encontrado em plugnotas.com.br), caso contrário ele gera o cupom não fiscal configurado no curso */

//imprimir etiqueta
$largura_codigo_barras = 3; //2 ou 3
$altura_codigo_barras = 50; //não menor que 20
$etiquetas_por_linha = 7; //7 etiquetas de código de barras por linha da página
$linhas_etiquetas_pagina = 17; //17 linhas de código de barras por página, com $etiquetas_por_linhas (7) por linha, usando esses valores temos 119 etiquetas por página

?>