<?php
class BrowserGame_Information
{
	private $sUrl;
	private $sError;
	
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
	
	public function getPortalUrl ()
	{
		$dom = $this->getDom ();
		if ($dom)
		{
			$content = $dom->getElementsByTagName('root')->item (0)->getElementsByTagName('content')->item (0);
			return $content->getElementsByTagName ('portal_url')->item (0)->nodeValue;
		}
		return false;
	}
	
	public function isValidated ()
	{
		// Fetch the XML file
		$content = $this->getXMLContent ();
		
		if (empty ($content))
		{
			$this->sError = 'Could not connect to server. Please make sure your XML is readable.';
			return false;
		}
		
		$dom = $this->getDom ();
		
		if (!$dom)
		{
			$this->sError = 'Could not validate your XML file. It seems like the file does not valid XML data.';
			return false;
		}
		
		// Check for validation (overwritten)
		if (false && !$dom->schemaValidate ('schema/information.dtd'))
		{
			$this->sError = 'Could not validate your XML to the DTD. Are you sure you have included all required elements?';
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
			$this->sError = 'Could not connect to portal site.';
			return false;
		}
		
		// Look for back link
		// <link rel="browser-game-info" href="http://master.dolumar.be/serverlist/list/">
		
		$check = '<link rel="browser-game-info" href="'.$this->sUrl.'">';
		
		if (stripos ($content, $check) === false)
		{
			$this->sError = 'Could not find the a link back to the information XML. <br />'.
				'Please put the following code in the portal sites header: <br />'.
				htmlentities ($check);
				
			return false;
		}
		
		return true;
	}
	
	public function getError ()
	{
		return $this->sError;
	}
}
?>
