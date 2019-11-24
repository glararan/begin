<?
	session_start();

	// Připojení k MySQL
    @mysql_connect("wm27.wedos.net", "a8403_palermo", "qwedsayxc789") or die("Nelze se připojit do databáze.");
    @mysql_select_db("d8403_palermo") or die("Databáze nebyla nalezena.");
    // Konec
   
    if(isset($_POST['show']))
	{
    	$query_rooms = mysql_query("SELECT * FROM room WHERE private = 0");
                
        while($row = mysql_fetch_array($query_rooms))
        {
        	$game_status = $row['status'];
                    
            if($game_status == 0)
            	$game_status = '<span class="orange">Čeká se</span>';
            else
            	$game_status = '<span class="blue">Hraje se</span>';
                        
            $query_rooms_players = mysql_query("SELECT COUNT(*) FROM room_players WHERE id = ".$row['id']);
            $players             = mysql_result($query_rooms_players, 0);
                    
            echo '<tr><td width=5%>'.$row['id'].'</td><td><a href="/room/'.$row['id'].'/" class="blue">'.$row['name'].'</a></td><td>'.$game_status.'</td><td>'.$players.'/10</td><td><a href="/room/'.$row['id'].'/"><input type="button" value="Připojit" class="button"></a></td></tr>';
		}
    }
?>