<?php
	session_start();
	
	if (!isset($_SESSION['rank']) && ($_SESSION['rank'] != 'admin' || $_SESSION['rank'] != 'user'))
		exit($lang['dontHaveAccess']);
	
	// Připojení k databázi
	$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
	$mysqli->set_charset('utf8');
	
	if (mysqli_connect_errno()) 
		die(mysqli_connect_errno());
		
	function getTransfers()
	{
		global $mysqli;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT fromDate, toDate, user, device FROM transfers WHERE status = 1"))
		{
			$query->execute();
			$query->store_result();
			$query->bind_result($fromDate, $toDate, $userID, $deviceID);
		
			echo "<table class='table table-bordered table-striped table-hover users-table'><tr><th>Email studenta</th><th>Název zařízení</th><th class='width12p'>Datum vypůjčení</th><th class='width12p'>Datum vrácení</th></tr>";
		
			while ($query->fetch())
			{
				$query_u = $mysqli->stmt_init();
				
				if($query_u = $mysqli->prepare("SELECT email FROM users WHERE id = ?"))
				{
					$query_u->bind_param("i", $userID);
					$query_u->execute();
					$query_u->store_result();
					$query_u->bind_result($userEmail);
					
					while($query_u->fetch())
					{
						$query_d = $mysqli->stmt_init();
						
						if($query_d = $mysqli->prepare("SELECT name FROM devices WHERE id = ?"))
						{
							$query_d->bind_param("i", $deviceID);
							$query_d->execute();
							$query_d->store_result();
							$query_d->bind_result($deviceName);
							
							while($query_d->fetch())
							{
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
								
								echo "<tr><td>".$userEmail."</td><td>".$deviceName."</td><td>".$fromDate."</td><td>".$toDate."</td></tr>";
							}
							
							$query_d->close();
						}
					}
					
					$query_u->close();
				}
			}
	
			echo "</table>";
			
			$query->close();
		}
		else
		{
			echo $lang['error_cantDoStatement'];
			return;
		}
	}
	
	function getTransfersHistory()
	{
		global $mysqli;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT id, fromDate, toDate, user, admin, device, comment FROM transfers WHERE status = 2"))
		{
			$query->execute();
			$query->store_result();
			$query->bind_result($transferID, $fromDate, $toDate, $userID, $adminID, $deviceID, $transferComment);
			
			echo "<table class='table table-bordered table-striped table-hover users-table'><tr><th width=2%>ID</th><th>Email studenta</th><th>Název zařízení</th><th>Půjčil</th><th class='width9p'>Od</th><th class='width9p'>Do</th><th>Komentář</th></tr>";
			
			while($query->fetch())
			{
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
				
				$query_u = $mysqli->stmt_init();
				
				if($query_u = $mysqli->prepare("SELECT email FROM users WHERE id = ?"))
				{
					$query_u->bind_param("i", $userID);
					$query_u->execute();
					$query_u->store_result();
					$query_u->bind_result($userEmail);
				
					while($query_u->fetch())
					{	
						$query_a = $mysqli->stmt_init();
						
						if($query_a = $mysqli->prepare("SELECT email FROM admins WHERE id = ?"))
						{
							$query_a->bind_param("i", $adminID);
							$query_a->execute();
							$query_a->store_result();
							$query_a->bind_result($adminEmail);
						
							while($query_a->fetch())
							{
								$query_d = $mysqli->stmt_init();
								
								if($query_d = $mysqli->prepare("SELECT name FROM devices WHERE id = ?"))
								{
									$query_d->bind_param("i", $deviceID);
									$query_d->execute();
									$query_d->store_result();
									$query_d->bind_result($deviceName);
								
									while($query_d->fetch())
										echo "<tr><td>".$transferID."</td><td>".$userEmail."</td><td>".$deviceName."</td><td>".$adminEmail."</td><td>".$fromDate."</td><td>".$toDate."</td><td>".empty($transferComment) == true ? "Žádný" : $transferComment."</td></tr>";
									
									$query_d->close();
								}
							}
							
							$query_a->close();
						}
					}
					
					$query_u->close();
				}
			}
			
			echo "</table>";
			
			$query->close();
		}
		else
		{
			echo $lang['error_cantDoStatement'];
			return;
		}
	}
?>

<div class="page-header">
	<h3>Vypůjčení</h3>
</div>

<div class="article">
	<div class="title"><h4>Stav</h4></div>
	
	<div id="transactions">
		<?php getTransfers(); ?>
	</div>
</div>

<?php
	if($_SESSION['rank'] == 'admin')
	{
?>
<div class="article">
	<div class="title"><h4>Historie</h4></div>
	
	<div id="history_transactions">
		<?php getTransfersHistory(); ?>
	</div>
</div>
<?php
	}
?>