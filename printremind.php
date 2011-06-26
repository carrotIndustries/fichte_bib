<?php
	require("functions.php");
	$id = $_GET["id"];
	$id = mysql_real_escape_string($id);
	//echo "SELECT firstname, lastname, class, title, lend_expiredate, lend_date, author1_firstname, author1_lastname FROM lend, objects, pupils WHERE lend_returndate = '0-0-0' AND object_id =" . $id . " AND objects.id = lend.object_id AND pupils.id = lend_pupil_id";
	$result = mysql_fetch_superarray(mysql_query("SELECT firstname, lastname, class, title, printed, lend_expiredate, lend_date, author1_firstname, author1_lastname FROM lend, objects, pupils WHERE lend_returndate = '0-0-0' AND object_id =" . $id . " AND objects.id = lend.object_id AND pupils.id = lend.pupil_id"));
	
	
	$text = get_remindtext();
	$fine = floatval(get_fine());
	$text = str_replace("%pfn", $result[0]["firstname"], $text);
	$text = str_replace("%pln", $result[0]["lastname"], $text);
	$text = str_replace("%class", $result[0]["class"], $text);
	$text = str_replace("%afn", $result[0]["author1_firstname"], $text);
	$text = str_replace("%aln", $result[0]["author1_lastname"], $text);
	$text = str_replace("%title", $result[0]["title"], $text);
	$text = str_replace("%ldate", convertdate($result[0]["lend_date"]), $text);
	$text = str_replace("%edate", convertdate($result[0]["lend_expiredate"]), $text);
	$text = str_replace("%n", $result[0]["printed"], $text);
	$text = str_replace("%fine", "€".number_format($fine*intval($result[0]["printed"]), 2, ",", "."), $text);
	$text = str_replace("%rfine", "€".number_format($fine, 2, ",", "."), $text);
	
	
	
?>

<html>
<head>
<style type="text/css">
@media print {
	.hid {
		display: none;
	}
}	
</style>
<script type="text/javascript" src="std.js">
	</script>
<script type="text/javascript">
function doprint(e) {
	var q = window.confirm("Soll der Mahnungszähler erhöht werden?");
	if(q) {
		var req = new ajax();
		var url ="handle.php?do=advanceprinted&id=<?php echo $id; ?>";
		req.create(url);
		var resp = req.fetch();
		if(resp != "done") {
			alert(resp);
		}
	}
	//alert(e.style.display);
	//e.style.display = "none";
	window.print();
	
}
</script>
</head>
<body><!--
	<h1>Mahnung</h1>
	<p>Liebe/r <?php echo $presult[0]["firstname"]; ?> <?php echo $presult[0]["lastname"]; ?> aus der Klasse <?php echo $presult[0]["class"]; ?></p>
	<p>Du hast das Objekt «<?php echo $oresult[0]["title"]; ?>» von <?php echo $oresult[0]["author1_lastname"]; ?>, <?php echo $oresult[0]["author1_firstname"]; ?> am <?php echo convertdate($lresult[0]["lend_date"]); ?> ausgeliehen und nicht bis zum <?php echo convertdate($lresult[0]["lend_expiredate"]); ?> zurückgebracht.</p>
	<p>Dies ist deine <?php echo $lresult[0]["printed"]; ?>. Mahnung</p>
	-->
	<?php
	echo $text;
	?>
	</p>
	<button class="hid" OnClick="doprint(this)">Drucken</button>
	<button class="hid" onclick="window.top.killpopup()">Schließen</button>
</body>
</html>
