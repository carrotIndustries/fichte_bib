<html>
<head>
<script type="text/javascript" src="std.js">
</script>
<style type="text/css">
body {font-family: "Nimbus Mono L", CourierNew, Courier;}
</style>
</head>
<body>
<?php
$id = mysql_real_escape_string($_GET['id']);
require("defs.php");
$link    = mysql_connect($dbsrv, $dbuser, $dbpasswd);
mysql_query("USE " . $dbname, $link);
$result = mysql_fetch_assoc(mysql_query("SELECT firstname, lastname, card_id FROM pupils WHERE id=" . $id));
echo $result['lastname'];
echo "<br />";
echo $result['firstname'];
echo "<br />";
?>
<img src="ean-13.php?code=<?php echo $result['card_id']; ?>" /><br />
<span id="ctrl" style="display: inline;">
<button onclick="$('ctrl').style.display = 'none'; window.print(); window.setTimeout('$(\'ctrl\').style.display = \'inline\'', 2000); ">Drucken</button>
<button onclick="window.top.killpopup()">Schlie&szlig;en</button>
</span>
</body>
</html>