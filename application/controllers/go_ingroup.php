<?php
########################################################################################################
####  Name:             	go_ingroup.php                                                      ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Jerico James Milo				            	    ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_ingroup extends Controller{
    function __construct(){
        parent::Controller();
        $this->load->model('goingroup');
        $this->load->library(array('session','userhelper','commonhelper'));
        //$this->load->library('commonhelper');
        
    }

    function index() {

        /* load models */
        $this->asteriskDB = $this->load->database('dialerdb',TRUE);
        //$this->a2billingDB =$this->load->database('billingdb',TRUE) ;
        
        $this->load->model('goingroup');
//        $this->load->model('go_reports');
        $this->load->model('go_dashboard');

        $groupId = $this->go_dashboard->go_get_groupid();

            if ($groupId == 'ADMIN' || $groupId == 'admin') {
               $ul='';
            } else {
               $allowedcampaign = $this->go_dashboard->go_getall_allowed_campaigns();
               $ul = " WHERE campaign_id IN ('$allowedcampaign') ";
            }
				
                $callfunc = $this->go_dashboard->go_get_userfulname();
                $data['userfulname'] = $callfunc;


  //      $callfunc = $this->go_reports->go_get_userfulname();
  //    $data['userfulname'] = $callfunc;
        $data['gouser'] = $this->session->userdata('user_name');
        $data['gopass'] = $this->session->userdata('user_pass');
        $data['theme'] = $this->session->userdata('go_theme');
        $data['adm']= 'wp-has-current-submenu';
        $data['hostp'] = $_SERVER['SERVER_ADDR'];
        $data['foldlink'] = '';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';
        $data['bannertitle'] = 'Ingroup Listings';
        $data['folded'] = 'folded';
        $data['go_main_content'] = 'go_ingroup/go_ingroup'; 
        $data['users'] = $users;
        $data['usergroups'] = $usergroups;
        $data['account'] = $account;
        $data['gowizard'] = "gowizard";

        $val = "";

		  /* for multi tenant  $RLIKEaccnt = "OR list_name RLIKE '$PHP_AUTH_USER'"; */
        /* get functions */
        $userlevel = $this->goingroup->getuserlevel($data['gouser']);
        //$accounts = $this->goingroup->getaccounts($data['gouser']); // get account number
        $ingrouplists = $this->goingroup->getallingroup($accounts, $userlevel);
        $scriptlists = $this->goingroup->scriptlists($userlevel,$accounts);
        $ingrouppulldown = $this->goingroup->ingrouppulldown($userlevel,$accounts);
        $calltimespulldown = $this->goingroup->calltimespulldown($userlevel,$accounts);
        $callmenupulldown = $this->goingroup->getcallmenu($userlevel,$accounts);
        $phonespulldown = $this->goingroup->getphonelist();
        $groupaliaspulldown = $this->goingroup->groupalias();
        $campaigns = $this->goingroup->getcampaign($ul);
        $getdids = $this->goingroup->getdids($userlevel,$accounts);
        $getservers = $this->goingroup->getservers($userlevel);
        $getfpgroups = $this->goingroup->getfpgroups();
        $getallcallmenus = $this->goingroup->getallcallmenus($userlevel,$accounts);
        
        
        
		 /* if ($LOGuser_level < 9) {
			$addedSQL = "and uniqueid_status_prefix='$PHP_AUTH_USER'";
			$addedMenuSQL = "where menu_name LIKE '$PHP_AUTH_USER %'";
			$addedNOTE = " <small>(do not remove your account code)</small>";
			$addedValue = "$PHP_AUTH_USER - ";
			}*/
        

	     /* autocreate */ 
			$stage = $this->input->get('stage');
			$accnt = $this->input->get('accnt');
	
 	     if($stage=='addLIST') {
       		$genlist = $this->goingroup->autogenlist($accnt);
       		$data['allmine'] = $genlist;
       		$this->load->view('go_list/go_db_query',$data);
	     }
	     /* end auto create */

   	  /* insert post values */ 
	     $addSUBMIT = $this->input->post('addSUBMIT');
	     $addCALLMENU = $this->input->post('addCALLMENU');
	     $addDID = $this->input->post('addDID');
	     $editDID = $this->input->post('editDID');
	     $list_id = $this->input->post('list_id');
	     $list_name = $this->input->post('list_name');
	     $list_description = $this->input->post('list_description');
	     $active = $this->input->post('active');
	     $campaign_ids = $this->input->post('campaign_ids');
	     
   	     $group_id = $this->input->post('group_id');
	     $group_name = $this->input->post('group_name');
	     $group_color = $this->input->post('group_color');
	     $active = $this->input->post('active');
	     $web_form_address = $this->input->post('web_form_address');	     
	     $voicemail_ext = $this->input->post('voicemail_ext');
	     $next_agent_call = $this->input->post('next_agent_call');
	     $fronter_display = $this->input->post('fronter_display');
	     $script_id = $this->input->post('script_id');
	     $get_call_launch = $this->input->post('get_call_launch');
	     
	     //DID
	     $did_description = $this->input->post('did_description');
	     $did_pattern = $this->input->post('did_pattern');
	     $did_route = $this->input->post('did_route');
	     switch($did_route)
	     {
		case "AGENT":
		    $diddata['user'] = $this->input->post('user');
		    $diddata['user_unavailable_action'] = $this->input->post('user_unavailable_action');
		    $diddata['user_route_settings_ingroup'] = $this->input->post('user_route_settings_ingroup');
		    $db_column = ",user,user_unavailable_action,user_route_settings_ingroup";
		    break;
		
		case "EXTEN":
		    $diddata['extension'] = $this->input->post('extension');
		    $diddata['exten_context'] = $this->input->post('exten_context');
		    $db_column = ",extension,exten_context";
		    break;
		
		case "VOICEMAIL":
		    $diddata['voicemail_ext'] = $this->input->post('voicemail_ext');
		    $db_column = ",voicemail_ext";
		    break;
		
		case "PHONE":
		    $diddata['phone'] = $this->input->post('phone');
		    $diddata['server_ip'] = $this->input->post('server_ip');
		    $db_column = ",phone,server_ip";
		    break;
		
		case "IN_GROUP":
		    $diddata['group_id'] = $this->input->post('group_id');
		    $diddata['call_handle_method'] = (strlen($this->input->post('call_handle_method')) > 0) ? $this->input->post('call_handle_method') : "CIDLOOKUP";
		    $diddata['agent_search_method'] = (strlen($this->input->post('agent_search_method')) > 0) ? $this->input->post('agent_search_method') : "LB";
		    $diddata['list_id'] = $this->input->post('list_id');
		    $diddata['campaign_id'] = $this->input->post('campaign_id');
		    $diddata['phone_code'] = (strlen($this->input->post('phone_code')) > 0) ? $this->input->post('phone_code') : "1";
		    $db_column = ",group_id,call_handle_method,agent_search_method,list_id,campaign_id,phone_code";
		    break;
		
		case "CALLMENU":
		    $diddata['menu_id'] = $this->input->post('menu_id');
		    $db_column = ",menu_id";
		    break;
	     }
	     
	     //CALLMENU
	     $menu_id = $this->input->post('menu_id');
	     $menu_name = $this->input->post('menu_name');

        if($addSUBMIT) {
        	 $message = $this->goingroup->insertingroup($accounts, $users, $group_id, $group_name, $group_color, $active, $web_form_address, $voicemail_ext, $next_agent_call, $fronter_display, $script_id, $get_call_launch);
	
        	 if($message !=null) {
        	 	print "<script type=\"text/javascript\">alert('GROUP NOT ADDED - Please go back and look at the data you entered');</script>";
        	 } else {
        	 	header("Location: #");
			 }      	 		      
		  }
		  
		 if($addDID) {
		 	 $message3 = $this->goingroup->insertdid($did_pattern,$did_description,$data['gouser'],$diddata, $db_column);

                        if($message3 !=null) {
                                print "<script type=\"text/javascript\">alert('DID NOT ADDED - DID already exist.');</script>";
                        } else {
                                header("Location: #");
                        }

		 }

		 if($addCALLMENU) { 
			$options['menu_prompt'] = $this->input->post('menu_prompt');
			$options['menu_timeout'] = $this->input->post('menu_timeout');
			$options['menu_timeout_prompt'] = $this->input->post('menu_timeout_prompt');
			$options['menu_invalid_prompt'] = $this->input->post('menu_invalid_prompt');
			$options['menu_repeat'] = $this->input->post('menu_repeat');
			$options['menu_time_check'] = $this->input->post('menu_time_check');
			$options['call_time_id'] = $this->input->post('call_time_id');
			$options['track_in_vdac'] = $this->input->post('track_in_vdac');
			$options['custom_dialplan_entry'] = $this->input->post('custom_dialplan_entry');
			$options['tracking_group'] = $this->input->post('tracking_group');

			for ($ctr=0;$ctr<10;$ctr++)
			{
				if (strlen($this->input->post('option_value_'.$ctr)) < 1)
					break;
				
				$option_route[$ctr]['option_value'] = $this->input->post('option_value_'.$ctr);
				$option_route[$ctr]['option_description'] = $this->input->post('option_description_'.$ctr);
				$option_route[$ctr]['option_route'] = $this->input->post('option_route_'.$ctr);
				$option_route[$ctr]['option_route_value'] = $this->input->post('option_route_value_'.$ctr);
				
				switch ($this->input->post('option_route_'.$ctr))
				{
					case "EXTENSION":
						$option_route[$ctr]['option_route_value_context'] = $this->input->post('option_route_value_context_'.$ctr);
						break;
					
					case "INGROUP":
						$option_route[$ctr]['option_route_value_context'] = $this->input->post('handle_method_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('search_method_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('list_id_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('campaign_id_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('phone_code_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('enter_filename_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('id_number_filename_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('confirm_filename_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('validate_digits_'.$ctr);
						break;
				}
				
			}

		 	$message4 = $this->goingroup->insertcallmenu($menu_id,$menu_name,$options,$option_route);
		 	header("Location: #");
		 }		 
		 
		 if($editDID) {
		 	
		 }
		 
		  /* end insert */		  
		  
		  /* edit */
		  
		  $editSUBMIT = $this->input->post('editSUBMIT');
		  $editparam = $this->input->post('editparam');
		  $listidparam = $this->input->post('listidparam');
		  
		 /* $editparam = $this->uri->segment(3);
		  $listidparam = $this->uri->segment(4);*/
		  
		  if($editparam=='editLIST') {
				//$listvalues = $this->goingroup->geteditvalues($listidparam);
				//$data['listvalues'] = $listvalues;
				
				$this->load->view('go_list/go_db_query',$data);
		  }

		  $editlist = $this->input->post('editlist');
		  if($editlist=='editlist') {
					//die("milo");
					$listid = $this->input->post('list_id');
					$listname = $this->input->post('list_name');
					$campaign_id = $this->input->post('campaign_id');
					$active = $this->input->post('active');
					$list_description = $this->input->post('list_description');
					$list_changedate = $this->input->post('list_changedate');
					$list_lastcalldate = $this->input->post('list_lastcalldate');
					$reset_time = $this->input->post('reset_time');
					$agent_script_override = $this->input->post('agent_script_override');
					$campaign_cid_override = $this->input->post('campaign_cid_override');
					$am_message_exten_override = $this->input->post('am_message_exten_override');
					$drop_inbound_group_override = $this->input->post('drop_inbound_group_override');
					$xferconf_a_number = $this->input->post('xferconf_a_number');
					$xferconf_b_number = $this->input->post('xferconf_b_number');
					$xferconf_c_number = $this->input->post('xferconf_c_number');
					$xferconf_d_number = $this->input->post('xferconf_d_number');
					$xferconf_e_number = $this->input->post('xferconf_e_number');
					$web_form_address = $this->input->post('web_form_address');
					$web_form_address_two = $this->input->post('web_form_address_two');						  	
		  	
					$this->goingroup->editvalues($listid,$listname,$campaign_id,$active,$list_description,$list_changedate,$list_lastcalldate,$reset_time,$agent_script_override,$campaign_cid_override,$am_message_exten_override,$drop_inbound_group_override,$xferconf_a_number,$xferconf_b_number,$xferconf_c_number,$xferconf_d_number,$xferconf_e_number,$web_form_address,$web_form_address_two);
		 //$this->goingroup->geteditvalues($listidparam); 			
		  }
		  /* end edit */
		  
		  /* load leads */
		  	
			/* POST VALUES */			
			$phonedoces = $this->goingroup->getphonecodes();	
			$leadsload = $this->input->post('leadsload');
			$lead_file = $this->input->post('leadfile');
			$list_id_override = $this->input->post('list_id_override');
			$phone_code_override = $this->input->post('phone_code_override');
			$dupcheck = $this->input->post('dupcheck');
			
			$data['leadsload'] = $leadsload;
			
			$vendor_lead_code_field =		$this->input->post('vendor_lead_code_field');
			$source_code_field =			$this->input->post('source_id_field');
			//$source_id=$source_code;
			$list_id_field =				$this->input->post('list_id_field');
			$gmt_offset =			'0';
			$called_since_last_reset='N';
			$phone_code_field =			eregi_replace("[^0-9]", "", $this->input->post('phone_code_field'));
			$phone_number_field =			eregi_replace("[^0-9]", "", $this->input->post('phone_number_field'));
			$title_field =				$this->input->post('title_field');
			$first_name_field =			$this->input->post('first_name_field');
			$middle_initial_field =		$this->input->post('middle_initial_field');
			$last_name_field =			$this->input->post('last_name_field');
			$address1_field =				$this->input->post('address1_field');
			$address2_field =				$this->input->post('address2_field');
			$address3_field =				$this->input->post('address3_field');
			$city_field =	$this->input->post('city_field');
			$state_field =				$this->input->post('state_field');
			$province_field =				$this->input->post('province_field');
			$postal_code_field =			$this->input->post('postal_code_field');
			$country_code_field =			$this->input->post('country_code_field');
			$gender_field =				$this->input->post('gender_field');
			$date_of_birth_field =		$this->input->post('date_of_birth_field');
			$alt_phone_field =			eregi_replace("[^0-9]", "", $this->input->post('alt_phone_field'));
			$email_field =				$this->input->post('email_field');
			$security_phrase_field =		$this->input->post('security_phrase_field');
			$comments_field =				trim($this->input->post('comments_field'));
			$rank_field =					$this->input->post('rank_field');
			$owner_field =				$this->input->post('owner_field');
	
        			/* pass value to views milo */
        			$data['ingrouppulldown'] = $ingrouppulldown;
        			$data['calltimespulldown'] = $calltimespulldown;
					$data['callmenupulldown'] = $callmenupulldown;
					$data['phonespulldown'] = $phonespulldown;
					$data['groupaliaspulldown'] = $groupaliaspulldown;
					$data['ingrouplists'] = $ingrouplists;
					$data['scriptlists'] = $scriptlists;
					$data['campaigns'] = $campaigns;
					$data['accounts'] = $accounts;
					$data['agentranks'] = $agentranks;
					$data['phonedoces'] = $phonedoces;
					$data['getdids'] = $getdids;	
					$data['campaigns_list']	= $campaigns;
					$data['getservers'] = $getservers;
					$data['getfpgroups'] = $getfpgroups;
					$data['getallcallmenus'] = $getallcallmenus;
					$data['agent_list'] = $this->goingroup->getallagents();
					$data['phone_list'] = $this->goingroup->getallactivephones();
				 #callmenu			
				 $data['callmenu'] = $this->goingroup->getcallmenu();
                                 if(!empty($data['callmenu'])){
                                     $lastdigit = end($data['callmenu']);
                                 } else {
                                     $data['lastcount'] = '01';
                                 }
                                 # phone
                                 /*$this->goingroup->phone();
                                 $phones = $this->goingroup->results();
                                 if(!empty($phones)){
                                      foreach($phones as $row){
                                          $collectedphones[$row->extension] = "{$row->extension} - {$row->dialplan_number}";
                                      }
                                 }else{
                                      $collectedphones = array();
                                 }
                                 $data['phones'] = $collectedphones;*/

							$clist = $this->goingroup->getcustomlist();
							$data['clist'] = $clist;
				        	$this->load->view('includes/go_dashboard_template.php',$data);
			
    }
    
	function editview() {
		$this->load->view('go_ingroup/go_values',$data);
	}
    
	function showoption() {
		$this->load->view('go_ingroup/go_values',$data);
	}
	
	function editsubmit() {
		$this->load->view('go_ingroup/go_values',$data);
	}
	
	function checkagentrank(){
		$this->load->view('go_ingroup/go_values',$data);
	}

	function chooser() {
		$this->load->view('go_ingroup/go_chooser',$data);
	}
	
	
	
	
	function deletesubmit() {
		$this->load->view('go_ingroup/go_values',$data);
	}

	function editcustomview() {
		$this->load->view('go_list/go_values',$data);
	}
	
	function editcustomlist() {
		$listidparam = $this->uri->segment(3);
		$data['listidparam'] = $listidparam;
		$data['userfulname'] = $callfunc;
        $data['gouser'] = $this->session->userdata('user_name');
        $data['gopass'] = $this->session->userdata('user_pass');
        $data['theme'] = $this->session->userdata('go_theme');
        $data['rep']= 'wp-has-current-submenu';
        $data['hostp'] = $_SERVER['SERVER_ADDR'];
        $data['foldlink'] = '';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';
        $data['bannertitle'] = 'Lists';
        $data['folded'] = 'folded';
        $data['go_main_content'] = 'go_list/go_customlist'; 
        $data['users'] = $users;
        $data['usergroups'] = $usergroups;
        $data['account'] = $account;
        $data['gowizard'] = "gowizard";

			$custeditview = $this->goingroup->custeditview($listidparam);
			$countfields = $this->goingroup->countfields($listidparam);
		

        $data['custeditview'] = $custeditview;
        $data['countfields'] = $countfields;
		 
		$this->load->view('includes/go_dashboard_template.php',$data);
		
	}
	
	function go_check_ingroup()
	{
		$menu_id = $this->uri->segment(3);
        $this->asteriskDB = $this->load->database('dialerdb',TRUE);
		$query = $this->asteriskDB->query("SELECT * FROM vicidial_call_menu WHERE menu_id = '$menu_id'");
		$return = $query->num_rows();
		
		$reserved_id = array(
			'vicidial',
			'vicidial-auto',
			'general',
			'globals',
			'default',
			'trunkinbound',
			'loopback-no-log',
			'monitor_exit',
			'monitor'
		);
		
		$exist_on_reserved_id = false;
		foreach ($reserved_id as $resid)
		{
			if ($menu_id === $resid)
				$exist_on_reserved_id = true;
		}
		
		if ($return || $exist_on_reserved_id)
		{
			$return = "<small style=\"color:red;\">Not Available.</small>";
		} else {
			$return = "<small style=\"color:green;\">Available.</small>";
		}
		
		echo $return;
	}
	

        function batchupdate(){
            $this->asteriskDB = $this->load->database('dialerdb',TRUE);
            if(!empty($_POST)){
                if(!empty($_POST['batch']) && is_array($_POST['batch'])){
                    foreach($_POST['batch'] as $batch){
                        switch($_POST['action']){
                             case 'Y':
                             case 'N':
                             case 'D':
                                 # check the table to switch condition
                                 if($_POST['table'] == "vicidial_inbound_groups"){
                                     $this->asteriskDB->where('group_id',$batch);
                                     $vars = array('active'=>$_POST['action']);
                                 }elseif($_POST['table'] == "vicidial_inbound_dids"){
                                     $this->asteriskDB->where('did_id',$batch);
                                     $vars = array('did_active'=>$_POST['action']);
                                 }else{
                                     $this->asteriskDB->where('menu_id',$batch);
                                 }

                                 if($_POST['action'] != 'D'){
                                     if($_POST['table'] != "vicidial_call_menu"){
                                         $this->asteriskDB->update($_POST['table'],$vars);
                                     }
                                 }else{
                                     $this->asteriskDB->delete($_POST['table']);
                                 }
                             break;
                             default:
                             break;
                        }
                    }
                    echo "Success: Batch process complete";
                }
            }else{
                die("Error: No raw data to process");
            }
        }
}
