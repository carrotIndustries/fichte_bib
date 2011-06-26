<?php
require("functions.php");

?> 
<html>
<head>
<script src="std.js" type="text/javascript">
</script>
<script src="listgroups.js" type="text/javascript">
</script>
<script src="functions.js" type="text/javascript">
</script>
<script src="popup.js" type="text/javascript">
</script>
<link rel="stylesheet" href="black.css" />
<style type="text/css">
</style>
</head>
<body onLoad="list()">
<?php require("header.php") ?>
<img src="gfx/throbber.gif" style="float:right; visibility:hidden; " id="throbber"/>
<br />
<div id="tablecont">
</div>

</body>
</html>