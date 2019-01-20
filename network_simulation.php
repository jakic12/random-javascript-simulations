<!DOCTYPE html>
<html style="height:100%">
    <head>
            <?php include("template/config.php") ?>
            <title>physics</title>
            <meta name="title" content ="network simulation">
            <meta name="description" content="network simulation">
            <meta property="og:title" content="network simulation" />
            <meta property="og:description" content="network simulation" />
	    <meta property="og:image" content="<?= $domain ?>/projects/images/" />
		
<style>

.component{
	position:absolute;
	height:100px;
	width:100px;
}

.component:hover{
	cursor: move;
}

#content{
	position:relative;
}

.menu{
	position:absolute;
	background-color:white;
	color:black;
}

</style>

    </head>
<body>
        <?php include("template/HaS.php");?>
	<div id="content">	
		<button onclick="components.push(new component('router', 0,0))">add router</button>

        </div>
	<script src="javascript/functionality.js"></script>
<script>
var components = [];

function component(type, x, y){
	this.id = (Math.random()+"").replace(".","");
	this.type = type;

	this.inputs = [];
	this.outputs = [];

	this.ip;
	this.gateway;

	this.dom = createDom(this);
}


function createDom(x){
	let canvas = document.getElementById("content");
	
	let y = document.createElement("IMG");

	y.setAttribute("class", "component " + x.type);

	y.setAttribute("style", "left:" + 0 + "; top:" + 0 + ";");

	if(x.type == "router"){
		y.setAttribute("src","/projects/images/networkSim/wireless_router.png");
	}else if(x.type == "pc"){
		y.setAttribute("src","/projects/images/networkSim/pc.png");
	}else if(x.type == "server"){
		y.setAttribute("src","/projects/images/networkSim/server.png");
	}

	makeDraggable(y);

	y.addEventListener('contextmenu', function(e){
		e.preventDefault();
		showMenu(e,this);
		document.onmousedown = closeMenus;
		return false;
	});


	canvas.appendChild(y);
	return y;
}

var openMenus = [];

function isDescendant(parent, child){
	let node = child;
	
	while(node != null){
		if(node == parent){
			return true;
		}
		node = node.parentNode;
	}	
	return false;
}

function closeMenus(e){		
	openMenus.forEach(function(element,i){
		if(!isDescendant(element, e.target)){
			element.parentNode.removeChild(element);
			openMenus.splice(i, 1);
		}
	});
}

function showMenu(e,y){
	let content = document.getElementById("content");
	let newDiv = document.createElement("DIV");

	newDiv.setAttribute("style", "left:" + e.clientX + "px; top:" + (e.clientY-70) + "px;");
	newDiv.setAttribute("class", "menu");
	newDiv.innerHTML = "<ul><li>delete</li><li>no dleete</li></ul>";
	
	openMenus.push(newDiv);
	content.appendChild(newDiv);
}


function makeDraggable(element){
	element.onmousedown = function(e){dragMouseDown(e,this)};
}

var drag_element;
var posX1, posY1, posX2, posY2;

function dragMouseDown(e, element){
	e = e || window.event;
	e.preventDefault();

	drag_element = element;

	posY2 = e.clientY;
	posX2 = e.clientX;

	document.onmouseup = closeDragElement;
	document.onmousemove = elementDrag;
}

function elementDrag(e, element){
	e = e || window.event;
	e.preventDefault();

	posX1 = posX2 - e.clientX;
	posY1 = posY2 - e.clientY;
	posX2 = e.clientX;
	posY2 = e.clientY;
	
	drag_element.style.top = (drag_element.offsetTop - posY1) + "px";
	drag_element.style.left = (drag_element.offsetLeft - posX1) + "px";
}

function closeDragElement(){
	document.onmouseup = null;
	document.onmousemove = null;

	drag_element = null;
}


</script>
</body>

</html>
