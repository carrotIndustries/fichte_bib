<?php
require("functions.php");
$pupil =  $_GET["pupil"];
$pupil = mysql_real_escape_string($pupil);
$objects = explode(";", $_GET["objects"]);

$result = mysql_fetch_superarray(mysql_query("SELECT pupils.grp, groups.id, groups.duration FROM pupils, groups WHERE pupils.id=" . $pupil . " AND groups.id = pupils.grp"));
$duration = $result[0]["duration"];

$result = mysql_fetch_assoc(mysql_query("SELECT last_return FROM meta"));
$last = $result["last_return"];

$result = mysql_fetch_assoc(mysql_query("SELECT last_return_mode FROM meta"));
$mode = $result["last_return_mode"];
?> 
<html>
<head>
<script src="std.js" type="text/javascript">
</script>

<script src="functions.js" type="text/javascript">
</script>
<link rel="stylesheet" href="black.css" />
<script type="text/javascript">
	pupil = <?php echo $_GET["pupil"]; ?>;
	objects = "<?php echo $_GET["objects"]; ?>";
	objects = objects.split(";");
	
	
	function process() {
		var lends = new Array();
		var obj = {};
		i = 0;
		//alert(objects.length);
		var url = "handle.php?do=lend&pupil=" + pupil + "&param=";
		while(i<objects.length) {
			url += objects[i] + ",";
			url += document.forms['f'+objects[i]].year.value + ",";
			url += document.forms['f'+objects[i]].month.value + ",";
			url += document.forms['f'+objects[i]].day.value + ";";
			
			//alert(obj.toSource());
			//lends.push(obj);
			i++;
		}
		var req = new ajax();
		req.create(url);
		var resp = req.fetch();
		if(resp == "done") {
			if(objects.length == 1) {
				alert("1 Objekt ausgeliehen!");
			}
			else {
				alert(objects.length + " Objekte ausgeliehen!");
			}
			<?php
			$base= explode(".", basename($_SERVER["HTTP_REFERER"]));
			if($base[0] == "listobjects") {
				echo 'location.href="listobjects.php";';
			}
			if($base[0] == "lend_select") { 
				echo 'location.href="quick";';
			}
			?>
		}
		else {
			alert("Fehler");
			alert(resp);
		}
	}
	
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onLoad="">
<?php require("header.php") ?>

<div class="lendhead">
Ausleihen : <?php
$result = mysql_fetch_superarray(mysql_query("SELECT * FROM pupils WHERE id=" . $pupil));
echo $result[0]["lastname"] . " " . $result[0]["firstname"] . " " . $result[0]["class"] .  "<br />";
$year = date("Y", time());
$month = date("n", time());
$day = date("j", time());
echo "Datum: " . $day . "." . $month . "." . $year . "<br />";


?>
</div>
<div style="font-weight: bold; background-color: red; ">
<?php if($mode=="f") {?>
Festes Rückgabedatum gesetzt!
<?php } ?>
<?php if($mode=="l") {?>
Spätestes Rückgabedatum gesetzt!
<?php } ?>
</div><br />
<?php

foreach($objects as $object) {
	if($mode == "o") {
		$expiretime = time() + ($duration*60*60*24);
		$year = date("Y", $expiretime);
		$month = date("n", $expiretime);
		$day = date("j", $expiretime);
	}
	if($mode == "f") {
		$last = explode("-", $last);
		$year = $last[0];
		$month = $last[1];
		$day = $last[2];
	}
	if($mode == "l") {
		$lasttime = strtotime($last);
		$expiretime = time() + ($duration*60*60*24);
		if($expiretime > $lasttime) {
			$expiretime = $lasttime;
		}
		$year = date("Y", $expiretime);
		$month = date("n", $expiretime);
		$day = date("j", $expiretime);
	}
	
	$result = mysql_fetch_superarray(mysql_query("SELECT * FROM objects WHERE id=" . $object));
	if(count( mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_returndate = '0-0-0' AND object_id=" . $object))) == 0) {
		
		echo $result[0]["title"];
		echo '<form name="f' . $object . '">';
		echo "F&auml;llig bis ";
		echo '<input type="text" name="day" value="' . $day . '" maxlength="2" size="2" />.';
		echo '<input type="text" name="month" value="' . $month . '" maxlength="2" size="2" />.';
		echo '<input type="text" name="year" value="' . $year . '" maxlength="4" size="4" />';
		
		
		echo "</form><hr />";
	}
	else {
		echo "<b>Das Objekt ist bereits ausgeliehen?!</b><hr />";
	}
	
}
?>
<button onclick="process()">Ausleihen</button>
</body>
</html>
