<?php
########################################################################################################
####  Name:             	go_systemsettings_ce.php                                            ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Franco Hora				            	    	    ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_systemSettings_ce extends Controller{
     function __construct(){
 
         parent::Controller();
         $this->load->model('go_systemsettings');
         $this->load->library(array('commonhelper'));
         $this->load->helper(array('html','form'));
		 $this->userLevel = $this->session->userdata('users_level');

     }


     function index(){
		if ($this->userLevel < 9) { die('Error: You do not have permission to view this page.'); }
        $data['userfulname'] = $this->session->userdata('full_name');
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';
	$data['theme'] = $this->session->userdata('go_theme');
	$data['bannertitle'] = 'System Settings';
	//$data['adm']= 'wp-has-current-submenu';
	$data['hostp'] = $_SERVER['SERVER_ADDR'];
	$data['folded'] = 'folded';
	$data['foldlink'] = '';
	$togglestatus = "1";
	$data['togglestatus'] = $togglestatus;
        $this->go_systemsettings->getsettings();
        $settings = $this->go_systemsettings->query_result();
        $data['settings'] = $settings->result();
        $this->go_systemsettings->getservers();
        $servers = $this->go_systemsettings->query_result();
        if($servers->num_rows() > 0){
            foreach($servers->result() as $server){
                $data['servers'][$server->server_ip] = "{$server->server_ip} - {$server->server_description}";
            }
        }

        $adl=1;
        while($adl < 1000)
        {
        $data['dialLimit']["$adl"] = $adl;
        if ($adl < 3)
        {$adl = ($adl + 0.1);}
        else
        {
            if ($adl < 4)
            {$adl = ($adl + 0.25);}
            else
            {
                 if ($adl < 5)
                 {$adl = ($adl + 0.5);}
                 else
                 {
                      if ($adl < 10)
                      {$adl = ($adl + 1);}
                      else
                      {
                           if ($adl < 20)
                           {$adl = ($adl + 2);}
                           else
                           {
                               if ($adl < 40)
                               {$adl = ($adl + 5);}
                               else
                               {
                                   if ($adl < 200)
                                   {$adl = ($adl + 10);}
                                   else
                                   {
                                        if ($adl < 400)
                                        {$adl = ($adl + 50);}
                                        else
                                        {
                                             if ($adl < 1000)
                                             {$adl = ($adl + 100);}
                                             else
                                             {$adl = ($adl + 1);}
                                        }
                                   }
                               }
                           }
                      }
                 } 
            }
        }
        if($adl == 1000){
          $data['dialLimit']["$adl"] = $adl;
        }
        }
        $Vreports = 'NONE, Real-Time Main Report, Real-Time Campaign Summary, Inbound Report, Inbound Service Level Report, Inbound Summary Hourly Report, Inbound DID Report, Inbound IVR Report, Outbound Calling Report, Outbound Summary Interval Report, Fronter - Closer Report, Lists Campaign Statuses Report, Export Calls Report, Agent Time Detail, Agent Status Detail, Agent Performance Detail, Single Agent Daily, User Timeclock Report, User Group Timeclock Status Report, User Timeclock Detail Report, Server Performance Report, Administration Change Log, List Update Stats, User Stats, User Time Sheet, Download List';
        $Vreports = explode(', ',$Vreports);
        foreach($Vreports as $reports){
            $slavedb[$reports] = $reports;
        }
        $data['slavedb'] = $slavedb;
        $data['go_main_content'] = 'go_systemsettings/index';
        $data['sys']= 'wp-has-current-submenu';

        $this->load->view('includes/go_dashboard_template',$data); 
     }


     function updatesettings(){
          if(!empty($_POST)){
              $_POST['reports_use_slave_db'] = implode(',',$_POST['reports_use_slave_db']);
              $reload_dialplan_on_servers = $_POST['reload_dialplan_on_servers'];
              unset($_POST['reload_dialplan_on_servers']);
              $this->go_systemsettings->asteriskDB->update('system_settings',$_POST);
              $this->commonhelper->auditadmin("MODIFY","Modify system settings",$_POST);
              if($reload_dialplan_on_servers == 1){
                  $this->go_systemsettings->asteriskDB->where(array('active'=>'Y','active_asterisk_server'=>'Y','generate_vicidial_conf'=>'Y'));
                  $this->go_systemsettings->asteriskDB->update('servers',array('rebuild_conf_files'=>'Y'));
              }
              echo "Success: Update successful!";
          }
     }
}