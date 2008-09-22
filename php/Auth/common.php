<?php
/*
$path_extra = dirname(dirname(dirname(__FILE__)));
$path = ini_get('include_path');
$path = $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);
*/

function doIncludes() {
    /**
     * Require the OpenID consumer code.
     */
    require_once "Auth/OpenID/Consumer.php";

    /**
     * Require the "file store" module, which we'll need to store
     * OpenID information.
     */
    require_once "Auth/OpenID/FileStore.php";

    /**
     * Require the Simple Registration extension API.
     */
    require_once "Auth/OpenID/SReg.php";

    /**
     * Require the PAPE extension module.
     */
    require_once "Auth/OpenID/PAPE.php";
}

doIncludes();

function displayError($message) 
{
	echo $message;
	exit(0);
}

global $pape_policy_uris;

$pape_policy_uris = array
(
	PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
	PAPE_AUTH_MULTI_FACTOR,
	PAPE_AUTH_PHISHING_RESISTANT
);

function &getStore() 
{
	/**
	* This is where the example will store its OpenID information.
	* You should change this path if you want the example store to be
	* created elsewhere.  After you're done playing with the example
	* script, you'll have to remove this directory manually.
	*/
	$store_path = CACHE_DIR.'openid';

	if (!file_exists($store_path) && !mkdir($store_path)) 
	{
		print "Could not create the FileStore directory '$store_path'. ".
			" Please check the effective permissions.";
		exit(0);
	}

	return new Auth_OpenID_FileStore ($store_path);
}

function &getConsumer() 
{
	/**
	* Create a consumer object using the store object created
	* earlier.
	*/
	$store = getStore();
	$consumer =& new Auth_OpenID_Consumer($store);
	return $consumer;
}

function getScheme() 
{
	$scheme = 'http';
	if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') 
	{
		$scheme .= 's';
	}
	return $scheme;
}

function getReturnTo() 
{
	/*
	return sprintf
	(
		"%s://%s:%s%s/openid/finish/",
			getScheme(), 
			$_SERVER['SERVER_NAME'],
			$_SERVER['SERVER_PORT'],
			dirname($_SERVER['PHP_SELF'])
	);
	*/
	
	
	return ABSOLUTE_URL.'index.php?p=openid/finish/';
}

function getTrustRoot() 
{
	/*
	return sprintf("%s://%s:%s%s/",
		   getScheme(), $_SERVER['SERVER_NAME'],
		   $_SERVER['SERVER_PORT'],
		   dirname($_SERVER['PHP_SELF']));
	*/
	
	return ABSOLUTE_URL;
}

function getOpenIDURL() 
{
	// Render a default page if we got a submission without an openid
	// value.
	if (empty($_GET['openid_url'])) 
	{
		$error = "Expected an OpenID URL.";
		die ($error);
	}
	
	// Check for http
	$url = trim ($_GET['openid_url']);
	
	if (substr ($url, 0, 4) != 'http')
	{
		$url = 'http://'.$url;
	}

	return $url;
}

function escape($thing) {
    return htmlentities($thing);
}

define ('Auth_OpenID_RAND_SOURCE', true);
?>
