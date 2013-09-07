<?php
####################################################################################################
####  Name:             	go_calltimes.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1373515200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_calltimes extends Model {

	function __construct()
	{
	    parent::Model();
	    $this->db = $this->load->database('dialerdb', true);
	}

	function go_get_calltimes_list($type=null)
	{
		if (strlen($type) < 1)
			$query = $this->db->query("SELECT call_time_id,call_time_name,ct_default_start,ct_default_stop,user_group FROM vicidial_call_times ORDER BY call_time_id;");
		else
			$query = $this->db->query("SELECT state_call_time_id,state_call_time_state,state_call_time_name,sct_default_start,sct_default_stop,user_group FROM vicidial_state_call_times ORDER BY state_call_time_id;");

		$return = $query->result();

		return $return;
	}
	
	function go_get_calltimes_info($cid,$type=null)
	{
		if (strlen($type) < 1)
			$query = $this->db->query("SELECT * FROM vicidial_call_times WHERE call_time_id='$cid';");
		else
			$query = $this->db->query("SELECT * FROM vicidial_state_call_times WHERE state_call_time_id='$cid';");

		$return = $query->row();
		return $return;
	}
	
	function go_add_state_rule($cid,$rule)
	{
		$query = $this->db->query("SELECT ct_state_call_times FROM vicidial_call_times WHERE call_time_id='$cid'");
		$new_rule = $query->row()->ct_state_call_times . "$rule|";
		$query = $this->db->query("UPDATE vicidial_call_times SET ct_state_call_times='$new_rule' WHERE call_time_id='$cid'");
		
		$result = "SUCCESS|UPDATE vicidial_call_times SET ct_state_call_times='$new_rule' WHERE call_time_id='$cid'";
		return $result;
	}
	
	function go_delete_state_rule($cid,$rule)
	{
		$query = $this->db->query("SELECT ct_state_call_times FROM vicidial_call_times WHERE call_time_id='$cid'");
		$new_rule = str_replace("||","|",str_replace($rule,"",$query->row()->ct_state_call_times));
		if ($new_rule=="|")
			$new_rule = "";
		$query = $this->db->query("UPDATE vicidial_call_times SET ct_state_call_times='$new_rule' WHERE call_time_id='$cid'");
		
		$result = "SUCCESS|UPDATE vicidial_call_times SET ct_state_call_times='$new_rule' WHERE call_time_id='$cid'";
		return $result;
	}
	
	function go_delete_calltimes($cid,$type)
	{
		if (strlen($type) < 1)
		{
			$query = $this->db->query("SELECT * FROM vicidial_call_times WHERE call_time_id='$cid'");
			if ($query->num_rows())
			{
				$query = $this->db->query("DELETE FROM vicidial_call_times WHERE call_time_id='$cid'");
				$result = "SUCCESS|DELETE FROM vicidial_call_times WHERE call_time_id='$cid'";
			} else {
				$result = "FAILED";
			}
		} else {
			$query = $this->db->query("SELECT * FROM vicidial_state_call_times WHERE state_call_time_id='$cid'");
			if ($query->num_rows())
			{
				$query = $this->db->query("DELETE FROM vicidial_state_call_times WHERE state_call_time_id='$cid'");
				$result = "SUCCESS|DELETE FROM vicidial_state_call_times WHERE state_call_time_id='$cid'";
			} else {
				$result = "FAILED";
			}
		}
		return $result;
	}
	
	function go_get_list_using_this($cid,$type=null)
	{
		if (strlen($type) < 1)
		{
			$query = $this->db->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns WHERE local_call_time='$cid' ORDER BY campaign_id");
			$result['camp'] = $query->result();
			
			$query = $this->db->query("SELECT group_id,group_name FROM vicidial_inbound_groups WHERE call_time_id='$cid' ORDER BY group_id");
			$result['inb'] = $query->result();
		} else {
			$query = $this->db->query("SELECT call_time_id,call_time_name FROM vicidial_call_times WHERE ct_state_call_times rlike '\\\|$cid\\\|' ORDER BY call_time_id");
			$result['list'] = $query->result();
		}
		
		return $result;
	}

	function go_get_groupid()
	{
		$userID = $this->session->userdata('user_name');
	    $query = $this->db->query("select user_group from vicidial_users where user='$userID'");
	    $resultsu = $query->row();
	    $groupid = $resultsu->user_group;
	    return $groupid;
	}

	function go_get_userfulname()
	{
		$userID = $this->session->userdata('user_name');
		$query = $this->db->query("select full_name from vicidial_users where user='$userID';");
		$resultsu = $query->row();
		$userfulname = $resultsu->full_name;
		return $userfulname;
	}
	
	function go_list_server_ips()
	{
		$query = $this->db->query("SELECT server_ip,server_description FROM servers WHERE active='Y';");
		$return = $query->result();
		return $return;
	}

	function go_get_usergroups()
	{
	    $query = $this->db->query("select user_group,group_name from vicidial_user_groups");
	    $groups = $query->result();
	    return $groups;
	}
	
	function go_get_systemsettings()
	{
		$query = $this->db->query("SELECT use_non_latin,enable_queuemetrics_logging,enable_vtiger_integration,qc_features_active,outbound_autodial_active,sounds_central_control_active,enable_second_webform,user_territories_active,custom_fields_enabled,admin_web_directory,webphone_url,first_login_trigger,hosted_settings,default_phone_registration_password,default_phone_login_password,default_server_password,test_campaign_calls,active_voicemail_server,voicemail_timezones,default_voicemail_timezone,default_local_gmt,campaign_cid_areacodes_enabled,pllb_grouping_limit,did_ra_extensions_enabled,expanded_list_stats,contacts_enabled,alt_log_server_ip,alt_log_dbname,alt_log_login,alt_log_pass,tables_use_alt_log_db FROM system_settings");
		$settings = $query->row();
		return $settings;
	}

}
