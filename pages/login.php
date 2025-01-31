<?php
session_start();
require_once '../classes/User.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Attempt to log in
    if ($user->login($username, $password)) {
        // Debug: Print session variables
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';

        // Redirect based on user role
        if ($user->getRole() === 'admin') {
            header('Location: ../pages/admin.php');
        } else {
            header('Location: ../index.php'); // Redirect for other roles
        }
        exit();
    } else {
        $error = 'Invalid username or password'; // Error message for failed login
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <form method="post" action="">
        <h2>Login</h2>
        <?php if (isset($error)) { echo '<p>' . htmlspecialchars($error) . '</p>'; } ?>
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
