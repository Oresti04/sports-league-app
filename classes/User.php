<?php
require_once 'Database.php';

class User {
    private $db;
    private $table = 'users';

    public function __construct() {
        $this->db = new Database();
    }

    // Method to login the user
    public function login($username, $password) {
        // Correct SQL query to fetch role name
        $sql = "SELECT u.id, u.username, u.password, r.name AS role, u.team_id 
                FROM {$this->table} u
                JOIN roles r ON u.role_id = r.id
                WHERE u.username = :username";
        $stmt = $this->db->query($sql, ['username' => $username]);
        
        // Check if the statement execution was successful
        if ($stmt === false) {
            throw new Exception("Query failed: " . implode(", ", $this->db->errorInfo())); // Debugging error message
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify if user exists and the password matches
        if ($user && $user['password'] === $this->hashPassword($password)) {
            $_SESSION['user_id'] = $user['id'];     // Store user ID in session
            $_SESSION['role'] = $user['role'];       // Store role name in session
            $_SESSION['team_id'] = $user['team_id']; // Store team ID in
            return true; // Successful login
        }
        return false; // Failed login
    }

    // Hash passwords using SHA-256
    public function hashPassword($password) {
        return hash('sha256', $password);
    }

    // Get the current user's role from the session
    public function getRole() {
        return $_SESSION['role'] ?? null; 
    }

    // Get the current user's ID from the session
    public function getId() {
        return $_SESSION['user_id'] ?? null; 
    }

    // Check if the user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);  
    }

    // Get the current user's league ID (used for League Managers or Team Managers)
    public function getLeagueId() {
        return $_SESSION['league_id'] ?? null;
    }

    // Get the current user's team ID (for Coaches or Team Managers)
    public function getTeamId() {
        return $_SESSION['team_id'] ?? null; 
    }

    // Authorize user based on required role
    public function authorize($requiredRole) {
        if (!$this->isLoggedIn() || $this->getRole() !== $requiredRole) {
            header('Location: ../pages/login.php'); // Redirect to login page if not authorized
            exit();
        }
    }

    // Fetch all users (admin functionality)
    public function getAllUsers() {
        $sql = "SELECT u.username, r.name AS role 
                FROM {$this->table} u
                JOIN roles r ON u.role_id = r.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
