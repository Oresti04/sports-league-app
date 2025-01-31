<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Database.php';
require_once '../classes/Team.php';
require_once '../templates/header.php';

$user = new User();
$role = $user->getRole(); 
$db = new Database(); 
$conn = $db->getConnection();

// ============================ ADMIN ROLE ============================
if ($role === 'admin') {
    echo '<h1>Admin Page</h1>';

    // Fetch all users from the database
    $sql_users = "SELECT u.id, u.username, r.name FROM users u JOIN roles r ON u.role_id = r.id";
    $users_result = $db->query($sql_users);

    // Fetch all sports from the database
    $sql_sports = "SELECT id, name FROM sports";
    $sports_result = $db->query($sql_sports);

        // Add New User Form
        echo '<h3>Add New User</h3>';
        echo '<form id="add-user-form" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
                <label for="role_id">Role:</label>
                <select name="role_id" required>
                    <option value="1">Admin</option>
                    <option value="2">League Manager</option>
                    <option value="3">Team Manager</option>
                    <option value="4">Coach</option>
                    <option value="5">Parent</option>
                </select>
                <button type="submit">Add User</button>
            </form>
            <div id="user-response"></div>';

    echo '<hr>';

    // Display Users
    echo '<h2>Manage Users</h2>';
    $users = $db->query("SELECT u.id, u.username, r.name AS role FROM users u JOIN roles r ON u.role_id = r.id")->fetchAll(PDO::FETCH_ASSOC);
    if (count($users) > 0) {
        echo '<ul>';
        foreach ($users as $userItem) {
            echo '<li>' . htmlspecialchars($userItem['username']) . ' - ' . htmlspecialchars($userItem['role']) . 
                ' <a href="javascript:void(0);" class="delete-user" data-id="' . $userItem['id'] . '">Delete</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No users found.</p>';
    }

    echo '<hr>';

    // Add New Sport Form
    echo '<h3>Add New Sport</h3>';
    echo '<form id="add-sport-form" method="POST">
            <label for="sport_name">Sport Name:</label>
            <input type="text" name="sport_name" required>
            <button type="submit">Add Sport</button>
        </form>
        <div id="sport-response"></div>';

    echo '<hr>';

    // Manage Sports Section
    $sports = $db->query("SELECT id, name FROM sports")->fetchAll(PDO::FETCH_ASSOC);
    echo '<h2>Manage Sports</h2>';

    if (count($sports) > 0) {
        echo '<ul>';
        foreach ($sports as $sport) {
            echo '<li>' . htmlspecialchars($sport['name']) . ' 
                <button class="edit-sport" data-id="' . $sport['id'] . '">Edit</button>
                <button class="delete-sport" data-id="' . $sport['id'] . '">Delete</button>
                </li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No sports available.</p>';
    }
}

// ============================ LEAGUE MANAGER ROLE ============================
if ($role === 'league_manager' || $role === 'admin') {
    echo '<h1>League Manager Functionality</h1>';

    // ================== Manage Seasons (Add, Edit, Delete) ===================
    echo '<h3>Manage Seasons</h3>';

    // Form to add a new season
    echo '<form id="add-season-form">
            <label for="season_year">Season Year:</label>
            <input type="number" name="season_year" min="1900" required>
            <label for="description">Description (e.g., Spring 2024):</label>
            <input type="text" name="description" required> 
            <button type="submit">Add Season</button>
        </form>
        <div id="response"></div>';

    // Display and manage existing seasons
    $seasons = $db->query("SELECT id, year, description FROM seasons")->fetchAll(PDO::FETCH_ASSOC);
    echo '<h2>Seasons</h2>';
    if (count($seasons) > 0) {
        echo '<ul>';
        foreach ($seasons as $season) {
            echo '<li>' . htmlspecialchars($season['year']) . ' (' . htmlspecialchars($season['description']) . ') 
                <a href="javascript:void(0);" class="edit-season" data-id="' . $season['id'] . '">Edit</a>
                <a href="javascript:void(0);" class="delete-season" data-id="' . $season['id'] . '">Delete</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No seasons available.</p>';
    }

    // ================== Manage Teams (Sport, League, Season Combination) ===================
    echo '<h3>Manage Teams</h3>';

    // Fetch sports, leagues, and seasons for dropdowns
    $sports = $db->query("SELECT id, name FROM sports")->fetchAll(PDO::FETCH_ASSOC);
    $leagues = $db->query("SELECT id, name FROM leagues")->fetchAll(PDO::FETCH_ASSOC);
    $seasons = $db->query("SELECT id, description FROM seasons")->fetchAll(PDO::FETCH_ASSOC);

    // Form to add a new team
    echo '<form id="add-team-form">
            <label for="team_name">Team Name:</label>
            <input type="text" name="team_name" required>
            <label for="mascot">Mascot:</label>
            <input type="text" name="mascot" required>';

    // Dropdown for sports
    echo '<label for="sport_id">Sport:</label>';
    echo '<select name="sport_id" required>';
    foreach ($sports as $sport) {
        echo '<option value="' . htmlspecialchars($sport['id']) . '">' . htmlspecialchars($sport['name']) . '</option>';
    }
    echo '</select>';

    // Dropdown for leagues
    echo '<label for="league_id">League:</label>';
    echo '<select name="league_id" required>';
    foreach ($leagues as $league) {
        echo '<option value="' . htmlspecialchars($league['id']) . '">' . htmlspecialchars($league['name']) . '</option>';
    }
    echo '</select>';

    // Dropdown for seasons
    echo '<label for="season_id">Season:</label>';
    echo '<select name="season_id" required>';
    foreach ($seasons as $season) {
        echo '<option value="' . htmlspecialchars($season['id']) . '">' . htmlspecialchars($season['description']) . '</option>';
    }
    echo '</select>';

    // Colors Dropdown (Limited to 10 predefined colors)
    $colors = ['Red', 'Blue', 'Green', 'Yellow', 'Black', 'White', 'Purple', 'Orange', 'Gray', 'Pink'];
    echo '<label for="homecolor">Home Color:</label>';
    echo '<select name="homecolor" required>';
    foreach ($colors as $color) {
        echo '<option value="' . htmlspecialchars($color) . '">' . htmlspecialchars($color) . '</option>';
    }
    echo '</select>';

    echo '<label for="awaycolor">Away Color:</label>';
    echo '<select name="awaycolor" required>';
    foreach ($colors as $color) {
        echo '<option value="' . htmlspecialchars($color) . '">' . htmlspecialchars($color) . '</option>';
    }
    echo '</select>';

    // Maximum Players Dropdown (up to 20 players)
    echo '<label for="maxplayers">Max Players:</label>';
    echo '<select name="maxplayers" required>';
    for ($i = 1; $i <= 20; $i++) {
        echo '<option value="' . $i . '">' . $i . '</option>';
    }
    echo '</select>';

    echo '<button type="submit">Add Team</button>
        </form>
        <div id="response"></div>';

    // Display all teams for the league manager
    $teams = $db->query("
        SELECT t.id, t.name AS team_name, s.name AS sport_name, l.name AS league_name, se.description AS season_desc
        FROM teams t
        JOIN sports s ON t.sport_id = s.id
        JOIN leagues l ON t.league_id = l.id
        JOIN seasons se ON t.season_id = se.id")->fetchAll(PDO::FETCH_ASSOC);

    echo '<h2>Teams</h2>';
    if (count($teams) > 0) {
        echo '<ul>';
        foreach ($teams as $team) {
            echo '<li>' . htmlspecialchars($team['team_name']) . ' - ' . htmlspecialchars($team['sport_name']) . ' - ' .
                 htmlspecialchars($team['league_name']) . ' - ' . htmlspecialchars($team['season_desc']) . '
                 <a href="javascript:void(0);" class="edit-team" data-id="' . $team['id'] . '">Edit</a>
                 <a href="javascript:void(0);" class="delete-team" data-id="' . $team['id'] . '">Delete</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No teams available.</p>';
    }

    // =================== Team Edit Form ===================
        echo '<div id="edit-team-popup" style="display:none;">';
        echo '<form id="edit-team-form" method="POST" action="admin_handler.php">';
        echo '<input type="hidden" name="edit_team_id" id="edit_team_id">';

        echo '<label for="team_name">Team Name:</label>';
        echo '<input type="text" name="team_name" id="team_name" required>';

        echo '<label for="sport_id">Sport:</label>';
        echo '<select name="sport_id" id="sport_id">';

        // Fetch sports options from the database
        $sports = $db->query("SELECT id, name FROM sports")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($sports as $sport) {
            echo "<option value='{$sport['id']}'>{$sport['name']}</option>";
        }
        echo '</select>';

        echo '<label for="league_id">League:</label>';
        echo '<select name="league_id" id="league_id">';

        // Fetch leagues options from the database
        $leagues = $db->query("SELECT id, name FROM leagues")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($leagues as $league) {
            echo "<option value='{$league['id']}'>{$league['name']}</option>";
        }
        echo '</select>';

        echo '<label for="season_id">Season:</label>';
        echo '<select name="season_id" id="season_id">';

        // Fetch seasons options from the database
        $seasons = $db->query("SELECT id, description FROM seasons")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($seasons as $season) {
            echo "<option value='{$season['id']}'>{$season['description']}</option>";
        }
        echo '</select>';

        echo '<button type="submit">Update Team</button>';
        echo '</form>';
        echo '</div>';



    // ================== Manage Games (Add/Edit/Delete) ===================
    echo '<h3>Manage Games</h3>';
    echo '<form id="add-game-form">
            <label for="hometeam_id">Home Team:</label>';
    echo '<select name="hometeam_id" required>';
    foreach ($teams as $team) {
        echo '<option value="' . htmlspecialchars($team['id']) . '">' . htmlspecialchars($team['team_name']) . '</option>';
    }
    echo '</select>';

    echo '<label for="awayteam_id">Away Team:</label>';
    echo '<select name="awayteam_id" required>';
    foreach ($teams as $team) {
        echo '<option value="' . htmlspecialchars($team['id']) . '">' . htmlspecialchars($team['team_name']) . '</option>';
    }
    echo '</select>';

    echo '<label for="scheduled">Scheduled Date:</label>';
    echo '<input type="datetime-local" name="scheduled" required>';

    echo '<label for="homescore">Home Team Score:</label>';
    echo '<input type="number" name="homescore" min="0">';

    echo '<label for="awayscore">Away Team Score:</label>';
    echo '<input type="number" name="awayscore" min="0">';

    echo '<label for="completed">Completed:</label>';
    echo '<input type="checkbox" name="completed" value="1">';

    echo '<button type="submit">Add Game</button>';
    echo '</form>';

    // Display all games
    $games = $db->query("
        SELECT g.id, g.homescore, g.awayscore, g.scheduled, g.completed, 
               ht.name AS home_team, at.name AS away_team 
        FROM schedule g
        JOIN teams ht ON g.hometeam_id = ht.id
        JOIN teams at ON g.awayteam_id = at.id
        ORDER BY g.scheduled DESC")->fetchAll(PDO::FETCH_ASSOC);

    echo '<h2>Games</h2>';
    if (count($games) > 0) {
        echo '<ul>';
        foreach ($games as $game) {
            echo '<li>' . htmlspecialchars($game['home_team']) . ' vs. ' . htmlspecialchars($game['away_team']) . 
                 ' on ' . htmlspecialchars($game['scheduled']) . ' - Score: ' . htmlspecialchars($game['homescore']) . ' - ' . 
                 htmlspecialchars($game['awayscore']) . ' - ' . ($game['completed'] ? 'Completed' : 'Scheduled') . '
                 <a href="javascript:void(0);" class="edit-game" data-id="' . $game['id'] . '">Edit</a>
                 <a href="javascript:void(0);" class="delete-game" data-id="' . $game['id'] . '">Delete</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No games available.</p>';
    }
}


// ============================ COACH/TEAM MANAGER ROLE ============================
if ($role === 'coach' || $role === 'team_manager' || $role === 'admin') {
    echo '<h1>Coach/Team Manager Functionality</h1>';

    // Fetch players in their team
    $teamId = $user->getTeamId(); // Get the team ID of the coach/team manager
    $players = $db->query("
    SELECT p.id, p.firstname, p.lastname, p.jerseynumber, 
           pos.name AS position
    FROM players p
    LEFT JOIN player_positions pp ON p.id = pp.player_id
    LEFT JOIN positions pos ON pp.position_id = pos.id
    WHERE p.team_id = :team_id
", ['team_id' => $teamId])->fetchAll(PDO::FETCH_ASSOC);

    // Display players with positions
    if (count($players) > 0) {
        echo '<ul>';
        foreach ($players as $player) {
            // Display player info with a delete button
            echo '<li>' . htmlspecialchars($player['firstname']) . ' ' . htmlspecialchars($player['lastname']) . 
                 ' (#' . htmlspecialchars($player['jerseynumber']) . ') - Position: ' . htmlspecialchars($player['position'] ?? 'Unassigned') . 
                 ' <form method="POST" style="display:inline;">
                    <input type="hidden" name="player_id" value="' . $player['id'] . '">
                    <button type="submit" name="delete_player">Delete</button>
                 </form>
                 </li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No players available in your team.</p>';
    }
    
    // Handle player deletion
    if (isset($_POST['delete_player'])) {
        if (isset($_POST['player_id'])) {
            $player_id = $_POST['player_id'];
    
            // First, delete the player from the player_positions table
            $sql = "DELETE FROM player_positions WHERE player_id = :player_id";
            $params = ['player_id' => $player_id];
            $result = $db->query($sql, $params);
    
            if ($result) {
                // Now, delete the player from the players table
                $sql = "DELETE FROM players WHERE id = :player_id";
                $params = ['player_id' => $player_id];
                $result = $db->query($sql, $params);
    
                if ($result) {
                    echo "Player deleted successfully.";
                } else {
                    echo "Error deleting player from the players table.";
                }
            } else {
                echo "Error deleting player from player_positions.";
            }
        } else {
            echo "No player selected for deletion.";
        }
    }
    

    // Fetch positions from the database
    $positions = $db->query("SELECT id, name FROM positions")->fetchAll(PDO::FETCH_ASSOC);

    // Form to add a new player
    echo '<h3>Add New Player</h3>';
    echo '<form id="add-player-form" method="POST" action="admin_handler.php">
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" required>

            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" required>

            <label for="dateofbirth">Date of Birth:</label>
            <input type="date" name="dateofbirth" required>

            <label for="jerseynumber">Jersey Number:</label>
            <input type="number" name="jerseynumber" min="0" required>

            <label for="position_id">Position:</label>
            <select name="position_id" required>';
            
            // Populate the dropdown with position names and corresponding IDs
            foreach ($positions as $position) {
                echo '<option value="' . $position['id'] . '">' . $position['name'] . '</option>';
            }

    echo '  </select>
            <button type="submit">Add Player</button>
        </form>
        <div id="response"></div>';
}

// ============================ PARENT ROLE ============================
if ($role === 'parent') {
    // Fetch the parent’s team
    $userId = $user->getId();
    $teamId = $user->getTeamId();  // Assuming you have team_id stored in user session or database

    if ($teamId) {
        // Fetch team details
        $team = $db->query("SELECT * FROM teams WHERE id = :team_id", ['team_id' => $teamId])->fetch(PDO::FETCH_ASSOC);
        
        // Fetch games scheduled for the team
        $games = $db->query("SELECT g.id, g.scheduled, g.completed, ht.name AS home_team, at.name AS away_team 
                             FROM schedule g
                             JOIN teams ht ON g.hometeam_id = ht.id
                             JOIN teams at ON g.awayteam_id = at.id
                             WHERE g.hometeam_id = :team_id OR g.awayteam_id = :team_id", 
                             ['team_id' => $teamId])->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<h1>Your Team: ' . htmlspecialchars($team['name']) . '</h1>';
        
        // Display games for this team
        echo '<h2>Games Scheduled for Your Team</h2>';
        if (count($games) > 0) {
            echo '<ul>';
            foreach ($games as $game) {
                $status = $game['completed'] ? 'Completed' : 'Scheduled';
                echo '<li>' . htmlspecialchars($game['home_team']) . ' vs. ' . htmlspecialchars($game['away_team']) . 
                     ' on ' . htmlspecialchars($game['scheduled']) . ' - Status: ' . $status . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No games scheduled for your team.</p>';
        }
    } else {
        echo '<p>Team not assigned to this parent.</p>';
    }
}

require_once '../templates/footer.php';

?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {

     // AJAX to add new user
        $('#add-user-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'admin_handler.php',
                type: 'POST',
                data: $(this).serialize() + '&action=add_user',
                success: function(response) {
                    $('#user-response').html(response);
                    loadUsers(); // Reload the users list after adding a new user
                }
            });
        });

        // AJAX to delete user
        $(document).on("click", ".delete-user", function() {
            var userId = $(this).data("id");
            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    url: 'admin_handler.php',
                    type: 'POST',
                    data: { delete_user_id: userId },
                    success: function(response) {
                        alert(response);
                        location.reload(); // Reload page after deletion
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        });

        // AJAX to add new sport
        $('#add-sport-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'admin_handler.php',
                type: 'POST',
                data: $(this).serialize() + '&action=add_sport',
                success: function(response) {
                    $('#sport-response').html(response);
                    loadSports(); // Reload the sports list after adding a new sport
                }
            });
        });

        //DELETE SPORT
        $(document).on("click", ".delete-sport", function() {
            var sportId = $(this).data("id");
            if (confirm("Are you sure you want to delete this sport?")) {
                $.ajax({
                    url: 'admin_handler.php',
                    type: 'POST',
                    data: { delete_sport_id: sportId },
                    success: function(response) {
                        alert(response);
                        location.reload(); // Reload page after deletion
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        });

        //EDIT SPORT
        $(document).on("click", ".edit-sport", function() {
            var sportId = $(this).data("id");

            // Prompt the user to edit the sport name
            var newSportName = prompt("Enter new sport name:");
            if (newSportName) {
                $.ajax({
                    url: 'admin_handler.php',
                    type: 'POST',
                    data: { edit_sport_id: sportId, new_sport_name: newSportName },
                    success: function(response) {
                        alert(response);
                        location.reload(); // Reload page after update
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        });

        // Add Season via AJAX
        $("#add-season-form").on("submit", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'admin_handler.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#response").html(response);
                    $("#add-season-form")[0].reset();
                    location.reload(); // Reload to show the newly added season
                },
                error: function(xhr, status, error) {
                    $("#response").html('<p>Error: ' + error + '</p>');
                }
            });
        });

        // Add Team via AJAX
        $("#add-team-form").on("submit", function(e) {
            e.preventDefault(); 
            var formData = $(this).serialize(); 
            
            $.ajax({
                url: 'admin_handler.php', 
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#response").html(response); 
                    $("#add-team-form")[0].reset(); 
                    loadTeams(); 
                },
                error: function(xhr, status, error) {
                    $("#response").html('<p>Error: ' + error + '</p>');
                }
            });
        });

        // Function to reload teams list
        function loadTeams() {
            $.ajax({
                url: 'admin_handler.php',
                type: 'GET',
                data: { action: 'get_teams' },
                success: function(response) {
                    $("#team-list").html(response); // Update team list
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        }

        // Add Game via AJAX
        $("#add-game-form").on("submit", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'admin_handler.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $("#response").html(response);
                    $("#add-game-form")[0].reset();
                    location.reload(); // Reload to show the newly added game
                },
                error: function(xhr, status, error) {
                    $("#response").html('<p>Error: ' + error + '</p>');
                }
            });
        });

        // Event delegation for Edit and Delete buttons on Seasons, Teams, and Games
        $(document).on("click", ".edit-season", function() {
            var seasonId = $(this).data("id");
            var newYear = prompt("Enter a new year for the season:");
            var newDescription = prompt("Enter new description for the season:");

            if (newYear && newDescription) {
                $.ajax({
                    url: 'admin_handler.php', // Handler for editing season
                    type: 'POST',
                    data: {
                        edit_season_id: seasonId,
                        new_year: newYear,
                        new_description: newDescription
                    },
                    success: function(response) {
                        $("#response").html(response); // Display response
                        location.reload(); // Refresh the page
                    },
                    error: function(xhr, status, error) {
                        $("#response").html('<p>Error: ' + error + '</p>');
                    }
                });
            }
        });

        // Handle edit button click for teams
        document.querySelectorAll('.edit-team').forEach(button => {
            button.addEventListener('click', function () {
                var teamId = this.getAttribute('data-id');
                // Set team ID in the hidden input
                document.getElementById('edit_team_id').value = teamId;
                document.getElementById('edit-team-popup').style.display = 'block';
            });
        });

        $(document).on("click", ".delete-season", function() {
            var seasonId = $(this).data("id");
            if (confirm("Are you sure you want to delete this season?")) {
                $.ajax({
                    url: 'admin_handler.php',
                    type: 'POST',
                    data: { delete_season_id: seasonId },
                    success: function(response) {
                        alert(response);
                        location.reload(); // Reload page after deletion
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        });       


        $(document).on("click", ".delete-team", function() {
            var teamId = $(this).data("id");
            if (confirm("Are you sure you want to delete this team?")) {
                $.ajax({
                    url: 'admin_handler.php',
                    type: 'POST',
                    data: { delete_team_id: teamId },
                    success: function(response) {
                        alert(response);
                        location.reload(); // Reload page after deletion
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        });

        document.getElementById('add-player-form').addEventListener('submit', function(event) {
            event.preventDefault(); 

            const formData = new FormData(this);

            fetch('admin_handler.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('response').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        

        $(document).on("click", ".edit-game", function() {
            var gameId = $(this).data("id");
            // Prompt for new score, scheduled time, etc.
            var newHomescore = prompt("Enter new home score:");
            var newAwayscore = prompt("Enter new away score:");
            var newScheduled = prompt("Enter new scheduled date (YYYY-MM-DD HH:MM):");

            if (newHomescore && newAwayscore && newScheduled) {
                $.ajax({
                    url: 'admin_handler.php',
                    type: 'POST',
                    data: {
                        edit_game_id: gameId,
                        homescore: newHomescore,
                        awayscore: newAwayscore,
                        scheduled: newScheduled
                    },
                    success: function(response) {
                        $("#response").html(response); // Display response
                        location.reload(); // Refresh the page
                    },
                    error: function(xhr, status, error) {
                        $("#response").html('<p>Error: ' + error + '</p>');
                    }
                });
            }
        });

        //Delete player AJAX
        document.querySelectorAll('.delete-player').forEach(function(button) {
            button.addEventListener('click', function() {
                var playerId = this.getAttribute('data-player-id');
                var listItem = document.getElementById('player-' + playerId); 

                // Send AJAX request to delete the player
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'admin_handler.php', true); 
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = xhr.responseText.trim();
                        if (response === 'success') {
                            // If deletion is successful, remove the player from the list
                            listItem.remove();
                        } else {
                            alert('Error deleting player: ' + response);
                        }
                    } else {
                        alert('Request failed. Please try again.');
                    }
                };
                xhr.send('delete_player=1&player_id=' + playerId); // Send data to PHP handler
            });
        });

        //Delete game AJAX
        $(document).on("click", ".delete-game", function() {
            var gameId = $(this).data("id");
            if (confirm("Are you sure you want to delete this game?")) {
                $.ajax({
                    url: 'admin_handler.php',
                    type: 'POST',
                    data: { delete_game_id: gameId },
                    success: function(response) {
                        alert(response);
                        location.reload(); // Reload page after deletion
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        });
    });
</script>
