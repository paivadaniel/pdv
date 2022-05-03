<?php
require_once("../conexao.php");

$codigo = $_GET['codigo'];

require_once("classe_barras.php");

//style css abaixo é para remover texto que aparece na parte de cima e de baixo da página ao clicar em imprimir

?>

<style>
    @page {
        margin: 15px;

    }

    .margem {
        margin-right: 15px;
        display: inline-block;
        font-size: 14px;
        text-align: center;
        letter-spacing: 2px;

    }

    .linha_codigo {
        margin-bottom: 15px;
    }
</style>

<?php
for ($j = 0; $j < $linhas_etiquetas_pagina; $j++) { ?>

    <div class="linha_codigo">
    <?php
for ($i = 0; $i < $etiquetas_por_linha; $i++) { ?>

        <span class="margem">

            <?php
            geraCodigoBarra($codigo, $largura_codigo_barras, $altura_codigo_barras);
            ?>
            <br>
            <?php echo $codigo; ?>
        </span>

    <?php } //fechamento do for com $i ?>

    </div>

    <?php } //fechamento do for com $j?>