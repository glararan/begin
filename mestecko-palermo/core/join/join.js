// JavaScript Document

// Join
var xmlHttpObject;

function showRooms()
{
	xmlHttpObject = GetXmlHttpObject();
	
	if(xmlHttpObject == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	var url = '../core/join/join.php';
	var params = 'show=y';
	
	xmlHttpObject.open("POST", url, true);
    xmlHttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttpObject.setRequestHeader("Content-length", params.length);
    xmlHttpObject.setRequestHeader("Connection", "close");
    xmlHttpObject.onreadystatechange = function()
	{
    	if(xmlHttpObject.readyState == 4 && xmlHttpObject.status == 200)
		{
			var html_code = "<table width=100%><tr><th width=5% align><b>ID</b></th><th width=15%><b>Název roomky</b></th><th width=7%><b>Status</b></th><th width=7%><b>Hráči</b></th></tr>";
			
			html_code += xmlHttpObject.responseText;
			html_code += "</table>";
			
        	document.getElementById("RoomArea").innerHTML = html_code;
		}
    };
    xmlHttpObject.send(params);
}

function GetXmlHttpObject()
{
	if (window.XMLHttpRequest) // IE7+, Firefox, Chrome, Opera, Safari
    	return new XMLHttpRequest();
    
	if (window.ActiveXObject) // IE6, IE5
    	return new ActiveXObject("Microsoft.XMLHTTP");
		
    return null;
}