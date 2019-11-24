<?php
	session_start();

	require 'common.php';
	
	// Připojení k databázi
	$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
	$mysqli->set_charset('utf8');
	
	if (mysqli_connect_errno()) 
	{
		echo json_encode('database error');
		return;
	}
	
	if (isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin')
	{
		$device = $_POST['device'];
		$type   = $_POST['type'];
		
		if(!isset($device) && !isset($type))
		{	
			json_encode($lang['error_missingParameter']);
			return;
		}
		else
		{
			if($type != 0 && $type != 1 && $type != 2)
			{
				json_encode($lang['error_missingParameter']);
				return;
			}
		}
		
		$data = array();
		
		// add
		if($type == 0)
		{			
			$nick        = $_POST['nick'];
			$description = $_POST['descr'];
			$status      = $_POST['status'];
			$category    = $_POST['category'];
			
			if(!isset($nick) && !isset($description) && !isset($status) && !isset($category))
			{
				json_encode($lang['error_missingParameter']);
				return;
			}
			else
			{
				if(!filter_var($status, FILTER_VALIDATE_INT) && !filter_var($category, FILTER_VALIDATE_INT))
				{
					json_encode($lang['error_missingParameter']);
					return;
				}
				else
				{
					if($status != 0 && $status != 1 && $status != 2 && $status != 3)
					{
						json_encode($lang['error_missingParameter']);
						return;
					}
				}
			}

			$query = $mysqli->stmt_init();

			if($query = $mysqli->prepare("INSERT INTO devices (name, nick, description, status, category) VALUES(?, ?, ?, ?, ?)"))
			{
				$query->bind_param("sssdd", $device, $nick, $description, $status, $category);
			
				if($query->execute())
				{
					$query->store_result();
					
					$query2 = $mysqli->stmt_init();
					
					if($query2 = $mysqli->prepare("SELECT id FROM devices WHERE name = ?"))
					{
						$query2->bind_param("s", $device);
						$query2->execute();
						$query2->store_result();
						$query2->bind_result($deviceTID);
					
						if($query2->num_rows > 1)
						{
							$count = 0;
							
							while($query2->fetch())
							{
								if($count != $query2->num_rows - 1)
									$count++;
								else
									$deviceTID = $deviceTID;
							}
						}
						else
						{
							while($query2->fetch())
								;
						}
					
						$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné přidání:", "alert-text" => "Gratulujeme, právě jste přidali zařízení!", "deviceTableAdd_status" => 0, "deviceTableID" => $deviceTID);
						
						$query2->close();
					}
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Něco se pokazilo s databází!", "deviceTableAdd_status" => 1);
				}
				else
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Něco se pokazilo s databází!", "deviceTableAdd_status" => 1);
				
				$query->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Něco se pokazilo s databází!", "deviceTableAdd_status" => 1);
				
			echo json_encode($data);
		}
		else if($type == 1) // delete
		{
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT name FROM devices WHERE id = ?"))
			{
				$query_check->bind_param("i", $device);
				$query_check->execute();
				$query_check->store_result();
			
				if($query_check->num_rows <= 0)
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Toto zařízení neexistuje!", "deviceTableDelete_status" => 1);
				else
				{
					$query = $mysqli->stmt_init();
					
					if($query = $mysqli->prepare("DELETE FROM devices WHERE id = ?"))
					{
						$query->bind_param("i", $device);

						if($query->execute())
							$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné odebrání:", "alert-text" => "Gratulujeme, právě jste odebrali zařízení!", "deviceTableDelete_status" => 0);
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Něco se pokazilo s databází!", "deviceTableDelete_status" => 1);
							
						$query->close();
					}
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Něco se pokazilo s databází!", "deviceTableDelete_status" => 1);
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Něco se pokazilo s databází!", "deviceTableDelete_status" => 1);
			
			echo json_encode($data);
		}
		else if($type == 2) // edit
		{
			$device_ID   = $_POST['deviceID'];
			$nick        = $_POST['nick'];
			$description = $_POST['descr'];
			$status      = $_POST['status'];
			$category    = $_POST['category'];
			
			if(!isset($device_ID) && !isset($nick) && !isset($description) && !isset($status) && !isset($category))
			{
				json_encode($lang['error_missingParameter']);
				return;
			}
			else
			{
				if(!filter_var($status, FILTER_VALIDATE_INT) && !filter_var($category, FILTER_VALIDATE_INT) && !filter_var($device_ID, FILTER_VALIDATE_INT))
				{
					json_encode($lang['error_missingParameter']);
					return;
				}
				else
				{
					if($status != 0 && $status != 1 && $status != 2 && $status != 3)
					{
						json_encode($lang['error_missingParameter']);
						return;
					}
				}
			}
			
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT name FROM devices WHERE id = ?"))
			{
				$query_check->bind_param("i", $device_ID);
				$query_check->execute();
				$query_check->store_result();
				
				if($query_check->num_rows <= 0)
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Toto zařízení neexistuje!", "deviceTableEdit_status" => 1);
				else
				{
					$query = $mysqli->stmt_init();
					
					if($query = $mysqli->prepare("UPDATE devices SET name = ?, nick = ?, description = ?, status = ?, category = ? WHERE id = ?"))
					{
						$query->bind_param("sssddd", $device, $nick, $description, $status, $category, $device_ID);
					
						if($query->execute())
							$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné upravení:", "alert-text" => "Gratulujeme, právě jste upravili zařízení!", "deviceTableEdit_status" => 0);
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!", "deviceTableEdit_status" => 1);
					}
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!", "deviceTableEdit_status" => 1);
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!", "deviceTableEdit_status" => 1);
			
			echo json_encode($data);
		}
	}
	else
	{
		json_encode($lang['dontHaveAccess']);
		return;
	}
?>