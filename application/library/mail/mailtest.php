<?php
#require_once('../class.phpmailer.php');
/*require_once('./class.phpmailer.php');
include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$mail->IsSMTP(); // telling the class to use SMTP
$mail->CharSet = 'UTF-8';

$mail->Host       = "192.168.207.211"; // SMTP server
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->Port       = 25;

$name =	'FST';
$email = 'FStracker@grameenphone.com';

$mail->From = $email;
$mail->FromName = $name;
#$address = "m.minhaz.khan@grameenphone.com";
$address = "shohel@aaqa.co";
$mail->AddAddress($address, "Shohel");
$mail->Body = 'Test';
$mail->isHTML(true);


if(!$mail->Send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
} else {
echo "Message sent!";
}*/
//require_once ("mail2.php");

$to = "hasan@aaqa.co";
$cc = "hasan.masud.dcc@gmail.com";
$subject = "Test mail subject from AWS";
$message = "Test mail body from AWS";
//echo wcMailFunction($to, $cc='', $subject, $message, $actionlink='', $logref='');
echo $message;
?>
