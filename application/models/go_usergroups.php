<?php
########################################################################################################
####  Name:             	go_usergroups.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_usergroups extends Model {

	function __construct()
	{
	    parent::Model();
	    $this->db = $this->load->database('dialerdb', true);
	    //$this->a2db = $this->load->database('billingdb', true);
	}

	function go_get_usergroup_list()
	{
		$query = $this->db->query("SELECT user_group,group_name,forced_timeclock_login FROM vicidial_user_groups ORDER BY user_group;");
		$return = $query->result();

		return $return;
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
	
	function go_list_templates()
	{
		$query = $this->db->query("SELECT template_id as id,template_name as name from vicidial_conf_templates order by template_id;");
		$return = $query->result();
		return $return;
	}

	function go_get_usergroups()
	{
	    $query = $this->db->query("select user_group,group_name from vicidial_user_groups");
	    $groups = $query->result();
	    return $groups;
	}
	
	function go_get_campaigns()
	{
		$query = $this->db->query("select campaign_id,campaign_name from vicidial_campaigns order by campaign_id");
		$campaigns = $query->result();
		return $campaigns;
	}
	
	function go_get_shifts()
	{
		$query = $this->db->query("select shift_id,shift_name from vicidial_shifts order by shift_id");
		$shifts = $query->result();
		return $shifts;
	}
	
	function go_get_calltimes()
	{
		$query = $this->db->query("select call_time_id,call_time_name from vicidial_call_times order by call_time_id");
		$calltimes = $query->result();
		return $calltimes;
	}
	
	function go_get_systemsettings()
	{
		$query = $this->db->query("SELECT use_non_latin,enable_queuemetrics_logging,enable_vtiger_integration,qc_features_active,outbound_autodial_active,sounds_central_control_active,enable_second_webform,user_territories_active,custom_fields_enabled,admin_web_directory,webphone_url,first_login_trigger,hosted_settings,default_phone_registration_password,default_phone_login_password,default_server_password,test_campaign_calls,active_voicemail_server,voicemail_timezones,default_voicemail_timezone,default_local_gmt,campaign_cid_areacodes_enabled,pllb_grouping_limit,did_ra_extensions_enabled,expanded_list_stats,contacts_enabled,alt_log_server_ip,alt_log_dbname,alt_log_login,alt_log_pass,tables_use_alt_log_db FROM system_settings");
		$settings = $query->row();
		return $settings;
	}

}
