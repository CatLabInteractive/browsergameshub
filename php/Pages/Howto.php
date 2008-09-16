<?php
class Pages_Howto extends Pages_Page
{
	public function getContent ()
	{
		$page = new Core_Template ();
		return $page->parse ('pages/howto.phpt');
	}
}
?>
