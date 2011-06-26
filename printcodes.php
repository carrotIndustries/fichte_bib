<?php require ("functions.php");

?>

<html>
<head>
	
<link rel="stylesheet" href="black.css" />
<script src="std.js" type="text/javascript"></script>
<script src="popup.js" type="text/javascript"></script>
</head>
<body onload="">
<?php require("header.php");?>
Barcodes ausdrucken
<hr />
<a href="#" Onclick="popup('quickcodes.html', 900, 300)">Quickmodus-Barcodes</a><br />
<a href="#" Onclick="popup('barcodes-pupil.php', 1000, 500)">Schüler Barcodes</a><br />
<a href="#" Onclick="popup('barcodes.php', 1000, 500)">Vorratspackung Barcodes</a><br />
</body>
</html>

