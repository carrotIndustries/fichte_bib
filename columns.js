colnames = new Array("Ort", "Medium", "2. Autor (Nachname)", "2. Autor (Vorname)", "ISBN", "Verlag", "Erscheinungsjahr", "Auflage", "Genre");

function process() {
	var i= 0;
	var result = "";
	while(i<colnames.length) {
		result += $("c"+i).checked?1:0;
		i++;
	}
	//alert(result);
	var req = new ajax();
	req.create("handle.php?do=setdisplaycols&cols="+result);
	var resp = req.fetch();
	if(resp == "done") {
		alert("Spalten wurden übernommen");
	}
	else {
		alert(resp);
	
	}
}

function init() {
	var cols = get_display_cols();
	var form = new selectElement("input");
	var i= 0;
	while(i<colnames.length) {
		var box = new createElem("input", form.elem);
		box.createAttribute("type", "checkbox");
		box.createAttribute("value", i);
		box.createAttribute("id", "c"+i);
		if(cols[i]) {
			box.createAttribute("checked", "checked");
		}
		new createElem("span", form.elem).createText(colnames[i]);
		new createElem("br", form.elem);
		i++;
		
		
	}
}
