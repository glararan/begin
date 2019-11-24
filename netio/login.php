<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    
    <title>netIO 4 - <?php echo $lang['login_login']; ?></title>

	<base href="http://open-source.veteska.cz/netio/">
    
    <link href="css/login.css" rel="stylesheet" type="text/css">
    
    <script type="text/javascript">
	  WebFontConfig =
	  {
		google: { families: [ 'Dosis:300:latin,latin-ext' ] }
	  };
	  (function()
	  {
		var wf = document.createElement('script');
		wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
		  '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
		wf.type = 'text/javascript';
		wf.async = 'true';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(wf, s);
	  })(); 
	</script>
</head>
<body>
	<div class="logo"></div>
	<div class="login_wrapper">
		<?php
            if(!isset($_POST['login']))
            {
        ?>
        <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="account"><?php echo $lang['login_account']; ?><br></label>
            <input type="text" name="account" id="account" class="box" placeholder="Account">
            <br>
            <label for="password"><?php echo $lang['login_password']; ?><br></label>
            <input type="password" name="password" id="password" class="box" placeholder="Password">
            <br>
            <input type="submit" name="login" id="login" class="button" value="Log In">
        </form>
        <?php
            }
            else
            {
                $acc = $_POST['account'];
                $pass = $_POST['password'];
                
                $xml = new DOMDocument();
                $xml->load('data/database/users.xml');
                
                $Accounts = $xml->getElementsByTagName('Account');
                
                $accIsFinded = false;
                $passIsTrue = false;
                
                foreach($Accounts as $Account)
                {
                    $names = $Account->getElementsByTagName('Name');
                    $name = $names->item(0)->nodeValue;
                    
                    if($name == $acc)
                    {
                        $accIsFinded = true;
                        
                        $passwords = $Account->getElementsByTagName('Password');
                        $password = $passwords->item(0)->nodeValue;
                        
                        if($password == md5($pass))
                            $passIsTrue = true;
							
						$accIP = $Account->getElementsByTagName('IP');
						$IP = $accIP->item(0);
						$IP->nodeValue = $_SERVER['REMOTE_ADDR'];
                        
						$accLastLogin = $Account->getElementsByTagName('LastLogin');
						$lastLogin = $accLastLogin->item(0);
						$lastLogin->nodeValue = date("d/m/Y H:i:s", time());
						
                        break;
                    }
                }
                
                if($accIsFinded == false)
                    die($lang['login_error_0'].'<meta http-equiv="refresh" content="2">');
                else
                {
                    if($passIsTrue == false)
                        die($lang['login_error_1'].'<meta http-equiv="refresh" content="2">');
                    else
                    {
						$xml->save('data/database/users.xml');
                        echo $lang['login_success_0'];
                        
                        $_SESSION['account'] = $acc;
                        echo '<meta http-equiv="refresh" content="2">';
                    }
                }
            }
        ?>
	</div>
</body>
</html>