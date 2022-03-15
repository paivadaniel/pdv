<?php
//faz a chamada do dompdf

require_once('../config.php');

$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];
$status = $_POST['status'];

//ALIMENTAR OS DADOS NO RELATÓRIO
$html = file_get_contents($url_sistema."rel/relCompras.php?dataInicial=".$dataInicial."&dataFinal=".$dataFinal."&status=".$status);

//utf8_encode para acentuação
//file_get_contents() incorpora página

if($relatorio_pdf != "Sim") {
    echo $html;
    exit();
}

//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true); //necessário para habilitar imagens na biblioteca Dompdf

$pdf = new DOMPDF($options);

//Definir o tamanho do papel e orientação da página
$pdf->set_paper('A4', 'portrait'); //caso queira a folha em paisagem ao invés de retrato, use landscape ao invés de portrait

//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html); //utiliza a classe DOMPDF no caminho apontado acima na variável $html

//RENDERIZAR O PDF
$pdf->render();

//NOMEAR O PDF GERADO
$pdf->stream(
    'compras.pdf',
    array("Attachment" => false)
);
