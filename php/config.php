<?php
/*

	Configuration
	
*/ 

define ('BASE_PATH', dirname (dirname (__FILE__)));

if 
(
	$_SERVER['SERVER_NAME'] == 'daedelserv.local' || 
	$_SERVER['SERVER_NAME'] == 'thijsvanderschaeghe.no-ip.com' ||
	$_SERVER['SERVER_NAME'] == 'daedeloth.no-ip.org' ||
	$_SERVER['SERVER_NAME'] == '192.168.0.100'
)
{
	define ('DB_USERNAME', 'myuser');
	define ('DB_PASSWORD', 'myuser');
	define ('DB_SERVER', 'localhost');
	define ('DB_DATABASE', 'browsergames');
}
else
{
	define ('DB_USERNAME', 'browser_games');
	define ('DB_PASSWORD', 'BmWoeJBpeOBPIjsmEZHpOIBE654EomijBEmHW');
	define ('DB_SERVER', 'localhost');
	define ('DB_DATABASE', 'browser_games');
}

define ('BASE_URL', '');
define ('TIME_ZONE', 'Europe/Brussels');

define ('DEFAULT_TEMPLATE_DIR', 'templates/');

?>
