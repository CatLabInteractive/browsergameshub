<?php

/*

	CONNECT.PHP
	This file is loaded on every request.

*/


// Set session ID if provided
if (isset ($_GET['phpSessionId']) && !empty ($_GET['phpSessionId']))
{
	session_id ($_GET['phpSessionId']);
}

session_start();
include ('config.php');

date_default_timezone_set (TIME_ZONE);

/*
	Stupid magic quotes
*/
if (get_magic_quotes_gpc())
{
        $in = array(&$_GET, &$_POST, &$_COOKIE);
        while (list($k,$v) = each($in))
        {
                foreach ($v as $key => $val)
                {
                        if (!is_array($val))
                        {
                                $in[$k][$key] = stripslashes($val);
                                continue;
                        }
                        $in[] =& $in[$k][$key];
                }
        }
        unset($in);
}

/*
	Auto load function:
	Real OOP!
	
	Loads the class /php/group/class.php by calling "new Group_Class ();"
*/
function __autoload ($class_name) 
{

	static $cache;
	
	if (!isset ($cache[$class_name]))
	{

		$cache[$class_name] = true;

		$v = explode ('_', $class_name);
		
		$p = count ($v) - 1;
		$url = BASE_URL.'php';
		
		foreach ($v as $k => $vv)
		{
		
			if ($k == $p)
			{
				$url .= '/'.$vv.'.php';
			}
			
			else {
				$url .= '/'.$vv;
			}
		}
	
		if (file_exists ($url))
		{
			include_once ($url);
		}
		
		else {
			//echo ("Class not found: ".$url.".");
			return false;
		}
	}
}

function getPriceFromCurrency ($currency)
{
	$curs = config_getPriceList ();
	foreach ($curs as $v)
	{
		if (strtolower ($currency) == strtolower ($v['tag']))
		{
			return array ('tag' => $v['tag'], 'cost' => $v['cost']);
		}
	}
	return array ('tag' => $curs[0]['tag'], 'cost' => $curs[0]['cost']);
}

function checkIncomingUrl ($sUrl)
{
	return true; // Disable security
	
	foreach (config_getClientUrls () as $v)
	{
		if (strpos ($sUrl, $v) !== false)
		{
			return true;
		}
	}
	return false;
}

function checkAPIKey ($sKey)
{
	$key1 = md5 (md5 (PREMIUM_API_KEY.date ('dDWY', time () + 60*24*24).PREMIUM_API_KEY));
	$key2 = md5 (md5 (PREMIUM_API_KEY.date ('dDWY', time () - 60*24*24).PREMIUM_API_KEY));
	$key3 = md5 (md5 (PREMIUM_API_KEY.date ('dDWY', time ()).PREMIUM_API_KEY));
	
	return $sKey == $key1 || $sKey == $key2 || $sKey == $key3;
}

?>
