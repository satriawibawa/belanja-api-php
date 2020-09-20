<?php
// used to get mysql database connection
class DatabaseService{

    private $db_host = "localhost";
    private $db_name = "penjualan";
    private $db_user = "root";
    private $db_password = "";
    private $connection;

    public function getConnection(){

        $this->connection = null;

        try{
            $this->connection = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
        }catch(PDOException $exception){
            echo "Connection failed: " . $exception->getMessage();
        }

        return $this->connection;
    }
}

// CREATE TABLE IF NOT EXISTS `Users` ( `id` INT AUTO_INCREMENT ,
// `first_name` VARCHAR(150) NOT NULL , `last_name` VARCHAR(150) NOT NULL ,
// `email` VARCHAR(255), `password` VARCHAR(255), PRIMARY KEY (`id`) )