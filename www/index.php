<!DOCTYPE html>
<html>
<head>
	<title>XSS Testing</title>
	<style type="text/css">
		body{
			font-family: monospace;
		}
		th > h3{
			text-align: center;
			text-decoration: underline;
		}
		td {
			vertical-align: top;
		}
		li {
			list-style-type: square;
		}
	</style>
</head>
<body>

	<h1>XSS Testing</h1>
	
	<hr>

	<table>
		<thead>
			<tr>
				<th>
					<h3>Reflected XSS</h3>
				</th>
				<th>
					<h3>Stored XSS</h3>
				</th>
				<th>
					<h3>CSP</h3>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					
					<ul>
						<li><a href="reflected/xss-reflected-1.php">Challenge 1</a></li>
						<li><a href="reflected/xss-reflected-2.php">Challenge 2</a></li>
						<li><a href="reflected/xss-reflected-3.php">Challenge 3</a></li>
						<li><a href="reflected/xss-reflected-4.php">Challenge 4</a></li>
						<li><a href="reflected/xss-reflected-5.html">Challenge 5</a></li>
						<li><a href="reflected/xss-reflected-6.html">Challenge 6</a></li>
					</ul>
				</td>
				<td>	
					<ul>
						<li><a href="stored/xss-stored-1.html">Challenge 1</a></li>
					</ul>
				</td>
				<td>
					<ul>
						<li><a href="CSP/xss-csp-1.php">Challenge 1</a></li>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>
	
	

</body>
</html>