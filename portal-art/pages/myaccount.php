<?php
	session_start();
	
	if (!isset($_SESSION['rank']) && !isset($_SESSION['user_email']))
		exit($lang['dontHaveAccess']);
		
	function isEmail($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
			return true;
		else
			return false;
	}
		
	if(!isEmail($_SESSION['user_email']))
		exit($lang['invalidEmail']);
	
	// Připojení k databázi
	$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
	$mysqli->set_charset('utf8');
	
	if (mysqli_connect_errno()) 
		die(mysqli_connect_errno());
		
	function getAccountStatus()
	{
		global $mysqli;
		global $lang;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT status FROM users WHERE email = ?"))
		{
			$query->bind_param("s", $_SESSION['user_email']);
			$query->execute();
			$query->bind_result($status);
			$query->fetch();
				
			switch($status)
			{
				case 0:
					echo "Bez trestu";
					break;
					
				case 1:
					echo "Varování";
					break;
					
				case 2:
					echo "Zakázaný";
					break;
					
				default:
					echo "Neznámý";
					break;
			}
			
			$query->close();
		}
		else
		{
			echo $lang['error_cantDoStatement'];
			return;
		}
	}
	
	function getAccountDevicesComments()
	{
		global $mysqli;
		global $lang;
		
		$r = NULL;
		$p = NULL;
		
		$query_u = $mysqli->stmt_init();
		
		if($query_u = $mysqli->prepare("SELECT id FROM users WHERE email = ?"))
		{
			$query_u->bind_param("s", $_SESSION['user_email']);
			$query_u->execute();
			$query_u->store_result();
			$query_u->bind_result($userID);
			$query_u->fetch();
			
			$query = $mysqli->stmt_init();
			
			if($query = $mysqli->prepare("SELECT admin, device, comment FROM transfer_rejects WHERE user = ?"))
			{
				$query->bind_param("i", $userID);
				$query->execute();
				$query->store_result();
				$query->bind_result($adminID, $deviceID, $comment);
				
				while($query->fetch())
				{
					$query_a = $mysqli->stmt_init();
					
					if($query_a = $mysqli->prepare("SELECT email FROM admins WHERE id = ?"))
					{
						$query_a->bind_param("i", $adminID);
						$query_a->execute();
						$query_a->store_result();
						$query_a->bind_result($adminEmail);
						$query_a->fetch();
						
						$query_d = $mysqli->stmt_init();
						
						if($query_d = $mysqli->prepare("SELECT name FROM devices WHERE id = ?"))
						{
							$query_d->bind_param("i", $deviceID);
							$query_d->execute();
							$query_d->store_result();
							$query_d->bind_result($deviceName);
							$query_d->fetch();
							
							$p .= "<tr><td>".$deviceName."</td><td>".$adminEmail."</td><td>".$comment."</td></tr>";
							
							$query_d->close();
						}
						
						$query_a->close();						
					}
				}
				
				$query->close();
			}
			
			$query_u->close();
		}
		
		if($p != NULL)
		{
			?>
<script>
	$(document).ready(function()
	{
		$(".spoilerDC").click(function()
		{
			switch($(".spoilerDC").html())
			{
				case "Skrýt":
					{
						$("#gadc").slideUp();
						
						$(".spoilerDC").html("Zobrazit");
					}
					break;
				
				case "Zobrazit":
					{
						$("#gadc").slideDown();
						
						$(".spoilerDC").html("Skrýt");
					}
					break;
			}
		});
	});
</script>
<?php
			echo "<table class='table table-bordered table-striped table-hover users-table' id='gadc'><tr><th>Název zařízení</th><th>Zamítl</th><th>Komentář</th></tr>".$p."</table><button class='btn btn-primary spoilerDC'>Skrýt</button>";
		}
		else
			echo "<p>Ještě Vám nikdo nezamítl vypůjčení nebo jste si o nic nikdy nezažádali.";
	}
	
	function getAccountDevicesUsing()
	{
		global $mysqli;
		global $lang;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT id FROM users WHERE email = ?"))
		{
			$query->bind_param("s", $_SESSION['user_email']);
			$query->execute();
			$query->store_result();
			$query->bind_result($userID);
			
			while($query->fetch())
			{
				$query_t = $mysqli->stmt_init();
				
				if($query_t = $mysqli->prepare("SELECT device, fromDate, toDate, adminText FROM transfers WHERE user = ? AND status = 1"))
				{
					$query_t->bind_param("i", $userID);
					$query_t->execute();
					$query_t->store_result();
					$query_t->bind_result($deviceID, $fromDate, $toDate, $adminText);
					
					if($query_t->num_rows > 0)
					{
						$org_warning = false;
						$red_warning = false;					
?>
<script>
	$(document).ready(function()
	{
		$(".spoilerDU").click(function()
		{
			switch($(".spoilerDU").html())
			{
				case "Skrýt":
					{
						$("#gadu").slideUp();
						
						$(".spoilerDU").html("Zobrazit");
					}
					break;
				
				case "Zobrazit":
					{
						$("#gadu").slideDown();
						
						$(".spoilerDU").html("Skrýt");
					}
					break;
			}
		});
	});
</script>
<?php
						
						echo "<div id='gadu'><table class='table table-bordered table-striped table-hover users-table'><tr><th>Název zařízení</th><th>Datum vypůjčení</th><th>Datum vrácení</th><th>Komentář</th></tr>";
						
						while($query_t->fetch())
						{
							$o_w = false;
							$r_w = false;
							
							if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", time()))
								$fromDate = "Dnes";
							else if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) - 1, date("Y", time()))))
								$fromDate = "Včéra";
							else if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) - 2, date("Y", time()))))
								$fromDate = "Předevčírem";
							else if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) + 1, date("Y", time()))))
								$fromDate = "Zítra";
							else if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) + 2, date("Y", time()))))
								$fromDate = "Pozítří";
							else
								$fromDate = date("d. m. Y", strtotime($fromDate));
								
							if(floor((strtotime($toDate) - time()) / 86400) >= 0 && floor((strtotime($toDate) - time()) / 86400) <= 7)
								$o_w = true;
							else if(floor((strtotime($toDate) - time()) / 86400) < 0)
								$r_w = true;
								
							if($o_w)
								$org_warning = true;
							if($r_w)
								$red_warning = true;
								
							if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", time()))
								$toDate = "Dnes";
							else if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) - 1, date("Y", time()))))
								$toDate = "Včéra";
							else if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) - 2, date("Y", time()))))
								$toDate = "Předevčírem";
							else if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) + 1, date("Y", time()))))
								$toDate = "Zítra";
							else if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) + 2, date("Y", time()))))
								$toDate = "Pozítří";
							else
								$toDate = date("d. m. Y", strtotime($toDate));
							
							$query_d = $mysqli->stmt_init();
							
							if($query_d = $mysqli->prepare("SELECT name FROM devices WHERE id = ?"))
							{
								$query_d->bind_param("i", $deviceID);
								$query_d->execute();
								$query_d->store_result();
								$query_d->bind_result($deviceName);
								
								while($query_d->fetch())
								{
									$tr = "<tr>";
									
									if($o_w)
										$tr = "<tr class='orange_tr'>";
									if($r_w)
										$tr = "<tr class='red_tr'>";
									
									echo $tr."<td>".$deviceName."</td><td>".$fromDate."</td><td>".$toDate."</td><td>".$adminText."</td></tr>";
								}
								
								$query_d->close();
							}
							else
								echo $lang['error_cantDoStatement'];
						}
						
						echo "</table></div>";
						
						if($org_warning)
							echo "<p class='orange_text'>Některé položky jsou označeně oranžovou barvou, to znamená, že je do týdne musíte vrátit.</p>";
						if($red_warning)
							echo "<p class='red_text'>Některé položky jsou označené červenou barvou, to znamená, že je dnes máte vrátit, nebo jste už prošvihly termín a máte je vrátit!</p>";
							
						echo '<button class="btn btn-primary spoilerDU">Skrýt</button>';
					}
					else
						echo "<p>Momentálně jste si nevypůjčili žádné zařízení.</p>";
						
					$query_t->close();
				}
				else
					echo $lang['error_cantDoStatement'];
			}
				
			$query->close();
		}
		else
		{
			echo $lang['error_cantDoStatement'];
			return;
		}
	}
	
	function getAccountDevicesHistory()
	{
		global $mysqli;
		global $lang;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT id FROM users WHERE email = ?"))
		{
			$query->bind_param("s", $_SESSION['user_email']);
			$query->execute();
			$query->store_result();
			$query->bind_result($userID);
			
			while($query->fetch())
			{
				$query_t = $mysqli->stmt_init();
					
				if($query_t = $mysqli->prepare("SELECT device FROM transfers WHERE user = ? AND status = 2"))
				{
					$query_t->bind_param("i", $userID);
					$query_t->execute();
					$query_t->store_result();
					$query_t->bind_result($deviceID);
					
					while($query_t->fetch())
					{
						if($query_t->num_rows > 0)
						{
?>
<script>
	$(document).ready(function()
	{
		$(".spoilerDH").click(function()
		{
			switch($(".spoilerDH").html())
			{
				case "Skrýt":
					{
						$("#gadh").slideUp();
						
						$(".spoilerDH").html("Zobrazit");
					}
					break;
				
				case "Zobrazit":
					{
						$("#gadh").slideDown();
						
						$(".spoilerDH").html("Skrýt");
					}
					break;
			}
		});
	});
</script>
<?php
							echo "<div id='gadh'><table class='table table-bordered table-striped table-hover users-table'><tr><th>Název zařízení</th></tr>";

							$query_d = $mysqli->stmt_init();
							
							if($query_d = $mysqli->prepare("SELECT name FROM devices WHERE id = ?"))
							{
								$query_d->bind_param("i", $deviceID);
								$query_d->execute();
								$query_d->store_result();
								$query_d->bind_result($deviceName);
								
								while($query_d->fetch())
									echo "<tr><td>".$deviceName."</td></tr>";
								
								$query_d->close();
							}
							else
								echo $lang['error_cantDoStatement'];
						
							echo "</table></div>";
							echo '<button class="btn btn-primary spoilerDH">Skrýt</button>';
						}
						else
							echo "<p>Ještě jste si nevypůjčili nic, nebo máte momentálně vypůjčenou věc, kterou jste ještě nevrátil.</p>";
					}
					
					$query_t->close();
				}
				else
					echo $lang['error_cantDoStatement'];
			}
				
			$query->close();
		}
		else
		{
			echo $lang['error_cantDoStatement'];
			return;
		}
	}
	
	function getAccountPastesHistory()
	{
		global $mysqli;
		global $lang;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT id, name, date, syntax, public FROM sharecode WHERE author = ?"))
		{
			$query->bind_param("s", $_SESSION['user_email']);
			$query->execute();
			$query->store_result();
			$query->bind_result($pID, $pName, $pDate, $pSyntax, $pPublic);
			
			if($query->num_rows > 0)
			{
?>
<script>
	$(document).ready(function()
	{
		$(".spoilerPH").click(function()
		{
			switch($(".spoilerPH").html())
			{
				case "Skrýt":
					{
						$("#gaph").slideUp(2000);
						
						$(".spoilerPH").html("Zobrazit");
					}
					break;
				
				case "Zobrazit":
					{
						$("#gaph").slideDown(2000);
						
						$(".spoilerPH").html("Skrýt");
					}
					break;
			}
		});
	});
</script>
<?php
				echo "<div id='gaph'><table class='table table-bordered table-striped table-hover users-table'><tr><th>Název</th><th>Přidáno</th><th>Syntax</th><th>Publikace</th></tr>";
						
				while($query->fetch())
				{
					if((time() - strtotime($pDate)) / 60 < 1)
						$pDate = "Do minuty";
					else if((time() - strtotime($pDate)) / 3600 < 1)
						$pDate = "Do hodiny";
					else
						$pDate = date("H:i d. m. Y", strtotime($pDate));
						
					switch($pPublic)
					{
						case 0:
							$pPublic = "Veřejný";
							break;
							
						case 1:
							$pPublic = "Skrytý";
							break;
							
						case 2:
							$pPublic = "Privátní";
							break;
					}
					
					switch($pSyntax)
					{
						case "bash":
							$pSyntax = "Bash";
							break;
							
						case "applescript":
							$pSyntax = "AppleScript";
							break;
						
						case "actionscript":
							$pSyntax = "ActionScript";
							break;
							
						case "vbscript":
							$pSyntax = "VBScript";
							break;
							
						case "cs":
							$pSyntax = "C#";
							break;
							
						case "cpp":
							$pSyntax = "C++";
							break;
							
						case "java":
							$pSyntax = "Java";
							break;
							
						case "glsl":
							$pSyntax = "GLSL";
							break;
							
						case "css":
							$pSyntax = "CSS";
							break;
							
						case "xml":
							$pSyntax = "HTML/XML";
							break;
							
						case "php":
							$pSyntax = "PHP";
							break;
							
						case "javascript":
							$pSyntax = "Javascript";
							break;
							
						case "sql":
							$pSyntax = "SQL";
							break;
							
						case "json":
							$pSyntax = "JSON";
							break;
					}
					
					echo "<tr><td><a href='/pastecode/".$pID."/'>".$pName."</a></td><td>".$pDate."</td><td>".$pSyntax."</td><td>".$pPublic."</td></tr>";
				}
				
				echo "</table></div>";
				echo '<button class="btn btn-primary spoilerPH">Skrýt</button>';
			}
			else
				echo "<p>Ještě jste nevložili žádný kód.</p>";
			
			$query->close();
		}
		else
		{
			echo $lang['error_cantDoStatement'];
			return;
		}
	}
	
	function getAccountDevicesUsingCount()
	{
		global $mysqli;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT id FROM users WHERE email = ?"))
		{
			$query->bind_param("s", $_SESSION['user_email']);
			$query->execute();
			$query->store_result();
			$query->bind_result($accID);
			$query->fetch();
			
			$query2 = $mysqli->stmt_init();
			
			if($query2 = $mysqli->prepare("SELECT COUNT(id) FROM transfers WHERE user = ? AND status = 1"))
			{
				$query2->bind_param("i", $accID);
				$query2->execute();
				$query2->store_result();
				$query2->bind_result($count);
				$query2->fetch();
				
				echo $count;
				
				$query2->close();
			}
			else
				echo "Error";
			
			$query->close();
		}
		else
			echo "Error";
	}
	
	function getAccountDeviceHistoryCount()
	{
		global $mysqli;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT id FROM users WHERE email = ?"))
		{
			$query->bind_param("s", $_SESSION['user_email']);
			$query->execute();
			$query->store_result();
			$query->bind_result($accID);
			$query->fetch();
			
			$query2 = $mysqli->stmt_init();
			
			if($query2 = $mysqli->prepare("SELECT COUNT(id) FROM transfers WHERE user = ? AND status = 2"))
			{
				$query2->bind_param("i", $accID);
				$query2->execute();
				$query2->store_result();
				$query2->bind_result($count);
				$query2->fetch();
				
				echo $count;
				
				$query2->close();
			}
			else
				echo "Error";
			
			$query->close();
		}
		else
			echo "Error";
	}
	
	function getAccountDevicesRejetedCount()
	{
		global $mysqli;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT id FROM users WHERE email = ?"))
		{
			$query->bind_param("s", $_SESSION['user_email']);
			$query->execute();
			$query->store_result();
			$query->bind_result($accID);
			$query->fetch();
			
			$query2 = $mysqli->stmt_init();
			
			if($query2 = $mysqli->prepare("SELECT COUNT(id) FROM transfer_rejects WHERE user = ?"))
			{
				$query2->bind_param("i", $accID);
				$query2->execute();
				$query2->store_result();
				$query2->bind_result($count);
				$query2->fetch();
				
				echo $count;
				
				$query2->close();
			}
			else
				echo "Error";
			
			$query->close();
		}
		else
			echo "Error";
	}
	
	function getAccountPastecodeCount()
	{
		global $mysqli;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT COUNT(id) FROM sharecode WHERE author = ?"))
		{
			$query->bind_param("s", $_SESSION['user_email']);
			$query->execute();
			$query->store_result();
			$query->bind_result($count);
			$query->fetch();
			
			echo $count;
			
			$query->close();
		}
		else
			echo "Error";
	}
