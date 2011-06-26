<?php require ("functions.php");
$id = mysql_real_escape_string($_GET["id"]);
$result = mysql_fetch_superarray(mysql_query("SELECT * FROM objects where id=" . $id));
?>
<html>
<head>
<script type="text/javascript" src="editobject.js">
</script>
<script type="text/javascript" src="functions.js">
</script>
<script type="text/javascript" src="std.js">
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body>
Objekt Bearbeiten
<hr />
<form name="edit" OnSubmit="return process();">
<table border="0px">
<tr><td>Medium:</td><td>
<select name="media_type" size="1">
	<?php
		$lresult = mysql_fetch_superarray(mysql_query("SELECT * FROM mediatypes"));
		foreach($lresult as $mt) {
			$selected = "";
			if($result[0]["media_type"] == $mt["id"]) {
				$selected = "selected";
			}
			echo '<option value="' . $mt["id"] . '"' . $selected . '>' . $mt["media"] . '</option>\n';
		}
	?>
</select>
</td></tr>

<tr><td>
1. Autor (Nachname):</td><td>
<input name="author1_lastname" type="text" maxlength="30" value="<?php echo $result[0]["author1_lastname"]; ?>" />
</td></tr>

<tr><td>
1. Autor (Vorname):</td><td>
<input name="author1_firstname" type="text" maxlength="30" value="<?php echo $result[0]["author1_firstname"]; ?>" />
</td></tr>

<tr><td>
2. Autor (Nachname):</td><td>
<input name="author2_lastname" type="text" maxlength="30" value="<?php echo $result[0]["author2_lastname"]; ?>" />
</td></tr>

<tr><td>
2. Autor (Vorname):</td><td>
<input name="author2_firstname" type="text" maxlength="30" value="<?php echo $result[0]["author2_firstname"]; ?>" />
</td></tr>

<tr><td>
Titel:</td><td>
<input name="title" type="text" maxlength="200" value="<?php echo $result[0]["title"]; ?>"/>*
</td></tr>

<tr><td>
ISBN:</td><td>
<input name="isbn" type="text" maxlength="13" value="<?php echo $result[0]["isbn"]; ?>" /> <!--<a href="#" OnClick="autocomplete();">Vervollst&auml;ndigen</a><span style="display: none;" id="confirm">Stimmen diese Angaben? : <a href="#" onclick="process();">JA</a> <a href="#" onclick="document.forms['add'].reset(); $('confirm').style.display = 'none'">NEIN</a></span> &nbsp; <a href="#" onclick="wnd = window.open('ean-13.php?code=' + document.forms['add'].elements['isbn'].value); window.setTimeout('wnd.print()', 1000); window.setTimeout('wnd.close()', 4000);">Barcode ausdrucken</a> &nbsp; <a href="#" onclick="document.forms['add'].elements['isbn'].value = getrand();">ISBN erzeugen</a><img id="throbber" src="gfx/throbber.gif" style="visibility: hidden;" />-->
</td></tr>

<tr><td>
Verlag:</td><td>
<input name="publisher" type="text" maxlength="30" value="<?php echo $result[0]["publisher"]; ?>" />
</td></tr>

<tr><td>
Erscheinungsjahr:</td><td>
<input name="year" type="text" maxlength="4" value="<?php echo $result[0]["year"]; ?>" />
</td></tr>

<tr><td>
Auflage:</td><td>
<input name="edition" type="text" maxlength="11" value="<?php echo $result[0]["edition"]; ?>" />
</td></tr>

<tr><td>
Genre:</td><td>
<input name="genre" type="text" maxlength="20" value="<?php echo $result[0]["genre"]; ?>" />
</td></tr>

<tr><td>
Ort:</td><td>
<select name="location" size="1">
	<?php
		$lresult = mysql_fetch_superarray(mysql_query("SELECT * FROM locations"));
		foreach($lresult as $loc) {
			$selected = "";
			if($result[0]["location"] == $loc["id"]) {
				$selected = "selected";
			}
			echo '<option value="' . $loc["id"] . '"' . $selected . '>' . $loc["location"] . '</option>\n';
		}
	?>
</select>
</td></tr>

<?php
$result2 = mysql_fetch_superarray(mysql_query("SELECT * FROM usercols"));
foreach($result2 as $col) {
	echo "<tr><td>" . $col["name"] . ':</td><td><input name="user' . $col["id"] . '" value="' . $result[0]["user" . $col["id"]] . '" type="text" maxlength="30" /></td></tr>';
}
?>

<tr><td>
* : Obligatorisch <br />
</td></tr>

</table>
<input type="hidden" name="id" value="<?php echo $result[0]["id"]; ?>" />
<input type="submit" value="Objekt &auml;ndern">
<input type="reset" value="Zur&uuml;cksetzen">
<input type="button" value="Schlie&szlig;en" OnCLick="window.top.killpopup();">


</form>
</body>
</html>
