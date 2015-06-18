<?php
if ($db = new SQLite3('local_db.sql')) {
    $q = @$db->query('CREATE TABLE IF NOT EXISTS users (id int, first_name varchar(32), last_name varchar(32), email varchar(64), phone varchar(11), PRIMARY KEY (id))');
}
$id = $db->escapeString($_POST['id']);

$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);
$user = $q->fetchArray();



//header("Content-Type: image/png");
//echo realpath('.');
//putenv('GDFONTPATH=' . realpath('.'));
$font = './Verdana.ttf';

$im = @imagecreate(280*2, 180*2)
    or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 255, 255, 255);
$text_color = imagecolorallocate($im, 0, 0, 0);
$text_color2 = imagecolorallocate($im, 100, 100, 100);
//imagestring($im, 5, 5, 5,  $user['first_name'] . " " . $user['last_name'], $text_color);
//imagestring($im, 5, 10, 30,  $user['email'], $text_color);
//imagestring($im, 5, 10, 50,  $user['phone'], $text_color);
//imagettftext(image, size, angle, x, y, color, fontfile, text)
imagettftext($im, 40, 0, 15, 60, $text_color, $font, $user['first_name'] . " " . $user['last_name']);
imagettftext($im, 30, 0, 15, 120, $text_color2, $font, preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $user['phone']));
imagettftext($im, 30, 0, 15, 160, $text_color2, $font, $user['email']);
imagepng($im, 'temp.png');
imagedestroy($im);

//header('Location: index.php');
$printerName = file_get_contents('printer.txt');
$ret = shell_exec("lpr -P \"" . $printerName . "\" " .  realpath('.') . "/temp.png");
//$ret = shell_exec("lpr print.php");
if($ret === NULL) {
	echo "An error has occured!";
}
echo $ret;

//let's give out guest a nice, warm welcome
?>

<html>
	<head>
		<title>Welcome</title>
		<style type="text/css">
		body {
			font-family: Verdana;
			background-color: #bbb;
			text-align: center;
		}

		b {
			color: #2d2d99;
		}
		</style>
	</head>
	<body>
		<h1>Welcome to TechSpring, <b><?php echo $user['first_name'] ?></b></h1>
		<p><?php echo file_get_contents('welcome_message.txt'); ?></p>
		<hr/>
		<p style="font-size: 8pt">The page should redirect within 5 seconds. If it doesn't, <a href="index.php">click here</a></p>
	</body>
</html>
