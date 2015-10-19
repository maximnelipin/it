/**
 * 
 */
function selextnet(){
	
	document.getElementById("selextnet").className="divon";
	document.getElementById("id_extnet").required=true;
	document.getElementById("addextnet").className="divoff";
	document.getElementById("extip").required=false;
	return 0;
	
}

function addextnet(){
	
	document.getElementById("addextnet").className="divon";
	document.getElementById("selextnet").className="divoff";
	document.getElementById("id_extnet").required=false;
	document.getElementById("extip").required=true;
	return 0;
}

function selppp(){
	
	document.getElementById("selppp").className="divon";
	document.getElementById("addppp").className="divoff";
	document.getElementById("srv").required=false;
	return 0;
	
}

function addppp(){
	
	document.getElementById("addppp").className="divon";
	document.getElementById("selppp").className="divoff";
	document.getElementById("srv").required=true;
	return 0;
}

function selcomp(){
	
	document.getElementById("selcomp").className="divon";
	document.getElementById("id_company").required=true;
	document.getElementById("addcomp").className="divoff";
	document.getElementById("name").required=false;
	return 0;
	
}

function addcomp(){
	
	document.getElementById("addcomp").className="divon";
	document.getElementById("id_company").required=false;
	document.getElementById("selcomp").className="divoff";
	document.getElementById("name").required=true;
	return 0;
}
	
	
	
	