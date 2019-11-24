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
			
		function getYear()
		{
			global $mysqli;
			
			$query = $mysqli->stmt_init();
			
			if($query = $mysqli->prepare("SELECT value FROM settings WHERE name = ?"))
			{
				$parameter = "projects";
				
				$query->bind_param("s", $parameter);
				$query->execute();
				$query->store_result();
				$query->bind_result($pYear);
				$query->fetch();
				
				$query->close();
			}
			else
				$pYear = 0;
				
			return $pYear;
		}
			
		function getProjects()
		{
			global $mysqli;
			
			$query = $mysqli->stmt_init();
			
			if($query = $mysqli->prepare("SELECT id, name, number, year FROM projects"))
			{
				$query->execute();
				$query->bind_result($pID, $pName, $pNumber, $pYear);
				
				while($query->fetch())
					echo "<tr><td>".$pID."</td><td>".$pName."</td><td>".$pNumber."</td><td>".$pYear."</td><td class='width8p'><button class='btn btn-primary editProject'>Upravit</button></td></tr>";
					
				$query->close();
			}
			else
				echo "Nepodařilo se nám provést dotaz do databáze.";
		}
		
		function getSettings()
		{
			global $mysqli;
			
			
		}
?>
<script>
	$(document).ready(function()
	{
		$("#alert_bar").fadeOut(0);
		
		$("#dialog_ProjectSettings").dialog(
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
					var psYear = $("input[id=projectYear_ProjectSettings]").val();
					
					if(psYear == null)
					{
						alert("Rok projektu je prázdný!");
						return;
					}
					
					var serialData = {project: psYear, type: 3};
					
					$.post("/php/projects.php", serialData,
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
					}, 'json');
					
					$(this).dialog("close");
				},
				"Zpět": function()
				{
					$(this).dialog("close");
				}
			}
		});
		
		$("#dialog_deleteProject").dialog(
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
				"Smazat": function()
				{
					var pID = $("input[id=projectID_deleteProject]").val();
					
					if(pID == null)
					{
						alert("ID projektu ke smazání je prázdné!");
						return;
					}
					
					var serialData = {project: pID, type: 1};
					
					$.post("/php/projects.php", serialData,
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
						
						if(data['projectDelete_success'] == 0)
						{
							$('#projectsTable tbody tr').each(function()
							{
								var columnValue = $(this).find('td:eq(0)').html();
								
								if(columnValue == pID)
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
		
		$("#dialog_addProject").dialog(
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
				"Přidat": function()
				{				
					var pName = $("input[id=projectName_addProject]").val();
					var pNumber = $("input[id=projectNumber_addProject]").val();
					var pYear = $("input[id=projectYear_addProject]").val();
					
					if(pName == null)
					{
						alert("ID projektu ke smazání je prázdné!");
						return;
					}
					
					if(pNumber == null)
						pNumber = 0;
					
					if(!$.isNumeric(pNumber))
					{
						alert("Číslo projektu obsahuje nepovolené znaky!");
						return;
					}
					
					if(pYear == null)
					{
						alert("Rok projektu je prázdný!");
						return;
					}
					
					var serialData = {project: pName, projectL: $("input[id=projectLink_addProject]").val(), projectN: pNumber, projectY: pYear, type: 0};
					
					$.post("/php/projects.php", serialData,
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
						
						if(data['projectAdd_success'] == 0)
						{
							$("#projectsTable tbody").append("<tr><td>" + data["projectID"] + "</td><td>" + pName + "</td><td>" + pNumber + "</td><td>" + pYear + "</td><td class='width8p'><button class='btn btn-primary editProject'>Upravit</button></td></tr>");
							
							loadEditButtons();
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
		
		$("#dialog_editProject").dialog(
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
				"Upravit": function()
				{
					var pID = $("input[id=projectID_editProject]").val();
					var pName = $("input[id=projectName_editProject]").val();
					var pLink = $("input[id=projectLink_editProject]").val();
					var pNumber = $("input[id=projectNumber_editProject]").val();
					var pYear = $("input[id=projectYear_editProject]").val();
					
					if(pID == null)
					{
						alert("ID projektu k úpravě je prázdné!");
						return;
					}
					
					if(pName == null)
					{
						alert("Název projektu je prázdný!");
						return;
					}
					
					if(pNumber == null)
						pNumber = 0;
					
					if(!$.isNumeric(pNumber))
					{
						alert("Číslo projektu obsahuje nepovolené znaky!");
						return;
					}
					
					if(pYear == null)
					{
						alert("Rok projektu je prázdný!");
						return;
					}
					
					var serialData = {project: pID, projectName: pName, projectL: pLink, projectN: pNumber, projectY: pYear, type: 2};
					
					$.post("/php/projects.php", serialData,
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
						
						if(data['projectEdit_success'] == 0)
						{
							$('#projectsTable tbody tr').each(function()
							{
								var columnValue = $(this).find('td:eq(0)').html();
								
								if(columnValue == pID)
								{
									var $row = $(this).parents('tr');
									
									$(this).find('td:eq(1)').html(pName);
									$(this).find('td:eq(2)').html(pNumber);
									$(this).find('td:eq(3)').html(pYear);
									
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
		
		$(".ProjectSettings").click(function()
		{
			$("#dialog_ProjectSettings").dialog("open");
			
			return false;
		});
		
		$(".editProject").click(function()
		{
			editProject($(this));
		});
		
		$(".addProject").click(function()
		{
		  	$("#projectName_addProject").val("");
			$("#projectLink_addProject").val("");
			$("#projectNumber_addProject").val("");
			$("#projectYear_addProject").val("");
			
			$("#dialog_addProject").dialog("open");
			
			return false;
		});
		
		$(".deleteProject").click(function()
		{
			$("#projectID_deleteProject").val("");
			
			$("#dialog_deleteProject").dialog("open");
			
			return false;
		});
		
		$("#close_alert").click(function()
		{
			$("#alert_bar").fadeOut("slow");
		});
	});
	
	function editProject(parent)
	{
		var $row = $(parent).parents('tr');
		var $table = $(parent).closest('table');
		
		$("#projectID_editProject").val($row.find('td:eq(0)').html());
		$("#projectName_editProject").val($row.find('td:eq(1)').html());
		$("#projectNumber_editProject").val($row.find('td:eq(2)').html());
		$("#projectYear_editProject").val($row.find('td:eq(3)').html());
		
		$.post("/php/projectlink.php", {pID: $("#projectID_editProject").val()},
		function(data)
		{
			$("#projectLink_editProject").val(data['link']);
		}, 'json');
		
		$("#dialog_editProject").dialog("open");
		
		return false;
	}
	
	function loadEditButtons()
	{
		$("#projectsTable > tbody > tr").each(function()
		{
			$(this).delegate(".editProject", "click", function()
			{
				editProject($(this));
			});
		});
	}
</script>

<div class="page-header">
	<h3>Administrace</h3>
</div>

<div class="article">
	<div class="title"><h4>Projekty</h4></div>
	
	<table class="table table-bordered table-striped table-hover users-table" id="projectsTable">
		<tr>
			<th class="width2p">ID</th>
			<th>Název projektu</th>
			<th class="width12p">Číslo projektu</th>
			<th class="width12p">Rok projektu</th>
			<th class="borderLeftNone"></th>
		</tr>
		
		<?php getProjects(); ?>
	</table>
	
	<div class="controls text-right">
		<button class="btn btn-primary ProjectSettings">Nastavení</button>
		<button class="btn btn-primary addProject">Přidat</button>
		<button class="btn btn-primary deleteProject">Odebrat</button>
	</div>
</div>

<div id="dialog_ProjectSettings" class="dialog-form" title="Nastavení projektů">
	<form class="text-center">
		<fieldset>
		  <label for="projectYear_ProjectSettings">Zobrazující rok</label>
		  <input type="text" name="projectYear_ProjectSettings" id="projectYear_ProjectSettings" value="<?php echo getYear(); ?>">
		</fieldset>
	</form>
</div>

<div id="dialog_editProject" class="dialog-form" title="Upravení projektu">
	<form class="text-center">
		<fieldset>
		  <label for="projectName_editProject">Název projektu</label>
		  <input type="text" name="projectName_editProject" id="projectName_editProject">
		  <br>
		  <label for="projectLink_editProject">Odkaz na projekt</label>
		  <input type="text" name="projectLink_editProject" id="projectLink_editProject">
		  <br>
		  <label for="projectNumber_editProject">Číslo projektu</label>
		  <input type="text" name="projectNumber_editProject" id="projectNumber_editProject">
		  <br>
		  <label for="projectYear_editProject">Školní rok projektu</label>
		  <input type="text" name="projectYear_editProject" id="projectYear_editProject">
		  <input type="text" name="projectID_editProject" id="projectID_editProject" class="hide">
		</fieldset>
	</form>
</div>

<div id="dialog_addProject" class="dialog-form" title="Přidání projektu">
	<form class="text-center">
		<fieldset>
		  <label for="projectName_addProject">Název projektu</label>
		  <input type="text" name="projectName_addProject" id="projectName_addProject" placeholder="Portál Art">
		  <br>
		  <label for="projectLink_addProject">Odkaz na projekt</label>
		  <input type="text" name="projectLink_addProject" id="projectLink_addProject" placeholder="http://ssakhk.cz">
		  <br>
		  <label for="projectNumber_addProject">Číslo projektu</label>
		  <input type="text" name="projectNumber_addProject" id="projectNumber_addProject" placeholder="21">
		  <br>
		  <label for="projectYear_addProject">Školní rok projektu</label>
		  <input type="text" name="projectYear_addProject" id="projectYear_addProject" placeholder="2012/2013">
		</fieldset>
	</form>
</div>

<div id="dialog_deleteProject" class="dialog-form" title="Smazání projektu">
	<form class="text-center">
		<fieldset>
		  <label for="projectID_deleteProject">ID projektu</label>
		  <input type="text" name="projectID_deleteProject" id="projectID_deleteProject" placeholder="123...">
		</fieldset>
	</form>
</div>

<div class="alert" id="alert_bar">
	<a class="close" id="close_alert">×</a>
	<strong id="alert_info" class="alert_info"></strong>
	<div id="alert_text"></div>
</div>
<?php
	}
?>