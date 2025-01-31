<?php
require_once 'Database.php';

class Sport {
    private $db;
    private $table = 'sports';

    public function __construct() {
        $this->db = new Database();
    }

    // Get all sports
    public function getSports() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new sport
    public function addSport($name) {
        $sql = "INSERT INTO {$this->table} (name) VALUES (:name)";
        return $this->db->query($sql, ['name' => $name]);
    }

    // Edit an existing sport
    public function editSport($sportId, $name) {
        $sql = "UPDATE {$this->table} SET name = :name WHERE id = :id";
        return $this->db->query($sql, ['name' => $name, 'id' => $sportId]);
    }

    // Delete a sport
    public function deleteSport($sportId) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->db->query($sql, ['id' => $sportId]);
    }
}
