<?php
ob_start();
include ('pdf-content.php');
$html = ob_get_contents();
ob_end_clean();


use Dompdf\Dompdf;
use Dompdf\Options;
require_once ('Include/dompdf/autoload.inc.php');

$options = new Options();
$options->set('defaultFont', 'Courier');
$options->set('isRemoteEnabled', TRUE);
$options->set('debugKeepTemp', TRUE);
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$fichier="FicheFrais-".$mois;
$dompdf->stream($fichier);