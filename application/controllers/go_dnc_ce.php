<?php
########################################################################################################
####  Name:             	go_dnc_ce.php                                                  	    ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Christopher Lomuntad				                    ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_dnc_ce extends Controller {
	var $userLevel;

    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_campaign','go_auth','go_monitoring','golist','go_dashboard','go_reports'));
		$this->load->library(array('session','pagination','commonhelper'));
		$this->load->helper(array('date','form','url','path'));
		$this->is_logged_in();
		$this->lang->load('userauth', $this->session->userdata('ua_language'));

		$this->userLevel = $this->session->userdata('users_level');
		$config['enable_query_strings'] = FALSE;
//		$this->config->initialize($config);
    }

	function index()
	{
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_campaign_body_jsloader.php';

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = 'DNC Numbers';
		$data['adm']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);


		$data['userfulname'] = $this->go_campaign->go_get_userfulname();

// 		$accountNum = $this->go_campaign->go_get_groupid();
// 		$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns($accountNum);
// 		$query = $this->db->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns WHERE campaign_id IN ('$allowed_campaigns')");
// 		$data['list_of_campaigns'] = $query->result();
// 
// 		$query = $this->db->query("SELECT phone_number,vicidial_campaign_dnc.campaign_id,campaign_name FROM vicidial_campaign_dnc,vicidial_campaigns WHERE vicidial_campaign_dnc.campaign_id IN ('$allowed_campaigns') AND vicidial_campaigns.campaign_id=vicidial_campaign_dnc.campaign_id ORDER BY vicidial_campaign_dnc.campaign_id,phone_number");
// 		$data['dnc_list'] = $query->result();

		$data['go_main_content'] = 'go_dnc/go_dnc';
		$this->load->view('includes/go_dashboard_template',$data);
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$base = base_url("../login");
			echo "<script>javascript: window.location = 'https://".$_SERVER['HTTP_HOST']."/login'</script>";
