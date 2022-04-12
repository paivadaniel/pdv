<?php

@session_start();

require_once('../conexao.php');
require_once('verifica_permissao.php');

$hoje = date('Y-m-d');
$mesAtual = Date('m');
$anoAtual = Date('Y');
$dataInicioMes = $anoAtual . "-" . $mesAtual . "-01";

$entradas = 0;
$saidas = 0;
$saldo = 0;

$query2 = $pdo->query("SELECT * FROM movimentacoes where data = curDate() order by id desc"); //nome é o campo da tabela
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

if ($total_reg2 > 0) {

	for ($i = 0; $i < $total_reg2; $i++) {
		foreach ($res2[$i] as $key => $value) {
		} //fechamento do foreach


		if ($res2[$i]['tipo'] == 'Entrada') {
			$entradas += $res2[$i]['valor'];
		} else if ($res2[$i]['tipo'] == 'Saída') {
			$saidas += $res2[$i]['valor'];
		}

		$saldo = $entradas - $saidas;

		if ($saldo > 0) {
			$classeSaldo = 'text-success';
		} else {
			$classeSaldo = 'text-danger';
		}
	}
}


$query3 = $pdo->query("SELECT * FROM movimentacoes order by id desc limit 1"); //limit 1 traz um único registro
$res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
$valorMov = $res3[0]['valor'];
$descricaoMov = $res3[0]['descricao'];
$valorMov = number_format($valorMov, 2, ',', '.'); //não precisa criar valorMovF, pois não será feito cálculos, ou seja, essa variável não será modificada

if ($res3[0]['tipo'] == 'Entrada') {
	$classeMov = 'text-success';
} else if ($res3[0]['tipo'] == 'Saída') {
	$classeMov = 'text-danger';
}

//Contas à Pagar (Hoje), Contas à Pagar Vencidas, Contas à Receber (Hoje), Contas à Receber Vencidas

$query = $pdo->query("SELECT * FROM contas_pagar where vencimento = curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_hoje = @count($res);

$query = $pdo->query("SELECT * FROM contas_pagar where vencimento < curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = @count($res);

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

?>




<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

<div class="container-fluid">
	<section id="minimal-statistics">
		<div class="row">
			<div class="col-12 mt-3 mb-1">
				<h4 class="text-uppercase">Estatísticas Financeiras</h4>
				<p>Contas à Pagar, Receber e Movimentações</p>
			</div>
		</div>

		<div class="row mb-4">

			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi-cash text-success fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><span class="text-success">R$ <?php echo @number_format($entradas, 2, ',', '.') ?> </span></h3>
									<!-- se colocar div ao invés de span, ocupa mais espaço o valor em reais das entradas, e ocorre quebra de linha -->
									<span>Entradas do Dia</span>
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
									<h3><span class="text-danger">R$ <?php echo @number_format($saidas, 2, ',', '.') ?></span></h3>
									<span>Saídas do Dia</span>
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
									<h3><span class="<?php echo $classeSaldo ?>">R$ <?php echo @number_format($saldo, 2, ',', '.') ?> </span></h3>
									<span>Saldo do Dia</span>
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
									<i class="bi bi-exclamation-triangle-fill <?php echo @$classeMov ?> fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3>R$ <?php echo @$valorMov ?></h3>
									<span><?php echo @$descricaoMov ?></span>
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
									<h1>18,000</h1>
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
				width: 500px;
				height: 100%;
				margin-left: 10px;
				font-family: Verdana, Helvetica, sans-serif;
				font-size: 14px;

			}

			#barra {
				margin: 0 2px;
				vertical-align: bottom;
				display: inline-block;


			}

			.cor1,
			.cor2,
			.cor3,
			.cor4 {
				color: #FFF;
				padding: 5px;
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
		</style>


		<?php
		// definindo porcentagem
		$height1 = '28px';
		$height2 = '49px';
		$height3 = '33px';
		$height4 = '83px';
		$total  = 4; // total de barras
		?>
		<div id="principal">
			<p>Vendas no Ano</p>
			<?php
			for ($i = 1; $i <= $total; $i++) {
				$height = ${'height' . $i};
			?>
				<div id="barra">
					<div class="cor<?php echo $i ?>" style="height:<?php echo $height ?>"> <?php echo $height ?> </div>
					<div>Janeiro</div>

				</div>
			<?php } ?>
		</div>

	</section>

</div>