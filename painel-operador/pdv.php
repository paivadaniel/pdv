<?php

@session_start();
$id_usuario = $_SESSION['id_usuario']; //tem que vir antes de verifica_permissao.php, pois esse utiliza a variável de sessão $_SESSION['id_usuario']

require_once('../conexao.php');
require_once('verifica_permissao.php');

$pag = 'pdv';

//VERIFICA SE O CAIXA ESTÁ ABERTO, SE NÃO TIVER, NÃO ENTRA NO PDV
$query = $pdo->query("SELECT * from caixa WHERE operador = '$id_usuario' AND status = 'Aberto'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg == 0) {
  echo "<script language='javascript'>window.location='index.php'</script>";
}

?>

<!DOCTYPE html>
<html class="wide wow-animation" lang="pt-br">

<head>
  <title><?php echo $nome_sistema ?></title>
  <meta name="format-detection" content="telephone=no">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

  <!-- favicon -->
  <link rel="shortcut icon" href="../img/favicon.ico" />

  <!--bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <!--bootstrap javascript-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <link rel="stylesheet" href="../vendor/css/telapdv.css">

</head>

<body>


  <div class='checkout'>
    <div class="row">
      <div class="col-md-5 col-sm-12">
        <div class='order py-2'>
          <p class="background">LISTA DE PRODUTOS</p>

          <span id="listar">

          </span>


        </div>
      </div>



      <div id='payment' class='payment col-md-7'>

        <div class="row py-2">
          <div class="col-md-7">
            <form method="POST" id="form-buscar">

              <p class="background">CÓDIGO DE BARRAS</p>
              <input type="text" class="form-control form-control-lg" id="codigo" name="codigo" placeholder="Código de barras" required="">

              <p class="background mt-3">PRODUTO</p>
              <input type="text" class="form-control form-control-md" id="produto" name="produto" placeholder="Produto" required="">

              <p class="background mt-3">DESCRIÇÃO</p>
              <input type="text" class="form-control form-control-md" id="descricao" name="descricao" placeholder="Descrição" required="">

              <div class="row">
                <div class="col-md-6">

                  <p class="background mt-3">QUANTIDADE</p>
                  <input type="text" class="form-control form-control-md" id="quantidade" name="quantidade" placeholder="Quantidade" required="">

                  <p class="background mt-2">VALOR UNITÁRIO</p>
                  <input type="text" class="form-control form-control-md" id="valor_unitario" name="valor_unitario" placeholder="Valor Unitário" required="">

                  <p class="background mt-2">ESTOQUE</p>
                  <input type="text" class="form-control form-control-md" id="estoque" name="estoque" placeholder="Estoque" required="">

            </form>
          </div>

          <div class="col-md-6 mt-3">

            <img id="foto" src="" alt="produto" width="100%">

          </div>
        </div>

      </div>

      <div class="col-md-5">
        <p class="background">TOTAL DO ITEM</p>
        <input type="text" class="form-control form-control-md" id="total_item" name="total_item" placeholder="Total do Item" required="">

        <p class="background mt-3">SUBTOTAL</p>
        <input type="text" class="form-control form-control-md" id="subtotal" name="subtotal" placeholder="Subtotal" required="">

        <p class="background mt-3">DESCONTO</p>
        <input type="text" class="form-control form-control-md" id="desconto" name="desconto" placeholder="Desconto" required="">

        <p class="background mt-3">TOTAL DA COMPRA</p>
        <input type="text" class="form-control form-control-md" id="total_compra" name="total_compra" placeholder="Total da Compra" required="">

        <p class="background mt-3">VALOR RECEBIDO</p>
        <input type="text" class="form-control form-control-md" id="valor_recebido" name="valor_recebido" placeholder="Valor Recebido" required="">

        <p class="background mt-3">TROCO</p> <!-- input com id="troco" é colocado como hide no javascript abaixo -->
        <input type="text" class="form-control form-control-md" id="troco" name="troco" placeholder="Troco" required="">



      </div>
    </div>





  </div>


  </div>
  </div>

</body>

</html>

<!-- CDN para máscaras -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<!-- mascaras.js -->
<script type="text/javascript" src="../vendor/js/mascaras.js"></script>

<!-- SCRIPT PARA MODAL DE ABERTURA E FECHAMENTO -->
<script>
  $(document).ready(function() {
    listarProdutos();
    document.getElementById('codigo').focus();
    document.getElementById('quantidade').value = '1';
    $('#foto').attr('src', '../img/produtos/sem-foto.jpg');

  });
</script>

<!--AJAX PARA BUSCAR DADOS PARA OS INPUTS -->

<script type="text/javascript">
  $('#codigo').keyup(function() {
    /*
    função change só funciona para campo select, se for para input tem que ser keyup
    */
    buscarDados();
  });
</script>

<script type="text/javascript">
  var pag = "<?= $pag ?>";

  function buscarDados() {
    $.ajax({
      url: pag + '/buscar-dados.php',
      method: 'POST',
      data: $('#form-buscar').serialize(),
      dataType: "html",

      success: function(result) {
        console.log(result);

        var array = result.split("&-/");
        var estoque = array[0];
        var nome = array[1];
        var descricao = array[2];
        var foto = array[3];
        var valor_venda = array[4];
        var subtotal = array[5];

        if (nome.trim() != 'Código não cadastrado') {

          document.getElementById('estoque').value = estoque;
          document.getElementById('produto').value = nome;
          document.getElementById('descricao').value = descricao;
          document.getElementById('valor_unitario').value = valor_venda;

          if (foto.trim() === "") { //se o caminho da imagem não existir
            $('#foto').attr('src', '../img/produtos/sem-foto.jpg'); //não vai funcionar para o primeiro item, por isso colocamos assim que a página estiver carregada para já atribuir o sem-foto.jpg

          } else {
            $('#foto').attr('src', '../img/produtos/' + foto); //atribui o segundo argumento ao primeiro, ou seja, salva em src o caminho digitado no segundo argumento

          }

          var audio = new Audio('../img/barCode.wav');
          audio.addEventListener('canplaythrough', function() {
            audio.play();

          valor_format = "R$ " + valor_venda.replace(".", ",");
          document.getElementById('total_item').value = valor_format;

          //formatando subtotal
          subtotal_format = "R$ " + subtotal.replace(".", ",");

          array_subtotal = subtotal_format.split(",");

          if (array_subtotal.length == 1 && subtotal != "") {
            subtotal_format = subtotal_format + ",00";
          }

          document.getElementById('subtotal').value = subtotal_format;
          

          



          //formatando total venda (corrigir)
          total_venda_format = "R$ " + total_venda.replace(".", ",");

          array_total_venda = total_venda_format.split(",");

          if (array_total_venda.length == 1 && total_venda != "") {
            total_venda_format = total_venda_format + ",00";
          }

          document.getElementById('total_venda').value = total_venda_format;














          document.getElementById('codigo').value = "";

          listarProdutos();


          });
        }

      }

    });

  }
</script>



<!--AJAX PARA MOSTRAR OS PRODUTOS DA VENDA -->

<script type="text/javascript">
  var pag = "<?= $pag ?>";

  function listarProdutos() {
    $.ajax({
      url: pag + '/listar-produtos.php',
      method: 'POST',
      data: $('#form-buscar').serialize(),
      dataType: "html",

      success: function(result) {
        $("#listar").html(result); //joga o html do resultado do AJAX na div listar



      }

    });

  }
</script>