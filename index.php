<!DOCTYPE html>
<html>
	<head>
		<title>TechSpring Sign-in</title>
		<style type="text/css">
		body {
			font-family: Verdana;
			text-align: center;
		}

		#search {
			display: inline-block;
			padding: none;
			margin: none;
			height: 50px;
			vertical-align: center;
			font-size: 50px;
			width: 75%;
			text-align: center;
		}

		#button {
			background-color: #00ff00;
			padding-left: none;
			width: 20%;
			margin: 0 auto;
			border-radius: 10px;
		}

		input {
			padding: 5px;
			margin: none;
			border: 1px solid #ccc;
			background-color: #ddd;
			border-radius: 10px;
		}
		</style>
	</head>
	<body>
		<h1>Welcome to TechSpring</h1>
		<p>I suck at formatting, so I'll do that later!</p>
		<div id="search">
			<form id="form" action="search.php" method="post">
				<input name="name" style="height: 50px; width: 100%; font-size: 50px" type="text" /><br />
				<button onclick="javascript:document.getElementById('#form').submit();"id="button" style="height: 60px; margin-top:5px; padding: 5px; font-size: 40px" type="submit" />Sign-In</button>
			</form>
		</div>
	</body>
</html>