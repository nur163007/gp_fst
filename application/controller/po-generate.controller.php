<?php
if ( !session_id() ) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");

if (!empty($_GET["pono"]))
{
    require_once(LIBRARY_PATH . "/tcpdf/tcpdf.php");

    $pdfName = "Test PDF";

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Aqa Technology');
    $pdf->SetTitle($pdfName);
    $pdf->SetSubject('PDF Invoice');
    $pdf->SetKeywords('TCPDF, PDF Invoice');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->Cell(30, 0, '', 1, $ln=0, 'C', 0, '', 0, false, 'D', 'B');
    $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica', '', '7', '', true);

    $pono = $_GET["pono"];

    $sql = "SELECT lineNo, itemCode, itemDesc, DATE_FORMAT(needByDate, '%d-%b-%Y') needByDate, uom, Format(unitPrice,2) unitPrice, poQty,Format(poTotal,2) poTotal FROM wc_t_po_dump where poNo = '60007607';";

    $objdal = new dal();
    $objdal->read($sql);

    $htmlTemp = '<h2>PO: '.$pono.'</h2><br>';

    $htmlTemp .= '<table cellpadding="5" cellspacing="0" width="100%" border=".5"> 
                     <tr>
                        <td width="6%" style="text-align: center">Line No</td>
                        <td width="10%" style="text-align: center">Item Code</td>
                        <td width="34%" >Desc</td>
                        <td width="10%">Date</td>
                        <td width="10%">UOM</td>
                        <td width="10%" style="text-align: right">Unit Price</td>
                        <td width="10%" style="text-align: right">Po Quantity</td>
                        <td width="10%" style="text-align: right">Po Total</td>
                     </tr>';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $htmlTemp .= '<tr>';
            $htmlTemp .= '<td width="6%" style="text-align: center">'.$val['lineNo'].'</td>';
            $htmlTemp .= '<td width="10%" style="text-align: center">'.$val['itemCode'].'</td>';
            $htmlTemp .= '<td width="34%">'.htmlentities($val['itemDesc']).'</td>';
            $htmlTemp .= '<td width="10%">'.$val['needByDate'].'</td>';
            $htmlTemp .= '<td width="10%">'.$val['uom'].'</td>';
            $htmlTemp .= '<td width="10%" style="text-align: right">'.$val['unitPrice'].'</td>';
            $htmlTemp .= '<td width="10%" style="text-align: right">'.$val['poQty'].'</td>';
            $htmlTemp .= '<td width="10%" style="text-align: right">'.$val['poTotal'].'</td>';
            $htmlTemp .= '</tr>';
        }
    } else {
        $htmlTemp .= '<tr>';
        $htmlTemp .= '<td>No record to show</td>';
        $htmlTemp .= '</tr>';
    }
    $htmlTemp .= '</table>';

//    $htmlTemp = "test";

    $pdf->writeHTML($htmlTemp, true, false, false, false, '');
    $pdf->Output($pdfName, 'I');

}

?>