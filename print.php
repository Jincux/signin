<?php
$db = new SQLite3('local_db.sql');

//header("Content-Type: image/png");
//echo realpath('.');
//putenv('GDFONTPATH=' . realpath('.'));
echo "including image gen";
include 'imageGen.php';
if(isset($_POST['from_options'])) {
	echo "with options";
	printWithOptions($_POST['id']);
} else {
	echo "from options";
	if(!printFromOptions($_POST['id'])) {
		echo "returning";
		return;
	}
}

$printerName = trim(file_get_contents('config/printer.txt'));
$options = "-o InputSlot=Left"; //prints off the left spool

$ret = shell_exec("lpr " . $options . " -P \"" . $printerName . "\" " .  realpath('.') . "/temp.png");

$id = $db->escapeString($_POST['id']);

echo "getting user";
$q = $db->query('SELECT * FROM `users` WHERE `id`=' . $id);
echo $db->lastErrorMsg();
$user = $q->fetchArray();


if(isset($user['phone'])) {
	require 'twilio.php';
	doTwilio($user['phone']);
}

echo "inserting..";
//log in the database the the vistor was here
$db->query("INSERT INTO `visits` (uid, time, info) VALUES ('" . $id . "', '" . time() . "', '" . $db->escapeString(json_encode($optionsObject)) . "')");
//let's give out guest a nice, warm welcome
echo $db->lastErrorMsg();
$db->close();
echo "inserted";
?>

<html>
	<head>
		<title>Welcome!</title>
		<meta http-equiv="refdresh" content="5;URL='/'" />
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style type="text/css">
		body {
			font-family: Verdana;
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
