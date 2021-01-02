<?php

/*

	CONNECT.PHP
	This file is loaded on every request.

*/

require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

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
