<?php 


include('../../conexao.php');

$id = $_GET['id'];

//BUSCAR AS INFORMAÇÕES DO PEDIDO
$res = $pdo->query("SELECT * from vendas where id = '$id' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$hora = $dados[0]['hora'];
$total = $dados[0]['total'];
$total_pago = $dados[0]['total_pago'];
$tipo_pgto = $dados[0]['tipo_pgto'];
$status = $dados[0]['status'];
$pago = $dados[0]['pago'];
$troco = $dados[0]['troco'];
$cliente = $dados[0]['cliente'];
$obs = $dados[0]['obs'];
$data = $dados[0]['data'];
$entrega_fixa = $dados[0]['entrega_fixa'];

$data2 = implode('/', array_reverse(explode('-', $data)));

$res = $pdo->query("SELECT * from clientes where cpf = '$cliente' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $dados[0]['nome'];
$rua = $dados[0]['rua'];
$numero = $dados[0]['numero'];
$bairro = $dados[0]['bairro'];
$cidade = $dados[0]['cidade'];
$estado = $dados[0]['estado'];
$cep = $dados[0]['cep'];
?>


<link rel="stylesheet" type="text/css" href="comprovante.css">
<link rel="stylesheet" type="text/css" href="../../css/style.css">
<link rel="stylesheet" type="text/css" href="../../css/fonts.css">


<table class="printer-ticket">

	<tr>
		<th class="title" colspan="3"><?php echo $nome_loja ?></th>

	</tr>
	<tr>
		<th colspan="3"><?php echo $data2 ?> - <?php echo $hora ?></th>
	</tr>
	<tr>
		<th colspan="3">
			<?php echo $nome_cliente ?> <br />
			<?php echo $cliente ?>
		</th>
	</tr>
	<tr>
		<th class="ttu" colspan="3">
			Cupom não fiscal
		</th>
	</tr>
	
	<tbody>

		<?php 

		$res = $pdo->query("SELECT * from carrinho where id_venda = '$id' order by id asc");
		$dados = $res->fetchAll(PDO::FETCH_ASSOC);
		$linhas = count($dados);

		$sub_tot;
		for ($i=0; $i < count($dados); $i++) { 
			foreach ($dados[$i] as $key => $value) {
			}

			$id_produto = $dados[$i]['id_produto']; 
			$quantidade = $dados[$i]['quantidade'];
			$id_carrinho = $dados[$i]['id'];


			$res_p = $pdo->query("SELECT * from produtos where id = '$id_produto' ");
			$dados_p = $res_p->fetchAll(PDO::FETCH_ASSOC);
			$nome_produto = $dados_p[0]['nome'];  
			$valor = $dados_p[0]['valor'];
			$adc_sabor = $dados_p[0]['adicional'];

			$total_item = $valor * $quantidade;


			
				//verificar se o produto é pizza com mais de um sabor
				$total_sab = 0;
				if($adc_sabor == 'Sim'){
					

					$res_sab = $pdo->query("SELECT * from sabores_itens where id_car = '$id_carrinho'");
					$dados_sab = $res_sab->fetchAll(PDO::FETCH_ASSOC);

					for ($i_sab=0; $i_sab < count($dados_sab); $i_sab++) { 
						foreach ($dados_sab[$i_sab] as $key => $value) {
						}

						$id_sab = $dados_sab[$i_sab]['id_sab'];
						$id_item_sab = $dados_sab[$i_sab]['id'];
						$res2_sab = $pdo->query("SELECT * from sabores where id = '$id_sab'");
						$dados2_sab = $res2_sab->fetchAll(PDO::FETCH_ASSOC);
						$nome_sab = $dados2_sab[0]['nome'];
						$valor_sab = $dados2_sab[0]['valor'];
						@$total_sab = @$total_sab + $valor_sab;

						if(@count($dados_sab) > 1){
							if($i_sab == 0){
							@$nome_sab2 = @$nome_sab2 .''.$nome_sab .' e ';
							}else{
								@$nome_sab2 = @$nome_sab2 .''.$nome_sab;
							}
						}else{
							@$nome_sab2 = @$nome_sab;
						}
						

						

					}


				}else{
					@$nome_sab2 = "";
				}

				


			?>

			<tr>
				


				<?php 

				$total_adc = 0;
				$nome_adc2 = "";
				$nome_adc = "";
				$res_adc = $pdo->query("SELECT * from adicionais_itens where id_car = '$id_carrinho'");
				$dados_adc = $res_adc->fetchAll(PDO::FETCH_ASSOC);

				if(@count($dados_adc)>0){

				
					for ($i_adc=0; $i_adc < count($dados_adc); $i_adc++) { 
						foreach ($dados[$i_adc] as $key => $value) {
						}

						$id_adc = $dados_adc[$i_adc]['id_adc'];
						$id_item_adc = $dados_adc[$i_adc]['id'];
						$res2 = $pdo->query("SELECT * from adicionais where id = '$id_adc'");
						$dados2 = $res2->fetchAll(PDO::FETCH_ASSOC);
						$nome_adc = $dados2[0]['nome'];
						$valor_adc = $dados2[0]['valor'];
						@$total_adc = @$total_adc + $valor_adc;

						$valor_adc = number_format( $valor_adc , 2, ',', '.');

						if(@count($dados_adc) > 1){
							if($i_adc == 0){
							@$nome_adc2 = @$nome_adc2 .' + '.$nome_adc .' + ';
							}else{
								@$nome_adc2 = @$nome_adc2 .''.$nome_adc;
							}
						}else{
							@$nome_adc2 = ' + '. @$nome_adc;
						}


						?>

						

				<?php } }else{
					@$nome_adc2 = "";
				}  ?>

					<td><?php echo $quantidade ?> - <?php echo $nome_produto ?> 
						<?php if(@$mostrar_adicional == 'Sim'){ ?> 
							:  <?php echo @$nome_adc2 ?> <br><u><i><?php echo @$nome_sab2 ?></u></i><br>
						<?php } ?>

					</td>
				



				<td align="right">R$ <?php

				@$total_item = $total_item + @$total_adc + @$total_sab;
				@$sub_tot = @$sub_tot + @$total_item;
				$sub_total = $sub_tot;
				$total = $sub_total + $entrega_fixa;

				$sub_total = number_format( $sub_total , 2, ',', '.');
				$total_item = number_format( $total_item , 2, ',', '.');
				$total = number_format( $total , 2, ',', '.');

				echo $total_item ;
				?></td>
			</tr>

		<?php } ?>
		
	</tbody>
	<tfoot>

		<tr>
			<td colspan="3" class="cor">
				----------------------------------------------------------------------------------
			</td>
		</tr>

		
		<tr>
			<td colspan="2">Sub-total</td>
			<td align="right">R$ <?php echo @$sub_total ?></td>
		</tr>
		<tr>
			<td colspan="2">Taxa de Entrega</td>
			<td align="right">R$ <?php echo $entrega_fixa ?></td>
		</tr>
		
		<tr>
			<td colspan="2">Total</td>
			<td align="right">R$ <?php echo $total ?></td>
		</tr>

		<tr>
			<td colspan="2">Total Pago</td>
			<td align="right">R$ <?php echo $total_pago ?></td>
		</tr>

		<tr>
			<td colspan="2">Troco</td>
			<td align="right">R$ <?php echo $troco ?></td>
		</tr>

		<tr>
			<td colspan="3" class="cor">
				----------------------------------------------------------------------------------
			</td>
		</tr>

		<tr>
			<td align="center" class="ttu" colspan="3">
				pagamento e entrega
			</td>

		</tr>

		<tr>
			<td colspan="3" class="cor">
				----------------------------------------------------------------------------------
			</td>
		</tr>

		<tr>
			<td colspan="2">Forma de Pagamento</td>
			<td align="right"><?php echo $tipo_pgto ?></td>
		</tr>
		<tr>
			<td colspan="2">Pago</td>
			<td align="right"><?php echo $pago ?></td>
		</tr>

		<tr>
			<td colspan="3" class="cor">
				----------------------------------------------------------------------------------
			</td>
		</tr>

		<tr>
			<td align="center" colspan="3">Endereço Entrega - <?php echo $rua .' Nº '. $numero .' Bairro '. $bairro;  ?></td>

			
			
		</tr>

		<tr>

			<td align="center" colspan="3"><?php echo $cidade .' - '. $estado .'   CEP '. $cep;  ?></td>

		</tr>
		

		<tr>
			<td colspan="3" class="cor">
				----------------------------------------------------------------------------------
			</td>
		</tr>

		<tr>
			<td colspan="3" align="center">
				<b>Pedido: <?php echo $id ?></b>
				<?php if($obs != ""){ ?>
					<p><b>OBS: </b><?php echo $obs ?></p>
				<?php } ?>
				
			</td>
		</tr>

		<tr>
			<td colspan="3" class="cor">
				----------------------------------------------------------------------------------
			</td>
		</tr>
		
		<tr>
			<td colspan="3" align="center">
				www.hugocursos.com.br
			</td>
		</tr>
	</tfoot>
</table>