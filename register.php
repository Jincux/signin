
<html>
	<head>
	</head>
	<body>
		<?php
		if(!isset($_GET['loggedout'])) {
		?>
		<iframe style="display:none;height:0;width:0;" src="http://www.techspringhealth.org/logout"></iframe>
		<script type="text/javascript">window.location.replace("register.php?loggedout=1");</script>
		<?php
		} else { ?>
		<iframe style="display:inline-block; border: none: padding: none; width:100%;height:90%; margin: 0 auto;" src="http://www.techspringhealth.org/springin_spash?splash=1">
		</iframe>
		<?php } ?>
		<h1 style="font-family: Verdana; margin: 0 auto; text-align:center;"><a style="text-decoration: none;" href="register_complete.php">When you're done, click here!</a></h1>
	</body>
</html>