function showThrobber() {
	$("throbber").style.visibility = "visible";
}
function hideThrobber() {
	$("throbber").style.visibility = "hidden";
}

function longerobject(id) {
	q = window.prompt("Um wie viele Tage soll das Objekt verlaengert werden?");
	if(q != null) {
		if(!isNaN(q)) {
			var req = new ajax();
			var url ="handle.php?do=longer&id=" + id + "&days=" + q;
			req.create(url);
			var resp = req.fetch();
			if(resp == "done") {
				alert("Das Objekt wurde verlaengert");
				list();
			}
			else {
				alert("Fehler");
				alert(resp);
			}
		}
		else {
			alert("Das ist keine Zahl!");
		}
	}
}

function editobject(id) {
	var req = new ajax();
	var url ="handle.php?do=delete&id=" + id;
	req.create(url);
	var resp = req.fetch();
	
}

function packMediaTypes(f) {
	var req = new ajax();
	var url = "handle.php?do=listmediatypes";
	req.create(url);
	var mediatypes = req.fetch().toJSON();
	var i = 0;
	var str=""
	while(i<mediatypes.length) {
		str += mediatypes[i].id+":"+((f["mt"+mediatypes[i].id].checked)?"1":"0")+",";
		i++;
	}
	return str;
}
	

function doSearch(f) {
	listobjects(f.search_for.value, f.match.value, encodeURIComponent(f.search.value), f.limit.value, packMediaTypes(f), f.order_by.value, f.order.value, f.location.value, f.state.value);
	return false;
}


function get_display_cols() {
	var req = new ajax();
	var url = "handle.php?do=getdisplaycols";
	req.create(url);
	return req.fetch().toJSON();
}

