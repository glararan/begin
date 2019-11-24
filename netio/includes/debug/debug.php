<?php
	class Debugger
	{
		public static function Debugger($file, $message)
		{
			$handle = fopen($file, "w+");
			
			fwrite($handle, $message);
			fclose($handle);
		}
	}
?>