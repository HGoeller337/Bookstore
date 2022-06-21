<!-- Login Page by James Hyun -->

<!-- Login handling -->
<?php
// Set up database object
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "cs4300project";
$mysqli     = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Login vars
$email   = filter_input(INPUT_POST, 'email');
$pass    = filter_input(INPUT_POST, 'pass');
$success = false;

// Fetch user credentials
$result = $mysqli->query('
    SELECT email, pass
    FROM users
    WHERE email = "' . $email . '"
');

// Check if user even exists + check password
$resultUser = $result->fetch_assoc();
$success    = ($resultUser != null) && ($resultUser['pass'] == $pass);

// Cookie handling (putting it here just to keep most of the complex php stuff up here)
// TODO: Use a different cookie ID for user login sessions (in case the cart needs PHPSESSID cookies)
function setSessionCookie() {
    // Start session
    session_start();
    setcookie('cart', '{}', 0, '/');
    
    // Write session id to database
    global $mysqli, $email;
    $id        = session_id();
    $setResult = $mysqli->query('
        UPDATE users
        SET session_id = "' . $id . '"
        WHERE email = "' . $email . '"
    ');
    
    // Fetch cart data
    $cartResult = $mysqli->query('
        SELECT cart
        FROM users
        WHERE email = "' . $email . '"
    ');
    $cart = $cartResult->fetch_assoc();
    if ($cart) {
        setcookie('cart', $cart['cart'], 0, '/');
    } else {
        setcookie('cart', '{}', 0, '/');
    }
    
    // Clear duplicate session ids to prevent (((identity fraud)))
    $dupesResult = $mysqli->query('
        SELECT session_id, id
        FROM users
        WHERE email != "' . $email . '" AND session_id = "' . $id . '"
    ');
    while ($user = $dupesResult->fetch_assoc()) {
        $mysqli->query('
            UPDATE users
            SET session_id = NULL
            WHERE id = ' . $user['id'] . '
        ');
    }
}

?>

<!-- HTML -->
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Login</title>
    <!--#include virtual="../head.php"-->
</head>

<body>
    <!--#include virtual="../header.php"-->
    <main>
        <h1>Sign In</h1>
        <?php
        if ($success) {
            setSessionCookie();
        ?>
        <p>You have successfully signed in!</p>
        <p><a href="..">Return to home page</a></p>
        <?php
        } else {
        ?>
        <p>The login credentials you entered were incorrect or did not belong to an existing user.</p>
        <p><a href=".">Return to login page</a></p>
        <?php } ?>
    </main>
</body>

</html>