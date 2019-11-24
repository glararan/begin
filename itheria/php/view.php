<?php
    class View
    {
        public static function show($path, $data = false)
        {
            if(file_exists("php/models/".$path.".php"))
                require_once("models/".$path.".php");
            
            require("views/".$path.".php");
        }
        
        public static function showAdmin($path, $data = false)
        {
            if(file_exists("../admin/php/models/".$path.".php"))
                require_once("../admin/php/models/".$path.".php");
            
            require("../admin/php/views/".$path.".php");
        }
    }
?>