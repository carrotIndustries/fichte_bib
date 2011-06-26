<?php
require("functions.php");

?> 
<html>
<head>
<script src="std.js" type="text/javascript">
</script>
<script src="location.js" type="text/javascript">
</script>
<script src="functions.js" type="text/javascript">
</script>
<script type="text/javascript">
	
</script>
<link rel="stylesheet" href="black.css" />
</head>
<body onLoad="list()">
<?php require("header.php") ?>
<img src="gfx/throbber.gif" style="float:right; visibility:hidden; " id="throbber"/>
<button onclick="addlocation();">Neu... </button>
<div id="tablecont">
</div>

</body>
</html>