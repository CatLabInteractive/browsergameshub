<?php
class Pages_Index extends Pages_Page
{
	public function getContent ()
	{
		$page = new Core_Template ();
		return $page->parse ('pages/about.phpt');
	}
}
?>
