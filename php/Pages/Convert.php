<?php
class Pages_Convert extends Pages_Xml
{
	private $sAttributePrefix = '@';
	private $domOptions;
	
	public function __construct ()
	{
		$this->domOptions = LIBXML_NOBLANKS + LIBXML_NSCLEAN;
	}

	public function getOutput ()
	{
		$format = self::getRequestInput (1);
		$game = intval (self::getRequestInput (2));
		
		// Clean the format
		switch ($format)
		{
			case 'print':
			case 'json':
			case 'serialized':

			break;
			
			default:
				$format = 'print';
			break;
		}
		
		if ($game > 0)
		{
			$data = $this->getGameConvertion ($game);
		}
		else
		{
			$data = $this->getGamelistConvertion ($format);
		}
		
		// Translate to the requested format.
		switch ($format)
		{
			case 'json':
				return json_encode ($data);
			break;
			
			case 'serialized':
				return serialize ($data);
			break;
		
			default:
				return '<pre>'.print_r ($data, true).'</pre>';
			break;
		}
	}
	
	/*
		Return a list of all games in data format!
	*/
	private function getGamelistConvertion ($format)
	{
		$xml = parent::getOutput (false);
		
		$dom = DOMDocument::loadXML ($xml, $this->domOptions);
		
		$data = $this->xml2array ($dom);
		
		// Important! Replace the info_xml values to point 
		// to the converted script!
		if (
			isset ($data['browsergames'])
			&& isset ($data['browsergames']['games'])
			&& isset ($data['browsergames']['games']['game'])
			&& is_array ($data['browsergames']['games']['game'])
		)
		{
			foreach ($data['browsergames']['games']['game'] as $id => $game)
			{
				if (isset ($game['info_xml']) && isset ($game['id']))
				{
					$data['browsergames']['games']['game'][$id]['info_url'] = BASE_URL.'convert/'.$format.'/'.$game['id'].'/';
				}
			}
		}
		
		return $data;
	}
	
	/*
		Return data for one game
	*/
	private function getGameConvertion ($id)
	{
		$sUrl = CACHE_PATH.'information/'.$id.'.xml';
		$sUrl = 'http://browser-games-hub.org/public/information/3.xml';
	
		//$xmlobj = simplexml_load_file ($sUrl);
		//return xml2json::convertSimpleXmlElementObjectIntoArray ($xmlobj);
		
		
		
		return self::xml2array (DOMDocument::load ($sUrl, $this->domOptions));
	}
	
	/*
		Turn xml into data
	*/
	private function xml2array ($dom) 
	{
		//return xml2json::convertSimpleXmlElementObjectIntoArray (simplexml_load_string ($xml));	
		return $this->dom2Array ($dom);
	}
	
	private function dom2Array ($node)
	{	
		$result = array();
		if ($node->nodeType == XML_TEXT_NODE) 
		{
			$result = $node->nodeValue;
		}
		else 
		{
			if($node->hasAttributes()) 
			{
				$attributes = $node->attributes;
				if(!is_null($attributes)) 
					foreach ($attributes as $index=>$attr) 
						$result['@'.$attr->name] = $attr->value;
			}
			
			if($node->hasChildNodes())
			{
				$children = $node->childNodes;
				for($i=0;$i<$children->length;$i++) 
				{
					$child = $children->item($i);
					if($child->nodeName != '#text')
						if(!isset($result[$child->nodeName]))
							$result[$child->nodeName] = $this->dom2array($child);
						else {
							$aux = $result[$child->nodeName];
							$result[$child->nodeName] = array( $aux );
							$result[$child->nodeName][] = $this->dom2array($child);
						}
				}
			}
		}
		return $result;
	}
}
?>
