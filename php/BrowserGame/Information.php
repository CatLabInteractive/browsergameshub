<?php
class BrowserGame_Information
{
	private $sUrl;
	private $sError = array ();
	
	private $sWarnings = array ();
	
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
			$this->dom->preserveWhiteSpace = false;
		}
		return $this->dom;
	}
	
	public function getXMLDump ()
	{
		$dom = $this->getDOM ();
		
		if ($dom)
		{
			//return $this->xml;
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
		
		// Check external media
		$this->checkExternals ();
		
		return true;
	}
	
	/*
		Checks all external URI and removed invalid elements.
		This function creates warnings, not errors.
	*/
	private function checkExternals ()
	{
		$dom = $this->getDom ();
		
		$toRemove = array ();
	
		// First: check logo
		$logo = $this->getElement ('logo_url');
		if ($logo)
		{
			// Fetch the image size
			$size = @getimagesize ($logo->nodeValue);
			if (!$size || $size[0] > 100 || $size[1] > 100)
			{
				$toRemove[] = $logo;
				$this->sWarnings[] = 'Your logo is not valid. Make sure it\'s accessable and it\'s maximum 100x100 px.';
			}
		}
		
		// Second: 
		$banner = $this->getElement ('banner_url');
		if ($banner)
		{
			// Fetch the image size
			$size = @getimagesize ($banner->nodeValue);
			if (!$size || $size[0] != 468 || $size[1] != 60)
			{
				$toRemove[] = $banner;
				$this->sWarnings[] = 'Your banner is not valid. Make sure it\'s accessable and it should be 468x60 pixels precise.';
			}
		}
		
		// Check screenshots
		$screens = $this->getElement ('screenshots');
		if ($screens)
		{
			$screens = $screens->getElementsByTagName ('screenshot');
		
			for ($i = 0; $i < $screens->length; $i ++)
			{
				$screen = $screens->item($i);
				$url = $screen->getElementsByTagName ('url')->item(0)->nodeValue;
				
				$size = @getimagesize (trim ($url));
				if (!$size)
				{
					$this->sWarnings[] = 'One of your screenshots is not a valid image: <br />'.$url;
					$toRemove[] = $screen;
				}
			}
		}
		
		// Check servers
		$servers = $this->getElement ('servers');
		
		if ($servers)
		{
			$servers = $servers->getElementsByTagName ('server');
			
			for ($i = 0; $i < $servers->length; $i ++)
			{
				$server = $servers->item ($i);
								
				$game_url = $server->getElementsByTagName ('game_url')->item (0)->nodeValue;
				
				/*if (!$this->isSiteOnline ($game_url))
				{
					$this->sWarnings[] = 'Could not connect to game server '.$game_url;
					$toRemove[] = $server;
					
					continue;
				}
				*/
				
				// Check for OpenID
				$openid = $server->getElementsByTagName ('openid_url');
				
				if ($openid->length == 1)
				{
					$openid = $openid->item(0);
					
					$openid_value = str_replace ('%25', '%', $openid->nodeValue);
				
					// Check OpenID server
					/*
					if (!$this->checkOpenID ($openid_value))
					{
						$this->sWarnings[] = 'Invalid OpenID url: '.$openid_value;
						$toRemove[] = $openid;
					}
					*/
				}
				
				// Check the ranking
				$ranking = $server->getElementsByTagName ('ranking_url');
				if ($ranking->length == 1)
				{
					$ranking = $ranking->item(0);
					$ranking_url = $ranking->nodeValue;
					
					if (!$this->checkRanking ($ranking_url))
					{
						$toRemove[] = $ranking;
					}
				}
			}
		}
		
		// Check RSS
		$rss_url = $this->getElement ('rss_url');
		if ($rss_url)
		{
			$rsschk = new DOMDocument ();
			$rss = @$rsschk->load ($rss_url->nodeValue);
			if (!$rss)
			{
				$this->sWarnings[] = 'Could not reach your RSS file.';
				$toRemove[] = $rss_url;
			}
			elseif (!$rsschk->schemaValidate (SCHEMA_PATH.'rss-2.0.xsd'))
			{
				$this->sWarnings[] = 'Your RSS file is not valid. <a href="'.SCHEMA_URL.'rss-2.0.xsd">Check schema</a>';
				$toRemove[] = $rss_url;
			}
		}
		
		// Remove all nodes		
		foreach ($toRemove as $v)
		{
			$v->parentNode->removeChild ($v);
		}
	}
	
	private function checkRanking ($url)
	{
		return true;
	
		$rankcheck = new DOMDOcument ();
		$rank = @$rankcheck->load ($url);
		if (!$rank)
		{
			$this->sWarnings[] = 'Could not reach Ranking API '.$url.'.';
			
			return false;
		}
		elseif (!$rankcheck->schemaValidate (SCHEMA_PATH.'ranking.xsd'))
		{
			$warning = 'Your Ranking API <a href="'.$url.'">'.$url.'</a> is not valid.<br />Check the <a href="'.SCHEMA_URL.'ranking.xsd">XML Schema</a>.<br /><br /><strong>Errors:</strong><br />';
			
			foreach (libxml_display_errors () as $v)
			{
				$warning .= $v . "<br />";
			}
			
			$this->sWarnings[] = $warning;
			
			return false;
		}
		
		return true;
	}
	
	/*
		A special thanks to:
		http://www.jellyandcustard.com/2006/05/31/determining-if-a-url-exists-with-curl/
	*/
	private function isSiteOnline ($sUrl)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://www.jellyandcustard.com/");
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
		$data = curl_exec($ch);
		curl_close($ch);
		preg_match_all("/HTTP\/1\.[1|0]\s(\d{3})/",$data,$matches);
		$code = end($matches[1]);

		if(!$data) 
		{
			return false;
		} 
		else 
		{
			if($code==200) 
			{
				return true;
			} 
			elseif($code==404) 
			{
				return false;
			}
		}
	}
	
	private function checkOpenID ($server)
	{
		// First: check string
		if (strpos ($server, '%s') === false)
			return false;
		
		return $this->isSiteOnline ($server);
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
			return htmlentities ($element->nodeValue);
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
	
	private function getAttribute ($data)
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
			
			return $content->getAttribute ($data);
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
	
	public function getBanners ($lang = 'en')
	{
		$screenshots = $this->getElement ('banners');
		if (!$screenshots)
		{
			return array ();
		}
		
		$screenshots = $screenshots->getElementsByTagName ('banner');
		
		$out = array ();
		for ($i = 0; $i < $screenshots->length; $i ++)
		{
			$url = $screenshots->item ($i)->nodeValue;
			$type = $screenshots->item ($i)->getAttribute ('type');
			if (empty ($type)) { $type = 'image'; }
			
			$out[] = array
			(
				'url' => $url,
				'type' => $type
			);
		}
		return $out;
	}
	
	public function getLogos ($lang = 'en')
	{
		$screenshots = $this->getElement ('logos');
		if (!$screenshots)
		{
			return array ();
		}
		
		$screenshots = $screenshots->getElementsByTagName ('logo');
		
		$out = array ();
		for ($i = 0; $i < $screenshots->length; $i ++)
		{
			$url = $screenshots->item ($i)->nodeValue;
			
			$out[] = array
			(
				'url' => $url
			);
		}
		return $out;
	}
	
	/*
		Build a token
	*/
	public function getToken ()
	{
		$name = $this->getData ('name');
		
		$str = strtolower(trim($name));
		$str = preg_replace('/[^a-z0-9-]/', '-', $str);
		$token = preg_replace('/-+/', "-", $str);
		
		$token = substr ($token, 0, 40);

		if ($this->isTokenUnique ($token))
		{
			return $token;
		}
		
		$i = 2;
		
		while (!$this->isTokenUnique ($token . $i))
		{
			$i ++;
		}
		
		return $token . $i;
	}
	
	private function isTokenUnique ($token)
	{
		$db = Core_Database::__getInstance ();
		
		$chk = $db->getDataFromQuery ($db->customQuery
		("
			SELECT
				*
			FROM
				b_browsergames
			WHERE
				b_token = '{$db->escape ($token)}'
		"));
		
		return count ($chk) == 0;
	}
	
	public function updateCache ($id)
	{
		$db = Core_Database::__getInstance ();
	
		$cache = Core_Cache::__getInstance ('information/', 'xml');
		
		$data = array
		(
			'b_lastCheck' => date ('Y-m-d H:i:s'),
			'b_failures' => 0,
			'b_isValid' => 1,
			'b_name' => $this->getData ('name'),
			'b_genre' => $this->getData ('genre'),
			'b_setting' => $this->getData ('setting'),
			'b_status' => $this->getData ('status'),
			'b_timing' => $this->getData ('timing'),
			'b_openid' => $this->hasOpenId () ? '1' : '0',
			'b_revisit' => min (7, max (1, intval ($this->getAttribute ('revisit'))))
		);
		
		// Check voor token
		$chk = $db->getDataFromQuery ($db->customQuery
		("
			SELECT
				*
			FROM
				b_browsergames
			WHERE
				b_id = {$id}
		"));
		
		if (count ($chk) > 0)
		{
			if (empty ($chk[0]['b_token']))
			{
				$data['b_token'] = $this->getToken ();
			}
		}
	
		// Update the database
		$db->update
		(
			'b_browsergames',
			$data,
			"b_id = ".$id
		);
		
		// Update the XML
		$cache->setCache ($id, $this->getXMLDump ());
	}
	
	private function hasOpenId ()
	{
		$servers = $this->getServers ();
		
		foreach ($servers as $v)
		{
			if (!empty ($v['openid_url']))
			{
				return true;
			}
		}
		return false;
	}
	
	public function getLanguages ()
	{
		$out = array ();
		
		// Fetch all languages (everywhere!)
		$descriptions = $this->getElement ('descriptions');
		if (!$descriptions)
		{
			return array ();
		}
		
		$descriptions = $descriptions->getElementsByTagName ('description');
		for ($i = 0; $i < $descriptions->length; $i ++)
		{
			$item = $descriptions->item ($i);
			$attribute = $item->getAttributeNode ('lang');
			$out[$attribute->value] = $attribute->value;
		}
		return array_values ($out);
	}
	
	private function addError ($error)
	{
		$this->sError[] = $error;
	}
	
	public function getError ()
	{
		return $this->sError;
	}
	
	public function getWarnings ()
	{
		return $this->sWarnings;
	}
}
?>
