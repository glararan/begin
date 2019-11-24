<?php
	$name    = $_POST['your-name'];
	$email   = $_POST['your-email'];
	$text    = $_POST['message'];
	$subject = $_POST['subject'];
	
	if(isset($name) && isset($email) && isset($text) && isset($subject))
	{
		$message = '<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
						
						<title>QEditor - Web formulář: '.$subject.'</title>
					</head>
					<body>
						<p>Posílám formulář z webu od <b>'.$name.'</b>('.$email.')</p>
						<p>'.$text.'</p>
					</body>
					</html>';
					
		// Headery
		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
		$headers .= 'To: Lukáš Veteška <glararan@glararan.eu>, Martin Hruška <hruska.martin@ssakhk.cz>, Jindřich Jadrný <jadrny.jindrich@ssakhk.cz>'."\r\n";
		$headers .= 'Od: '.$name.' <'.$email.'>'."\r\n";
		$headers .= 'Reply-To: '.$email."\r\n";
	
		// Mailnutí
		mail("glararan@glararan.eu, hruska.martin@ssakhk.cz, jadrny.jindrich@ssakhk.cz", "QEditor - Web formulář: ".$subject, $message, $headers);
	}
?>