$(document).ready(loadAll);
var permissions = 'defauld';

function loadAll()
{
	//$(document).delegate('a', 'click', redirect);
	//setDefauldMenu();
	//$.get('/pages/devices.php', loadPage);
}

function redirect(event)
{
	event.preventDefault();
	var url = $(this).attr('href');

	if (url == 'logout')
	{
		$.post('/php/logout.php');
		permissions = 'defauld';
		setDefauldMenu();
		$.get('/pages/devices.php', loadPage);
	}
	
	$.get('/pages/' + $(this).attr('href') + '.php', loadPage);
}

function loadPage(page)
{
	$('#content').empty();
	$('#content').append(page);
}

function setDefauldMenu()
{
	// menu
	$('.navbar-collapse').empty();
	$('.navbar-collapse').append('<ul class="nav"><li class="active"><a href="/">DOMŮ</a></li><li><a href="/devices/">ZAŘÍZENÍ</a></li></ul><ul class="nav pull-right"><li><a href="/login/">PŘIHLÁSIT SE</a></li></ul>');
	
	// links
	$('.links').empty();
	$('.links').append('<a href="#">Domů</a><a href="#">Zařízení</a><a href="/login/">Přihlásit se</a>');
}

function setUserMenu()
{
	// menu
	$('menu').empty();
	$('menu').append('<ul class="nav"><li><a href="devices">ZAŘÍZENÍ</a></li><li><a href="transfers">VÝPŮJČKY</a></li></ul><ul class="nav pull-right"><li><a href="logout">ODHLÁSIT SE</a></li></ul>');
	
	// links
	$('.links').empty();
	$('.links').append('<a href="#">Domů</a><a href="#">Zařízení</a><a href="/login/">Přihlásit se</a>');
}

function setAdminMenu()
{
	// menu
	$('menu').empty();
	$('menu').append('<ul class="nav"><li><a href="devices">ZAŘÍZENÍ</a></li><li><a href="transfers">VÝPŮJČKY</a></li><li><a href="users">UŽIVATELÉ</a></li></ul><ul class="nav pull-right"><li><a href="logout">ODHLÁSIT SE</a></li></ul>');
	
	// links
	$('.links').empty();
	$('.links').append('<a href="#">Domů</a><a href="#">Zařízení</a><a href="/login/">Přihlásit se</a>');
}