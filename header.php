<?php
// DO NOT MOVE THIS FILE OUT OF THE SAME DIRECTORY WHERE rootPath.php IS LOCATED

// Set up database object
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "cs4300project";
$mysqli     = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Get user name
$firstName  = 'Guest';
$isLoggedIn = false;
$isAdmin    = false;
if (array_key_exists('PHPSESSID', $_COOKIE)) {
    $nameResult = $mysqli->query('
        SELECT session_id, first_name, is_admin
        FROM users
        WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
    ');                         // for some reason session_id() isnt working here??
    
    $userResult = $nameResult->fetch_assoc();
    if ($userResult != null) {
        $firstName  = $userResult['first_name'];
        $isAdmin    = $userResult['is_admin'];
        $isLoggedIn = true;
    }
}

// Get path to root directory of this website. See rootPath.php for more comments
include 'rootPath.php';
?>

<header>
    <img src="<?php echo $rootPath; ?>bookstore logo.png" />
    <h1>Riveting Reads</h1>
    <div class="alignRight">
    <?php if ($isLoggedIn) { ?>
        <span>Hello, <?php echo $firstName; ?>.</span>
        <a href="<?php echo $rootPath; ?>logout.php">Logout</a>
    <?php } else { ?>
        <a href="<?php echo $rootPath; ?>login">Sign In</a>
        <a href="<?php echo $rootPath; ?>register">Create an Account</a>
    <?php } ?>
    </div>
</header>
<nav>
    <a href="<?php echo $rootPath; ?>">Home</a>
    <a href="<?php echo $rootPath; ?>search">Catalog</a>
    <?php if ($isLoggedIn) { ?>
    <a href="<?php echo $rootPath; ?>cart">Cart</a>
    <a href="<?php echo $rootPath; ?>checkout">Checkout</a>
    <a href="<?php echo $rootPath; ?>profile">Profile</a>
    <?php } ?>
    <a href="<?php echo $rootPath; ?>users">User List</a>
    <?php if ($isAdmin) { ?>
    <a href="<?php echo $rootPath; ?>../phpmyadmin">phpMyAdmin</a>
    <?php } ?>
</nav>