<?php
    require_once("../../../config.php");

    require_once("../../../php/database.php");
    require_once("../../../php/user.php");

    session_start();

    $horde    = 0x3B2;
    $alliance = 0x20044D;

    if(!User::existsInstance() || !in_array(User::getInstance()->getType(), array(UserType::GM, UserType::Admin)))
        die(json_encode(array("error" => "Nejspíše vypršela session, přihlašte se znovu.", "success" => 0)));

    $charDB = new Database(DB_CHAR);

    $query = $charDB->toArray($charDB->select("SELECT * FROM characters WHERE online = 1")); // char online

    $map = array();

    for($i = 0; $i < count($query); $i++)
    {
        $player = array("name" => $query[$i]["name"],
                        "position" => array("x" => $query[$i]["position_x"],
                                            "y" => $query[$i]["position_y"],
                                            "z" => $query[$i]["position_z"],
                                            "o" => $query[$i]["orientation"]),
                        "map" => $query[$i]["map"],
                        "faction" => $horde & (0x1 << ($query[$i]["race"] - 1)));
        
        array_push($map, $player);
    }

    echo json_encode(array("error" => "", "success" => 1, "players" => $map));
?>