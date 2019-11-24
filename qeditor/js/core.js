// JavaScript Document
$(document).ready(function()
{
	// flexslider main
	$('#main-flexslider').flexslider(
	{						
		animation: "swing",
		direction: "vertical",
		slideshow: true,
		slideshowSpeed: 3500,
		animationDuration: 1000,
		directionNav: true,
		prevText: '<i class="icon-angle-up icon-2x"></i>',       
		nextText: '<i class="icon-angle-down icon-2x active"></i>', 
		controlNav: false,
		smootheHeight:true,						
		useCSS: false
	});
	
	// twitter
	/*$('#tweet').tweet(
	{
		modpath: '/js/',
		username: 'theqeditor',
		count: 1,
		loading_text: 'Načítám tweety...'
	});*/
});