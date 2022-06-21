<!-- Order Page by James Hyun -->

<!-- PHP -->
<?php
// Set up database object
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "cs4300project";
$mysqli     = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Fetch user credentials
$result = $mysqli->query('
    SELECT first_name
    FROM users
    WHERE session_id = "' . $_COOKIE["PHPSESSID"] . '"
');
$user = $result->fetch_assoc();

setcookie('cart', '{}', 0, '/');
$_COOKIE['cart'] = '{}';
$mysqli->query('
    UPDATE users
    SET cart = \'{}\'
    WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
');

?>

<!-- HTML -->
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Order Placed</title>
    <!--#include virtual="../head.php"-->
</head>

<body>
    <!--#include virtual="../header.php"-->
    <main>
        <h1>Order placed!</h1>
        <p>Thank you for your order, <?php echo $user['first_name'] ?>! Please allow for up to 5 business days for delivery to your listed shipping address.</p>
        <p><a href="..">Return to home page</a></p>
    </main>
</body>

</html>