<?php
	session_start();

	// Připojení k databázi
	$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
	$mysqli->set_charset('utf8');
	
	if (mysqli_connect_errno()) 
		die(mysqli_connect_errno());
		
	function getYear()
	{
		global $mysqli;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT value FROM settings WHERE name = ?"))
		{
			$parameter = "projects";
			
			$query->bind_param("s", $parameter);
			$query->execute();
			$query->store_result();
			$query->bind_result($pYear);
			$query->fetch();
			
			$query->close();
		}
		else
			$pYear = 0;
			
		return $pYear;
	}
	
	$gYear = getYear();
		
	function getProjects()
	{
		global $mysqli;
		global $gYear;
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT name, link, number FROM projects WHERE year = ?"))
		{
			$query->bind_param("s", $gYear);
			$query->execute();
			$query->store_result();
			$query->bind_result($pName, $pLink, $pNumber);
			
			while($query->fetch())
				echo "<li><a href='".$pLink."'><div><p class='margin0 pull-left'>".$pName."</p><p class='margin0 text-right'>".$pNumber."</p></div></a></li>";
				
			$query->close();
		}
		else
			echo "Chyba s databází.";
	}
?>

<div class="page-header">
	<h3>Projekty</h3>
</div>

<div class="article">
	<div class="title"><h4>Rok <?php echo $gYear; ?></h4></div>
	
	<div class="well">
		<ul id="categories" class="nav nav-list">	
			<?php getProjects(); ?>
		</ul>
	</div>
</div>