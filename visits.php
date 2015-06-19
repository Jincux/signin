<?php
$db = new SQLite3('local_db.sql');
$res = @$db->query("SELECT * FROM `visits`");

$cachedUsers = array();
while($visit = $res->fetchArray()) {
	if(!isset($cachedUsers[$visit['uid']])) {
		$userRes = @$db->query("SELECT * FROM `users` WHERE id=" . $visit['uid']);
		$cachedUsers[$visit['uid']] = $userRes->fetchArray();
	}

	$userInfo = $cachedUsers[$visit['uid']];

	echo $userInfo['first_name'] . " " . $userInfo['last_name'] . " was here at " . date("F j, Y, g:i a", $visit['time']) . "<br />";
}
?>