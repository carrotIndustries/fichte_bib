function editgroup(id, name, maxlend, duration) {
	var req = new ajax();
	var url = "handle.php?do=editgroup&id=" + id + "&name=" + name + "&maxlend=" + maxlend + "&duration=" + duration;
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
	var f = document.forms["edit"].elements;
	if(f['name'].value == "") {
		notFilledFields.push("Name");
		valid = false;
	}
		
	if(isNaN(f['maxlend'].value) || f['maxlend'].value == "") {
		notFilledFields.push("Max. auszuleihende Obj.");
		alert("Das ist keine Zahl");
		valid = false;
	}
	
	if(isNaN(f['duration'].value) || f['duration'].value == "") {
		notFilledFields.push("Max. Ausleihdauer:");
		alert("Das ist keine Zahl");
		valid = false;
	}
	
	if(!valid) {
		alert("Es warten noch Felder darauf richtig ausgefuellt zu werden: \n" + notFilledFields.join("\n"));
	}
	else {
		if(editgroup(document.forms['edit'].elements['id'].value, encodeURIComponent(document.forms['edit'].elements['name'].value), document.forms['edit'].elements['maxlend'].value, document.forms['edit'].elements['duration'].value)) {
			window.top.list();
			alert("Gruppe wurde gespeichert");
			window.top.killpopup();
		}
		else {
			alert("Fehler");
		}
	}
	return false;
}
