<div class="content_left">
	<?php echo $lang['blog_img']; ?>
</div>

<div class="content_right">
	<?php echo $lang['blog_title']; ?>
    <br>
    <?php echo $lang['blog_text']; ?>
    <?php echo $lang['highlighter']; ?>
    
    <?php
		# blog system
		
		// BLOG LIST
		if(!isset($_GET['arg1']) && !isset($_GET['arg2']))
		{
			// TABLE
			echo "<table width='100%'><tbody><tr><td><b>".$lang['blog_table_id']."</b></td><td><b>".$lang['blog_table_name']."</b></td><td><b>".$lang['blog_table_delete']."</b></td></tr>";
			
			$read_articles = new DOMDocument();
            $read_articles->load('data/database/blog.xml');
                
            $Articles = $read_articles->getElementsByTagName('Article');
				
			foreach($Articles as $Article)
			{
				$_articles = $Article->getElementsByTagName('Title');
				$_article = $_articles->item(0)->nodeValue;
				
				$_id = $Article->getAttribute('ID');
					
				echo "<tr><td>".$_id."</td><td><a href='/blog/".$_id."/edit/'>".$_article."</a></td><td><a href='/blog/".$_id."/'><font color='red'>".$lang['delete']."</font></a></td></tr>";
			}
			
			echo "</tbody></table><br><br>";
			
			// Create button
			echo "<button type='button' class='button'><a href='/blog/new/create/'>".$lang['blog_create_article']."</a></button>";
		}
		else if(isset($_GET['arg1']) && !isset($_GET['arg2']))
		{
			if(!isset($_POST['deleteButton']))
				echo sprintf($lang['blog_delete_article'], $_GET['arg1'])."<form method='post' action=''><input type='submit' name='deleteButton' class='button' value='".$lang['yes']."'><button type='button' name='no' class='button'><a href='/blog/'>".$lang['no']."</a></button></form>";
			else
			{
				$_xml = new DOMDocument();
				$_xml->load('data/database/blog.xml');
						
				$Articles = $_xml->getElementsByTagName('Article');
				$articleIDForDelete = $_GET['arg1'];
				$articleExists = false;
								
				foreach($Articles as $Article)
				{
					$articleID = $Article->getAttribute('ID');
					
					if($articleID == $articleIDForDelete)
						$articleExists = true;
				}
				
				if($articleExists == false)
					die($lang['blog_error_0']."<meta http-equiv='refresh' content='3;URL=/blog/'>");
				else
				{
					$xpath = new DOMXpath($_xml);
					$nodeList = $xpath->query('//Article[@ID="'.(int)$articleIDForDelete.'"]');
							
					if($nodeList->length)
					{
						$node = $nodeList->item(0);
						$node->parentNode->removeChild($node);
					}
									
					$_xml->save('data/database/blog.xml');
					
					echo sprintf($lang['blog_success_0'], $articleIDForDelete).'<meta http-equiv="refresh" content="3;URL=/blog/">';
				}
			}
		}
		else
		{
			if($_GET['arg1'] == "new" && $_GET['arg2'] == "create")
			{
				if(!isset($_POST['createArticle']))
					echo "<form method='post' action=''><label for='title'>".$lang['blog_create_new_article_title']."<br></label><input type='text' name='title' class='box'><br><br><br><label for='text'>".$lang['blog_create_new_article_short']."<br></label><textarea name='text' rows='5' cols='60'></textarea><br><br><br><label for='text_f'>".$lang['blog_create_new_article_long']."<br></label><textarea name='text_f' rows='5' cols='60'></textarea><br><input type='submit' name='createArticle' class='button' value='".$lang['blog_create_new_article_button']."'></form>";
				else
				{
					$_title_ = $_POST['title'];
					$_short_ = $_POST['text'];
					$_long_ = $_POST['text_f'];
					
					$_author_ = $_SESSION['account'];
					$_date_ = date("d/m/Y H:i:s", time());
					
					if(empty($_title_))
						die($lang['blog_error_1'].'<meta http-equiv="refresh" content="5;URL=/blog/new/create/">');
					
					$_xml = new DOMDocument();
					$_xml->load('data/database/blog.xml');
					
					$Articles = $_xml->getElementsByTagName('Article');
					$lastID = -1;
					
					foreach($Articles as $Article)
					{
						$_articles = $Article->getElementsByTagName('Title');
						$_article = $_articles->item(0)->nodeValue;
						
						$lastID = $Article->getAttribute('ID');
					}
					
					$_id_ = $lastID + 1;
					$_link_ = "/blog/".$_id_."/content/";
						
					$Article = $_xml->createElement('Article');
					$Article->setAttribute('ID', $_id_);
					
					$Title = $_xml->createElement('Title');
					$TitleText = $_xml->createTextNode($_title_);
					$Title->appendChild($TitleText);
					$Article->appendChild($Title);
					
					$Author = $_xml->createElement('Author');
					$AuthorText = $_xml->createTextNode($_author_);
					$Author->appendChild($AuthorText);
					$Article->appendChild($Author);
					
					$Date = $_xml->createElement('Date');
					$DateText = $_xml->createTextNode($_date_);
					$Date->appendChild($DateText);
					$Article->appendChild($Date);
					
					$Text = $_xml->createElement('Text');
					$TextText = $_xml->createTextNode($_short_);
					$Text->appendChild($TextText);
					$Article->appendChild($Text);
					
					$TextF = $_xml->createElement('TextF');
					$TextFText = $_xml->createTextNode($_long_);
					$TextF->appendChild($TextFText);
					$Article->appendChild($TextF);
					
					$Link = $_xml->createElement('Link');
					$LinkText = $_xml->createTextNode($_link_);
					$Link->appendChild($LinkText);
					$Article->appendChild($Link);
					
					$_xml->documentElement->appendChild($Article);
					
					$_xml->save('data/database/blog.xml');
					
					echo sprintf($lang['blog_success_1'], $_title_).'<meta http-equiv="refresh" content="2;URL=/blog/">';
				}
			}
			else if($_GET['arg2'] == "edit")
			{
				if(!isset($_POST['editArticle']))
				{
					$id = $_GET['arg1'];
					
					$_title = "";
					$_short = "";
					$_long = "";
					
					$_xml = new DOMDocument();
					$_xml->load('data/database/blog.xml');
					
					$Articles = $_xml->getElementsByTagName('Article');
					
					foreach($Articles as $Article)
					{
						if($Article->getAttribute('ID') == $id)
						{
							$_articlesTitle = $Article->getElementsByTagName('Title');
							$_articleTitle = $_articlesTitle->item(0)->nodeValue;
							
							$_articlesShort = $Article->getElementsByTagName('Text');
							$_articleShort = $_articlesShort->item(0)->nodeValue;
							
							$_articlesLong = $Article->getElementsByTagName('TextF');
							$_articleLong = $_articlesLong->item(0)->nodeValue;
							
							$_title = $_articleTitle;
							$_short = $_articleShort;
							$_long = $_articleLong;
						}
					}
					
					echo "<form method='post' action=''><label for='title'>".$lang['blog_create_new_article_title']."<br></label><input type='text' name='title' class='box' value='".$_title."'><br><br><br><label for='text'>".$lang['blog_create_new_article_short']."<br></label><textarea name='text' rows='5' cols='60'>".$_short."</textarea><br><br><br><label for='text_f'>".$lang['blog_create_new_article_long']."<br></label><textarea name='text_f' rows='5' cols='60'>".$_long."</textarea><br><input type='submit' name='editArticle' class='button' value='".$lang['blog_edit_article_button']."'></form>";
				}
				else
				{
					$id = $_GET['arg1'];
					
					$_title_ = $_POST['title'];
					$_short_ = $_POST['text'];
					$_long_ = $_POST['text_f'];
					
					if(empty($_title_))
						die($lang['blog_error_1'].'<meta http-equiv="refresh" content="5;URL=/blog/'.$id.'/edit/">');
					
					$_xml = new DOMDocument();
					$_xml->load('data/database/blog.xml');
					
					$Articles = $_xml->getElementsByTagName('Article');
					
					foreach($Articles as $Article)
					{
						if($Article->getAttribute('ID') == $id)
						{
							$titleTag = $Article->getElementsByTagName('Title');
							$title = $titleTag->item(0);
							$title->nodeValue = $_title_;
							
							$shortTag = $Article->getElementsByTagName('Text');
							$short = $shortTag->item(0);
							$short->nodeValue = $_short_;
							
							$longTag = $Article->getElementsByTagName('TextF');
							$long = $longTag->item(0);
							$long->nodeValue = $_long_; 
						}
					}
					
					$_xml->save('data/database/blog.xml');
					
					echo sprintf($lang['blog_success_2'], $_title_).'<meta http-equiv="refresh" content="2;URL=/blog/'.$id.'/edit/">';
				}
			}
		}
	?>
</div>

<div class="clear">
</div>