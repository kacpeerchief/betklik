<?php
class Database {
    private mysqli $connection;

    public function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "projekt4";

        $this->connection = new mysqli($servername, $username, $password, $dbname);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getDatabaseConnection(): mysqli {
        return $this->connection;
    }
}
?>
