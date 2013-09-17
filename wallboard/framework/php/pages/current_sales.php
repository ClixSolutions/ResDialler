<?php

	class current_sales extends singlepage {
		
		// Set up our page
		protected function run() {
			
			$current_stats = json_decode(file_get_contents("https://portal.res.clixconnect.net/dialler/api/wallboard_past_stats/RESOLVE.json"));
						
			$this->addContent("hotKeysLastMonth", $current_stats->last_month->referrals);
			$this->addContent("packOutLastMonth", $current_stats->last_month->pack_out);
			$this->addContent("totalPackOutLastMonth", $current_stats->last_month->pack_out_value);
			$this->addContent("packOutRatioLastMonth", $current_stats->last_month->pack_out_percentage);
			
			$this->addContent("hotKeysThisMonth", $current_stats->this_month->referrals);
			$this->addContent("packOutThisMonth", $current_stats->this_month->pack_out);
			$this->addContent("totalPackOutThisMonth", $current_stats->this_month->pack_out_value);
			$this->addContent("packOutRatioThisMonth", $current_stats->this_month->pack_out_percentage);
			
			$this->addContent("hotKeysThisWeek", $current_stats->this_week->referrals);
			$this->addContent("packOutThisWeek", $current_stats->this_week->pack_out);
			$this->addContent("totalPackOutThisWeek", $current_stats->this_week->pack_out_value);
			$this->addContent("packOutRatioThisWeek", $current_stats->this_week->pack_out_percentage);
			
		}
		
	}

?>
