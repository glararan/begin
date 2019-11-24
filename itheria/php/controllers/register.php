<?php
    require_once("../../config.php");
    require_once("../database.php");
    require_once("../user.php");

    session_start();

    $db = new Database(DB_AUTH);

    if(!$db->isInitialized())
        die(json_encode(array("success" => 0, "error" => "Databáze se neinicializovala!")));

    if(User::isLogged())
        die(json_encode(array("success" => 0, "error" => "Jste již přihlášený!")));

    $acc    = $_POST['account'];
    $email  = $_POST['email'];
    $passw  = $_POST['password'];
    $passw2 = $_POST['password2'];

    if(is_null($acc) || !is_string($acc))
        die(json_encode(array("error" => "Účet není řetězec", "success" => 0, "user" => 0, "email" => 0, "password" => 0)));

    if(is_null($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        die(json_encode(array("error" => "Email není validní", "success" => 0, "user" => 0, "email" => 0, "password" => 0)));

    if(is_null($passw) || !is_string($passw) || is_null($passw2) || !is_string($passw2))
        die(json_encode(array("error" => "Heslo není řetězec", "success" => 0, "user" => 0, "email" => 0, "password" => 0)));

    if($passw != $passw2)
        die(json_encode(array("error" => "Heslo není totožné", "success" => 0, "user" => 0, "email" => 0, "password" => 0)));

    $result = $db->toArray($db->select("SELECT username, email FROM account"));

    $userExists = false;
    $emailUsed = false;

    if(count($result) > 0)
    {
        for($i = 0; $i < count($result); $i++)
        {
            if(strtoupper($result[$i]["username"]) == strtoupper($acc))
                $userExists = true;
            
            if(strtoupper($result[$i]["email"]) == strtoupper($email))
                $emailUsed = true;
        }
        
        if($userExists)
            die(json_encode(array("error" => "Účet s tímto jménem již existuje!", "success" => 0, "user" => 1, "email" => 0, "password" => 0)));
        
        if($emailUsed)
            die(json_encode(array("error" => "Email byl již použit, zvolte jiný.", "success" => 0, "user" => 0, "email" => 1, "password" => 0)));
    }

    $data = array("username" => strtoupper($acc),
                  "sha_pass_hash" => sha1(strtoupper($acc).":".strtoupper($passw)),
                  "email" => $email,
                  "expansion" => 3);

    $db->insert("account", $data);

    $user = new User(0, $email);
                    
    $_SESSION['userClass'] = $user;
                
    echo json_encode(array("error" => 0, "success" => 1, "email" => 1, "password" => 1));
?>