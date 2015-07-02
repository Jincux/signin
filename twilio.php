<?php
require_once dirname(__FILE__) . '/twilio/Services/Twilio.php';
function doTwilio($number = 0) {
	try {
		$sid = "AC9814f36482cc4b68d08d5c7e5fa03185"; 
		$token = "10b77ca60b44eba1b7116d70aace4c6b"; 
		$client = new Services_Twilio($sid, $token);

		/*if($number == 0) { //testing
			$alex = "+14139499697";
			$devon = "+14074632016";

			$number = $alex;
		}*/

		$sms = $client->account->sms_messages->create("+14136501650",
		 $number,
		 "Welcome to TechSpring!\n\nFeel free to grab a cup of coffee and hop on our wifi!\nSSID: TechSpring\nPass: baystate",
		 array());

		// $sms->sid;
	} catch (Exception $e) {
		
	}
}

if(isset($_GET['ind'])) {
	doTwilio();
}
?>