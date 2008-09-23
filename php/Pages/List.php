<?php
class Pages_List extends Pages_Page
{
	public function getContent ()
	{
		$page = new Core_Template ();
		
		// Fetch all active games
		$db = Core_Database::__getInstance ();
		
		// Order
		$sort = Core_Tools::getInput ('_GET', 'sort', 'varchar');
		$order = Core_Tools::getInput ('_GET', 'order', 'varchar');
		
		switch ($order)
		{
			case 'desc':
				$order = 'desc';
			break;
		
			case 'asc':
			default:
				$order = 'asc';
			break;
		}
		
		switch ($sort)
		{
			case 'setting':
			case 'status':
			case 'timing':
			case 'genre':
				$sqlorder = 'b_'.$sort.' '.$order;
			break;

			case 'name':		
			default:
				$sort = 'name';
				$sqlorder = 'b_name '.$order;
			break;
		}
		
		$data = $db->select
		(
			'b_browsergames',
			array ('*'),
			"b_isValid = 1",
			$sqlorder
		);

		$games = array ();		
		foreach ($data as $v)
		{
			$games[] = array
			(
				'url' => ABSOLUTE_URL.'game/'.$v['b_id'].'/'.urlencode ($v['b_name']),
				//'url' => 'javascript:alert(\'Online soon\');',
				'name' => $v['b_name'],
				'genre' => $v['b_genre'],
				'setting' => $v['b_setting'],
				'status' => $v['b_status'],
				'timing' => $v['b_timing'],
				'openid' => $v['b_openid'] == 1
			);
		}
		
		$page->set ('order', $sort.'_'.$order);
		$page->set ('games', $games);
		
		return $page->parse ('pages/list.phpt');
	}
}
?>
