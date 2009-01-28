<?php

class Core_Tools
{

	/*
	
		Translate a mysql date - field to a unix timestamp.
		Returns false if date is not set.
	
	*/
	public static function dateToTimestamp ($date)
	{
	
		return $date != '0000-00-00' ? 
			mktime (
			
				0,
				0,
				1,
				substr ($date, 5, 2),
				substr ($date, 8, 2),
				substr ($date, 0, 4)
			 
		): false;
	
	}

	public static function datetimeToTimestamp ($date)
	{
		return strtotime ($date);
	}
	
	public static function dateToMysql ($day, $month, $year)
	{
	
		return self::addZeros ($year, 4).'-'.self::addZeros ($month, 2).'-'.self::addZeros ($day, 2);
	
	}
	
	public static function datetimeToMysql ($day, $month, $year, $hour, $minute, $seconds)
	{
	
		return self::addZeros ($year, 4).
			'-'.self::addZeros ($month, 2).
			'-'.self::addZeros ($day, 2)
			.' '.self::addZeros ($hour, 2)
			.':'.self::addZeros ($minute, 2).
			':'.self::addZeros ($seconds, 2);
	
	}
	
	public static function timestampToMysql ($time = null)
	{
	
		if ($time == null)
		{
		
			$time = time ();
		
		}
	
		return self::dateToMysql (date ('d', $time), date ('m', $time), date ('Y', $time));
	
	}
	
	public static function timestampToMysqlDatetime ($time = null)
	{
	
		if ($time == null)
		{
		
			$time = time ();
		
		}
		
		return self::datetimeToMysql (date ('d', $time), date ('m', $time), date ('Y', $time), 
			date ('H', $time), date ('i', $time), date ('s', $time));
	
	}

	public function getArrayFirstValue ($a)
	{
		foreach ($a as $k => $v)
			return array ($k, $v);
	}
	
	public static function addZeros ($int, $totaal)
	{
	
		while (strlen ($int) < $totaal)
		{
		
			$int = "0".$int;
		
		}
		
		return $int;
	
	}

	public static function getInput ($dat, $key, $type, $default = false)
	{

		global $$dat;
		$dat = $$dat;

		if (!isset ($dat[$key])) {

			return $default;
		}

		else {
			// Check if the value has the right type
			if (Core_Tools::checkInput ($dat[$key], $type)) {

				return $dat[$key];

			}

			else {

				return $default;

			}

		}

	}

	public static function checkInput ($value, $type)
	{

		if ($type == 'bool' || $type == 'text')
		{

			return true;
		
		}
		
		elseif ($type == 'varchar')
		{
		
			return true;
		
		}
		
		elseif ($type == 'password')
		{
		
			return strlen ($value) > 2;
		
		}
		
		elseif ($type == 'email')
		{
		
			return strlen ($value) > 2;
		
		}
		
		elseif ($type == 'username')
		{
		
			return preg_match ('/^[a-zA-Z0-9_ .-]{3,30}$/', $value);
		
		}
		
		elseif ($type == 'village')
		{
			return strlen ($value) > 3 && strlen ($value) <= 40;
		}

		elseif ($type == 'unitname')
		{
			return preg_match ('/^[a-zA-Z0-9 ]{3,20}$/', $value);
		}
		
		elseif ($type == 'md5')
		{
		
			return strlen ($value) == 32;
		
		}
		
		elseif ($type == 'int')
		{
		
			return is_numeric ($value);
		
		}
		
		else {
		
			return false;
			echo 'fout: '.$type;
		
		}

	}
	
	public static function convert_price ($basic_price)
	{
	
		$basic_price = str_replace (",", ".", $basic_price);
		$basic_price = number_format ($basic_price, 2, ".", "");
		
		return $basic_price;
	
	}

	public static function putIntoText ($text, $ar = array(), $delimiter = '@@') 
	{
		$k = 0;
		foreach ($ar as $v) {
			$text = str_replace ($delimiter.$k, $v, $text);
			$k ++;
		}
		return $text;
	}

	public static function output_title ($title)
	{

		return htmlentities (stripslashes($title), ENT_QUOTES, 'UTF-8');

	}
	
