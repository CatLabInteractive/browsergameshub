<?php
class Pages_Xml extends Pages_Page
{
	public function getOutput ()
	{
		$output = array ();
		
		$output['games'] = array ();
		
		$db = Core_Database::__getInstance ();
		
		$rows = $db->select
		(
			'b_browsergames',
			array ('*'),
			"b_isValid = 1"
		);
		
		foreach ($rows as $v)
		{
			$output['games'][] = array
			(
				'name' => $v['b_name'],
				'genre' => $v['b_genre'],
				'setting' => $v['b_settings'],
				'status' => $v['b_status'],
				'timing' => $v['b_timing'],
				'info_xml' => BASE_URL.'public/information/'.$v['b_id'].'.xml'
			);
		}
		
		header ('Content-type: text/xml');
		echo Core_Tools::output_xml ($output, 0.1, 'browsergames');
	}
}
?>
