<?php
class Core_Cache
{
	public static function __getInstance ($sDir, $extension = 'tmp')
	{
		static $in;
		
		if (!isset ($in))
		{
			$in = array ();
		}
		
		if (!isset ($in[$sDir]))
		{
			$in[$sDir] = new Core_Cache ($sDir, $extension);
		}
		
		return $in[$sDir];
	}
	
	private $sDir = '';
	private $sPath = '';
	private $sExt = '';
	
	public function __construct ($sDir, $extension = 'tmp')
	{		
		$this->sDir = $sDir;
		$this->sPath = CACHE_PATH . $sDir;
		$this->sExt = '.' . $extension;
		
		// Check if cache dir exists & create if not
		$this->touchFolder ($sDir);
	}
	
	public function touchFolder ($sDir)
	{
		if (!file_exists ($sDir))
		{
			$sCrums = explode ('/', $sDir);
			$sPath = CACHE_PATH;
			foreach ($sCrums as $v)
			{
				$sPath .= $v . '/';
				if (!file_exists ($sPath))
				{
					mkdir ($sPath);
				}
			}
		}
	}
	
	public function getFolder ($subpath = '')
	{
		if (!empty ($subpath))
		{
			$realpath = $this->sPath . '/' . $subpath;
			$this->touchFolder ($this->sDir . '/' . $subpath);
		}
		else
		{
			$realpath = $this->sPath;
		}
		
		return $realpath;
	}
	
	public function hasCache ($sKey, $iLifeSpan = 86400)
	{
		if (file_exists ($this->sPath . $sKey . $this->sExt))
		{
			if (filectime ($this->sPath . $sKey . $this->sExt) > (time () - $iLifeSpan))
			{
				return true;
			}
			else
			{
				unlink ($this->sPath . $sKey . $this->sExt);
				return false;
			}
		}
		return false;
	}
	
	public function setCache ($sKey, $sContent)
	{
		$out = file_put_contents ($this->sPath . $sKey . $this->sExt, $sContent);

		chmod ($this->sPath . $sKey . $this->sExt, 0777);

		return $out;
	}
	
	public function getCache ($sKey, $iLifeSpan = 86400)
	{
		if (self::hasCache ($sKey, $iLifeSpan))
		{
			return file_get_contents ($this->sPath . $sKey . $this->sExt);
		}
		else
		{
			return false;
		}
	}
}
?>
