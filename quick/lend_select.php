<?php
require("../functions.php");
$pupil =  $_GET["pupil"];
$pupil = mysql_real_escape_string($pupil);
?> 
<html>
<head>
<link rel="stylesheet" href="../black.css" />

<script src="../std.js" type="text/javascript">
</script>

<script src="functions.js" type="text/javascript">
</script>
<script type="text/javascript">
	function getObject(id) {
		var url = "../handle.php?do=getobject&id=" + id;
		var req = new ajax();
		req.create(url);
		return req.fetch().toJSON();
	}

	
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
			var req = new ajax();
			req.create("../handle.php?do=canbelend&id=" + id);
			if(req.fetch() == 1) {
		
				resp = getObject(id);
				basket.push(id);
				remainingobjects = maxlend - lended_objects- basket.length;
				$("remain").firstChild.nodeValue = remainingobjects;
				var item = new createElem("div", $("list"));
				item.createText(resp[0].title);
			
				
				if(remainingobjects == 0) {
					checkout();
				}
				
			}
			else {
				alert("Huch! Das Objekt ist bereits ausgeliehen!");
			}
		}
		else {
			alert("Das Objekt wird bereits ausgeliehen")
		}
		if($("sel")) {
			new selectElement("sel").destroy();
		}
		document.forms.f.elements.c.disabled = false;
		document.forms.f.elements.c.value = '';
		document.forms.f.elements.c.focus();
	}
	pupil = <?php echo $_GET["pupil"]; ?>;
	function checkout() {
		location.href = "../lend.php?pupil=" + pupil + "&objects=" + basket.join(";");
	}
	
	
	
	lended_objects = <?php
		$result = mysql_fetch_superarray(mysql_query("SELECT * FROM lend WHERE lend_returndate='0-0-0' AND pupil_id=" . $pupil));
		echo count($result);?>;
	maxlend = <?php
		$result = mysql_fetch_superarray(mysql_query("SELECT maxlend FROM pupils,groups WHERE pupils.id=" . $pupil . " AND groups.id = pupils.grp"));
		echo $result[0]["maxlend"];?>;
	
	function check() {
		var f = document.forms.f.elements.c.value;
		if(f.length == 13) {
			if(f == "<?php echo $quick_cancel; ?>") {
				location.href = "lend.php";
			}
			else {
				if(f == "<?php echo $quick_lend; ?>") {
					if(basket.length != 0) {
						checkout();
					}
					document.forms.f.elements.c.value = '';
					document.forms.f.elements.c.focus();
				}
				else {
					isbntoid(f);
					document.forms.f.elements.c.value = '';
					document.forms.f.elements.c.focus();
				}
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
			window.open("../addobject.php?isbn=" + f);
		}
		if(resp.length == 1) {
			addBasket(resp[0].id);

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
				lnk.createAttribute("onclick", "addBasket(" + resp[i].id + ")");
				i++;
			}
		}
			
		
	}
	
	function init() {
		document.forms.f.elements.c.value = '';
		document.forms.f.elements.c.focus();
		remainingobjects = maxlend - lended_objects;
		if(remainingobjects == 0) {
			document.forms.f.elements.c.disabled = true;
		}

		$("remain").firstChild.nodeValue = remainingobjects;
	}
</script>
</head>
<body onLoad="init();">
<?php require("header.php") ?>
<div class="quickhead">
<?php
$result = mysql_fetch_superarray(mysql_query("SELECT * FROM pupils WHERE id=" . $pupil));
echo "Ausleihen: " . $result[0]["lastname"] . " " . $result[0]["firstname"];
echo "; Verbleibende Objekte:<span id='remain'> </span>"; 
?>
</div
<div id="selcont">
</div>
<form action="#" name="f" onsubmit="return check();">
<input name="c" maxlength="13" size="13">
</form>
<div id="list">
</div>
</body>
</html>