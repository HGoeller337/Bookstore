<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cs4300project";
$conn = new mysqli($servername, $username, $password, $dbname);

function filter($var){
    return $var >= 0;
}

if (!ISSET($_GET['direction'])) {
	$_GET['direction'] = 'DESC';
}
if (!ISSET($_GET['order'])) {
	$_GET['order'] = 'relevance';
}
if (!ISSET($_GET['page'])) {
	$_GET['page'] = 1;
}
$genreCondition = 'WHERE 1 ';
if (!ISSET($_GET['genre'])) {
	$_GET['genre'] = '';
}
if (!EMPTY($_GET['genre'])) {
	if ($_GET['genre'] == 'fiction') {
		$genreCondition = 'WHERE is_fiction = TRUE ';
	} else if ($_GET['genre'] == 'nonfiction') {
		$genreCondition = 'WHERE is_fiction = FALSE ';
	} else {
		$genreCondition = 'WHERE t3.id = '.$_GET['genre'].' ';
	}
}
if (!ISSET($_GET['term'])) {
	$_GET['term'] = '';
}

$idRelevance = array();
$keyWordsQuery = 
	'SELECT t1.id AS id, CONCAT(title, " ", first_name, " ", last_name, " ", publish_year) AS key_words '.
	'FROM books t1 '.
	'INNER JOIN writers t2 ON t1.author = t2.id;';
$keyWords = $conn->query($keyWordsQuery);
while ($book = $keyWords->fetch_assoc()) {
	$token = strtok($_GET['term'], " ");
	if (!ISSET($idRelevance[$book['id']])) {
		$idRelevance[$book['id']] = 0;
	}
	while($token !== FALSE) {
		if (stripos($book['key_words'], $token, 0) !== FALSE) {
			$idRelevance[$book['id']] += 1;
		} else {
			$idRelevance[$book['id']] -= 1;
		}
		$token = strtok(" ");
	}
}
$relevantIds = array_filter($idRelevance, "filter");
if ($_GET['direction'] == 'DESC') {
	foreach ($idRelevance as &$val) {
		$val *= -1;
	}
}

$idString = "(";
foreach (array_keys($relevantIds) as $key) {
	$idString .= "$key,";
}
$idString = substr($idString ,0, -1);
$idString .= ")";

$scoreQuery = '(SELECT book, FORMAT(AVG(score), 1) as avg_score FROM reviews GROUP BY book)';
if ($_GET['order'] == 'relevance') {
	$searchQuery =
		'SELECT t1.id AS id, price, publish_year, stock, title, '.
		'word_count, is_fiction, t3.name AS genre, '.
		'CONCAT(first_name, " ", last_name) AS author, avg_score '.
		'FROM books t1 '.
		'INNER JOIN writers t2 ON t1.author = t2.id '.
		'INNER JOIN genres t3 ON t1.genre = t3.id '.
		'LEFT JOIN '.$scoreQuery.' t4 on t1.id = t4.book '.
		$genreCondition.
		'AND t1.id IN '.$idString.' '.
		'LIMIT '.(($_GET['page'] - 1) * 12).', 12;';
} else {
	$searchQuery =
		'SELECT t1.id AS id, price, publish_year, stock, title, '.
		'word_count, is_fiction, t3.name AS genre, '.
		'CONCAT(first_name, " ", last_name) AS author, avg_score '.
		'FROM books t1 '.
		'INNER JOIN writers t2 ON t1.author = t2.id '.
		'INNER JOIN genres t3 ON t1.genre = t3.id '.
		'LEFT JOIN '.$scoreQuery.' t4 on t1.id = t4.book '.
		$genreCondition.
		'AND t1.id IN '.$idString.' '.
		'ORDER BY '.$_GET['order'].' '.$_GET['direction'].' '.
		'LIMIT '.(($_GET['page'] - 1) * 12).', 12;';
}
$countQuery =
	'SELECT COUNT(*) AS count FROM books t1 '.
	'INNER JOIN genres t3 on t1.genre = t3.id '.
	$genreCondition.
	'AND t1.id IN '.$idString.';';
	
$bookCount = 0;
$pageCount = 1;
$bookCheck = $conn->query($countQuery);
if ($bookCheck !== FALSE) {
	$bookCount = $bookCheck->fetch_assoc()['count'];
	if ($bookCount > 0) $pageCount = CEIL($bookCount / 12);
}
?>

