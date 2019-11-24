<h3><a href="/">Připojit</a></h3>
<?php
	connectToMySQL();
?>

<div id="RoomArea">
</div>

<div class="join_options">
    <h4>Připojení k místnosti ručně</h4>
    <table class="join_tables" width=50%>
        <tbody>
            <tr>
                <td><b>Zadejte ID místnosti</b></td>
                <td><b>Zadejte název místnosti</b></td>
            </tr>
            <tr>
                <form action="" method="post">
                    <td width="40%"><input type="text" name="join_room_id" class="join_room_box" placeholder="ID místnosti"></td>
                    <td width="40%"><input type="text" name="join_room_name" class="join_room_box" placeholder="Název místnosti"></td>
                    <td width="20%"><input type="submit" name="join_room" class="join_room_button" tabindex="1" value="Připojit"></td>
                </form>
            </tr>
        </tbody>
    </table>
    
    <h4>Vytvoření místnosti</h4>
    <table class="join_tables" width=50%>
        <tbody>
            <tr>
                <td><b>Zadejte název místnosti</b></td>
                <td><b>Zadejte viditelnost místnosti</b></td>
            </tr>
            <tr>
                <form action="" method="post">
                    <td width="40%"><input type="text" name="create_room_name" class="join_room_box" placeholder="Název místnosti"></td>
                    <td width="40%"><select name="create_room_visible" class="join_room_option"><option value="visible">Veřejná</option><option value="invisible">Privátní</option></select></td>
                    <td width="20%"><input type="submit" name="create_room" class="join_room_button" tabindex="0" value="Vytvořit"></td>
                </form>
            </tr>
        </tbody>
    </table>
</div>

<?php
	if(isset($_POST['join_room']))
	{
		$room_id   = mysql_real_escape_string(addslashes($_POST['join_room_id']));
		$room_name = mysql_real_escape_string(addslashes($_POST['join_room_name']));
		
		$continue_join = false;
		$using_id = false;
			
		if(!empty($room_id))
		{
			if(is_numeric($room_id))
			{
				$continue_join = true;
				$using_id = true;
			}
			else
			{
				$join_error2 = true;
				$join_error = '<font color="red">ID neni numerické!</font>';
			}
		}
			
		if(!empty($room_name))
		{
			$continue_join = true;
			$using_id = false;
		}
			
		if($continue_join)
		{
			if($using_id)
				$query_room = mysql_query("SELECT COUNT(*) FROM room WHERE id = ".$room_id);
			else
				$query_room = mysql_query("SELECT id FROM room WHERE name = '".$room_name."'");
				
			$result = mysql_result($query_room, 0);
			
			if($using_id)
			{
				if($result == 0)
					$join_error = "<font color='red'>Místnost nenalezena!</font>";
				else if($result == 1)
					echo '<meta http-equiv="refresh" content="0;URL=\'/room/'.$room_id.'/\'">';
			}
			else
				echo '<meta http-equiv="refresh" content="0;URL=\'/room/'.$result.'/\'">';
		}
		else
		{
			if(!$join_error2)
				$join_error = "<font color='red'>Vyplňte alespoň jedno pole pro připojení!</font>";
		}
	}
	
	if(isset($_POST['create_room']))
	{
		$room_name    = mysql_real_escape_string(addslashes($_POST['create_room_name']));
		$room_visible = mysql_real_escape_string(addslashes($_POST['create_room_visible']));
		
		if(!empty($room_name))
		{
			if($room_visible == "visible")
				$room_visible = 0;
			else if($room_visible == "invisible")
				$room_visible = 1;
			else
				die("Error v příkazu!");
				
			$room_query = mysql_query("SELECT COUNT(*) FROM room WHERE name = '".$room_name."'");
			$result     = mysql_result($room_query, 0);
			
			if($result == 0)
			{
				mysql_query("INSERT INTO room (name, private) VALUES('".$room_name."', ".$room_visible.")");
				
				$room_query = mysql_query("SELECT id FROM room WHERE name = '".$room_name."'");
				$result     = mysql_result($room_query, 0);
				
				echo '<meta http-equiv="refresh" content="0;URL=\'/room/'.$result.'/\'">';
			}
			else
				$join_error = "<font color='red'>Název místnosti již existuje. Zvolte jiný.</font>";
		}
		else
			$join_error = "<font color='red'>Název místnosti je prázdný!</font>";
	}
	
	echo $join_error;
?>