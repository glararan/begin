<?php
    class Database extends PDO
    {
        private $initialized = true;
        
        function __construct($db)
        {
            try
            {
                parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.$db.';charset=utf8', DB_USER, DB_PASS);
                
                $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
            }
            catch(PDOException $e)
            {
                //Logger::newMessage($e);
                //logger::customErrorMsg();
                $this->initialized = false;
            }
        }
        
        public function isInitialized()
        {
            return $this->initialized;
        }

        public function select($sql, $array = array(), $fetchMode = PDO::FETCH_OBJ)
        {
            $stmt = $this->prepare($sql);
            
            foreach($array as $key => $value)
                $stmt->bindValue("$key", $value);

            $stmt->execute();
            
            return $stmt->fetchAll($fetchMode);
        }
        
        public static function toArray($select)
        {
            if(is_object($select)) // Gets the properties of the given object with get_object_vars function
                $select = get_object_vars($select);

            if(is_array($select)) // Return array converted to object Using __FUNCTION__ (Magic constant) for recursive call
                return array_map("Database::toArray", $select);
            else // Return array
                return $select;
        }

        public function insert($table, $data)
        {
            ksort($data);

            $fieldNames  = implode(',', array_keys($data));
            $fieldValues = ':'.implode(', :', array_keys($data));

            $stmt = $this->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

            foreach($data as $key => $value)
                $stmt->bindValue(":$key", $value);

            $stmt->execute();
        }
        
        public function replace($table, $data, $where)
        {
            ksort($data);

            $fieldDetails = NULL;
            
            foreach($data as $key => $value)
                $fieldDetails .= "$key = :$key,";

            $fieldDetails = rtrim($fieldDetails, ',');

            $whereDetails = NULL;
            $i = 0;
            
            foreach($where as $key => $value)
            {
                if($i == 0)
                    $whereDetails .= "$key = :$key";
                else
                    $whereDetails .= " AND $key = :$key";

                $i++;
            }
            
            $whereDetails = ltrim($whereDetails, ' AND ');
            
            $values = implode(',', array_keys($data));

            $stmt = $this->prepare("REPLACE INTO $table ($values) VALUES ($fieldDetails) WHERE $whereDetails");

            foreach($data as $key => $value)
                $stmt->bindValue(":$key", $value);

            foreach($where as $key => $value)
                $stmt->bindValue(":$key", $value);

            $stmt->execute();
        }

        public function update($table, $data, $where)
        {
            ksort($data);

            $fieldDetails = NULL;
            
            foreach($data as $key => $value)
                $fieldDetails .= "$key = :$key,";

            $fieldDetails = rtrim($fieldDetails, ',');

            $whereDetails = NULL;
            $i = 0;
            
            foreach($where as $key => $value)
            {
                if($i == 0)
                    $whereDetails .= "$key = :$key";
                else
                    $whereDetails .= " AND $key = :$key";

                $i++;
            }
            
            $whereDetails = ltrim($whereDetails, ' AND ');

            $stmt = $this->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");

            foreach($data as $key => $value)
                $stmt->bindValue(":$key", $value);

            foreach($where as $key => $value)
                $stmt->bindValue(":$key", $value);

            $stmt->execute();
        }

        public function delete($table, $where, $limit = 1)
        {
            ksort($where);

            $whereDetails = NULL;
            $i = 0;
            
            foreach($where as $key => $value)
            {
                if($i == 0)
                    $whereDetails .= "$key = :$key";
                else
                    $whereDetails .= " AND $key = :$key";

                $i++;
            }
            
            $whereDetails = ltrim($whereDetails, ' AND ');

            $stmt = $this->prepare("DELETE FROM $table WHERE $whereDetails LIMIT $limit");

            foreach($where as $key => $value)
                $stmt->bindValue(":$key", $value);

            $stmt->execute();
        }

        public function truncate($table)
        {
            return $this->exec("TRUNCATE TABLE $table");
        }
    }

    /*public static function toArray($select)
    {
        if(is_object($select)) // Gets the properties of the given object with get_object_vars function
            $select = get_object_vars($select);

        if(is_array($select)) // Return array converted to object Using __FUNCTION__ (Magic constant) for recursive call
            return array_map(__FUNCTION__, $select);
        else // Return array
            return $select;
    }*/
?>