<?php
require("mail/mail2.php");

$emailBody = '<p>Email configuration has been complated.</p><p>Regards</p><p>Shohel Iqbal</p><br />
		<div style="border-top: solid 1px #ccc; text-align:left; color:#666666; font-size:12px;">
			Automatically generated email by FST.
		</div>';
echo $emailBody;
echo testEmail();
//echo wcSendEMail('shohel@aaqa.co','shohelic@outlook.com', 'Email configuration completed', $emailBody);

?>