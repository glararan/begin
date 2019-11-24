<!doctype html>
<html>
<head>
    <meta http-equiv="Content-language" content="cs"; charset="utf-8">
    <meta name="generator" content="Adobe Dreamweaver CS6">
    <meta name="author" content="Lukáš Veteška (glararan)">
    <meta name="robots" content="noindex, nofollow">
        
    <title>netIO 4</title>

	<base href="http://open-source.veteska.cz/netio/">
        
    <link href="css/style.css" rel="stylesheet" type="text/css">
    
    <script type="text/javascript">
	  WebFontConfig =
	  {
		google: { families: [ 'Dosis:300:latin,latin-ext' ] }
	  };
	  (function()
	  {
		var wf = document.createElement('script');
		wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
		  '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
		wf.type = 'text/javascript';
		wf.async = 'true';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(wf, s);
	  })(); 
	</script>
    
    <?php
		if(strstr("/files/", $_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']))
		{
			echo '<link href="includes/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" rel="stylesheet" type="text/css">';
			echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>';
			
			echo '<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>';

            echo '<script type="text/javascript" src="includes/plupload/plupload.full.js"></script>';
			echo '<script type="text/javascript" src="includes/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>';
			echo '<script type="text/javascript" src="includes/plupload/i18n/cs.js"></script>';

            echo '<!-- <script type="text/javascript"  src="http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js"></script> -->';
			
			echo '<script type="text/javascript">
				$(function()
				{
					$("#upload_container").pluploadQueue({
						runtimes : \'gears,flash,silverlight,browserplus,html5\',
						browse_button : \'pickfiles\',
						max_file_size : \''.preg_replace('/\s+/', "", $xml->read('MaxUpload', 0, true)).'\',
						multiple_queues : true,
						url : \'includes/plupload_upload.php\',
						resize : {width : '.preg_replace('/\s+/', "", $xml->read('FilesWidthResize', 0, true)).', height : '.preg_replace('/\s+/', "", $xml->read('FilesHeightResize', 0, true)).', quality : '.preg_replace('/\s+/', "", $xml->read('FilesQualityResize', 0, true)).'},
						flash_swf_url : \'includes/plupload/plupload.flash.swf\',
						silverlight_xap_url : \'includes/plupload/plupload.silverlight.xap\',
						filters :
						[
							{title : "Image files", extensions : "jpg,jpeg,gif,png"},
							{title : "Zip files", extensions : "zip"},
							{title : "Rar files", extensions : "rar"},
							{title : "Other files", extensions : "*"}
						]
					});
					
					$("form").submit(function(e)
					{
						var uploader = $("#upload_container").pluploadQueue();
						
						if(uploader.files.lenght > 0)
						{
							uploader.bind("State Changed", function()
							{
								if(uploader.files.lenght === (uploader.total.uploaded + uploader.total.failed))
								{
									$("form")[0].submit();
								}
							});
							
							uploader.start();
						}
						else
						{
							alert("'.$lang['files_alert'].'");
						}
						
						return false;
					});
				});</script>';
		}
		
		if((strstr("/blog/new/create/", $_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) or (strstr("/blog/".$_GET['arg1']."/edit/", $_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])))
		{
			echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>';
			
			echo '<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>';
			
			echo '<script type="text/javascript" src="includes/ckeditor/ckeditor.js"></script>';
			echo '<script type="text/javascript" src="includes/ckeditor/adapters/jquery.js"></script>';
			
			echo '<script type="text/javascript">
				$(function()
				{
					var config = {skin: \'office2003\', toolbar: \'Full\', enterMode: CKEDITOR.ENTER_BR, shiftEnterMode: CKEDITOR.ENTER_P, language: \'cs\'};
					
					$("textarea").ckeditor(config);
				});
				  </script>';
		}
	?>
</head>

<body>
<div class="wrapper">
	<div class="header">
    	<div class="logo">
        </div>
        
        <div class="menu">
        	<ul>
            	<li><a href="blog/"><?php echo $lang['menu_blog']; ?></a></li>
                <li><a href="pages/"><?php echo $lang['menu_pages']; ?></a></li>
                <li><a href="files/"><?php echo $lang['menu_files']; ?></a></li>
                <li><a href="users/"><?php echo $lang['menu_users']; ?></a></li>
                <li><a href="tools/"><?php echo $lang['menu_tools']; ?></a></li>
                <li><a href="devs/"><?php echo $lang['menu_devs']; ?></a></li>
            </ul>
        </div>
    </div>
    
    <div class="content">
    	<div class="content_body">