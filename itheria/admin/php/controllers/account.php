<?php
    require_once("../../../config.php");

    require_once("../../../php/database.php");
    require_once("../../../php/user.php");

    session_start();

    if(!User::existsInstance() || User::getInstance()->getType() != UserType::Admin)
        die(json_encode(array("error" => "Nejspíše vypršela session, přihlašte se znovu.", "success" => 0)));

    $type = $_POST['type'];
    $acc  = $_POST['account'];

    if(is_null($type) || !is_numeric($type))
        die(json_encode(array("error" => "Typ je nevalidní", "success" => 0)));

    if(is_null($acc) || !is_numeric($acc))
        die(json_encode(array("error" => "Account je nevalidní", "success" => 0)));

    if($acc == User::getInstace()->getID())
        die(json_encode(array("error" => "Nemůžete provádět akce sám na sebe!", "success" => 0)));

    $authDB = new Database(DB_AUTH);

    switch($type)
    {
        case 0: // delete
            $authDB->delete("account", array("id" => $acc));
            break;
            
        case 1: // set
            {
                $level = $_POST['level'];
                
                if(is_null($level) || !is_numeric($level) || $level > 2 || $level < 0)
                    die(json_encode(array("error" => "Level je nevalidní", "success" => 0)));
                
                if($level == 0)
                    $authDB->delete("rbac_account_permissions", array("accountId" => $acc));
                else
                {
                    $data = array("accountId" => $acc,
                                  "permissionId" => $level,
                                  "granted" => 1,
                                  "realmId" => -1);
                
                    $authDB->replace("rbac_account_permissions", $data, array("accountId" => $acc));
                }
            }
            break;
            
        default:
            die(json_encode(array("error" => "Typ je mimo index", "success" => 0)));
            break;
    }

    echo json_encode(array("error" => "", "success" => 1));
?>