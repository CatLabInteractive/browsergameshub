<?php

class Core_Template
{

	private $values = array ();
	private $lists = array ();

	public static function getStylesheets ()
	{
	
		$style = array ();
		if (defined (TEMPLATE_DIR) && is_readable (TEMPLATE_DIR.'/style.php')) {
		
			include TEMPLATE_DIR.'/style.php';
		
		}
		
		elseif (is_readable (DEFAULT_TEMPLATE_DIR.'/style.php')) {
		
			include DEFAULT_TEMPLATE_DIR.'/style.php';
		
		}
		
		return $styles;
	
	}
	
	public function set ($var, $value, $overwrite = false, $first = false)
	{
		$this->setVariable ($var, $value, $overwrite, $first);
	}

	public function setVariable ($var, $value, $overwrite = false, $first = false)
	{
	
		if ($overwrite)
		{
		
			$this->values[$var] = $value;
		
		}
		
		else {
	
			if (isset ($this->values[$var]))
			{
		
				if ($first)
				{
				
					$this->values[$var] = $value.$this->values[$var];
				
				}
				
				else {
				
					$this->values[$var].= $value;
				
				}
			
			}
			
			else {
			
				$this->values[$var] = $value;
			
			}
		
		}
	
	}
	
	public function addListValue ($var, $value)
	{
		$this->lists[$var][] = $value;
	}
	
	public function sortList ($list)
	{
		if (isset ($this->lists[$list]))
		{
			asort ($this->lists[$list]);
		}
	}

	public function parse ($template)
	{

		/* Set static url adress */
		$this->setVariable ('static_url', STATIC_URL.'/layout/templates/default/');

		foreach ($this->values as $k => $v)
		{
		
			$$k = $v;
		
		}
		
		foreach ($this->lists as $k => $v)
		{
		
			$n = 'list_'.$k;
			
			$$n = $v;
		
		}

		ob_start ();
		
		if (defined ('template_dir') && is_readable (TEMPLATE_DIR.'/'.$template)) {
		
			include TEMPLATE_DIR.'/'.$template;
		
		}
		
		elseif (is_readable (DEFAULT_TEMPLATE_DIR.'/'.$template)) {
		
			include DEFAULT_TEMPLATE_DIR.'/'.$template;
		
		}
		
		else {
		
			echo '<h1>Template not found</h1>';
			echo '<p>'.DEFAULT_TEMPLATE_DIR.'/'.$template.'</p>';
		
		}
		
		$val = ob_get_contents();
		ob_end_clean();
		
		return $val;

	}

}

?>
