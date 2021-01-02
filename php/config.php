<?php
/*

	Configuration
	
*/ 

define ('BASE_PATH', dirname (dirname (__FILE__)).'/');
define ('STATIC_URL', '');

define ('DB_USERNAME', getenv('DB_USERNAME'));
define ('DB_PASSWORD', getenv('DB_PASSWORD'));
define ('DB_SERVER', getenv('DB_SERVER'));
define ('DB_DATABASE', getenv('DB_DATABASE'));

define('HTTPS', isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN));
define ('BASE_URL', (HTTPS ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'].'/');


define ('TIME_ZONE', 'Europe/Brussels');

define ('DEFAULT_TEMPLATE_DIR', 'templates/');
define ('CACHE_PATH', BASE_PATH.'public/');
define ('CACHE_URL', BASE_URL.'public/');
define ('SCHEMA_PATH', BASE_PATH.'schema/');
define ('SCHEMA_URL', BASE_URL.'schema/');

define ('CACHE_DIR', CACHE_PATH);

define ('ABSOLUTE_URL', BASE_URL);
