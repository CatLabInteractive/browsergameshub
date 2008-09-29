<?php
/*
	@author: Thijs Van der Schaeghe
	@version: 0.1
	@requires: This script requires the SimpleXML PHP extension.
*/
define ('GAME_LIST_URL', 'http://www.browser-games-hub.org/xml/');

// Load the XML list
$xml = simplexml_load_file (GAME_LIST_URL);

// Loop trough the games
foreach ($xml->games->game as $v)
{
	// Fetch the information XML
	if (isset ($v->info_xml))
	{
		$game = simplexml_load_file ($v->info_xml);
		
		// Fetch description
		$descriptionNode = $game->descriptions;
		$description = null;
		
		// Only fetch the english description
		foreach ($descriptionNode as $v)
		{
			$attributes = $v->attributes ();
			
			if (!isset ($attributes['lang']) || $attributes['lang'] == 'en')
			{
				$description = (string)$v->description;
				break;
			}
		}
	
		// Fetch values
		$name = isset ($game->name) ? (string)$game->name : null;
		$site_url = isset ($game->site_url) ? (string)$game->site_url : null;
		$genre = isset ($game->genre) ? (string)$game->genre : null;
		$setting = isset ($game->setting) ? (string)$game->setting : null;
		$players = isset ($game->players) ? (string)$game->players : null;
		$timing = isset ($game->timing) ? (string)$game->timing : null;
		$age_recom = isset ($game->age_recom) ? (string)$game->age_recom : null;
		$logo_url = isset ($game->logo_url) ? (string)$game->logo_url : null;
		$banner_url = isset ($game->banner_url) ? (string)$game->banner_url : null;
		
		// The basic game data is loaded.
		// Now put this data in your database.
		
		/* BEGIN "YOU SHOULD REPLACE THIS PART" */
		echo '<h2>Game Data for '.$name.'</h2>';
		echo '<pre>';
		print_r
		(
			array
			(
				'name' => $name,
				'site_url' => $site_url,
				'genre' => $genre,
				'setting' => $setting,
				'players' => $players,
				'timing' => $timing,
				'age_recom' => $age_recom,
				'logo_url' => $logo_url,
				'banner_url' => $banner_url
			)
		);
		echo '</pre>';
		/* END "YOU SHOULD REPLACE THIS PART" */
	}
}

?>
