<?php
$db = new SQLite3('../local_db.sql');
$query = @$db->query("SELECT `uid`,`time` FROM `visits`");
$number_of_fields = $query->numColumns();
$first_field_name = $query->columnName(0);
//header('Content-Type: text/csv');
$first_row = true;
$fo = fopen("data.csv", "w");
fwrite($fo, "nationbuilder_id,timestamp,datetime\n");
while($row = $query->fetchArray(SQLITE3_NUM)) {
	fwrite($fo, '"' . stripslashes(implode('","',$row)) . "\",\"" . date("Y-m-d H:i:s",$row[1]) . "\"\n");
}
fclose($fo);

header('Location: data.csv');
?>