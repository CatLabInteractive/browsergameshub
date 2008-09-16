<?php
class Core_Stats
{
	private $folder, $files = array ();

	public static function __getInstance ($folder)
	{
		static $in;
		if (empty ($in[$folder]))
		{
			$in[$folder] = new Core_Stats ($folder);
		}
		return $in[$folder];
	}

	public function __construct ($folder)
	{
		$this->folder = $folder;
	}

	public function get ($key, $groep, $file, $race = null, $default = null)
	{
		if (is_object ($race))
		{
			$race = $race->getName ();
		}
		
		if ($race != null)
		{
			$file = STATS_DIR . $this->folder . '/'. $race . '/' . $file . '.ini';
		}

		else
		{
			$file = STATS_DIR . $this->folder . '/'. $file . '.ini';
		}

		if (!isset ($this->files[$file]))
		{
			$this->loadFile ($file);
		}

		if (isset ($this->files[$file][$groep][$key]))
		{
			return $this->files[$file][$groep][$key];
		}

		else
		{
			// Return value
			if ($race != null)
			{
				return $this->get ($key, $groep, $file, null, $default);
			}

			else
			{
				return $default;
			}
		}
	}

	public function getGroup ($groep, $orgFile, $race = null, $default = array ())
	{
		if (is_object ($race))
		{
			$race = $race->getName ();
		}
		
		if ($race != null)
		{
			$file = STATS_DIR . $this->folder . '/'. $race . '/' . $orgFile . '.ini';
		}

		else
		{
			$file = STATS_DIR . $this->folder . '/'. $orgFile . '.ini';
		}

		if (!isset ($this->files[$file]))
		{
			$this->loadFile ($file);
		}

		if (isset ($this->files[$file][$groep]) && is_array ($this->files[$file][$groep]))
		{
			return $this->files[$file][$groep];
		}

		else
		{
			// Return value
			if ($race != null)
			{
				return $this->getGroup ($groep, $orgFile, null, $default);
			}

			else
			{
				return $default;
			}
		}
	}

	private function loadFile ($file)
	{
		if (file_exists ($file))
		{
			$this->files[$file] = parse_ini_file ($file, true);
		}

		else
		{
			$this->files[$file] = false;
		}
	}
}
?>