<?php
class Pages_Login extends Pages_Page
{
	public function getContent ()
	{
		$url = isset ($_SESSION['openid_url']) ? $_SESSION['openid_url'] : null;
		
		if (isset ($url))
		{
			return $this->getLoggedIn ($url);
		}
		else
		{
			return $this->getLogin ();
		}
	}
	
	private function getLoggedIn ($url)
	{
		$page = new Core_Template ();
		
		$page->set ('openid_url', $url);
		$page->set ('about', $this->getAbout ());
		
		return $page->parse ('pages/login/loggedin.phpt');
	}
	
	private function getAbout ()
	{
		$page = new Core_Template ();
		return $page->parse ('pages/login/about.phpt');
	}
	
	private function getLogin ()
	{
		$page = new Core_Template ();
		$page->set ('about', $this->getAbout ());
		return $page->parse ('pages/login/login.phpt');
	}
}
?>
