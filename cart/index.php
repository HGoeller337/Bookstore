<!-- Cart page by James Hyun & Zach Cushenberry -->

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

// Check if we received a post request to add item to cart
$newBookID = filter_input(INPUT_POST, 'add');
if ($newBookID && $loggedIn) {
    $cart = json_decode($_COOKIE['cart'], true);
    if (array_key_exists($newBookID, $cart)) {
        $cart[$newBookID] += 1;
    } else {
        $cart[$newBookID] = 1;
    }
    $newCart = json_encode($cart);
    setcookie('cart', $newCart, 0, '/');
    $_COOKIE['cart'] = $newCart;
    
    $mysqli->query('
        UPDATE books
        SET stock = stock - 1
        WHERE id = ' . $newBookID . '
    ');
    $mysqli->query('
        UPDATE users
        SET cart = \'' . $newCart . '\'
        WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
    ');
}

// Check similar except remove item
$newBookID = filter_input(INPUT_POST, 'del');
if ($newBookID && $loggedIn) {
    $cart = json_decode($_COOKIE['cart'], true);
    if (array_key_exists($newBookID, $cart)) {
        $cart[$newBookID] -= 1;
        if ($cart[$newBookID] <= 0) {
            unset($cart[$newBookID]);
        }
    }
    $newCart = json_encode($cart);
    setcookie('cart', $newCart, 0, '/');
    $_COOKIE['cart'] = $newCart;
    
    $mysqli->query('
        UPDATE books
        SET stock = stock + 1
        WHERE id = ' . $newBookID . '
    ');
    $mysqli->query('
        UPDATE users
        SET cart = \'' . $newCart . '\'
        WHERE session_id = "' . $_COOKIE['PHPSESSID'] . '"
    ');
}

// Functions
function getBookInfo($id) {
    global $mysqli;
    $result = $mysqli->query('
        SELECT *
        FROM books
        WHERE id = ' . $id . '
    ');
    return $result->fetch_assoc();
}
function fillCartTable() {
    $cart     = json_decode($_COOKIE['cart'], true);
    $subtotal = 0.0;
    $taxRate  = .08;
    
    // Get cart items
    foreach ($cart as $id => $qty) {
        $book = getBookInfo($id);
        if ($book == null) {
            continue;
        }
        $cost      = $book['price'] * $qty;
        $subtotal += $cost;
        echo '<tr>';
        echo '<td><img src="../thumbnails/' . $id . '.png" /></td>';
        echo '<td>'  . $book['title'] . '</td>';
        echo '<td>$' . $book['price'] . '</td>';
        echo '<td>'  . $qty  . '</td>';
        echo '<td>$' . number_format($cost, 2) . '</td>';
        echo '<td><form method="POST"><button type="submit" name="del" value=' . $id . '>Remove 1</button></form></td>';
        echo '</tr>';
    }
    
    // Subtotal, tax, and grand total
    $tax   = $subtotal * $taxRate;
    $total = $subtotal + $tax;
    echo '<tr></tr>'; // blank row for spacing
    echo '<tr><td></td><td>Subtotal</td><td></td><td></td><td>$'. number_format($subtotal, 2) .'</td><td></td></tr>';
    echo '<tr><td></td><td>Tax (8%)</td><td></td><td></td><td>$'. number_format($tax, 2) .'</td><td></td></tr>';
    echo '<tr><td></td><td><b>Grand Total</b></td><td></td><td></td><td>$'. number_format($total, 2) .'</td><td></td></tr>';
}
?>

<!-- HTML -->
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Cart</title>
    <!--#include virtual="../head.php"-->
    <link rel="stylesheet" href="style.css" type="text/css"/>
</head>

<body>
    <!--#include virtual="../header.php"-->
    <main>
        <h1>Cart</h1>
        <?php if ($loggedIn) { ?>
        <table>
            <tr>
                <th></th>
                <th>Book</th>
                <th>Price Per</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th></th>
            </tr>
            <?php fillCartTable(); ?>
        </table>
        <?php } else { ?>
        <p>You are not logged in!</p>
        <p>Please <a href="../login">log in</a> to view this page, or you can <a href="../">return to the Home Page</a>.</p>
        <?php } ?>
    </main>
</body>

</html>