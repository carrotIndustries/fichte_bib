<html>
<head>
<link rel="stylesheet" href="black.css" />
<script src="std.js"></script>
<script type="text/javascript">
function doReturnObject(id) {
	if(returnobject(id)) {
		alert("Objekt wurde zurueckgegeben");
		//list();
	}
	else {
		alert("Objekt war ueberfaellig");
		//list();
	}
}</script>
<script src="functions.js"></script>
</head>
<body>
Sch&uuml;ler betrachten
<hr />
<?php
	require("functions.php");
	$id= mysql_real_escape_string($_GET["id"]);
	$query  = 'SELECT lastname, firstname, class FROM pupils WHERE id=' . $id;
	$row = mysql_fetch_assoc(mysql_query($query, $link)) or die("Fehler:" . mysql_error());
	//echo 'Der Schüler wurde erfolgreich bearbeitet. <a href="listpupils.php">Zurück</a>';
	//echo $query;
	
?>
<table class="listernx">
<tr>
<td colspan="2" class="head">Sch&uuml;lerinformationen<td>
</tr>

<tr>
<td>Nachname: </td>
<td><?php echo $row["lastname"]; ?></td>
</tr>

<tr class="oddrow">
<td>Vorname: </td>
<td><?php echo $row["firstname"]; ?></td>
</tr>

<tr>
<td>Klasse: </td>
<td><?php echo $row["class"]; ?></td>
</tr>
</table>
<button onclick="window.top.killpopup()">Schlie&szlig;en</button>
<table class="lister">
<tr class="head">
<td>1. Autor (Nachname)</td>
<td>1. Autor (Vorname)</td>
<td>Titel</td>
<td>Ausleihedatum</td>
<td>R&uuml;ckgabedatum</td>
<td>F&auml;lligkeitsdatum</td>
<td>Aktionen</td>
</tr>

<?php

	$query  = 'SELECT object_id, author1_lastname,author1_firstname,title,lend_date,lend_returndate,lend_expiredate,pupil_id FROM lend,objects WHERE lend.pupil_id=' . $id . ' AND lend.object_id = objects.id ORDER BY lend_date DESC';
	$rows = mysql_fetch_superarray(mysql_query($query, $link));
	//print_r($rows);
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
		
		echo '<td>' . $row["author1_lastname"] . '</td>';
		echo '<td>' . $row["author1_firstname"] . '</td>';
		echo '<td>' . $row["title"] . '</td>';
		echo '<td>' .convertdate( $row["lend_date"]) . '</td>';
		if($row["lend_returndate"] == "0000-00-00") {
			echo "<td></td>";
		}
		else {
			echo '<td>' . convertdate($row["lend_returndate"]) . '</td>';
		}
		echo '<td>' . convertdate($row["lend_expiredate"]) . '</td>';
		
		if(count(mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_expiredate <= CURDATE() AND lend_returndate = 0000-00-00 AND pupil_id=" . $row["pupil_id"] . " AND lend_date='" . $row["lend_date"] . "' AND object_id=" . $row["object_id"]))) != 0) {
		echo '<td><a  href="#" Onclick="doReturnObject(' . $row["object_id"] . '); window.top.list(); location.reload();"><img src="gfx/return.png"></a></td>';
		}
		else {
			echo "<td></td>";
		}
		echo '</tr>';
	}
?>

</table>

</body>
</html>
<?php
	mysql_close($link);
?>
