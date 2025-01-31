<?php
require_once 'Database.php';

class Team {
    private $db;
    private $table = 'teams';

    public function __construct() {
        $this->db = new Database();
    }

    // Get all teams with sport, league, and season details
    public function getTeams() {
        $sql = "SELECT t.id, t.name, t.mascot, t.picture, t.homecolor, t.awaycolor, t.maxplayers,
                       s.name AS sport_name, l.name AS league_name, se.year AS season_year
                FROM {$this->table} t
                JOIN sports s ON t.sport_id = s.id
                JOIN leagues l ON t.league_id = l.id
                JOIN seasons se ON t.season_id = se.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get players for a specific team
    public function getPlayersByTeam($teamId) {
        $sql = "SELECT p.id, p.firstname, p.lastname, p.jerseynumber, 
                       IFNULL(pos.name, 'No position assigned') AS position
                FROM players p
                LEFT JOIN player_positions pp ON p.id = pp.player_id
                LEFT JOIN positions pos ON pp.position_id = pos.id
                WHERE p.team_id = :team_id";  // This filters by team_id
        
        $stmt = $this->db->query($sql, ['team_id' => $teamId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
