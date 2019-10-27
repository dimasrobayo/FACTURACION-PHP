<?php
	// Datos conexion para el envio de Email
	require_once('PHPMailer/class.phpmailer.php');
		
	//$body             = file_get_contents('contents.html');
	//$body             = eregi_replace("[\]",'',$body);
	$mail             = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->CharSet    = 'UTF-8';
	
	$mail->SMTPAuth   = true;                  // enable SMTP authentication	
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	$mail->Host       = "smtp.gmail.com"; // sets the SMTP server
	$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
	$mail->Username   = "atencion.soberanoportuguesa@gmail.com"; // SMTP account username
	$mail->Password   = "r2aiportuguesa";        // SMTP account password
	$mail->SetFrom('atencion.soberanoportuguesa@gmail.com', 'Gobernacion del Estado Portuguesa');
?>