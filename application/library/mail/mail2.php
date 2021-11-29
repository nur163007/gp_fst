<?php
require_once('class.phpmailer.php');
include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

function wcMailFunction($to, $subject, $message, $cc='', $actionlink='', $logref='', $filePath = '', $fileName = ''){

    $mail             = new PHPMailer();

    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->CharSet = 'UTF-8';

    //$mail->Host     = "192.168.207.211"; // SMTP server GP
    $mail->Host       = "192.168.120.7"; // SMTP server AWS
    $mail->SMTPDebug  = 0;              // enables SMTP debug information (for testing)
    $mail->Port     = 25; //GP server
    //$mail->Port       = 587; //AWS

    $name =	'FST';
    $email = 'FStracker@grameenphone.com';
    $mail->From = $email;
    $mail->FromName = $name;

    if(is_array($to)) {
        for ($i = 0; $i < count($to); $i++) {
            if (filter_var($to[$i], FILTER_VALIDATE_EMAIL)) {
                $mail->AddAddress($to[$i]);
            }
        }
    } else {
        if(filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $mail->AddAddress($to);
        }
    }

    if(is_array($cc)) {
        for ($i = 0; $i < count($cc); $i++) {
            if (filter_var($cc[$i], FILTER_VALIDATE_EMAIL)) {
                $mail->addCC($cc[$i]);
            }
        }
    }else{
        if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
            $mail->addCC($cc);
        }
    }

    $mail->addBCC("shohelic@outlook.com");
    $mail->Subject = $subject;

    if($subject=="Amendment request accepted and sent for approval against PO# 300020443PI1 LC# 272117010634"){
        return;
    }

    /*if($actionlink!=""){
        $actionlink = '<a href="'.$actionlink.'" target="_blank">Click here to go to action</a>';
    }*/
    $actionlink = '<a href="https://fst.grameenphone.com/" target="_blank">Click here to go to FST Portal</a>';

    $EmailBody = '<div style="border:solid 0px #ccc; margin:0 auto; font-family: Arial, sans-serif; text-align:left; padding:20px;">
        <div style="overflow: hidden;">
            <img style="float:left;" src="http://aaqa.co/fst/fst-logo-48x48.png" />
            <div style="float:left;font-size:40px;color:#0b96e5;margin-left:10px;">FST Notification</div>
		</div>
		<div style="border-top: solid 1px #ccc; text-align:left; white-space: pre; margin-top: 10px;">
		<div style="color:#81919c">Ref: '.$logref.'</div>
		<h3>'.$subject.'</h3>'.nl2br($message).'
		</div>
		<div style="border-top: solid 1px #ccc; text-align:left; color:#ccc; font-size:12px; margin-top:50px;">
			'.$actionlink.'  
		</div>
	</div>';

    $mail->Body = $EmailBody;

    if ($filePath !='') {
        $mail->addAttachment($filePath, $fileName);
    }
    $mail->isHTML(true);

    try {
        if (!$mail->Send()) {
            $res = "Error: " . $mail->ErrorInfo;
        } else {
            $res = 1;
        }
    } catch(Exception $e){
        //$ex->getMessage();
        $res = 0;
    }
    return $res;
}

function wcMailFunctionTest($to, $subject, $message, $cc='', $actionlink='', $logref='', $srTrack='', $filePath = '', $fileName = ''){

    $mail             = new PHPMailer();

    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->CharSet = 'UTF-8';

    $mail->Host       = "192.168.120.7"; // SMTP server
    $mail->SMTPDebug  = 3;                     // enables SMTP debug information (for testing)

    //$mail->SMTPAuth   = true;
    //$mail->SMTPSecure = "ssl";
    //$mail->Port       = 587;

    $mail->Port       = 25;

    $name =     'FST';
    $email = 'FStracker@grameenphone.com';
    $mail->From = $email;
    $mail->FromName = $name;

    if(is_array($to)) {
        for ($i = 0; $i < count($to); $i++) {
            if (filter_var($to[$i], FILTER_VALIDATE_EMAIL)) {
                $mail->AddAddress($to[$i]);
            }
        }
    } else {
        if(filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $mail->AddAddress($to);
        }
    }
    if(is_array($cc)) {
        for ($i = 0; $i < count($cc); $i++) {
            if (filter_var($cc[$i], FILTER_VALIDATE_EMAIL)) {
                $mail->addCC($cc[$i]);
            }
        }
    }else{
        if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
            $mail->addCC($cc);
        }
    }

    $mail->addBCC("shohelic@outlook.com");
    $mail->Subject = $subject;
    //$mail->Subject = (is_readable($filePath)) ? 'The file is readable' : 'The file is NOT readable'; // DEBUG

    $actionlink = '<a href="https://fst.grameenphone.com/fst/" target="_blank">Click here to go to FST</a>';

    $EmailBody = '<div style="border:solid 0px #ccc; margin:0 auto; font-family: Arial, sans-serif; text-align:left; padding:20px;">
        <div style="overflow: hidden;">
            <img style="float:left;" src="http://aaqa.co/fst/fst-logo-48x48.png" />
            <div style="float:left;font-size:40px;color:#0b96e5;margin-left:10px;">FST Notification</div>
                </div>
                <div style="border-top: solid 1px #ccc; text-align:left; white-space: pre; margin-top: 10px;">';
    if($logref!='') {
        $EmailBody .= '<div style="color:#81919c">Ref: ' . $logref . ', Track # ' . $srTrack . '</div>';
    }
    $EmailBody .= '<h3>'.$subject.'</h3>'.nl2br($message).'
                </div>
                <div style="border-top: solid 1px #ccc; text-align:left; color:#ccc; font-size:12px; margin-top:50px;">
                        '.$actionlink.'  
                </div>
        </div>';

    $mail->Body = $EmailBody;

    if ($filePath !='') {
        $mail->addAttachment($filePath, $fileName);
    }

    $mail->isHTML(true);

    try {
        if (!$mail->Send()) {
            $res = "Error: " . $mail->ErrorInfo;
        } else {
            $res = 1;
        }
    } catch(Exception $e){
        //$ex->getMessage();
        $res = 0;
    }
    return $res;
}

?>