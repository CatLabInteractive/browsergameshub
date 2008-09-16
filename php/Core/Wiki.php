<?php
class Core_Wiki
{
	public static function parseHelpFile ($file)
	{
		$edit = WIKI_EDIT_URL.$file;
		
		$f = CACHE_DIR.'mediawiki/'.str_replace ('/', '|', $file.'.wiki');
		if (file_exists ($f))
		{
			if (filectime ($f) > (time() - 60 * 60 * 24))
			{
				return file_get_contents ($f);
			}

			else
			{
				unlink ($f);
			}
		}

		$t = self::parseNewFile ($file);

		if ($t)
		{
			if (file_put_contents ($f, $t))
			{
				return $t;
			}

			else
			{
				return $t . "\n\n<p>File Write Error to $f.</p>";
			}
		}

		else
		{
			return '<p>404: Not Found (' . $file . ').<br />Click <a href="' . $edit.'" target="wiki">here</a> to edit.</p>';
		}
	}

	private static function parseNewFile ($file)
	{
		$text = Core_Text::__getInstance ();
		$url = WIKI_GUIDE_URL.urlencode ($file);

		//customMail ('daedelson@gmail.com', 'bla', $url);

		// Load file from wiki
		$wiki = file_get_contents ($url);
		$wiki = unserialize ($wiki);
		
		// Get the real content
		$content = Core_Tools::getArrayFirstValue ($wiki['query']['pages']);

		if (!isset ($content[1]['revisions']))
		{
			return false;
		}

		else
		{
			$content = Core_Tools::getArrayFirstValue ($content[1]['revisions']);
			$content = $content[1]['*'];
			
			// Replace external links
			$content = preg_replace (
				'/([^\[]{1})' . '\[' . '([^ \]]+)' . '\]' . '([^\]]{1})' . '/si',
				'\\1[url]\\2[/url]\\3',
				$content
			);

			$content = preg_replace (
				'/([^\[]{1})' . '\[' . '([^ \]]+)' . ' ([^\]]+)' . '\]' . '([^\]]{1})' . '/si',
				'\\1[url=\\2]\\3[/url]\\4',
				$content
			);
			
			// Replace headers
			$content = preg_replace ("/====([^|]*?)====/si", "[h3]\\1[/h3]", $content);
			$content = preg_replace ("/===([^|]*?)===/si", "[h2]\\1[/h2]", $content);
			$content = preg_replace ("/==([^|]*?)==/si", "[h1]\\1[/h1]", $content);
			
			$txt = Core_Tools::output_text ($content);
			
			$txt = preg_replace (
				"/\[\[([^|]*?)]]/si",
				'<a href="javascript:void(0);" title="\\1" onclick="windowAction(this, \'page|=v|\\1\');">\\1</a>',
				$txt
			);

			$txt = preg_replace (
				"/\[\[([^|\[]*?)\|([^|\]]*?)\]\]/si",
				'<a href="javascript:void(0);" title="\\2" onclick="windowAction(this, \'page|=v|\\1\');">\\2</a>',
				$txt
			);
			
			return $txt;
		}
	}
}
?>