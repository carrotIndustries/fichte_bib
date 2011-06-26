
function process() {
	var f = document.forms["def"];
	var req = new ajax();
	req.create("handle.php?do=setlatest&date="+f.year.value+"-"+f.month.value+"-"+f.day.value+"&mode="+f.mode.value);
	var resp = req.fetch();
	if(resp == "done") {
		alert("Rückgabedatum übernommen!");
	}
	else {
		alert(resp);
	}
	return false;
}
