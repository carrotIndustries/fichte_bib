<?php
require("functions.php");
$lend = -1;
if(array_key_exists("lend", $_GET)) {
	$lend = $_GET["lend"];
}

?> 
<html>
<head>
<script src="std.js" type="text/javascript">
</script>
<script src="listobjects.js" type="text/javascript">
</script>
<script src="functions.js" type="text/javascript">
</script>
<script src="popup.js" type="text/javascript">
</script>
<link rel="stylesheet" href="black.css" />
<script type="text/javascript">
	lend_pupil = <?php echo $lend; ?>;
	lended_objects = <?php
	if($lend != -1) {
		$result = mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_returndate='0-0-0' AND pupil_id=" . $lend));
		echo count($result);
	}
	else {
		echo 0;
	}
	?>;
	maxlend = <?php
	if($lend != -1) {
		$result = mysql_fetch_superarray(mysql_query("SELECT maxlend FROM pupils,groups WHERE pupils.id=" . $lend . " AND groups.id = pupils.grp"));
		echo $result[0]["maxlend"];
	}
	else {
		echo 0;
	}
	?>
	
	;
	remainingobjects = 0;
	basket = new Array();
	function list() {
		doSearch(document.forms['search']);
	}
	
	function init() {
		list();
		document.forms['search'].search.focus();
		remainingobjects = maxlend - lended_objects;
		if(lend_pupil != -1) {
			$("remain").firstChild.nodeValue = remainingobjects;
		}
		new selectElement("autoadd").createText("");
	}
	
	function checkout() {
		if(basket.length > 0) {
			location.href = "lend.php?pupil=" + lend_pupil + "&objects=" + basket.join(";");
		}
	}
	
	function cancel() {
		location.href = "listobjects.php?";
	}
</script>
</head>
<body onLoad="init()">

<?php require("header.php") ?>
<?php
$defaults=get_formdefaults();
$objsearchdefault=$defaults[0];
$objsearchinc=intval($defaults[1]);
$pupilsearchdefault=$defaults[2];
$pupilsearchinc=intval($defaults[3]);
function makeobjsearchdefaultoption($option) {
	global $objsearchdefault;
	//echo $objsearchdefault;
	echo 'value="' . $option . '" ';
	if($option==$objsearchdefault) {
		echo"selected";
	}
}
?>
<form name="search" OnSubmit="return doSearch(this);" Onchange="doSearch(this);" <?php echo $objsearchinc?'Onkeypress="doSearch(this);"':''?>>
<select name="search_for" size="1">
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
<select name="match" size="1">
<option value="contains">Enth&auml;lt</option>
<option value="start">Beginnt mit</option>
<option value="end">Endet mit</option>
<option value="equal">Ist genau</option>
</select>
<input type="text" name="search" onfocus="this.focussed = true;" onblur="this.focussed = false;"/>
<select name="limit" size="1">
<option value="all">Alle</option>
<option value="l20" selected>20</option>
<option value="l50">50</option>
<option value="l100">100</option>
</select>
Wo:<select name="location" size="1">
	<option value ="-1" selected>&Uuml;berall</a>
	<?php
		$result = mysql_fetch_superarray(mysql_query("SELECT * FROM locations"));
		
		foreach($result as $loc) {
			echo '<option value="' . $loc["id"] . '">' . $loc["location"] . '</option>\n';
		}
	?>
</select>
Status:<select name="state" size="1">
	<option value="all">Egal</option>
	<option value="avail">Verfügbar</option>
	<option value="lend">Ausgeliehen</option>
	<option value="expired">Überfällig</option>
</select>
<br />
Suche auf folgenden Medien beschränken: 
<?php
		$result = mysql_fetch_superarray(mysql_query("SELECT * FROM mediatypes"));
		foreach($result as $mt) {
			echo '<input type="checkbox" name="mt' . $mt["id"] . '">' . $mt["media"] . '&nbsp;';
		}
	?>
<br />
Sortiere
<select name="order_by" size="1">
<option value="author1_lastname">1. Autor (Nachname)</option>
<option value="author1_firstname">1. Autor (Vorname)</option>
<option value="author2_lastname">2. Autor (Nachname)</option>
<option value="author2_firstname">2. Autor (Vorname)</option>
<option value="title" selected>Titel</option>
<option value="publisher">Verlag</option>
<option value="year">Erscheinungsjahr</option>
<option value="edition">Auflage</option>
<option value="genre">Genre</option></select>
<select name="order" size="1">
<option value="asc">Aufsteigend</option>
<option value="desc">Absteigend</option>
</select>
<input type="submit" value="Suchen" />
<!--<button OnClick="location.href='listobjects.php'">Alle anzeigen</button>-->
<input type="button" value="Zur&uuml;cksetzen" OnClick="document.forms['search'].reset(); doSearch(document.forms['search']);"></input>
<img src="gfx/throbber.gif" style="float:right; visibility:hidden; " id="throbber"/>
<?php
if($lend != -1) {
	$result = mysql_fetch_superarray(mysql_query("SELECT * FROM pupils WHERE id=" . $lend));
	echo "Ausleihen: " . $result[0]["lastname"] . " " . $result[0]["firstname"];
	echo "; Verbleibende Objekte:<span id='remain'> </span> <button onclick='checkout(); return false;'>...Ausleihen</button><button onclick='cancel(); return false;'>Abbrechen</button>";
 }

?>
</form>
<div id="add" style="display:none;">Objekt wurde nicht gefunden <a id="addlink" href="javascript:;">Hinzufügen</a></div>
<div id="tablecont">
</div>

</body>
</html>
