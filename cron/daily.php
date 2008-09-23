<?php
require_once ('../php/connect.php');

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
		
		$game->updateCache ($data['b_id']);
	}
	else
	{
		echo $data['b_url'] . " is not valid.\n";
	
		// Remove if keeps failing
		if ($data['b_failures'] > 500)
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
