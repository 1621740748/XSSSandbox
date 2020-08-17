<?php

	header("X-XSS-Protection: 0");

	function xss_filter($data) {
		$bad = array('script', 'iframe');
		$filtered = strtolower($data);
		$filtered = str_replace($bad, "badword", $filtered);

		$regex_pat = "/[\"\']\w+[\"\']/i";
		$regex_pat2 = "/on\w+\=\w+[\s()>]/i";
		
		$filtered = preg_replace($regex_pat, "BAD", $filtered);
		$filtered = preg_replace($regex_pat2, "BAD", $filtered);

		return $filtered;
	}

	if (isset($_REQUEST['source'])) {
		$source = file_get_contents(__FILE__);
		$source = highlight_string($source, true);
		die($source);
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>XSS Reflected 3</title>
</head>
<body>
	<h1>XSS Reflected 3</h1>
	<?php if (isset($_REQUEST['name'])) { echo "<b>Hello ".xss_filter($_REQUEST['name'])."</b><br>"; } ?>
	<form method="GET">
		<input type="text" name="name" autocomplete="false" placeholder="Enter your name">
		<input type="submit" value="Say Hello">
	</form>
	<!-- navigate to ?source to get the source -->
</body>
</html>