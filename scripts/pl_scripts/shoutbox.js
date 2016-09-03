/*
Note there are dependancies on shoutboxSaveUrl and shoutboxOutputUrl being set outside of this script first.
*/

 function ajaxFunction(){
	var ajaxRequest;

	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				//browsers all not support, rare case
				alert("Your browser broke!");
				return false;
			}
		}

	}
	return ajaxRequest;
}


function showData() {
	htmlRequest = ajaxFunction();
	if (htmlRequest==null){ // If it cannot create a new Xmlhttp object.
		alert ("Browser does not support HTTP Request");
		return;
	} 

	htmlRequest.onreadystatechange = function(){
		if(htmlRequest.readyState == 4){
			document.getElementById("shoutarea").innerHTML = htmlRequest.responseText;
		}
	}
	htmlRequest.open("GET", shoutboxOutputUrl, true);
	htmlRequest.send(null);
}

showData();

// TODO -Configurable
setInterval("showData()",30000);


function saveData() {

	htmlRequest = ajaxFunction();
	if (htmlRequest==null){ // If it cannot create a new Xmlhttp object.
		alert ("Browser does not support HTTP Request");
		return;
	} 

	htmlRequest.open('POST', shoutboxSaveUrl);
	htmlRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	htmlRequest.send('name='+document.forms[1].elements['shouter'].value+'&message='+document.forms[1].elements['shouter_comment'].value); 
	//alert('name='+document.forms[1].elements['shouter'].value+'&message='+document.forms[1].elements['shouter_comment'].value);

	document.forms[1].elements['shouter_comment'].value = ''; // Updates the shout box’s text area to NULL.
	document.forms[1].elements['shouter_comment'].focus(); // Focuses the text area.
	
	// Show the data after a mimimal pause, we need this pause otherwise it won't work the first time
	setTimeout("showData();", 200); 
	

} 