	public function date_long ($stamp)
	{
	
		$text = Core_Text::__getInstance ();
		
		$dag = $text->get ('day'.(date ('w', $stamp) + 1), 'days', 'main');
		$maand = $text->get ('mon'.date ('m', $stamp), 'months', 'main');
	
		return Core_Tools::putIntoText (
			$text->get ('longDateFormat', 'dateFormat', 'main'),
			array
			(
				$dag,
				date ('d', $stamp),
				$maand,
				date ('Y', $stamp)
			)
		);
	
	}
	
	public static function splitLongWords ($input)
	{
	
		$array = explode (' ', $input);
		
		foreach ($array as $k => $v)
		{
		
			$array[$k] = wordwrap ($v, 20, ' ', 1);
		
		}
		
		return implode (' ', $array);
	
	}
	
	public static function output_text ($text, $p = true)
	{	
		//$input = Core_Tools::splitLongWords ($input);

		/* Config: breaks: */
		$p_open = '<p>';
		$p_close = '</p>';
		$p_break = '<br  />';

		$convert = stripslashes ($text);
		$convert = htmlentities ($convert, ENT_QUOTES, 'UTF-8');

		$bbcode_regex = array
		(
			0 => '/\[b\](.+?)\[\/b\]/s',
			1 => '/\[i\](.+?)\[\/i\]/s',
			2 => '/\[u\](.+?)\[\/u\]/s',
			3 => '/\[quote\](.+?)\[\/quote\]/s',
			4 => '/\[quote\=(.+?)](.+?)\[\/quote\]/s',
			5 => '/\[url\](.+?)\[\/url\]/s',
			6 => '/\[url\=(.+?)\](.+?)\[\/url\]/s',
			7 => '/\[img\](.+?)\[\/img\]/s',
			8 => '/\[col\=(.+?)\](.+?)\[\/col\]/s',
			9 => '/\[color\=(.+?)\](.+?)\[\/color\]/s',
			10 => '/\[colour\=(.+?)\](.+?)\[\/colour\]/s',
			11 => '/\[size\=(.+?)\](.+?)\[\/size\]/s'
		);

		$bbcode_replace = array
		(
			0 => '<span style="font-weight:bold;">$1</span>',
			1 => '<span style="font-style:italic;">$1</span>',
			2 => '<span style="text-decoration:underline;">$1</span>',
			3 => '<strong>Quote:</strong><div class="textQuote">$1</div>',
			4 => '<strong>Quoting $1:</strong><div class="textQuote">$2</div>',
			5 => '<a href="$1" target="_BLANK">$1</a>',
			6 => '<a href="$1" target="_BLANK">$2</a>',
			7 => '<img src="$1" alt="User submitted image" />',
			8 => '<span style="color:$1;">$2</span>',
			9 => '<span style="color:$1;">$2</span>',
			10 => '<span style="color:$1;">$2</span>',
			11 => '<span style="font-size:$1;">$2</span>'
		);

		$convert = preg_replace($bbcode_regex, $bbcode_replace, $convert);		
		
		if (!$p) {
			// Headers
			$convert = preg_replace (
				"/\[h(.*?)](.*?)\[\/h(.*?)]/si",
				'<h\\1>\\2</h\\1>',
				$convert
			);
		}
		
		else {
			// Headers
			$convert = preg_replace (
			
				"/\[h(.*?)](.*?)\[\/h(.*?)]/si",
				$p_close.'<h\\1>\\2</h\\1>'.$p_open,
				$convert
				
			);
		}

		// Hyperlinks
		$convert = eregi_replace(
			"\[url]([^\[]*)\[/url]",
			"<a target=\"_BLANK\" href=\"\\1\">\\1</a>", $convert);
		
		$convert = eregi_replace(
			"\[url=([^\[]*)\]([^\[]*)\[/url]",
			"<a target=\"_BLANK\" href=\"\\1\">\\2</a>", $convert);

		// Images align=left
		$convert = eregi_replace(
			"\[img]([-_./a-zA-Z0-9!&%#?,'=:~]+)\[/img]",
			"<img class=\"tc\" style=\"margin: 0px 5px;\" src=\"\\1\">", $convert);
		
		// Images align=left
		$convert = eregi_replace(
			"\[img:([-_./a-zA-Z0-9!&%#?,'=:~]+)\]([-_./a-zA-Z0-9!&%#?,'=:~]+)\[/img]",
			"<img class=\"tc\" style=\"margin: 0px 5px;\"  align=\"\\1\" src=\"\\2\">", $convert);
		

		// Paragraphs and line breaks
		$convert = str_replace ("\r", "", $convert);
		$convert = str_replace ("\n\n", $p_close.$p_open, $convert);
		$convert = str_replace ("\n", $p_break, $convert);
		
		if ($p) {
			$convert = $p_open . $convert . $p_close;
		}
		
		// Remove "empty p"
		$convert = str_replace ($p_open.$p_break, $p_open, $convert);
		$convert = str_replace ($p_open.$p_close, '', $convert);
		
		// Let's go for the smileys
		$smileys = array 
		(
			':)' 	=> 'smile',
			':-)'	=> 'smile',
			
			':('	=> 'sad',
			':-('	=> 'sad',
			
			':D'	=> 'grin',
			':-D'	=> 'grin',
			
			':P' 	=> 'tease',
			':-P' => 'tease',
			':p' 	=> 'tease',
			':-p'	=> 'tease',
			
			';)'	=> 'wink',
			';-)'	=> 'wink',
			
			':|'	=> 'frown',
			':-|'	=> 'frown',
			
			'[:/]'	=> 'unsure',
			'[:-/]'	=> 'unsure',
			
			':@'	=> 'angry',
			':-@'	=> 'angry'
		);
		
		foreach ($smileys as $k => $v)
		{
			$convert = str_replace ($k, '<img src="'.SMILEY_DIR . $v . '.png" alt="'.$k.'" class="smiley" />', $convert);
			$convert = str_replace ('['.$k.']', '<img src="'.SMILEY_DIR . $v . '.png" alt="'.$k.'" class="smiley" />', $convert);
		}
		
		return $convert;
	
	}
	
