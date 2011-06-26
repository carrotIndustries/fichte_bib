<?php
require("functions.php");

?> 
<html>
<head>
<script src="std.js" type="text/javascript">
</script>
<script src="listobjects.js" type="text/javascript">
</script>
<script src="columns.js" type="text/javascript">
</script>

<script src="functions.js" type="text/javascript">
</script>
<script type="text/javascript">
	
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onLoad="init()">
<?php require("header.php") ?>
Anzuzeigende Spalten:
<form id="input" name="input" OnSubmit="return false;">
	
</form>
<input type="button" value="Übernehmen" onclick="process()">
</body>
</html>
