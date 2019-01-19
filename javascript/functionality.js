var sidebarShowing = false;

document.getElementById("content").addEventListener("click", function(){
	if(sidebarShowing){
		toggleSidebar();
	}
});

function toggleSidebar(){
	if(sidebarShowing){
		document.getElementById("sidebar").style.left = "-320px";
		sidebarShowing = false;
	}else{
		document.getElementById("sidebar").style.left = "0";
		sidebarShowing = true;
	}
}

//setTimeout(toggleSidebar, 500);