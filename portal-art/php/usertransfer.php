<?php
	session_start();

	require 'common.php';
	
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
	{
		echo json_encode('database error');
		return;
	}
	
	if (isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin')
	{
		$transferID = $_POST['transferID'];
		$type = $_POST['type'];
		
		if(!isset($transferID) && !isset($type))
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
		
		// accept
		if($type == 0)
		{
			$adminComment = $_POST['adminText'];
			
			if($adminComment == "")
				$adminComment = "Žádný";
				
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT status FROM transfers WHERE id = ?"))
			{
				$query_check->bind_param("i", $transferID);
				$query_check->execute();
				$query_check->store_result();
				$query_check->bind_result($checkStatus);
				
				while($query_check->fetch())
				{
					if($query_check->num_rows <= 0)
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Takový požadavek na schválení neni v databázi!", "transfer_status" => 1);
					else
					{
						if($checkStatus == 0)
						{
							$query2 = $mysqli->stmt_init();
											
							if($query2 = $mysqli->prepare("SELECT user, device FROM transfers WHERE id = ?"))
							{
								$query2->bind_param("i", $transferID);
								$query2->execute();
								$query2->store_result();
								$query2->bind_result($userID, $deviceID);
							
								while($query2->fetch())
								{
									$query_ds = $mysqli->stmt_init();
									
									if($query_ds = $mysqli->prepare("SELECT status FROM devices WHERE id = ?"))
									{
										$query_ds->bind_param("i", $deviceID);
										$query_ds->execute();
										$query_ds->store_result();
										$query_ds->bind_result($deviceStatus);
										
										while($query_ds->fetch())
										{
											if($deviceStatus != 0)
												$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Zařízení je již vypůjčeno!", "transfer_status" => 1);
											else
											{
												$query_a = $mysqli->stmt_init();
												
												if($query_a = $mysqli->prepare("SELECT id FROM admins WHERE email = ?"))
												{
													$query_a->bind_param("s", $_SESSION['user_email']);
													$query_a->execute();
													$query_a->store_result();
													$query_a->bind_result($adminID);
												
													while($query_a->fetch())
													{
														$query = $mysqli->stmt_init();
														
														if($query = $mysqli->prepare("UPDATE transfers SET admin = ?, adminText = ?, status = 1 WHERE id = ?"))
														{
															$query->bind_param("isi", $adminID, $adminComment, $transferID);
															$query->execute();
															$query->store_result();
													
															if($query->execute())
															{	
																$query_ue = $mysqli->stmt_init();
																
																if($query_ue = $mysqli->prepare("SELECT email FROM users WHERE id = ?"))
																{
																	$query_ue->bind_param("i", $userID);
																	$query_ue->execute();
																	$query_ue->store_result();
																	$query_ue->bind_result($userEmail);
																
																	while($query_ue->fetch())
																	{
																		$query_dn = $mysqli->stmt_init();
																		
																		if($query_dn = $mysqli->prepare("SELECT name FROM devices WHERE id = ?"))
																		{
																			$query_dn->bind_param("i", $deviceID);
																			$query_dn->execute();
																			$query_dn->store_result();
																			$query_dn->bind_result($deviceName);
																		
																			while($query_dn->fetch())
																			{
																				$query3 = $mysqli->stmt_init();
																				
																				if($query3 = $mysqli->prepare("UPDATE devices SET status = 1 WHERE id = ?"))
																				{
																					$query3->bind_param("i", $deviceID);
																				
																					if($query3->execute())
																						$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné schválení:", "alert-text" => "Práve jste schválili vypůjčení!", "transfer_status" => 0, "id" => $transferID, "deviceName" => $deviceName, "userEmail" => $userEmail, "adminEmail" => $_SESSION['user_email']);
																					else
																						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
																						
																					$query3->close();
																				}
																				else
																					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
																			}
																			
																			$query_dn->close();
																		}
																		else
																			$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
																	}
																	
																	$query_ue->close();
																}
																else
																	$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
															}
															else
																$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
																
															$query->close();
														}
														else
															$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
													}
														
													$query_a->close();
												}
												else
													$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
											}
										}
										
										$query_ds->close();
									}
									else
										$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
								}
								
								$query2->close();
							}
							else
								$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);		
						}
						else if($checkStatus == 1)
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Toto bylo někým před Vámi schváleno!", "transfer_status" => 1);
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
					}
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné schválení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
			
			echo json_encode($data);
		}
		else if($type == 1) // reject
		{
			$adminComment = $_POST['adminText'];
			
			if($adminComment == "")
				$adminComment = "Žádný";
			
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT status, user, device FROM transfers WHERE id = ?"))
			{
				$query_check->bind_param("i", $transferID);
				$query_check->execute();
				$query_check->store_result();
				$query_check->bind_result($checkStatus, $transerUserID, $transferDeviceID);
				
				while($query_check->fetch())
				{
					if($query_check->num_rows <= 0)
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné zamítnutí:", "alert-text" => "Tato položka na zamítnutí neexistuje!", "transfer_status" => 1);
					else
					{
						if($checkStatus == 0)
						{
							$query_admin = $mysqli->stmt_init();
							
							if($query_admin = $mysqli->prepare("SELECT id FROM admins WHERE email = ?"))
							{
								$query_admin->bind_param("s", $_SESSION['user_email']);
								$query_admin->execute();
								$query_admin->store_result();
								$query_admin->bind_result($adminID);
								$query_admin->fetch();
								
								$query_comment = $mysqli->stmt_init();
							
								if($query_comment = $mysqli->prepare("INSERT INTO transfer_rejects (user, admin, device, comment) VALUES(?, ?, ?, ?)"))
								{
									$query_comment->bind_param("iiis", $transerUserID, $adminID, $transferDeviceID, $adminComment);
									
									if($query_comment->execute())
									{
										$query_comment->store_result();
										
										$query = $mysqli->stmt_init();
							
										if($query = $mysqli->prepare("DELETE FROM transfers WHERE id = ?"))
										{
											$query->bind_param("i", $transferID);
											
											if($query->execute())
												$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné zamítnutí:", "alert-text" => "Právě jste zamítli vypůjčení!", "transfer_status" => 0);
											else
												$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné zamítnutí:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
												
											$query->close();
										}
										else
											$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné zamítnutí:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
									}
									else
										$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné zamítnutí:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
									
									$query_comment->close();
								}
								else
									$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné zamítnutí:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
								
								$query_admin->close();
							}
							else
								$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné zamítnutí:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
							
							
						}
						else if($checkStatus == 1)
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné zamítnutí:", "alert-text" => "Toto bylo někým před Vámi schváleno!", "transfer_status" => 1);
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné zamítnutí:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
					}
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné zamítnutí:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
			
			echo json_encode($data);
		}
		else if($type == 2) // return
		{
			$endComment = $_POST['endText'];
			$returnSelect = $_POST['returnSelect'];
			
			if(!isset($returnSelect))
			{
				echo json_encode($lang['error_missingParameter']);
				return;
			}
			else
			{
				if($returnSelect != 0 && $returnSelect != 2 && $returnSelect != 3)
				{
					echo json_encode($lang['error_missingParameter']);
					return;
				}
			}
			
			if($endComment == "")
				$endComment = "Žádný";
			
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT status FROM transfers WHERE id = ?"))
			{
				$query_check->bind_param("i", $transferID);
				$query_check->execute();
				$query_check->store_result();
				$query_check->bind_result($checkStatus);
			
				if($query_check->num_rows <= 0)
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné vrácení:", "alert-text" => "Tato položka na vrácení neexistuje!", "transfer_status" => 1);
				else
				{
					if($checkStatus == 1)
					{
						$query = $mysqli->stmt_init();
						
						if($query = $mysqli->prepare("UPDATE transfers SET status = 2, comment = ? WHERE id = ?"))
						{
							$query->bind_param("si", $endComment, $transferID);
						
							if($query->execute())
							{
								$query->store_result();
								
								$query2 = $mysqli->stmt_init();
								
								if($query2 = $mysqli->prepare("SELECT device FROM transfers WHERE id = ?"))
								{
									$query2->bind_param("i", $transferID);
									$query2->execute();
									$query2->store_result();
									$query2->bind_result($deviceID);
								
									while($query2->fetch())
									{
										$query3 = $mysqli->stmt_init();
										
										if($query3 = $mysqli->prepare("UPDATE devices SET status = ? WHERE id = ?"))
										{
											$query3->bind_param("ii", $returnSelect, $deviceID);
										
											if($query3->execute())
												$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné vrácení:", "alert-text" => "Gratulujeme, právě jste vrátili položku!", "transfer_status" => 0);
											else
												$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné vrácení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
											
											$query3->close();
										}
										else
											$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné vrácení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
									}
									
									$query2->close();
								}
								else
									$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné vrácení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
							}
							else
								$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné vrácení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
								
							$query->close();
						}
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné vrácení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
					}
					else if($checkStatus == 0)
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné vrácení:", "alert-text" => "Toto je ještě ve stavu zažádání!", "transfer_status" => 1);
					else if($checkStatus == 2)
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné vrácení:", "alert-text" => "Toto již bylo vráceno!", "transfer_status" => 1);
				}
				
				$query_check->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné vrácení:", "alert-text" => "Něco se pokazilo s databází!", "transfer_status" => 1);
			
			echo json_encode($data);
		}
	}
	else
	{
		json_encode($lang['dontHaveAccess']);
		return;
	}
?>