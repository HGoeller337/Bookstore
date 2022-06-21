<!-- Profile Page by James Hyun -->

<!-- PHP -->
<?php
// Set up database object
$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "cs4300project";
$mysqli     = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Fetch users
$result = $mysqli->query('
    SELECT id, first_name, last_name, email
    FROM users
    ORDER BY id
');

// Functions
function printUserRows() {
    global $result;
    while ($user = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $user['first_name'] . ' ' . $user['last_name'] . '</td>';
        echo '<td>' . $user['email'] . '</td>';
        echo '<td><a href="../profile?user=' . $user['id'] . '">View Profile</a></td>';
        echo '</tr>';
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>

<html lang="en">

<head>
    <title>User List</title>
    <!--#include virtual="../head.php"-->
    <link rel="stylesheet" href="style.css" type="text/css"/>
</head>

<body>
    <!--#include virtual="../header.php"-->
    <main>
        <h1>User List</h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Profile</th>
            </tr>
            <?php printUserRows(); ?>
        </table>
    </main>
</body>

</html>