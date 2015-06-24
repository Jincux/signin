<!DOCTYPE html>
<html>
	<head>
		<meta name="apple-mobile-web-app-capable" content="yes">
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
		}

		input {
			padding: 5px;
			margin: none;
			border: 1px solid #455268;
			background-color: #6699FF;
			border-radius: 10px;

			box-shadow: inset 0px 0px 5px 0px #666;
			-webkit-box-shadow: inset 0px 0px 5px 0px #666;
			-moz-box-shadow: inset 0px 0px 5px 0px #666;
			-o-box-shadow: inset 0px 0px 5px 0px #666;
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