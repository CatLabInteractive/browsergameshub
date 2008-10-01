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
	'b_lastCheck ASC'
);

$refresh = isset ($_GET['refresh']);

foreach ($l as $data)
{
	if ($refresh || strtotime ($data['b_lastCheck']) < (time () - ($data['b_revisit'] * 60*60*24)))
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
			if (strtotime ($data['b_lastCheck']) < time () - 60*60*24*31)
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
}

echo '</pre>';
?>
