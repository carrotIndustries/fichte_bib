function showThrobber() {
	$("throbber").style.visibility = "visible";
}
function hideThrobber() {
	$("throbber").style.visibility = "hidden";
}


function list() {
	showThrobber();
	var req = new ajax();
	var url = "handle.php?do=listlocations";
	
	//alert(url);
	url = encodeURI(url);
	//alert(url);
	req.create(url);
	var result = req.fetch().toJSON()
	if($("table")) {
		new selectElement("table").destroy();
	}
	table = new createElem("table", $("tablecont"));
	table.setId("table");
	//table.createAttribute("border", "1px");
	table.setClass("listernx");
	var even = true;
	var row = new createElem("tr", table.elem);
	row.setClass("head");
		new createElem("td", row.elem).createText("Ort");
		new createElem("td", row.elem).createText("Aktionen");
	i=0;
	while(i<result.length) {
		var row = new createElem("tr", table.elem);
		if(even) {
			row.setClass("evenrow");
			even = false;
		}
		else {
			row.setClass("oddrow");
			even = true;
		}
					
			new createElem("td", row.elem).createText(result[i].location);
			var actions = new createElem("td", row.elem);
				var dodelete = new createElem("img", actions.elem);
				dodelete.createAttribute("OnClick", "dodeletelocation(" + result[i].id + ")");
				dodelete.createAttribute("title", "Loeschen");
				dodelete.createAttribute("src", "gfx/trash.png");
				dodelete.elem.style.cursor = "pointer";
				
				var doedit = new createElem("img", actions.elem);
				doedit.createAttribute("OnClick", "doeditlocation(" + result[i].id + ")");
				doedit.createAttribute("title", "Aendern");
				doedit.createAttribute("src", "gfx/edit.png");
				doedit.elem.style.cursor = "pointer";
			
			
		i++;
	}
	
	hideThrobber();
}

function addlocation() {
	showThrobber();
	var location = window.prompt("Wie soll der neue Ort heißen?");
	if((location != "") && (location != null)) {
		var req = new ajax()
		var url = "handle.php?do=addlocation&location=" + encodeURIComponent(location);
		//alert(url);
		req.create(url);
		var resp = req.fetch();
		if(resp != "done") {
			alert("Fehler");
			alert(resp);
		}
		else {
			alert("Ort wurde erfolgreich hinzugefuegt");
			list();
		}
			
			
	}
	hideThrobber();
	
}


function deletelocation(id) {
	var req = new ajax();
	req.create("handle.php?do=deletelocation&id=" + id);
	var resp = req.fetch();
	if(resp == "done") {
		return true;
	}
	else {
		alert(resp);
		return false;
	}
}

function editlocation(id, location) {
	var req = new ajax();
	req.create("handle.php?do=editlocation&id=" + id + "&location=" + location);
	var resp = req.fetch();
	if(resp == "done") {
		return true;
	}
	else {
		alert(resp);
		return false;
	}
}


function doeditlocation(id) {
	showThrobber();
	var location = window.prompt("Wie soll der Ort heißen?", getLocation(id));
	if((location != "") && (location != null)) {
		var req = new ajax()
		var url = "handle.php?do=editlocation&location=" + encodeURIComponent(location) + "&id=" + id;
		req.create(url);
		var resp = req.fetch();
		if(resp != "done") {
			alert("Fehler");
			alert(resp);
		}
		else {
			alert("Ort wurde erfolgreich geaendert");
			list();
		}
			
			
	}
	hideThrobber();
	
}

function dodeletelocation(id) {
	q=window.confirm("Soll der Ort wirklich geloescht werden?");
	if(q) {
		if(deletelocation(id)) {
			alert("Ort wurde geloescht");
		}
		list();
	}
}
