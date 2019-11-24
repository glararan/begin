<?php
	function printExpansion($data)
	{
		switch($data['expansion'])
		{
			case 0:
				return "Classic";
				break;
				
			case 1:
				return "The Burning Crusade";
				break;
				
			case 2:
				return "Wrath of The Lich King";
				break;
				
			case 3:
				return "Cataclysm";
				break;
		}
	}
	
	function printTitle()
	{
		switch($_GET['page'])
		{
			case "home":
				return "Přehled";
				break;
				
			case "emailchange":
				return "Změna E-mailu";
				break;
				
			case "accountexp":
				return "Změna typu účtu";
				break;
				
			case "passwordchange":
				return "Změna hesla";
				break;
				
			case "itemtransfer":
				return "Převod itemu - Krok 1";
				break;
				
			case "itemtransfer2":
				return "Převod itemu - Krok 2";
				break;
				
			case "shopsummary":
				return "Shop - Přehled";
				break;
				
			case "kreditbuy":
				return "Kredity - Nákup";
				break;
				
			case "kredittransfer":
				return "Kredity - Převod";
				break;
				
			case "charactertransfer":
				return "Převod postavy";
				break;
				
			case "itembuy":
				return "Shop - Objednávka";
				break;
				
			default:
				return "Přehled";
				break;
		}
	}
	
	function displayShop($db_realmd)
	{
		$query = mysql_query("SELECT * FROM `".$db_realmd."`.`shop` ORDER BY`id`") or die(mysql_error());
		
		while($data = mysql_fetch_array($query))
		{
			echo '<tr><td><a href="#" class="shopname">'.$data['name'].'</a></td><td class="shopcena">'.$data['cost'].' kreditů</td><td><a href="?page=itembuy&amp;id='.$data['id'].'" class="shopkoupit">Koupit</a></td></tr>';
		}
	}
	
	function selectChacked($item, $eq)
	{
		if($item == $eq)
			echo ' selected="selected"';
		else
			echo "";
	}
?>