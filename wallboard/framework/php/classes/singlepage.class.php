<?php

	class singlepage {
		
		private $_view = "";
		private $_content = Array();
		protected $_database;
		protected $_debtsolvServer = "api.gregsonandbrooke.co.uk/json.php";
		
		// Returns a json array of the page content and the view.
		public function getContent() {
			return json_encode(Array("view" => $this->_view, "content" => $this->_content));
		}
		
		// This function will replace the content in a given $divID
		// and replace it with the $replacement
		protected function addContent($divID, $replacement) {
			$this->_content[$divID] = $replacement;
		}
		
		// Loads the view and stores it in the class private variable
		protected function loadView($file=FALSE) {
			if (!$file) $file = get_class($this);
			return file_get_contents("views/".$file.".html");
		}
		
		//
		protected function getDebtsolvDetails($resultGET) {
			
		
			$ch = curl_init("http://".$this->_debtsolvServer."?referrals=".$resultGET."&center=RESOLVE");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$jsonReturn=curl_exec($ch);
			curl_close($ch);
			
			return json_decode($jsonReturn);
		}
		
		// This function is ran on creating the class
		protected function run() {
			
		}
		
		// Class construct, load and get the view
		public function __construct($file=FALSE) {
			$this->_view = $this->loadView($file);
			if (is_array($this->_dbconfig)) {
				$this->_database = database::connect($this->_dbconfig);
			}
			if (is_array($this->_msdbconfig)) {
				//$this->_msdatabase = msdatabase::connect($this->_msdbconfig);
			}
			$this->run();
		}
		
	}

?>