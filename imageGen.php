<?php


function printWithOptions($id) {
	global $_POST, $db, $infoText;
	if(!isset($db)) {
		$db = new SQLite3('local_db.sql');
	}

	$id = $db->escapeString($id);

	$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);
	$user = $q->fetchArray();
	//

	if(isset($_GET['debug'])) {
		$q = @$db->query('SELECT * FROM `users` WHERE `first_name` = \'Devon\'');
	} else {
		$id = $db->escapeString($_POST['id']);
		$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);
	}
	$user = $q->fetchArray();
	$id = $user['id'];

	//log in the database the the vistor was here
	//@$db->query("INSERT INTO `visits` (uid, time) VALUES ('" . $id . "', '" . time() . "')");






	$infoText = "";

	if(isset($_POST['title'])) {
		$infoText = $infoText . $_POST['title'] . "\n";
	}

	if(isset($_POST['employer'])) {
		$infoText = $infoText . $_POST['employer'] . "\n";
	} 

	$infoText = $infoText . "\n";

	if(isset($_POST['phone']) && isset($_POST['phone_display'])) {
		$infoText = $infoText . preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $user['phone']) . "\n";
	} 

	if(isset($_POST['email']) && isset($_POST['email_display'])) {
		$infoText = $infoText . $_POST['email'] . "\n";
	} 

	if(isset($_POST['event'])) {
		$printText = $_POST['event'] . "\n" . $infoText;
	} else {
		$printText = $infoText;
	}



	include 'phpqrcode/phpqrcode.php';
	unlink('resources/qr.png');
	unlink('temp.png');
	QRcode::png($id, 'resources/qr.png');
	$qrImage = imagecreatefrompng('resources/qr.png');



	if(isset($_GET['print'])) {
		header("Content-Type: image/png");
	} else {
		echo $id;
	}
	//echo realpath('.');
	//putenv('GDFONTPATH=' . realpath('.'));
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
	imagecopyresized($im, $qrImage, 0, 0, 0, 0, 70, 70, 87, 87);
	//imagecopyresized(dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)

	//$user = array('first_name'=>'Devon', 'last_name'=>'Endicott');
	imagettftext($im, 40, 0, 35, 110, $text_color, $font, $user['first_name'] . " " . $user['last_name']);
	imagettftext($im, 25, 0, 35, 170, $text_color2, $font, $printText);

	imagerotate($im, 180, $background_color);

	imagesavealpha($im, false);
	if(isset($_GET['debug'])) {
		imagepng($im);
	} else {
		imagepng($im, 'temp.png');
	}

	imagedestroy($im);
	imagedestroy($logo);
}

function printFromOptions($id) {
	global $db, $infoText, $_POST;

	if(!isset($db)) {
		$db = new SQLite3('local_db.sql');
	}

	$id = $db->escapeString($id);

	$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);
	$user = $q->fetchArray();

	$visit = @$db->query('SELECT * FROM `visits` WHERE `uid`=' . $id . ' ORDER BY `time` DESC LIMIT 1;');
	if(($lastVisit = $visit->fetchArray()) === false) {
		if(isset($_POST['event'])) {
			header('Location: options.php?id=' . $id . "&event=" . htmlspecialchars($_POST['event']));
		} else {
			header('Location: options.php?id=' . $id);
		}
		return;
	} else {
		$infoText = $lastVisit['info'];
	}

	include 'phpqrcode/phpqrcode.php';
	unlink('resources/qr.png');
	unlink('temp.png');
	QRcode::png($id, 'resources/qr.png');
	$qrImage = imagecreatefrompng('resources/qr.png');

	if(isset($_POST['event'])) {
		echo $_POST['event'];
		$printText = $_POST['event'] . "\n" . $infoText;
	} else {
		$printText = $infoText;
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
	imagecopyresized($im, $qrImage, 0, 0, 0, 0, 70, 70, 87, 87);

	imagettftext($im, 40, 0, 35, 110, $text_color, $font, $user['first_name'] . " " . $user['last_name']);
	imagettftext($im, 25, 0, 35, 170, $text_color2, $font, $printText);

	imagerotate($im, 180, $background_color);

	imagesavealpha($im, false);
	imagepng($im, 'temp.png');

	imagedestroy($im);
	imagedestroy($logo);
}