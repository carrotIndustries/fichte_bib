<?php
require("functions.php");

?> 
<html>
<head>
<script src="std.js" type="text/javascript">
</script>
<script src="listpupils.js" type="text/javascript">
</script>
<script src="functions.js" type="text/javascript">
</script>
<script src="popup.js" type="text/javascript">
</script>
<script type="text/javascript">
	function list() {
		doSearch(document.forms['search']);
	}
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onLoad="list(); document.forms['search'].search.focus();">
<?php require("header.php") ?>
<?php
$defaults=get_formdefaults();
$objsearchdefault=$defaults[0];
$objsearchinc=intval($defaults[1]);
$pupilsearchdefault=$defaults[2];
$pupilsearchinc=intval($defaults[3]);
function makepupilsearchdefaultoption($option) {
	global $pupilsearchdefault;
	echo 'value="' . $option . '" ';
	if($option==$pupilsearchdefault) {
		echo"selected";
	}
}
?>
<form name="search" OnSubmit="return doSearch(this);" Onchange="doSearch(this);"  <?php echo $pupilsearchinc?'Onkeypress="doSearch(this);"':''?>>
<select name="search_for" size="1">
<option <?php makepupilsearchdefaultoption("lastname");?>>Nachname</option>
<option <?php makepupilsearchdefaultoption("firstname");?>>Vorname</option>
<option <?php makepupilsearchdefaultoption("class");?>>Klasse</option>
<option  <?php makepupilsearchdefaultoption("card_id");?>>Kartennummer</option>
</select>
<select name="match" size="1">
<option value="contains">Enth&auml;lt</option>
<option value="start">Beginnt mit</option>
<option value="end">Endet mit</option>
<option value="equal">Ist genau</option>
</select>
<input type="text" name="search" />
<br />
Sortiere
<select name="order_by" size="1">
<option value="lastname">Nachname</option>
<option value="firstname">Vorname</option>
<option value="class">Klasse</option>
</select>
<select name="order" size="1">
<option value="asc">Aufsteigend</option>
<option value="desc">Absteigend</option>
</select>
Gruppe:<select name="group" size="1">
	<option value ="-1" selected>Alle</a>
	<?php
		$result = mysql_fetch_superarray(mysql_query("SELECT * FROM groups"));
		
		foreach($result as $loc) {
			echo '<option value="' . $loc["id"] . '">' . $loc["name"] . '</option>\n';
		}
	?>
</select>

<br />

<input type="submit" value="Suchen" />
<!--<button OnClick="location.href='listobjects.php'">Alle anzeigen</button>-->
<input type="button" value="Zur&uuml;cksetzen" OnClick="document.forms['search'].reset(); doSearch(document.forms['search']);"></input>
<img src="gfx/throbber.gif" style="float:right; visibility:hidden; " id="throbber"/>
</form>
<div id="tablecont">
</div>

</body>
</html>