	public static function output_form ($text)
	{
	
		return htmlentities (stripslashes ($text) , ENT_QUOTES, 'UTF-8');
	
	}
	
	public static function output_varchar ($text)
	{
	
		$input = Core_Tools::splitLongWords ($text);
		return htmlentities (stripslashes ($text), ENT_QUOTES, 'UTF-8');
	
	}

	public static function color_mkwebsafe ( $in )
	{
		// put values into an easy-to-use array
		$vals['r'] = hexdec( substr($in, 0, 2) );
		$vals['g'] = hexdec( substr($in, 2, 2) );
		$vals['b'] = hexdec( substr($in, 4, 2) );
		
		// loop through
		foreach( $vals as $val )
		{
		// convert value
		$val = ( round($val/51) * 51 );
		// convert to HEX
		$out .= str_pad(dechex($val), 2, '0', STR_PAD_LEFT);
		}
		
		return $out;
	}
	
	public static function getConfirmLink ()
	{
	
		return 'confirmed';
	
	}
	
	public static function checkConfirmLink ($link)
	{
	
		return ($link == self::getConfirmLink ());
	
	}
	
	public static function getCountdown ($future, $class = 'counter')
	{
		$timeLeft = $future - time ();
		
		$hours = floor ($timeLeft / 3600);
		$minutes = floor (($timeLeft - $hours * 3600) / 60);
		$seconds = $timeLeft - $hours * 3600 - $minutes * 60;
		
		if ($hours < 10) $hours = '0'.$hours;
		if ($minutes < 10) $minutes = '0'.$minutes;
		if ($seconds < 10) $seconds = '0'.$seconds;
	
		return '<span class="'.$class.'">'.$hours.':'.$minutes.':'.$seconds.'</span>';
	}

	public static function getDuration ($duration)
	{
		$hours = floor ($duration / 3600);
		$minutes = floor ( ($duration - $hours * 3600) / 60 );
		$seconds = floor ( $duration - $hours * 3600 - $minutes * 60 );

		if ($hours < 10) { $hours = '0' . $hours; }
		if ($minutes < 10) { $minutes = '0' . $minutes; }
		if ($seconds < 10) { $seconds = '0' . $seconds; }

		if ($hours > 0)
		{
			$dur = $hours . ':' . $minutes . ':' . $seconds;
		}

		else
		{
			$dur = $minutes . ':' . $seconds;
		}

		return $dur;
	}

