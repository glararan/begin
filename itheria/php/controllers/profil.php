<?php
    require_once("../../config.php");
    require_once("../database.php");
    require_once("../user.php");

    session_start();

    $db = new Database(DB_AUTH);

    if(!$db->isInitialized())
        die(json_encode(array("success" => 0, "error" => "Databáze se neinicializovala!")));

    if(!User::isLogged())
        die(json_encode(array("success" => 0, "error" => "Nejste přihlášený!")));

    $oldPass = $_POST['oldPass'];
    $newPass = $_POST['newPass'];
    $newPass2 = $_POST['newPass2'];

    if(is_null($oldPass) || !is_string($oldPass))
        die(json_encode(array("error" => "Staré heslo není řetězec", "success" => 0, "email" => 0, "password" => 0)));

    if(is_null($newPass) || !is_string($newPass) || is_null($newPass2) || !is_string($newPass2) || $newPass != $newPass2)
        die(json_encode(array("error" => "Hesla se neshodují nebo nesplňují požadavky", "success" => 0, "email" => 0, "password" => 0)));

    $account = "account";
    $rbac    = "rbac_account_permissions";

    $result = $db->toArray($db->select("SELECT sha_pass_hash as hash FROM account WHERE id = :id", array(":id" => User::getInstance()->getID())));

    if(count($result) > 0)
    {
        $result = $result[0];
        
        if($result["hash"] == sha1(strtoupper(User::getInstance()->getName()).":".strtoupper($oldPass)))
        {
            $db->update("account", array("sha_pass_hash" => sha1(strtoupper(User::getInstance()->getName()).":".strtoupper($newPass))), array("id" => User::getInstance()->getID()));
            
            echo json_encode(array("error" => 0, "success" => 1));
        }
        else
            die(json_encode(array("error" => "Staré heslo je nevalidní!", "success" => 0)));
    }
    else
        die(json_encode(array("error" => "Váš účet byl s největší pravděpodobností smazán, nebo došlo k chybě!", "success" => 0)));
?>