<?php

include('../conexao.php');

$id = $_GET['id'];

//BUSCAR AS INFORMAÇÕES DO PEDIDO
$res = $pdo->query("SELECT * from vendas where id = '$id' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$data = $dados[0]['data'];
$hora = $dados[0]['hora'];

$total_venda = $dados[0]['valor'];

$valor_recebido = $dados[0]['valor_recebido'];
$tipo_pgto = $dados[0]['forma_pgto'];
$status = $dados[0]['status'];
$troco = $dados[0]['troco'];
$desconto = $dados[0]['desconto'];
$id_operador = $dados[0]['operador'];


$data2 = implode('/', array_reverse(explode('-', $data)));

$res = $pdo->query("SELECT * from forma_pgtos where codigo = '$tipo_pgto'");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$forma_pgto = $dados[0]['nome'];


$res = $pdo->query("SELECT * from usuarios where id = '$id_operador'");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$nome_operador = $dados[0]['nome'];


?>

<style type="text/css">
	* {
		margin: 0px;
		padding: 5px;
		background-color: #f7fccc;

	}

	.text {

		text-align: center;
	}

	.ttu {
		text-transform: uppercase;
		font-weight: bold;
		font-size: 1.2em;
	}

	.printer-ticket {
		display: table !important;
		width: 100%;
		max-width: 400px;
		font-weight: light;
		line-height: 1.3em;
		padding: 5px;
		font-family: Tahoma, Geneva, sans-serif;
		font-size: 12px;



	}

	th {
		font-weight: inherit;
		padding: 5px;
		text-align: center;
		border-bottom: 1px dashed #BCBCBC;
	}






	.cor {
		color: #BCBCBC;
	}


	.title {
		font-size: 1.5em;
	}
</style>

<link rel="stylesheet" type="text/css" href="fonts.css">

<table class="printer-ticket">

	<tr>
		<th class="title" colspan="3"><?php echo $nome_sistema ?></th>

	</tr>
	<tr>
		<th colspan="3"><?php echo $data2 ?> - <?php echo $hora ?></th>
	</tr>
	<tr>
		<th colspan="3">
			Endereço: <?php echo $endereco_sistema; ?> <br />
			Telefone: <?php echo $telefone_sistema; ?> <br />
			CNPJ: <?php echo $cnpj_sistema; ?>
			<!-- html pode dar quantos espaçamentos forem depois dos dois pontos (ou de qualquer outro caractere), ele só vai considerar um único espaçamento -->
		</th>
	</tr>
	<tr>
		<th class="ttu" colspan="3" style="padding-top:15px; padding-bottom:15px;">
			Cupom não fiscal <?php

				if($status == 'Cancelada') {
					echo ' -  Venda Cancelada';
					$total = number_format($total_venda, 2, ',', '.'); //mostra aqui pois se a venda for cancelada, e o total não estiver aqui, não vai aparecer o total da venda no comprovante da venda cancelada
				}

			?>
		</th>
	</tr>

	<tbody>

		<?php

		$res = $pdo->query("SELECT * from itens_venda where venda = '$id' order by id asc"); //id passado via GET
		$dados = $res->fetchAll(PDO::FETCH_ASSOC);
		$linhas = count($dados);

		$sub_tot;
		for ($i = 0; $i < count($dados); $i++) {
			foreach ($dados[$i] as $key => $value) {
			}

			$id_produto = $dados[$i]['produto'];
			$quantidade = $dados[$i]['quantidade'];
			$id_item = $dados[$i]['id'];


			$res_p = $pdo->query("SELECT * from produtos where id = '$id_produto' ");
			$dados_p = $res_p->fetchAll(PDO::FETCH_ASSOC);
			$nome_produto = $dados_p[0]['nome'];
			$valor = $dados_p[0]['valor_venda'];

			$total_item = $valor * $quantidade;


		?>

			<tr>


				<td colspan="2" width="50%"><?php echo $quantidade ?> - <?php echo $nome_produto ?>


				</td>




				<td align="right">R$ <?php

										@$total_item;
										@$sub_tot = @$sub_tot + @$total_item;
										$sub_total = $sub_tot;
										$total = $sub_total;

										$sub_total = number_format($sub_total, 2, ',', '.');
										$total_item = number_format($total_item, 2, ',', '.');

										echo $total_item;
										?></td>
			</tr>

		<?php } ?>

		<?php
		$valor_recebido = number_format($valor_recebido, 2, ',', '.');
		$troco = number_format($troco, 2, ',', '.');
		?>

	</tbody>
	<tfoot>

		<tr>
			<td colspan="3" class="cor">
				--------------------------------------------------------------------------------------------------------------------------------------------------------------
			</td>
		</tr>


		<tr>
			<td colspan="2">Sub-total</td>
			<td align="right">R$ <?php echo @$sub_total ?></td>
		</tr>
		<tr>
			<td colspan="2">Desconto</td>
			<td align="right"><?php echo $desconto ?></td>
		</tr>

		<tr>
			<td colspan="2">Total</td>
			<td align="right">R$ <?php echo $total ?></td>
		</tr>

		<tr>
			<td colspan="2">Total Pago</td>
			<td align="right">R$ <?php echo $valor_recebido ?></td>
		</tr>

		<tr>
			<td colspan="2">Troco</td>
			<td align="right">R$ <?php echo $troco ?></td>
		</tr>

		<tr>
			<td colspan="3" class="cor">
				--------------------------------------------------------------------------------------------------------------------------------------------------------------
			</td>
		</tr>

		<tr>
			<td align="center" class="ttu" colspan="3">
				Forma de pagamento
			</td>

		</tr>

		<tr>
			<td colspan="3" class="cor">
				--------------------------------------------------------------------------------------------------------------------------------------------------------------
			</td>
		</tr>

		<tr>
			<td colspan="2">Forma de Pagamento</td>
			<td align="right"><?php echo $forma_pgto ?></td>
		</tr>
		<tr>
			<td colspan="2">Operador</td>
			<td align="right"><?php echo $nome_operador ?></td>
		</tr>


		<tr>
			<td colspan="3" class="cor">
				--------------------------------------------------------------------------------------------------------------------------------------------------------------

			</td>
		</tr>

		<tr>
			<td colspan="3" align="center">
				<?php echo $url_site ?>
			</td>
		</tr>
	</tfoot>
</table>