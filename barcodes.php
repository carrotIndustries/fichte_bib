<html>
<head>
	<style type="text/css">
	body {
		font-family: "Nimbus Mono L", Courier;
		}
		@media print {
	.hid {
		display: none;
	}
}	
	</style>
</head>
	
<body onload="window.print()">
	<button class="hid" onclick="window.top.killpopup()">Schließen</button>
<table border="0px">
<?php 
require("functions.php");
$cols = 4;
$rows = 16;
$col = 0;
$row = 0;

while ($row < $rows) {
	echo "<tr>";
	$col = 0;
	while ($col < $cols) {
		$code = generatecode("4711");
	
		echo '<td><img src="ean-13.php?code=' . $code . '" /></td>';
		$col++;
	}
	echo "</tr>\n";
	$row++;
}
?>
</table>
</body>
</html>
