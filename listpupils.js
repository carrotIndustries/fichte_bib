
function showThrobber() {
	$("throbber").style.visibility = "visible";
}
function hideThrobber() {
	$("throbber").style.visibility = "hidden";
}

function doSearch(f) {
	listpupils(f.search_for.value, f.match.value, encodeURIComponent(f.search.value), f.order_by.value, f.order.value, f.group.value);
	return false;
}

function can_lend(id) {
	var req = new ajax();
	var url ="handle.php?do=canlend&id=" + id;
	req.create(url);
	if(req.fetch() == 1) {
		return true;
	}
	else {
		return false;
	}
}


function listpupils(search_for, match, search, order_by, order, group) {
	showThrobber();
	var req = new XMLHttpRequest();
	var url = "handle.php?do=listpupils&search_for=" + search_for + "&match=" + match + "&search=" + search + "&group=" + group + "&order_by=" + order_by + "&order=" + order + "&group=" + group;
	var even = true;
	//alert(url);
	
	//url = encodeURI(url);
	//alert(url);
	req.open("GET", url, true);
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			var result = req.responseText.toJSON();
			hideThrobber();
			if($("table")) {
				new selectElement("table").destroy();
			}
			table = new createElem("table", $("tablecont"));
			table.setId("table");
			table.setClass("lister");
			//table.createAttribute("border", "1px");
			//table.setInnerHTML('<tr><td>Medium</td><td>1. Autor (Nachname)</td><td>1. Autor (Vorname)</td><td>2. Autor (Nachname)</td><td>2. Autor (Vorname)</td><td>Titel</td><td>ISBN</td><td>Verlag</td><td>Erscheinungsjahr</td><td>Auflage</td><td>Genre</td><td>Aktionen</td></tr>');
			var row = new createElem("tr", table.elem);
			row.setClass("head");
				/*new createElem("td", row.elem).createText("Nachname");
				new createElem("td", row.elem).createText("Vorname");
				new createElem("td", row.elem).createText("Klasse");
				new createElem("td", row.elem).createText("Kartennr.");
				new createElem("td", row.elem).createText("Gruppe");
				new createElem("td", row.elem).createText("Aktionen");
			*/
			
			
				var col = new createElem("td", row.elem);
				col.createText("Nachname");
				col.elem.style.cursor = "pointer";
				col.createAttribute("OnClick", "sort('lastname')")	
				
				var col = new createElem("td", row.elem);
				col.createText("Vorname");
				col.elem.style.cursor = "pointer";
				col.createAttribute("OnClick", "sort('firstname')")	
				
				var col = new createElem("td", row.elem);
				col.createText("Klasse");
				col.elem.style.cursor = "pointer";
				col.createAttribute("OnClick", "sort('class')")	
				
				var col = new createElem("td", row.elem);
				col.createText("Kartennr.");
				
				var col = new createElem("td", row.elem);
				col.createText("Gruppe");
				
				var col = new createElem("td", row.elem);
				col.createText("ID");
				
				var col = new createElem("td", row.elem);
				col.createText("Aktionen");
			
			i=0;
			while(i<result.length) {
				if(result[i].deleted == 0) {
					var row = new createElem("tr", table.elem);
					if(even) {
						row.setClass("evenrow");
						even = false;
					}
					else {
						row.setClass("oddrow");
						even = true;
					}
					new createElem("td", row.elem).createText(result[i].lastname);
					new createElem("td", row.elem).createText(result[i].firstname);
					new createElem("td", row.elem).createText(result[i].class);
					new createElem("td", row.elem).createText(result[i].card_id);
					new createElem("td", row.elem).createText(result[i].grp_name);
					new createElem("td", row.elem).createText(result[i].id);
					var actions = new createElem("td", row.elem);
					actions.setClass("actions");
						var dodelete = new createElem("img", actions.elem);
						dodelete.createAttribute("OnClick", "doDeletePupil(" + result[i].id + ")");
						dodelete.createAttribute("title", "Loeschen");
						dodelete.createAttribute("src", "gfx/trash.png");
						dodelete.elem.style.cursor = "pointer";
						
						var doedit = new createElem("img", actions.elem);
						doedit.createAttribute("OnClick", "doEditPupil(" + result[i].id + ")");
						doedit.createAttribute("title", "Aendern");
						doedit.createAttribute("src", "gfx/edit.png");
						doedit.elem.style.cursor = "pointer";
						
						var docard = new createElem("img", actions.elem);
						docard.createAttribute("OnClick", "pupilcard(" + result[i].id + ")");
						docard.createAttribute("title", "Schuelerkarte");
						docard.createAttribute("src", "gfx/pupilcard.png");
						docard.elem.style.cursor = "pointer";
						
						var dodisplay = new createElem("img", actions.elem);
						dodisplay.createAttribute("OnClick", "doDisplayPupil(" + result[i].id + ")");
						dodisplay.createAttribute("title", "Karteikarte");
						dodisplay.createAttribute("src", "gfx/page.png");
						dodisplay.elem.style.cursor = "pointer";
						
						if(result[i].can_lend == 1) {
							var dolend = new createElem("img", actions.elem);
							dolend.createAttribute("onclick", "location.href = 'listobjects.php?lend=" + result[i].id + "'");
							row.createAttribute("ondblclick", "location.href = 'listobjects.php?lend=" + result[i].id + "'");
							row.elem.style.cursor = "pointer";
							dolend.createAttribute("title", "Ausleihen");
							dolend.createAttribute("src", "gfx/lend.png");
							dolend.elem.style.cursor = "pointer";
						}
				}
				i++;
			}
		}
	}
	req.send(null);
	
	
}

function deletePupil(id) {
	var req = new ajax();
	req.create("handle.php?do=deletepupil&id=" + id);
	var resp = req.fetch();
	if(resp == "done") {
		return true;
	}
	else {
		alert(resp);
		return false;
	}
}

function doDeletePupil(id) {
	//alert(id);
	q=window.confirm("Soll der Schueler geloescht werden?");
	if(q) {
		if(deletePupil(id)) {
			alert("Schueler wurde geloescht");
		}
		list();
	}
}

function doEditPupil(id) {
	popup("editpupil.php?id=" + id, 500, 275);
}

function doDisplayPupil(id) {
	popup("display_pupil.php?id=" + id, 900, 500);
}

function pupilcard(id) {
	popup("pupilcard.php?id=" + id, 250, 175);
}

function sort(by) {
	f = document.forms["search"].elements;
	if(f.order_by.value == by) {
		if(f.order.value == "asc") {
			f.order.value = "desc";
		}
		else {
			f.order.value = "asc";
		}
	}
	else {
		f.order_by.value = by;
		f.order.value = "asc";
	}
	list();
		
}
