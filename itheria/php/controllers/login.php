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

    $acc   = $_POST['account'];
    $passw = $_POST['password'];

    if(is_null($acc) || !is_string($acc))
        die(json_encode(array("error" => "Účet není řetězec", "success" => 0, "email" => 0, "password" => 0)));

    if(is_null($passw) || !is_string($passw))
        die(json_encode(array("error" => "Heslo není řetězec", "success" => 0, "email" => 0, "password" => 0)));

    $account = "account";
    $rbac    = "rbac_account_permissions";

    $result = $db->toArray($db->select("SELECT $account.username as username, $account.email as email, $account.sha_pass_hash as hash, $rbac.permissionId as permissionId FROM $account LEFT JOIN $rbac ON $rbac.accountId = $account.id"));

    $userExists = false;

    if(count($result) > 0)
    {
        for($i = 0; $i < count($result); $i++)
        {
            if(strtoupper($result[$i]["username"]) == strtoupper($acc))
            {
                $userExists = true;
                
                if($result[$i]["hash"] == sha1(strtoupper($acc).":".strtoupper($passw)) || $result[$i]["hash"] == strtoupper(sha1(strtoupper($acc).":".strtoupper($passw))))
                {
                    $type = 0;
                    
                    if($result[$i]["permissionId"] == 192)
                        $type = UserType::Admin;
                    else if($result[$i]["permissionId"] == 193)
                        $type = UserType::GM;
                    
                    // LOGGED
                    $_SESSION['userClass'] = new User($type, $result[$i]["username"]);
                    
                    echo json_encode(array("error" => 0, "success" => 1, "email" => 1, "password" => 1));
                    
                    return;
                }
            }
        }
        
        if($userExists)
            die(json_encode(array("error" => "Špatné heslo!", "success" => 0, "email" => 1, "password" => 0)));
        else
            die(json_encode(array("error" => "Účet neexistuje!", "success" => 0, "email" => 0, "password" => 0)));
    }
    else
        die(json_encode(array("error" => "Žádný účet dosued neexistuje!", "success" => 0, "email" => 0, "password" => 0)));
?>