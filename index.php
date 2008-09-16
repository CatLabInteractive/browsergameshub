<?php
include ('php/connect.php');

$page = Pages_Page::getRightPage ();

if ($page)
{
	echo $page->getOutput ();
}
else
{
	header('Status: 404 Not Found');
	header('HTTP/1.0 404 Not Found');
	
	echo 'Page not found!';
}

?>
