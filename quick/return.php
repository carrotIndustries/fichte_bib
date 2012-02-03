<?php
require("../functions.php");
?> 
<html>
<head>
<link rel="stylesheet" href="../black.css" />

<script src="../std.js" type="text/javascript">
</script>

<script src="functions.js" type="text/javascript">
</script>
<script type="text/javascript">

	function returnobject(id) {
		var url = "../handle.php?do=return&id=" + id;
		var req = new ajax();
		req.create(url);
		var resp = req.fetch();
		if(resp == "done") {
			return true;
		}
		if(resp == "expdone") {
			return false;
		}
	}
	function clearStatus() {
		new selectElement("msg").setText("");
	}
	function doReturnObject(id) {
		var req = new ajax();
		req.create("../handle.php?do=canbelend&id=" + id);
		if(req.fetch() == 1) {
			alert("Das Objekt ist nicht ausgeliehen!");
			return;
		}
		var req = new ajax();
		req.create("../handle.php?do=wholend&id=" + id);
		var resp = req.fetch().toJSON();
		if(returnobject(id)) {
			new selectElement("msg").setText("Objekt zurückgegeben");
			window.setTimeout(clearStatus, 1000);
		}
		else {
			new selectElement("msg").setText("Objekt war überfällig am "+resp[0].lend_expiredate);
			window.setTimeout(clearStatus, 2000);
		}
		if($("sel")) {
			new selectElement("sel").destroy();
		}
		document.forms.f.elements.c.disabled = false;
		document.forms.f.elements.c.value = '';
		document.forms.f.elements.c.focus();
	}
	
	
	
	
	function check() {
		var f = document.forms.f.elements.c.value;
		if(f.length == 13) {
			if(f == "<?php echo $quick_cancel; ?>") {
				location.href = ".";
			}
			else {
				isbntoid(f);
				document.forms.f.elements.c.value = '';
				document.forms.f.elements.c.focus();
			}
				
		}
		return false;
	}
	function isbntoid(id) {
		var f = document.forms.f.elements.c.value;
		var req = new ajax();
		req.create("../handle.php?do=isbntoid&isbn="+f);
		var resp  = req.fetch().toJSON();
		if(resp.length == 0)  {
			alert("Dieses Objekt existiert nicht!");
		}
		if(resp.length == 1) {
			doReturnObject(resp[0].id);

		}
		if(resp.length > 1) {
			document.forms.f.elements.c.disabled = true;
			var i = 0;
			
			if($("sel")) {
				new selectElement("sel").destroy();
			}
			sel = new createElem("div", $("selcont"));
			sel.setId("sel");
			while(i<resp.length) {
				var lnk = new createElem("a", sel.elem);
				lnk.createText(resp[i].title);
				lnk.createAttribute("href", "javascript:;");
				new createElem("br", sel.elem);
				lnk.createAttribute("onclick", "doReturnObject(" + resp[i].id + ")");
				i++;
			}
		}
			
		
	}
	
	function init() {
		document.forms.f.elements.c.value = '';
		document.forms.f.elements.c.focus();
		new selectElement("msg").createText("");
		
	}
</script>
</head>
<body onLoad="init();">
<?php require("header.php") ?>

<div id="selcont">
</div>
<div class="quickhead"> Zurückgeben: Was?</div>
<form action="#" name="f" onsubmit="return check();">
<input name="c" maxlength="13" size="13">
</form>
<div id="msg">
</div>
</body>
</html>
