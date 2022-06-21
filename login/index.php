<!-- Login Page by James Hyun -->

<!-- Check if already logged in -->
<?php
// Set up database object
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "cs4300project";
$mysqli     = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

$loggedIn = false;

// Fetch user credentials
$result = $mysqli->query('
    SELECT session_id
    FROM users
    WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
');
$resultUser = $result->fetch_assoc();
$loggedIn   = !($resultUser == null);
?>

<!-- HTML -->
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Login</title>
    <!--#include virtual="../head.php"-->
    <?php if ($loggedIn) { ?>
    <meta http-equiv="refresh" content="5; url='..'" />
    <?php } else { ?>
    <link rel="stylesheet" href="./loginFormStyle.css" type="text/css"/>
    <script src="login.js"></script>
    <?php } ?>
</head>

<body>
    <!--#include virtual="../header.php"-->
    <main>
        <h1>Sign In</h1>
        
        <?php // If not logged in, show login page
        if (!($loggedIn)) {
        ?>
        <form id="loginForm" method="post" action="login.php">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email"/>
            <span id="emailNote">*</span>
            
            <label for="pass">Password:</label>
            <input type="password" id="pass" name="pass"/>
            <span id="passNote">*</span>
            
            <input id="submitBtn" type="submit" value="Login"/>
            <a id="registerBtn" href="../register"><button type="button">Register</button></a>
        </form>
        
        <?php // If logged in, redirect back to home
        } else {
        ?>
        <p>You are already logged in!</p>
        <p>Redirecting you to the homepage in 5 seconds...</p>
        <p>Click <a href="..">here</a> to go now or if you are not redirected.</p>
        <?php } ?>
    </main>
</body>

</html>