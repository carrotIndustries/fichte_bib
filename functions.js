function getLocation(id) {
	var url = "handle.php?do=getlocation&id=" + id;
	var req = new ajax();
	req.create(url);
	return req.fetch();
}

function getMediaType(id) {
	var req = new ajax();
	req.create("handle.php?do=getmediatype&id=" + id);
	return req.fetch();
}
function getGroup(id) {
	var url = "handle.php?do=getgroup&id=" + id;
	var req = new ajax();
	req.create(url);
	return req.fetch();
}

function getUserCol(id) {
	var req = new ajax();
	var url = "handle.php?do=getusercols";
	req.create(url);
	var result = req.fetch().toJSON();
	var i= 0;
	while(i < result.length) {
		if(result[i].id == id) {
			return result[i].name;
		}
		i++;
	}
}

function getUserCols() {
	var req = new ajax();
	var url = "handle.php?do=getusercols";
	req.create(url);
	return req.fetch().toJSON();
}

function deleteObject(id) {
	var req = new ajax();
	req.create("handle.php?do=delete&id=" + id);
	var resp = req.fetch();
	if(resp == "done") {
		return true;
	}
	else {
		alert(resp);
		return false;
	}
}

function returnobject(id) {
	var url = "handle.php?do=return&id=" + id;
	var req = new ajax();
	req.create(url);
	var resp = req.fetch();
	if(resp == "done") {
		return true;
	}
	if(resp == "expdone") {
		return false;
	}
}


