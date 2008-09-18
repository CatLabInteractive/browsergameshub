<?php
require_once ('../php/connect.php');

$cache = Core_Cache::__getInstance ('information/', 'xml');
$db = Core_Database::__getInstance ();

echo '<pre>';

// Fetch all games
$l = $db->select
(
	'b_browsergames',
	array ('*'),
	null,
	'b_lastCheck DESC'
);

foreach ($l as $data)
{
	$game = new BrowserGame_Information ($data['b_url']);
	
	if ($game->isValidated () && $game->isPortalValid ())
	{
		echo $data['b_url'] . " is valid. XML Has been updated.\n";
		
		// Update the database
		$db->update
		(
			'b_browsergames',
			array
			(
				'b_lastCheck' => date ('Y-m-d H:i:s'),
				'b_failures' => 0,
				'b_isValid' => 1,
				'b_name' => $game->getData ('name'),
				'b_genre' => $game->getData ('genre'),
				'b_setting' => $game->getData ('setting'),
				'b_status' => $game->getData ('status'),
				'b_timing' => $game->getData ('timing')
			),
			"b_id = ".$data['b_id']
		);
		
		// Update the XML
		$cache->setCache ($data['b_id'], $game->getXMLDump ());
	}
	else
	{
		echo $data['b_url'] . " is not valid.\n";
	
		// Remove if keeps failing
		if ($data['b_failures'] > 10)
		{
			echo $data['b_url'] . " has been removed due to too many failures.\n";
		
			$db->remove
			(
				'b_browsergames',
				"b_id = ".$data['b_id']
			);
		}
		
		else
		{
			$db->update
			(
				'b_browsergames',
				array
				(
					'b_failures' => '++',
					'b_isValid' => 0
				),
				"b_id = ".$data['b_id']
			);
		}
	}
}

echo '</pre>';
?>