// 			echo "<script>javascript: window.location = '$base'</script>";
			#echo 'You don\'t have permission to access this page. <a href="../go_index">Login</a>';
			die();
			#$this->load->view('go_login_form');
		}
	}

	function go_dnc_numbers()
	{
		$string = $this->go_campaign->go_unserialize($this->uri->segment(3));
		$cnt = 0;

		if ($string['campaign_id'] == "INTERNAL")
		{
			$dnc_numbers = explode("\r\n",$string['phone_numbers']);

			foreach ($dnc_numbers as $dnc)
			{
				$query = $this->db->query("SELECT phone_number AS cnt FROM vicidial_dnc WHERE phone_number='$dnc'");
				$idnc_exist = $query->num_rows();
				
				$query = $this->db->query("SELECT phone_number AS cnt FROM vicidial_campaign_dnc WHERE phone_number='$dnc'");
				$cdnc_exist = $query->num_rows();

				if ($idnc_exist < 1 && $cdnc_exist < 1)
				{
					if ($string['stage'] == "add" && $dnc != '')
					{
// 							var_dump("INSERT INTO vicidial_campaign_dnc VALUES('$dnc','".$camp->campaign_id."')");
						$query = $this->db->query("INSERT INTO vicidial_dnc VALUES('$dnc')");
						$this->commonhelper->auditadmin('ADD',"Added DNC Number $dnc to Internal DNC List","INSERT INTO vicidial_dnc VALUES('$dnc')");
						$cnt++;
					}
				} else {
					if ($string['stage'] == "delete" && $dnc != '')
					{
// 							var_dump("DELETE FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$camp->campaign_id."'");
						$query = $this->db->query("DELETE FROM vicidial_dnc WHERE phone_number='$dnc'");
						$this->commonhelper->auditadmin('DELETE',"Deleted DNC Number $dnc from Internal DNC List","DELETE FROM vicidial_dnc WHERE phone_number='$dnc'");
						$cnt++;
					}
				}
			}

			if ($cnt)
			{
				if ($string['stage'] == "add")
					$msg = "added";
				else
					$msg = "deleted";
			} else {
				if ($string['stage'] == "add")
					$msg = "already exist";
				else
					$msg = "does not exist";
			}
		} else {
			$dnc_numbers = explode("\r\n",$string['phone_numbers']);

			foreach ($dnc_numbers as $dnc)
			{
				$query = $this->db->query("SELECT phone_number AS cnt FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$string['campaign_id']."'");
				$cdnc_exist = $query->num_rows();
				
				$query = $this->db->query("SELECT phone_number AS cnt FROM vicidial_dnc WHERE phone_number='$dnc'");
				$idnc_exist = $query->num_rows();

				if ($idnc_exist < 1 && $cdnc_exist < 1)
				{
					if ($string['stage'] == "add")
					{
// 						var_dump("INSERT INTO vicidial_campaign_dnc VALUES('$dnc','".$string['campaign_id']."')");
						$query = $this->db->query("INSERT INTO vicidial_campaign_dnc VALUES('$dnc','".$string['campaign_id']."')");
						$this->commonhelper->auditadmin('ADD',"Added DNC Number $dnc for Campaign ".$string['campaign_id'],"INSERT INTO vicidial_campaign_dnc VALUES('$dnc','".$string['campaign_id']."')");
						$cnt++;
					}
				} else {
					if ($string['stage'] == "delete")
					{
// 						var_dump("DELETE FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$string['campaign_id']."'");
						$query = $this->db->query("DELETE FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$string['campaign_id']."'");
						$this->commonhelper->auditadmin('DELETE',"Deleted DNC Number $dnc from Campaign ".$string['campaign_id'],"DELETE FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$string['campaign_id']."'");
						$cnt++;
					}
				}
			}

			if ($cnt)
			{
				if ($string['stage'] == "add")
					$msg = "added";
				else
					$msg = "deleted";
			} else {
				if ($string['stage'] == "add")
					$msg = "already exist";
				else
					$msg = "does not exist";
			}
		}

		echo $msg;
	}

	function go_get_dnc_numbers()
	{
		$query = $this->db->query("SELECT phone_number FROM vicidial_dnc ORDER BY phone_number");
		$dnc_int = $query->result();

		$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns();
		if ($allowed_campaigns!="ALLCAMPAIGNS")
		{
			$filter_camp_SQL = "vicidial_campaign_dnc.campaign_id IN ('$allowed_campaigns') AND";
		}
		
		$query = $this->db->query("SELECT phone_number,vicidial_campaign_dnc.campaign_id,vicidial_campaigns.campaign_name FROM vicidial_campaign_dnc,vicidial_campaigns WHERE $filter_camp_SQL vicidial_campaigns.campaign_id=vicidial_campaign_dnc.campaign_id ORDER BY phone_number");
		$dnc_camp = $query->result();

		$data['dnc_list'] = array_merge($dnc_int, $dnc_camp);
		$this->load->view('go_dnc/go_dnc_list',$data);
	}

	function go_search_dnc()
	{
		$number = $this->uri->segment(3);
		
		$query = $this->db->query("SELECT phone_number FROM vicidial_dnc WHERE phone_number LIKE '$number%' ORDER BY phone_number");
		$dnc_int = $query->result();

		$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns();
		if ($allowed_campaigns!="ALLCAMPAIGNS")
		{
			$filter_camp_SQL = "vicidial_campaign_dnc.campaign_id IN ('$allowed_campaigns') AND";
		}

		$query = $this->db->query("SELECT phone_number,vicidial_campaign_dnc.campaign_id,campaign_name FROM vicidial_campaign_dnc,vicidial_campaigns WHERE $filter_camp_SQL vicidial_campaigns.campaign_id=vicidial_campaign_dnc.campaign_id AND phone_number LIKE '$number%' ORDER BY phone_number");
		$dnc_camp = $query->result();

		$data['dnc_list'] = array_merge($dnc_int, $dnc_camp);
		$this->load->view('go_dnc/go_dnc_list',$data);
	}

	function go_submit_dnc()
	{
		$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns();
		if ($allowed_campaigns!="ALLCAMPAIGNS")
		{
			$filter_camp_SQL = "WHERE campaign_id IN ('$allowed_campaigns')";
		}
		
		$query = $this->db->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns $filter_camp_SQL");
		$data['list_of_campaigns'] = $query->result();

		$this->load->view('go_dnc/go_dnc_wizard',$data);
	}

	function go_delete_dnc_number()
	{
		list($number, $camp) = explode('-',$this->uri->segment(3));
		if (strlen($camp)>0) {
			$stmt = "DELETE FROM vicidial_campaign_dnc WHERE phone_number='$number' AND campaign_id='$camp'";
			$query = $this->db->query($stmt);
		}
		else {
			$stmt = "DELETE FROM vicidial_dnc WHERE phone_number='$number'";
			$query = $this->db->query($stmt);
		}

		$return = $this->db->affected_rows();
        $this->commonhelper->auditadmin("DELETE","DELETE DNC $number",$stmt);

		echo $return;
	}

	function go_delete_mass_dnc_number()
	{
		$array = explode(',',$this->uri->segment(3));

		foreach ($array as $segment)
		{
			list($number, $camp) = explode('-',$segment);
			$query = $this->db->query("DELETE FROM vicidial_campaign_dnc WHERE phone_number='$number' AND campaign_id='$camp'");
			$query = $this->db->query("DELETE FROM vicidial_dnc WHERE phone_number='$number'");
                        $this->commonhelper->auditadmin("DELETE","DELETE DNC $number","DELETE FROM vicidial_campaign_dnc WHERE phone_number='$number' AND campaign_id='$camp'; DELETE FROM vicidial_dnc WHERE phone_number='$number'");
			$return = $this->db->affected_rows();
		}

		echo $return;
	}

}