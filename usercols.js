function showThrobber() {
	$("throbber").style.visibility = "visible";
}
function hideThrobber() {
	$("throbber").style.visibility = "hidden";
}


function list() {
	showThrobber();
	var req = new ajax();
	var url = "handle.php?do=getusercols";
	
	//alert(url);
	url = encodeURI(url);
	//alert(url);
	req.create(url);
	var result = req.fetch().toJSON();
	//alert(result);
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
		new createElem("td", row.elem).createText("Spalte");
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
					
			new createElem("td", row.elem).createText(result[i].name);
			var actions = new createElem("td", row.elem);
				var dodelete = new createElem("img", actions.elem);
				dodelete.createAttribute("OnClick", "dodeleteusercol('" + result[i].id + "')");
				dodelete.createAttribute("title", "Loeschen");
				dodelete.createAttribute("src", "gfx/trash.png");
				dodelete.elem.style.cursor = "pointer";
				
				var doedit = new createElem("img", actions.elem);
				doedit.createAttribute("OnClick", "doeditusercol('" + result[i].id + "')");
				doedit.createAttribute("title", "Aendern");
				doedit.createAttribute("src", "gfx/edit.png");
				doedit.elem.style.cursor = "pointer";
			
			
		i++;
	}
	
	hideThrobber();
}

function addusercol() {
	showThrobber();
	var col = window.prompt("Wie soll die neue Spalte heißen?");
	if((col != "") && (col != null)) {
		var req = new ajax()
		var url = "handle.php?do=addusercol&col=" + encodeURIComponent(col);
		//alert(url);
		req.create(url);
		var resp = req.fetch();
		if(resp != "done") {
			alert("Fehler");
			alert(resp);
		}
		else {
			alert("Spalte wurde erfolgreich hinzugefuegt");
			list();
		}
			
			
	}
	hideThrobber();
	
}


function deleteusercol(id) {
	var req = new ajax();
	req.create("handle.php?do=deleteusercol&id=" + id);
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


function doeditusercol(id) {
	showThrobber();
	var col = window.prompt("Wie soll die Spalte heißen?", getUserCol(id));
	if((col != "") && (col != null)) {
		var req = new ajax()
		var url = "handle.php?do=editusercol&name=" + encodeURIComponent(col) + "&id=" + id;
		//alert(url);
		req.create(url);
		var resp = req.fetch();
		if(resp != "done") {
			alert("Fehler");
			alert(resp);
		}
		else {
			alert("Spalte wurde erfolgreich geändert");
			list();
		}
			
			
	}
	hideThrobber();
	
}

function dodeleteusercol(id) {
	q=window.confirm("Soll die Spalte UND ALLE IHRE INHALTE wirklich gelöscht werden?");
	if(q) {
		if(deleteusercol(id)) {
			alert("Spalte wurde geloescht");
		}
		list();
	}
}
