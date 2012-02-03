colnames = new Array("Ort", "Medium", "2. Autor (Nachname)", "2. Autor (Vorname)", "ISBN", "Verlag", "Erscheinungsjahr", "Auflage", "Genre");

function process() {
	var req= new ajax();
	req.create("handle.php?do=setremindtext&text=" + encodeURIComponent($("text").value));
	var resp = req.fetch();
	
	if(isNaN($("fine").value) || $("fine").value == "" || parseFloat($("fine").value)<0) {
		alert("Ungültige Mangebühr");
		return;
	}
	else {
		req.create("handle.php?do=setfine&val=" + $("fine").value);
		var resp2 = req.fetch();
	}
	req.create("handle.php?do=setlpropts&val=" + $("lpr").value);
	var resp3 = req.fetch();
	if((resp == "done")&&(resp2 == "done")&&(resp3 == "done")) {
		alert("Text wurde übernommen");
	}
	else {
		alert(resp);
		alert(resp2);
		alert(resp3);
	
	}
}



function init() {
	var req = new ajax();
	req.create("handle.php?do=getremindtext");
	var resp = req.fetch();
	//alert(resp);
	$("text").value = resp;
	
	req.create("handle.php?do=getfine");
	var resp = req.fetch();
	//alert(resp);
	$("fine").value = resp;
	
	req.create("handle.php?do=getlpropts");
	var resp = req.fetch();
	//alert(resp);
	$("lpr").value = resp;
}