?>
<div class="page-header">
	<h3>Můj účet</h3>
</div>

<div class="article">
	<div class="title"><h4>Přehled</h4></div>
	
	<p>Vítejte, <b><?php echo $_SESSION['user_email']; ?></b>.</p>
	<br>
	
	<div class="well">
		<ul class="nav nav-list">
			<li class="nav-header">Status<p class="pull-right"><?php getAccountStatus(); ?></p></li>
			<li class="nav-header">Momentálně vypůjčených zařízení<p class="pull-right"><?php getAccountDevicesUsingCount(); ?></p></li>
			<li class="nav-header">Vypůjčené zařízení(v minulosti)<p class="pull-right"><?php getAccountDeviceHistoryCount(); ?></p></li>
			<li class="nav-header">Zamítnuto vypůčejní<p class="pull-right"><?php getAccountDevicesRejetedCount(); ?></p></li>
			<li class="nav-header">Naslídeno kodů<p class="pull-right"><?php getAccountPastecodeCount(); ?></p></li>
		</ul>
	</div>
</div>

<div class="article">
	<div class="title"><h4>Momentálně vypůjčené zařízení</h4></div>
	
	<?php getAccountDevicesUsing(); ?>
</div>

<div class="article">
	<div class="title"><h4>Historie zamítnutých zařízení</h4></div>
	
	<?php getAccountDevicesComments(); ?>
</div>

<div class="article">
	<div class="title"><h4>Historie vypůjčených zařízení</h4></div>
	
	<?php getAccountDevicesHistory(); ?>
</div>

<div class="article">
	<div class="title"><h4>Moje zdrojové kódy</h4></div>
	
	<?php getAccountPastesHistory(); ?>
</div>