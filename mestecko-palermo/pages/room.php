<h3><a href="/">Vítejte v místnosti <?php echo $param[2]; ?></a></h3>

<noscript>
	<font color='red'>JavaScript je vypnut, proto budete přesměrováni!</font>
    <meta http-equiv="refresh" content="3;URL='/room/'">
    
    <?php
		if(!isset($_SESSION['javascript']))
			$java_is_offline = true;
	?>
</noscript>

<?php
	if(!$java_is_offline)
	{
		session_start();
		
		connectToMySQL();
		
		////// room_players - přidat kolonku LEADER!
	
		$query_players_in_room = mysql_query("SELECT COUNT(*) FROM room_players WHERE id = ".$param[2]);
		$players_in_room       = mysql_result($query_players_in_room, 0);
		
		if($players_in_room <= 9)
		{
			// check if is in room
			$query_you_are_in_room = mysql_query("SELECT COUNT(*) FROM room_players WHERE player = '".$_SESSION['username']."'");
			$you_are_in_room       = mysql_result($query_you_are_in_room, 0);
			
			if($you_are_in_room == 0)
			{
				if($players_in_room == 0)
					$setLeader = true;
					
				if($setLeader)
					mysql_query("INSERT INTO room_players (id, player, leader) VALUES(".$param[2].", '".$_SESSION['username']."', 1)");
				else
					mysql_query("INSERT INTO room_players (id, player) VALUES(".$param[2].", '".$_SESSION['username']."')");
			}
			
			$_SESSION['room_id'] = $param[2];
			
			$query_players_in_room = mysql_query("SELECT COUNT(*) FROM room_players WHERE id = ".$param[2]);
			$players_in_room       = mysql_result($query_players_in_room, 0);
			
			if($players_in_room >= 4)
			{
				$query_player_is_leader  = mysql_query("SELECT leader FROM room_players WHERE player = '".$_SESSION['username']."'");
				$result_player_is_leader = mysql_result($query_player_is_leader, 0);
				
				if($result_player_is_leader == 1)
					$canStart = true;
			}
			?>
			
            <div id="playersTable">
            </div>
            
            <div id="gameContent">
            </div>
            
            <div id="gameText">
            </div>
            
            <div class='chat'>
                <div id='ChatArea' class="box">Loading...</div>
                <div class='AddMessage'>
                    <input type='text' class="chat_message" id='ChatNewMessage'>
                    <option>
                    	<select value="0" selected>Všichni</select>
                        <select value="1">Mafie</select>
                    </option>
                    <input type='button' class='send' value='Odeslat' tabindex="0" onclick='sendMsg()'>
                </div>
            </div>
        
<?php
			if($setLeader)
				echo '<div id="startButton"></div>';
		}
		else
			$room_error = "<font color='red'>O co se to pokoušíš?! Místnost je plná!</font>";
			
		echo $room_error;
		
		$room_time = time();
?>

    <form name="close" method="post" action="">
        <input type="hidden" name="game_window">
    </form>

<?php
	} // java is offline == false
?>