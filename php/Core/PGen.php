<?php

class Core_PGen {

        //
        // PRIVATE CLASS VARIABLES
        //
        private $_start_time;
        private $_stop_time;
        private $_gen_time;

        //
        // USER DEFINED VARIABLES
        //
        public $round_to;

	public static function __getInstance ()
	{
	
		static $in;
		
		if (!isset ($in))
		{
		
			$in = new Core_PGen ();
		
		}
		
		return $in;
	
	}

        //
        // FIGURE OUT THE TIME AT THE BEGINNING OF THE PAGE
        //
        public function start ()
        {

            $microstart = explode(' ',microtime());
            $this->_start_time = $microstart[0] + $microstart[1];

        }

        //
        // FIGURE OUT THE TIME AT THE END OF THE PAGE
        //
        public function stop ()
        {

            $microstop = explode(' ',microtime());
            $this->_stop_time = $microstop[0] + $microstop[1];

        }

        //
        // CALCULATE THE DIFFERENCE BETWEEN THE BEGINNNG AND THE END AND RETURN THE VALUE
        //
        public function gen ($round_to) 
        {

		$this->_gen_time = round ($this->_stop_time - $this->_start_time, $round_to);
		return $this->_gen_time; 
		
        }
}
?>