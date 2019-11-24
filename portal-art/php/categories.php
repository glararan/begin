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
		$category = $_POST['category'];
		$type     = $_POST['type'];
		
		if(!isset($category) && !isset($type))
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
		
		if($type == 0)
		{
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT name FROM categories WHERE name = ?"))
			{
				$query_check->bind_param("s", $category);
				$query_check->execute();
				$query_check->store_result();
			
				if($query_check->num_rows > 0)
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Tato kategorie existuje!", "categoryTableAdd_status" => 1);
				else
				{
					$query = $mysqli->stmt_init();
					
					if($query = $mysqli->prepare("INSERT INTO categories (name) VALUES(?)"))
					{
						$query->bind_param("s", $category);
					
						if($query->execute())
						{
							$query->store_result();
							
							$query2 = $mysqli->stmt_init();
							
							if($query2 = $mysqli->prepare("SELECT id FROM categories WHERE name = ?"))
							{
								$query2->bind_param("s", $category);
								$query2->execute();
								$query2->store_result();
								$query2->bind_result($categoryTID);
								
								while($query2->fetch())
									$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné přidání:", "alert-text" => "Gratulujeme, právě jste přidali kategorii!", "categoryTableAdd_status" => 0, "categoryTableID" => $categoryTID);
								
								$query2->close();
							}
							else
								$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Tato kategorie existuje!", "categoryTableAdd_status" => 1);
						}
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Něco se pokazilo s databází!", "categoryTableAdd_status" => 1);
							
						$query->close();
					}
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Tato kategorie existuje!", "categoryTableAdd_status" => 1);
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné přidání:", "alert-text" => "Něco se pokazilo s databází!", "categoryTableAdd_status" => 1);
			
			echo json_encode($data);
		}
		else if($type == 1)
		{
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT name FROM categories WHERE id = ?"))
			{
				$query_check->bind_param("i", $category);
				$query_check->execute();
				$query_check->store_result();
			
				if($query_check->num_rows <= 0)
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Tato kategorie neexistuje!", "categoryTableDelete_status" => 1);
				else
				{
					$query = $mysqli->stmt_init();
					
					if($query = $mysqli->prepare("DELETE FROM categories WHERE id = ?"))
					{
						$query->bind_param("i", $category);
					
						if($query->execute())
							$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné odebrání:", "alert-text" => "Gratulujeme, právě jste odebrali kategorii!", "categoryTableDelete_status" => 0);
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Něco se pokazilo s databází!", "categoryTableDelete_status" => 1);
							
						$query->close();
					}
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Něco se pokazilo s databází!", "categoryTableDelete_status" => 1);
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odebrání:", "alert-text" => "Něco se pokazilo s databází!", "categoryTableDelete_status" => 1);
			
			echo json_encode($data);
		}
		else if($type == 2)
		{
			$category_ID = $_POST['categoryID'];
			
			if(!isset($category_ID))
			{
				json_encode($lang['error_missingParameter']);
				return;
			}
			else
			{
				if(!filter_var($category_ID, FILTER_VALIDATE_INT))
				{
					json_encode($lang['error_missingParameter']);
					return;
				}
			}
			
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT name FROM categories WHERE name = ?"))
			{
				$query_check->bind_param("s", $category);
				$query_check->execute();
				$query_check->store_result();
			
				if($query_check->num_rows > 0)
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Tato kategorie již existuje!", "categoryTableEdit_status" => 1);
				else
				{
					$query = $mysqli->stmt_init();
					
					if($query = $mysqli->prepare("UPDATE categories SET name = ? WHERE id = ?"))
					{
						$query->bind_param("si", $category, $category_ID);
					
						if($query->execute())
							$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné upravení:", "alert-text" => "Gratulujeme, právě jste upravili kategorii!", "categoryTableEdit_status" => 0);
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!", "categoryTableEdit_status" => 1);
							
						$query->close();
					}
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!", "categoryTableEdit_status" => 1);
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné upravení:", "alert-text" => "Něco se pokazilo s databází!", "categoryTableEdit_status" => 1);
			
			echo json_encode($data);
		}
	}
	else
	{
		json_encode($lang['dontHaveAccess']);
		return;
	}
?>