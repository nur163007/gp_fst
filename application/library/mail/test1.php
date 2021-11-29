<?php
require("mail2.php");

$emailBody = '<p>Email configuration testing.....</p><p>Regards</p><p>Shohel Iqbal</p>
		<div style="border-top: solid 1px #ccc; text-align:left; color:#666666; font-size:12px;">
			Automatically generated email by FST.
		</div>';
//echo $emailBody;
//echo testEmail();
$cc = array("shohelic@outlook.com");
echo wcSendEMail('shohelic@gmail.com',$cc, 'Test email....', $emailBody);

?>