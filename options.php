<?php
$db = new SQLite3('local_db.sql');
$id = $db->escapeString($_POST['id']);

$q = @$db->query('SELECT * FROM `users` WHERE `id`=' . $id);

$user = $q->fetchArray();
?>
<!DOCTYPE html>
<html>
<head>
	<title>SpringIn - Print Options</title>
	<style type="text/css">
	html {
		font-family: Verdana;
		font-size: 20pt;
	}
	input {
		font-size: 35pt;
		padding: 10px;
		border: 2px solid #dadada;
   		border-radius: 7px;
   		color: #ccc;
	}

	input:focus {
		color: #000;
		outline: none;
	    border-color: #9ecaed;
	    box-shadow: 0 0 10px #9ecaed;
	}

	input[type=text] {
		float: center;
		width: 100%;
	}

	input[type=checkbox] {
		height: 30pt;
		width: 30pt;
	}

	input[type=submit] {
		color: #000;
	}

	tr td:first-child {
		text-align: right;
	}

	tr td:last-child {
		width: 80%;
		padding: 5px 20px;
	}

	tr:last-child {
		width: auto;
	}

	tr:last-child td {
		width: auto;
		text-align: center;
	}

	table {
		margin: 0 auto;
		background-color: #ccc;
		padding: 15px;
		border-radius: 15px;
	}
	</style>
</head>
<body>
	<div>
		<form action="imageGen.php" method="post">
			<table>
				<tr>
					<td>Title</td>
					<td><input type="text" name="title">
					</td>
				</tr>
				<tr>
					<td>Employer</td>
					<td><input type="text" name="employer"></td>
				</tr>
				<tr>
					<td>Display Phone</td>
					<td><input type="checkbox" name="phone_display"></td>
				</tr>
				<tr>
					<td>Phone</td>
					<td><input type="text" name="phone" value="<?php echo $user['phone']; ?>"></td>
				</tr>
				<tr>
					<td>Display E-Mail</td>
					<td><input type="checkbox" name="email_display"></td>
				</tr>
				<tr>
					<td>E-Mail Address</td>
					<td><input type="text" name="email" value="<?php echo $user['email']; ?>"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="Update and Print"></td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>