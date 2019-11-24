<?php
	session_start();

	// Připojení k MySQL
    @mysql_connect("wm27.wedos.net", "a8403_palermo", "qwedsayxc789") or die("Nelze se připojit do databáze.");
    @mysql_select_db("d8403_palermo") or die("Databáze nebyla nalezena.");
    // Konec
	
	// delete player
	mysql_query("DELETE FROM room_players WHERE player = '".$_SESSION['username']."' and id = ".$_SESSION['room_id']);
	
	// delete room on 0 players
	$players_in_room  = mysql_query("SELECT COUNT(*) FROM room_players WHERE id = ".$_SESSION['room_id']);
	$result_player_IR = mysql_result($players_in_room, 0);
	
	$room_status   = mysql_query("SELECT status FROM room WHERE id = ".$_SESSION['room_id']);
	$result_status = mysql_result($room_status, 0);
	
	if($result_player_IR == 0)
	{
		// delete room
		mysql_query("DELETE FROM room WHERE id = ".$_SESSION['room_id']);
		
		// delete chat
		mysql_query("DELETE FROM chat WHERE room_id = ".$_SESSION['room_id']);
	}
	else if($result_player_IR >= 3 && $result_status == 1)
		mysql_query("UPDATE room SET autoend = 1 WHERE id = ".$_SESSION['room_id']);
	else if($result_player_IR >= 1 && $result_status == 0)
	{
		$query_choose_player  = mysql_query("SELECT player FROM room_players WHERE id = ".$_SESSION['room_id']);
		$result_choose_player = mysql_result($query_choose_player, 0);
		
		mysql_query("UPDATE room_players SET leader = 1 WHERE player = '".$result_choose_player."'");
	}
	
	unset($_SESSION['room_id']);
?>