<div class="content_left">
	<?php echo $lang['users_img']; ?>
</div>

<div class="content_right">
	<?php echo $lang['users_title']; ?>
    <br>
    <?php echo $lang['users_text']; ?>
    <?php echo $lang['highlighter']; ?>
    
    <?php
		# users system
		
		if(!isset($_GET['arg1']))
		{		
			if(!isset($_POST['createAccount']))
			{
				// TABLE
				echo "<table width='100%'><tbody><tr><td><b>".$lang['users_table_name']."</b></td><td><b>".$lang['users_table_delete']."</b></td></tr>";
				
				$read_accounts = new DOMDocument();
                $read_accounts->load('data/database/users.xml');
                
                $Accounts = $read_accounts->getElementsByTagName('Account');
				
				foreach($Accounts as $Account)
				{
					$_accs = $Account->getElementsByTagName('Name');
					$_acc = $_accs->item(0)->nodeValue;
					
					if($_acc == $_SESSION['account'])
						echo "<tr><td>".$_acc."</td><td></td></tr>";
					else
						echo "<tr><td>".$_acc."</td><td><a href='/users/".$_acc."/'><font color='red'>".$lang['delete']."</font></a></td></tr>";
				}
				
				echo "</tbody></table>";
				
				// space
				echo "<br><br><br><br><br><br><br><br><br><br>";
				
				// CREATE ACOUNT FORM
				echo "<form method='post' action=''><label for='account'>".$lang['users_account_name']."<br></label><input type='text' name='account' class='box'><br><label for='password'>".$lang['users_account_pass']."<br></label><input type='password' name='password' class='box'><br><label for='password2'>".$lang['users_account_repeat_pass']."<br></label><input type='password' name='password2' class='box'><br><label for='email'>".$lang['users_account_email']."<br></label><input type='text' name='email' class='box'><br><br><input type='submit' name='createAccount' class='button' value='".$lang['users_create_account']."'></form>";
			}
			else
			{
				$_acc_ = $_POST['account'];
				$_pass1_ = $_POST['password'];
				$_pass2_ = $_POST['password2'];
				$_email_ = $_POST['email'];
				$IP = $_SERVER['REMOTE_ADDR'];
				$reg = date("d/m/Y H:i:s", time());
				$lastLogin = "00/00/00 00:00:00";
				
				if(empty($_acc_))
					die($lang['users_error_0'].'<meta http-equiv="refresh" content="5;URL=/users/">');
				
				if(empty($_pass1_))
					die($lang['users_error_1'].'<meta http-equiv="refresh" content="5;URL=/users/">');
					
				if(empty($_pass2_))
					die($lang['users_error_2'].'<meta http-equiv="refresh" content="5;URL=/users/">');
					
				if(empty($_email_))
					die($lang['users_error_3'].'<meta http-equiv="refresh" content="5;URL=/users/">');
				
				$_xml = new DOMDocument();
                $_xml->load('data/database/users.xml');
                
                $Accounts = $_xml->getElementsByTagName('Account');
				$lastID = -1;
				
				foreach($Accounts as $Account)
				{
					$_accs = $Account->getElementsByTagName('Name');
					$_acc = $_accs->item(0)->nodeValue;
					
					if($_acc == $_acc_)
						die($lang['users_error_4'].'<meta http-equiv="refresh" content="5;URL=/users/">');
					
					$lastID = $Account->getAttribute('ID');
				}
				
				if($_pass1_ != $_pass2_)
					die($lang['users_error_5'].'<meta http-equiv="refresh" content="5;URL=/users/">');
					
				$Account = $_xml->createElement('Account');
				$Account->setAttribute('ID', $lastID + 1);
				
				$Name = $_xml->createElement('Name');
				$NameText = $_xml->createTextNode($_acc_);
				$Name->appendChild($NameText);
				$Account->appendChild($Name);
				
				$Password = $_xml->createElement('Password');
				$PasswordText = $_xml->createTextNode(md5($_pass1_));
				$Password->appendChild($PasswordText);
				$Account->appendChild($Password);
				
				$Email = $_xml->createElement('Email');
				$EmailText = $_xml->createTextNode($_email_);
				$Email->appendChild($EmailText);
				$Account->appendChild($Email);
				
				$_IP = $_xml->createElement('IP');
				$IPText = $_xml->createTextNode($IP);
				$_IP->appendChild($IPText);
				$Account->appendChild($_IP);
				
				$RegDate = $_xml->createElement('RegDate');
				$RegDateText = $_xml->createTextNode($reg);
				$RegDate->appendChild($RegDateText);
				$Account->appendChild($RegDate);
				
				$_LastLogin = $_xml->createElement('LastLogin');
				$LastLoginText = $_xml->createTextNode($lastLogin);
				$_LastLogin->appendChild($LastLoginText);
				$Account->appendChild($_LastLogin);
				
				$_xml->documentElement->appendChild($Account);
				
				$_xml->save('data/database/users.xml');
				
				echo sprintf($lang['users_success_0'], $_acc_).'<meta http-equiv="refresh" content="2;URL=/users/">';
			}			
		}
		else if(isset($_GET['arg1']))
		{
			if($_GET['arg1'] == $_SESSION['account'])
				echo $lang['users_delete_cant_delete_self'];
			else
			{
				if(!isset($_POST['deleteAccount']))
					echo sprintf($lang['users_delete_do_you_want'], $_GET['arg1'])."<form method='post' action=''><input type='submit' name='deleteAccount' class='button' value='".$lang['yes']."'><button type='button' name='no' class='button'><a href='/users/'>".$lang['no']."</a></button></form>";
				else
				{
					if($_GET['arg1'] != $_SESSION['account'])
					{
						// deleting acc
						$_xml = new DOMDocument();
						$_xml->load('data/database/users.xml');
						
						$Accounts = $_xml->getElementsByTagName('Account');
						$accID = -1;
						$accForDelete = $_GET['arg1'];
								
						foreach($Accounts as $Account)
						{
							$_accs = $Account->getElementsByTagName('Name');
							$_acc = $_accs->item(0)->nodeValue;
								
							if($_acc == $accForDelete)
								$accID = $Account->getAttribute('ID');
						}
						
						$xpath = new DOMXpath($_xml);
						$nodeList = $xpath->query('//Account[@ID="'.(int)$accID.'"]');
						
						if($nodeList->length)
						{
							$node = $nodeList->item(0);
							$node->parentNode->removeChild($node);
						}
								
						$_xml->save('data/database/users.xml');
						
						echo sprintf($lang['users_success_1'], $accForDelete).'<meta http-equiv="refresh" content="3;URL=/users/">';
					}
				}
			}
		}
	?>
</div>

<div class="clear">
</div>