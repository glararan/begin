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
			
		function getDevices()
		{
			global $mysqli;
			
			$query = $mysqli->stmt_init();
				
			if($query = $mysqli->prepare("SELECT id, name, nick, description, status, category FROM devices"))
			{
				$query->execute();
				$query->store_result();
				$query->bind_result($deviceID, $name, $nick, $description, $status, $categoryID);
			
				while($query->fetch())
				{
					$query2 = $mysqli->stmt_init();
					
					if($query2 = $mysqli->prepare("SELECT name FROM categories WHERE id = ?"))
					{
						$query2->bind_param("i", $categoryID);
						$query2->execute();
						$query2->store_result();
						$query2->bind_result($category);
					
						while($query2->fetch())
						{
							$_status = "";
							
							switch($status)
							{
								case 0:
									$_status = "K vypujčení";
									break;
									
								case 1:
									$_status = "Vypůjčené";
									break;
									
								case 2:
									$_status = "Nelze vypůjčit";
									break;
									
								case 3:
									$_status = "Poškozené";
									break;
									
								default:
									$_status = "Neznámý";
									break;
							}
						
							echo "<tr><td>".$deviceID."</td><td>".$name."</td><td>".$nick."</td><td>".$description."</td><td>".$_status."</td><td>".$category."</td><td class='width8p'><button class='btn btn-primary editDevice'>Upravit</button></td></tr>";
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
		
		$("#dialog_editDevice").dialog(
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
					var Dname = $("input[id=deviceName_editDevice]").val();
					var Dnick = $("input[id=deviceNick_editDevice]").val();
					var Ddesc = $("textarea[id=deviceDescription_editDevice]").val();
					var Dstat = $("select[id=deviceStatus_editDevice]").val();
					var Dcatg = $("select[id=deviceCategory_editDevice]").val();
					var Did = $("input[id=deviceID_editDevice]").val();
					
					if($.isEmptyObject(Dname))
					{
						alert("Název zařízení je prázdný!");
						return;						
					}
					
					if($.isEmptyObject(Dnick))
						Dnick = "Žádný";
					
					if($.isEmptyObject(Ddesc))
						Ddesc = "Žádný";
						
					if($.isEmptyObject(Dcatg))
					{
						alert("ID kategorie pro zařízení je prazdné!");
						return;						
					}
					
					if($.isEmptyObject(Did))
					{
						alert("ID pro zařízení je prazdné!");
						return;						
					}
					
					if(Dstat > 3 && Dstat < 0)
					{
						alert("Kategorie pro zařízení je prázdná!");
						return;						
					}
					
					var serialData = {device: Dname, nick: Dnick, descr: Ddesc, status: Dstat, category: Dcatg, deviceID: Did, type: 2};
					
					$.post("/php/devices2.php", serialData,
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
	
						if(data["deviceTableEdit_status"] == 0)	
						{
							var deviceID = $("input[id=deviceID_editDevice]").val();
							
							$('#editTable tbody tr').each(function()
							{
								var columnValue = $(this).find('td:eq(0)').html();
								
								if(columnValue == deviceID)
								{								
									var $row = $(this).parents('tr');
									
									$(this).find('td:eq(1)').html(Dname);
									$(this).find('td:eq(2)').html(Dnick);
									$(this).find('td:eq(3)').html(Ddesc);
									$(this).find('td:eq(4)').html($("#deviceStatus_editDevice option[value='" + Dstat + "']").text());
									$(this).find('td:eq(5)').html($("#deviceCategory_editDevice option[value='" + Dcatg + "']").text());
									
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
		
		$(".editDevice").click(function()
		{
			editDevice($(this));
		});
		
		$("#dialog_addDevice").dialog(
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
					var Dname = $("input[id=deviceName_addDevice]").val();
					var Dnick = $("input[id=deviceNick_addDevice]").val();
					var Ddesc = $("textarea[id=deviceDescription_addDevice]").val();
					var Dstat = $("select[id=deviceStatus_addDevice]").val();
					var Dcatg = $("select[id=deviceCategory_addDevice]").val();
					
					if($.isEmptyObject(Dname))
					{
						alert("Název zařízení je prázdný!");
						return;						
					}
					
					if($.isEmptyObject(Dnick))
						Dnick = "Žádný";
					
					if($.isEmptyObject(Ddesc))
						Ddesc = "Žádný";
						
					if($.isEmptyObject(Dcatg))
					{
						alert("ID kategorie pro zařízení je prazdné!");
						return;						
					}
					
					if(Dstat > 3 && Dstat < 0)
					{
						alert("Kategorie pro zařízení je prázdná!");
						return;						
					}
					
					var serialData = {device: Dname, nick: Dnick, descr: Ddesc, status: Dstat, category: Dcatg, type: 0};
					
					$.post("/php/devices2.php", serialData,
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
						
						if(data["deviceTableAdd_status"] == 0)
						{
							$("#editTable tbody").append("<tr class='width8p'><td>" + data["deviceTableID"] + "</td><td>" + Dname + "</td><td>"+ Dnick + "</td><td>" + Ddesc + "</td><td>" + $("#deviceStatus_addDevice option[value='" + Dstat + "']").text() + "</td><td>" + $("#deviceCategory_addDevice option[value='" + Dcatg + "']").text() + "</td><td class='width8p'><button class='btn btn-primary editDevice'>Upravit</button></td></tr>");
							
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
		
		$(".addDevice").click(function()
		{
			$('#deviceCategory_addDevice').html("");
			
			$.post("/php/categoriesget.php", {all: true},
			function(data)
			{
				var f = true;
				
				$.each(data, function(i, object)
				{
					var f2 = true;
					
					var val1;
					var val2;
					
					$.each(object, function(property, value)
					{
						if(f2)
						{
							val1 = value;
							f2 = false;
						}
						else
							val2 = value;
					});
					
					if(f)
					{
						$('#deviceCategory_addDevice').append("<option selected value='" + val1 + "'>" + val2 + "</option>");
						f = false;
					}
					else
						$('#deviceCategory_addDevice').append("<option value='" + val1 + "'>" + val2 + "</option>");
				});
			}, 'json');
			
			$('#deviceName_addDevice').val("");
			$('#deviceNick_addDevice').val("");
			$('#deviceDescription_addDevice').val("");
			$('#deviceStatus_addDevice').val("0");
			$('#deviceCategory_addDevice').val("0");
	
			$("#dialog_addDevice").dialog("open");
			
			return false;
		});
		
		$("#dialog_deleteDevice").dialog(
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
				"Odebrat": function()
				{
					var deviceID = $("input[id=deviceID_deleteDevice]").val();
					
					if($.isEmptyObject(deviceID))
					{
						alert("ID zařízení je prázdné!");
						return;
					}
					
					var serialData = {device: deviceID, type: 1};
					
					$.post("/php/devices2.php", serialData,
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
						
						if(data["deviceTableDelete_status"] == 0)	
						{
							$('#editTable tbody tr').each(function()
							{
								var columnValue = $(this).find('td:eq(0)').html();
								
								if(columnValue == deviceID)
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
		
		$(".deleteDevice").click(function()
		{
			$('#deviceID_deleteDevice').val("");
	
			$("#dialog_deleteDevice").dialog("open");
			
			return false;
		});
		
		$("#close_alert").click(function()
		{
			$("#alert_bar").fadeOut("slow");
		});
	});
	
	function editDevice(parent)
	{
		var $row = $(parent).parents('tr');

		$('#deviceCategory_editDevice').html("");
		$('#deviceID_editDevice').val($row.find('td:eq(0)').html());
		
		$.post("/php/categoriesget.php", {all: true},
		function(data)
		{
			var f = true;
			
			$.each(data, function(i, object)
			{
				var f2 = true;
				
				var val1;
				var val2;
				
				$.each(object, function(property, value)
				{
					if(f2)
					{
						val1 = value;
						f2 = false;
					}
					else
						val2 = value;
				});
				
				if(f)
				{
					$('#deviceCategory_editDevice').append("<option selected value='" + val1 + "'>" + val2 + "</option>");
					f = false;
				}
				else
					$('#deviceCategory_editDevice').append("<option value='" + val1 + "'>" + val2 + "</option>");
			});
		}, 'json');
		
		$.post("/php/categoriesget.php", {catid: $('#deviceID_editDevice').val()},
		function(data)
		{
			$('#deviceCategory_editDevice').val(data["category"]);
		}, 'json');
		
		$('#deviceName_editDevice').val($row.find('td:eq(1)').html());
		$('#deviceNick_editDevice').val($row.find('td:eq(2)').html());
		$('#deviceDescription_editDevice').val($row.find('td:eq(3)').html());
		
		switch($row.find('td:eq(4)').html())
		{
			case "K vypůjčení":
				$('#deviceStatus_editDevice').val("0");
				break;
				
			case "Vypůjčené":
				$('#deviceStatus_editDevice').val("1");
				break;
				
			case "Nelze půjčit":
				$('#deviceStatus_editDevice').val("2");
				break;
				
			case "Poškozené":
				$('#deviceStatus_editDevice').val("3");
				break;
		}

		$("#dialog_editDevice").dialog("open");
		
		return false;
	}
	
	function loadEditButtons()
	{
		$("#editTable > tbody > tr").each(function()
		{
			$(this).delegate(".editDevice", "click", function()
			{
				editDevice($(this));
			});
		});
	}
</script>

<div class="page-header">
	<h3>Administrace</h3>
</div>

<div class="article">
	<div class="title"><h4>Upravení dostupných zařízení</h4></div>
	
	<!-- edit -->
	<table class="table table-bordered table-striped table-hover users-table" id="editTable">
		<tr>
			<th class="width2p">ID</th>
			<th>Název zařízení</th>
			<th>Přezdívka</th>
			<th>Popis</th>
			<th class="width9p">Status</th>
			<th>Kategorie</th>
			<th class="borderLeftNone"></th>
		</tr>
		
		<?php getDevices(); ?>
	</table>
	
	<!-- add, remove -->
	<div class="controls text-right">
		<button class="btn btn-primary addDevice">Přidat</button>
		<button class="btn btn-primary deleteDevice">Odebrat</button>
	</div>
</div>

<div id="dialog_editDevice" class="dialog-form" title="Upravit zařízení">
	<form class="text-center">
		<fieldset>
		  <label for="deviceName_editDevice">Název zařízení</label>
		  <input type="text" name="deviceName_editDevice" id="deviceName_editDevice" placeholder="Wacom Cintiq...">
		  <label for="deviceNick_editDevice">Nick zařízení</label>
		  <input type="text" name="deviceNick_editDevice" id="deviceNick_editDevice" placeholder="Cintiq...">
		  <label for="deviceDescription_editDevice">Popis zařízení</label>
		  <textarea name="deviceDescription_editDevice" id="deviceDescription_editDevice" placeholder="Wacom Cintiq 24HD, 1920x1080..."></textarea>
		  <label for="deviceStatus_editDevice">Status zařízení</label>
		  <select name="deviceStatus_editDevice" id="deviceStatus_editDevice">
		  	<option selected value="0">K vypůjčení</option>
			<option value="1">Vypůjčené</option>
			<option value="2">Nelze půjčit</option>
			<option value="3">Poškozené</option>
		  </select>
		  <label for="deviceCategory_editDevice">Kategorie zařízení</label>
		  <select name="deviceCategory_editDevice" id="deviceCategory_editDevice">
		  </select>
		  <input type="text" name="deviceID_editDevice" id="deviceID_editDevice" class="hide">
		</fieldset>
	</form>
</div>

<div id="dialog_addDevice" class="dialog-form" title="Přidání zařízení">
	<form class="text-center">
		<fieldset>
		  <label for="deviceName_addDevice">Název zařízení</label>
		  <input type="text" name="deviceName_addDevice" id="deviceName_addDevice" placeholder="Wacom Cintiq...">
		  <label for="deviceNick_addDevice">Nick zařízení</label>
		  <input type="text" name="deviceNick_addDevice" id="deviceNick_addDevice" placeholder="Cintiq...">
		  <label for="deviceDescription_addDevice">Popis zařízení</label>
		  <textarea name="deviceDescription_addDevice" id="deviceDescription_addDevice" placeholder="Wacom Cintiq 24HD, 1920x1080..."></textarea>
		  <label for="deviceStatus_addDevice">Status zařízení</label>
		  <select name="deviceStatus_addDevice" id="deviceStatus_addDevice">
		  	<option selected value="0">K vypůjčení</option>
			<option value="1">Vypůjčené</option>
			<option value="2">Nelze půjčit</option>
			<option value="3">Poškozené</option>
		  </select>
		  <label for="deviceCategory_addDevice">Kategorie zařízení</label>
		  <select name="deviceCategory_addDevice" id="deviceCategory_addDevice">
		  </select>
		</fieldset>
	</form>
</div>

<div id="dialog_deleteDevice" class="dialog-form" title="Odebrání zařízení">
	<form class="text-center">
		<fieldset>
		  <label for="deviceID_deleteDevice">ID zařízení</label>
		  <input type="text" name="deviceID_deleteDevice" id="deviceID_deleteDevice" placeholder="123..">
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