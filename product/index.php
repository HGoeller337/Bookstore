<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cs4300project";
$conn = new mysqli($servername, $username, $password, $dbname);
$scoreQuery = '(SELECT book, FORMAT(AVG(score), 1) as avg_score FROM reviews GROUP BY book)';

session_start();
$userInfoQuery =
	'SELECT first_name, last_name, email, ID
    FROM users
    WHERE session_id = "' . session_id() . '";';
$user = $conn -> query($userInfoQuery) -> fetch_assoc();

if ($user != NULL) {
	$reviewCheck =
		'SELECT count(*) as count FROM reviews
		WHERE critic = '.$user['ID'].' AND book = '.$_GET['book'].';';
	$userReviewCount = number_format($conn -> query($reviewCheck) -> fetch_assoc()['count']);
}

if (!ISSET($_GET['page'])) {
	$_GET['page'] = 1;
}

if (ISSET($_POST['deleterID'])) {
	$removeReview = 
		'DELETE FROM reviews WHERE critic = '.$_POST['deleterID'].'
		AND book = '.$_GET['book'].';';
	$conn -> query($removeReview);
	header("Refresh:0");
}

if (ISSET($_POST['score'])) {
	if ($userReviewCount < 1) {
	if (ISSET($_POST['critique'])) {
			$addReview = 
				'INSERT INTO reviews VALUES
				('.$user['ID'].', '.$_GET['book'].', '.$_POST['score'].', "'.$_POST['critique'].'");';
		} else {
			$addReview = 
				'INSERT INTO reviews (critic, book, score) VALUES
				('.$user['ID'].', '.$_GET['book'].', '.$_POST['score'].');';
		}
		$conn -> query($addReview);
		header("Refresh:0");
	}
}

?>

