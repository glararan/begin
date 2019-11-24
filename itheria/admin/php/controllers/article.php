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
        case 0: // delete
            {
                if(User::getInstance()->getType() != UserType::Admin)
                    die(json_encode(array("error" => "Nemáte oprávnění.", "success" => 0)));
                
                $article = $_POST["article"];
                
                if(is_null($article) || !is_numeric($article))
                    die(json_encode(array("error" => "Článek je nevalidní", "success" => 0)));
                
                $webDB->delete("news", array("id" => $article));
            }
            break;
            
        case 1: // add
            {
                $content = $_POST["content"];
                $title   = $_POST["title"];
                
                if(is_null($title) || !is_string($title))
                    die(json_encode(array("error" => "Titulek je nevalidní", "success" => 0)));
                
                if(is_null($content) || !is_string($content))
                    die(json_encode(array("error" => "Obsah je nevalidní", "success" => 0)));
                
                $webDB->insert("news", array("author" => User::getInstance()->getID(),
                                             "title" => $title,
                                             "content" => $content,
                                             "date" => date("Y-m-d H:i:s")));
            }
            break;
            
        case 2: // edit
            {
                $content = $_POST["content"];
                $title   = $_POST["title"];
                $article = $_POST["article"];
                
                if(is_null($article) || !is_numeric($article))
                    die(json_encode(array("error" => "Článek je nevalidní", "success" => 0)));
                
                if(is_null($title) || !is_string($title))
                    die(json_encode(array("error" => "Titulek je nevalidní", "success" => 0)));
                
                if(is_null($content) || !is_string($content))
                    die(json_encode(array("error" => "Obsah je nevalidní", "success" => 0)));
                
                $data = array("title" => $title,
                              "content" => $content);
                
                $webDB->update("news", $data, array("id" => $article));
            }
            break;
            
        case 3: // load
            {
                $id = $_POST["id"];
                
                if(is_null($id) || !is_numeric($id))
                    die(json_encode(array("error" => "Článek je nevalidní", "success" => 0)));
                
                $query = $webDB->toArray($webDB->select("SELECT * FROM news WHERE id = :id", array(":id" => $id)))[0];
                
                echo json_encode(array("error" => "", "success" => 1, "article" => $query));
                
                return;
            }
            break;
            
        default:
            die(json_encode(array("error" => "Typ je mimo index", "success" => 0)));
            break;
    }

    echo json_encode(array("error" => "", "success" => 1));
?>