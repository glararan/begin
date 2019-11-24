<?php
	function blog()
	{
		$read_blog_setting = new DOMDocument();
        $read_blog_setting->load('data/database/settings.xml');
                
        $BlogSetting = $read_blog_setting->getElementsByTagName('Blog');
		$short = "";
					
		foreach($BlogSetting as $_settings)
		{
			$short_ = $_settings->getElementsByTagName('Short');
			$short__ = $short_->item(0)->getElementsByTagName('Value');
			$short___ = $short__->item(0)->nodeValue;
			$short = $short___;
		}
					
		$read_articles = new DOMDocument();
        $read_articles->load('data/database/blog.xml');
                
        $Articles = $read_articles->getElementsByTagName('Article');
				
		foreach($Articles as $Article)
		{
			$_articlesTitle = $Article->getElementsByTagName('Title');
			$title = $_articlesTitle->item(0)->nodeValue;
						
			$_articlesAuthor = $Article->getElementsByTagName('Author');
			$author = $_articlesAuthor->item(0)->nodeValue;
						
			$_articlesDate = $Article->getElementsByTagName('Date');
			$date = $_articlesDate->item(0)->nodeValue;
						
			$_articlesText = $Article->getElementsByTagName('Text');
			$text = $_articlesText->item(0)->nodeValue;
						
			$_articlesLink = $Article->getElementsByTagName('Link');
			$link = $_articlesLink->item(0)->nodeValue;
				
			$id = $Article->getAttribute('ID');
						
			$content = $short;
						
			// Title
			$content = str_replace("{TITLE}", $title, $content);
			// Author
			$content = str_replace("{AUTHOR}", $author, $content);
			// Date
			$content = str_replace("{DATE}", $date, $content);
			// Text
			$content = str_replace("{TEXT}", $text, $content);
			// Link
			$content = str_replace("{LINK}", $link, $content);
						
			echo $content;
		}
	}
	
	function article($articleID)
	{
		$read_blog_setting = new DOMDocument();
        $read_blog_setting->load('data/database/settings.xml');
                
        $BlogSetting = $read_blog_setting->getElementsByTagName('Blog');
		$long = "";
					
		foreach($BlogSetting as $_settings)
		{
			$long_ = $_settings->getElementsByTagName('Long');
			$long__ = $long_->item(0)->getElementsByTagName('Value');
			$long___ = $long__->item(0)->nodeValue;
			$long = $long___;
		}
					
		$read_articles = new DOMDocument();
        $read_articles->load('data/database/blog.xml');
                
        $Articles = $read_articles->getElementsByTagName('Article');
				
		foreach($Articles as $Article)
		{
			if($Article->getAttribute('ID') == $articleID)
			{
				$_articlesTitle = $Article->getElementsByTagName('Title');
				$title = $_articlesTitle->item(0)->nodeValue;
							
				$_articlesAuthor = $Article->getElementsByTagName('Author');
				$author = $_articlesAuthor->item(0)->nodeValue;
							
				$_articlesDate = $Article->getElementsByTagName('Date');
				$date = $_articlesDate->item(0)->nodeValue;
							
				$_articlesText = $Article->getElementsByTagName('TextF');
				$text = $_articlesText->item(0)->nodeValue;
							
				$content = $long;
							
				// Title
				$content = str_replace("{TITLE}", $title, $content);
				// Author
				$content = str_replace("{AUTHOR}", $author, $content);
				// Date
				$content = str_replace("{DATE}", $date, $content);
				// Text
				$content = str_replace("{TEXT_FULL}", $text, $content);
							
				echo $content;
			}
		}
	}
	
	function page($pageID)
	{
		$read_pages_setting = new DOMDocument();
        $read_pages_setting->load('data/database/settings.xml');
                
        $PagesSetting = $read_pages_setting->getElementsByTagName('Pages');
		$long = "";
					
		foreach($PagesSetting as $_settings)
		{
			$long_ = $_settings->getElementsByTagName('Value');
			$long__ = $long_->item(0)->nodeValue;
			$long = $long__;
		}
					
		$read_pages = new DOMDocument();
        $read_pages->load('data/database/pages.xml');
                
        $Pages = $read_pages->getElementsByTagName('Page');
				
		foreach($Pages as $Page)
		{
			if($Page->getAttribute('ID') == $pageID)
			{
				$_pagesTitle = $Page->getElementsByTagName('Title');
				$title = $_pagesTitle->item(0)->nodeValue;
							
				$_pagesAuthor = $Page->getElementsByTagName('Author');
				$author = $_pagesAuthor->item(0)->nodeValue;
							
				$_pagesDate = $Page->getElementsByTagName('Date');
				$date = $_pagesDate->item(0)->nodeValue;
							
				$_pagesText = $Page->getElementsByTagName('Text');
				$text = $_pagesText->item(0)->nodeValue;
							
				$content = $long;
							
				// Title
				$content = str_replace("{TITLE}", $title, $content);
				// Author
				$content = str_replace("{AUTHOR}", $author, $content);
				// Date
				$content = str_replace("{DATE}", $date, $content);
				// Text
				$content = str_replace("{TEXT_FULL}", $text, $content);
							
				echo $content;
			}
		}
	}
?>