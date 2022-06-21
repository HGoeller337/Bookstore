<!-- Logout by James Hyun -->

<?php
// Set up database object
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "cs4300project";
$mysqli     = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

$success = false;
$userID  = NULL; // will be set later

// Fetch user credentials
$result = $mysqli->query('
    SELECT id, session_id
    FROM users
    WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
');

while ($user = $result->fetch_assoc()) {
    $success = true;
    $userID  = $user['id'];
}

// End session
function closeSession() {
    // End session
    session_write_close();
    
    // Write session id to database
    global $mysqli, $userID;
    $setResult = $mysqli->query('
        UPDATE users
        SET session_id = NULL
        WHERE id = ' . $userID . '
    ');
}

?>

<!-- HTML -->
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Logout</title>
    <!--#include virtual="head.php"-->
</head>

<body>
    <!--#include virtual="header.php"-->
    <main>
        <?php
        if ($success) {
            closeSession();
        ?>
        <p>You have successfully logged out of your account.</p>
        <a href=".">Return to home page</a>
        <?php
        } else {
        ?>
        <p>You were not signed in!</p>
        <a href=".">Return to home page</a>
        <?php } ?>
    </main>
</body>

</html>