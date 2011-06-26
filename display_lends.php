<html>
<head>
<link rel="stylesheet" href="black.css" />
</head>
<body>
Ausleihhistorie eines Objektes betrachten
<?php

	require("functions.php");
	$id= mysql_real_escape_string($_GET["id"]);
	$query  = 'SELECT author1_lastname, author1_firstname, title FROM objects WHERE id=' . $id;
	$row = mysql_fetch_assoc(mysql_query($query, $link)) or die("Fehler:" . mysql_error());
	//echo 'Der Schüler wurde erfolgreich bearbeitet. <a href="listpupils.php">Zurück</a>';
	//echo $query;
	
?>
<table class="listernx">
<tr class="head">
<td colspan="2">Objektinformationen<td>
</tr>

<tr>
<td>1. Autor (Nachname): </td>
<td><?php echo $row["author1_lastname"]; ?></td>
</tr>

<tr class="oddrow">
<td>1. Autor (Vorname): </td>
<td><?php echo $row["author1_firstname"]; ?></td>
</tr>

<tr>
<td>Titel: </td>
<td><?php echo $row["title"]; ?></td>
</tr>
</table>
<button onclick="window.top.killpopup()">Schlie&szlig;en</button>
<table class="lister">
<tr class="head">
<td>Nachname</td>
<td>Vorname</td>
<td>Klasse</td>
<td>Ausleihedatum</td>
<td>R&uuml;ckgabedatum</td>
<td>F&auml;lligkeitsdatum</td>
<tr>
<?php

	$query  = 'SELECT object_id,pupil_id,lastname,firstname,class,lend_date,lend_returndate,lend_expiredate FROM lend,pupils WHERE object_id=' . $id . ' AND lend.pupil_id = pupils.id ORDER BY lend_date DESC';
	$rows = mysql_fetch_superarray(mysql_query($query, $link));
	$even = true;
	foreach($rows as $row) {
		if($even) {
			$cls = "evenrow";
			$even = false;
		}
		else {
			$cls = "oddrow";
			$even = true;
		}
		if(count(mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_expiredate <= CURDATE() AND lend_returndate = 0000-00-00 AND pupil_id=" . $row["pupil_id"] . " AND lend_date='" . $row["lend_date"] . "' AND object_id=" . $row["object_id"]))) == 0) {
			echo '<tr class="' . $cls . '">';
		}
		else {
			echo '<tr style="color: red;" class="' . $cls . '">';
		}
		echo '<td>' . $row["lastname"] . '</td>';
		echo '<td>' . $row["firstname"] . '</td>';
		echo '<td>' . $row["class"] . '</td>';
		echo '<td>' . convertdate($row["lend_date"]) . '</td>';
		if($row["lend_returndate"] == "0000-00-00") {
			echo "<td></td>";
		}
		else {
			echo '<td>' . convertdate($row["lend_returndate"]) . '</td>';
		}
		echo '<td>' . convertdate($row["lend_expiredate"]) . '</td>';
		echo '</tr>';
	}
?>

</table>

</body>
</html>
<?php
	mysql_close($link);
?>