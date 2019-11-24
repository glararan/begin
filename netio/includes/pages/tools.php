<div class="content_left">
	<?php echo $lang['tools_img']; ?>
</div>

<div class="content_right">
	<?php echo $lang['tools_title']; ?>
    <br>
    <?php echo $lang['tools_text']; ?>
    <?php echo $lang['highlighter']; ?>
    
    <?php
		# tools system
	
		// TOOLS LIST
		if(!isset($_GET['arg1']))
			echo "<table width='100%'><tbody><tr><td><b>".$lang['tools_table_name']."</b></td><td><b>".$lang['tools_table_link']."</b></td></tr><tr><td>".$lang['tools_blog_name']."</td><td>".$lang['tools_blog_link']."</td></tr><tr><td>".$lang['tools_pages_name']."</td><td>".$lang['tools_pages_link']."</td></tr><tr><td>".$lang['tools_global_settings_name']."</td><td>".$lang['tools_global_settings_link']."</td></tr></tbody></table>";
		else
		{
			if($_GET['arg1'] == "blog")
			{
				if(!isset($_POST['editButton']))
				{
					// TOOLTIP
					echo "<table width='100%'><tbody><tr><td><b>".$lang['tools_table_blog_name']."</b></td><td><b>".$lang['tools_table_blog_properties']."</b></td></tr><tr><td>".$lang['tools_table_blog_tr1_title_n']."</td><td>".$lang['tools_table_blog_tr1_title_p']."</td></tr><tr><td>".$lang['tools_table_blog_tr2_author_n']."</td><td>".$lang['tools_table_blog_tr2_author_p']."</td></tr><tr><td>".$lang['tools_table_blog_tr3_date_n']."</td><td>".$lang['tools_table_blog_tr3_date_p']."</td></tr><tr><td>".$lang['tools_table_blog_tr4_link_n']."</td><td>".$lang['tools_table_blog_tr4_link_p']."</td></tr><tr><td>".$lang['tools_table_blog_tr5_text_n']."</td><td>".$lang['tools_table_blog_tr5_text_p']."</td></tr><tr><td>".$lang['tools_table_blog_tr6_text-f_n']."</td><td>".$lang['tools_table_blog_tr6_text-f_p']."</td></tr></tbody></table><br><br>";
					
					$settings = new DOMDocument();
					$settings->load('data/database/settings.xml');
					
					$BlogSetting = $settings->getElementsByTagName('Blog');
					
					foreach($BlogSetting as $_setting)
					{
						$BlogShortSetting = $_setting->getElementsByTagName('Short');
						$BlogLongSetting = $_setting->getElementsByTagName('Long');
						
						foreach($BlogShortSetting as $__setting)
						{
							$BlogShortSettingValue = $__setting->getElementsByTagName('Value');
							$short = $BlogShortSettingValue->item(0)->nodeValue;
						}
						
						foreach($BlogLongSetting as $__setting)
						{
							$BlogLongSettingValue = $__setting->getElementsByTagName('Value');
							$long = $BlogLongSettingValue->item(0)->nodeValue;
						}
					}
					
					// SHORT
					echo "<form method='post' action='' class='text_center'><label for='short'>".$lang['tools_form_blog_short_label']."<br></label><textarea name='short' rows='10' cols='100'>".$short."</textarea><br><br>";
					
					// BIG
					echo "<label for='long'>".$lang['tools_form_blog_long_label']."<br></label><textarea name='long' rows='10' cols='100'>".$long."</textarea><br><input type='submit' name='editButton' class='button' value='".$lang['tools_form_blog_edit_button']."'></form>";
				}
				else
				{
					$_short = $_POST['short'];
					$_long = $_POST['long'];
					
					$settings = new DOMDocument();
					$settings->load('data/database/settings.xml');
					
					$BlogSetting = $settings->getElementsByTagName('Blog');
					
					foreach($BlogSetting as $_setting)
					{
						$BlogShortSetting = $_setting->getElementsByTagName('Short');
						$BlogLongSetting = $_setting->getElementsByTagName('Long');
						
						foreach($BlogShortSetting as $__setting)
						{
							$BlogShortSettingValue = $__setting->getElementsByTagName('Value');
							$__short = $BlogShortSettingValue->item(0);
							$__short->nodeValue = $_short;
						}
						
						foreach($BlogLongSetting as $__setting)
						{
							$BlogLongSettingValue = $__setting->getElementsByTagName('Value');
							$__long = $BlogLongSettingValue->item(0);
							$__long->nodeValue = $_long;
						}
					}
					
					$settings->save('data/database/settings.xml');
					echo $lang['tools_blog_edit_success'];
				}
			}
			else if($_GET['arg1'] == "pages")
			{
				if(!isset($_POST['editButton']))
				{
					// TOOLTIP
					echo "<table width='100%'><tbody><tr><td><b>".$lang['tools_table_pages_name']."</b></td><td><b>".$lang['tools_table_pages_properties']."</b></td></tr><tr><td>".$lang['tools_table_pages_tr1_title_n']."</td><td>".$lang['tools_table_pages_tr1_title_p']."</td></tr><tr><td>".$lang['tools_table_pages_tr2_author_n']."</td><td>".$lang['tools_table_pages_tr2_author_p']."</td></tr><tr><td>".$lang['tools_table_pages_tr3_date_n']."</td><td>".$lang['tools_table_pages_tr3_date_p']."</td></tr><tr><td>".$lang['tools_table_pages_tr4_text_n']."</td><td>".$lang['tools_table_pages_tr4_text_p']."</td></tr></tbody></table><br><br>";
					
					$settings = new DOMDocument();
					$settings->load('data/database/settings.xml');
					
					$PagesSetting = $settings->getElementsByTagName('Pages');
					
					foreach($PagesSetting as $_setting)
					{
						$PagesSettingValue = $_setting->getElementsByTagName('Value');
						$content = $PagesSettingValue->item(0)->nodeValue;
					}
					
					echo "<form method='post' action='' class='text_center'><label for='content'>".$lang['tools_form_pages_content_label']."<br></label><textarea name='content' rows='10' cols='100'>".$content."</textarea><br><input type='submit' name='editButton' class='button' value='".$lang['tools_form_pages_edit_button']."'></form>";
				}
				else
				{
					$_content = $_POST['content'];
					
					$settings = new DOMDocument();
					$settings->load('data/database/settings.xml');
					
					$PagesSetting = $settings->getElementsByTagName('Pages');
					
					foreach($PagesSetting as $_setting)
					{
						$PagesSettingValue = $_setting->getElementsByTagName('Value');
						
						$__content = $PagesSettingValue->item(0);
						$__content->nodeValue = $_content;
					}
					
					$settings->save('data/database/settings.xml');
					echo $lang['tools_pages_edit_success'];
				}
			}
			else if($_GET['arg1'] == "settings")
			{
				if(!isset($_POST['editButton']))
				{
					$settings = new DOMDocument();
					$settings->load('includes/config.xml');
					
					$dirSearch = $settings->getElementsByTagName('Dir');
					$dirValue = $dirSearch->item(0)->getElementsByTagName('Value');
					$dir = $dirValue->item(0)->nodeValue;
					
					$langSearch = $settings->getElementsByTagName('Lang');
					$langValue = $langSearch->item(0)->getElementsByTagName('Value');
					$__lang = $langValue->item(0)->nodeValue;
					
					$_lang = "";
					if(strstr("cs_CZ", $__lang))
						$_lang = "Čeština";
					
					$maxUploadSearch = $settings->getElementsByTagName('MaxUpload');
					$maxUploadValue = $maxUploadSearch->item(0)->getElementsByTagName('Value');
					$maxUpload = $maxUploadValue->item(0)->nodeValue;
					
					$reuploadWidthSearch = $settings->getElementsByTagName('FilesWidthResize');
					$reuploadWidthValue = $reuploadWidthSearch->item(0)->getElementsByTagName('Value');
					$reuploadWidth = $reuploadWidthValue->item(0)->nodeValue;
					
					$reuploadHeightSearch = $settings->getElementsByTagName('FilesHeightResize');
					$reuploadHeightValue = $reuploadHeightSearch->item(0)->getElementsByTagName('Value');
					$reuploadHeight = $reuploadHeightValue->item(0)->nodeValue;
					
					$reuploadQualitySearch = $settings->getElementsByTagName('FilesQualityResize');
					$reuploadQualityValue = $reuploadQualitySearch->item(0)->getElementsByTagName('Value');
					$reuploadQuality = $reuploadQualityValue->item(0)->nodeValue;
					
					function _language($lang, $langWord)
					{
						if($lang == $langWord)
							return "selected='selected'";
					}
					
					// TOOLTIP
					echo "<form method='post' action=''><table width='100%'><tbody><tr><td><b>".$lang['tools_table_settings_name']."</b></td><td><b>".$lang['tools_table_settings_value']."</b></td></tr><tr><td>".$lang['tools_table_settings_tr1']."</td><td><input type='type' class='box' name='dir' value='".$dir."'></td></tr><tr><td>".$lang['tools_table_settings_tr2']."</td><td><select name='language' class='box'><option value='cs_CZ' "._language($_lang, 'cs_CZ').">Čeština</option></select></td></tr><tr><td>".$lang['tools_table_settings_tr3']."</td><td><input type='type' class='box' name='maxUpload' value='".$maxUpload."'></td></tr><tr><td>".$lang['tools_table_settings_tr4']."</td><td><input type='type' class='box' name='reuploadWidth' value='".$reuploadWidth."'></td></tr><tr><td>".$lang['tools_table_settings_tr5']."</td><td><input type='type' class='box' name='reuploadHeight' value='".$reuploadHeight."'></td></tr><tr><td>".$lang['tools_table_settings_tr6']."</td><td><input type='type' class='box' name='reuploadQuality' value='".$reuploadQuality."'></td></tr><tr><td></td><td><input type='submit' name='editButton' class='button' value='".$lang['tools_settings_edit_button']."'></td></tr></tbody></table></form>";
				}
				else
				{
					$_dir = $_POST['content'];
					$_lang_ = $_POST['language'];
					$_maxUpload = $_POST['maxUpload'];
					$_reuploadWidth = $_POST['reuploadWidth'];
					$_reuploadHeight = $_POST['reuploadHeight'];
					$_reuploadQuality = $_POST['reuploadQuality'];
					
					$settings = new DOMDocument();
					$settings->load('includes/config.xml');
					
					$DirSetting = $settings->getElementsByTagName('Dir');
					$DirValue = $DirSetting->item(0)->getElementsByTagName('Value');
					$__dir = $DirValue->item(0);
					$__dir->nodeValue = $_dir;
					
					$LangSetting = $settings->getElementsByTagName('Lang');
					$LangValue = $LangSetting->item(0)->getElementsByTagName('Value');
					$__lang_ = $LangValue->item(0);
					$__lang_->nodeValue = $_lang_;
					
					$MaxUploadSetting = $settings->getElementsByTagName('MaxUpload');
					$MaxUploadValue = $MaxUploadSetting->item(0)->getElementsByTagName('Value');
					$__maxUpload = $MaxUploadValue->item(0);
					$__maxUpload->nodeValue = $_maxUpload;
					
					$ReuploadWidthSetting = $settings->getElementsByTagName('FilesWidthResize');
					$ReuploadWidthValue = $ReuploadWidthSetting->item(0)->getElementsByTagName('Value');
					$__reuploadWidth = $ReuploadWidthValue->item(0);
					$__reuploadWidth->nodeValue = $_reuploadWidth;
					
					$ReuplaodHeightSetting = $settings->getElementsByTagName('FilesHeightResize');
					$ReuplaodHeightValue = $ReuplaodHeightSetting->item(0)->getElementsByTagName('Value');
					$__reuploadHeight = $ReuplaodHeightValue->item(0);
					$__reuploadHeight->nodeValue = $_reuploadHeight;
					
					$ReuploadQualitySetting = $settings->getElementsByTagName('FilesQualityResize');
					$ReuploadQualityValue = $ReuploadQualitySetting->item(0)->getElementsByTagName('Value');
					$__reuploadQuality = $ReuploadQualityValue->item(0);
					$__reuploadQuality->nodeValue = $_reuploadQuality;
					
					$settings->save('includes/config.xml');
					echo $lang['tools_settings_edit_success'];
				}
			}
		}
	?>
</div>

<div class="clear">
</div>