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
	var f = document.forms["add"].elements;
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
		if(addgroup(encodeURIComponent(document.forms['add'].elements['name'].value), document.forms['add'].elements['maxlend'].value, document.forms['add'].elements['duration'].value)) {
			alert("Gruppe wurde hinzugefuegt");
			location.href = "listgroups.php";
		}
		else {
			alert("Fehler");
		}
	}
	return false;
}

function getrand() {
	showThrobber();
	var req = new ajax()
	req.create("handle.php?do=generate&prefix=0815");
	hideThrobber();
	return req.fetch();
}

