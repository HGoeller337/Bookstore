<!-- Profile Page by James Hyun -->

<!-- PHP -->
<?php
// Set up database object
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "cs4300project";
$mysqli     = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Check if user is logged in
$loggedIn = false;
$result   = $mysqli->query('
    SELECT session_id
    FROM users
    WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
');
$resultUser = $result->fetch_assoc();
$loggedIn   = !($resultUser == null);

// Get user id from get request
$userid = filter_input(INPUT_GET, 'user');

// Functions
function printName($userInfo) {
    echo $userInfo['first_name'] . ' ' . $userInfo['last_name'];
    if ($userInfo['is_admin']) {
        echo ' (Admin)';
    }
}

function printBook($userInfo) {
    if ($userInfo['fav_book']) {
        global $mysqli;
        $result = $mysqli->query('
            SELECT title
            FROM books
            WHERE id = ' . $userInfo['fav_book'] . '
        ');
        $book = $result->fetch_assoc();
        if ($book) {
            echo '<a href="../product?book=' . $userInfo['fav_book'] . '"><u>' . $book['title'] . '</u></a>';
        } else {
            echo 'None';
        }
    } else {
        echo 'None';
    }
}

function printAuthor($userInfo) {
    if ($userInfo['fav_author']) {
        global $mysqli;
        $result = $mysqli->query('
            SELECT first_name, last_name
            FROM writers
            WHERE id = ' . $userInfo['fav_author'] . '
        ');
        $author = $result->fetch_assoc();
        if ($author) {
            //echo '<a href="../product.php?book=' . $userInfo['fav_book'] . '"><u>' . $book['title'] . '</u></a>';
            echo $author['first_name'] . ' ' . $author['last_name'];
        } else {
            echo 'None';
        }
    } else {
        echo 'None';
    }
}

function printBio($userInfo) {
    if ($userInfo['bio']) {
        echo $userInfo['bio'];
    } else {
        echo 'I am a mysterious person.';
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Profile</title>
    <!--#include virtual="../head.php"-->
    <link rel="stylesheet" href="style.css" type="text/css"/>
</head>

<body>
    <!--#include virtual="../header.php"-->
    <main>
        <?php
        // Pull up user's own profile
        if ($userid == null) { ?>
        <h1>Your Profile</h1>
        <h3><a href="./edit">Edit Profile</a></h3>
        <?php // If logged in, show profile info
        if ($loggedIn) {
            $result = $mysqli->query('
                SELECT is_admin, first_name, last_name, bio, fav_book, fav_author
                FROM users
                WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
            ');
            $userInfo = $result->fetch_assoc();
        ?>
        <p><b>Name: </b><?php printName($userInfo); ?></p>
        <p><b>Favorite Book: </b><?php printBook($userInfo); ?></p>
        <p><b>Favorite Author: </b><?php printAuthor($userInfo); ?></p>
        <p><b><u>Biography</u></b><br/><?php printBio($userInfo); ?></p>
        
        <?php // If not logged in, then don't
        } else {
        ?>
        <p>You are not logged in!</p>
        <p>Please <a href="../login">log in</a> to view this page, or you can <a href="../">return to the Home Page</a>.</p>
        <?php }
        
        // Pull up a specific user's profile
        } else { ?>
        <h1>User Profile</h1>
        <?php // If logged in, show profile info
        $result = $mysqli->query('
            SELECT is_admin, first_name, last_name, bio, fav_book, fav_author
            FROM users
            WHERE id = "' . $userid . '"
        ');
        $userInfo = $result->fetch_assoc();
        if ($userInfo == null) { ?>
        <p>User not found.</p>
        <?php } else { ?>
        <p><b>Name: </b><?php printName($userInfo); ?></p>
        <p><b>Favorite Book: </b><?php printBook($userInfo); ?></p>
        <p><b>Favorite Author: </b><?php printAuthor($userInfo); ?></p>
        <p><b><u>Biography</u></b><br/><?php printBio($userInfo); ?></p>
        <?php }
        } ?>
    </main>
</body>

</html>