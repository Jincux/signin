<!DOCTYPE html>
<html>
	<head>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta charset="utf-8">
		<title>TechSpring Sign-in</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<style type="text/css">
		body {
			font-family: Verdana;
			text-align: center;
			background-color: #B6C0D2;
			height:100%;
			width:100%;
			margin: 0;
			padding: 0;
			color: #1C263C;
			max-height: 100%
			
		}

		#search {
			display: inline-block;
			padding: none;
			margin: auto 0;
			height: 50px;
			font-size: 50px;
			width: 75%;
			text-align: center;
		}

		/*1C263C	313C53	455268	B6C0D2	9CFF00	FFFFFF*/

		#button {
			background-color:  #9CFF00;
			padding-left: none;
			width: 20%;
			margin: 0 auto;
			border-radius: 10px;
			display:block;
			height: 60px;
			margin-top:5px;
			padding: 5px;
			font-size: 40px;
		}

		input {
			padding: 5px;
			margin: none;
			/*border: 1px solid #455268;*/
			background-color: #6699FF;
			border-radius: 10px;

			box-shadow: inset 0px 0px 5px 0px #666;
			-webkit-box-shadow: inset 0px 0px 5px 0px #666;
			-moz-box-shadow: inset 0px 0px 5px 0px #666;
			-o-box-shadow: inset 0px 0px 5px 0px #666;
		}

		#events {
			background-color: #FFFFFF;
			box-shadow: inset 0px 0px 10px 0px #666;
			-webkit-box-shadow: inset 0px 0px 10px 0px #666;
			-moz-box-shadow: inset 0px 0px 10px 0px #666;
			-o-box-shadow: inset 0px 0px 10px 0px #666;
			margin: 10px;
			border-radius: 10px;
			padding: 10px;
			overflow-y: scroll;
			text-align: left;
			font-size: 14pt;
			height: 250px;
		}

		table > tbody > tr {
			display: block;
			border-bottom: 1px solid #9CFF00;
		}

		table > tbody > tr:last-child {
			border-bottom: none;
		}

		table > tbody > tr > td {
			text-align: left;
			vertical-align: top;
		}
		</style>
	</head>
	<body>
		<h1>SpringIn</h1>
		<h2>Welcome to TechSpring!</h2>		
		<div style="display:block; width: 100%; height:150px">
			<div id="search">
				<form id="form" action="search.php" method="post">
					<input name="name" style="height: 50px; width: 100%; font-size: 50px" type="text" /><br />
					<button onclick="javascript:document.getElementById('#form').submit();"id="button" type="submit" />Sign-In</button>
				</form>
			</div>
		</div>
		<br />
		<hr />
		<h1>Events Today</h1>
		<div style="display:inline-block;float:left;width:50%">
			<div id="events" style="padding: 15px">
				<table>
					<tbody>
						<?php
						$db = new SQLite3('local_db.sql');
						$events = @$db->query("SELECT * FROM `events`  WHERE DATE(start_time) = DATE() ORDER BY start_time ASC");
						$any = false;
						while($event = $events->fetchArray()) {
							$any = true;
							$startTime = strtotime($event['start_time']);
							$endTime = strtotime($event['end_time']);
						?>
						<tr>
							<td style="text-align: center;"><b><?php echo date("g:i a", $startTime) . "<br />-<br />" . date("g:i a", $endTime);?></b></td>
							<td>
								<p>
									<b><?php echo $event['name']; ?></b><br />
									<br />
									<?php echo $event['description']; ?>
								</p>
							</td>
						</tr>
						<?php
						}

						if($any === false) {
							?>
						<tr>
							<td colspan="2" style="text-align: center; font-size: 30px">No Events Today!</td>
						</tr>
							<?php
						}
						?>

					</tbody>
				</table>
			</div>
		</div>
		<div style="display:inline-block;float:right;width:50%">
			<div id="events" style="padding: 15px;">
				<?php
				$events->reset();
				while($event = $events->fetchArray()) {
					$rsvpRes = @$db->query("SELECT * FROM `rsvp` WHERE eid=" . $event['eid']);
					$attendees = array();
					while($rsvp = $rsvpRes->fetchArray()) {
						$personRes = @$db->query("SELECT * FROM `users` WHERE id=" . $rsvp['uid']);
						$attendees[] = $personRes->fetchArray();
					}

					foreach($attendees as $attendee) {
					?>
					<div style="margin: 5px; padding: 20px; background-color: #6699FF; box-shadow:0px 0px 10px #000;padding:25px;border-radius:5px;">
						<b><?php echo $attendee['first_name'] . ' ' . $attendee['last_name']; ?></b><br />
						<?php echo $event['name'];?>
						<div style="float: right">
							<form action="print.php" method="post">
								<input type="hidden" name="id" value="<?php echo $attendee['id']; ?>" />
								<input type="hidden" name="event" value="<?php echo htmlspecialchars($event['name']); ?>" />
								<input type="submit" value="Quick Print" />
							</form>
						</div>
					</div>
					<?php
					}
				}
				?>
			</div>
		</div>
	</body>
</html>