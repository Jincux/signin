<!DOCTYPE html>
<html>
<head>
	<title>SpringIn - Admin</title>
</head>
<body>
	<h1>SpringIn - Administrator Page</h1>
	<p>Developed by <a href="mailto:devon.endicott@gmail.com">Devon Endicott</a> and Alejandro Colon<br />
	All technical documentation can be found <a href="https://github.com/Jincux/signin/blob/master/README.md">here</a></p>
	<hr />
	<h3>Today's events:</h3>
	<table>
		<tr>
			<th>Title</th>
			<th>Time</th>
			<th>RSVPs</th>
			<th>Options</th>
		</tr>
		<tr>
			<td>Investor Meeting</td>
			<td>12:00 PM</td>
			<td>15</td>
			<td><a href="printEvent.php?id=1234">Print All Tags</a></td>
		</tr>
	</table>
	<hr />
	<h3>Settings</h3>
	<a href="localhost:631">CUPS Control Panel</a> - (NOT FUNCTIONAL: VISIT PORT :631) Use this to control printers connected to host device.
	<h4>API Token</h4>
	<form action="update.php" method="post">
		<input type="hidden" name="update" value="token">
		<input style="width: 500px" type="text" name="token" value="<?php echo trim(file_get_contents('../config/token.txt')); ?>">
		<input type="submit" value="Update">
	</form>
	<h4>Printer ID (slug on CUPS)</h4> 
	<form action="update.php" method="post">
		<input type="hidden" name="update" value="printer">
		<input style="width: 500px" type="text" name="token" value="<?php echo trim(file_get_contents('../config/printer.txt')); ?>">
		<input type="submit" value="Update">
	</form>
</body>
</html>