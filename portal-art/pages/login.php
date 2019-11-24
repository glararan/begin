<?php
	//session_start();

	if(isset($_SESSION['rank']))
	{
?>
<div class="page-header">
	<h3>Přihlášení</h3>
</div>

<div class="article">
	<div class="title"><h4>Error</h4></div>
	
	<p>Chyba! Už jste přihlášeni.</p>
</div>
<?php
	}
	else
	{
?>

<script>
	$(document).ready(function()
	{
		$("#alert_bar").fadeOut(0);
		
		$('input[type="submit"]').click(function(event)
		{
			event.preventDefault();
			
			var mail = $('input[name="email"]').val();
			var pass = $('input[name="password"]').val();
			
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
			
			$.post('/php/login.php', {email: mail, password: pass}, function(data)
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
		});
		
		$("#close_alert").click(function()
		{
			$("#alert_bar").fadeOut("slow");
		});
	});
</script>

<div class="page-header">
	<h3>Přihlašení</h3>
</div>

<div class="article">
	<div class="title"><h4>Přihlašovací formulář</h4></div>
	
	<form id="login" class="text-center">
		<label>Školní email</label>
		<input type='text' name='email' placeholder="příjmení.jméno@ssakhk.cz">
		<label>Heslo</label>
		<input type='password' name='password' placeholder="******">
		<br>
		<input class="btn btn-primary" type='submit' value='Přihlásit se'>
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