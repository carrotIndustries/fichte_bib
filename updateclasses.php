<?php
require("functions.php");
$pupils = mysql_fetch_superarray(mysql_query("SELECT * from pupils"));
$confirm = FALSE;
if(array_key_exists("confirm", $_GET)) {
		$confirm = TRUE;
}
?>
<html>
<head>
<script type="text/javascript" src="std.js">
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onload="">
<?php require("header.php");?>
Klassen Aktualisieren
<hr />
<button onclick="location.href='?confirm=1';">Passt schon</button>
<?php
if($confirm) {
	?>
	Klassen Aktualisiert. Bitte nicht neuladen oder auf 'passt schon' klicken!
	<button onclick="location.href='listpupils.php';">Schüler auflisten</button>
	<?php } ?>
<table class="listernx">
<tr class="head"><td>Name</td><td>Alte Klasse</td><td>Neue Klasse</td></tr>
<?php
foreach($pupils as $x) {
	echo "<tr>";
	$txt=$x["class"];
	if(strtolower($txt) != "leh") {
		echo "<td>";
			echo $x["firstname"] . " " . $x["lastname"];
			echo "</td>";
			
			echo "<td>";
			echo $txt;
			echo "</td>";
		if((ord($txt[0]) >= 49) && (ord($txt[0]) <= 57)) {
			
			$re1='(\\d+)';	# Integer Number 1

			if ($c=preg_match_all ("/".$re1."/is", $txt, $matches)) {
				$i=intval($matches[1][0]);
			}
			
			$re1='.*?';	# Non-greedy match on filler
			$re2='([a-z])';	# Any Single Word Character (Not Whitespace) 1

			if ($c=preg_match_all ("/".$re1.$re2."/is", $txt, $matches)) {
				$w=$matches[1][0];
			}
			
			
			
			echo "<td>";
			echo $i+1 . $w;
			if($confirm) {
				mysql_query("UPDATE pupils set class='" . ($i+1) .   $w . "' WHERE id=" . $x["id"]);
			}
			echo "</td>";
			
			
		}
		else {
			echo "<td>";
			echo "f!xme-" . $txt;
			if($confirm) {
				mysql_query("UPDATE pupils set class='" . "!" . $txt .  "' WHERE id=" . $x["id"]);
			}
			echo "</td>";
		}
		echo "</tr>";
	}
}
?>
</table>


</body>
</html>

