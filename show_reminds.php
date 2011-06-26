<html>
<head>
<link rel="stylesheet" href="black.css" />
<script src="std.js" type="text/javascript"></script>
<script src="popup.js" type="text/javascript"></script>
</head>
<body>

<?php
require("header.php");
require("functions.php");
	$query = "SELECT object_id, firstname,lastname,class,lend_date,lend_expiredate,title FROM lend,pupils,objects WHERE lend_expiredate <= CURDATE() AND lend.object_id=objects.id AND lend.pupil_id=pupils.id AND lend_returndate = '0-0-0'";
	$rows = mysql_fetch_superarray(mysql_query($query, $link));
	foreach($rows as $row) {
		echo "Nachname: " . $row["lastname"] . "<br />";
		echo "Vorname: " . $row["firstname"] . "<br />";
		echo "Klasse: " . $row["class"] . "<br />";
		echo "Auleihdatum: " . convertdate($row["lend_date"]) . "<br />";
		echo "F&auml;lligkeitsdatum: " . convertdate($row["lend_expiredate"]) . "<br />";
		echo "Buch: " . $row["title"] . "";
		echo '<img src="gfx/mail.png" Onclick="popup(\'printremind.php?id=' . $row["object_id"] . '\', 900, 500)" style="cursor:pointer;"><hr / >';
	}
	
?>
</body>
</html>
