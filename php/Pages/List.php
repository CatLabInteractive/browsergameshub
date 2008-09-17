<?php
class Pages_List extends Pages_Page
{
	public function getContent ()
	{
		$page = new Core_Template ();
		return $page->parse ('pages/list.phpt');
	}
}
?>
