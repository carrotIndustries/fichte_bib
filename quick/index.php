<?php require("../defs.php") ?>
<html>
<head>
<link rel="stylesheet" href="../black.css" />
<script type="text/javascript">
function check() {
	var f = document.forms.f.elements.c.value;
	if(f.length == 13) {
		if(f == "<?php echo $quick_lend; ?>" || f == "1337000000006") {
			location.href="lend.php";
		}
		if(f == "<?php echo $quick_return; ?>" || f == "1337000000013") {
			location.href="return.php";
		}
		if(f == "<?php echo $quick_cancel; ?>") {
			location.href="../listobjects.php";
		}
		
		
	}
	return false;
}
</script>
</head>
<body onload="document.forms.f.elements.c.value = ''; document.forms.f.elements.c.focus();">
<?php require("header.php"); ?>
<div class="quickhead">Aktion:</div>
<form action="#" name="f" onsubmit="return check();">
<input name="c" maxlength="13" size="13">
</form>
</body>
</html>
