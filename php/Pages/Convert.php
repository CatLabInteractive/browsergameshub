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
				return print_r ($data, true);
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
	private function getGamelistConvertion ($id)
	{
		$xmlobj = simplexml_load_file (CACHE_PATH.'information/'.$id.'.xml');
		return self::convertSimpleXmlElementObjectIntoArray ($xmlobj);
	}
	
	/*
		Turn xml into data
	*/
	private function xml2array ($xml) 
	{
		return self::convertSimpleXmlElementObjectIntoArray (simplexml_load_string ($xml));
	}
	
	private static function convertSimpleXmlElementObjectIntoArray($simpleXmlElementObject, &$recursionDepth=0) 
	{ 
	  // Keep an eye on how deeply we are involved in recursion.


	  if ($recursionDepth > MAX_RECURSION_DEPTH_ALLOWED) {
	    // Fatal error. Exit now.
	    return(null);
	  }


	  if ($recursionDepth == 0) {
	    if (get_class($simpleXmlElementObject) != SIMPLE_XML_ELEMENT_PHP_CLASS) {
	      // If the external caller doesn't call this function initially 
	      // with a SimpleXMLElement object, return now. 
	      return(null); 
	    } else {
	      // Store the original SimpleXmlElementObject sent by the caller.
	      // We will need it at the very end when we return from here for good.
	      $callerProvidedSimpleXmlElementObject = $simpleXmlElementObject;
	    }
	  } // End of if ($recursionDepth == 0) { 


	  if (get_class($simpleXmlElementObject) == SIMPLE_XML_ELEMENT_PHP_CLASS) {
	    // Get a copy of the simpleXmlElementObject
	    $copyOfsimpleXmlElementObject = $simpleXmlElementObject;
	    // Get the object variables in the SimpleXmlElement object for us to iterate.
	    $simpleXmlElementObject = get_object_vars($simpleXmlElementObject);
	  }


	  // It needs to be an array of object variables.
	  if (is_array($simpleXmlElementObject)) {
	    // Initialize the result array.
	    $resultArray = array();
	    // Is the input array size 0? Then, we reached the rare CDATA text if any.
	    if (count($simpleXmlElementObject) <= 0) {
	      // Let us return the lonely CDATA. It could even be
	      // an empty element or just filled with whitespaces.
	      return (trim(strval($copyOfsimpleXmlElementObject)));
	    }


	    // Let us walk through the child elements now.
	    foreach($simpleXmlElementObject as $key=>$value) {
	      // When this block of code is commented, XML attributes will be
	      // added to the result array.
	      // Uncomment the following block of code if XML attributes are 
	      // NOT required to be returned as part of the result array. 
	      /*
		if((is_string($key)) && ($key == SIMPLE_XML_ELEMENT_OBJECT_PROPERTY_FOR_ATTRIBUTES)) {
			continue;
	      }
	      */


	      // Let us recursively process the current element we just visited.
	      // Increase the recursion depth by one.
	      $recursionDepth++; 
	      $resultArray[$key] = 
		xml2json::convertSimpleXmlElementObjectIntoArray($value, $recursionDepth);


	      // Decrease the recursion depth by one.
	      $recursionDepth--;
	    } // End of foreach($simpleXmlElementObject as $key=>$value) { 


	    if ($recursionDepth == 0) {
	      // That is it. We are heading to the exit now.
	      // Set the XML root element name as the root [top-level] key of
	      // the associative array that we are going to return to the caller of this
	      // recursive function.
	      $tempArray = $resultArray;
	      $resultArray = array();
	      $resultArray[$callerProvidedSimpleXmlElementObject->getName()] = $tempArray;
	    }


	    return ($resultArray);
	  } else {
	    // We are now looking at either the XML attribute text or
	    // the text between the XML tags.
	    return (trim(strval($simpleXmlElementObject)));
	  } // End of else
	} // End of function convertSimpleXmlElementObjectIntoArray.

}
?>
