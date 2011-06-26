<?php require("../defs.php") ?>
<html>
<head>
<link rel="stylesheet" href="../black.css" />

<script type="text/javascript" src="../std.js">
</script>
<script type="text/javascript">
function check() {
	var f = document.forms.f.elements.c.value;
	if(f.length == 13) {
		if(f == "<?php echo $quick_cancel; ?>") {
			location.href =".";
		}
		else {
			var req = new ajax();
			req.create("../handle.php?do=pupilid&card_id="+f);
			id = req.fetch();
			if(id != "nonexist") {
				location.href ="lend_select.php?pupil=" + id;
			}
			else {
				alert("Es gibt diesen Schüler nicht.");
				document.forms.f.elements.c.value = '';
				document.forms.f.elements.c.focus();
			}
		}
		
	}
	else {
		var req = new ajax();
		req.create("../handle.php?do=pupilexist&id="+f);
		if(req.fetch() == "1") {
			location.href ="lend_select.php?pupil=" + f;
		}
		else {
			alert("Es gibt diesen Schüler nicht.");
				document.forms.f.elements.c.value = '';
				document.forms.f.elements.c.focus();
		}
	}
	return false;
}
</script>
</head>
<body onload="document.forms.f.elements.c.value = ''; document.forms.f.elements.c.focus();">
<?php require("header.php"); ?>
<div class="quickhead">Ausleihen: Wer?</div>
<form action="#" name="f" onsubmit="return check();">
<input name="c" maxlength="13" size="13">
</form>
</body>
</html>
