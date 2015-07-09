<?php
$id = 1000000 + rand(0, 1000);
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = "";
$phone = "";

$db = new SQLite3('local_db.sql');
$q = @$db->query("INSERT INTO `users` (id, first_name, last_name, email, phone) VALUES ('" . $id . "','" . $first_name . "','" . $last_name . "','" . $email. "','" . $phone . "');");

header('Location: index.php');
?>