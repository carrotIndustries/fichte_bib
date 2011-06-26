function popup(url, width, height) {
	overlay = new createDivElement();
	overlay.appear();
	overlay.setId("over");
	overlay.setX(0);
	overlay.setY(0);
	overlay.elem.style.width = "100%";
	overlay.elem.style.height = "100%";
	overlay.setBgColor("white");
	overlay.setOpacity(0.5);
	overlay.setZindex(100);
	//$("over").style.opacity = parseFloat($("over").style.opacity) + 0.3;
	overlay.elem.style.position = "fixed";
	iframe = new createElem("iframe", $body());
	iframe.setId("ifrm");
	iframe.setWidth(parseInt(width) + "px");
	iframe.setHeight(parseInt(height) + "px");
	iframe.setX((parseInt(window.innerWidth)-20)/2- (parseInt(width)/2) + "px");
	iframe.setY((parseInt(window.innerHeight)/2)- (parseInt(height)/2) + "px");
	iframe.createAttribute("src", url);
	iframe.setBgColor("white");
	iframe.setZindex(101);
	iframe.elem.style.position = "fixed";
}

function killpopup() {
	new selectElement("over").destroy();
	new selectElement("ifrm").destroy();
}