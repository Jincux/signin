<?php
if ($db = new SQLite3('local_db.sql')) {
    $q = @$db->query('CREATE TABLE IF NOT EXISTS users (id int, first_name varchar(32), last_name varchar(32), email varchar(64), phone varchar(11), PRIMARY KEY (id))');
}
$id = $db->escapeString($_POST['id']);

$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);
$user = $q->fetchArray();

//log in the database the the vistor was here
@$db->query("INSERT INTO `visits` (uid, time) VALUES ('" . $id . "', '" . time() . "')");

//header("Content-Type: image/png");
//echo realpath('.');
//putenv('GDFONTPATH=' . realpath('.'));

if(isset($_POST['from_options'])) {
	include 'imageGen.php';
} else {
	$font = 'resources/Verdana.ttf';

	$logo = imagecreatefrompng('resources/logo.png');

	$width = 280*2;
	$height = 180*2;

	$im = @imagecreatetruecolor($width, $height)
	    or die("Cannot Initialize new GD image stream");

	$background_color = imagecolorallocate($im, 255, 255, 255);
	$text_color = imagecolorallocate($im, 0, 0, 0);
	$text_color2 = imagecolorallocate($im, 100, 100, 100);

	//imageantialias($im, true);
	imagefill($im, 0, 0, $background_color);

	$tb = imagettfbbox(20, 0, $font, 'TechSpring'); //solution found on PHP forums for centering text
	$x = ceil(($width - $tb[2]) / 2);

	imagettftext($im, 25, 0, $x, 50, $text_color2, $font, "TechSpring");
	//imagesavealpha($im, true);
	//imagealphablending($im, false);
	imagecopyresized($im, $logo, $x - 60, -5, 0, 0, 75, 75, 393, 413);

	imagettftext($im, 40, 0, 35, 110, $text_color, $font, $user['first_name'] . " " . $user['last_name']);
	imagettftext($im, 25, 0, 35, 190, $text_color2, $font, preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $user['phone']));
	imagettftext($im, 25, 0, 35, 230, $text_color2, $font, $user['email']);

	imagesavealpha($im, false);
	imagepng($im, 'temp.png');

	imagedestroy($im);
	imagedestroy($logo);
}
//return;

//header('Location: index.php');
$printerName = trim(file_get_contents('config/printer.txt'));
$options = "-o InputSlot=Left"; //prints off the left spool
//echo "lpr " . $options . " -P \"" . $printerName . "\" " .  realpath('.') . "/temp.png";
$ret = shell_exec("lpr " . $options . " -P \"" . $printerName . "\" " .  realpath('.') . "/temp.png");

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
		<p><?php echo file_get_contents('config/welcome_message.txt'); ?></p>
		<hr/>
		<p style="font-size: 8pt">The page should redirect within 5 seconds. If it doesn't, <a href="index.php">click here</a></p>
	</body>
</html>
