<?php
//open $db as a database resource
$db = new SQLite3('local_db.sql');

//sanitizing the input string to prevent injection
$input = $_POST['name'];
$input = $db->escapeString($input);

//if somebody left it blank, send them home
if(trim($input) == "") {
	header('Location: index.php');
	die();
}

//split up each word entered
$terms = split(" ", $input);

//construct a SQL query
$searchSql = "";
foreach($terms as $term) {
	if($searchSql != "") {
		$searchSql = $searchSql . " OR ";
	}
	$searchSql = $searchSql . "`first_name` LIKE '" . $term . "' OR ";
	$searchSql = $searchSql . "`last_name` LIKE '" . $term . "' ";
}
$q = @$db->query('SELECT * FROM `users` WHERE ' . $searchSql);

//if the query returned no results, we're going to try it with wildcards
if($q->fetchArray() === false) {
	echo "adding wildcards!";
	$searchSql = "";
	foreach($terms as $term) {
		if($searchSql != "") {
			$searchSql = $searchSql . " OR ";
		}
		$searchSql = $searchSql . "`first_name` LIKE '" . $term . "%' OR ";
		$searchSql = $searchSql . "`last_name` LIKE '" . $term . "%' ";
	}
	$q = @$db->query('SELECT * FROM `users` WHERE ' . $searchSql);
} else {
	$q->reset();
}
//ordering results by prominence
$searchResults = array();
$pointArray = array();
$hasResults = true;
if($q->fetchArray() === false) {
	$hasResults = false;
} else {
	$q->reset();
	while($result = $q->fetchArray()) {
		$points = 0;

		$searchResults[$result['id']] = $result;

		if(in_array(strtolower($result['first_name']), array_map('strtolower', $terms))) {
			$points += 5;
			//echo "first name match +5<br />";
		}

		if(in_array(strtolower($result['last_name']), array_map('strtolower', $terms))) {
			$points += 5;
			//echo "last name match +5<br />";
		}

		foreach($terms as $term) {
			if(stripos($result['last_name'], $term) === 0) {
				$points += 3;
				//echo "last name begin +3<br />";
			} else if(stripos($result['last_name'], $term) !== false) {
				$points += 1;
				//echo "last name contain +1<br />";
			}

			if(stripos($result['first_name'], $term) === 0) {
				$points += 3;
				//echo "first name begin +3<br />";
			} else if(stripos($result['first_name'], $term) !== false) {
				$points += 1;
				//echo "first name contain +1<br />";
			}
		}
		//echo $points . "<br /><br />";
		$pointArray[$result['id']] = $points;
	}

	arsort($pointArray); //sorts array from high to low

	$orderedResults = array();
	foreach($pointArray as $key=>$points) {
		$orderedResults[] = $searchResults[$key];
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<title></title>
	<style class="text/css">
	body {
		text-align: center;
	}

	.search_profile {
		color: #1C263C;
		font-size: 20pt;
		padding: 20px;
		text-align: left;
		width: 60%;
		margin: 10px auto;
		background-color: #fff;
		border-radius: 10px;
	}

	.info {
		display: inline-block;

	}

	input {
		font-size: 20pt;
		border-radius: 10px;
		padding: 10px;
		background-color: #3333ff;
	}

	input[type=submit] {
		margin: 5px;
	}

	#buttons {
		float: right;
	}
	</style>
</head>
<body>
	<?php
	if($hasResults) {
	echo "<h1>Are you..</h1>";
	echo "<h2><a href=\"\">Go Back</a></h2>";
		foreach($orderedResults as $result) {
			echo "<div class=\"search_profile\"><div class=\"info\">";
			echo "<b>" . $result['first_name'] . " " . $result['last_name'] . "</b><br/><br/>";
			echo "<span style=\"color: #455268;\">";
			if($result['phone'] != "") { echo preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $result['phone']) . "<br />"; }
			if($result['email'] != "") { echo $result['email'] . "<br />"; }
			echo "</span></div>";
			echo "<div id=\"buttons\">";
			echo "<form action=\"print.php\" method=\"post\"><input type=\"hidden\" name=\"id\" value=\"" . $result['id'] . "\">";
			echo "<input style=\"float:right;display:inline-block\" type=\"submit\" value=\"Print\" />";
			echo "</form>";
			echo "<form action=\"options.php\" method=\"post\"><input type=\"hidden\" name=\"id\" value=\"" . $result['id'] . "\">";
			echo "<input style=\"background-color:#0f0; float:right;display:inline-block\" type=\"submit\" value=\"Options\" /></form>";
			echo "</div></div>";
		}
	} else {
		echo "<h1>Uh oh!</h1>";
		echo "We didn't find an account that matched that name!<br />";
		echo "Would you like to <a href=\"register.php\">make an account</a> or <a href=\"index.php\">try again?</a>";
	}
	?>
</body>
</html>
