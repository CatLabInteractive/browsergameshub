<?php
class Pages_Xml extends Pages_Page
{
	public function getOutput ($addHeaders = true)
	{
		$output = array ();
		
		$output['games'] = array ();
		
		$db = Core_Database::__getInstance ();
		
		// Build the where
		$where = "b_isValid = 1";
		
		$openid = Core_Tools::getInput ('_GET', 'openid', 'int');
		$verified = Core_Tools::getInput ('_GET', 'verified', 'int');
		
		if ($verified || $openid)
		{
			$where .= " AND b_openid = 1";
		}
		
		$rows = $db->select
		(
			'b_browsergames',
			array ('*'),
			$where,
			'b_id ASC'
		);
		
		foreach ($rows as $v)
		{
			$output['games'][] = array
			(
				'id' => $v['b_id'],
				'token' => $v['b_token'],
				'name' => $v['b_name'],
				'genre' => $v['b_genre'],
				'setting' => $v['b_setting'],
				'status' => $v['b_status'],
				'timing' => $v['b_timing'],
				'openid' => intval ($v['b_openid']) == 1 ? '1' : '0',
				'info_xml' => BASE_URL.'public/information/'.$v['b_id'].'.xml'
			);
		}
		
		if ($addHeaders)
		{
			header ('Content-type: text/xml');
		}
		
		return Core_Tools::output_xml ($output, 1, 'browsergames');
	}
}
?>
