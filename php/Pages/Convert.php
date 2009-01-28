<?php
require_once (BASE_PATH.'php/Services/xml2json.php');

class Pages_Convert extends Pages_Xml
{
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
		$data = $this->xml2array ($xml);
		
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
		$xmlobj = simplexml_load_file (CACHE_PATH.'information/'.$id.'.xml');
		return xml2json::convertSimpleXmlElementObjectIntoArray ($xmlobj);
	}
	
	/*
		Turn xml into data
	*/
	private function xml2array ($xml) 
	{
		return xml2json::convertSimpleXmlElementObjectIntoArray (simplexml_load_string ($xml));
	}
}
?>
