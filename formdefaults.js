
function process() {
	var f = document.forms["def"];
	var val = (f["search_for_obj"].value+","+(f["search_inc_obj"].checked?"1":"0")+","+f["search_for_pupil"].value+","+(f["search_inc_pupil"].checked?"1":"0"));
	var req = new ajax();
	req.create("handle.php?do=setformdefaults&val="+val);
	var resp = req.fetch();
	if(resp == "done") {
		alert("Formulareinstellungen übernommen!");
	}
	else {
		alert(resp);
	}
	return false;
}



function init() {
	var req = new ajax();
	req.create("handle.php?do=getremindtext");
	var resp = req.fetch();
	//alert(resp);
	$("text").value = resp;
	
	req.create("handle.php?do=getfine");
	var resp = req.fetch();
	//alert(resp);
	$("fine").value = resp;
}
