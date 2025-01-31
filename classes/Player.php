<?php
require_once 'Database.php';

class Player {
    private $db;
    private $table = 'players';

    public function __construct() {
        $this->db = new Database();
    }

    // Get all players for a specific team
    public function getPlayersByTeam($teamId) {
        $sql = "SELECT * FROM {$this->table} WHERE team_id = :team_id";
        $stmt = $this->db->query($sql, ['team_id' => $teamId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new player
    public function addPlayer($name, $position_id, $team_id) {
        $sql = "INSERT INTO {$this->table} (name, position_id, team_id) VALUES (:name, :position_id, :team_id)";
        return $this->db->query($sql, [
            'name' => $name,
            'position_id' => $position_id,
            'team_id' => $team_id
        ]);
    }

    // Edit an existing player
    public function editPlayer($playerId, $name, $position_id, $team_id) {
        $sql = "UPDATE {$this->table} SET name = :name, position_id = :position_id, team_id = :team_id WHERE id = :id";
        return $this->db->query($sql, [
            'name' => $name,
            'position_id' => $position_id,
            'team_id' => $team_id,
            'id' => $playerId
        ]);
    }

    // Delete a player
    public function deletePlayer($playerId) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->db->query($sql, ['id' => $playerId]);
    }
}
