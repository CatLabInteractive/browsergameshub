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
				//'url' => '?p=game&id='.$v['b_id'],
				'url' => 'javascript:alert(\'Online soon\');',
				'name' => $v['b_name'],
				'genre' => $v['b_genre']
			);
		}
		
		$page->set ('games', $games);
		
		return $page->parse ('pages/list.phpt');
	}
}
?>
