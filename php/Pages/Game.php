<?php
class Pages_Game extends Pages_Page
{
	public function getContent ()
	{
		$id = Core_Tools::getInput ('_GET', 'id', 'int');
		
		$page = new Core_Template ();
		
		// Load game from database
		$game = new BrowserGame_Information (CACHE_PATH.'information/'.intval($id).'.xml');
		
		$page->set ('game', $game);
		
		return $page->parse ('pages/game.phpt');
	}
}
?>
