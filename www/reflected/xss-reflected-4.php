<?php

	header("X-XSS-Protection: 0");

	include "removexss.php";

	$xss_filter = new TikiFilter_PreventXss();

	if (isset($_REQUEST['source'])) {
		$source = file_get_contents(__FILE__);
		$source = highlight_string($source, true);
		die($source);
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>XSS Reflected Challenge 4</title>
	<style type="text/css">
		body{
			font-family: monospace;
		}
	</style>
</head>
<body>
	<h1>XSS Reflected Challenge 4</h1>
	<?php if (isset($_REQUEST['name'])) { echo "<b>Hello ".$xss_filter->filter($_REQUEST['name'])."</b><br>"; } ?>
	<form method="GET">
		<input type="text" name="name" autocomplete="false" placeholder="Enter your name">
		<input type="submit" value="Say Hello">
	</form>
	<!-- navigate to ?source to get the source -->
	<!-- navigate to removexss.php?source to get the source -->
</body>
</html>