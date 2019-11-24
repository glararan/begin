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
			
		function getAccounts($type)
		{
			global $mysqli;
			
			$atype = -1;
			
			if(isset($type))
			{
				if($type == "free")
					$atype = 0;
				else if($type == "warn")
					$atype = 1;
				else if($type == "bann")
					$atype = 2;
				else
				{
					echo $lang['dontHaveAccess'];
					return;
				}
			}
			else
			{
				echo $lang['dontHaveAccess'];
				return;
			}
				
			$query = $mysqli->stmt_init();
				
			if($query = $mysqli->prepare("SELECT id, email FROM users WHERE status = ?"))
			{
				$query->bind_param("i", $atype);
				$query->execute();
				$query->bind_result($userID, $email);
				
				echo "<table class='table table-bordered table-striped table-hover users-table' id='".$atype."'><tr><th class='width2p'>ID</th><th class='widthFull'>Email</th><th class='borderLeftNone'></th></tr>";
				
				while($query->fetch())
					echo "<tr><td>".$userID."</td><td>".$email."</td><td class='width2p'><button class='btn btn-primary change_status'>Změnit</button></td></tr>";
	
				echo "</table>";
				
				$query->close();
			}
			else
			{
				echo $lang['error_cantDoStatement'];
				return;
			}
		}
?>

<script>
	$(document).ready(function()
	{
		$("#alert_bar").fadeOut(0);
		
		$("#dialog-form").dialog(
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
				"Změnit": function()
				{
					var statusID = $("#status").val();
					
					if(statusID != 0 && statusID != 1 && statusID != 2)
					{
						alert("Chyba! Status neodpovídá!");
						return;
					}
					
					var serialData = {email: $("input[id=email]").val(), status: statusID};
					
					$.post("/php/users.php", serialData,
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
						
						var mail = $("input[id=email]").val();
						
						var findMail = "";
						
						$('table tbody tr').each(function()
						{
							var columnValue = $(this).find('td:eq(1)').html();
							
							if(columnValue == mail)
							{
								findMail = $(this).closest('tr').html();
								
								$(this).closest('tr').remove();
								
								return false;
							}
						});
						
						var movingTable = $("table[id=" + $("#status").val() + "]").attr("id");
						$("#" + movingTable + " tbody").append("<tr>" + findMail + "</tr>");
						
						loadEditButtons();
					}, 'json');
					
					$(this).dialog("close");
				},
				"Zpět": function()
				{
					$(this).dialog("close");
				}
			}
		});
	 
		$(".change_status").click(function()
		{
			editStatus($(this));
		});
		
		$("#close_alert").click(function()
		{
			$("#alert_bar").fadeOut("slow");
		});
	});
	
	function editStatus(parent)
	{
		var $row = $(parent).parents('tr');
		var $table = $(parent).closest('table');
		
		$('#email').val($row.find('td:eq(1)').html());
		
		var option = $table.attr('id');
		
		switch(option)
		{
			case "0":
			default:
				$("#status").val("0");
				break;
			
			case "1":
				$("#status").val("1");
				break;
			
			case "2":
				$("#status").val("2");
				break;
		}
		
		if(!$("#alert_bar").hasClass("hide"))
			$("#alert_bar").addClass("hide");

		$("#dialog-form").dialog("open");
		
		return false;
	}
	
	function loadEditButtons()
	{
		$("table > tbody > tr").each(function()
		{
			$(this).delegate(".change_status", "click", function()
			{
				editStatus($(this));
			});
		});
	}
</script>

<div class="page-header">
	<h3>Uživatelé</h3>
</div>

<div class="article">
	<div class="title"><h4>Bez trestní</h4></div>
	
	<div id="free_users">
		<?php getAccounts("free"); ?>
	</div>
</div>

<div class="article">
	<div class="title"><h4>S varováním</h4></div>
	
	<div id="warned_users">
		<?php getAccounts("warn"); ?>
	</div>
</div>

<div class="article">
	<div class="title"><h4>Zakázaní</h4></div>
	
	<div id="banned_users">
		<?php getAccounts("bann"); ?>
	</div>
</div>

<div class="alert" id="alert_bar">
	<a class="close" id="close_alert">×</a>
	<strong id="alert_info" class="alert_info"></strong>
	<div id="alert_text"></div>
</div>

<div id="dialog-form" class="dialog-form" title="Změna uživatelského statusu">
	<form class="text-center">
		<fieldset>
			<label for="email">Email</label>
			<input type="text" name="email" id="email" readonly>
			
			<label for="status">Status</label>
			<select id="status" name="status">
				<option id='option_free' value="0">Bez trestu</option>
				<option id='option_warn' value="1">Varování</option>
				<option id='option_bann' value="2">Zakázaný</option>
			</select>
		</fieldset>
	</form>
</div>

<?php
	}
?>