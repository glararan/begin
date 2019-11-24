<div class="content_left">
	<?php echo $lang['pages_img']; ?>
</div>

<div class="content_right">
	<?php echo $lang['pages_title']; ?>
    <br>
    <?php echo $lang['pages_text']; ?>
    <?php echo $lang['highlighter']; ?>
    
    <?php
		# blog system
		
		// BLOG LIST
		if(!isset($_GET['arg1']) && !isset($_GET['arg2']))
		{
			// TABLE
			echo "<table width='100%'><tbody><tr><td><b>".$lang['pages_table_id']."</b></td><td><b>".$lang['pages_table_name']."</b></td><td><b>".$lang['pages_table_delete']."</b></td></tr>";
			
			$read_pages = new DOMDocument();
            $read_pages->load('data/database/pages.xml');
                
            $Pages = $read_pages->getElementsByTagName('Page');
				
			foreach($Pages as $Page)
			{
				$_pages = $Page->getElementsByTagName('Title');
				$_page = $_pages->item(0)->nodeValue;
				
				$_id = $Page->getAttribute('ID');
					
				echo "<tr><td>".$_id."</td><td><a href='/pages/".$_id."/edit/'>".$_article."</a></td><td><a href='/pages/".$_id."/'><font color='red'>".$lang['delete']."</font></a></td></tr>";
			}
			
			echo "</tbody></table><br><br>";
			
			// Create button
			echo "<button type='button' class='button'><a href='/pages/new/create/'>".$lang['pages_create_page']."</a></button>";
		}
		else if(isset($_GET['arg1']) && !isset($_GET['arg2']))
		{
			if(!isset($_POST['deleteButton']))
				echo sprintf($lang['pages_delete_page'], $_GET['arg1'])."<form method='post' action=''><input type='submit' name='deleteButton' class='button' value='".$lang['yes']."'><button type='button' name='no' class='button'><a href='/pages/'>".$lang['no']."</a></button></form>";
			else
			{
				$_xml = new DOMDocument();
				$_xml->load('data/database/pages.xml');
						
				$Pages = $_xml->getElementsByTagName('Page');
				$pageIDForDelete = $_GET['arg1'];
				$pageExists = false;
								
				foreach($Pages as $Page)
				{
					$pageID = $Page->getAttribute('ID');
					
					if($pageID == $pageIDForDelete)
						$pageExists = true;
				}
				
				if($pageExists == false)
					die($lang['pages_error_0']."<meta http-equiv='refresh' content='3;URL=/pages/'>");
				else
				{
					$xpath = new DOMXpath($_xml);
					$nodeList = $xpath->query('//Page[@ID="'.(int)$pageIDForDelete.'"]');
							
					if($nodeList->length)
					{
						$node = $nodeList->item(0);
						$node->parentNode->removeChild($node);
					}
									
					$_xml->save('data/database/pages.xml');
					
					echo sprintf($lang['pages_success_0'], $pageIDForDelete).'<meta http-equiv="refresh" content="3;URL=/pages/">';
				}
			}
		}
		else
		{
			if($_GET['arg1'] == "new" && $_GET['arg2'] == "create")
			{
				if(!isset($_POST['createPage']))
					echo "<form method='post' action=''><label for='title'>".$lang['pages_create_new_page_title']."<br></label><input type='text' name='title' class='box'><br><br><br><label for='text'>".$lang['pages_create_new_page_text']."<br></label><textarea name='text' rows='5' cols='60'></textarea><br><input type='submit' name='createPage' class='button' value='".$lang['pages_create_new_page_button']."'></form>";
				else
				{
					$_title_ = $_POST['title'];
					$_text_ = $_POST['text'];
					
					$_author_ = $_SESSION['account'];
					$_date_ = date("d/m/Y H:i:s", time());
					
					if(empty($_title_))
						die($lang['pages_error_1'].'<meta http-equiv="refresh" content="5;URL=/pages/new/create/">');
					
					$_xml = new DOMDocument();
					$_xml->load('data/database/pages.xml');
					
					$Pages = $_xml->getElementsByTagName('Page');
					$lastID = -1;
					
					foreach($Pages as $Page)
					{
						$_pages = $Page->getElementsByTagName('Title');
						$_page = $_pages->item(0)->nodeValue;
						
						$lastID = $Page->getAttribute('ID');
					}
					
					$_id_ = $lastID + 1;
						
					$Page = $_xml->createElement('Page');
					$Page->setAttribute('ID', $_id_);
					
					$Title = $_xml->createElement('Title');
					$TitleText = $_xml->createTextNode($_title_);
					$Title->appendChild($TitleText);
					$Page->appendChild($Title);
					
					$Author = $_xml->createElement('Author');
					$AuthorText = $_xml->createTextNode($_author_);
					$Author->appendChild($AuthorText);
					$Page->appendChild($Author);
					
					$Date = $_xml->createElement('Date');
					$DateText = $_xml->createTextNode($_date_);
					$Date->appendChild($DateText);
					$Page->appendChild($Date);
					
					$Text = $_xml->createElement('Text');
					$TextText = $_xml->createTextNode($_text_);
					$Text->appendChild($TextText);
					$Page->appendChild($Text);
					
					$_xml->documentElement->appendChild($Page);
					
					$_xml->save('data/database/pages.xml');
					
					echo sprintf($lang['pages_success_1'], $_title_).'<meta http-equiv="refresh" content="2;URL=/pages/">';
				}
			}
			else if($_GET['arg2'] == "edit")
			{
				if(!isset($_POST['editPage']))
				{
					$id = $_GET['arg1'];
					
					$_title = "";
					$_text = "";
					
					$_xml = new DOMDocument();
					$_xml->load('data/database/pages.xml');
					
					$Pages = $_xml->getElementsByTagName('Page');
					
					foreach($Pages as $Page)
					{
						if($Page->getAttribute('ID') == $id)
						{
							$_pagesTitle = $Page->getElementsByTagName('Title');
							$_pageTitle = $_pagesTitle->item(0)->nodeValue;
							
							$_pagesText = $Page->getElementsByTagName('Text');
							$_pageText = $_pageText->item(0)->nodeValue;
							
							$_title = $_pagesTitle;
							$_text = $_pageText;
						}
					}
					
					echo "<form method='post' action=''><label for='title'>".$lang['pages_create_new_page_title']."<br></label><input type='text' name='title' class='box' value='".$_title."'><br><br><br><label for='text'>".$lang['pages_create_new_page_text']."<br></label><textarea name='text' rows='5' cols='60'>".$_text."</textarea><br><input type='submit' name='editPage' class='button' value='".$lang['pages_edit_page_button']."'></form>";
				}
				else
				{
					$id = $_GET['arg1'];
					
					$_title_ = $_POST['title'];
					$_text_ = $_POST['text'];
					
					if(empty($_title_))
						die($lang['pages_error_1'].'<meta http-equiv="refresh" content="5;URL=/pages/'.$id.'/edit/">');
					
					$_xml = new DOMDocument();
					$_xml->load('data/database/pages.xml');
					
					$Pages = $_xml->getElementsByTagName('Page');
					
					foreach($Pages as $Page)
					{
						if($Page->getAttribute('ID') == $id)
						{
							$titleTag = $Page->getElementsByTagName('Title');
							$title = $titleTag->item(0);
							$title->nodeValue = $_title_;
							
							$textTag = $Page->getElementsByTagName('Text');
							$text = $textTag->item(0);
							$text->nodeValue = $_text_;
						}
					}
					
					$_xml->save('data/database/pages.xml');
					
					echo sprintf($lang['pages_success_2'], $_title_).'<meta http-equiv="refresh" content="2;URL=/pages/'.$id.'/edit/">';
				}
			}
		}
	?>
</div>

<div class="clear">
</div>