<!DOCTYPE html>
<html lang= 'en'>
	<head>
		<!--#include virtual="../head.php"-->
		<title> Search Page </title>
		<link rel="stylesheet" href="search.css" type="text/css"/>
		<script>
			function sort(nam, val) {
				document.getElementById(nam).value = val;
				document.getElementById('sortForm').submit();
			}
			function goTo(URL) {
				window.location = URL;
			}
		</script>
	</head>
	<body>
		<!--#include virtual="../header.php"-->
		<main id="searchMain">
			<form id = 'sortForm' action = '' method = 'GET'>
				<p id = 'searchBarHolder'>
				<input type='text' id = 'searchBar'
				maxlength = "100" name = 'term' value = '<?php echo $_GET['term'];?>'>
				<button type='submit'>Go</button>
				</p>
				<p id="sortText">
					Order By:
					<span onclick = 'sort("order", "relevance")'
						<?php if ($_GET['order'] == 'relevance') echo ' class = "selected"';?>>
						Search Relevance </span> |
					<span onclick = 'sort("order", "publish_year")'
						<?php if ($_GET['order'] == 'publish_year') echo ' class = "selected"';?>>
						Date of Publication </span> |
					<span onclick = 'sort("order", "price")' 
						<?php if ($_GET['order'] == 'price') echo ' class = "selected"';?>>
						Price </span> |
					<span onclick = 'sort("order", "avg_score")'
						<?php if ($_GET['order'] == 'avg_score') echo ' class = "selected"';?>>
						Ratings </span>
					&nbsp;
					with values
					&nbsp;
					<span onclick = 'sort("direction", "DESC")'
						<?php if ($_GET['direction'] == 'DESC') echo ' class = "selected"';?>>
						Descending </span> |
					<span onclick = 'sort("direction", "ASC")'
						<?php if ($_GET['direction'] == 'ASC') echo ' class = "selected"';?>>
						Ascending </span> 
				</p>
				<p id="pageText"> Page: 
				<?php
					for ($i = 1; $i <= $pageCount; $i++) {
						echo "<button type = 'submit' name = 'page' value = '".$i."'";
						if ($_GET['page'] == $i) {
							echo "class = 'currentPage'";
						}
						echo ">".$i."</button>";
					}
				?>
				</p>
				<input type = 'hidden' id = 'order' name = 'order' value = '<?php echo $_GET['order']; ?>'>
				<input type = 'hidden' id = 'direction'name = 'direction' value = '<?php echo $_GET['direction']; ?>'>
				<div id = 'sidebar'>
					<p onclick = "sort('genre', '')">All Genres</p>
					<div class = 'genreBox' <?php if ($_GET['genre'] == 'fiction') echo ' id = "currentGenre"';?>>
						<p onclick = "sort('genre', 'fiction')" class = "genreIdentifier">Fiction</p>
						<?php
							$fictionQuery = "SELECT name, id FROM genres WHERE is_fiction = 1;";
							$fictionGenres = $conn->query($fictionQuery);
							while ($genre = $fictionGenres->fetch_assoc()) {
								echo "<p onclick=\"sort('genre', '".$genre['id']."')\" class = 'genre'";
								if ($_GET['genre'] == $genre['id']) {
									echo " id = 'currentGenre'";
								}
								echo ">".$genre['name']."</p> ";
							}
						?>
					</div>
					<div class = 'genreBox' <?php if ($_GET['genre'] == 'nonfiction') echo ' id = "currentGenre"';?>>
						<p onclick = "sort('genre', 'nonfiction')" class = "genreIdentifier">NonFiction</p>
						<?php
							$nonFictionQuery = "SELECT name, id FROM genres WHERE is_fiction = 0;";
							$nonFictionGenres = $conn->query($nonFictionQuery);
							while ($genre = $nonFictionGenres->fetch_assoc()) {
								echo "<p onclick=\"sort('genre', '".$genre['id']."')\" class = 'genre'";
								if ($_GET['genre'] == $genre['id']) {
									echo " id = 'currentGenre'";
								}
								echo ">".$genre['name']."</p> ";							}
						?>
					</div>
					<input type = 'hidden' id = 'genre' name = 'genre' value = '<?php echo $_GET['genre']; ?>'>
				</div>
			</form>
			<div id = 'container'>
				<?php
					if ($bookCount > 0) {
						$results = $conn->query($searchQuery);
						while($book = $results->fetch_assoc()) {
							echo
								'<div class = "book" onclick = \'goTo("../product/?book='.$book['id'].'")\'
								style = "background-image: url(\'../thumbnails/'.$book['id'].'.png\');';
							if ($_GET['order'] == 'relevance') {
								echo 'order:'.$idRelevance[$book['id']].';';
							}
							echo '"><p class = "price"><i>$'.number_format($book['price'], 2).'</i></p>';
							if ($book['avg_score'] == NULL) {
								echo '<p class = "review"><i>No user ratings</i></p>';
							} else {
								$reviewCountQuery = 'SELECT COUNT(*) as review_count FROM reviews WHERE book = '.$book['id'].' GROUP BY book';
								$reviewCount = $conn ->query($reviewCountQuery) ->fetch_assoc()['review_count'];
								echo '<p class = "review">Reviews: '.$book['avg_score'].'/5 ('.$reviewCount.')</p>';
							}		
							echo
								'<p class = "title">'.$book['title'].'</p>'.
								'<p class = "author"><i>'.$book['author'].'</i></p>'.
								'<p class = "year">'.$book['publish_year'].'</p>'
							;		
							echo '</div>';
						}
					} else {
						echo '<p id="errorMessage"><i>No results found!</i></p>';
					}
				?>
			</div>
		</main>
	</body>
</html>