<?php
class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "portfolio_db";
    private $conn;    public function connect()
    {
        try {
            if (!$this->conn) {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->database,
                    $this->username,
                    $this->password,
                    array(PDO::ATTR_PERSISTENT => true)
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->conn;
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw new PDOException("Connection failed: " . $e->getMessage());
        }
    }
}
