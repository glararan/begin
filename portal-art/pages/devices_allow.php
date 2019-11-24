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
		
		function getDevicesToTransfer()
		{
			global $mysqli;
			
			$query = $mysqli->stmt_init();
			
			if($query = $mysqli->prepare("SELECT id, fromDate, toDate, user, userText, device FROM transfers WHERE status = 0"))
			{
				$query->execute();
				$query->store_result();
				$query->bind_result($transferID, $fromDate, $toDate, $userID, $userText, $deviceID);
				
				while($query->fetch())
				{
					if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", time()))
						$fromDate = "Dnes";
					else if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) - 1, date("Y", time()))))
						$fromDate = "Včéra";
					else if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) - 2, date("Y", time()))))
						$fromDate = "Předevčírem";
					else if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) + 1, date("Y", time()))))
						$fromDate = "Zítra";
					else if(date("d. m. Y", strtotime($fromDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) + 2, date("Y", time()))))
						$fromDate = "Pozítří";
					else
						$fromDate = date("d. m. Y", strtotime($fromDate));
					
					if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", time()))
						$toDate = "Dnes";
					else if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) - 1, date("Y", time()))))
						$toDate = "Včéra";
					else if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) - 2, date("Y", time()))))
						$toDate = "Předevčírem";
					else if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) + 1, date("Y", time()))))
						$toDate = "Zítra";
					else if(date("d. m. Y", strtotime($toDate)) == date("d. m. Y", mktime(0, 0, 0, date("m", time()), date("d", time()) + 2, date("Y", time()))))
						$toDate = "Pozítří";
					else
						$toDate = date("d. m. Y", strtotime($toDate));
					
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
							
							if($query3 = $mysqli->prepare("SELECT email FROM users WHERE id = ?"))
							{
								$query3->bind_param("i", $userID);
								$query3->execute();
								$query3->store_result();
								$query3->bind_result($userName);
							
								while($query3->fetch())
								{
									if($userText == "")
										$userText = "Žádný";
							
									echo "<tr><td>".$transferID."</td><td>".$deviceName."</td><td>".$userName."</td><td>".$userText."</td><td>".$fromDate."</td><td>".$toDate."</td><td class='width20p'><textarea id='adminComment'></textarea></td><td class='width8p'><button class='btn btn-primary acceptTransfer'>Schválit</button><button class='btn btn-primary rejectTransfer'>Zamítnout</button></td></tr>";
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
		
		$("#dialog_acceptTransfer").dialog(
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
				"Schválit": function()
				{
					var transferID = $("b[id=transferID_acceptTransfer]").html();
					var adminComment = "";
					
					if($.isEmptyObject(transferID))
					{
						alert("ID položky je prázdné.");
						return;
					}
					
					$('#lendTable tbody tr').each(function()
					{
						var columnValue = $(this).find('td:eq(0)').html();
						
						if(columnValue == transferID)
						{
							adminComment = $(this).find('textarea[id=adminComment]').val();
							
							return false;
						}
					});
					
					if(adminComment == "" || $.isEmptyObject(adminComment))
						adminComment = "Žádný";
					
					var serialData = {transferID: $("b[id=transferID_acceptTransfer]").html(), adminText: adminComment, type: 0};
					
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
							$('#lendTable tbody tr').each(function()
							{
								var columnValue = $(this).find('td:eq(0)').html();
								
								if(columnValue == transferID)
								{												
									$('#returnedTable').append("<tr><td>" + data['id'] + "</td><td>" + data['deviceName'] + "</td><td><select id='returnSelect'><option selected value='0'>K vypujčení</option><option value='2'>Nelze vypůjčit</option><option value='3'>Poškozené</option></select></td><td>" + data['userEmail'] + "</td><td>" + data['adminEmail'] + "</td><td><textarea id='adminReturnComment'></textarea></td><td class='width8p'><button class='btn btn-primary accept_ReturnTransfer'>Vráceno</button></td></tr>");
									
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
		
		$("#dialog_rejectTransfer").dialog(
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
				"Zamítnout": function()
				{
					var transferID = $("b[id=transferID_rejectTransfer]").html();
					
					if(transferID.length == 0)
					{
						alert("ID položky je prázdné.");
						return;
					}
					
					var adminComment = "";
					
					$('#lendTable tbody tr').each(function()
					{
						var columnValue = $(this).find('td:eq(0)').html();
						
						if(columnValue == transferID)
						{
							adminComment = $(this).find('textarea[id=adminComment]').val();
							
							return false;
						}
					});
					
					if(adminComment == "" || $.isEmptyObject(adminComment))
						adminComment = "Žádný";
					
					var serialData = {transferID: transferID, adminText: adminComment, type: 1};
					
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
							$('#lendTable tbody tr').each(function()
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
		
		$(".acceptTransfer").click(function()
		{
			$('#transferText_acceptTransfer').empty();
			$('#transferText_acceptTransfer').append("Opravdu chcete schválit půjčení s ID: <b id='transferID_acceptTransfer'></b>");
			
			var $row = $(this).parents('tr');
			
			$('#transferID_acceptTransfer').html($row.find('td:eq(0)').html());
	
			$("#dialog_acceptTransfer").dialog("open");
			
			return false;
		});
		
		$(".rejectTransfer").click(function()
		{
			$('#transferText_rejectTransfer').empty();
			$('#transferText_rejectTransfer').append("Opravdu chcete zamítnout půjčení s ID: <b id='transferID_rejectTransfer'></b>");
			
			var $row = $(this).parents('tr');
			
			$('#transferID_rejectTransfer').html($row.find('td:eq(0)').html());
	
			$("#dialog_rejectTransfer").dialog("open");
			
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
	<div class="title"><h4>Schválit pujčení</h4></div>
	
	<table class="table table-bordered table-striped table-hover users-table" id="lendTable">
		<tr>
			<th class="width2p">ID</th>
			<th>Název zařízení</th>
			<th>Email studenta</th>
			<th>Komentář studenta</th>
			<th class="width9p">Od</th>
			<th class="width9p">Do</th>
			<th class="width20p">Váš komentář</th>
			<th class="borderLeftNone"></th>
		</tr>
		
		<?php getDevicesToTransfer(); ?>
	</table>
</div>

<div id="dialog_acceptTransfer" class="dialog-form" title="Schválit půjčení">
	<p id="transferText_acceptTransfer"></p>
</div>

<div id="dialog_rejectTransfer" class="dialog-form" title="Zamítnout půjčení">
	<p id="transferText_rejectTransfer"></p>
</div>

<div class="alert" id="alert_bar">
	<a class="close" id="close_alert">×</a>
	<strong id="alert_info" class="alert_info"></strong>
	<div id="alert_text"></div>
</div>
<?php
	}
?>