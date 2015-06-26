<?php
$db = new SQLite3('../local_db.sql');
$query = @$db->query("SELECT `uid`,`time` FROM `visits`");
$number_of_fields = $query->numColumns();
$first_field_name = $query->columnName(0);
header('Content-Type: text');
$first_row = true;
echo "nationbuilder_id,timestamp\n";
while($row = $query->fetchArray(SQLITE3_NUM)) {
    print '"' . stripslashes(implode('","',$row)) . "\"\n";
}
?>