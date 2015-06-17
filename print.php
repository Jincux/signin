<?php
if ($db = new SQLite3('local_db.sql')) {
    $q = @$db->query('CREATE TABLE IF NOT EXISTS users (id int, first_name varchar(32), last_name varchar(32), email varchar(64), phone varchar(11), PRIMARY KEY (id))');
}
$id = $db->escapeString($_POST['id']);

$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);
$user = $q->fetchArray();



header("Content-Type: image/png");
$im = @imagecreate(280, 180)
    or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 255, 255, 255);
$text_color = imagecolorallocate($im, 0, 0, 0);
$text_color2 = imagecolorallocate($im, 50, 50, 50);
imagestring($im, 5, 5, 5,  $user['first_name'] . " " . $user['last_name'], $text_color);
imagestring($im, 5, 10, 30,  $user['email'], $text_color);
imagestring($im, 5, 10, 50,  $user['phone'], $text_color);
imagepng($im);
imagedestroy($im);

return;
$printerName = "Idunno";
$ret = shell_exec("lpr -P \"" . $printerName . "\" temp.png");
//$ret = shell_exec("lpr print.php");
if($ret === NULL) {
	echo "An error has occured!";
}
echo $ret;
?>