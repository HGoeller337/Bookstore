<?php
// Profile Edit form processing by James Hyun
$email     = filter_input(INPUT_POST, 'email');
$fName     = filter_input(INPUT_POST, 'fName');
$lName     = filter_input(INPUT_POST, 'lName');
$favBook   = filter_input(INPUT_POST, 'favBook');
$favAuthor = filter_input(INPUT_POST, 'favAuthor');
$bio       = filter_input(INPUT_POST, 'bio');

if (!($loggedIn)) return;

// Check whether we got a form request or if we just loaded the page
if ($email == NULL && $favBook == NULL && $favAuthor == NULL && $fName == NULL && $lName == NULL && $bio == NULL) {} else {

    // Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $flags['email'] = 1;
    }
    if ($fName == NULL || $fName == '') {
        $flags['fName'] = 1;
    }
    if ($lName == NULL || $lName == '') {
        $flags['lName'] = 1;
    }

    if (!($flags['email']) && $flags['email'] == $flags['fName'] && $flags['fName'] == $flags['lName']) {
        // Set up database object
        /*
        $serverName = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName     = "cs4300project";
        $mysqli     = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);
        */
        global $mysqli;
        
        // Check if email is already in use
        $result = $mysqli->query('
            SELECT email, session_id
            FROM users
            WHERE email = "' . $email . '"
        ');
        $resultUser = $result->fetch_assoc();
        if ($resultUser != null && $resultUser['session_id'] != $_COOKIE['PHPSESSID']) {
            $flags['email'] = 2;
        }
        
        // Bio - format value (empty string -> NULL for internal use, else surround with quotes)
        if ($bio == '') {
            $bio = 'NULL';
        } else {
            $bio = '"' . $bio . '"';
        }
        
        // Write user credentials to db
        if (!$flags['email']) {
            $mysqli->query('
                UPDATE users
                SET email = "' . $email . '", first_name = "' . $fName . '", last_name = "' . $lName . '",
                    fav_book = ' . $favBook . ', fav_author = ' . $favAuthor . ', bio = ' . $bio . '
                WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
            ');
            
            $flags['success'] = true;
        }
    }
}

?>