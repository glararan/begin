// JavaScript Document

// Game
function game()
{
	//
	showPlayers();
	buttonStart();
}

var xmlHttpObject;
var buttonIsActivated = false;
var buttonIsDeleted   = false;

function buttonStart()
{
	xmlHttpObject = GetXmlHttpObject();
	
	if(xmlHttpObject == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	if(!buttonIsActivated)
	{
		if(!document.getElementById("startButton"))
		{
			var startButtonElement = document.createElement('div');
			var child = document.getElementById("playersTable");
					
			startButtonElement.setAttribute("id", "startButton");
			child.appendChild(startButtonElement);
		}
	}
	
	var url = '../../core/game/game.php';
	var params = 'buttonShow=y';
	
	xmlHttpObject.open("POST", url, true);
    xmlHttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttpObject.setRequestHeader("Content-length", params.length);
    xmlHttpObject.setRequestHeader("Connection", "close");
    xmlHttpObject.onreadystatechange = function()
	{
    	if(xmlHttpObject.readyState == 4 && xmlHttpObject.status == 200)
			if(!buttonIsActivated)
				document.getElementById("startButton").innerHTML = xmlHttpObject.responseText;
			else
			{
				if(!buttonIsDeleted)
				{
					var removingElement = document.getElementById("startButton");
					removingElement.parentNode.removeChild(removingElement);
					
					buttonIsDeleted = true;
				}
			}
    };
    xmlHttpObject.send(params);
}

//
var playersShow;

function showPlayers()
{
	playersShow = GetXmlHttpObject();
	
	if(playersShow == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	var url = '../../core/game/game.php';
	var params = 'showPlayers=y';
	
	playersShow.open("POST", url, true);
    playersShow.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    playersShow.setRequestHeader("Content-length", params.length);
    playersShow.setRequestHeader("Connection", "close");
    playersShow.onreadystatechange = function()
	{
    	if(playersShow.readyState == 4 && playersShow.status == 200)
		{
			var html_code = "<table width=50%><tr><th><b>Název hráče</b></th><th><b>Hodnost</b></th></tr>";
			
			html_code += playersShow.responseText;
			html_code += "</table>";
			
        	document.getElementById("playersTable").innerHTML = html_code;
		}
    };
    playersShow.send(params);
}

//
var gameStarting;

function startGame()
{
	gameStarting = GetXmlHttpObject();
	
	if(gameStarting == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	var url = '../../core/game/game.php';
	var params = 'startgame=y';
	
	gameStarting.open("POST", url, true);
    gameStarting.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    gameStarting.setRequestHeader("Content-length", params.length);
    gameStarting.setRequestHeader("Connection", "close");
    gameStarting.onreadystatechange = function()
	{
    	if(autoEG.readyState == 4 && autoEG.status == 200)
			buttonIsActivated = true;
    };
    gameStarting.send(params);
}

//
var autoEG;

function autoEndGame()
{
	autoEG = GetXmlHttpObject();
	
	if(autoEG == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	var url = '../../core/game/game.php';
	var params = 'autoendgame=y';
	
	autoEG.open("POST", url, true);
    autoEG.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    autoEG.setRequestHeader("Content-length", params.length);
    autoEG.setRequestHeader("Connection", "close");
    autoEG.onreadystatechange = function()
	{
    	if(autoEG.readyState == 4 && autoEG.status == 200)
		{
			if(autoEG.responseText == "true")
				document.location.href = "../";
		}
    };
    autoEG.send(params);
}

//
var theGameStarted;
var theGameStartedB = false;
var botText;

function gameText()
{
	theGameStarted = GetXmlHttpObject();
	botText        = GetXmlHttpObject();
	
	if(botText == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	var url    = '../../core/game/game.php';
	var params = 'showgamestarted=y';
	
	if(!theGameStartedB)
	{
		if(!document.getElementById("theGameStarted"))
		{
			var gameStartedElement = document.createElement('div');
			var child = document.getElementById("playersTable");
					
			gameStartedElement.setAttribute("id", "theGameStarted");
			gameStartedElement.setAttribute("style", "display:none;");
			child.appendChild(gameStartedElement);
		}
		
		theGameStarted.open("POST", url, true);
		theGameStarted.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		theGameStarted.setRequestHeader("Content-length", params.length);
		theGameStarted.setRequestHeader("Connection", "close");
		theGameStarted.onreadystatechange = function()
		{
			if(theGameStarted.readyState == 4 && theGameStarted.status == 200)
				document.getElementById("theGameStarted").innerHTML = theGameStarted.responseText;
		};
		theGameStarted.send(params);
	}
	
	if(theGameStartedB == true || document.getElementById("theGameStarted").innerHTML == "1")
	{
		params = 'gamebot=y';
		
		botText.open("POST", url, true);
		botText.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		botText.setRequestHeader("Content-length", params.length);
		botText.setRequestHeader("Connection", "close");
		botText.onreadystatechange = function()
		{
			if(botText.readyState == 4 && botText.status == 200)
				document.getElementById("gameText").innerHTML = botText.responseText;
		};
		botText.send(params);
		
		if(!theGameStartedB)
		{
			var removingElement = document.getElementById("theGameStarted");
			removingElement.parentNode.removeChild(removingElement);
			
			theGameStartedB = true;
		}
	}
}

var TheGameContent;

function gameContent()
{
	if(theGameStartedB)
	{
		TheGameContent = GetXmlHttpObject();
	
		if(TheGameContent == null)
		{
			alert('Prohlížeč nepodporuje XmlHttp');
			
			return;
		}
		
		var url    = '../../core/game/game.php';
		var params = 'showgamecontent=y';
		
		TheGameContent.open("POST", url, true);
		TheGameContent.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		TheGameContent.setRequestHeader("Content-length", params.length);
		TheGameContent.setRequestHeader("Connection", "close");
		TheGameContent.onreadystatechange = function()
		{
			if(TheGameContent.readyState == 4 && TheGameContent.status == 200)
				document.getElementById("gameContent").innerHTML = TheGameContent.responseText;
		};
		TheGameContent.send(params);
	}
}

// Chat

function sendMsg()
{
	el = document.getElementById("ChatNewMessage");
	
	if(el.value.length != 0)
	{
		addNewMessage(el.value, document.getElementById("ChatNewMessageOption").selectedIndex);
		
		el.value = "";
		showChat();
	}
	else
		alert("Zpráva je prázdná!");
}

//-
var ajaxAddMessage;

function addNewMessage(message, option)
{
	ajaxAddMessage = GetXmlHttpObject();
	
	if(ajaxAddMessage == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	var url = '../core/chat/game.php?newmsg=y&option=' + option + '&text=' + message;
	
	ajaxAddMessage.open("GET", url, true);
	ajaxAddMessage.send(null);
}

//-
var showChatObj;

function showChat()
{
	showChatObj = GetXmlHttpObject();
	
	if(showChatObj == null)
	{
		alert('Prohlížeč nepodporuje XmlHttp');
		
		return;
	}
	
	var url = '../core/chat/game.php';
	var params = 'showroomchat=y';
	
	showChatObj.open("POST", url, true);
    showChatObj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    showChatObj.setRequestHeader("Content-length", params.length);
    showChatObj.setRequestHeader("Connection", "close");
    showChatObj.onreadystatechange = function()
	{
    	if(showChatObj.readyState == 4 && showChatObj.status == 200)
        	document.getElementById("ChatArea").innerHTML = showChatObj.responseText;
    };
    showChatObj.send(params);
}

// Other
function GetXmlHttpObject()
{
	if (window.XMLHttpRequest) // IE7+, Firefox, Chrome, Opera, Safari
    	return new XMLHttpRequest();
    
	if (window.ActiveXObject) // IE6, IE5
    	return new ActiveXObject("Microsoft.XMLHTTP");
		
    return null;
}