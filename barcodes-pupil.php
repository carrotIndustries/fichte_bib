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
$col = 0;
$row = 0;
$query = "SELECT firstname,lastname, card_id FROM pupils where deleted=0 ORDER BY lastname asc";
$result = mysql_fetch_superarray(mysql_query($query));
$i = 0;
//print_r($result);
while ($row < count($result)/$cols) {
	echo "<tr>";
	$col = 0;
	while (($col < $cols) && $i<count($result)) {
		
		$code = $result[$i]["card_id"];
		
		echo '<td><img src="ean-13.php?code=' . $code . '" /><br />';
		echo $result[$i]["firstname"];
		echo " ";
		echo $result[$i]["lastname"];
		echo "<br /><br /></td>";
		$i++;
		$col++;
	}
	echo "</tr>\n";
	$row++;
}
?>
</table>
</body>
</html>
