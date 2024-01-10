<?php
class DbConnect {
    private $host;
    private $dbName;
    private $user;
    private $pass;
    
    public function __construct() {
        $this->host = getenv('MYSQL_HOST');
        $this->dbName = getenv('MYSQL_DB');
        $this->user = getenv('MYSQL_USER');
        $this->pass = getenv('MYSQL_PASS');
    }
    
    public function connect() {
        try {
            $conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            echo 'Database Error: ' . $e->getMessage();
            return null; // Return null or handle the error as needed
        }
    }
}
?>
