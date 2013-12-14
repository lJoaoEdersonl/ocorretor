<?php
require_once HELPERDIR . "phpmailer/class.phpmailer.php";
global $mail;
$mail = new PHPMailer();
//porta
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->WordWrap = 80;
$mail->IsHTML( true );
?>