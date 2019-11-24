<?php
	session_start();

	// Připojení k MySQL
    @mysql_connect("wm27.wedos.net", "a8403_palermo", "qwedsayxc789") or die("Nelze se připojit do databáze.");
    @mysql_select_db("d8403_palermo") or die("Databáze nebyla nalezena.");
    // Konec

		$query_room_players  = mysql_query("SELECT COUNT(*) FROM room_players WHERE id = ".$_SESSION['room_id']);
		$result_room_players = mysql_result($query_room_players, 0);
		
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
				
				if($continue)
				{
					$p_mafians[$i] = $player_index;
					
					echo "mafiansky index ".$i.": ".$player_index."<br>";
				}
				else
					continue;
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
				
				if($continue)
				{
					$p_justice[$i] = $player_index;
						
					echo "justice index ".$i.": ".$player_index."<br>";
				}
				else
					continue;
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
						echo "breaking in citizen on mafian i-".($i + 1).", because player_index is: ".$player_index;
						
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
						echo "breaking in citizen on justice i-".($i + 1).", because player_index is: ".$player_index;
						
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
						echo "breaking in citizen on person i-".($i + 1).", because player_index is: ".$player_index;
						
						break;
					}
				}
				
				if($continue)
				{	
					$p_citizen[$i] = $player_index;
						
					echo "citizen index ".$i.": ".$player_index."<br>";
				}
				else
					continue;
			}
			
			// set roles
			$query_set_roles = mysql_query("SELECT player FROM room_players WHERE id = ".$_SESSION['room_id']);
			echo "<br>---".$mafians;
			echo $justice;
			echo $citizen;
			echo $players."---<br>";
			
			foreach($p_mafians as $mafian)
			{
				$result_set_roles = mysql_result($query_set_roles, $mafian - 1);
				
				echo "mafian: ".$result_room_players." - ".$mafian;
			}
			
			foreach($p_justice as $justic)
			{
				$result_set_roles = mysql_result($query_set_roles, $justic - 1);
				
				echo "justice: ".$result_room_players." - ".$justic;
			}
			
			foreach($p_citizen as $person)
			{
				$result_set_roles = mysql_result($query_set_roles, $person - 1);
				
				echo "citizen: ".$result_room_players." - ".$person;
			}
?>