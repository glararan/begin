<?php
	require 'common.php';
	
	// Připojení k databázi
	$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
	$mysqli->set_charset('utf8');
	
	if (mysqli_connect_errno()) 
	{
		echo json_encode('database error');
		return;
	}
	
	$all = $_POST['all'];
	
	if(!isset($all))
		$all = 0;
	else
	{
		if($all == "true")
			$all = 3;
		else
			$all = 0;
	}
	
	$status = $all;
		
	$query = 'SELECT devices.id, devices.name, nick, description, status, categories.name AS category FROM devices JOIN categories ON devices.category = categories.id WHERE status <= '.$status.' ORDER BY devices.category';
	$result = $mysqli->query($query);
	$categories = array();
	
	while ($device = $result->fetch_assoc())
	{
		$ok = false;
		
		for($i = 0; $i < count($categories); $i++)
		{
			if ($categories[$i]['name'] == $device['category'])
			{
				array_push($categories[$i]['devices'], $device);
				$ok = true;
				break;
			}
		}
		
		if (!$ok)
			array_push($categories, array('name' => $device['category'], 'devices' => array($device)));
	}
	
	echo json_encode($categories);
?>