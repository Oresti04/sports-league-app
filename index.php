<?php
session_start();
require_once 'classes/User.php';
require_once 'templates/header.php';

$user = new User();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports League Application</title>
    <link rel="stylesheet" href="/style/style.css"> 
</head>
<body>

<?php
if ($user->isLoggedIn()) {
    echo '<h1>Welcome to the Sports League Application</h1>';
    echo '<p>You are logged in as ' . htmlspecialchars($user->getRole()) . '.</p>';
} else {
    echo '<h1>Please <a href="pages/login.php">login</a> to access the site.</h1>';
}
?>

<?php require_once 'templates/footer.php'; ?>

</body>
</html>
