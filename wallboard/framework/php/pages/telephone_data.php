<?php

	// Dialler Server

	class telephone_data extends singlepage {
		
		// If we set this then a connection to the database will be made
		// this connection is stored as $_database and uses the
		// database class file.
		protected $_dbconfig = Array( "host" => "10.150.5.241",
							  "user" => "cron",
							  "pass" => "1234",
							  "database" => "asterisk" );
		
		protected $_campaignID = "BURTON1";
		
		
		private function getCampaignStats() {
			
			$results = $this->_database->prepared_query(FALSE, "SELECT * FROM  `vicidial_campaign_stats` WHERE campaign_id=?;", "s", Array($this->_campaignID) );
			
			$this->addContent("droppedCalls", $results[0]['drops_today']);
			$this->addContent("droppedCallPercentage", $results[0]['drops_answers_today_pct']);
			$this->addContent("callsMade", number_format((int)$results[0]['calls_today'],0));
			$this->addContent("waitTime", number_format(($results[0]['agent_wait_today'] / $results[0]['agent_calls_today']),1));
			
			
			$results = $this->_database->prepared_query(FALSE, "SELECT * FROM  `vicidial_campaigns` WHERE campaign_id=?;", "s", Array($this->_campaignID) );
			$this->addContent("dialLevel", number_format($results[0]['auto_dial_level'],2));
			
		}
		
		private function getLoggedInAgents() {
			$results = $this->_database->prepared_query(FALSE, "SELECT count(*) as count FROM  `vicidial_live_agents`;","",Array());
			return $results[0]['count'];
		}
		
		private function getActiveAgents() {
			$results = $this->_database->prepared_query(FALSE, "SELECT count(*) as count FROM  `vicidial_live_agents` WHERE status = 'INCALL';","",Array());
			return $results[0]['count'];
		}
		
		private function agentsInDisposition() {
			$results = $this->_database->prepared_query(FALSE, "SELECT count(*) as count FROM  `vicidial_live_agents` WHERE status = 'PAUSED' AND lead_id <> 0;","",Array());
			return $results[0]['count'];
		}
		
		private function agentsWaiting() {
			$results = $this->_database->prepared_query(FALSE, "SELECT count(*) as count FROM  `vicidial_live_agents` WHERE status = 'READY';","",Array());
			return $results[0]['count'];
		}
		
		private function callsPlaced() {
			$results = $this->_database->prepared_query(FALSE, "SELECT count(*) as count FROM  `live_channels` WHERE channel_data = 'SIP/ring';","",Array());
			return $results[0]['count'];
		}
		
		private function callsProgress() {
			$results = $this->_database->prepared_query(FALSE, "SELECT count(*) as count FROM  `live_channels` WHERE channel_data LIKE '%|F';","",Array());
			return $results[0]['count'];
		}
		
		

		// Set up our page
		protected function run() {
			$this->getCampaignStats();
			
			
			$this->addContent("callsPerPerson", number_format(((int)$this->getActiveAgents() / (int)$this->getLoggedInAgents()),2) );
			
			$this->addContent("agentsInCalls", $this->getActiveAgents());
			$this->addContent("agentsWaiting", $this->agentsWaiting());
			$this->addContent("agentsInDisposition", $this->agentsInDisposition());
			$this->addContent("callsInProgress", $this->callsProgress());
			$this->addContent("callsRinging", $this->callsPlaced());
			
		}
		
		
	}

?>