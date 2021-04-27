<?php 

class Db {

    public $dbhost;
    public $dbusername;
    public $dbpassword;
    public $dbname;

    public function getConnection(){
        $database=new mysqli($this->dbhost,$this->dbusername,$this->dbpassword,$this->dbname);
        if($database->connect_error){
            die("Error: ".$database->connect_error);
        }
        return $database;
    }
}




?>