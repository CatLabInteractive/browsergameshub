<?php
class Pages_Validator extends Pages_Page
{
	public function getContent ()
	{
		// Check for input
		$url = Core_Tools::getInput ('_POST', 'infourl', 'varchar');
		
		if ($url)
		{
			return $this->getValidator ($url);
		}
		else
		{
			$page = new Core_Template ();
			return $page->parse ('pages/validator/form.phpt');
		}
	}
	
	private function getValidator ($url)
	{
		$page = new Core_Template ();

		$page->set ('contacting', 'Fetching '.$url.' for validation.');
		
		$server = new BrowserGame_Information ($url);
		
		if ($server->isValidated ())
		{
			$page->set ('portalcheck', 'Contacting '.$server->getPortalURL ().' for link check.');
		
			// Check for portal check
			if ($server->isPortalValid ())
			{
				$page->set ('success', 'Your XML appears to be valid. It has been added to our list. '.
					'Please remember that your XML will be validated before every use. <br />'.
					'Feel free to re-validate your XML any time.');
					
				// Insert
				$db = Core_Database::__getInstance ();
				
				// Check if it's already in the database
				$chk = $db->select
				(
					'b_browsergames',
					array ('*'),
					"b_url = '".$db->escape ($url)."'"
				);
				
				if (count ($chk) == 0)
				{
					$id = $db->insert
					(
						'b_browsergames',
						array
						(
							'b_url' => $url,
							'b_lastCheck' => date ('Y-m-d H:i:s')
						)
					);
				}
				
				else
				{
					$id = $chk[0]['b_id'];
				}
				
				$server->updateCache ($id);
			}
			else
			{
				$page->set ('error', $server->getError ());
			}
		}
		else
		{
			$page->set ('error', $server->getError ());
		}

		return $page->parse ('pages/validator/result.phpt');
	}
}
?>
