<!DOCTYPE html>
<html>
	<head>
		<title>SpringIn - Admin</title>
		<style type="text/css">
		/*1C263C 313C53 455268 B6C0D2 9CFF00 FFFFFF*/
		html {
			font-family: Verdana;
			background-color: #1C263C;
		}

		#content {
			padding: 15px;
			width: 800px;
			margin: 0 auto;
			background-color: #B6C0D2;
		}
		</style>
		<script type="text/javascript">
		document.addEventListener('click', function(event) {
		  var target = event.target;
		  if (target.tagName.toLowerCase() == 'a')
		  {
		      var port = target.getAttribute('href').match(/^:(\d+)(.*)/);
		      if (port)
		      {
		         target.href = port[2];
		         target.port = port[1];
		      }
		  }
		}, false);
		</script>
	</head>
	<body>
		<div id="content">
			<h1>SpringIn - Administrator Page</h1>
			<p>Developed by <a href="mailto:devon.endicott@gmail.com">Devon Endicott</a> and Alejandro Colon<br />
			All technical documentation can be found <a href="https://github.com/Jincux/signin/blob/master/README.md">here</a></p>
			<hr />
			<h3>Today's events:</h3>
			<table style="width: 100%">
				<tr>
					<th>Title</th>
					<th>Time</th>
					<th>RSVPs</th>
					<th>Options</th>
				</tr>
				<?php
				$db = new SQLite3('../local_db.sql');
				$events = @$db->query("SELECT * FROM `events`  WHERE DATE(start_time) = DATE() ORDER BY start_time ASC");
				$any = false;
				while($event = $events->fetchArray()) {
					$any = true;
					$startTime = strtotime($event['start_time']);
					$endTime = strtotime($event['end_time']);
				?>
				<tr>
					<td><?php echo $events['name'] ?></td>
					<td><?php echo date("H:i", $startTime) ?></td>
					<td></td>
					<td><a href="printEvent.php?id=1234">Print All Tags</a></td>
				</tr>
				<?php
				}

				if(!$any) {
					echo "<tr><td colspan=\"4\" style=\"text-align: center;\">No events today!</td></tr>";
				}
				?>
			</table>
			<hr />
			<h3>Settings</h3>
			<a href=":631/">CUPS Control Panel</a> - Use this to control printers connected to host device.
			<h4>API Token</h4>
			<form action="update.php" method="post">
				<input type="hidden" name="update" value="token">
				<input style="width: 500px" type="text" name="token" value="<?php echo trim(file_get_contents('../config/token.txt')); ?>">
				<input type="submit" value="Update">
			</form>
			<h4>Printer ID (slug on CUPS)</h4> 
			<form action="update.php" method="post">
				<input type="hidden" name="update" value="printer">
				<input style="width: 500px" type="text" name="printer" value="<?php echo trim(file_get_contents('../config/printer.txt')); ?>">
				<input type="submit" value="Update">
			</form>
		</div>
	</body>
</html>