<?php

if(empty($_POST['filename'])){
	exit;
}

$filename = preg_replace('/[^a-z0-9\-\_\.]/i','',$_POST['filename']);

header("Cache-Control: ");
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=".$filename."");

echo "<html xmlns:o='urn:schemas-microsoft-com:office:office' 
    xmlns:w='urn:schemas-microsoft-com:office:word'
    xmlns='http://www.w3.org/TR/REC-html40'>
    <head><title>Time</title>";

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";
echo "<table width='100%'>";
echo "<tr>";
echo "  <td>&nbsp;</td>
        <td style='width:150px;'>Grameenphone Ltd.<br /> GP HOUSE, Bashundhara, Baridhara<br />Dhaka-1229</td>";
echo "</tr>";
echo "<tr>";
echo "  <td>&nbsp;</td>";  
echo "</tr>";
echo "<tr>";
echo "  <td>Date: 15.03.2016</td>";
echo "  <td>Ref: Clearance/ 8711</td>";
echo "</tr>";
echo "</table>";
echo "<p><b>Deputy Commissioner of Customs</b> <br />Customs House<br />Chittagong.</p>";
echo "<p>Subject: <b>Letter of Authorisation</b>.</p>";
echo "<p>Dear Sir,<br />We hereby authorise Messrs <b>BADAL AND COMPANY</b> to clear and take delivery of our consignment as referred below:</p>";
echo "<p><b>Vessel:  ANTWERPEN EXPRESS 012E05.</b><br /><b>B/L No.: NYKS5240212760.</b> <br/></p>";
echo "<p>Messrs <b>BADAL AND COMPANY</b> will complete the necessary customs formalities on our behalf.<br /><br />The value and composition of the shipment is stated on the shipper's invoice.</p>";
echo "<p>Yours faithfully<br /><br />For Grameenphone Ltd.<br /><br /><br /><br /><br /></p>";
echo "<table width='100%'>";
echo "<tr>";
echo "  <td>Benazir Ahmed<br />Specialist, External Approvals,<br />Sourcing Operation.</td>
        <td>&nbsp;</td>";
echo "</tr>";
echo "</table>";
echo "</body>";
echo "</html>";
?>