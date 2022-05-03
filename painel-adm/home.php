<?php

@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

$hoje = date('Y-m-d');
$mesAtual = Date('m');
$anoAtual = Date('Y');
$dataInicioMes = $anoAtual . "-" . $mesAtual . "-01";

$query = $pdo->query("SELECT * FROM produtos");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$totalProdutos = @count($res);

$query = $pdo->query("SELECT * FROM fornecedores");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$totalFornecedores = @count($res);

$query = $pdo->query("SELECT * FROM produtos WHERE estoque < $estoque_minimo");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$totalEstoqueBaixo = @count($res);

$query = $pdo->query("SELECT * FROM vendas WHERE data = curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$totalVendasDia = @count($res);



$query = $pdo->query("SELECT * FROM contas_receber where vencimento = curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_receber_hoje = @count($res);

$query = $pdo->query("SELECT * FROM contas_receber where vencimento < curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_receber_vencidas = @count($res);

//estatísticas mensais

$entradasM = 0;
$saidasM = 0;
$saldoM = 0;

$queryM = $pdo->query("SELECT * FROM movimentacoes where data >= '$dataInicioMes' and data <= curDate()"); //nome é o campo da tabela
$resM = $queryM->fetchAll(PDO::FETCH_ASSOC);
$total_regM = @count($resM);

if ($total_regM > 0) {

	for ($i = 0; $i < $total_regM; $i++) {
		foreach ($resM[$i] as $key => $value) {
		} //fechamento do foreach


		if ($resM[$i]['tipo'] == 'Entrada') {
			$entradasM += $resM[$i]['valor'];
		} else if ($resM[$i]['tipo'] == 'Saída') {
			$saidasM += $resM[$i]['valor'];
		}

		$saldoM = $entradasM - $saidasM;

		$entradasMesF = number_format($entradasM, 2, ',', '.');
		$saidasMesF = number_format($saidasM, 2, ',', '.');
		$saldoMesF = number_format($saldoM, 2, ',', '.');

		if ($saldoM > 0) {
			$classeSaldoM = 'text-success';
		} else {
			$classeSaldoM = 'text-danger';
		}
	}
}


$query = $pdo->query("SELECT * FROM contas_pagar where data >= '$dataInicioMes' and data <= curDate()");
$resPagar = $query->fetchAll(PDO::FETCH_ASSOC);
$totalContasPagarMes = @count($resPagar);

$query = $pdo->query("SELECT * FROM contas_receber where data >= '$dataInicioMes' and data <= curDate()");
$resReceber = $query->fetchAll(PDO::FETCH_ASSOC);
$totalContasReceberMes = @count($resReceber);

$pagarMes = 0;
$receberMes = 0;

for ($i = 0; $i < $totalContasPagarMes; $i++) {
	foreach ($resPagar[$i] as $key => $value) {
	} //fechamento do foreach

	$pagarMes += $resPagar[$i]['valor'];
}

for ($i = 0; $i < $totalContasReceberMes; $i++) {
	foreach ($resReceber[$i] as $key => $value) {
	} //fechamento do foreach

	$receberMes += $resReceber[$i]['valor'];
}

//totaliza o montante vendido do início do mês atual até o dia de hoje
$totalVendasMes = 0;
$query_v = $pdo->query("SELECT * FROM vendas where data >= '$dataInicioMes' and data <= curDate() and status = 'Concluída'");
$res_v = $query_v->fetchAll(PDO::FETCH_ASSOC);
$total_reg_v = @count($res_v);

for ($i = 0; $i < $total_reg_v; $i++) {
	foreach ($res_v[$i] as $key => $value) {
	} //fechamento do foreach

	$totalVendasMes += $res_v[$i]['valor'];
	$totalVendasMes_format = number_format($totalVendasMes, 2, ',', '.');
}

?>




<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

<div class="container-fluid">
	<section id="minimal-statistics">
		<div class="row">
			<div class="col-12 mt-3 mb-1">
				<h4 class="text-uppercase">Estatísticas do Sistema</h4>
				<p>Produtos, Fornecedores, Vendas e Contas</p>
			</div>
		</div>

		<div class="row mb-4">

			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-bar-chart-line-fill text-success fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><span class="text-success"><?php echo @$totalProdutos ?> </span></h3>
									<!-- se colocar div ao invés de span, ocupa mais espaço o valor em reais das entradas, e ocorre quebra de linha -->
									<span>Total de Produtos</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi-cash text-danger fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><span class="text-danger"><?php echo @$totalEstoqueBaixo ?></span></h3>
									<span>Estoque Baixo</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi-cash <?php echo $classeSaldo ?> fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><span class=""><?php echo @$totalFornecedores ?> </span></h3>
									<span>Total de Fornecedores</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-exclamation-triangle-fill fs-1 float-start text-success"></i>
								</div>
								<div class="col-9 text-end">
									<h3><?php echo @$totalVendasDia ?></h3>
									<span>Total Vendas Dia</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>



		<div class="row mb-4">

			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-calendar2-check-fill text-warning fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><span class="text-success"><?php echo @$contas_pagar_hoje ?> </span></h3>
									<!-- se colocar div ao invés de span, ocupa mais espaço o valor em reais das entradas, e ocorre quebra de linha -->
									<span>Contas à Pagar (Hoje)</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-calendar-x-fill text-danger fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><span class="text-danger"><?php echo @$contas_pagar_vencidas ?></span></h3>
									<span>Contas à Pagar Vencidas</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-calendar2-check-fill text-warning fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><span class="<?php echo $classeSaldo ?>"><?php echo @$contas_receber_hoje ?> </span></h3>
									<span>Contas à Receber (Hoje)</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-calendar-x-fill text-danger fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><?php echo @$contas_receber_vencidas ?></h3>
									<span>Contas à Receber Vencidas</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>


	</section>

	<section id="stats-subtitle">
		<div class="row">
			<div class="col-12 mt-3 mb-1">
				<h4 class="text-uppercase">Estatísticas Mensais</h4>
			</div>
		</div>

		<div class="row mb-4">

			<div class="col-xl-6 col-md-12">
				<div class="card overflow-hidden">
					<div class="card-content">
						<div class="card-body cleartfix">
							<div class="row media align-items-stretch">
								<div class="align-self-center col-1">
									<i class="bi-pencil-square text-primary fs-1 mr-2"></i>
								</div>
								<div class="media-body col-6">
									<h4>Saldo Total</h4>
									<span>Total Arrecadado Mês</span>
								</div>
								<div class="text-end col-5">
									<h1>
										<span class="<?php echo $classeSaldoM ?>"> R$ <?php echo @$saldoMesF ?> </span>
									</h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-6 col-md-12">
				<div class="card overflow-hidden">
					<div class="card-content">
						<div class="card-body cleartfix">
							<div class="row media align-items-stretch">
								<div class="align-self-center col-1">
									<i class="bi bi-calendar-week-fill text-danger fs-1 mr-2"></i>
								</div>
								<div class="media-body col-6">
									<h4>Contas à Pagar</h4>
									<span>Total de <?php echo $totalContasPagarMes ?> Contas à Pagar no Mês</span>
								</div>
								<div class="text-end col-5">
									<h1><span class="text-danger">R$ <?php echo number_format($pagarMes, 2, ',', '.') ?></span></h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>


		<div class="row mb-4">

			<div class="col-xl-6 col-md-12">
				<div class="card overflow-hidden">
					<div class="card-content">
						<div class="card-body cleartfix">
							<div class="row media align-items-stretch">
								<div class="align-self-center col-1">
									<i class="bi bi-calendar-week-fill text-success fs-1 mr-2"></i>
								</div>
								<div class="media-body col-6">
									<h4>Contas à Receber</h4>
									<span>Total de <?php echo $totalContasReceberMes ?> Contas à Receber no Mês</span>
								</div>
								<div class="text-end col-5">
									<h1><span class="text-success">R$ <?php echo number_format($receberMes, 2, ',', '.') ?></span></h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-6 col-md-12">
				<div class="card overflow-hidden">
					<div class="card-content">
						<div class="card-body cleartfix">
							<div class="row media align-items-stretch">
								<div class="align-self-center col-1">
									<i class="bi bi-calendar-check-fill text-primary fs-1 mr-2"></i>
								</div>
								<div class="media-body col-6">
									<h4>Total de Vendas</h4>
									<span>Vendas Realizadas no Mês</span>
								</div>
								<div class="text-end col-5">
									<h1><?php echo "R$ " . $totalVendasMes_format ?></h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>


	</section>

	<section id="stats-subtitle">
		<div class="row">
			<div class="col-12 mt-3 mb-1">
				<h4 class="text-uppercase">Modelo de Gráficos</h4>
			</div>
		</div>

		<style type="text/css">
			#principal {
				width: 100%;
				height: 100%;
				margin-left: 10px;
				font-family: Verdana, Helvetica, sans-serif;
				font-size: 14px;
			}

			#barra {
				margin: 0 2px;
				vertical-align: bottom;
				display: inline-block;
				padding:5px;
				text-align: center;
			}

			.cor1,
			.cor2,
			.cor3,
			.cor4,
			.cor5,
			.cor6,
			.cor7,
			.cor8,
			.cor9,
			.cor10,
			.cor11,
			.cor12 {
				color: #FFF;
				padding: 0px;
			}

			.cor1 {
				background-color: #FF0000;
			}

			.cor2 {
				background-color: #0000FF;
			}

			.cor3 {
				background-color: #FF6600;
			}

			.cor4 {
				background-color: #009933;
			}

			.cor5 {
				background-color: #009933;
			}

			.cor6 {
				background-color: #009933;
			}

			.cor7 {
				background-color: #009933;
			}

			.cor8 {
				background-color: #009933;
			}

			.cor9 {
				background-color: #009933;
			}

			.cor10 {
				background-color: #009933;
			}

			.cor11 {
				background-color: #009933;
			}

			.cor12 {
				background-color: #009933;
			}
		</style>

		<div id="principal">
			<p>Vendas em <?php echo $anoAtual ?></p>

			<?php
			//definindo porcentagem
			//busca o total vendido por mês


			for ($i_mes = 1; $i_mes <= 12; $i_mes++) {

				$dataMesInicio = $anoAtual . "-" . $i_mes . "-01";
				$dataMesFinal = $anoAtual . "-" . $i_mes . "-31";

				
				$query_vm = $pdo->query("SELECT * FROM vendas where data >= '$dataMesInicio' and data <= '$dataMesFinal' and status = 'Concluída'");
				$res_vm = $query_vm->fetchAll(PDO::FETCH_ASSOC);
				$total_reg_vm = @count($res_vm); //vm = vendas mês

				$altura_barra = 0;
				$totalValorPorMes = 0;
				$totalValorPorMes_format = 0;
				$totalValorPorMes_format = number_format($totalValorPorMes, 2, ',', '.');

				for ($i = 0; $i < $total_reg_vm; $i++) {
					foreach ($res_vm[$i] as $key => $value) {
					} //fechamento do foreach
					@$totalValorPorMes += $res_vm[$i]['valor'];
					$totalValorPorMes_format = number_format($totalValorPorMes, 2, ',', '.');
					

				}

				$altura_barra = $totalValorPorMes/10;

				if($i_mes < 10) {
					$dataGrafico = '0'.$i_mes . '/' . $anoAtual;
				} else {
					$dataGrafico = $i_mes . '/' . $anoAtual;
				}

			?>

				<div id="barra">
					<div class="cor<?php echo $i_mes ?>" style="height:<?php echo $altura_barra + 25 ?>px"> <!-- não pode deixar px com espaço em relação ao fechamento do php com ?>, pois senão, não considera o px, pode fazer um teste no css para ver como muda a cor -->
						<?php echo @$totalValorPorMes_format ?>
					</div>
					<div><?php echo $dataGrafico ?></div>

				</div>

			<?php

			}

			?>
		</div>

	</section>

</div>