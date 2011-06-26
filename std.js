String.prototype.superReplace = function(s, r) {
	var arr = this.split(s);
	return arr.join(r);
}

String.prototype.toJSON = function() {
	try {
		var out = eval("(" + this + ")");
		return out;
	}
	catch(e) {
		alert(this);
	}
}

String.prototype.nl2br = function() {
	var requ = new ajax();
	requ.createPOST("handle.php?action=nl2br&file=.", "data="+escape(this));
	return requ.fetch();
}

Array.prototype.serialize = function(Elements) {
	//return assembleJSON(this, Elements);
	return this.toSource();
}

Array.prototype.sortJSON = function(Element) {
	var outArray = new Array();
	var i = 0;
	while(i < this.length) {
		outArray.push(eval("this["+i+"]."+Element)+"##"+i);
		i++;
	}
	outArray = outArray.sort();
	var endArray = new Array();
	var j = 0;
	while(j < outArray.length) {
		var last = outArray[j].split("##")[1];
		endArray.push(this[last]);
		j++;
	}
	
	return endArray;
}

Array.prototype.existJSON = function (element, value) {
	var i = 0;
	var existing = false;
	while(i < this.length) { 
		if(eval("this["+i+"]."+element) == value) {
			existing = true;
			break;
		}
		i++;
	}
	return existing;
}

Array.prototype.findJSON = function (element, value) {
	var i = 0;
	var nr = false;
	while(i < this.length) { 
		if(eval("this["+i+"]."+element) == value) {
			nr = i;
			break;
		}
		i++;
	}
	return nr;
}

Array.prototype.multiFindJSON = function (element, value) {
	var i = 0;
	var nr = new Array();
	while(i < this.length) { 
		if(eval("this["+i+"]."+element).indexOf(value) != -1) {
			nr.push(i);
		}
		i++;
	}
	return nr;
}

String.prototype.script = function(id) {
	var script = new createElem("script", $("pageHead"));
	script.createAttribute("type", "text/javascript");
	//script.createAttribute("src", file);
	script.setInnerHTML(this);
	script.setId(id);
}

var stdglobal = {
}

function $ (idToGet) {
	return document.getElementById(idToGet);
}

function $body() {
	return document.getElementsByTagName("body")[0];
}

function removeSlashes(input) {
	return input.split("/").join("");
}

function selectElement(selctedId) {
	this.elem = $(selctedId);
	this.appear = showElement;
	this.show = showElement;
	this.hide = hideElement;
	this.toggle = toggleElement;
	this.setId = setElementId;
	this.setClass = setElementClass;
	this.setInnerHTML = setElementInnerHTML;
	this.setBgColor = setElementBgColor;
	this.setColor = setElementColor;
	this.setOpacity = setElementOpacity;
	this.setX = setElementX;
	this.setY = setElementY;
	this.setWidth = setElementWidth;
	this.setHeight = setElementHeight;
	this.setZindex = setElementZindex;
	this.createAttribute = createElementAttribute;
	this.destroy = destroyElement;
	this.createText = createTextElement;
	this.setText = setTextElement;
}

function createDivElement () {
	this.elem = document.createElement("div");
	this.elem.style.display = "none";
	
	this.appear = appearElement;
	this.hide = hideElement;
	this.show = showElement;
	this.toggle = toggleElement;
	this.setId = setElementId;
	this.setClass = setElementClass;
	this.setInnerHTML = setElementInnerHTML;
	this.setBgColor = setElementBgColor;
	this.setColor = setElementColor;
	this.setOpacity = setElementOpacity;
	this.setX = setElementX;
	this.setY = setElementY;
	this.setWidth = setElementWidth;
	this.setHeight = setElementHeight;
	this.setZindex = setElementZindex;
	this.createAttribute = createElementAttribute;
	this.destroy = destroyElement;
	this.createText = createTextElement;
	this.setText = setTextElement;
	//document.documentElement.appendChild(this.elem);
}


