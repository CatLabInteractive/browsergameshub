<?php
class Pages_Game extends Pages_Page
{
	public function getContent ()
	{
		$id = $this->getRequestInput (1);
		
		$page = new Core_Template ();
		
		// Load game from database
		$game = new BrowserGame_Information (CACHE_PATH.'information/'.intval($id).'.xml');
		
		$page->set ('game', $game);
		
		return $page->parse ('pages/game.phpt');
	}
}
?>
