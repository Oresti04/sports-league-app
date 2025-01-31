<?php
session_start();
require_once '../classes/Team.php';
require_once '../templates/header.php';

$team = new Team();
$teams = $team->getTeams(); // Fetch all teams with details

echo '<h1>Teams</h1>';

if (count($teams) > 0) {
    foreach ($teams as $teamData) {
        echo '<div class="team-info">';
        echo '<h2>' . htmlspecialchars($teamData['name']) . '</h2>';
        echo '<p><strong>Mascot:</strong> ' . htmlspecialchars($teamData['mascot']) . '</p>';
        echo '<p><strong>Sport:</strong> ' . htmlspecialchars($teamData['sport_name']) . '</p>';
        echo '<p><strong>League:</strong> ' . htmlspecialchars($teamData['league_name']) . '</p>';
        echo '<p><strong>Season:</strong> ' . htmlspecialchars($teamData['season_year']) . '</p>';
        echo '<p><strong>Colors:</strong> Home (' . htmlspecialchars($teamData['homecolor']) . '), Away (' . htmlspecialchars($teamData['awaycolor']) . ')</p>';

        // Fetch and display players for the team
        $players = $team->getPlayersByTeam($teamData['id']);
        if (count($players) > 0) {
            echo '<h3>Players</h3>';
            echo '<ul>';
            foreach ($players as $player) {
                echo '<li>' . htmlspecialchars($player['firstname']) . ' ' . htmlspecialchars($player['lastname']) .
                     ' (#' . htmlspecialchars($player['jerseynumber']) . ') - ' . htmlspecialchars($player['position']) . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No players available for this team.</p>';
        }

        echo '</div><hr>';
    }
} else {
    echo '<p>No teams available.</p>';
}

require_once '../templates/footer.php';
?>
