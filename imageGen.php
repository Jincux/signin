<?php
$id = $db->escapeString($_POST['id']);

$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);
$user = $q->fetchArray();

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








//header("Content-Type: image/png");
//echo realpath('.');
//putenv('GDFONTPATH=' . realpath('.'));
$font = './Verdana.ttf';

$logo = imagecreatefrompng('logo.png');

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

//$user = array('first_name'=>'Devon', 'last_name'=>'Endicott');
imagettftext($im, 40, 0, 35, 110, $text_color, $font, $user['first_name'] . " " . $user['last_name']);
imagettftext($im, 25, 0, 35, 170, $text_color2, $font, $infoText);

imagesavealpha($im, false);
imagepng($im, 'temp.png');

imagedestroy($im);
imagedestroy($logo);