function listobjects(search_for, match, search, limit, media_types, order_by, order, location, state) {
	showThrobber();
	//alert(media_types);
	var req = new XMLHttpRequest();
	var url = "handle.php?do=listobjects&search_for=" + search_for + "&match=" + match + "&search=" + search + "&limit=" + limit + "&mediatypes=" + media_types + "&order_by=" + order_by + "&order=" + order + "&location=" + location + "&state=" + state;
	var even = true;
	var cols = get_display_cols();
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
				if(cols[0]) {
					var col = new createElem("td", row.elem)
					col.createText("Ort");
				}
				if(cols[1]) {
					var col = new createElem("td", row.elem);
					col.createText("Medium");
				}
				
				var col = new createElem("td", row.elem);
				col.createText("1. Autor (Nachname)");
				col.elem.style.cursor = "pointer";
				col.createAttribute("OnClick", "sort('author1_lastname')")	
				
				var col = new createElem("td", row.elem);
				col.createText("1. Autor (Vorname)");
				col.elem.style.cursor = "pointer";
				col.createAttribute("OnClick", "sort('author1_firstname')")	
				
				if(cols[2]) {
					var col = new createElem("td", row.elem);
					col.createText("2. Autor (Nachname)");
					col.elem.style.cursor = "pointer";
					col.createAttribute("OnClick", "sort('author2_lastname')")	
				}
				
				if(cols[3]) {
					var col = new createElem("td", row.elem);
					col.createText("2. Autor (Vorname)");
					col.elem.style.cursor = "pointer";
					col.createAttribute("OnClick", "sort('author2_firstname')")	
				}
				
				var col = new createElem("td", row.elem);
				col.createText("Titel");
				col.elem.style.cursor = "pointer";
				col.createAttribute("OnClick", "sort('title')")	
				
				if(cols[4]) {
					var col = new createElem("td", row.elem);
					col.createText("ISBN");
				}
				
				if(cols[5]) {
					var col = new createElem("td", row.elem);
					col.createText("Verlag");
					col.elem.style.cursor = "pointer";
					col.createAttribute("OnClick", "sort('publisher')")	
				}				
				
				if(cols[6]) {
					var col = new createElem("td", row.elem);
					col.createText("Erscheinungsjahr");
					col.elem.style.cursor = "pointer";
					col.createAttribute("OnClick", "sort('year')")	
				}
				
				if(cols[7]) {
					var col = new createElem("td", row.elem);
					col.createText("Auflage");
					col.elem.style.cursor = "pointer";
					col.createAttribute("OnClick", "sort('edition')")	
				}
				
				if(cols[8]) {
					var col = new createElem("td", row.elem);
					col.createText("Genre");
					col.elem.style.cursor = "pointer";
					col.createAttribute("OnClick", "sort('genre')")	
				}
				
				var usercols = getUserCols();
				var j = 0;
				while (j<usercols.length) {
					var col = new createElem("td", row.elem);
					col.createText(usercols[j].name);
		
					j++;
				}
				
				var col = new createElem("td", row.elem);
				col.createText("Aktionen");
			
			i=0;
			
			if(result.length != 0) {
				$("add").style.display="none";
				while(i<result.length) {
					if(result[i].deleted == 0) {
						var row = new createElem("tr", table.elem);
						row.setId("tr_" + result[i].id);


						if(result[i].state == 1) {
							row.setColor("#ff0000");
						}
						if(result[i].state == 2) {
							row.setColor("#ff00ff");
						}
						if(even) {
							row.setClass("evenrow");
							even = false;
						}
						else {
							row.setClass("oddrow");
							even = true;
						}
						if(cols[0]) {new createElem("td", row.elem).createText(result[i].loc_name);}
						if(cols[1]) {new createElem("td", row.elem).createText(result[i].mt_name);}
						new createElem("td", row.elem).createText(result[i].author1_lastname);
						new createElem("td", row.elem).createText(result[i].author1_firstname);
						if(cols[2]) {new createElem("td", row.elem).createText(result[i].author2_lastname);}
						if(cols[3]) {new createElem("td", row.elem).createText(result[i].author2_firstname);}
						new createElem("td", row.elem).createText(result[i].title);
						if(cols[4]) {new createElem("td", row.elem).createText(result[i].isbn);}
						if(cols[5]) {new createElem("td", row.elem).createText(result[i].publisher);}
						if(cols[6]) {new createElem("td", row.elem).createText(result[i].year);}
						if(cols[7]) {new createElem("td", row.elem).createText(result[i].edition);}
						if(cols[8]) {new createElem("td", row.elem).createText(result[i].genre);}
						
						j=0;
						while (j<usercols.length) {
							var col = new createElem("td", row.elem);
							col.createText(result[i]["user"+usercols[j].id]);
		
							j++;
						}
						
						var actions = new createElem("td", row.elem);
						actions = new createElem("table", actions.elem);
						actions.setClass("action");
						firstrow = new createElem("tr", actions.elem);
							var dodelete = new createElem("img", new createElem("td", firstrow.elem).elem);
							dodelete.createAttribute("OnClick", "doDeleteObject(" + result[i].id + ")");
							//dodelete.createAttribute("href", "javascript:;");
							dodelete.createAttribute("title", "Loeschen");
							dodelete.createAttribute("src", "gfx/trash.png")
							dodelete.elem.style.cursor ="pointer";
							
							var doedit = new createElem("img", new createElem("td", firstrow.elem).elem);
							doedit.createAttribute("OnClick", "doEditObject(" + result[i].id + ")");
							doedit.createAttribute("title", "Aendern");
							doedit.createAttribute("src", "gfx/edit.png");
							doedit.elem.style.cursor ="pointer";
							
							var dodisplay = new createElem("img", new createElem("td", firstrow.elem).elem);
							dodisplay.createAttribute("OnClick", "doDisplayObject(" + result[i].id + ")");
							dodisplay.createAttribute("title", "Karteikarte");
							dodisplay.createAttribute("src", "gfx/page.png");
							dodisplay.elem.style.cursor ="pointer";
							
							secondrow = new createElem("tr", actions.elem);
							
							if(result[i].state == 0 && lend_pupil != -1) {
								var dolend = new createElem("img", secondrow.elem);
								dolend.createAttribute("OnClick", "addBasket(" + result[i].id + ")");
								dolend.createAttribute("title", "Ausleihen");
								dolend.createAttribute("src", "gfx/lend.png");
								dolend.elem.style.cursor = "pointer";
								row.createAttribute("ondblclick", "addBasket(" + result[i].id + ")");
								row.elem.style.cursor = "pointer";
							}
							if(result[i].state != 0) {
								var doreturn = new createElem("img",  new createElem("td", secondrow.elem).elem);
								doreturn.createAttribute("OnClick", "doReturnObject(" + result[i].id + ")");
								doreturn.createAttribute("title", "Zurueckgeben");
								doreturn.createAttribute("src", "gfx/return.png");
								doreturn.elem.style.cursor = "pointer";
								
								var dolonger = new createElem("img",  new createElem("td", secondrow.elem).elem);
								dolonger.createAttribute("OnClick", "longerobject(" + result[i].id + ")");
								dolonger.createAttribute("title", "Verlaengern");
								dolonger.createAttribute("src", "gfx/longer.png");
								dolonger.elem.style.cursor = "pointer";
								
								var doinfo = new createElem("img", new createElem("td", secondrow.elem).elem);
								doinfo.createAttribute("OnClick", "wholend(" + result[i].id + ")");
								doinfo.createAttribute("title", "Info");
								doinfo.createAttribute("src", "gfx/info.png");
								doinfo.elem.style.cursor = "pointer";

							}
							if(result[i].state == 2) {
								var doremind = new createElem("img",  new createElem("td", secondrow.elem).elem);
								doremind.createAttribute("OnClick", "doRemindObject(" + result[i].id + ")");
								doremind.createAttribute("title", "Mahnung");
								doremind.createAttribute("src", "gfx/mail.png");
								doremind.elem.style.cursor = "pointer";
							}
						
					}
					i++;
				}
			}
			else {
				if(search_for == "isbn") {
					$("addlink").href = "javascript:popup('addobject.php?inpopup&isbn=" + search + "', 1000, 600); "
					$("add").style.display="inline";
				}
				else {
					$("add").style.display="none";
				}
			}
			
		}
	}
	req.send(null);
	
	
}

function doDeleteObject(id) {
	q=window.confirm("Soll das Objekt gelöscht werden?");
	if(q) {
		if(deleteObject(id)) {
			alert("Objekt wurde gelöscht");
		}
		doSearch(document.forms["search"]);
	}
}


function doEditObject(id) {
	popup("editobject.php?id=" + id, 550, 550);
}

function doRemindObject(id) {
	popup("printremind.php?id=" + id, 900, 600);
}

function doDisplayObject(id) {
	popup("display_lends.php?id=" + id, 875, 500);
}

function doReturnObject(id) {
	if(returnobject(id)) {
		alert("Objekt wurde zurückgegeben");
		list();
	}
	else {
		alert("Objekt war überfällig");
		list();
	}
}

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
		basket.push(id);
		remainingobjects = maxlend - lended_objects- basket.length;
		$("remain").firstChild.nodeValue = remainingobjects;
		new selectElement("tr_" + id).setColor("orange");
		if(remainingobjects == 0) {
			checkout();
		}
	}
	else {
		alert("Das Objekt wird bereits ausgeliehen")
	}
}

function wholend(id) {
	var req = new ajax();
	req.create("handle.php?do=wholend&id=" + id);
	var resp = req.fetch().toJSON();
	alert("Das Objekt ist von " + resp[0].lastname + " " + resp[0].firstname + " ausgeliehen und fällig am " + resp[0].lend_expiredate);
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

