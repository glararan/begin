<?php
    require_once("database.php");

    interface UserType
    {
        const Player = 0;
        const GM     = 1;
        const Admin  = 2;
    }

    class User
    {
        private $id;
        private $username;
        private $profileImage;
        private $email;
        private $type;
        private $registrationdate;
        
        public function __construct($userType = null, $user = "", $registerDate = null)
        {            
            $this->type  = $userType;
            $this->username = $user;
            
            if(in_array($userType, array(UserType::Player, UserType::GM, UserType::Admin)) && $user != null)
            {
                $db = new Database(DB_AUTH);
                
                $users = "account";
                
                $result = $db->toArray($db->select("SELECT $users.* FROM $users WHERE username = :user", array(":user" => $user)))[0];
                
                $this->id               = $result["id"];
                $this->email            = $result["email"];
                //$this->profileImage     = $result["profileImage"];
                $this->registrationdate = $registerDate == null ? new DateTime($result["joindate"]) : new DateTime($registerDate);
                
                // delete this
                if($this->username == "TESTMAN")
                    $this->type = UserType::Admin;
            }
        }
        
        // IMPLEMENTOVAT refresh()
        public function refresh()
        {
            $users = "account";
            
            if($this->type != null && $this->username != null)
            {
                $db = new Database(DB_AUTH);
            
                $result = $db->toArray($db->select("SELECT $users.* FROM $users WHERE username = :user", array(":user" => $this->username)))[0];
                
                $this->email          = $result["email"];
                //$this->profileImage = $result["profileImage"];
            }
        }
        
        public function __set($property, $value)
        {
            switch($property)
            {                
                case "UserName":
                    $this->username = $value;
                    break;
                
                case "ProfileImage":
                    $this->profileImage = $value;
                    break;
                
                case "Email":
                    $this->email = $value;
                    break;
                
                case "Type":
                    $this->type = $value;
                    break;
                
                default:
                    {
                        if(property_exists($this, $property))
                            $this->$property = $value;
                    }
                    break;
            }
        }
        
        public static function existsInstance()
        {
            if(isset($_SESSION['userClass']) && $_SESSION['userClass'] instanceof User)
                return true;
            
            return false;
        }
        
        public static function getInstance()
        {
            if(isset($_SESSION['userClass']) && $_SESSION['userClass'] instanceof User)
                return $_SESSION['userClass'];
            else
                return null;
        }
        
        public static function setInstance($instance)
        {
            if($instance instanceof User)
                $_SESSION['userClass'] = $instance;
        }
        
        public static function isLogged()
        {
            if(User::existsInstance() && User::getInstance() != null && User::getInstance()->getName() != "")
                return true;
            
            return false;
        }
        
        public function getID()
        {
            return $this->id;
        }
               
        public function getName()
        {
            return $this->username;
        }
        
        public function getEmail()
        {
            return $this->email;
        }
        
        public function getType()
        {
            return $this->type;
        }
        
        public function getTypeName()
        {
            if($this->type == UserType::Player)
                return "Hráč";
            else if($this->type == UserType::GM)
                return "GameMaster";
            else if($this->type == UserType::Admin)
                return "Administrátor";
            else
                return "Unknown";
        }
        
        public function getProfileName()
        {
            return ucfirst(strtolower($this->username));
        }
        
        public function getProfilePath()
        {
            if($this->profileImage == null)
                return "/images/profilovka.png";
            else
                return $this->profileImage;
        }
        
        public function getRegistrationDate()
        {
            return $this->registrationdate;
        }
    }
?>