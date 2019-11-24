<?php
	session_start();

	// Připojení k MySQL
    @mysql_connect("wm27.wedos.net", "a8403_palermo", "qwedsayxc789") or die("Nelze se připojit do databáze.");
    @mysql_select_db("d8403_palermo") or die("Databáze nebyla nalezena.");
    // Konec
   
    if(isset($_POST['buttonShow']))
	{
		// is leader
		$query_IsLeader  = mysql_query("SELECT leader FROM room_players WHERE player = '".$_SESSION['username']."'");
		$result_IsLeader = mysql_result($query_IsLeader, 0);
		
		if($result_IsLeader == 1)
		{
			$query_room_players  = mysql_query("SELECT COUNT(*) FROM room_players WHERE id = ".$_SESSION['room_id']);
			$result_room_players = mysql_result($query_room_players, 0);
			
			if($result_room_players >= 4)
				echo '<input type="button" class="button" tabindex="0" value="Start..." onClick="startGame()">';
			else
			{
				switch($result_room_players)
				{
					case 3:
						$text = "1 hráč...";
						break;
						
					case 2:
						$text = "2 hráči...";
						break;
						
					case 1:
						$text = "3 hráči...";
						break;
						
					case 0:
						$text = "4 hráči...";
						break;
				}
				
				echo '<input type="button" class="button" tabindex="0" value="'.$text.'" disabled>';
			}
		}
    }
	
	if(isset($_POST['showPlayers']))
	{
		$query_room_players = mysql_query("SELECT * FROM room_players WHERE id = ".$_SESSION['room_id']);
		
		if(isset($_SESSION['room_id']))
		{
			while($row = mysql_fetch_array($query_room_players))
			{
				$rank = $row['leader'];
						
				if($rank == 0)
					$rank = "Hráč";
				else
					$rank = "Host";
				
				if($row['player'] != $_SESSION['username'])
					$td_color = 'blue';
				else
					$td_color = 'orange';
					
				if($row['deceased'] == 1)
					$deceased = true;
								
				echo '<tr><td class="'.$td_color.'">'.$row['player'].'</td><td>'.$rank.'</td>'.$deceased ? "<td class=\"red\">Mrtvý</td>" : "".'</tr>';
			}
		}
		else
			echo '<meta http-equiv="refresh" content="0;URL=\'/room/\'">';
    }
	
	if(isset($_POST['startgame']))
	{
		$query_room_players  = mysql_query("SELECT COUNT(*) FROM room_players WHERE id = ".$_SESSION['room_id']);
		$result_room_players = mysql_result($query_room_players, 0);
		
		$query_IsLeader  = mysql_query("SELECT leader FROM room_players WHERE player = '".$_SESSION['username']."'");
		$result_IsLeader = mysql_result($query_IsLeader, 0);
		
		$query_GameStatus  = mysql_query("SELECT status FROM room WHERE id = ".$_SESSION['room_id']);
		$result_GameStatus = mysql_result($query_GameStatus, 0);
		
		if($result_room_players >= 4 && $result_IsLeader == 1 && $result_GameStatus == 0)
		{
			// start play
			mysql_query("UPDATE room SET status = 1 WHERE id = ".$_SESSION['room_id']);
			
			// variables
			$mafians = 0;
			$justice = 0;
			$citizen = 0;
			$players = 0;
			
			function calculateCitizen($_players)
			{
				global $mafians, $justice, $citizen, $players;
				
				$citizen = $_players - $mafians - $justice;
				$players = $_players;
			}
			
			// system calculating: mafians, justice, citizen and players in room
			switch($result_room_players)
			{
				case 10:
					$mafians = 3;
					$justice = 2;
					calculateCitizen(10);
					break;
					
				case 9:
					$mafians = 3;
					$justice = 1;
					calculateCitizen(9);
					break;
					
				case 8:
					$mafians = 2;
					$justice = 1;
					calculateCitizen(8);
					break;
					
				case 7:
					$mafians = 2;
					$justice = 1;
					calculateCitizen(7);
					break;
					
				case 6:
					$mafians = 2;
					$justice = 1;
					calculateCitizen(6);
					break;
					
				case 5:
					$mafians = 1;
					$justice = 1;
					calculateCitizen(5);
					break;
					
				case 4:
					$mafians = 1;
					calculateCitizen(4);
					break;
			}
			
			// system choose role for each player
			$p_mafians = array();
			$p_justice = array();
			$p_citizen = array();
			
			for($i = 0; $i < $mafians; $i++)
			{
				$player_index = rand(1, $players);
				
				$continue = true;
				
				foreach($p_mafians as $mafian)
				{
					if($mafian == $player_index)
					{
						$i--;
						$continue = false;
						
						break;
					}
				}
				
				if(!$continue)
					continue;
				else
					$p_mafians[$i] = $player_index;
			}
			
			for($i = 0; $i < $justice; $i++)
			{
				$player_index = rand(1, $players);
				
				$continue = true;
				
				foreach($p_mafians as $mafian)
				{
					if($mafian == $player_index)
					{
						$i--;
						$continue = false;
						
						break;
					}
				}
				
				if(!$continue)
					continue;
					
				foreach($p_justice as $justic)
				{
					if($justic == $player_index)
					{
						$i--;
						$continue = false;
						
						break;
					}
				}
				
				if(!$continue)
					continue;
				else
					$p_justice[$i] = $player_index;
			}
			
			for($i = 0; $i < $citizen; $i++)
			{
				$player_index = rand(1, $players);
				
				$continue = true;
				
				foreach($p_mafians as $mafian)
				{
					if($mafian == $player_index)
					{
						$i--;
						$continue = false;
						
						break;
					}
				}
				
				if(!$continue)
					continue;
					
				foreach($p_justice as $justic)
				{
					if($justic == $player_index)
					{
						$i--;
						$continue = false;
						
						break;
					}
				}
				
				if(!$continue)
					continue;
					
				foreach($p_citizen as $person)
				{
					if($person == $player_index)
					{
						$i--;
						$continue = false;
						
						break;
					}
				}
				
				if(!$continue)
					continue;
				else
					$p_citizen[$i] = $player_index;
			}
			
			// set roles
			$query_set_roles = mysql_query("SELECT player FROM room_players WHERE id = ".$_SESSION['room_id']);
			
			foreach($p_mafians as $mafian)
			{
				$result_set_roles = mysql_result($query_set_roles, $mafian - 1); // -1 because starting at 0
				
				mysql_query("UPDATE room_players SET mafian = 1 WHERE player = '".$result_set_roles."'");
			}
			
			foreach($p_justice as $justic)
			{
				$result_set_roles = mysql_result($query_set_roles, $justic - 1); // -1 because starting at 0
				
				mysql_query("UPDATE room_players SET justice = 1 WHERE player = '".$result_set_roles."'");
			}
			
			foreach($p_citizen as $person)
			{
				$result_set_roles = mysql_result($query_set_roles, $person - 1); // -1 because starting at 0
				
				mysql_query("UPDATE room_players SET citizen = 1 WHERE player = '".$result_set_roles."'");
			}
		}
	}
	
	if(isset($_POST['showgamestarted']))
	{
		$query_room_started  = mysql_query("SELECT status FROM room WHERE id = ".$_SESSION['room_id']);
		$result_room_started = mysql_result($query_room_started, 0);
		
		echo $result_room_started;
	}
	
	if(isset($_POST['autoendgame']))
	{
		$query_autoend  = mysql_query("SELECT autoend FROM room WHERE id = ".$_SESSION['room_id']);
		$result_autoend = mysql_result($query_autoend, 0);
		
		if($result_autoend == 1)
			echo "true";
	}
	
	if(isset($_POST['gamebot']))
	{
		echo "Hra začala!<br>";
		
		$query_rooom_player_role = mysql_query("SELECT * FROM room_players WHERE player = '".$_SESSION['username']."'");
		
		while($row = mysql_fetch_array($query_rooom_player_role))
		{
			$isMafia   = $row['mafian'];
			$isJustice = $row['justice'];
			$isCitizen = $row['citizen'];
			
			if($isMafia == 1)
				echo "Jste mafián!<br>";
			else if($isJustice == 1)
				echo "Jste soukromý detektiv!<br>";
			else if($isCitizen == 1)
				echo "Jste občan!<br>";
			else
				echo "Error: nemáte roli!";
		}
		
		$query_room_player_bot_chat = mysql_query("SELECT * FROM room_players_game_direction WHERE player = '".$_SESSION['username']."'");
		
		while($row = mysql_fetch_array($query_room_player_bot_chat))
			echo $row['text'] + '<br>';
		
		while($row = mysql_fetch_array($query_rooom_player_role))
		{
			if($row['deceased'] == 1)
				echo "Jste mrtvý!<br>";
		}
	}
	
	if(isset($_POST['showgamecontent']))
	{
		$query_rooom_player_role = mysql_query("SELECT * FROM room_players WHERE player = '".$_SESSION['username']."'");
		
		while($row = mysql_fetch_array($query_rooom_player_role))
		{
			$isMafia   = $row['mafian'];
			$isJustice = $row['justice'];
			$isCitizen = $row['citizen'];
			
			if($isMafia == 1)
				$role = "mafian";
			else if($isJustice == 1)
				$role = "justice";
			else if($isCitizen == 1)
				$role = "citizen";
		}
		
		$query_room_players = mysql_query("SELECT * FROM room_players WHERE id = ".$_SESSION['room_id']);
		
		while($row = mysql_fetch_array($query_room_players))
		{
			if($row['deceased'] == 0)
			{
				switch($role)
				{
					case "mafian":
						{
							if($row['mafian'] == 1)
								$_gameContent += "<input type='button' class='mafian' value='' disabled>";
							else
								$_gameContent += "<input type='button' class='citizen' value='' disabled>"; 
						}
						break;
						
					case "justice":
						{
							if($row['justice'] == 1)
								$_gameContent += "<input type='button' class='justice' value='' disabled>";
							else
								$_gameContent += "<input type='button' class='citizen' value='' disabled>"; 
						}
						break;
						
					case "citizen":
						{
							$_gameContent += "<input type='button' class='citizen' value='' disabled>"; 
						}
						break;
				}
				
				echo $_gameContent;
			}
		}
	}
	
	// chat
	if(isset($_GET['newmsg']))
	{
    	if(isset($_GET['text']))
		{
        	if(!empty($_GET['text']))
            	mysql_query("INSERT INTO `chat` (`user`, `text`, `date`, `room`, `room_id`, `mafie`) VALUES('".$_SESSION['username']."', '".mysql_real_escape_string(addslashes($_GET['text']))."', ".Date("U").", 1, ".$_SESSION['room_id'].", ".$_GET['option'].")");
		}
    }
   
    if(isset($_POST['showroomchat']))
	{
    	$getmsgs = mysql_query("SELECT * FROM `chat` WHERE `room_id` = ".$_SESSION['room_id']." ORDER BY `date` DESC LIMIT 20");
		
        while($msg = mysql_fetch_array($getmsgs))
		{
			if($msg['mafie'] == 1)
				echo "<div class='message'><p><span>[".Date("d.m v H:i", $msg['date'])."] <div class='user'>".$msg['user']." (mafie)</div>:</span>\n";
			else
        		echo "<div class='message'><p><span>[".Date("d.m v H:i", $msg['date'])."] <div class='user'>".$msg['user']."</div>:</span>\n";
        	
            echo $msg['text']."\n";
            echo "</p></div>\n";
		}
    }
?>