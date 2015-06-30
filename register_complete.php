<?php
header('Location: index.php');
$inhibitLoad = true;
include 'update_database.php';

getPeople(1); //loads the latest 10
?>