<?php
require("functions.php");

?> 
<html>
<head>
<script src="std.js" type="text/javascript">
</script>
<script src="listobjects.js" type="text/javascript">
</script>
<script src="remindtext.js" type="text/javascript">
</script>

<script src="functions.js" type="text/javascript">
</script>
<script type="text/javascript">
	
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onLoad="init()">
<?php require("header.php") ?>
Mahnungstext, verwende HTML zur Formatierung

	
<form id="input" name="input" OnSubmit="return false;">
<textarea name="text" id="text" rows="25" cols="200"></textarea>
<br />Mahngebühr/Mahnung: €<input name="fine" id="fine" maxlength="4" size="4"></input> Verwende . (Punkt) als Dezimaltrenner<br />

<input type="button" value="Übernehmen" onclick="process()"><br />
</form>
<br />
Platzhalter sind:
<table border="0px">
<tr><td>%pfn</td><td>Vorname des Schülers</td></tr>
<tr><td>%pln</td><td>Nachname des Schülers</td></tr>
<tr><td>%class</td><td>Klasse des Schülers</td></tr>
<tr><td>%afn</td><td>Vorname des 1. Autors</td></tr>
<tr><td>%aln</td><td>Nachname des 1. Autors</td></tr>
<tr><td>%title</td><td>Buchtitel</td></tr>
<tr><td>%ldate</td><td>Ausleihdatum</td></tr>
<tr><td>%edate</td><td>Fälligkeitsdatum</td></tr>
<tr><td>%n</td><td>Nummer der Mahnung</td></tr>
<tr><td>%fine</td><td>Gesamte Mahngebühr</td></tr>
<tr><td>%rfine</td><td>Mahngebühr/Mahnung</td></tr>
</table>
</body>
</html>
