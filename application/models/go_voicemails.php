<?php
####################################################################################################
####  Name:             	go_voicemails.php                                                   ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Originated by:        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_voicemails extends Model {

	function __construct()
	{
	    parent::Model();
	    $this->db = $this->load->database('dialerdb', true);
	    //$this->a2db = $this->load->database('billingdb', true);
	}

	function go_get_voicemail_list()
	{
		$query = $this->db->query("SELECT voicemail_id,fullname,active,messages,old_messages,delete_vm_after_email FROM vicidial_voicemail ORDER BY voicemail_id;");
		$return = $query->result();

		return $return;
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
	
	function go_get_systemsettings()
	{
		$query = $this->db->query("SELECT use_non_latin,enable_queuemetrics_logging,enable_vtiger_integration,qc_features_active,outbound_autodial_active,sounds_central_control_active,enable_second_webform,user_territories_active,custom_fields_enabled,admin_web_directory,webphone_url,first_login_trigger,hosted_settings,default_phone_registration_password,default_phone_login_password,default_server_password,test_campaign_calls,active_voicemail_server,voicemail_timezones,default_voicemail_timezone,default_local_gmt,campaign_cid_areacodes_enabled,pllb_grouping_limit,did_ra_extensions_enabled,expanded_list_stats,contacts_enabled,alt_log_server_ip,alt_log_dbname,alt_log_login,alt_log_pass,tables_use_alt_log_db FROM system_settings");
		$settings = $query->row();
		return $settings;
	}

}
