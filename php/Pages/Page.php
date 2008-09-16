<?php
abstract class Pages_Page
{
	public static function getRightPage ()
	{
		// Fetch the input
		$page = ucfirst (self::getRequestInput (0));
		
		if (!$page)
		{
			$page = 'Index';
		}
		
		$classname = 'Pages_'.$page;
		
		if (class_exists ($classname))
		{
			return new $classname ();
		}
		else
		{
			return false;
		}
	}
	
	public static function getRequestInput ($part = 0)
	{
		$p = Core_Tools::getInput ('_GET', 'p', 'varchar');
		$data = explode ('/', $p);
		return isset ($data[$part]) ? $data[$part] : false;
	}
	
	public function getOutput ()
	{
		$page = new Core_Template ();
		$page->set ('content', $this->getContent ());
		$page->set ('navigation', $this->getNavigation ());
		return $page->parse ('index.phpt');
	}
	
	public function getContent ()
	{
		return '<p>General page. No content</p>';
	}
	
	public function getNavigation ()
	{
		$page = new Core_Template ();
		return $page->parse ('navigation.phpt');
	}
}
?>
