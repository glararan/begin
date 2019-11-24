// JavaScript Document

// Chat

function sendMsg()
{
	el = document.getElementById("ChatNewMessage");
	
	if(el.value.length != 0)
	{
		addNewMessage(el.value);
		
		el.value = "";
		showChat();
	}
	else
		alert("Zpráva je prázdná!");
}

//
var ajaxAddMessage;

function addNewMessage(message)
{
	ajaxAddMessage = GetXmlHttpObject();
	
	if(ajaxAddMessage == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	var url = '../core/chat/chat.php?newmsg=y&text=' + message;
	
	ajaxAddMessage.open("GET", url, true);
	ajaxAddMessage.send(null);
}

//
var xmlHttpObject;

function showChat()
{
	xmlHttpObject = GetXmlHttpObject();
	
	if(xmlHttpObject == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	var url = '../core/chat/chat.php';
	var params = 'show=y';
	
	xmlHttpObject.open("POST", url, true);
    xmlHttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttpObject.setRequestHeader("Content-length", params.length);
    xmlHttpObject.setRequestHeader("Connection", "close");
    xmlHttpObject.onreadystatechange = function()
	{
    	if(xmlHttpObject.readyState == 4 && xmlHttpObject.status == 200) // Do těla této podmínky vkládejte své kódy. Ověřuje, jestli byla data úspěšně odeslána a že cíl odpověděl.
        	document.getElementById("ChatArea").innerHTML = xmlHttpObject.responseText; // Vložíme odpověď do určeného elementu
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