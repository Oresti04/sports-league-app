<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';

$db = new Database();
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Add user
    if (isset($_POST['action']) && $_POST['action'] == 'add_user') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
        $role_id = $_POST['role_id'];

        $sql = "INSERT INTO users (username, password, role_id) VALUES (:username, :password, :role_id)";
        $params = ['username' => $username, 'password' => $password, 'role_id' => $role_id];
        
        if ($db->query($sql, $params)) {
            echo "User added successfully!";
        } else {
            echo "Error adding user.";
        }
        exit;
    }

    // Delete user
    if (isset($_POST['delete_user_id'])) {
        $user_id = $_POST['delete_user_id'];

        $sql = "DELETE FROM users WHERE id = :user_id";
        $params = ['user_id' => $user_id];

        if ($db->query($sql, $params)) {
            echo "User deleted successfully!";
        } else {
            echo "Error deleting user.";
        }
    }

    // Add sport
    if (isset($_POST['action']) && $_POST['action'] == 'add_sport') {
        $sport_name = $_POST['sport_name'];

        $sql = "INSERT INTO sports (name) VALUES (:sport_name)";
        $params = ['sport_name' => $sport_name];

        if ($db->query($sql, $params)) {
            echo "Sport added successfully!";
        } else {
            echo "Error adding sport.";
        }
        exit;
    }

    // Delete sport
    if (isset($_POST['delete_sport_id'])) {
        $sport_id = $_POST['delete_sport_id'];

        $sql = "DELETE FROM sports WHERE id = :sport_id";
        $params = ['sport_id' => $sport_id];

        if ($db->query($sql, $params)) {
            echo "Sport deleted successfully!";
        } else {
            echo "Error deleting sport.";
        }
    }

    // Edit sport
    if (isset($_POST['edit_sport_id']) && isset($_POST['new_sport_name'])) {
        $sport_id = $_POST['edit_sport_id'];
        $new_sport_name = $_POST['new_sport_name'];

        $sql = "UPDATE sports SET name = :new_sport_name WHERE id = :sport_id";
        $params = ['new_sport_name' => $new_sport_name, 'sport_id' => $sport_id];

        if ($db->query($sql, $params)) {
            echo "Sport updated successfully!";
        } else {
            echo "Error updating sport.";
        }
    }


    // Add Season 
    if (isset($_POST['season_year'], $_POST['description'])) {
        $seasonYear = $_POST['season_year'];
        $description = $_POST['description'];

        $sql = "INSERT INTO seasons (year, description) VALUES (:year, :description)";
        $stmt = $db->query($sql, [
            'year' => $seasonYear,
            'description' => $description
        ]);

        if ($stmt) {
            echo 'Season added successfully.';
        } else {
            echo 'Error adding season.';
        }
    }

    // Add Team 
    if (isset($_POST['team_name'], $_POST['mascot'], $_POST['sport_id'], $_POST['league_id'], $_POST['season_id'], $_POST['homecolor'], $_POST['awaycolor'], $_POST['maxplayers'])) {
        $teamName = $_POST['team_name'];
        $mascot = $_POST['mascot'];
        $sportId = $_POST['sport_id'];
        $leagueId = $_POST['league_id'];
        $seasonId = $_POST['season_id'];
        $homecolor = $_POST['homecolor'];
        $awaycolor = $_POST['awaycolor'];
        $maxplayers = $_POST['maxplayers'];

        $sql = "INSERT INTO teams (name, mascot, sport_id, league_id, season_id, homecolor, awaycolor, maxplayers) 
                VALUES (:name, :mascot, :sport_id, :league_id, :season_id, :homecolor, :awaycolor, :maxplayers)";
        
        $stmt = $db->query($sql, [
            'name' => $teamName,
            'mascot' => $mascot,
            'sport_id' => $sportId,
            'league_id' => $leagueId,
            'season_id' => $seasonId,
            'homecolor' => $homecolor,
            'awaycolor' => $awaycolor,
            'maxplayers' => $maxplayers
        ]);

        if ($stmt) {
            echo 'Team "' . htmlspecialchars($teamName) . '" added successfully.';
        } else {
            echo 'Error adding team.';
        }
    }

    // Add Game
    if (isset($_POST['hometeam_id'], $_POST['awayteam_id'], $_POST['scheduled'], $_POST['homescore'], $_POST['awayscore'], $_POST['completed'])) {
        $homeTeamId = $_POST['hometeam_id'];
        $awayTeamId = $_POST['awayteam_id'];
        $scheduled = $_POST['scheduled'];
        $homescore = $_POST['homescore'];
        $awayscore = $_POST['awayscore'];
        $completed = $_POST['completed'];

        $sql = "INSERT INTO schedule (hometeam_id, awayteam_id, scheduled, homescore, awayscore, completed) 
                VALUES (:hometeam_id, :awayteam_id, :scheduled, :homescore, :awayscore, :completed)";

        $stmt = $db->query($sql, [
            'hometeam_id' => $homeTeamId,
            'awayteam_id' => $awayTeamId,
            'scheduled' => $scheduled,
            'homescore' => $homescore,
            'awayscore' => $awayscore,
            'completed' => $completed
        ]);

        if ($stmt) {
            echo 'Game added successfully.';
        } else {
            echo 'Error adding game.';
        }
    }

    // Handle Seasons - Edit and Delete
    if (isset($_POST['edit_season_id'], $_POST['new_description'])) {
        $seasonId = $_POST['edit_season_id'];
        $newYear = $_POST['new_year'];
        $newDescription = $_POST['new_description'];

        $sql = "UPDATE seasons SET year = :year, description = :description WHERE id = :id";
        $stmt = $db->query($sql, [
            'year'=> $newYear,
            'description' => $newDescription,
            'id' => $seasonId
        ]);

        if ($stmt) {
            echo 'Season updated successfully.';
        } else {
            echo 'Error updating season.';
        }
    }

    // Handle Team Update 
    if (isset($_POST['edit_team_id'])) {
        $teamId = $_POST['edit_team_id'];
        $teamName = $_POST['team_name'];
        $sportId = $_POST['sport_id'];
        $leagueId = $_POST['league_id'];
        $seasonId = $_POST['season_id'];

        $sql = "UPDATE teams SET name = :name, sport_id = :sport_id, league_id = :league_id, season_id = :season_id WHERE id = :id";
        
        // Using PDO prepare and execute methods
        $stmt = $db->query($sql, [
            ':name' => $teamName,
            ':sport_id' => $sportId,
            ':league_id' => $leagueId,
            ':season_id' => $seasonId,
            ':id' => $teamId
        ]);

        if ($stmt) {
            echo "Team updated successfully.";
        } else {
            echo "Error updating team.";
        }
    }


    if (isset($_POST['delete_season_id'])) {
        $seasonId = $_POST['delete_season_id'];

        // Delete the season
        $sql = "DELETE FROM seasons WHERE id = :id";
        $stmt = $db->query($sql, ['id' => $seasonId]);

        if ($stmt) {
            echo 'Season deleted successfully.';
        } else {
            echo 'Error deleting season.';
        }
    }

    //return updated teams list
    if (isset($_GET['action']) && $_GET['action'] == 'get_teams') {
        $teams = $db->query("SELECT t.id, t.name AS team_name, s.name AS sport_name, l.name AS league_name, se.description AS season_desc
                             FROM teams t
                             JOIN sports s ON t.sport_id = s.id
                             JOIN leagues l ON t.league_id = l.id
                             JOIN seasons se ON t.season_id = se.id")->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($teams as $team) {
            echo '<li>' . htmlspecialchars($team['team_name']) . ' - ' . htmlspecialchars($team['sport_name']) . ' - ' . 
                 htmlspecialchars($team['league_name']) . ' - ' . htmlspecialchars($team['season_desc']) . '
                 <a href="javascript:void(0);" class="edit-team" data-id="' . $team['id'] . '">Edit</a>
                 <a href="javascript:void(0);" class="delete-team" data-id="' . $team['id'] . '">Delete</a></li>';
        }
    }
    

    // Delete Team 
    if (isset($_POST['delete_team_id'])) {
        $teamId = $_POST['delete_team_id'];

        $sql = "DELETE FROM teams WHERE id = :id";
        $stmt = $db->query($sql, ['id' => $teamId]);

        if ($stmt) {
            echo 'Team deleted successfully.';
        } else {
            echo 'Error deleting team.';
        }
    }

    // Handle Editing Games
    if (isset($_POST['edit_game_id'], $_POST['homescore'], $_POST['awayscore'], $_POST['scheduled'])) {
        $gameId = $_POST['edit_game_id'];
        $homescore = $_POST['homescore'];
        $awayscore = $_POST['awayscore'];
        $scheduled = $_POST['scheduled'];

        $sql = "UPDATE schedule SET homescore = :homescore, awayscore = :awayscore, scheduled = :scheduled 
                WHERE id = :id";
        $stmt = $db->query($sql, [
            'homescore' => $homescore,
            'awayscore' => $awayscore,
            'scheduled' => $scheduled,
            'id' => $gameId
        ]);

        if ($stmt) {
            echo 'Game updated successfully.';
        } else {
            echo 'Error updating game.';
        }
    }

    // Handle Deleting Games
    if (isset($_POST['delete_game_id'])) {
        $gameId = $_POST['delete_game_id'];

        $sql = "DELETE FROM schedule WHERE id = :id";
        $stmt = $db->query($sql, ['id' => $gameId]);

        if ($stmt) {
            echo 'Game deleted successfully.';
        } else {
            echo 'Error deleting game.';
        }
    }

    if (isset($_POST['delete_player']) && isset($_POST['player_id'])) {
        $player_id = $_POST['player_id'];
    
        $sql = "DELETE FROM player_positions WHERE player_id = :player_id";
        $params = ['player_id' => $player_id];
        $result = $db->query($sql, $params);
    
        if ($result) {
            $sql = "DELETE FROM players WHERE id = :player_id";
            $params = ['player_id' => $player_id];
            $result = $db->query($sql, $params);
    
            if ($result) {
                echo 'success';
            } else {
                echo 'Error deleting player from players table.';
            }
        } else {
            echo 'Error deleting player from player_positions.';
        }


    //Add new player
    $coach_manager_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session

    $sql = "SELECT team_id FROM users WHERE id = :user_id";
    $params = ['user_id' => $coach_manager_id]; 
    
    $stmt = $db->query($sql, $params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC); 
    
    if (!$result || !isset($result['team_id'])) {
        echo "Error: This coach manager doesn't have a team assigned.";
        exit; 
    }
    
    $team_id = $result['team_id']; // Assign the team_id for the player insertion
    
    // Check if form data is posted for adding a new player
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['firstname'], $_POST['lastname'], $_POST['dateofbirth'], $_POST['jerseynumber'], $_POST['position_id'])) {
            
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $dateofbirth = $_POST['dateofbirth'];
            $jerseynumber = $_POST['jerseynumber'];
            $position_id = $_POST['position_id'];
    
            $sql = "INSERT INTO players (firstname, lastname, dateofbirth, jerseynumber, team_id) 
                    VALUES (:firstname, :lastname, :dateofbirth, :jerseynumber, :team_id)";
            $params = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'dateofbirth' => $dateofbirth,
                'jerseynumber' => $jerseynumber,
                'team_id' => $team_id
            ];
            $result = $db->query($sql, $params);
    
            if ($result) {

                $player_id = $db->lastInsertId(); 
    
                // Insert the player and position relationship into the player_positions table
                $sql = "INSERT INTO player_positions (player_id, position_id) VALUES (:player_id, :position_id)";
                $params = [
                    'player_id' => $player_id,
                    'position_id' => $position_id
                ];
                $result = $db->query($sql, $params);
    
                if ($result) {
                    echo "Player added successfully!";
                } else {
                    echo "Error adding player to positions.";
                }
            } else {
                echo "Error adding player.";
            }
        }
    }
}
}
?>
