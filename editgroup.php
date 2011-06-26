<?php require ("functions.php");
$id = mysql_real_escape_string($_GET["id"]);
$result = mysql_fetch_superarray(mysql_query("SELECT * FROM groups where id=" . $id));
?>
<html>
<head>
<script type="text/javascript" src="editgroup.js">
</script>
<script type="text/javascript" src="std.js">
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body>
Gruppe bearbeiten
<hr />
<form name="edit" OnSubmit="return process();">
<table border="0px">
<input type="hidden" name="id" value="<?php echo $result[0]["id"]; ?>" />
<tr><td>
Gruppenname:</td><td>
<input name="name" type="text" maxlength="30" value="<?php echo $result[0]["name"]; ?>" />
</td></tr>

<tr><td>
Max. auszuleihende Obj.:</td><td>
<input name="maxlend" type="text" maxlength="4" size="4" value="<?php echo $result[0]["maxlend"]; ?>" />
</td></tr>

<tr><td>
Max. Ausleihdauer in Tagen:</td><td>
<input name="duration" type="text" maxlength="4" size="4" value="<?php echo $result[0]["duration"]; ?>" />
</td></tr>

<tr><td>
<input type="submit" value="Gruppe &auml;ndern">
<input type="reset" value="Zur&uuml;cksetzen">
<input type="button" value="Schlie&szlig;en" OnCLick="window.top.killpopup();">
</td></tr>
</table>
</form>
</body>
</html>