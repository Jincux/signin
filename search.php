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

function search($input) {
	global $db;
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
		return false;
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
		return $orderedResults;
	}
}

if(($results = search($input))) {
	$hasResults = true;
} else {
	$hasResults = false;
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

	.info {
		
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

	table {
		margin: 0 auto;
		border-collapse: collapse;
	}

	table > tbody > tr {
		
		text-align: left;
		width: 60%;
		margin: 10px auto;
		border-radius: 10px;
		min-height: 120px;
	}

	table > tbody > tr > td {
		color: #1C263C;
		background-color: #fff;
		font-size: 20pt;
		padding: 20px;
	}

	table > tbody > tr > td:first-child {
		border-radius: 10px 0 0 10px;
	}

	table > tbody > tr > td:last-child {
		border-radius: 0 10px 10px 0;
	}

	.filler {
		background: none;
		height: 20px;
	}

	#buttons {
		float: right;
		vertical-align: top;
		position: relative;
	}
	</style>
</head>
<body>
	<?php
	if($hasResults) {
		echo "<h1>Are you..</h1>";
		echo "<h2><a href=\"\">Go Back</a></h2>";
		echo "<table><tbody>";
		foreach($results as $result) {
			?>
				<tr class="search_profile">
					<td class="info">
						<b><?php echo $result['first_name'] . " " . $result['last_name']; ?></b><br/><br/>
						<span style="width: 1000px; color: #455268;word-wrap: break-word;">
						<?php
						if($result['phone'] != "") { echo preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $result['phone']) . "<br />"; }
						if($result['email'] != "") { echo $result['email'] . "<br />"; }
						?>
						</span>
					</td>
					<td>
						<div id="buttons">
							<form action="print.php" method="post"><input type="hidden" name="id" value="<?php echo $result['id']; ?>">
								<input style="float:right;display:inline-block" type="submit" value="Print" />
							</form>
							<form action="options.php" method="post"><input type="hidden" name="id" value="<?php echo $result['id']; ?>">
								<input style="background-color:#00f; float:right;display:inline-block" type="submit" value="Options" />
							</form>
						</div>
					</td>
				</tr>
				<tr class="filler"></tr>
			<?php
		}
		echo "</tbody></table>";
	} else {
		echo "<h1>Uh oh!</h1>";
		echo "We didn't find an account that matched that name!<br />";
		echo "Would you like to <a href=\"register.php\">make an account</a> or <a href=\"index.php\">try again?</a>";
	}
	?>
</body>
</html>
