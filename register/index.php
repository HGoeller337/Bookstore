<!-- Registration Page by James Hyun -->

<!-- Registration handling -->
<?php
// Registration vars
$email   = filter_input(INPUT_POST, 'email');
$pass    = filter_input(INPUT_POST, 'pass');
$passRep = filter_input(INPUT_POST, 'passRepeat');
$fName   = filter_input(INPUT_POST, 'fName');
$lName   = filter_input(INPUT_POST, 'lName');
$flags   = array(
    'success' => false, // true if registration was successful
    'email'   => 0,     // 0 -> okay, 1 -> invalid email, 2 -> email already taken
    'pass'    => 0,     // 0 -> okay, 1 -> passwords don't match
    'fName'   => 0,     // 0 -> okay, 1 -> not provided
    'lName'   => 0      // ditto
);

// Check whether we got a form request or if we just loaded the page
if ($email == NULL && $pass == NULL && $passRep == NULL && $fName == NULL && $lName == NULL) {} else {

    // Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $flags['email'] = 1;
    }
    if ($pass == NULL || $passRep == NULL || $pass != $passRep) {
        $flags['pass'] = 1;
    }
    if ($fName == NULL || $fName == '') {
        $flags['fName'] = 1;
    }
    if ($lName == NULL || $lName == '') {
        $flags['lName'] = 1;
    }

    if (!($flags['email']) && $flags['email'] == $flags['pass']) {
        // Set up database object
        $serverName = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName     = "cs4300project";
        $mysqli     = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);
        
        // Check if email is already in use
        $result = $mysqli->query('
            SELECT email
            FROM users
            WHERE email = "' . $email . '"
        ');
        $resultUser = $result->fetch_assoc();
        if ($resultUser != null) {
            $flags['email'] = 2;
        }
        
        // Write user credentials to db
        if (!$flags['email']) {
            $mysqli->query('
                INSERT INTO users (email, pass, is_admin, first_name, last_name, cart) VALUES
                ("' . $email . '", "' . $pass . '", FALSE, "' . $fName . '", "' . $lName . '", "{}")
            ');
            
            $flags['success'] = true;
        }
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Create an Account</title>
    <!--#include virtual="../head.php"-->
    <link rel="stylesheet" href="./regFormStyle.css" type="text/css"/>
    <script src="registration.js"></script>
</head>

<body>
    <!--#include virtual="../header.php"-->
    <main>
        <h1>Create an Account</h1>
        
        <?php if ($flags['success'] == false) { ?>
        <!-- If no request or if something was wrong -->
        <form id="registrationForm" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />
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
            <input type="text" id="fName" name="fName" required />
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
            <input type="text" id="lName" name="lName" required />
            <span id="lNameNote"><?php
                switch($flags['lName']) {
                    case 0:
                        echo '*';
                        break;
                    case 1:
                        echo 'Please enter your last name.';
                }
            ?></span>
            
            <label for="pass">Password:</label>
            <input type="password" id="pass" name="pass" required />
            <span id="passNote"><?php
                switch($flags['pass']) {
                    case 0:
                        echo '*';
                        break;
                    case 1:
                        echo 'The passwords you entered do not match.';
                }
            ?></span>
            
            <label for="passRepeat">Repeat Password:</label>
            <input type="password" id="passRepeat" name="passRepeat" required />
            <span id="passRepeatNote">*</span>
            
            <input id="submitBtn" type="submit" value="Register"/>
        </form>
        
        <?php } else { ?>
        <!-- Successful registration -->
        <p>You have successfully registered! You may now log into your new account from the login page.</p>
        <a href="../login">Return to login page</a>
        <?php } ?>
    </main>
</body>

</html>