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
		
		// Fetch the filters
		$filter = array ();
		$filter['name'] = Core_Tools::getInput ('_GET', 'name', 'varchar');
		$filter['genre'] = Core_Tools::getInput ('_GET', 'genre', 'varchar');
		$filter['setting'] = Core_Tools::getInput ('_GET', 'setting', 'varchar');
		$filter['status'] = Core_Tools::getInput ('_GET', 'status', 'varchar');
		$filter['timing'] = Core_Tools::getInput ('_GET', 'timing', 'varchar');
		
		$page->set ('filters', $filter);
		
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
		
		// WHERE stuff
		$where = 'b_isValid = 1 ';
		
		foreach ($filter as $k => $v)
		{
			if (!empty ($v))
			{
				$where .= "AND b_".$k." LIKE '%".$db->escape ($v)."%' ";
			}
		}
		
		$data = $db->select
		(
			'b_browsergames',
			array ('*'),
			$where,
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
				'genre' => strtolower ($v['b_genre']),
				'setting' => strtolower ($v['b_setting']),
				'status' => strtolower ($v['b_status']),
				'timing' => strtolower ($v['b_timing']),
				'openid' => $v['b_openid'] == 1
			);
		}
		
		$page->set ('order', $sort.'_'.$order);
		$page->set ('games', $games);
		
		return $page->parse ('pages/list.phpt');
	}
}
?>
