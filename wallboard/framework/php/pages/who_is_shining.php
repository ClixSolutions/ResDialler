<?php

	class who_is_shining extends singlepage {
		
		// If we set this then a connection to the database will be made
		// this connection is stored as $_database and uses the
		// database class file.
		
		protected $_dbconfig = Array( "host" => "diallerdb.res.clixconnect.net",
							  "user" => "cron",
							  "pass" => "1234",
							  "database" => "asterisk" );
		
		// Values for scoring agents based on hotkeys and packouts.
		protected $_hotkeyPoints = 0.5;
		protected $_packoutPoints = 2;
		
		protected $_allStats = Array();
				
		// Pull a list of all agents that made a sale in the specified date range
		private function getAgentList($dateStart, $dateEnd) {
			$results = $this->_database->prepared_query(10,"SELECT DISTINCT U.user_id, U.user, U.full_name, count(VL.lead_id) AS sales FROM vicidial_list AS VL LEFT JOIN vicidial_users AS U ON VL.user=U.user WHERE modify_date >= '2012-03-27 08:00:00' AND modify_date <= '2012-03-27 21:00:00' AND VL.user <> 'VDAD' AND status IN ('SALE') GROUP BY VL.user;","",Array());
			return $results['data'];
		}
		
		// Get either top or bottom 5 results (ASC / DESC)
		private function getResults($totalStars, $startDate, $endDate, $sort="ASC") {
		
			$allLeads = $this->_database->prepared_query(10, "SELECT VL.lead_id, U.user_id, U.user, U.full_name FROM vicidial_log AS VL LEFT JOIN vicidial_users AS U ON VL.user=U.user WHERE call_date >= '".$startDate."' AND call_date <= '".$endDate."' AND VL.user <> 'VDAD' AND status = 'SALE';", "", Array() );
			
			
			if ( isset($allLeads['data'][0]['lead_id']) ) {

				$getArray = Array();
				foreach ($allLeads['data'] AS $res) {
					$getArray[] = $res['lead_id'];
				}
				
				$resultGET = implode(",",$getArray);
				
				$arrayResult = $this->getDebtsolvDetails($resultGET);
				
				$packOutResults = Array();
				$i = 0;
				foreach ($arrayResult AS $key => $res) {
					if ($res->status == "Lead Completed") {
						$packOutResults[$i]['key'] = $key;
						$packOutResults[$i]['value'] = $res->amount;
						$i++;
					}
				}
				
				
				
				$userStats = Array();
				foreach ($allLeads['data'] as $lead) {
				
					$in_array = FALSE;
					$thisValue= 0;
					foreach ($packOutResults as $pOut) {
						if ($pOut['key'] == $lead['lead_id']) {
							$thisValue = $pOut['value'];
							$in_array = TRUE;
						}
					}
					
				
					if ($in_array) {
						if (isset($userStats[$lead['full_name']])) {
							$userStats[$lead['full_name']]['packouts']++;
							$userStats[$lead['full_name']]['packoutValue'] = $userStats[$lead['full_name']]['packoutValue'] + $thisValue;
						} else {
							$userStats[$lead['full_name']]['packouts'] = 1;
							$userStats[$lead['full_name']]['packoutValue'] = $thisValue;
						}
					
					}
										
					if (isset($userStats[$lead['full_name']])) {
						$userStats[$lead['full_name']]['hotkeys']++;
					} else {
						$userStats[$lead['full_name']]['hotkeys']=1;
					}
					
				}
				
				
				
				
				
				foreach ($userStats as $key => $row) {
					$packouts[$key] = $row['packouts'];
					$packoutValue[$key] = $row['packoutValue'];
					$totalhotkeys[$key] = $row['hotkeys'];
					
					
					$thisScore = 0;
					$ratio = (int)(((int)$row['hotkeys'] / (int)$row['packouts']) * 100);
					
					if ($ratio < 34) {
					   $referralPoints = 50;
				    } else if ($ratio < 51) {
				        $referralPoints = 100;
					} else {
					   $referralPoints = 150;
				    }
					
					$thisScore = $thisScore + ( (int)$row['hotkeys'] * $referralPoints );
					$thisScore = $thisScore + ( $row['packouts'] * 300 );
					//$thisScore = $thisScore + ($row['packoutValue']/100);

					$userStats[$key]['score'] = $thisScore;
					$score[$key] = $thisScore;					
				}
				
				
				
				$this->_allStats = $userStats;
				
				if ($sort == "DESC") {
					array_multisort($score, SORT_DESC);
				} else {
					array_multisort($score, SORT_ASC);
				}
				
				$ordered = Array();
				
				foreach ($score AS $user=>$points)
				{
				    $ordered[$user] = $userStats[$user];
				}
				
				return array_slice( $ordered, 0, $totalStars );
			
			} else {
				return FALSE;
			}
		}
		
		
		
		// Set up our page
		protected function run() {
			
			
			$agentDay = (array)json_decode(file_get_contents("https://portal.res.clixconnect.net/dialler/api/get_telesales_report_period/RESOLVE/day.json"));
			$agentMonth = (array)json_decode(file_get_contents("https://portal.res.clixconnect.net/dialler/api/get_telesales_report_period/RESOLVE/month.json"));
			
			
			
			
			$todayStar = (array)$agentDay['report'][0];
			
			
			$this->addContent("dayStarName", $todayStar['name']);
			$this->addContent("dayStarHotkeys", (int)$todayStar['referrals']);
			$this->addContent("dayStarPackouts", (int)$todayStar['packouts']);
			$this->addContent("dayStarPORatio", number_format($todayStar['conversionrate'],2));
			$this->addContent("dayStarTotals", number_format($todayStar['points'],2));





			$todayStar = (array)$agentMonth['report'][0];
			
			
			$this->addContent("monthStarName", $todayStar['name']);
			$this->addContent("monthStarHotkeys", (int)$todayStar['referrals']);
			$this->addContent("monthStarPackouts", (int)$todayStar['packouts']);
			$this->addContent("monthStarPORatio", number_format($todayStar['conversionrate'],2));
			$this->addContent("monthStarTotals", number_format($todayStar['points'],2));


			
			
			
			
			for ($i = 0; $i <= 4; $i++) {

    			$todayStar = (array)$agentMonth['report'][$i];
    			
    			$this->addContent("topStar".($i+1)."Name", $todayStar['name']);
    			$this->addContent("topStar".($i+1)."TotalHtk", number_format($todayStar['points'],2));
    			
            }
			
			
						
			
			
			
			
		}
		
	}

?>
