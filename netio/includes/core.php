<?php
	//include_once('includes/debug/debug.php');
	//Debugger::Debugger('debug.txt', date("d/m/y H:i:s").' - DEBUG\t');

	include_once('xml.php');
	//Debugger::Debugger('debug.txt', 'XML INCLUDED\t');

	$xml = new XML('includes/config.xml');
	//Debugger::Debugger('debug.txt', 'XML CONFIG INCLUDED\t');
	
	$lang_prefix = preg_replace('/\s+/', "", $xml->read('Lang', 0, true));
	//Debugger::Debugger('debug.txt', 'XML CONFIG READ DONE\t');
	
	include_once('lang_'.$lang_prefix.'.php');
	//Debugger::Debugger('debug.txt', 'LANG: lang_'.$lang.'.php INCLUDED\t');
	
	function bytesToSize($bytes, $precision = 2)
	{
		$kilobyte = 1024;
		$megabyte = $kilobyte * 1024;
		$gigabyte = $megabyte * 1024;
		$terabyte = $gigabyte * 1024;
	   
		if(($bytes >= 0) && ($bytes < $kilobyte))
			return $bytes . ' B';
	 	elseif (($bytes >= $kilobyte) && ($bytes < $megabyte))
			return round($bytes / $kilobyte, $precision) . ' KB';
	 	elseif (($bytes >= $megabyte) && ($bytes < $gigabyte))
			return round($bytes / $megabyte, $precision) . ' MB';
	 	elseif (($bytes >= $gigabyte) && ($bytes < $terabyte))
			return round($bytes / $gigabyte, $precision) . ' GB';
	 	elseif ($bytes >= $terabyte)
			return round($bytes / $terabyte, $precision) . ' TB';
		else
			return $bytes . ' B';
	}
?>