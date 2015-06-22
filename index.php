<!DOCTYPE html>
<html>
	<head>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<title>TechSpring Sign-in</title>
		<style type="text/css">
		body {
			font-family: Verdana;
			text-align: center;
			background-color: #bbb;
			height:100%;
			width:100%;
			margin: 0;
			padding: 0;
			
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
		<h1>SpringIn</h1>
		<h2>Welcome to TechSpring!</h2>		
		<div style="display:block; height: 100% width: 100%">
			<div id="search">
				<form id="form" action="search.php" method="post">
					<input name="name" style="height: 50px; width: 100%; font-size: 50px" type="text" /><br />
					<button onclick="javascript:document.getElementById('#form').submit();"id="button" style="height: 60px; margin-top:5px; padding: 5px; font-size: 40px" type="submit" />Sign-In</button>
				</form>
			</div>
		</div>
	</body>
</html>