<?php
	// Dialler Server
	
	class static_view extends singlepage {
		
		// If we set this then a connection to the database will be made
		// this connection is stored as $_database and uses the
		// database class file.

		// Set up our page
		protected function run() {
		
			$stats = (array)json_decode(file_get_contents("https://portal.res.clixconnect.net/dialler/api/wallboard_static/RESOLVE.json"));
			
			$this->addContent("_seniorsFree", $stats['seniors_available']);
			$this->addContent("_seniorsQueue", $stats['seniors_queue']);
		


			$this->addContent("_hotKeys", $stats['referrals']);
			$this->addContent("_packOutToday", $stats['pack_out_today']);
			
			
			$this->addContent("_packOutPercent", $stats['pack_out_percentage']."%");
			$this->addContent("_currentValue", "£".$stats['pack_out_value']);

			$this->addContent("_currentDIAverage", "£".$stats['pack_out_average_di']);

		}
		
	}

?>