<html lang = 'en'>
	<head>
		<!--#include virtual="../head.php"-->
		<script>
			window.onload = function() {
				<?php
					$bookQuery=
						'SELECT price, publish_year, stock, title, '.
						'word_count, t3.name AS genre, '.
						'CONCAT(first_name, " ", last_name) AS author, avg_score '.
						'FROM books t1 '.
						'INNER JOIN writers t2 ON t1.author = t2.id '.
						'INNER JOIN genres t3 ON t1.genre = t3.id '.
						'LEFT JOIN '.$scoreQuery.' t4 on t1.id = t4.book '.
						'WHERE t1.id = '.$_GET['book'].';';
					$book = $conn->query($bookQuery)->fetch_assoc();
					if ($book === NULL) {
						echo 'window.alert("Book not Found");';
						echo 'window.location = "../search/";';
					} else {
						$reviewCountQuery = 'SELECT COUNT(*) as review_count FROM reviews WHERE book = '.$_GET['book'].' GROUP BY book';
						$reviewCount = $conn ->query($reviewCountQuery) ->fetch_assoc()['review_count'];
					}
				?>
			}
		</script>
		<title><?php echo $book['title']; ?></title>
		<style>
			html {
				font-family:arial;
			}
			main {
				margin:auto;
				width:75%;
				padding-left:5%;
				padding-right:5%;
			}
			section img {
				width:30%;
			}
			body {
				margin:0em;
				background-color:#e8e8e8;
			}
			#description {
				float:right;
				width:60%;
				background-color:white;
				border-radius:5px;
				padding:2em;
			}
			#reviews {
				clear:both;
				width:80%;
				margin:auto;
				background-color:white;
				padding:2em;
				border-radius:5px;
			}
			h1 {
				text-align:center;
			}
			.currentPage {
				background-color:#ffcccb;
			}
			#pageText {
				width:30%;
				text-align:center;
				margin:auto;
			}
			.review {
				border: 1px solid black;
				padding:1em;
				padding-top:0.5em;
				padding-bottom:0.5em;
				margin:1em;
				font-size:0.9em;
				border-radius:5px;
				background-color:#ffcccb;
			}
			hr {
				margin-top:10%;
				clear:both;
			}
			#fade {
				display:none;
				width:100%;
				height:100%;
				background-color:rgba(128, 128, 128, 0.5);
				z-index:1;
				position:fixed;
			}
			aside{
				display:none;
				padding:1%;
				border-radius:5px;
				padding-top:0.5%;
				padding-bottom:0.5%;
				position:fixed;
				border: 1px solid black;
				top:25%;
				right:35%;
				z-index:2;
				background-color:white;
				width:30%;
			}
			.rating {
				display:inline;
				text-align:center;
				width:20%;
				float:left;
				margin-top:0em;
			}
			label {
				display:block;
				margin:0em;
			}
			input {
				display:block;
				margin-left:42%;
			}
			textarea {
				resize:none;
				width:100%;
				padding:0.5%;
				margin-bottom:3%;
			}
			#reviewForm button {
				margin-left:1%;
				margin-right:1%;
				display:block;
				float:left;
			}
			span {
				font-weight:normal;
				font-size:0.8em;
			}
			#reviewForm h2 {
				margin:auto;
				text-align:center;
			}
			#logInPlease {
				font-size:0.8em;
				margin-top:-1.5em;
				margin-left:8em;
			}
			#deleteMessage {
				text-decoration:underline;
				cursor:pointer;
				color:rgb(204, 0, 0);
			}
			a {
				color:rgb(204, 0, 0);
			}
			a:visited {
				color:rgb(204, 0, 0);
			}
		</style>
		<script>
			function closeForm() {
				document.getElementById('formHolder').style.display = 'none';
				document.getElementById('fade').style.display = 'none';
			}
			function openForm() {
				document.getElementById('formHolder').style.display = 'block';
				document.getElementById('fade').style.display = 'block';
			}
			function submitDeletion() {
				document.getElementById('deletionForm').submit();
			}
			
		</script>
	</head>
	<div id = 'fade'></div>
	<body>
		<!--#include virtual="../header.php"-->
		<section>
		<aside id = 'formHolder'>
			<form id = 'reviewForm' method = 'POST' action = ''>
				<h2> Rate <i><?php echo $book['title']; ?></i></h4>
				<p> Score: (required) </p>
				<p class = 'rating'>
					<label for = 'one'>1</label>
					<input type = 'radio' id='one' value = '1' name = 'score' required>
				</p>
				<p class = 'rating'>
					<label for = 'two'>2</label>
					<input type = 'radio' id='two' value = '2' name = 'score'>
				</p>
				<p class = 'rating'>
					<label for = 'three'>3</label>
					<input type = 'radio' id='three' value = '3' name = 'score'>
				</p>
				<p class = 'rating'>
					<label for = 'four'>4</label>
					<input type = 'radio' id='four' value = '4' name = 'score'>
				</p>
				<p class = 'rating'>
					<label for = 'five'>5</label>
					<input type = 'radio' id='five' value = '5' name = 'score'>
				</p>
				<p> Review: (optional)</p>
				<textarea maxlength = '1000' rows = '10' name = 'critique'></textarea>
				<button type='submit'>Submit Review</button>
				<button type = 'button' onclick = 'closeForm()'>Close</button>
			</form>
		</aside>
		<main>
			<div id='description'>
				<p><a href='../search'>Return to search page</a></p>
				<h1><?php echo $book['title']; ?></h1>
				<h2> Author: <span><?php echo $book['author']; ?></span></h2>
				<h4> Year of Publication: <span><?php echo $book['publish_year']; ?></span></h4>
				<h4> Genre: <span><?php echo $book['genre']; ?></span></h4>
				<h4> Length: <span><?php echo $book['word_count']; ?> words</span></h4>
				<h4>
					<?php
						if ($book['avg_score'] != NULL) {
							echo 'Average user score: <span>'.$book['avg_score'].'/5 ('.$reviewCount.' reviews)</span>';
						} else {
							echo '<i>No reviews yet!</i>';
						}
					?>
				</h4>
				<br>
				<h2><i>$<?php echo $book['price']; ?></i></h2>
				<h4>In stock: <span><?php echo $book['stock']; ?></span></h4>
				<form action = "../cart/index.php" method = "POST">
					<button type = 'submit'  name = 'add' value = '<?php echo $_GET['book']?>'
						<?php if ($book['stock'] == 0) echo 'disabled';?>>Add to Cart</button>
				</form>
			</div>
			<img alt='<?php echo $book['title']; ?>' src = '../thumbnails/<?php echo $_GET['book']; ?>.png'>
			<hr>
			<div id='reviews'>
				<h2>User Reviews:</h2>
				<?php
					if ($user == NULL) {
						echo "<button type='button' disabled>Write Review</button>";
						echo "<p id = 'logInPlease'><i>Must be <a href = '../login/'>logged in</a> to leave a review!</i></p>";
					} else if ($userReviewCount > 0) {
						echo "<button type='button' disabled>Write Review</button>";
						echo "<p id = 'logInPlease'><i>You may only leave one review!</i></p>";
						echo '<form action = "" id = "deletionForm" method = "POST">
								<p>
									<span id = "deleteMessage" onclick = "submitDeletion()">Delete Review</span>
								</p>
								<input type = "hidden" name = "deleterID" value = "'.$user['ID'].'">
							</form>';
					} else {
						echo "<button type='button' onclick = 'openForm()'>Write Review</button>";
					}
					if ($reviewCount == 0) {
						echo '<p><br>No reviews yet. Be the first to write a review!</p>';
					} else {
						$pageCount = ceil($reviewCount / 3);
						$reviewQuery =
							'SELECT score, critique, CONCAT(first_name, " ", last_name) AS user, email, id
							FROM reviews t1
							INNER JOIN users t2 ON t1.critic = t2.id
							WHERE t1.book = '.$_GET['book'].'
							AND t1.critique IS NOT NULL
							LIMIT '.(($_GET['page'] - 1) * 3).', 3;';
						$reviews = $conn -> query($reviewQuery);
						while ($review = $reviews->fetch_assoc()) {
							echo '<div class = "review">';
							echo '<p class = "score"><b>Score: '.$review['score'].' out of 5</b></p>';
							if ($review['user'] == NULL) {
								echo '<p class = "name"><i>Anonymous</i></p>';
							} else {
								echo '<p class = "name"><a href="../profile?user='.$review['id'].'"><i>'.$review['user']
                                    .'</i></a></p>';
							}
							echo '<p class = "email"><i>'.$review['email'].'</i></p>';
							echo '<p class = "critique"><br>'.$review['critique'].'</p>';
							echo '</div>';
						}
						echo '<form id = "pageForm" name = "pageForm" method = "GET" action = "#reviews">';
						echo '<input type = "hidden" value = "'.$_GET['book'].'" name = "book">';
						echo '<p id="pageText"> Page: ';
						for ($i = 1; $i <= $pageCount; $i++) {
							echo "<button type = 'submit' name = 'page' value = '".$i."'";
							if ($_GET['page'] == $i) {
								echo "class = 'currentPage'";
							}
							echo ">".$i."</button>";
						}
						echo '</p>';
						echo '</form>';
					}
				?>
				
			</div>
		</main>
		</section>
	</body>
</html>