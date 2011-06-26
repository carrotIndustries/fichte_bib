function showThrobber() {
	$("throbber").style.visibility = "visible";
}
function hideThrobber() {
	$("throbber").style.visibility = "hidden";
}


function list() {
	showThrobber();
	var req = new ajax();
	var url = "handle.php?do=listgroups";
	var even =true;
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
	var row = new createElem("tr", table.elem);
	row.setClass("head");
		new createElem("td", row.elem).createText("Name");
		new createElem("td", row.elem).createText("Max. auszuleihende Obj.");
		new createElem("td", row.elem).createText("Max. Ausleihdauer:");
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
			new createElem("td", row.elem).createText(result[i].maxlend);
			new createElem("td", row.elem).createText(result[i].duration);
			var actions = new createElem("td", row.elem);
				var dodelete = new createElem("img", actions.elem);
				dodelete.createAttribute("OnClick", "dodeletegroup(" + result[i].id + ")");
				dodelete.createAttribute("title", "Löschen");
				dodelete.createAttribute("src", "gfx/trash.png");
				dodelete.elem.style.cursor = "pointer";
				
				var doedit = new createElem("img", actions.elem);
				doedit.createAttribute("OnClick", "doeditlocation(" + result[i].id + ")");
				doedit.createAttribute("title", "Aendern");
				doedit.createAttribute("src", "gfx/edit.png");			
				doedit.elem.style.cursor = "pointer";
			
		i++;
	}
	var row = new createElem("tr", table.elem);
	
	row.setClass("evenrow");
	var iname = new createElem("input", new createElem("td", row.elem).elem);
	iname.createAttribute("type", "text");
	iname.setId("iname");
	var iobj =  new createElem("input", new createElem("td", row.elem).elem);
	iobj.createAttribute("type", "text");
	iobj.setId("iobj");
	var idur =  new createElem("input", new createElem("td", row.elem).elem);
	idur.createAttribute("type", "text");
	idur.setId("idur");
	
	var doadd = new createElem("img", new createElem("td", row.elem).elem);
	doadd.createAttribute("OnClick", "process()");
	doadd.createAttribute("title", "Hinzufügen");
	doadd.createAttribute("src", "gfx/plus.png");
	doadd.elem.style.cursor = "pointer";
	hideThrobber();
}

function addgroup(name, maxlend, duration) {
	var req = new ajax();
	var url = "handle.php?do=addgroup&name=" + name + "&maxlend=" + maxlend + "&duration=" + duration;
	//url = encodeURI(url);
	//alert(url);
	req.create(url);
	resp = req.fetch();

	if(resp == "done") {
		return true;
	}
	else {
		alert(resp);
		return false;
	}
}

function process() {
	var valid = true;
	var notFilledFields = new Array();
	if($("iname").value == "") {
		notFilledFields.push("Name");
		valid = false;
	}
		
	if(isNaN($("iobj").value) || $("iobj").value == "") {
		notFilledFields.push("Max. auszuleihende Obj.");
		alert("Das ist keine Zahl");
		valid = false;
	}
	
	if(isNaN($("idur").value) || $("idur").value == "") {
		notFilledFields.push("Max. Ausleihdauer:");
		alert("Das ist keine Zahl");
		valid = false;
	}
	
	if(!valid) {
		alert("Es warten noch Felder darauf richtig ausgefuellt zu werden: \n" + notFilledFields.join("\n"));
	}
	else {
		if(addgroup(encodeURIComponent($("iname").value), $("iobj").value, $("idur").value)) {
			alert("Gruppe wurde hinzugefuegt");
			list();
		}
		else {
			alert("Fehler");
		}
	}
	return false;
}

function deletegroup(id) {
	var req = new ajax();
	req.create("handle.php?do=deletegroup&id=" + id);
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
		popup("editgroup.php?id=" + id, 600, 210);
}



function dodeletegroup(id) {
	//alert(id);
	q=window.confirm("Soll die Gruppe wirklich geloescht werden?");
	if(q) {
		if(deletegroup(id)) {
			alert("Gruppe wurde geloescht");
		}
		list();
	}
}
