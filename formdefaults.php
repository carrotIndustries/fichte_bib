<?php require ("functions.php");
$defaults=get_formdefaults();
$objsearchdefault=$defaults[0];
$objsearchinc=intval($defaults[1]);
$pupilsearchdefault=$defaults[2];
$pupilsearchinc=intval($defaults[3]);
?>
<?php
function makeobjsearchdefaultoption($option) {
	global $objsearchdefault;
	//echo $objsearchdefault;
	echo 'value="' . $option . '" ';
	if($option==$objsearchdefault) {
		echo"selected";
	}
}

function makepupilsearchdefaultoption($option) {
	global $pupilsearchdefault;
	echo 'value="' . $option . '" ';
	if($option==$pupilsearchdefault) {
		echo"selected";
	}
}

?>
<html>
<head>
<script type="text/javascript" src="formdefaults.js">
</script>
<script type="text/javascript" src="std.js">
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onload="">
<?php require("header.php");?>
Formulareinstellungen
<hr />
<form name="def" OnSubmit="return process();">
<table border="0px">
<tr><td>
Objektsuchkriteriumsvorgabe:</td><td>
<select name="search_for_obj" size="1">
<option <?php makeobjsearchdefaultoption("author1_lastname");?>>1. Autor (Nachname)</option>
<option <?php makeobjsearchdefaultoption("author1_firstname");?>>1. Autor (Vorname)</option>
<option <?php makeobjsearchdefaultoption("author2_lastname");?>>2. Autor (Nachname)</option>
<option <?php makeobjsearchdefaultoption("author2_firstname");?>>2. Autor (Vorname)</option>
<option <?php makeobjsearchdefaultoption("title");?>>Titel</option>
<option <?php makeobjsearchdefaultoption("isbn");?>>ISBN</option>
<option <?php makeobjsearchdefaultoption("publisher");?>>Verlag</option>
<option <?php makeobjsearchdefaultoption("year");?>>Erscheinungsjahr</option>
<option <?php makeobjsearchdefaultoption("edition");?>>Auflage</option>
<option <?php makeobjsearchdefaultoption("genre");?>>Genre</option>
</select>
</td></tr>
<tr><td>
Inkrementell in Objekten Suchen?</td><td>
<input type="checkbox" name="search_inc_obj" <?php echo $objsearchinc?"checked":"" ?> />
</tr></td>

<tr><td>
Schülersuchkriteriumsvorgabe:</td><td>
<select name="search_for_pupil" size="1">
<option <?php makepupilsearchdefaultoption("lastname");?>>Nachname</option>
<option <?php makepupilsearchdefaultoption("firstname");?>>Vorname</option>
<option <?php makepupilsearchdefaultoption("class");?>>Klasse</option>
<option  <?php makepupilsearchdefaultoption("card_id");?>>Kartennummer</option>
</select>
</td></tr>

<tr><td>
Inkrementell in Schülern Suchen?</td><td>
<input type="checkbox" name="search_inc_pupil" <?php echo $pupilsearchinc?"checked":"" ?> />
</tr></td>

</table>

<input type="submit" value="Übernehmen">

</form>
</body>
</html>
