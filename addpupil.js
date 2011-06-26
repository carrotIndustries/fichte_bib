function addpupil(lastname, firstname, pclass, card_id, group) {
	var req = new ajax();
	var url = "handle.php?do=addpupil&lastname=" + lastname + "&firstname=" + firstname + "&class=" + pclass + "&card_id=" + card_id + "&group=" + group;
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

function getrand() {
	//showThrobber();
	var req = new ajax()
	req.create("handle.php?do=generate&prefix=4711");
	//hideThrobber();
	document.forms["add"].elements["card_id"].value = req.fetch();
}

function process() {
	var valid = true;
	var notFilledFields = new Array();
	var f = document.forms["add"].elements;
	
	if(f['lastname'].value == "") {
		notFilledFields.push("Nachname");
		valid = false;
	}
		
	if(f['firstname'].value == "") {
		notFilledFields.push("Vorname");
		valid = false;
	}
	
	if(f['class'].value == "") {
		notFilledFields.push("Klasse");
		valid = false;
	}
	
	if(f['card_id'].value == "" || isNaN(f['card_id'].value)) {
		notFilledFields.push("Kartennummer");
		alert("Das ist keine Zahl");
		valid = false;
	}
	if(f['card_id'].value.length != 13) {
		notFilledFields.push("Kartennummer");
		alert("Die Kartennummer muss 13 Stelle haben.");
		valid = false;
	}	
	
	
	if(!valid) {
		alert("Es warten noch Felder darauf richtig ausgefuellt zu werden: \n" + notFilledFields.join("\n"));
	}
	else {
		if(addpupil(encodeURIComponent(document.forms['add'].elements['lastname'].value), encodeURIComponent(document.forms['add'].elements['firstname'].value), encodeURIComponent(document.forms['add'].elements['class'].value), document.forms['add'].elements['card_id'].value, document.forms['add'].elements['group'].value)) {
			alert("Schueler wurde hinzugefuegt");
			location.href = "listpupils.php";
		}
		else {
			alert("Fehler");
		}
	}
	return false;
}



