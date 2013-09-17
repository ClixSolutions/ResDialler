<?php

	class three_circles extends singlepage {
		
		// Set up our page
		protected function run() {

			$allstats = (array)json_decode(file_get_contents("https://intranet.gregsonandbrooke.co.uk/dialler/api/mobile_wallboard.json"));
	
			$this->addContent("_GBSReferral", $allstats['PCC']->referrals);
			$this->addContent("_GBSPackOuts", $allstats['PCC']->pack_out);
			$this->addContent("_GBSTotalValue", $allstats['PCC']->pack_out_value);
			$this->addContent("_GBSAverageDI", $allstats['PCC']->di);
			
			$this->addContent("_HQReferral", $allstats['HQ']->referrals);
			$this->addContent("_HQPackOuts", $allstats['HQ']->pack_out);
			$this->addContent("_HQTotalValue", $allstats['HQ']->pack_out_value);
			$this->addContent("_HQAverageDI", $allstats['HQ']->di);
			
			$this->addContent("_RESOLVEReferral", $allstats['RESOLVE']->referrals);
			$this->addContent("_RESOLVEPackOuts", $allstats['RESOLVE']->pack_out);
			$this->addContent("_RESOLVETotalValue", $allstats['RESOLVE']->pack_out_value);
			$this->addContent("_RESOLVEAverageDI", $allstats['RESOLVE']->di);
			
		}
		
	}

?>