	public static function getDurationText ($duration)
	{
		$text = Core_Text::__getInstance ();
	
		$day = 24 * 60 * 60;
		$hour = 60 * 60;
		$minute = 60;

		$days = floor ($duration / $day);
		$duration -= $days * $day;

		$hours = floor ($duration / $hour);
		$duration -= $hours * $hour;

		$minutes = floor ($duration / $minute);
		$duration -= $minutes * $minute;

		$seconds = $duration;

		$txt = null;

		if ($days > 0)
			$txt .= $days . " ".$text->get ('days', 'datetime', 'main').", ";

		if ($hours > 0)
			$txt .= $hours . " ".$text->get ('hours', 'datetime', 'main').", ";

		if ($minutes > 0)
			$txt .= $minutes . " ".$text->get ('minutes', 'datetime', 'main').", ";

		if ($seconds > 0)
			$txt .= $seconds . " ".$text->get ('seconds', 'datetime', 'main');

		return $txt;
	}
	
	public static function checkIE6 ()
	{
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($ua,'MSIE') != false && strpos($ua,'Opera') === false)
		{
			$false = true;
			if (strpos($ua,'Windows NT 5.2') != false) {
				if(strpos($ua,'.NET CLR') === false) $false = false;
			}
	
			if (substr($ua,strpos($ua,'MSIE')+5,1) < 7 && $false)
			{
				return true;
			}
		}
		return false;
	}

	public static function floor_array ($array)
	{
		foreach ($array as $k => $v)
		{
			$array[$k] = floor ($v);
		}
		return $array;
	}

	public static function splitInPages ($page, $total, $current = 0, $perpage = 10, $maxAantalSnelclicks = 10)
	{
		$pages = ceil ($total / $perpage);

		$maxAantalSnelclicks --;
	
		if ($current < 1)
		{
			$current = 1;
		}

		$deHelft = round ($maxAantalSnelclicks / 2);
		if ($current < $deHelft)
		{
			$snelcount = 1;
			$morevar = $deHelft - $current + 1;
		} 

		else
		{
			$snelcount = $current - $deHelft;
			$morevar = $deHelft;
		}

		if ($current > ($pages - $deHelft) && $current > $deHelft)
		{
			$snelvar = $pages - $current;
			$snelcount = $snelcount - ($morevar - $snelvar);
		}
		
		if ($snelcount < 1)
		{ 
			$snelcount = 1; 
		}

		$snelmax = $snelcount + $maxAantalSnelclicks;

		// replace the stuff
		$pS = $snelcount;
		$pE = min ($snelmax, $pages);
	
		$page->set ('pagelist_curpage', $current);
		$page->set ('pagelist_total', $pages);
		$page->set ('pagelist_start', $pS);
		$page->set ('pagelist_end', $pE);
		
		return array
		(
			'limit' => ( ($current - 1) * $perpage) . ', ' . $perpage,
			'start' => ($current-1) * $perpage,
			'perpage' => $perpage
		);
	}
	
	public function output_xml ($data, $version = '0.1', $root = 'root')
	{
		function writexml (XMLWriter $xml, $data, $item_name = 'item')
		{
			foreach($data as $key => $value)
			{
				if (is_int ($key))
				{
					$key = $item_name;
				}
	
				if (is_array($value))
				{
					$xml->startElement($key);
					
					if (isset ($value['attributes']) && is_array ($value['attributes']))
					{
						foreach ($value['attributes'] as $k => $v)
						{
							$xml->writeAttribute ($k, $v);
						}
						
						unset ($value['attributes']);
					}
					
					writexml ($xml, $value, substr ($key, 0, -1));
					$xml->endElement();
				}
		
				else
				{
					$xml->writeElement($key, $value);
				}
			}
		}
	
		$xml = new XmlWriter();
		$xml->openMemory();
		$xml->startDocument('1.0', 'UTF-8');
		$xml->startElement($root);
		
		$xml->writeAttribute ('version', $version);

		writexml ($xml, $data);

		$xml->endElement();
		return $xml->outputMemory(true);
	}
}

?>
