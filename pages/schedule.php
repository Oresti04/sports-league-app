<?php
session_start();
require_once '../classes/Schedule.php';
require_once '../templates/header.php';

$schedule = new Schedule();
$games = $schedule->getGames(); // Fetch all scheduled games

echo '<h1>Game Schedule</h1>';

if (count($games) > 0) {
    echo '<table>';
    echo '<tr><th>Home Team</th><th>Away Team</th><th>Scheduled Date/Time</th><th>Status</th><th>Score</th></tr>';
    
    foreach ($games as $game) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($game['home_team_name']) . '</td>';
        echo '<td>' . htmlspecialchars($game['away_team_name']) . '</td>';
        echo '<td>' . htmlspecialchars($game['scheduled']) . '</td>';
        
        // Display whether the game is completed or still scheduled
        if ($game['completed']) {
            echo '<td>Completed</td>';
        } else {
            echo '<td>Scheduled</td>';
        }

        // Display score if the game is completed, otherwise leave blank
        if ($game['completed']) {
            echo '<td>' . htmlspecialchars($game['homescore']) . ' - ' . htmlspecialchars($game['awayscore']) . '</td>';
        } else {
            echo '<td>--</td>';
        }
        
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<p>No games scheduled.</p>';
}

require_once '../templates/footer.php';
?>
