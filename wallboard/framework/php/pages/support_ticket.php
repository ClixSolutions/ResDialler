<?php

	class support_ticket extends singlepage {
		
		// If we set this then a connection to the database will be made
		// this connection is stored as $_database and uses the
		// database class file.
		protected $_dbconfig = Array( "host" => "localhost",
									  "user" => "root",
									  "pass" => "root",
									  "database" => "db336374141" );
		
		
		private function getOverdueTicketCount() {
			$results = $this->_database->prepared_query(FALSE, 'SELECT count(ticket_id) AS count FROM ost_ticket WHERE dept_id=? AND isoverdue=? AND status=?;', "iis", Array(1, 1, "open") );
			return $results[0]['count'];
		}
		
		private function getOpenTicketCount() {
			$results = $this->_database->prepared_query(FALSE, 'SELECT count(ticket_id) AS count FROM ost_ticket WHERE dept_id=? AND status=?;', "is", Array(1, "open") );
			return $results[0]['count'];
		}
		
		private function getUnansweredTicketCount() {
			$results = $this->_database->prepared_query(FALSE, 'SELECT count(ticket_id) AS count FROM ost_ticket WHERE dept_id=? AND isanswered=? AND status=?;', "iis", Array(1, 0,"open") );
			return $results[0]['count'];
		}

		// Set up our page
		protected function run() {
			$this->addContent("overdueTickets", $this->getOverdueTicketCount());
			$this->addContent("unansweredTickets", $this->getUnansweredTicketCount());
			$this->addContent("openTickets", $this->getOpenTicketCount());
		}
		
	}

?>