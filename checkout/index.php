<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cs4300project";
$conn = new mysqli($servername, $username, $password, $dbname);

?>

<!DOCTYPE html>
<html lang='en'>
	<head>
		<!--#include virtual="../head.php"-->
		<title>Checkout</title>
        <link rel="stylesheet" href="./checkoutFormStyle.css" type="text/css"/>
        <!--<script src="checkout.js"></script>-->
	</head>
	<body>
		<!--#include virtual="../header.php"-->
		<main>
			<form id="checkoutForm" method = "POST" action="order.php">
                <h2> Billing Information </h2>
                <label for = 'creditCard'>Credit Card Number: </label>
                <input type = 'text' required id = 'creditCard' maxlength = '16' size = '16' pattern = '\d{16}'>
                <span id="creditCardNote">*</span>

                <label for = 'cvv'>CVV: </label>
                <input type = 'text' required id = 'cvv' maxlength = '3' size = '3' pattern = '\d{3}'>
                <span id="cvvNote">*</span>

                <label for = 'date'>Expiration Date: </label>
                <input type = 'text' required id = 'date'>
                <span id="expirationDateNote">*</span>

                <label for = 'name'>Cardholder Name: </label>
                <input type='text' id = 'name' required maxlength = '32'>
                <span id="nameNote">*</span>
                        
                <h2> Billing Address </h2>
                <label for = 'addressOne'>Address Line 1:</label>
                <input type='text' id = 'addressOne' required maxlength = '32'>
                <span id="addressOneNote">*</span>

                <label for = 'addressTwo'>Address Line 2: </label>
                <input type='text' id = 'addressTwo' maxlength = '32'>

                <label for = 'city'>City:</label>
                <input type='text' id = 'city' required maxlength = '32'>
                <span id="cityNote">*</span>

                <label for = 'state'>State:</label>
                <input type='text' id = 'state' required maxlength = '2' size = '2' pattern = '[A-Za-z]{2}'>
                <span id="stateNote">*</span>

                <label for = 'zip'>Zip Code:</label>
                <input type='text' id = 'zip' required maxlength = '5' size = '5' pattern = '[0-9]{5}'>
                <span id="zipNote">*</span>
                
                <h2> Shipping Address </h2>
                <label for = 'addressOneShip'>Address Line 1:</label>
                <input type='text' id = 'addressOneShip' required maxlength = '32'>
                <span id="addressOneShipNote">*</span>

                <label for = 'addressTwoShip'>Address Line 2: </label>
                <input type='text' id = 'addressTwoShip' maxlength = '32'>

                <label for = 'cityShip'>City:</label>
                <input type='text' id = 'cityShip' required maxlength = '32'>
                <span id="cityShipNote">*</span>

                <label for = 'stateShip'>State:</label>
                <input type='text' id = 'stateShip' required maxlength = '2' size = '2' pattern = '[A-Za-z]{2}'>
                <span id="stateShipNote">*</span>

                <label for = 'zipShip'>Zip Code:</label>
                <input type='text' id = 'zipShip' required maxlength = '5' size = '5' pattern = '[0-9]{5}'>
                <span id="zipShipNote">*</span>
                <button id="submitBtn" type='submit'>Submit Purchase Information</button>
			</form>
		</main>
	</body>
</html>