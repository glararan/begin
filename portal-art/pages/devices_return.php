<?php
	session_start();

	if(!isset($_SESSION['rank']) || $_SESSION['rank'] != "admin")
	{
?>
<div class="page-header">
	<h3>Přístup zamítnut!</h3>
</div>

<div class="article">
	<div class="title"><h4>Error</h4></div>
	
	<p>Nemáte povolený přístup k této stránce, možná ani neexistuje.</p>
</div>
<?php
	}
	else
	{
		// Připojení k databázi
		$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
		$mysqli->set_charset('utf8');
		
		if (mysqli_connect_errno()) 
			die(mysqli_connect_errno());
		
		function getDevicesToReturn()
		{
			global $mysqli;
			
			$query = $mysqli->stmt_init();
			
			if($query = $mysqli->prepare("SELECT id, admin, user, device FROM transfers WHERE status = 1"))
			{
				$query->execute();
				$query->store_result();
				$query->bind_result($transferID, $adminID, $userID, $deviceID);
			
				while($query->fetch())
				{
					$query2 = $mysqli->stmt_init();
					
					if($query2 = $mysqli->prepare("SELECT name FROM devices WHERE id = ?"))
					{
						$query2->bind_param("i", $deviceID);
						$query2->execute();
						$query2->store_result();
						$query2->bind_result($deviceName);
					
						while($query2->fetch())
						{
							$query3 = $mysqli->stmt_init();
							
							if($query3 = $mysqli->prepare("SELECT email FROM admins WHERE id = ?"))
							{
								$query3->bind_param("i", $adminID);
								$query3->execute();
								$query3->store_result();
								$query3->bind_result($adminEmail);
							
								while($query3->fetch())
								{
									$query4 = $mysqli->stmt_init();
									
									if($query4 = $mysqli->prepare("SELECT email FROM users WHERE id = ?"))
									{
										$query4->bind_param("i", $userID);
										$query4->execute();
										$query4->store_result();
										$query4->bind_result($userEmail);
									
										while($query4->fetch())
											echo "<tr><td>".$transferID."</td><td>".$deviceName."</td><td><select id='returnSelect'><option selected value='0'>K vypujčení</option><option value='2'>Nelze vypůjčit</option><option value='3'>Poškozené</option></select></td><td>".$userEmail."</td><td>".$adminEmail."</td><td><textarea id='adminReturnComment'></textarea></td><td class='width8p'><button class='btn btn-primary accept_ReturnTransfer'>Vráceno</button></td></tr>";
										
										$query4->close();
									}
								}
								
								$query3->close();
							}
						}
						
						$query2->close();
					}
				}
				
				$query->close();
			}
		}
?>
<script>
	$(document).ready(function()
	{
		$("#alert_bar").fadeOut(0);
		
		$("#dialog_returnDevice").dialog(
		{
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			resizable: false,
			position: 'center',
			closeOnEsc: true,
			draggable: false,
			closeText: "X",
			buttons:
			{
				"Označit": function()
				{
					var transferID = $("b[id=transferID_returnTransfer]").html();
					var endComment = "";
					var returnSelect = 0;
					
					if($.isEmptyObject(transferID))
					{
						alert("ID položky je prázdné.");
						return;
					}
					
					$('#returnedTable tbody tr').each(function()
					{
						var columnValue = $(this).find('td:eq(0)').html();
						
						if(columnValue == transferID)
						{
							endComment = $(this).find('textarea[id=adminReturnComment]').val();
							returnSelect = $(this).find('select[id=returnSelect]').val();
							
							return false;
						}
					});
					
					if(returnSelect != 0 && returnSelect != 2 && returnSelect != 3)
					{
						alert("Nesprávné ID pro typ stavu.");
						return;
					}
					
					if(endComment == "" || $.isEmptyObject(endComment))
						endComment = "Žádný";
					
					var serialData = {transferID: $("b[id=transferID_returnTransfer]").html(), endText: endComment, status: returnSelect, type: 2};
					
					$.post("/php/usertransfer.php", serialData,
					function(data)
					{
						if($("#alert_bar").hasClass("alert-error"))
							$("#alert_bar").removeClass("alert-error");
							
						if($("#alert_bar").hasClass("alert-sucess"))
							$("#alert_bar").removeClass("alert-sucess");
										
						$("#alert_bar").addClass(data["bar-color"]);
						
						$("#alert_info").empty();
						$("#alert_info").append(data["alert-info"]);
						
						$("#alert_text").empty();
						$("#alert_text").append(data["alert-text"]);
						
						$("#alert_bar").fadeIn("slow");
	
						if(data["transfer_status"] == 0)	
						{					
							$('#returnedTable tbody tr').each(function()
							{
								var columnValue = $(this).find('td:eq(0)').html();
								
								if(columnValue == transferID)
								{
									$(this).closest('tr').remove();
									
									return false;
								}
							});
						}
					}, 'json');
					
					$(this).dialog("close");
				},
				"Zpět": function()
				{
					$(this).dialog("close");
				}
			}
		});
		
		$(".accept_ReturnTransfer").click(function()
		{
			$('#transferText_returnTransfer').empty();
			$('#transferText_returnTransfer').append("Opravdu chcete toto půjčení s ID: <b id='transferID_returnTransfer'></b> označit za vrácené?");
			
			var $row = $(this).parents('tr');
			
			$('#transferID_returnTransfer').html($row.find('td:eq(0)').html());
	
			$("#dialog_returnDevice").dialog("open");
			
			return false;
		});
		
		$("#close_alert").click(function()
		{
			$("#alert_bar").fadeOut("slow");
		});
	});
</script>

<div class="page-header">
	<h3>Administrace</h3>
</div>

<div class="article">
	<div class="title"><h4>Zápis vráceného zařízení</h4></div>
	
	<table class="table table-bordered table-striped table-hover users-table" id="returnedTable">
		<tr>
			<th class="width2p">ID</th>
			<th>Název zařízení</th>
			<th>Stav</th>
			<th>Použil</th>
			<th>Půjčil</th>
			<th>Váš komentář</th>
			<th class="borderLeftNone"></th>
		</tr>
		
		<?php getDevicesToReturn(); ?>
	</table>
</div>

<div id="dialog_returnDevice" class="dialog-form" title="Vrácení zařízení">
	<p id="transferText_returnTransfer"></p>
</div>

<div class="alert" id="alert_bar">
	<a class="close" id="close_alert">×</a>
	<strong id="alert_info" class="alert_info"></strong>
	<div id="alert_text"></div>
</div>
<?php
	}
?>