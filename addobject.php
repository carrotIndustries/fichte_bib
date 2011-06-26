<?php
$isbn = "";
if(array_key_exists("isbn", $_GET)) {
	$isbn = $_GET["isbn"];
}
$inpopup = FALSE;
if(array_key_exists("inpopup", $_GET)) {
	$inpopup = TRUE;
}
?>
<?php require ("functions.php"); ?>
<html>
<head>
	<script type="text/javascript">
		inpopup = <?php echo $inpopup?"true":"false";?>;
	</script>
	<script type="text/javascript" src="std.js">
</script>
<script type="text/javascript" src="addobject.js">
</script>
<script type="text/javascript" src="functions.js">
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onload="document.forms['add'].isbn.focus();">
<?php if(!$inpopup) { require ("header.php");} ?>
<form name="add" OnSubmit="return false;">
<table border="0px">
<tr><td>Medium:</td><td>
<select name="media_type" size="1">
	<?php
		$result = mysql_fetch_superarray(mysql_query("SELECT * FROM mediatypes"));
		foreach($result as $mt) {
			echo '<option value="' . $mt["id"] . '">' . $mt["media"] . '</option>\n';
		}
	?>
</select>
</td></tr>

<tr><td>
1. Autor (Nachname):</td><td>
<input name="author1_lastname" type="text" maxlength="30" />
</td></tr>

<tr><td>
1. Autor (Vorname):</td><td>
<input name="author1_firstname" type="text" maxlength="30" />
</td></tr>

<tr><td>
2. Autor (Nachname):</td><td>
<input name="author2_lastname" type="text" maxlength="30" />
</td></tr>

<tr><td>
2. Autor (Vorname):</td><td>
<input name="author2_firstname" type="text" maxlength="30" />
</td></tr>

<tr><td>
Titel:</td><td>
<input name="title" type="text" maxlength="200" />*
</td></tr>

<tr><td>
ISBN:</td><td>
<input name="isbn" type="text" maxlength="13" value="<?php echo $isbn; ?>" /> <input type="button" OnClick="autocpl(); return false;" value="Vervollst&auml;ndigen" /><span style="display: none;" id="confirm">Stimmen diese Angaben? : <button onclick="process(); return false;">JA</button> <input type="button" onclick="document.forms['add'].reset(); $('confirm').style.display = 'none'; document.forms['add'].isbn.focus(); return false;" value="NEIN" /></span> &nbsp; <input type="button" onclick="wnd = window.open('ean-13.php?code=' + document.forms['add'].elements['isbn'].value); window.setTimeout('wnd.print()', 1000); window.setTimeout('wnd.close()', 4000); return false;" value="Barcode ausdrucken" /> &nbsp; <input type="button" onclick="document.forms['add'].elements['isbn'].value = getrand(); return false;" value="ISBN erzeugen" /><img id="throbber" src="gfx/throbber.gif" style="visibility: hidden;" />
</td></tr>

<tr><td>
Verlag:</td><td>
<input name="publisher" type="text" maxlength="30" />
</td></tr>

<tr><td>
Erscheinungsjahr:</td><td>
<input name="year" type="text" value="2000" maxlength="4" />
</td></tr>

<tr><td>
Auflage:</td><td>
<input name="edition" type="text" value="1" maxlength="11" />
</td></tr>

<tr><td>
Genre:</td><td>
<input name="genre" type="text" maxlength="20" />
</td></tr>

<tr><td>
Ort:</td><td>
<select name="location" size="1">
	<?php
		$result = mysql_fetch_superarray(mysql_query("SELECT * FROM locations"));
		foreach($result as $loc) {
			echo '<option value="' . $loc["id"] . '">' . $loc["location"] . '</option>\n';
		}
	?>
</select>
</td></tr>

<?php
$result = mysql_fetch_superarray(mysql_query("SELECT * FROM usercols"));
foreach($result as $col) {
	echo "<tr><td>" . $col["name"] . ':</td><td><input name="user' . $col["id"] . '" type="text" maxlength="30" /></td></tr>';
}
?>
<tr><td>
* : Obligatorisch <br />
</td></tr>

<tr><td>
<input type="button" value="Objekt hinzuf&uuml;gen" onclick="process()">
<input type="reset" value="Zur&uuml;cksetzen" onclick="$('confirm').style.display = 'none'; return true;">
<?php if($isbn != "") { ?>
<button onclick="<?php echo $inpopup?"window.top.killpopup()":"window.close()"?>">Schließen</button>
<?php } ?>
</td></tr>
</table>
</form>
</body>
</html>
