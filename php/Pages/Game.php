<?php
class Pages_Game extends Pages_Page
{
	public function getContent ()
	{
		$id = $this->getRequestInput (1);
		
		$page = new Core_Template ();
		
		// Load game from database
		$game = new BrowserGame_Information (CACHE_PATH.'information/'.intval($id).'.xml');
		
		$page->set ('game_url', ABSOLUTE_URL.'game/'.$id.'/'.urlencode ($game->getData ('name')).'/');
		
		$servers = $game->getServers ();
		
		foreach ($servers as $k => $v)
		{
			if (isset ($_SESSION['openid_url']) && isset ($v['openid_url']))
			{
				$servers[$k]['game_url'] = sprintf ($v['openid_url'], urlencode ($_SESSION['openid_url']));
				$servers[$k]['directplay_url'] = sprintf ($v['openid_url'], urlencode ($_SESSION['openid_url']));
			}
			elseif (isset ($v['openid_url']))
			{
				$servers[$k]['directplay_url'] = ABSOLUTE_URL . 'login/';
			}
			else
			{
				$servers[$k]['directplay_url'] = null;
			}
		}
		
		$page->set ('servers', $servers);
		
		$page->set ('xml_url', BASE_URL.'public/information/'.$id.'.xml');
		$page->set ('game', $game);
		
		$languages = $game->getLanguages ();
		$language = Core_Tools::getInput ('_GET', 'lang', 'varchar', 'en');
		
		$page->set ('languages', $languages);
		
		if (!in_array ($language, $languages))
		{
			$language = $languages[0];
		}
		
		$page->set ('language', $language);
		
		return $page->parse ('pages/game.phpt');
	}
}
?>
