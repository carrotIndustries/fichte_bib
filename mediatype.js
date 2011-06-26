function showThrobber() {
	$("throbber").style.visibility = "visible";
}
function hideThrobber() {
	$("throbber").style.visibility = "hidden";
}



function list() {
	showThrobber();
	var req = new ajax();
	var url = "handle.php?do=listmediatypes";
	
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
		new createElem("td", row.elem).createText("Medium");
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
					
			new createElem("td", row.elem).createText(result[i].media);
			var actions = new createElem("td", row.elem);
				var dodelete = new createElem("img", actions.elem);
				dodelete.createAttribute("OnClick", "dodeletemediatype(" + result[i].id + ")");
				dodelete.createAttribute("title", "Löschen");
				dodelete.createAttribute("src", "gfx/trash.png");
				dodelete.elem.style.cursor = "pointer";
				
				var doedit = new createElem("img", actions.elem);
				doedit.createAttribute("OnClick", "doeditmediatype(" + result[i].id + ")");
				doedit.createAttribute("title", "Ändern");
				doedit.createAttribute("src", "gfx/edit.png");
				doedit.elem.style.cursor = "pointer";
			
			
		i++;
	}
	
	hideThrobber();
}

function addmediatype() {
	showThrobber();
	var mt = window.prompt("Wie soll das neue Medium heißen?");
	if((mt != "") && (mt != null)) {
		var req = new ajax()
		var url = "handle.php?do=addmediatype&mt=" + encodeURIComponent(mt);
		//alert(url);
		req.create(url);
		var resp = req.fetch();
		if(resp != "done") {
			alert("Fehler");
			alert(resp);
		}
		else {
			alert("Medium wurde erfolgreich hinzugefügt");
			list();
		}
			
			
	}
	hideThrobber();
	
}


function deletemediatype(id) {
	var req = new ajax();
	req.create("handle.php?do=deletemediatype&id=" + id);
	var resp = req.fetch();
	if(resp == "done") {
		return true;
	}
	else {
		alert(resp);
		return false;
	}
}

function editmediatype(id, mt) {
	var req = new ajax();
	req.create("handle.php?do=editmediatype&id=" + id + "&mt=" + mt);
	var resp = req.fetch();
	if(resp == "done") {
		return true;
	}
	else {
		alert(resp);
		return false;
	}
}


function doeditmediatype(id) {
	showThrobber();
	var mt = window.prompt("Wie das Medium heißen?", getMediaType(id));
	if((mt != "") && (mt != null)) {
		var req = new ajax()
		var url = "handle.php?do=editmediatype&mt=" + encodeURIComponent(mt) + "&id=" + id;
		req.create(url);
		var resp = req.fetch();
		if(resp != "done") {
			alert("Fehler");
			alert(resp);
		}
		else {
			alert("Medium wurde erfolgreich geändert");
			list();
		}
			
			
	}
	hideThrobber();
	
}

function dodeletemediatype(id) {
	q=window.confirm("Soll das Medium wirklich gelöscht werden?");
	if(q) {
		if(deletemediatype(id)) {
			alert("Medium wurde gelöscht");
		}
		list();
	}
}
