<?php
require 'PHPMailerAutoload.php';
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 11/29/2016
 * Time: 4:03 PM
 */
echo testEmail();

function testEmail(){
//    echo "start";
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';

    $mail->Host       = "10.10.19.37"; // SMTP server example
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
    $mail->Username   = "FStracker@grameenphone.com"; // SMTP account username example
    $mail->Password   = "Grameen@123";        // SMTP account password example

    $name =	'FST';
    $email = 'FStracker@grameenphone.com';

    $mail->From = $email;
    $mail->addAddress('shohelic@gmail.com');
    $mail->FromName = $name;
    $mail->Subject = 'Test';
    $mail->Body = 'Test';
    $mail->isHTML(true);
//    echo "object complete";
    $res = '';
    if(!$mail->Send())
    {
        $res = "Error sending: " . $mail->ErrorInfo;;
    }
    else
    {
        $res = '1';
    }
    unset($mail);
    return $res;

}



?>
