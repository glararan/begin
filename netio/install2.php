<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>netIO 4 - Install</title>
    
    <style>
		div {margin: 0 auto;font-size:14pt;font-family:Microsoft Yi Baiti, Courier New, Helvetica, sans-serif;width:1000px;background-color:#FFF;position:relative;top:-25px}
		label {padding-left:50px;}
		.button {padding-left:50px;padding-bottom:15px;}
		p {text-align:center;padding:10px;}
	</style>
</head>
<body bgcolor="#333333" style="padding:0;margin:0;">
<div>
	<h1 align="center">Install</h1>
    <?php
    	if(!isset($_POST['submit']))
    	{
    ?>
	<form method="post" action="">
        <table width="80%">
            <tr>
            	<td width="30%">
                    <label for="account">Account</label>
                </td>
                <td width="70%">
                    <input type="text" name="account" placeholder="Account">
                </td>
            </tr>
            <tr>
            	<td width="30%">
                    <label for="password">Password</label>
                </td>
                <td width="70%">
                    <input type="password" name="password" placeholder="Password">
                </td>
            </tr>
            <tr>
            	<td width="30%">
                    <label for="password2">Repeat-Password</label>
                </td>
                <td width="70%">
                    <input type="password" name="password2" placeholder="Repeat-Password">
                </td>
            </tr>
            <tr>
            	<td width="30%">
                    <label for="email">Email</label>
                </td>
                <td width="70%">
                    <input type="text" name="email" value="@">
                </td>
            </tr>
            <tr>
            	<td>
                	<br>
                </td>
            </tr>
            <tr>
            	<td class="button">
                    <input type="submit" name="submit" value="Register">
                </td>
            </tr>
        </table>
    </form>
    <?php
    	}
        else
        {
        	$acc = $_POST['account'];
            $pass = $_POST['password'];
			$pass2 = $_POST['password2'];
			$mail = $_POST['email'];
			$IP = $_SERVER['REMOTE_ADDR'];
			$reg = date("d/m/Y H:i:s", time());
			$lastLogin = date("d/m/Y H:i:s", time());
			
			if($acc == null)
			{
				die('<p>Account neni vyplňen. Konec...</p>');
				echo '<meta http-equiv="refresh" content="2;URL=index.php">';
			}
				
			if($pass == null)
			{
				die('<p>Password neni vyplňen. Konec...</p>');
				echo '<meta http-equiv="refresh" content="2;URL=index.php">';
			}
				
			if($pass2 == null)
			{
				die('<p>Password2 neni vyplňen. Konec...</p>');
				echo '<meta http-equiv="refresh" content="2;URL=index.php">';
			}
				
			if($mail == null)
			{
				die('<p>Email neni vyplňen. Konec...</p>');
				echo '<meta http-equiv="refresh" content="2;URL=index.php">';
			}			
            
			if($pass != $pass2)
			{
				die('<p>Hesla se neshodují. Konec...</p>');
				echo '<meta http-equiv="refresh" content="2;URL=index.php">';
			}
			else
			{	
				$xml = new DOMDocument();
				$xml->load('data/database/users.xml');
					
				$Account = $xml->createElement('Account');
				$Account->setAttribute('ID', '0');
				
				$Name = $xml->createElement('Name');
				$NameText = $xml->createTextNode($acc);
				$Name->appendChild($NameText);
				$Account->appendChild($Name);
				
				$Password = $xml->createElement('Password');
				$PasswordText = $xml->createTextNode(md5($pass));
				$Password->appendChild($PasswordText);
				$Account->appendChild($Password);
				
				$Email = $xml->createElement('Email');
				$EmailText = $xml->createTextNode($mail);
				$Email->appendChild($EmailText);
				$Account->appendChild($Email);
				
				$_IP = $xml->createElement('IP');
				$IPText = $xml->createTextNode($IP);
				$_IP->appendChild($IPText);
				$Account->appendChild($_IP);
				
				$RegDate = $xml->createElement('RegDate');
				$RegDateText = $xml->createTextNode($reg);
				$RegDate->appendChild($RegDateText);
				$Account->appendChild($RegDate);
				
				$_LastLogin = $xml->createElement('LastLogin');
				$LastLoginText = $xml->createTextNode($lastLogin);
				$_LastLogin->appendChild($LastLoginText);
				$Account->appendChild($_LastLogin);
				
				$xml->documentElement->appendChild($Account);
					
				/*$c2 = $xml->createElement('Account');
						
				$ID = $xml->createElement('ID');
				$ID->appendChild($xml->createTextNode('1'));
				$c2->appendChild($ID);
						
				$Name = $xml->createElement('Name');
				$Name->appendChild($xml->createTextNode($acc));
				$c2->appendChild($Name);
					
				$Password = $xml->createElement('Password');
				$Password->appendChild($xml->createTextNode($pass));
				$c2->appendChild($Password);
						
				$Email = $xml->createElement('Email');
				$Email->appendChild($xml->createTextNode($mail));
				$c2->appendChild($Email);
						
				$IP = $xml->createElement('IP');
				$IP->appendChild($xml->createTextNode($IP));
				$c2->appendChild($IP);
						
				$RegDate = $xml->createElement('RegDate');
				$RegDate->appendChild($xml->createTextNode($reg));
				$c2->appendChild($RegDate);
						
				$LastLogin = $xml->createElement('LastLogin');
				$LastLogin->appendChild($xml->createTextNode($lastLogin));
				$c2->appendChild($LastLogin);
				
				$c->appendChild($c2);*/
				
				$xml->save('data/database/users.xml');
					
				echo '<p>Smažte soubor install.php, poté obnovte stránku.</p>';
			}
        }
	?>
</div>
</body>
</html>