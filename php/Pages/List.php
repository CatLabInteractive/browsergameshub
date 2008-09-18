<?php
class Pages_List extends Pages_Page
{
	public function getContent ()
	{
		$page = new Core_Template ();
		
		// Fetch all active games
		$db = Core_Database::__getInstance ();
		
		$data = $db->select
		(
			'b_browsergames',
			array ('*'),
			"b_isValid = 1",
			'b_name ASC'
		);

		$games = array ();		
		foreach ($data as $v)
		{
			$games[] = array
			(
				'url' => 'game/'.$v['b_id'].'/'.urlencode ($v['b_name']),
				//'url' => 'javascript:alert(\'Online soon\');',
				'name' => $v['b_name'],
				'genre' => $v['b_genre'],
				'setting' => $v['b_setting'],
				'status' => $v['b_status'],
				'timing' => $v['b_timing']
			);
		}
		
		$page->set ('games', $games);
		
		return $page->parse ('pages/list.phpt');
	}
}
?>
