<?php require ("functions.php");
$id = mysql_real_escape_string($_GET["id"]);
$result = mysql_fetch_superarray(mysql_query("SELECT * FROM pupils where id=" . $id));
?>
<html>
<head>
<script type="text/javascript" src="editpupil.js">
</script>
<script type="text/javascript" src="std.js">
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onload="">
Sch&uuml;ler bearbeiten
<hr />
<form name="add" OnSubmit="return process();">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<table border="0px">
<tr><td>
Nachname:</td><td>
<input name="lastname" type="text" maxlength="30" value="<?php echo $result[0]["lastname"]; ?>" />
</td></tr>

<tr><td>
Vorname:</td><td>
<input name="firstname" type="text" maxlength="30" value="<?php echo $result[0]["firstname"]; ?>" />
</td></tr>

<tr><td>
Klasse:</td><td>
<input name="class" type="text" maxlength="3" size="3" value="<?php echo $result[0]["class"]; ?>" />
</td></tr>

<tr><td>
Kartennummer:</td><td>
<input name="card_id" type="text" maxlength="13" size="13" value="<?php echo $result[0]["card_id"]; ?>" /><input type="button" onclick="getrand(); return false;" value="Neue Kartennummer" />
</td></tr>

<tr><td>
Gruppe:</td><td>
<select name="group" size="1">
	<?php
		$gresult = mysql_fetch_superarray(mysql_query("SELECT * FROM groups"));
		foreach($gresult as $grp) {
			$selected = "";
			if($result[0]["grp"] == $grp["id"]) {
				$selected = "selected";
			}
			echo '<option value="' . $grp["id"] . '" ' . $selected . ' >' . $grp["name"] . '</option>\n';
		}
	?>
</select>
</td></tr>
</table>

<input type="submit" value="Schüler speichern">
<input type="reset" value="Zurücksetzen">
<input type="button" value="Schlie&szlig;en" OnCLick="window.top.killpopup();">


</form>
</body>
</html>
