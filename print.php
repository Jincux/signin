<?php
$db = new SQLite3('local_db.sql');
$id = $db->escapeString($_POST['id']);

$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);
$user = $q->fetchArray();

//header("Content-Type: image/png");
//echo realpath('.');
//putenv('GDFONTPATH=' . realpath('.'));

if(isset($_POST['from_options'])) {
	include 'imageGen.php';
} else {
	$visit = @$db->query('SELECT * FROM `visits` WHERE `uid`=' . $id . ' ORDER BY `time` DESC LIMIT 1;');
	if(($lastVisit = $visit->fetchArray()) === false) {
		header('Location: options.php?id=' . $id);
		return;
	} else {
		$infoText = $lastVisit['info'];
	}
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
	imagettftext($im, 25, 0, 35, 170, $text_color2, $font, $infoText);

	imagesavealpha($im, false);
	imagepng($im, 'temp.png');

	imagedestroy($im);
	imagedestroy($logo);
}


$printerName = trim(file_get_contents('config/printer.txt'));
$options = "-o InputSlot=Left"; //prints off the left spool

//$ret = shell_exec("lpr " . $options . " -P \"" . $printerName . "\" " .  realpath('.') . "/temp.png");

//log in the database the the vistor was here
@$db->query("INSERT INTO `visits` (uid, time, info) VALUES ('" . $id . "', '" . time() . "', '" . $db->escapeString($infoText) . "')");
echo $db->lastErrorMsg();
//let's give out guest a nice, warm welcome
$db->close();
?>

<html>
	<head>
		<title>Welcome!</title>
		<meta http-equiv="refresh" content="5;URL='/'" />
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
