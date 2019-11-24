<?php
    require_once("../../../config.php");

    require_once("../../../php/database.php");
    require_once("../../../php/user.php");

    session_start();

    if(!User::existsInstance() || !in_array(User::getInstance()->getType(), array(UserType::GM, UserType::Admin)))
        die(json_encode(array("error" => "Nejspíše vypršela session, přihlašte se znovu.", "success" => 0)));

    $type = $_POST['type'];

    if(is_null($type) || !is_numeric($type))
        die(json_encode(array("error" => "Typ je nevalidní", "success" => 0)));

    $webDB = new Database(DB_WEB);

    switch($type)
    {
        case 0: // status
            {
                $id     = $_POST["task"];
                $status = $_POST["status"];
                
                if(is_null($id) || !is_numeric($id) || is_null($status) || !is_numeric($status))
                    die(json_encode(array("error" => "Úkol je nevalidní", "success" => 0)));
                
                $webDB->update("tasks", array("status" => $status), array("id" => $id));
            }
            break;
            
        case 1: // add
            {
                $title    = $_POST["title"];
                $priority = $_POST["priority"];
                $comment  = $_POST["comment"];
                
                if(is_null($title) || !is_string($title))
                    die(json_encode(array("error" => "Nadpis je nevalidní", "success" => 0)));
                
                if(is_null($priority) || !is_numeric($priority))
                    die(json_encode(array("error" => "Priorita je nevalidní", "success" => 0)));
                
                if(is_null($comment) || !is_string($comment))
                    die(json_encode(array("error" => "Komentář je nevalidní", "success" => 0)));
                
                $webDB->insert("tasks", array("title" => $title,
                                              "comment" => $comment,
                                              "priority" => $priority));
            }
            break;
            
        case 2: // edit
            {
                $title    = $_POST["title"];
                $priority = $_POST["priority"];
                $comment  = $_POST["comment"];
                $task     = $_POST["task"];
                
                if(is_null($task) || !is_numeric($task))
                    die(json_encode(array("error" => "Úkol je nevalidní", "success" => 0)));
                
                if(is_null($title) || !is_string($title))
                    die(json_encode(array("error" => "Nadpis je nevalidní", "success" => 0)));
                
                if(is_null($priority) || !is_numeric($priority))
                    die(json_encode(array("error" => "Priorita je nevalidní", "success" => 0)));
                
                if(is_null($comment) || !is_string($comment))
                    die(json_encode(array("error" => "Komentář je nevalidní", "success" => 0)));
                
                $data = array("title" => $title,
                              "priority" => $priority,
                              "comment" => $comment);
                
                $webDB->update("tasks", $data, array("id" => $task));
            }
            break;
            
        default:
            die(json_encode(array("error" => "Typ je mimo index", "success" => 0)));
            break;
    }

    echo json_encode(array("error" => "", "success" => 1));
?>