<?php
$to = "shohelic@outlook.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: noreply@fst.grameenphone.com" . "\r\n" .
"CC: shohelic@gmail.com";

mail($to,$subject,$txt,$headers);

echo 'Success !!';
?> 