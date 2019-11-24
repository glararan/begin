<?php
	class Debugger
	{
		public function Debugger($file, $message)
		{
			$handle = fopen($file, "w+");
			
			fwrite($handle, $message);
			fclose($handle);
		}
	}
?>