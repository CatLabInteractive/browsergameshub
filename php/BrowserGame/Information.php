<?php
class BrowserGame_Information
{
	private $sUrl;
	private $sError = array ();
	
	private $xml = null;
	private $dom = null;

	public function __construct ($url)
	{
		$this->sUrl = $url;
	}
	
	private function getXMLContent ()
	{
		if (!isset ($this->xml))
		{
			$this->xml = @file_get_contents ($this->sUrl);
		}
		return $this->xml;
	}
	
	private function getDOM ()
	{
		if (!isset ($this->dom))
		{
			$content = $this->getXMLContent ();
			if (empty ($content))
			{
				return false;
			}
			
			$this->dom = DOMDocument::loadXML ($content);
		}
		return $this->dom;
	}
	
	public function getXMLDump ()
	{
		$dom = $this->getDOM ();
		
		if ($dom)
		{
			return $dom->saveXML ();
		}
		return false;
	}
	
	public function getPortalUrl ()
	{
		$dom = $this->getDom ();
		if ($dom)
		{
			$content = $dom->getElementsByTagName('browsergameshub');
			if ($content && $content->length > 0)
			{
				$content = $content->item (0);
			}
			else
			{
				return false;
			}
			
			return $content->getElementsByTagName ('site_url')->item (0)->nodeValue;
		}
		return false;
	}
	
	public function isValidated ()
	{
		// Fetch the XML file
		$content = $this->getXMLContent ();
		
		if (empty ($content))
		{
			$this->addError ('Could not connect to server. Please make sure your XML is readable.');
			return false;
		}
		
		$dom = $this->getDom ();
		
		if (!$dom)
		{
			$this->addError ('Could not validate your XML file. It seems like the file does not contain valid XML data.');
			return false;
		}
		
		// Check for validation (overwritten)
		if (!$dom->schemaValidate (SCHEMA_PATH.'information.xsd'))
		{
			foreach (libxml_display_errors () as $v)
			{
				$this->addError ($v);
			}
			
			$this->addError ('XML Validation failed, please check the XML schema <a href="schema/information.xsd">here</a>.');
			
			return false;
		}
		
		return true;
	}
	
	public function isPortalValid ()
	{
		// Contact portal
		$url = $this->getPortalUrl ();
		
		$content = @file_get_contents ($url);
		
		if (empty ($content))
		{
			$this->addError ('Could not connect to your site.');
			return false;
		}
		
		// Look for back link
		// <link rel="browser-game-info" href="http://master.dolumar.be/serverlist/list/">
		
		$check = '"'.$this->sUrl.'"';
		$check2 = "'".$this->sUrl."'";
		
		if (stripos ($content, $check) === false && stripos ($content, $check2) === false)
		{
			$this->addError ('Could not find the a link back to the information XML. <br />'.
				'Please put the following code in the portal sites header: <br />'.
				htmlentities ($check));
				
			return false;
		}
		
		return true;
	}
	
	public function getData ($data, $default = false)
	{
		$element = $this->getElement ($data);
		if ($element)
		{
			return $element->nodeValue;
		}
		else
		{
			return $default;
		}
	}
	
	private function getElement ($data)
	{
		$dom = $this->getDom ();
		if ($dom)
		{
			$content = $dom->getElementsByTagName('browsergameshub');
			if ($content && $content->length > 0)
			{
				$content = $content->item (0);
			}
			else
			{
				return false;
			}
			
			$content = $content->getElementsByTagName ($data);
			
			if ($content && $content->length > 0)
			{
				return $content->item (0);
			}
		}
		return false;
	}
	
	public function getDescription ($lang = 'en')
	{
		$descriptions = $this->getElement ('descriptions');
		if (!$descriptions)
		{
			return false;
		}
		
		$descriptions = $descriptions->getElementsByTagName ('description');
		for ($i = 0; $i < $descriptions->length; $i ++)
		{
			$item = $descriptions->item ($i);
			$attribute = $item->getAttributeNode ('lang');
			if ($attribute->value == $lang)
			{
				return $item->nodeValue;
			}
		}
		return false;
	}
	
	public function getServers ($lang = 'en')
	{
		$descriptions = $this->getElement ('servers');
		if (!$descriptions)
		{
			return array ();
		}
		
		$descriptions = $descriptions->getElementsByTagName ('server');
		
		$out = array ();
		
		// Default information
		$base_info = array
		(
			'name' => '?',
			'version' => '?',
			'game_url' => '#',
			'ranking_url' => null,
			'openid_url' => null,
			'players' => '?',
			'status' => '?',
			'description' => '?'
		);
		
		for ($i = 0; $i < $descriptions->length; $i ++)
		{
			$tmp = $base_info;
			
			$domnodes = $descriptions->item($i)->childNodes;
			for ($j = 0; $j < $domnodes->length; $j ++)
			{
				$tmp[$domnodes->item ($j)->nodeName] = $domnodes->item ($j)->nodeValue;
			}
			
			$out[] = $tmp;
		}
		
		return $out;
	}
	
	public function getScreenshots ($lang = 'en')
	{
		$screenshots = $this->getElement ('screenshots');
		if (!$screenshots)
		{
			return array ();
		}
		
		$screenshots = $screenshots->getElementsByTagName ('screenshot');
		
		$out = array ();
		for ($i = 0; $i < $screenshots->length; $i ++)
		{
			$url = $screenshots->item ($i)->getElementsByTagName ('url')->item(0)->nodeValue;
			
			$description = $screenshots->item ($i)->getElementsByTagName ('description')->item(0)->childNodes->item(0)->nodeValue;
			
			$out[] = array
			(
				'url' => $url,
				'description' => $description
			);
		}
		return $out;
	}
	
	private function addError ($error)
	{
		$this->sError[] = $error;
	}
	
	public function getError ()
	{
		return $this->sError;
	}
}
?>
