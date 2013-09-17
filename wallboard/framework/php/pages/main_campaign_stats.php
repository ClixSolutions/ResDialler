<?php
	// Dialler Server
	
	class main_campaign_stats extends singlepage {
		
		// If we set this then a connection to the database will be made
		// this connection is stored as $_database and uses the
		// database class file.
		protected $_dbconfig = Array( "host" => "10.150.5.241",
									  "user" => "cron",
									  "pass" => "1234",
									  "database" => "asterisk" );
		
				
		private function salesToday() {
			$results = $this->_database->prepared_query(FALSE, "SELECT vicidial_users.full_name, VL.lead_id,VL.modify_date,TIMEDIFF(NOW(),VL.modify_date) AS diff_list,VL.user,VL.status,VS.status_name AS vssn ,VCS.status_name AS vcssn,VLS.list_name,VLS.list_description,VLS.list_lastcalldate,TIMEDIFF(NOW(),VLS.list_lastcalldate) AS diff_call FROM vicidial_lists AS VLS, vicidial_list AS VL LEFT JOIN vicidial_campaign_statuses VCS USING (status) LEFT JOIN vicidial_statuses VS USING (status) LEFT JOIN vicidial_users ON VL.user=vicidial_users.user WHERE VL.list_id=VLS.list_id AND VL.modify_date >= CONCAT(CURDATE(),' 07:30') AND VL.user <> 'VDAD' AND VL.status IN ('SALE') AND (VLS.campaign_id=VCS.campaign_id OR VL.status=VS.status) ORDER BY diff_list, VL.user, VL.modify_date DESC;", "", Array() );
			
			$referrals = count($results);
			
			$this->addContent("salesToday", $referrals);
			
			$startTime = strtotime("+ 9 hours", strtotime("Today"));
			$timeNow = strtotime("Now");
			
			$timeDif = $timeNow - $startTime;
			
			$timeDifHour = (($timeDif/60) / 60);
			
			$refPerHour = $referrals / $timeDifHour;
			
			$this->addContent("salesPerHour", "(".number_format($refPerHour,2)."/hour)");
			
			$staff = Array();
			foreach ($results AS $result) {
				if (isset($staff[$result['full_name']])) {
					$staff[$result['full_name']]++;
				} else {
					$staff[$result['full_name']] = 1;
				}
			}
			
			arsort($staff);
			
			$i = 0;
			foreach ($staff as $name => $number) {
				$i++;
				$this->addContent("referrer".$i."Name", $name);
				$this->addContent("referrer".$i."Count", $number);
			}
			
		}
		
		private function topReferers() {
			
		}

		// Set up our page
		protected function run() {
			$this->salesToday();
		}
		
	}

?>