<?php
	session_start();

	if(isset($_SESSION['rank']) && $_SESSION['rank'] == "admin")
	{
		// Připojení k databázi
		$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
		$mysqli->set_charset('utf8');
		
		if (mysqli_connect_errno()) 
			die(mysqli_connect_errno());
	}
?>

<script>
$(document).ready(function()
{
	$("#alert_bar").fadeOut(0);
	
	$.post('/php/devices.php', {all: 'false'}, loadData, 'json');
	
	$('#free_label').click(function()
	{
		var checkbox = $('#free');
		
		checkbox.prop('checked', !checkbox.prop('checked'));
		
		if(checkbox.prop('checked'))
		{
			$.post('/php/devices.php', {all: 'false'}, loadData, 'json');
			
			if(!$(this).hasClass('btn-primary'))
				$(this).addClass('btn-primary');
		}
		else
		{
			$.post('/php/devices.php', {all: 'true'}, loadData, 'json');
			
			$(this).removeClass('btn-primary');
		}
	});
	
	$("#close_alert").click(function()
	{
		$("#alert_bar").fadeOut("slow");
	});
	
	<?php
		if(!isset($_SESSION['rank']))
		{
	?>
	$('#dialog-login').dialog(
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
			'Přihlásit se': function()
			{
				var mail = $("input[id=email]").val();
				var pass = $("input[id=password]").val();
				
				if($.isEmptyObject(mail))
				{
					alert("Emailová adresa je prázdná!");
					return;
				}
				else if(mail.indexOf("@") < 0)
				{
					alert("Emailová adresa neni ve správném tvaru!");
					return;
				}
				
				if($.isEmptyObject(pass))
				{
					alert("Heslo je prázdné!");
					return;
				}
				
				$.post('/php/login.php', {email: mail, password: pass},
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
			'Zpět': function()
			{
				$(this).dialog("close");
			}
		}
	});
	<?php
		}
		else if($_SESSION['rank'] == "user" && $_SESSION['user_status'] != 2)
		{
	?>
	$('#dialog_orderDevice').dialog(
	{
		autoOpen: false,
		height: 300,
		width: 500,
		modal: true,
		resizable: false,
		position: 'center',
		closeOnEsc: true,
		draggable: false,
		closeText: "X",
		buttons:
		{
			'Vypůjčit': function()
			{
				var dID = $('input[id=deviceID_orderDevice]').val();
				var fDate = $('input[id=deviceFrom_orderDevice]').val();
				var tDate = $('input[id=deviceTo_orderDevice]').val();
				var comm = $('input[id=deviceComment_orderDevice]').val();
				
				if($.isEmptyObject(dID))
				{
					alert("Problém s ID zařízení. Obnovte stránku a proveďte operaci znovu.");
					return;
				}
				
				if($.isEmptyObject(fDate))
				{
					alert("Datum vypůjčení je prázdné!");
					return;
				}
				
				if($.isEmptyObject(tDate))
				{
					alert("Datum vrácení je prázdné!");
					return;
				}
				
				if($.isEmptyObject(comm))
					comm = "Žádný";
				
				var serialData = {deviceID: dID, from: fDate, to: tDate, comment: comm};
				
				$.post("/php/deviceorder.php", serialData,
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
			'Zpět': function()
			{
				$(this).dialog("close");
			}
		}
	});
	<?php
		}
	?>
					
});

<?php
	if(isset($_SESSION['rank']) && $_SESSION['rank'] == "user" && $_SESSION['user_status'] != 2)
	{
?>
function loadInfo(dID)
{
	$.post("/php/deviceinfo.php", {deviceID: dID},
	function(data)
	{						
		if(data['deviceStatus'] == 1)
		{
			$('#deviceName_orderDevice').html("Název zařízení: " + data['name'] + " (" + data['nick'] + ")");
			$('#deviceDescription_orderDevice').html("Popis zařízení: " + data['description']);
			$('#deviceCategory_orderDevice').html("Katgorie: " + data['category']);
			$('#deviceStatus_orderDevice').html("Stav: " + data['status']);
			
			$('#deviceID_orderDevice').val(dID);
			$('#deviceFrom_orderDevice').val("");
			$('#deviceTo_orderDevice').val("");
			$('#deviceComment_orderDevice').val("");
		}
		else
			return false;
	}, 'json');
	
	$('#dialog_orderDevice').dialog("open");
}
<?php
	}
?>

function loadData(categories)
{
    $('#categories').empty();

    for(var i = 0; i < categories.length; i++)
    {
        var li = $('<li class="nav-header">' + categories[i]['name'] + '</li>');
        var pom = $('<table class="widthFull" id="devsTable"></table>');
        //li.append(pom);
        $('#categories').append(li);
		$('#categories').append(pom);

        for(var x = 0; x < categories[i]['devices'].length; x++)
        {
            var device = categories[i]['devices'][x];

            if (device['status'] == 0)
            {
				<?php
					if(!isset($_SESSION['rank']) || (isset($_SESSION['rank']) && $_SESSION['rank'] == "user" && $_SESSION['user_status'] != 2))
					{
				?>
                var polozka = $('<tr id="' + device['id'] + '"><td>' + device['name'] + ' (' + device['nick'] + ') </td><td class="width2p"><input type="submit" id="' + device['id'] + '" class="btn btn-primary orderDevice" value="Vypůjčit"/></td></tr>');
				<?php
					}
					else
					{
				?>
				var polozka = $('<tr id="' + device['id'] + '"><td>' + device['name'] + ' (' + device['nick'] + ') </td><td class="pull-right"></td></tr>');
				<?php
					}
				?>
				
				<?php
					if(!isset($_SESSION['rank']))
					{
				?>
				$(polozka).delegate(".orderDevice", "click", function()
				{
                    $('#dialog-login').dialog("open");
					
					return false;
                });
				<?php
					}
				?>

                polozka.appendTo(pom);
            }
            else if(device['status'] == 1)
                pom.append('<tr><td style="color:#F60;">' + device['name'] + ' (' + device['nick'] + ')</td><td></td></tr>');
			else if(device['status'] > 1)
				pom.append('<tr><td style="color:#ff0039;">' + device['name'] + ' (' + device['nick'] + ')</td><td></td></tr>');
        }
    }
	
	<?php
		if(isset($_SESSION['rank']) && $_SESSION['rank'] == "user"  && $_SESSION['user_status'] != 2)
		{
	?>
	var rowC = $("#devsTable tr").length;
	
	$("#devsTable > tbody > tr").each(function()
	{
		var dID = $(this).attr("id");
		
		$(this).delegate(".orderDevice", "click", function()
		{
			loadInfo(dID);
		});
	});
	<?php
		}
	?>

    <?php
		if(!isset($_SESSION['rank']) || (isset($_SESSION['rank']) && $_SESSION['rank'] == "user"  && $_SESSION['user_status'] != 2))
		{
	?>
    $('input[type="submit"]').button();
	<?php
		}
	?>
}
</script>

<div class="page-header">
	<h3>Zařízení</h3>
</div>

<div class="article">
	<div class="title"><h4>Stav</h4></div>
	
	<div class="well">
		<ul id="categories" class="nav nav-list">
		</ul>
	</div>
	
	<div>
		<div class="pull-left">
			<ul class="ui-legend">
				<li class="black">K vypůjčení</li>
				<li class="orange">Vypůjčeno</li>
				<li class="red">Nelze vypůjčit, poškozené</li>
			</ul>
		</div>
	
		<div class="controls text-right">
			<input type="checkbox" checked="checked" class="hide" id="free">
			<button class="btn btn-primary" id="free_label">Pouze volná</button>
		</div>
	</div>
</div>

<?php
	if(!isset($_SESSION['rank']))
	{
?>
<div id="dialog-login" class="dialog-form" title="Příhlašovací okno">
	<form class="text-center">
		<fieldset>
		  <label for="email">Školní email</label>
		  <input type="text" name="email" id="email" placeholder="příjmení.jméno@ssakhk.cz">
		  <label for="password">Heslo</label>
		  <input type="password" name="password" id="password" placeholder="******">
		</fieldset>
	</form>
</div>
<?php
	}
	else
	{
		if($_SESSION['rank'] == "user"  && $_SESSION['user_status'] != 2)
		{
?>
<div id="dialog_orderDevice" class="dialog-form" title="Vypůjčit zařízení">
	<strong id="deviceName_orderDevice"></strong>
	<p id="deviceDescription_orderDevice"></p>
	<p id="deviceStatus_orderDevice"></p>
	<p id="deviceCategory_orderDevice"></p>
	
	<hr>
	
	<form class="text-center">
		<fieldset>
			<input type="text" class="hide" name="deviceID_orderDevice" id="deviceID_orderDevice">
			<div>
				<div class="pull-left">
					<label for="deviceFrom_orderDevice">Od</label>
					<input type="text" name="deviceFrom_orderDevice" id="deviceFrom_orderDevice" placeholder="30.4.2013">
				</div>
				<div class="pull-right">
					<label for="deviceTo_orderDevice">Do</label>
					<input type="text" name="deviceTo_orderDevice" id="deviceTo_orderDevice" placeholder="10.5.2013">
				</div>
			</div>
			<label for="deviceComment_orderDevice">Váš komentář(nepovinný)</label>
			<textarea id="deviceComment_orderDevice"></textarea>
		</fieldset>
	</form>
</div>
<?php
		}
	}
?>

<div class="alert" id="alert_bar">
	<a class="close" id="close_alert">×</a>
	<strong id="alert_info" class="alert_info"></strong>
	<div id="alert_text"></div>
</div>