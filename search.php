<?php
if ($db = new SQLite3('local_db.sql')) {
    $q = @$db->query('CREATE TABLE IF NOT EXISTS users (id int, first_name varchar(32), last_name varchar(32), email varchar(64), phone varchar(11), PRIMARY KEY (id))');
}

$input = $_POST['name'];
$input = $db->escapeString($input);

$terms = split(" ", $input);

$searchSql = "";
foreach($terms as $term) {
	if($searchSql != "") {
		$searchSql = $searchSql . " OR ";
	}
	$searchSql = $searchSql . "`first_name` LIKE '" . $term . "' OR ";
	$searchSql = $searchSql . "`last_name` LIKE '" . $term . "' ";
}
$q = @$db->query('SELECT * FROM `users` WHERE ' . $searchSql);
//echo 'SELECT * FROM `users` WHERE ' . $searchSql;
$list = "";
while($result = $q->fetchArray()) {
	$list = $list . $result['first_name'] . " " . $result['last_name'] . "<br />";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style class="text/css">
	body {
		font-family: Verdana;
		background-color: #bbb;
		text-align: center;
	}

	.search_profile {
		padding: 20px;
		text-align: left;
		width: 300px;
		margin: 10px auto;
		background-color: #eee;
		border-radius: 10px;
	}

	button {
		margin: 0 auto;
		float: center;
	}
	</style>
</head>
<body>
	<h1>Are you..</h1>
	<?php
	while($result = $q->fetchArray()) {
		echo "<div class=\"search_profile\">";
		echo "<b>" . $result['first_name'] . " " . $result['last_name'] . "</b><br/><br/>";
		echo "<span style=\"color: #444\">";
		echo preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $result['phone']) . "<br />";
		echo $result['email'] . "<br />";
		echo "</span>";
		echo "<form action=\"print.php\" method=\"post\"><input type=\"hidden\" name=\"id\" value=\"" . $result['id'] . "\">";
		echo "<input type=\"submit\" value=\"Print\" /></form>";
		echo "</div>";
	}
	?>
</body>
</html>
