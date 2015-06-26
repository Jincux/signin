<?php
$db = new SQLite3('local_db.sql');

//header("Content-Type: image/png");
//echo realpath('.');
//putenv('GDFONTPATH=' . realpath('.'));

include 'imageGen.php';
if(isset($_POST['from_options'])) {
	printWithOptions($_POST['id']);
} else {
	printFromOptions($_POST['id']);
}


$printerName = trim(file_get_contents('config/printer.txt'));
$options = "-o InputSlot=Left"; //prints off the left spool

$ret = shell_exec("lpr " . $options . " -P \"" . $printerName . "\" " .  realpath('.') . "/temp.png");

$id = $db->escapeString($_POST['id']);

$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);
$user = $q->fetchArray();

//log in the database the the vistor was here
@$db->query("INSERT INTO `visits` (uid, time, info) VALUES ('" . $id . "', '" . time() . "', '" . $db->escapeString($infoText) . "')");
//let's give out guest a nice, warm welcome
$db->close();
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
