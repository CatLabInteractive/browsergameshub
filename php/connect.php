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
		$url = BASE_PATH.'php';
		
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

function libxml_display_error($error)
{
    $return = "";
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "<b>Warning $error->code</b>: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "<b>Error $error->code</b>: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "<b>Fatal Error $error->code</b>: ";
            break;
    }
    $return .= trim($error->message);
    if ($error->file) {
      //  $return .=    " in <b>$error->file</b>";
    }
    //$return .= " on line <b>$error->line</b>\n";

    return $return;
}

function libxml_display_errors() {
    $errors = libxml_get_errors();
    
    $out = array ();
    foreach ($errors as $error) {
        $out[] = libxml_display_error($error);
    }
    libxml_clear_errors();
    
    return $out;
}

libxml_use_internal_errors(true);
?>
