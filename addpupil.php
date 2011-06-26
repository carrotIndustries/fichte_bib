<?php require ("functions.php"); ?>
<html>
<head>
<script type="text/javascript" src="addpupil.js">
</script>
<script type="text/javascript" src="std.js">
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onload="getrand();">
<?php require ("header.php"); ?>
<form name="add" OnSubmit="return process();">
<table border="0px">
<tr><td>
Nachname:</td><td>
<input name="lastname" type="text" maxlength="30" />
</td></tr>

<tr><td>
Vorname:</td><td>
<input name="firstname" type="text" maxlength="30" />
</td></tr>

<tr><td>
Klasse:</td><td>
<input name="class" type="text" maxlength="3" size="3" />
</td></tr>

<tr><td>
Kartennummer:</td><td>
<input name="card_id" type="text" maxlength="13" size="13" /><input type="button" onclick="getrand(); return false;" value="Neue Kartennummer" />
</td></tr>

<tr><td>
Gruppe:</td><td>
<select name="group" size="1">
	<?php
		$result = mysql_fetch_superarray(mysql_query("SELECT * FROM groups"));
		foreach($result as $loc) {
			echo '<option value="' . $loc["id"] . '">' . $loc["name"] . '</option>\n';
		}
	?>
</select>
</td></tr>

<tr><td>
<input type="submit" value="Sch&uuml;ler hinzuf&uuml;gen">
<input type="reset" value="Zur&uuml;cksetzen">
</td></tr>
</table>
</form>
</body>
</html>
