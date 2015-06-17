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
</head>
<body>
	<h1>You could be...</h1>
	<?php echo $list;?>
</body>
</html>
