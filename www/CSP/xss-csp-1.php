<?php
	function generateNonce() {
		$b = openssl_random_pseudo_bytes(32);
		$n = base64_encode($b);
		return $n;
	}

	$nonce = generateNonce();

	header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-eval' 'nonce-".$nonce."'; ");
?>
<!DOCTYPE html>
<html>
<head>
	<title>XSS Reflected Challenge 1</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<h1>XSS Reflected Challenge 1</h1>
	<div id="img-box"><?php if (isset($_REQUEST['url'])) { echo "<img src=\"".$_REQUEST['url']."\">"; } ?></div><br/>
	<form method="GET">
		<input type="text" name="url" autocomplete="false" placeholder="Enter your Image URL">
		<input type="submit" value="Show Image">
	</form>
	<!-- <script type="text/javascript" src="scripts/csp1.js"></script> -->
	<script type="text/javascript" nonce="<?php echo $nonce; ?>">
		// Just learnt CSP!
		console.log("Hello From CSP!");
	</script>
</body>
</html>