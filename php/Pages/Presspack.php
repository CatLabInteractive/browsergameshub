<?php
class Pages_Presspack extends Pages_Page
{
	public function getOutput ()
	{
		$id = $this->getRequestInput (1);
		
		$db = Core_Database::__getInstance ();
		
		// Fetch game from database
		$l = $db->select
		(
			'b_browsergames',
			array ('*'),
			"b_id = ".intval ($id)
		);
		
		if (count ($l) != 1)
		{
			return '<p>Invalid input: game not found.</p>';
		}
		
		$gameData = $l[0];
		
		// Load game from database
		$game = new BrowserGame_Information (CACHE_PATH.'information/'.intval($id).'.xml');
		
		// What do we need to include?
		// 1. screenshots
		// 2. banners
		// 3. information (in xml)
		
		$files = array ();
		
		$files['information'] = CACHE_PATH.'information/'.intval($id).'.xml';
		
		$banner = $game->getData ('banner_url');
		if ($banner)
		{
			$files['banner'] = $banner;
		}
		
		$banner = $game->getData ('logo_url');
		if ($banner)
		{
			$files['logo'] = $banner;
		}
		
		// Screenshots
		$files['screenshots'] = array ();
		foreach ($game->getScreenshots () as $v)
		{
			$files['screenshots'][] = $v['url'];
		}
		
		// Banners
		$files['banners'] = array ();
		foreach ($game->getBanners () as $v)
		{
			if (!isset ($files['banners'][$v['type']]))
			{
				$files['banners'][$v['type']] = array ();
			}
			
			$files['banners'][$v['type']][] = $v['url'];
		}
		
		echo "<pre>";
		
		echo "Please wait, we are building your press pack\n\n";
		flush ();
		
		$zip = $this->getZip ($files, $game->getData ('name'));
		echo "</pre>";
		
		/*
		echo "<pre>";
		print_r ($files);
		echo "</pre>";
		*/
		
		echo "<p>You can download your press package <a href=\"{$zip}\">here</a></p>";
		echo '<script type="text/javascript">window.location=\''.$zip.'\';</script>';
	}
	
	private function getCache ()
	{
		$cache = Core_Cache::__getInstance ('zips');
		return $cache;
	}
	
	private function getZip ($files, $filename)
	{
		$cache = $this->getCache ();
		$folder = $cache->getFolder ();
		
		$zipname = $folder . "/" .  $filename . ".zip";
		
		if (file_exists ($zipname))
		{
			unlink ($zipname);
		}
		
		$zip = new ZipArchive ();
		$res = $zip->open ($zipname, ZIPARCHIVE::CREATE);
		if ($res === true)
		{
			echo "Adding files\n";
			flush ();
			
			$tmpfolderpath = $cache->getFolder ($filename);
			$this->addFiles ($zip, $files, '', '', $tmpfolderpath);
		}
		else
		{
			echo "Failed: " . $res;
		}
		$zip->close ();
		
		$url = CACHE_URL . 'zips/' . $filename . ".zip";
		
		flush ();
		
		return $url;
	}
	
	private function addFiles ($zip, $files, $path, $key, $tmpfolderpath)
	{
		if (is_array ($files))
		{
			foreach ($files as $k => $v)
			{
				$this->addFiles ($zip, $v, $path . '/' . $key, $k, $tmpfolderpath);
			}
		}
		else
		{
			$this->addFile ($zip, $files, $path, $key, $tmpfolderpath);
		}
	}
	
	private function addFile ($zip, $files, $path, $key, $tmpfolderpath)
	{
		$lastpart = $key;
		if (is_numeric ($lastpart))
		{
			$lastpart = $this->getFilename ($files);
		}
		
		else
		{
			$lastpart = $key . '.' . $this->getFileExtension ($files);
		}
		
		$folder = substr ($path, 1) . "/";
		$folder = substr ($folder, 1);
		
		$localname = $folder . $lastpart;
		
		// First we download the file
		$md5 = md5 ($localname);
		
		$cache = $this->getCache ();
		
		$localtmpname = $tmpfolderpath . '/' . $md5;
		
		copy ($files, $localtmpname);
		
		//echo "- " . $tmpfolderpath . $folder . "\n";
		
		//copy ($files, $tmpfolderpath . $localname);
		
		if ($zip->addFile ($localtmpname, $localname))
		{
			echo "OK! ";
		}
		else
		{
			echo "Error! ";
		}
		
		echo $files . " => " . $localname . "\n";
		flush ();
	}
	
	private function getFilename ($file)
	{
		$file = explode ("/", $file);
		$file = $file[count ($file) - 1];
		
		$ext = $this->getFileExtension ($file);
		
		$file = substr ($file, 0, 0 - (strlen ($ext) + 1));
		
		return $file . '.' . $ext;
	}
		
	private function getFileExtension ($file)
	{
		$file = explode ('.', $file);
		$ext = $file[count ($file) - 1];
		
		return strtolower ($ext);
	}
}
?>
