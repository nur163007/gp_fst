<?php
echo "test";
/* include autoloader */
require_once 'application/library/dompdf/autoload.inc.php';

/* reference the Dompdf namespace */
use Dompdf\Dompdf;

/* instantiate and use the dompdf class */
$dompdf = new Dompdf();

$html = '<link rel="stylesheet" href="pdf-bootstrap.min.css">
		<div>'.$_POST['content'].'</div>';

/*$html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<h1>'.$_POST['title'].'</h1>
        <div>'.$_POST['content'].'</div>';*/

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
/* Render the HTML as PDF */
$dompdf->render();

/* Output the generated PDF to Browser */

$output_file_name = trim($_POST['ponum']).'.pdf';

//$dompdf->stream($output_file_name);

$output = $dompdf->output();
file_put_contents($output_file_name, $output);
?>