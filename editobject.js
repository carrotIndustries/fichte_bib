function editobject(id, media_type, author1_lastname, author1_firstname, author2_lastname, author2_firstname, title, isbn, publisher, year, edition, genre, location, usercols) {
	var s = "";
	var i = 0;
	while (i<usercols.length) {
		s+=usercols[i].id+":"+usercols[i].val+";";
		i++;
	}
	var req = new ajax();
	var url = "handle.php?do=editobject&id=" + id + "&media_type=" + media_type + "&author1_lastname=" + author1_lastname + "&author1_firstname=" + author1_firstname + "&author2_lastname=" + author2_lastname + "&author2_firstname=" + author2_firstname + "&title=" + title + "&isbn=" + isbn + "&publisher=" + publisher + "&year=" + year + "&edition=" + edition + "&genre=" + genre + "&location=" + location + "&usercols=" + s;
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
	showThrobber();
	var req = new ajax()
	req.create("handle.php?do=generate&prefix=0815");
	hideThrobber();
	return req.fetch();
}

function showThrobber() {
	$("throbber").style.visibility = "visible";
}
function hideThrobber() {
	$("throbber").style.visibility = "hidden";
}

function fetchISBN(isbn) {
	var requ = new ajax();
	requ.create("handle.php?do=fetch&url=" + isbn);
	var xml = requ.fetchXML();
	var retobj = {};
	retobj.valid = true;
	if(xml.getElementsByTagName("entry").length == 0) {
		retobj.valid = false;
		return retobj;
	}
	
	retobj.author1_firstname = "";
	retobj.author1_lastname = "";
	
	if(xml.getElementsByTagName("dc:creator")[0]) {
		var author1 = xml.getElementsByTagName("dc:creator")[0].firstChild.nodeValue;
		var author1_arr = author1.split(" ");
		
		var author1_firstname_arr = author1_arr.slice(0, -1);
		var author1_firstname = author1_firstname_arr.join(" ");
		var author1_lastname = author1_arr[author1_arr.length - 1];
		retobj.author1_firstname = author1_firstname;
		retobj.author1_lastname = author1_lastname;
	}
	
	retobj.author2_firstname = "";
	retobj.author2_lastname = "";
	if(xml.getElementsByTagName("dc:creator")[1]) {
		var author2 = xml.getElementsByTagName("dc:creator")[1].firstChild.nodeValue;
		var author2_arr = author2.split(" ");
	
		var author2_firstname_arr = author2_arr.slice(0, -1);
		var author2_firstname = author2_firstname_arr.join(" ");
		var author2_lastname = author2_arr[author2_arr.length - 1];
		retobj.author2_firstname = author2_firstname;
		retobj.author2_lastname = author2_lastname;
	}
	var year = parseInt(xml.getElementsByTagName("dc:date")[0].firstChild.nodeValue);
	retobj.year = "";
	retobj.year = year;
	var title = xml.getElementsByTagName("dc:title")[0].firstChild.nodeValue;
	retobj.title = title;
	retobj.publisher = "";
	if(xml.getElementsByTagName("dc:publisher")[0]) {
		var publisher = xml.getElementsByTagName("dc:publisher")[0].firstChild.nodeValue;
		retobj.publisher = publisher;
	}
	return retobj;
}

function autocomplete() {
	var f = document.forms["edit"].elements;
	showThrobber();
	var infs = fetchISBN(document.forms['edit'].elements['isbn'].value);
	if(infs.valid == true) {
		f['author1_lastname'].value = infs.author1_lastname;
		f['author1_firstname'].value = infs.author1_firstname;
		
		f['author2_lastname'].value = infs.author2_lastname;
		f['author2_firstname'].value = infs.author2_firstname;
		
		f['title'].value = infs.title;
		f['publisher'].value = infs.publisher;
		f['year'].value = infs.year;
		//document.forms["book"].submit();
		$("confirm").style.display = 'inline';
	}
	else {
		alert("Nicht moeglich");
		//document.forms['book'].elements['isbn'].value = "";
	}
	hideThrobber();
}

function packUserCols() {
	var cols = getUserCols();
	var i =0;
	var obj=new Array();
	var f = document.forms["edit"].elements;
	while(i<cols.length) {
		obj.push({val :encodeURIComponent(f["user"+cols[i].id].value), id:cols[i].id});
		i++;
	}
	return obj;
}

function process() {
	var valid = true;
	var notFilledFields = new Array();
	var f = document.forms["edit"].elements;
	if(f['title'].value == "") {
		notFilledFields.push("Titel");
		valid = false;
	}
	if(f['isbn'].value == "") {
		//ISBN validiern
		notFilledFields.push("ISBN");
		valid = false;
	}
	
	if(isNaN(f['year'].value) || f['year'].value == "") {
		notFilledFields.push("Erscheinugsjahr");
		alert("Sie haben keine gueltige Jahreszahl als Erscheinungsdatum angegeben!");
		valid = false;
	}
	
	if(f["isbn"].value.length != 13) {
		notFilledFields.push("ISBN");
		alert("Die ISBN muss 13 Stellen haben");
		valid = false;
	}
	
	if(isNaN(f['edition'].value) || f['edition'].value =="") {
		notFilledFields.push("Auflage");
		alert("Sie haben keine gueltige Zahl als Auflage angegeben!");
		valid = false;
	}
	
	if(!valid) {
		alert("Es warten noch Felder darauf richtig ausgefuellt zu werden: \n" + notFilledFields.join("\n"));
	}
	else {
		if(editobject(document.forms['edit'].elements['id'].value, encodeURIComponent(document.forms['edit'].elements['media_type'].value), encodeURIComponent(document.forms['edit'].elements['author1_lastname'].value), encodeURIComponent(document.forms['edit'].elements['author1_firstname'].value), encodeURIComponent(document.forms['edit'].elements['author2_lastname'].value), encodeURIComponent(document.forms['edit'].elements['author2_firstname'].value), encodeURIComponent(document.forms['edit'].elements['title'].value), document.forms['edit'].elements['isbn'].value, encodeURIComponent(document.forms['edit'].elements['publisher'].value), document.forms['edit'].elements['year'].value, encodeURIComponent(document.forms['edit'].elements['edition'].value), encodeURIComponent(document.forms['edit'].elements['genre'].value), encodeURIComponent(document.forms['edit'].elements['location'].value), packUserCols())) {
			window.top.list();
			alert("Objekt wurde geaendert");
			window.top.killpopup();
		}
		else {
			alert("Fehler");
		}
		
	}
	
	return false;
}

