<?php
	require("functions.php");
	$ids = $_GET["id"];
	$ids = mysql_real_escape_string($ids);
	$ids = explode(",", $ids);
	$content = "";
	$rtext = get_remindtext();
	$i = 0;
	$perpage = 3;
	foreach($ids as $id) {
		//echo "SELECT firstname, lastname, class, title, lend_expiredate, lend_date, author1_firstname, author1_lastname FROM lend, objects, pupils WHERE lend_returndate = '0-0-0' AND object_id =" . $id . " AND objects.id = lend.object_id AND pupils.id = lend_pupil_id";
		$result = mysql_fetch_superarray(mysql_query("SELECT firstname, lastname, class, title, printed, lend_expiredate, lend_date, author1_firstname, author1_lastname FROM lend, objects, pupils WHERE lend_returndate = '0-0-0' AND object_id =" . $id . " AND objects.id = lend.object_id AND pupils.id = lend.pupil_id"));
		
		
		//$text = get_remindtext();
		$text = $rtext;
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
		$text = str_replace("%fine", "\\euro ".number_format($fine*intval($result[0]["printed"]), 2, ",", "."), $text);
		$text = str_replace("%rfine", "\\euro".number_format($fine, 2, ",", "."), $text);
		$content = $content . $text;
		
		$i++;
		if($i == $perpage) {
			$i =0;
			$content = $content . "\\newpage";
		}
	}
	
	
?>

<html>
<head>
<style type="text/css">
@media print {
	.hid {
		display: none;
	}

.break { page-break-before: always; }

}	
</style>
<script type="text/javascript" src="std.js">
	</script>
<script type="text/javascript">
	basket = [<?php echo join(",", $ids)?>];
function doclose(e) {
	var q = window.confirm("Sollen die Mahnungszähler erhöht werden?");
	
	if(q) {
		var i= 0;
		while(i<basket.length) {
			var req = new ajax();
			var url ="handle.php?do=advanceprinted&id="+basket[i];
			req.create(url);
			var resp = req.fetch();
			if(resp != "done") {
				alert(resp);
			}
			i++;
		}
	}
	//alert(e.style.display);
	//e.style.display = "none";
	
}
</script>
</head>
<body>
	<button class="hid" onclick="doclose(); window.top.reset()">Schließen</button>
	<?php
	file_put_contents("ruffeltex/body.tex", $content);
	$r = system("make -C ruffeltex");
	if ($r === FALSE) {
		die("Fehler beim Erzeugen");
	}
	system("lpr " . get_lpropts() . " ruffeltex/mahnung.pdf");
	echo("lpr " . get_lpropts() . " ruffeltex/mahnung.pdf");
	?>
	</p>
	
</body>
</html>