function createElem(nameOfTag, parentElem) {
	this.elem = document.createElement(nameOfTag);
	parentElem.appendChild(this.elem);
	this.setId = setElementId;
	this.setClass = setElementClass;
	this.setInnerHTML = setElementInnerHTML;
	this.setBgColor = setElementBgColor;
	this.setColor = setElementColor;
	this.setOpacity = setElementOpacity;
	this.setX = setElementX;
	this.setY = setElementY;
	this.setWidth = setElementWidth;
	this.setHeight = setElementHeight;
	this.setZindex = setElementZindex;
	this.createAttribute = createElementAttribute;
	this.destroy = destroyElement;
	this.createText = createTextElement;
	this.hide = hideElement;
	this.show = showElement;
	}

function createTextElement(txt) {
	this.elem.appendChild(document.createTextNode(txt));
}

function setTextElement(txt) {
	this.elem.firstChild.nodeValue = txt;
}

	
function appearElement() {
	//alert(this.elem);
	document.documentElement.appendChild(this.elem);
	this.elem.style.display = "inline";
}

function hideElement() {
	this.elem.style.display = "none";
}

function showElement() {
	this.elem.style.display = "inline";
}

function toggleElement() {
	if(this.elem.style.display == "inline") {
		this.hide();
	}
	else {
		this.elem.style.display = "inline";
	}
}

function setElementId(idToSet) {
	this.elem.id = idToSet;
}

function setElementInnerHTML(txt) {
	//alert(this.elem)
	this.elem.innerHTML = txt;
}

function setElementClass(classToSet) {
	var classOfElement = document.createAttribute("class");
	
	classOfElement.nodeValue = classToSet;
	this.elem.setAttributeNode(classOfElement);
}

function setElementBgColor(bgColorToSet) {
	this.elem.style.backgroundColor = bgColorToSet;
}

function setElementColor(colorToSet) {
	this.elem.style.color = colorToSet;
}

function setElementOpacity(opacityToSet) {
	this.elem.style.opacity = opacityToSet;
}

function setElementX(XToSet) {
	this.elem.style.position = "absolute";
	this.elem.style.left = parseInt(XToSet)+"px";
}

function setElementY(YToSet) {
	this.elem.style.position = "absolute";
	this.elem.style.top = parseInt(YToSet)+"px";
}

function setElementWidth(widthToSet) {
	this.elem.style.width = parseInt(widthToSet)+"px";
}

function setElementHeight(heightToSet) {
	this.elem.style.height = parseInt(heightToSet)+"px";
}

function destroyElement() {
	this.elem.parentNode.removeChild(this.elem);
}

function setElementZindex(ZindexToSet) {
	this.elem.style.zIndex = ZindexToSet;
}

function createElementAttribute(AttributeName, AttributeValue) {
	var attribute = document.createAttribute(AttributeName);
	
	attribute.nodeValue = AttributeValue;
	this.elem.setAttributeNode(attribute);
}

function ajax() {
	this.requ = new XMLHttpRequest();
	this.create = createAjax;
	this.createPOST = createAjaxPOST;
	this.createRPC = createAjaxRPC;
	this.fetch = fetchAjax;
	this.fetchXML = fetchAjaxXML;
}

function createAjax(urlToGet) {
	this.requ.open("GET", urlToGet, false);
	//alert(urlToGet);
	//alert(this.args[1]);
	this.requ.send(null);
}

function createAjaxPOST(urlToGet, postData) {
	this.requ.open("POST", urlToGet, false);
	this.requ.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	this.requ.send(postData);
}

function createAjaxRPC(urlToGet) {
	this.requ.open("GET", "rpc.php?url="+urlToGet, false);
	//alert(urlToGet);
	//alert(this.args[1]);
	this.requ.send(null);
}

function fetchAjax() {
	return this.requ.responseText;
}

function fetchAjaxXML() {
	return this.requ.responseXML;

}
/*
function JSON() {
	this.parse = parseJSON;
	this.init = initJSON;
	this.addLine = addJSONLine;
	this.addContent = addJSONContent;
	this.finish = finishJSON;
	this.assemble = assembleJSON;
}
*/


function initJSON() {
	this.JSONArray = new Array();
}

function parseJSON(JSONToCreate) {
	try {
		var out = eval("(" + JSONToCreate + ")");
		return out;
	}
	catch(e) {
		alert(JSONToCreate);
	}
}

