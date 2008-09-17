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
		
		$check = $this->sUrl;
		
		if (stripos ($content, $check) === false)
		{
			$this->addError ('Could not find the a link back to the information XML. <br />'.
				'Please put the following code in the portal sites header: <br />'.
				htmlentities ($check));
				
			return false;
		}
		
		return true;
	}
	
	public function getData ($data)
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
				return $content->item (0)->nodeValue;
			}
		}
		return false;
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
