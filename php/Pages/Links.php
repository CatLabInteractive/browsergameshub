<?php
class Pages_Links extends Pages_Page
{
	public function getContent ()
	{
		$page = new Core_Template ();
		
		return $page->parse ('pages/links.phpt');
	}
}
?>