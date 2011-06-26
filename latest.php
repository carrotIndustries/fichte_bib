<?php require ("functions.php");
$result = mysql_fetch_assoc(mysql_query("SELECT last_return FROM meta"));
$last = $result["last_return"];
$last = explode("-", $last);
		$year = $last[0];
		$month = $last[1];
		$day = $last[2];
$result = mysql_fetch_assoc(mysql_query("SELECT last_return_mode FROM meta"));
$mode = $result["last_return_mode"];
?>

<html>
<head>
<script type="text/javascript" src="latest.js">
</script>
<script type="text/javascript" src="std.js">
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onload="">
<?php require("header.php");?>
Rückgabedatum
<hr />
<form name="def" OnSubmit="return process();">
<table border="0px">
<tr><td>
Modus:</td><td>
<select name="mode" size="1">
<option value="o" <?php echo ($mode=="o")?"selected":""?>>Deaktiviert</option>
<option value="l" <?php echo ($mode=="l")?"selected":""?>>Spätestens</option>
<option value="f" <?php echo ($mode=="f")?"selected":""?>>Fest</option>
</select>
</td></tr>


<tr><td>
Datum</td><td>
<?php
echo '<input type="text" name="day" value="' . $day . '" maxlength="2" size="2" />.';
		echo '<input type="text" name="month" value="' . $month . '" maxlength="2" size="2" />.';
		echo '<input type="text" name="year" value="' . $year . '" maxlength="4" size="4" />';
		?>
</tr></td>

</table>

<input type="submit" value="Übernehmen">

</form>
</body>
</html>

