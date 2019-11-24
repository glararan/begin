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
		$project = $_POST['project'];
		$type    = $_POST['type'];
		
		if(!isset($project) && !isset($type))
		{
			json_encode($lang['error_missingParameter']);
			return;
		}
		else
		{
			if($type != 0 && $type != 1 && $type != 2 && $type != 3)
			{
				json_encode($lang['error_missingParameter']);
				return;
			}
		}
		
		$data = array();
		
		if($type == 0) // add
		{
			$link   = $_POST['projectL'];
			$number = $_POST['projectN'];
			$year   = $_POST['projectY'];
			
			if(!isset($link) && !isset($number) && !isset($year))
			{
				json_encode($lang['error_missingParameter']);
				return;
			}
			else
			{
				if(!filter_var($number, FILTER_VALIDATE_INT))
				{
					json_encode($lang['error_missingParameter']);
					return;
				}
			}

			$query = $mysqli->stmt_init();

			if($query = $mysqli->prepare("INSERT INTO projects (name, link, number, year) VALUES(?, ?, ?, ?)"))
			{
				$query->bind_param("ssis", $project, $link, $number, $year);
			
				if($query->execute())
				{
					$query->store_result();
					
					$query2 = $mysqli->stmt_init();
					
					if($query2 = $mysqli->prepare("SELECT id FROM projects WHERE name = ?"))
					{
						$query2->bind_param("s", $project);
						$query2->execute();
						$query2->store_result();
						$query2->bind_result($projectID);
					
						if($query2->num_rows > 1)
						{
							$count = 0;
							
							while($query2->fetch())
							{
								if($count != $query2->num_rows - 1)
									$count++;
								else
									$projectID = $projectID;
							}
						}
						else
							$query2->fetch();
					
						$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné přidání:", "alert-text" => "Gratulujeme, právě jste přidali projekt!", "projectAdd_success" => 0, "projectID" => $projectID);
						
						$query2->close();
					}
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Něco se pokazilo s databází!", "projectAdd_success" => 1);
				}
				else
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Něco se pokazilo s databází!", "projectAdd_success" => 1);
				
				$query->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Něco se pokazilo s databází!", "projectAdd_success" => 1);
		}
		else if($type == 1) // delete
		{
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT name FROM projects WHERE id = ?"))
			{
				$query_check->bind_param("i", $project);
				$query_check->execute();
				$query_check->store_result();
			
				if($query_check->num_rows <= 0)
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Tento projekt neexistuje!", "projectDelete_success" => 1);
				else
				{
					$query = $mysqli->stmt_init();
					
					if($query = $mysqli->prepare("DELETE FROM projects WHERE id = ?"))
					{
						$query->bind_param("i", $project);

						if($query->execute())
							$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné odebrání:", "alert-text" => "Gratulujeme, právě jste odebrali projekt!", "projectDelete_success" => 0);
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Něco se pokazilo s databází!", "projectDelete_success" => 1);
							
						$query->close();
					}
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Něco se pokazilo s databází!", "projectDelete_success" => 1);
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Něco se pokazilo s databází!", "projectDelete_success" => 1);
		}
		else if($type == 2) // edit
		{
			$name   = $_POST['projectName'];
			$link   = $_POST['projectL'];
			$number = $_POST['projectN'];
			$year   = $_POST['projectY'];
			
			if(!isset($name) && !isset($link) && !isset($number) && !isset($year))
			{
				json_encode($lang['error_missingParameter']);
				return;
			}
			else
			{
				if(!filter_var($number, FILTER_VALIDATE_INT) && !filter_var($project, FILTER_VALIDATE_INT))
				{
					json_encode($lang['error_missingParameter']);
					return;
				}
			}
			
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT name FROM projects WHERE id = ?"))
			{
				$query_check->bind_param("i", $project);
				$query_check->execute();
				$query_check->store_result();
				
				if($query_check->num_rows <= 0)
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Tento projekt neexistuje!", "projectEdit_success" => 1);
				else
				{
					$query = $mysqli->stmt_init();
					
					if($query = $mysqli->prepare("UPDATE projects SET name = ?, link = ?, number = ?, year = ? WHERE id = ?"))
					{
						$query->bind_param("ssisi", $name, $link, $number, $year, $project);
					
						if($query->execute())
							$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné upravení:", "alert-text" => "Gratulujeme, právě jste upravili projekt!", "projectEdit_success" => 0);
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!", "projectEdit_success" => 1);
					}
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!", "projectEdit_success" => 1);
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!", "projectEdit_success" => 1);
		}
		else if($type == 3) // settings
		{
			$query = $mysqli->stmt_init();
			
			if($query = $mysqli->prepare("UPDATE settings SET value = ? WHERE name = ?"))
			{
				$parameter = "projects";
				
				$query->bind_param("ss", $project, $parameter);
				
				if($query->execute())
					$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné upravení:", "alert-text" => "Gratulujeme, právě jste upravili nastavení projektů!");
				else
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!");
				
				$query->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!");
		}
		
		echo json_encode($data);
	}
	else
	{
		json_encode($lang['dontHaveAccess']);
		return;
	}
?>