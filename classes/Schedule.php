<?php
require_once 'Database.php';

class Schedule {
    private $db;
    private $table = 'schedule';

    public function __construct() {
        $this->db = new Database();
    }

    // Get all scheduled games with team details
    public function getGames() {
        $sql = "SELECT g.id, g.homescore, g.awayscore, g.scheduled, g.completed,
                       home_team.name AS home_team_name, away_team.name AS away_team_name
                FROM {$this->table} g
                JOIN teams home_team ON g.hometeam_id = home_team.id
                JOIN teams away_team ON g.awayteam_id = away_team.id
                ORDER BY g.scheduled DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
