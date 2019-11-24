<?php
	// CZECH LANGUAGE FILE
	
	# Install
	$lang['install_install'] = "Instalace";
	$lang['install_account'] = "Účet";
	$lang['install_password'] = "Heslo";
	$lang['install_password2'] = "Heslo znovu";
	$lang['install_email'] = "E-Mail";
		
	$lang['install_error_0'] = "<p>Account neni vyplňen. Konec...</p>";
	$lang['install_error_1'] = "<p>Password neni vyplňen. Konec...</p>";
	$lang['install_error_2'] = "<p>Password neni vyplňen. Konec...</p>";
	$lang['install_error_3'] = "<p>Email neni vyplňen. Konec...</p>";
	$lang['install_error_4'] = "<p>Hesla se neshodují. Konec...</p>";
		
	$lang['install_success_0'] = "<p>Smažte soubor install.php, poté obnovte stránku.</p>";
	
	# Login
	$lang['login_login'] = "Přihlašení";
	$lang['login_account'] = "Účet";
	$lang['login_password'] = "Heslo";
	
	$lang['login_error_0'] = "Nenalezl jsem Váš Account v databázi. Konec...";
	$lang['login_error_1'] = "Vaše heslo se neshoduje! Konec...";
	
	$lang['login_success_0'] = "Byly jste úspěšně přihlášeni...";
	
	# Header
	$lang['menu_blog'] = "Články";
	$lang['menu_pages'] = "Stránky";
	$lang['menu_files'] = "Soubory";
	$lang['menu_users'] = "Uživatelé";
	$lang['menu_tools'] = "Nástroje";
	$lang['menu_devs'] = "DEVs";
	
	# Footer
	$lang['footer'] = "<div class='left'><a href='/logout/'>Odhlásit se</a></div><div class='right'>© ".date('Y')." <a href='http://glararan.eu'>glararan.eu</a>. Všechna práva vyhrazena.</div>";
	
	# Main
	$lang['main_img'] = "<img src='/images/main.png' height='120' width='120' alt='Main'>";
	$lang['main_title'] = "<h2>Vítejte ".$_SESSION['account']."</h2>";
	$lang['main_text'] = "Zdravím Vás v Administraci v své administraci <strong>netIO 4</strong> systému. Doufáme, že se Vám moje funkce budou líbit.";
	
	$lang['main_table_name'] = "Název";
	$lang['main_table_value'] = "Hodnota";
	$lang['main_table_language'] = "Jazyk";
	$lang['main_table_version'] = "Verze";
	
	# 404
	$lang['404_img'] = "<img src='/images/404.png' height='110' width='120' alt='404 Error'>";
	$lang['404_title'] = "<h2>404 ERROR</h2>";
	$lang['404_text'] = "Stránka ".$_SERVER['REQUEST_URI']." neexistuje nebo došlo k hrozné chybě.";
	
	# Log Out
	$lang['logout_img'] = "<img src='/images/logout.png' height='120' width='120' alt='Log Out'>";
	$lang['logout_title'] = "<h2>Log Out</h2>";
	$lang['logout_text'] = "Byly jste odhlášeni...<meta http-equiv='refresh' content='3;URL=../index.php'>";
	
	# Blog
	$lang['blog_img'] = "<img src='/images/blog.png' height='120' width='120' alt='Blog'>";
	$lang['blog_title'] = "<h2>Články</h2>";
	$lang['blog_text'] = "Zde můžete vytvořit, upravit či mazat články na blogu.";
	
	$lang['blog_table_id'] = "ID";
	$lang['blog_table_name'] = "Název článku";
	$lang['blog_table_delete'] = "Smazat";
	
	$lang['blog_create_article'] = "Vytvořit článek";
	
	$lang['blog_delete_article'] = "Chcete opravdu smazat článek s ID %s: ";
	
	$lang['blog_create_new_article_title'] = "Název článku";
	$lang['blog_create_new_article_short'] = "Úvodní text na blogu";
	$lang['blog_create_new_article_long'] = "Text celého článku";
	$lang['blog_create_new_article_button'] = "Vytvořit článek";
	
	$lang['blog_edit_article_button'] = "Upravit článek";
	
	$lang['blog_error_0'] = "Error: Článek s tímto ID neexistuje. Konec...";
	$lang['blog_error_1'] = "Kolonka pro název článku je prázdná. Konec...";
	
	$lang['blog_success_0'] = "Článek s ID: %s byl úspešně smazán.";
	$lang['blog_success_1'] = "Článek: %s byl úspešně vytvořený.";
	$lang['blog_success_2'] = "Článek: %s byl úspešně upravený.";
	
	# Pages
	$lang['pages_img'] = "<img src='/images/pages.png' height='120' width='120' alt='Pages'>";
	$lang['pages_title'] = "<h2>Stránky</h2>";
	$lang['pages_text'] = "Zde můžete vytvořit, upravit či mazat stránky.";
	
	$lang['pages_table_id'] = "ID";
	$lang['pages_table_name'] = "Název stránky";
	$lang['pages_table_delete'] = "Smazat";
	
	$lang['pages_create_page'] = "Vytvořit stránku";
	
	$lang['pages_delete_page'] = "Chcete opravdu smazat stránku s ID %s: ";
	
	$lang['pages_create_new_page_title'] = "Název stránky";
	$lang['pages_create_new_page_text'] = "Text stránky";
	$lang['pages_create_new_page_button'] = "Vytvořit stránku";
	
	$lang['pages_edit_page_button'] = "Upravit stránku";
	
	$lang['pages_error_0'] = "Error: Stránka s tímto ID neexistuje. Konec...";
	$lang['pages_error_1'] = "Kolonka pro název stránky je prázdná. Konec...";
	
	$lang['pages_success_0'] = "Stránka s ID: %s byla úspešně smazána.";
	$lang['pages_success_1'] = "Stránka: %s byla úspešně vytvořena.";
	$lang['pages_success_2'] = "Stránka: %s byla úspešně upravena.";
	
	# Files
	$lang['files_img'] = "<img src='/images/files.png' height='120' width='120' alt='Files'>";
	$lang['files_title'] = "<h2>Soubory</h2>";
	$lang['files_text'] = "Zde můžete nahrát soubory, které pak můžete používat.";
	
	$lang['files_table_name'] = "Název souboru";
	$lang['files_table_size'] = "Velikost";
	
	$lang['files_alert'] = "Musíte mít přidaný alespoň jeden soubor!";
	
	# Users
	$lang['users_img'] = "<img src='/images/users.png' height='120' width='120' alt='Users'>";
	$lang['users_title'] = "<h2>Uživatelé</h2>";
	$lang['users_text'] = "Zde můžete vytvořit, smazat či upravit uživatele.";
	
	$lang['users_table_name'] = "Název uživatele";
	$lang['users_table_delete'] = "Smazat";
	
	$lang['users_account_name'] = "Název účtu";
	$lang['users_account_pass'] = "Heslo";
	$lang['users_account_repeat_pass'] = "Heslo znovu";
	$lang['users_account_email'] = "E-Mail";
	$lang['users_create_account'] = "Vytvořit účet";
	
	$lang['users_delete_cant_delete_self'] = "Nemůžete smazat Váš účet!";
	$lang['users_delete_do_you_want'] = "Chcete opravdu smazat účet jménem %s: ";
	
	$lang['users_error_0'] = "Kolonka pro účet je prázdná. Konec...";
	$lang['users_error_1'] = "Kolonka pro heslo je prázdná. Konec...";
	$lang['users_error_2'] = "Kolonka pro heslo znovu je prázdná. Konec...";
	$lang['users_error_3'] = "Kolonka pro E-Mail je prázdná. Konec...";
	$lang['users_error_4'] = "Účet s tímto jménem již existuje. Konec...";
	$lang['users_error_5'] = "Hesla se neshodují. Konec...";
	
	$lang['users_success_0'] = "Účet %s byl úspešně vytvořený.";
	$lang['users_success_1'] = "Účet %s byl úspešně smazán";
	
	# Tools
	$lang['tools_img'] = "<img src='/images/tools.png' height='120' width='120' alt='Tools'>";
	$lang['tools_title'] = "<h2>Nástroje</h2>";
	$lang['tools_text'] = "Zde můžete upravit jak se budou zobrazovat Vaše články a stránky či ostatní nastavení.";
	
	$lang['tools_table_name'] = "Název nástroje";
	$lang['tools_table_link'] = "Odkaz";
	
	$lang['tools_blog_name'] = "Výpis článků";
	$lang['tools_blog_link'] = "Upravit výpis článků můžete <a href='/tools/blog/'>kliknutím zde</a>.";
	$lang['tools_pages_name'] = "Výpis stránek";
	$lang['tools_pages_link'] = "Upravit výpis stránek můžete <a href='/tools/pages/'>kliknutím zde</a>.";
	$lang['tools_global_settings_name'] = "Globální nastavení";
	$lang['tools_global_settings_link'] = "Upravit globální nastavení můžete <a href='/tools/settings/'>kliknutím zde</a>.";
	
	$lang['tools_table_blog_name'] = "Název vlastnosti";
	$lang['tools_table_blog_properties'] = "Vlastnost";
	$lang['tools_table_blog_tr1_title_n'] = "{TITLE}";
	$lang['tools_table_blog_tr1_title_p'] = "Název článku";
	$lang['tools_table_blog_tr2_author_n'] = "{AUTHOR}";
	$lang['tools_table_blog_tr2_author_p'] = "Autor článku";
	$lang['tools_table_blog_tr3_date_n'] = "{DATE}";
	$lang['tools_table_blog_tr3_date_p'] = "Datum vytvoření článku";
	$lang['tools_table_blog_tr4_link_n'] = "{LINK}";
	$lang['tools_table_blog_tr4_link_p'] = "Odkaz na článek";
	$lang['tools_table_blog_tr5_text_n'] = "{TEXT}";
	$lang['tools_table_blog_tr5_text_p'] = "Úvodní text článku";
	$lang['tools_table_blog_tr6_text-f_n'] = "{TEXT_FULL}";
	$lang['tools_table_blog_tr6_text-f_p'] = "Text celého článku po rozkliknutí";
	
	$lang['tools_form_blog_short_label'] = "Úvodní text";
	$lang['tools_form_blog_long_label'] = "Text celého článku";
	$lang['tools_form_blog_edit_button'] = "Upravit výpis článku";
	
	$lang['tools_blog_edit_success'] = "Výpis článku byl úspešně aktualizován.<meta http-equiv='refresh' content='3;URL=/tools/blog/'>";
	
	$lang['tools_table_pages_name'] = "Název vlastnosti";
	$lang['tools_table_pages_properties'] = "Vlastnost";
	$lang['tools_table_pages_tr1_title_n'] = "{TITLE}";
	$lang['tools_table_pages_tr1_title_p'] = "Název stránky";
	$lang['tools_table_pages_tr2_author_n'] = "{AUTHOR}";
	$lang['tools_table_pages_tr2_author_p'] = "Autor stránky";
	$lang['tools_table_pages_tr3_date_n'] = "{DATE}";
	$lang['tools_table_pages_tr3_date_p'] = "Datum vytvoření stránky";
	$lang['tools_table_pages_tr4_text_n'] = "{TEXT}";
	$lang['tools_table_pages_tr4_text_p'] = "Text stránky";
	
	$lang['tools_form_pages_content_label'] = "Text stránky";
	$lang['tools_form_pages_edit_button'] = "Upravit výpis stránek";
	
	$lang['tools_pages_edit_success'] = "Výpis stránek byl úspešně aktualizován.<meta http-equiv='refresh' content='3;URL=/tools/pages/'>";
	
	$lang['tools_table_settings_name'] = "Název vlastnosti";
	$lang['tools_table_settings_value'] = "Hodnota";
	$lang['tools_table_settings_tr1'] = "Složka (s lomítkem na začátku)";
	$lang['tools_table_settings_tr2'] = "Jazyk";
	$lang['tools_table_settings_tr3'] = "Maximální velikost pro nahrávání";
	$lang['tools_table_settings_tr4'] = "Zmenšení obrázku při nahrádání (šířka)";
	$lang['tools_table_settings_tr5'] = "Zmenšení obrázku při nahrádání (výška)";
	$lang['tools_table_settings_tr6'] = "Zmenšení obrázku při nahrádání (kvalita)";
	
	$lang['tools_settings_edit_button'] = "Upravit nastavení";
	$lang['tools_settings_edit_success'] = "Nastavení bylo úspešně aktualizováno.<meta http-equiv='refresh' content='3;URL=/tools/settings/'>";
	
	# Devs
	$lang['developers_img'] = "<img src='/images/developers.png' height='110' width='120' alt='Developers'>";
	$lang['developers_title'] = "<h2>Vývojáři</h2>";
	$lang['developers_text'] = "Jsem soukromý CMS <strong>netIO 4</strong> a běžím za podpory PHP 5 a XML, vytvořili mě níže uvedení lidé:<br><br><h3>glararan</h3><ul><li><a href='http://glararan.eu'>Web</a></li><li><a href='mailto:glararan@glararan.eu'>E-Mail</a></li><li><a href='skype:glararan?call'>Skype</a></li></ul><br>";
	
	# Other
	$lang['yes'] = "Ano";
	$lang['no'] = "Ne";
	$lang['delete'] = "Smazat";
	$lang['highlighter'] = "<div class='text_center'><br><img src='/images/highlight.png' alt='Highlighter'></div>";
?>