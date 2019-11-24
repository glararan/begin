<?php
	class XML
	{
		private $xml;
		
		public function XML($file)
		{
			$this->xml = new DOMDocument();
			$this->xml->load($file);
		}
		
		public function read($tag, $pos, $nodeValue = false)
		{
			$search = $this->xml->getElementsByTagName($tag);
			
			if($nodeValue)
				$_search = $search->item($pos)->nodeValue;
			else
				$_search = $search->item($pos);
				
			return $_search;
		}
		
		public function getElementsByTagName($tag)
		{
			$search = $this->xml->getElementsByTagName($tag);
			
			return $search;
		}
		
		public function write()
		{
		}
		
		public function edit()
		{
		}
	}
?>