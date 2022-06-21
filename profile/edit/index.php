<!-- Profile Edit Page by James Hyun -->

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

// Form processing
$flags = array(
    'success' => false, // true if update was successful
    'email'   => 0,     // 0 -> okay, 1 -> invalid email, 2 -> email already taken
    'fName'   => 0,     // 0 -> okay, 1 -> not provided
    'lName'   => 0      // ditto
);
require 'process.php';

// Functions
function fillBooksDropdown($userInfo) {
    global $mysqli;
    $result = $mysqli->query('
        SELECT title, id
        FROM books
        ORDER BY title
    ');
    while ($book = $result->fetch_assoc()) {
        $tag = '<option value="'. $book['id'] . '"';
        if ($userInfo['fav_book'] == $book['id']) {
            $tag = $tag . ' selected';
        }
        $tag = $tag . '>' . $book['title'] . '</option>';
        echo $tag;
    }
}
function fillAuthorsDropdown($userInfo) {
    global $mysqli;
    $result = $mysqli->query('
        SELECT first_name, last_name, id
        FROM writers
        ORDER BY first_name
    ');
    while ($author = $result->fetch_assoc()) {
        $tag = '<option value="'. $author['id'] . '"';
        if ($userInfo['fav_author'] == $author['id']) {
            $tag = $tag . ' selected';
        }
        $tag = $tag . '>' . $author['first_name'] . ' ' . $author['last_name'] . '</option>';
        echo $tag;
    }
}

?>

<!-- HTML -->
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Edit Profile</title>
    <!--#include virtual="../../head.php"-->
    <link rel="stylesheet" href="./editFormStyle.css" type="text/css"/>
    <script src="editProfile.js"></script>
</head>

<body>
    <!--#include virtual="../../header.php"-->
    <main>
        <h1>Edit Your Profile</h1>
        <?php // If logged in, show profile info
        if ($loggedIn) {
            $result = $mysqli->query('
                SELECT email, first_name, last_name, bio, fav_book, fav_author
                FROM users
                WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
            ');
            $userInfo = $result->fetch_assoc();
        ?>
        
        <?php
            if ($flags['success']) {
                echo '<p>Your profile has been successfully updated!</p>';
            }
        ?>
        
        <form id="profileEditForm" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $userInfo['email']; ?>" required />
            <span id="emailNote"><?php
                switch($flags['email']) {
                    case 0:
                        echo '*';
                        break;
                    case 1:
                        echo 'Please enter a valid email address.';
                        break;
                    case 2:
                        echo 'This email address is already registered.';
                }
            ?></span>
            
            <label for="fName">First Name:</label>
            <input type="text" id="fName" name="fName" value="<?php echo $userInfo['first_name']; ?>"required />
            <span id="fNameNote"><?php
                switch($flags['fName']) {
                    case 0:
                        echo '*';
                        break;
                    case 1:
                        echo 'Please enter your first name.';
                }
            ?></span>
            
            <label for="lName">Last Name:</label>
            <input type="text" id="lName" name="lName" value="<?php echo $userInfo['last_name']; ?>" required />
            <span id="lNameNote"><?php
                switch($flags['lName']) {
                    case 0:
                        echo '*';
                        break;
                    case 1:
                        echo 'Please enter your last name.';
                }
            ?></span>
            
            <label for="favBook">Favorite Book:</label>
            <select id="favBook" name="favBook">
                <option value="NULL">None</option>
                <?php fillBooksDropdown($userInfo); ?>
            </select>
            
            <label for="favAuthor">Favorite Author:</label>
            <select id="favAuthor" name="favAuthor">
                <option value="NULL">None</option>
                <?php fillAuthorsDropdown($userInfo); ?>
            </select>
            
            <label for="bio">Biography:</label>
            <textarea id="bio" name="bio" rows="18" cols="60"><?php echo $userInfo['bio']; ?></textarea>
            
            <input id="submitBtn" type="submit" value="Update Profile"/>
            <a id="backBtn" href=".."><button type="button">Return to Profile</button></a>
        </form>
        
        <?php // If not logged in, then don't
        } else {
        ?>
        <p>You are not logged in!</p>
        <p>Please <a href="../../login">log in</a> to view this page, or you can <a href="../..">return to the Home Page</a>.</p>
        <?php } ?>
    </main>
</body>

</html>