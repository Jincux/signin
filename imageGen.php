<?php
if(!isset($db)) {
	$db = new SQLite3('local_db.sql');
}
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
	$infoText = $infoText . $_POST['phone'] . "\n";
} 

if(isset($_POST['email']) && isset($_POST['email_display'])) {
	$infoText = $infoText . $_POST['email'] . "\n";
} 



include 'phpqrcode/phpqrcode.php';
QRcode::png($id);
$qrImage = imagecreatefrompng('resources/qr.png');



if(isset($_GET['debug'])) {
	header("Content-Type: image/png");
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
imagecopyresized($im, $qrImage, 0, 0, 0, 0, 60, 60, 100, 100);
//imagecopyresized(dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)

//$user = array('first_name'=>'Devon', 'last_name'=>'Endicott');
imagettftext($im, 40, 0, 35, 110, $text_color, $font, $user['first_name'] . " " . $user['last_name']);
imagettftext($im, 25, 0, 35, 170, $text_color2, $font, $infoText);

imagesavealpha($im, false);
if(isset($_GET['debug'])) {
	imagepng($im);
} else {
	imagepng($im, 'temp.png');
}

imagedestroy($im);
imagedestroy($logo);