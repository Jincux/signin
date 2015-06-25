<?php

function pullUrl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}

$token = trim(file_get_contents('config/token.txt'));

//fetch initial listing, primarley to find the page count (defined as total_pages)
$mainPage = pullUrl("https://techspring.nationbuilder.com/api/v1/sites/v2/pages/events?starting=2015-06-24&access_token=" . $token . "");
$obj = json_decode($mainPage);
//echo "<pre>" . json_encode($obj, JSON_PRETTY_PRINT) . "</pre>";

if ($db = new SQLite3('local_db.sql')) {
	$q = @$db->query('CREATE TABLE IF NOT EXISTS events (eid INTEGER, start_time TEXT, end_time TEXT, name TEXT, description TEXT, PRIMARY KEY(eid))');
	$q = @$db->query('CREATE TABLE IF NOT EXISTS rsvp (eid INTEGER, uid INTEGER)');
}


foreach($obj->results as $eventObj) {
	echo "<br/>$eventObj->id - $eventObj->name - $eventObj->start_time - $eventObj->end_time - $eventObj->intro <br />";
	$startTime = strtotime($eventObj->start_time);
	$endTime = strtotime($eventObj->end_time);
	echo "Date: " . date("l, F jS", $startTime);
	echo "<br />Time:" . date("g:i a", $startTime) . " - " . date("g:i a", $endTime) . "<br />";
	@$db->query("INSERT OR IGNORE INTO `events` (eid, start_time, end_time, name, description) VALUES " . 
		"('" . $eventObj->id . "'," .
		"'" . $eventObj->start_time . "'," . 
		"'" . $eventObj->end_time . "'," .
		"'" . $eventObj->name . "'," . 
		"'" . $eventObj->intro . "');");
}

$eventRes = @$db->query("SELECT * FROM `events`");

while($event = $eventRes->fetchArray()) {
	$json = pullUrl("https://techspring.nationbuilder.com/api/v1/sites/v2/pages/events/" . $event['eid'] . "/rsvps?limit=10&__proto__=&access_token=" . $token);
	$rsvpData = json_decode($json);
	foreach($rsvpData->results as $rsvp) {
		@$db->query("INSERT OR IGNORE INTO `rsvp` (eid, uid) VALUES ('" . $event['eid'] . "','" . $rsvp->person_id . "')");
	}
}