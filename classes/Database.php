<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'ok6522';
    private $username = 'ok6522';
    private $password = 'Immorality9&selfsatisfied';
    private $conn;

    // Establish a database connection using PDO
    public function __construct() {
        $this->connect();
    }

    // Establish connection
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
    }

    // Function to run parameterized queries
    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            echo 'Query Error: ' . $e->getMessage();
            return false;
        }
    }

    // Fetch all rows for a query
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // Get the last inserted ID
    public function lastInsertId() {
        return $this->conn->lastInsertId(); // Returns the ID of the last inserted row
    }

    // Close connection
    public function disconnect() {
        $this->conn = null;
    }

    // Return the PDO connection for direct use
    public function getConnection() {
        return $this->conn;
    }
}
