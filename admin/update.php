<?php
header('Location: index.php');
if(isset($_POST['update'])) {
	if($_POST['update'] == "printer") {
		$fo = fopen(dirname(__DIR__) . '/config/printer.txt', "w");
		fwrite($fo, $_POST['printer']);
	} if($_POST['update'] == "token") {
		$fo = fopen(dirname(__DIR__) . '/config/token.txt', "w");
		fwrite($fo, $_POST['token']);
	}
}
?>