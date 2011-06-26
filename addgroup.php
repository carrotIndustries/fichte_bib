<?php require ("functions.php"); ?>
<html>
<head>
<script type="text/javascript" src="addgroup.js">
</script>
<script type="text/javascript" src="std.js">
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body>
<?php require ("header.php"); ?>
<form name="add" OnSubmit="return process();">
<table border="0px">
<tr><td>
Gruppenname:</td><td>
<input name="name" type="text" maxlength="30" />
</td></tr>

<tr><td>
Max. auszuleihende Obj.:</td><td>
<input name="maxlend" type="text" maxlength="4" size="4" />
</td></tr>

<tr><td>
Max. Ausleihdauer in Tagen:</td><td>
<input name="duration" type="text" maxlength="4" size="4" />
</td></tr>

<tr><td>
<input type="submit" value="Gruppe hinzuf&uuml;gen">
<input type="reset" value="Zur&uuml;cksetzen">
</td></tr>
</table>
</form>
</body>
</html>