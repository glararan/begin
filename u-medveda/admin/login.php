<?php
	if(!isset($_POST['submit']))
	{
		if($_SESSION['login_error_displayed'])
		{
			$_SESSION['login_error']           = "";
			$_SESSION['login_error_displayed'] = false;
		}
		
?>

<form method="post" action="" class="login">
	<p>
		<label for="login">Účet:</label>
		<input type="text" name="login" id="login" placeholder="administrator">
	</p>

	<p>
		<label for="password">Heslo:</label>
		<input type="password" name="password" id="password" placeholder="*********">
	</p>
	
	<p class="login-submit">
		<button type="submit" name="submit" class="login-button">Přihlásit se</button>
	</p>
	
	<p class="forgot-password"><a href="/admin/zapomenute-heslo/">Zapomněli jste heslo?</a></p>
	<?php
		if(!empty($_SESSION['login_error']))
		{
			echo $_SESSION['login_error'];
			
			$_SESSION['login_error_displayed'] = true;
		}
	?>
</form>

<?php
	}
	else
	{
		function setLoginError($message)
		{
			$_SESSION['login_error'] = "<p class='login-error'>".$message."</p>";
			
			echo '<meta http-equiv="refresh" content="0; url=/admin/">';
		}
		
		$lUser = "admin";
		$lPass = "test2";
	
		$user = $_POST['login'];
		$pass = $_POST['password'];
		
		if(!isset($user))
		{
			setLoginError("Uživatelské jméno je prázdné!");

			return;
		}
		
		if(!isset($pass))
		{
			setLoginError("Heslo je prázdné!");
			
			return;
		}
		
		if(preg_match("/^[a-zA-Z]+$/", $user) != 1)
		{
			setLoginError("Uživatelské jméno obsahuje nepovolené znaky!");
			
			return;
		}
		
		if(preg_match("/^[a-zA-Z0-9]+$/", $pass) != 1)
		{
			setLoginError("Heslo obsahuje nepovolené znaky!");
			
			return;
		}
		
		if($user != $lUser)
		{
			setLoginError("Uživatelské jméno se neschoduje!");
			
			return;
		}
		
		if($pass != $lPass)
		{
			setLoginError("Heslo se neschoduje!");
			
			return;
		}
		
		$_SESSION['user']        = $user;
		$_SESSION['login_error'] = "";
		
		echo '<meta http-equiv="refresh" content="0; url=/admin/">';
	}
?>
	