<html>
<head>
<link rel="stylesheet" href="black.css" />
<script src="std.js" type="text/javascript"></script>
<script src="popup.js" type="text/javascript"></script>
<script type="text/javascript">
	basket = new Array();
	function addBasket(id) {
	var i = 0;
	exists = 0;
	while(i < basket.length) {
		if(basket[i] == id) {
			exists = 1;
		}
		i++;
	}
	if(!exists) {
		basket.push(id);
		//remainingobjects = maxlend - lended_objects- basket.length;
		//$("remain").firstChild.nodeValue = remainingobjects;
		new selectElement("e" + id).setColor("orange");
		new selectElement("l" + id).hide();
	}
	else {
		alert("Diese Mahnung wird bereits gedruckt")
	}
}

function reset() {
	killpopup();
	location.reload();
}
</script>
	
</head>
<body>

<?php
require("header.php");
require("functions.php");
	$query = "SELECT object_id, firstname,lastname,class,lend_date,lend_expiredate,title,printed FROM lend,pupils,objects WHERE lend_expiredate <= CURDATE() AND lend.object_id=objects.id AND lend.pupil_id=pupils.id AND lend_returndate = '0-0-0'";
	$rows = mysql_fetch_superarray(mysql_query($query, $link));
	?>
	<button onclick="if(basket.length > 0){popup('printremind_tex.php?id=' +basket.join(','), 500, 300)}">Gewählte Mahnungen drucken</button>
	<button onclick="popup('printremind_tex.php?id=<?php
	$s = "";
	foreach($rows as $row) {
		
		$s = $s . $row["object_id"] . ",";
		
	}
	echo substr($s, 0, -1);
	?>', 500, 300)">Alle Mahnungen drucken</button>
	<?php
	foreach($rows as $row) {
		echo "<div id='e" . $row["object_id"] . "'>";
		echo "Nachname: " . $row["lastname"] . "<br />";
		echo "Vorname: " . $row["firstname"] . "<br />";
		echo "Klasse: " . $row["class"] . "<br />";
		echo "Auleihdatum: " . convertdate($row["lend_date"]) . "<br />";
		echo "F&auml;lligkeitsdatum: " . convertdate($row["lend_expiredate"]) . "<br />";
		echo $row["printed"] . ". Mahnung<br />";
		echo "Buch: " . $row["title"] . "";
		
		echo '<img src="gfx/mail.png" id="l' . $row["object_id"] . '" Onclick="addBasket(' . $row["object_id"] . ')" style="cursor:pointer;"></div><hr / >';
	}
	
?>
</body>
</html>
