<?php
########################################################################################################
####  Name:             	goingroup.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Jerico James Milo                                       	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Goingroup extends Model {


     function __construct(){
         parent::Model();
         $this->asteriskDB = $this->load->database('dialerdb',TRUE);
         //$this->a2billingDB =$this->load->database('billingdb',TRUE) ;
     }
     
     function getuserlevel($uname=null) {

	      $query = $this->asteriskDB->query("SELECT user_id,user,pass,full_name,user_level FROM vicidial_users where user='$uname'");
	      $row = $query->row();
	      return $row->user_level;

     }
     
//	 function getaccounts($accntnum=null) {
//	 	
//	 	  $stmt="SELECT account_num,company FROM a2billing_wizard WHERE account_num='$accntnum' ORDER BY company";
//	 
//	      $acctno = $this->asteriskDB->query($stmt);
//	      $row = $acctno->row(); 
//         return $row->account_num;
//     }


     function getallingroup($accntnum=null,$userlevel=null) {

			
	     if ($userlevel < 7) {
			$uniqueid_status_prefixSQL = "WHERE uniqueid_status_prefix='$accntnum'";
	     }

        $stmt="SELECT group_id,group_name,queue_priority,active,call_time_id,group_color FROM vicidial_inbound_groups $uniqueid_status_prefixSQL ORDER BY group_id";

   	     $listall = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listall->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              } 
	      return $lists;
     }
     
     function scriptlists($userlevel,$account) {
     	
     	  if($userlevel > 8) {
     	  		$stmt = "SELECT vs.script_id,vs.script_name from vicidial_scripts AS vs, go_scripts AS gs WHERE vs.script_id=gs.script_id order by vs.script_id";
     	  } else {
     	  		$stmt="SELECT vs.script_id,vs.script_name from vicidial_scripts AS vs, go_scripts AS gs WHERE vs.script_id=gs.script_id AND gs.account_num='$account' order by vs.script_id";
     	  }
     	  
     	  $listall = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listall->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              } 
	      return $lists;
     }

     function getcampaign($wherecampaigns=null) {
	      
	      $stmt="SELECT campaign_id,campaign_name from vicidial_campaigns $wherecampaigns order by campaign_id";
	   
	      $campaignall = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($campaignall->result() as $info){
                  $campaigns[$ctr] = $info;
                  $ctr++;
              }

	      return $campaigns;
     }
     
     function getdids($userlevel=null,$accounts=null) {
     	if ($userlevel < 7) {
			$addedSQL = "WHERE did_description LIKE '$accounts%'";
		}
     	
			$stmt="SELECT did_id,did_pattern,did_description,did_active,did_route,record_call from vicidial_inbound_dids $addedSQL order by did_pattern;";
			
			$didall = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($didall->result() as $info){
                  $didallin[$ctr] = $info;
                  $ctr++;
              }

	      return $didallin;
     }
     
     function getservers($userlevel=null) {
     	
     	if($userlevel < 9) {
     		$addedSQL = "WHERE server_description LIKE 'MAIN DIALER%'";
     	}
     	
     	$stmt="SELECT server_ip,server_description,external_server_ip from servers $addedSQL order by server_ip";
		
		$servs = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($servs->result() as $info){
                  $servsin[$ctr] = $info;
                  $ctr++;
              }

	   return $servsin;
     	
     }
     
     function getfpgroups() {
     		$stmt="select filter_phone_group_id,filter_phone_group_name from vicidial_filter_phone_groups order by filter_phone_group_id;";
     		
			$fpgroups = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($fpgroups->result() as $info){
                  $fpgroupsin[$ctr] = $info;
                  $ctr++;
              }

	   return $fpgroupsin;
     		
  	  }
  	  
  	  function getallcallmenus($userlevel=null,$accounts=null) {
  	  	 if ($userlevel < 9) {
                $filterSQL = "WHERE menu_id LIKE '$accounts%' OR menu_name LIKE '$accounts%'";
        }

        $stmt="SELECT menu_id,menu_name,menu_prompt,menu_timeout from vicidial_call_menu $filterSQL order by menu_id";
        
        $callmenu = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($callmenu->result() as $info){
                  $callmenuin[$ctr] = $info;
                  $ctr++;
              }

	   return $callmenuin;

  	  	
  	  }
  	  
	  function insertcallmenu($menu_id=null,$menu_name=null,$options=null,$option_route=null) {
	  		$stmt = "SELECT value FROM vicidial_override_ids where id_table='vicidial_call_menu' and active='1';";
	  		$query = $this->asteriskDB->query($stmt);
        	$voi_ct = $query->num_rows;
        	
        	if ($voi_ct > 0) {
	        	$row = $query->row(); 
	        	$menu_id = ($row->value + 1);
	        	$stmt = "UPDATE vicidial_override_ids SET value='$menu_id' where id_table='vicidial_call_menu' and active='1';";
				$this->asteriskDB->query($stmt);
        	}
			
			foreach ($options as $id => $val)
			{
			   $ScmSQL .= ",$id";
			   $EcmSQL .= ",'$val'";
			}

        	$stmt="SELECT count(*) as menucounts from vicidial_call_menu where menu_id='$menu_id';";
			$query = $this->asteriskDB->query($stmt);
      	 	$row = $query->row();
      	 	
      	 	 if ($row->menucounts > 0) {
      	 	 		$message = "CALL MENU NOT ADDED - there is already a CALL MENU in the system with this ID\n";
      	 	 } else {
      	 	 		$stmt="INSERT INTO vicidial_call_menu (menu_id,menu_name$ScmSQL) values('$menu_id','$menu_name'$EcmSQL);";
      	 	 		$this->asteriskDB->query($stmt);
					# set default entry in vicidial_callmenu_options by Franco Hora 
					$this->asteriskDB->insert('vicidial_call_menu_options',array('menu_id'=>$menu_id,'option_value'=>'TIMEOUT','option_description'=>'Hangup','option_route'=>'HANGUP','option_route_value'=>'vm-goodbye'));
					
					if (count($option_route) > 0)
					{
					   foreach ($option_route as $cnt => $option_value)
					   {
						 $OptIdSQL = "(menu_id";
						 $OptValSQL = "('$menu_id'";
						 foreach ($option_value as $id => $val)
						 {
							$OptIdSQL .= ",$id";
							$OptValSQL .= ",'$val'";
						 }
						 $OptIdSQL .= ")";
						 $OptValSQL .= ")";
						 $this->asteriskDB->query("INSERT INTO vicidial_call_menu_options $OptIdSQL VALUES $OptValSQL;");
					   }
					}
			 }
      	 	 	
	  	
	  }  	 

     function insertingroup($accounts=null, $users=null, $group_id=null, $group_name=null, $group_color=null, $active=null, $web_form_address=null, $voicemail_ext=null, $next_agent_call=null, $fronter_display=null, $script_id=null, $get_call_launch=null) {
			
		   $group_id = $trimmed = str_replace(" ", "", $group_id);
		   
        $stmt = "SELECT value FROM vicidial_override_ids where id_table='vicidial_inbound_groups' and active='1';";
        $query = $this->asteriskDB->query($stmt);
        $voi_ct = $query->num_rows;
        
        if ($voi_ct > 0) {
        	$row = $query->row(); 
        	$group_id = ($row->value + 1);
        	$stmt = "UPDATE vicidial_override_ids SET value='$group_id' where id_table='vicidial_inbound_groups' and active='1';";
        	$this->asteriskDB->query($stmt);
        }
        
        $stmt="SELECT count(*) as vigcount from vicidial_inbound_groups where group_id='$group_id';";
        $query = $this->asteriskDB->query($stmt);
      	 $row = $query->row(); 
      	 
        if ($row->vigcount > 0) {
        		$message = "<br>GROUP NOT ADDED - there is already a group in the system with this ID\n";
        } else {
                $stmt="SELECT count(*) as vccampid from vicidial_campaigns where campaign_id='$group_id';";
		         $query = $this->asteriskDB->query($stmt);
		         $row = $query->row(); 
          
                if ($row->vccampid > 0) {
                		$message = "<br>GROUP NOT ADDED - there is already a campaign in the system with this ID\n";
                } else {
                        
                        if ( (strlen($group_id) < 2) or (strlen($group_name) < 2)  or (strlen($group_color) < 2) or (strlen($group_id) > 20) or (eregi(' ',$group_id)) or (eregi("\-",$group_id)) or (eregi("\+",$group_id)) ) {
                                
                                $message = "<br>GROUP NOT ADDED - Please go back and look at the data you entered\n <br>Group ID must be between 2 and 20 characters in length and contain no ' -+'.\n <br>Group name and group color must be at least 2 characters in length\n";
                                
							} else {

                                $groupcallt = $group_id.'_ingroup';
                                
                                $stmt="INSERT INTO vicidial_inbound_groups (group_id,group_name,group_color,active,web_form_address,voicemail_ext,next_agent_call,fronter_display,ingroup_script,get_call_launch,web_form_address_two,start_call_url,dispo_call_url,add_lead_url,uniqueid_status_prefix,call_time_id) values('".mysql_real_escape_string($group_id)."','".mysql_real_escape_string($group_name)."','$group_color','$active','".mysql_real_escape_string($web_form_address)."','".mysql_real_escape_string($voicemail_ext)."','$next_agent_call','$fronter_display','$script_id','$get_call_launch','','','','','$accounts','24hours');";
                                $query = $this->asteriskDB->query($stmt);
                                
				$suser = $users;

                                //$stmtCT="INSERT INTO vicidial_call_times SET call_time_id='$groupcallt',call_time_name='$group_name',call_time_comments='$group_name',accountno='$accounts',ct_default_start='0', ct_default_stop='2400';";
                                //$query = $this->asteriskDB->query($stmtCT);
									 //die($stmt);


                                $stmtA="INSERT INTO vicidial_campaign_stats (campaign_id) values('".mysql_real_escape_string($group_id)."');";
                                $query = $this->asteriskDB->query($stmtA);
                                //die($stmt.'<br>'.$stmtCT.'<br>'.$stmtA);

                               /* ### LOG INSERTION Admin Log Table ###
                                $SQL_log = "$stmt|";
                                $SQL_log = ereg_replace(';','',$SQL_log);
                                $SQL_log = addslashes($SQL_log);
                                $stmt="INSERT INTO vicidial_admin_log set event_date='$SQLdate', user='$PHP_AUTH_USER', ip_address='$ip', event_section='INGROUPS', event_type='ADD', record_id='$group_id', event_code='ADMIN ADD INBOUND GROUP', event_sql=\"$SQL_log\", event_notes='';";
                                if ($DB) {echo "|$stmt|\n";}
                                $rslt=mysql_query($stmt, $link);*/
                                }
                        }
                }
                
			return $message;
        //$ADD=3111;

     }
     
     function insertdid($did_pattern=null, $did_description=null, $gouser=null, $diddata=null, $db_column=null) {
     	    	     	
        $trimmed = str_replace(" ", "", $did_pattern);
        $did_descriptionfinal = $gouser." - ".$did_description;
	$did_data = implode("','",$diddata);

                $stmtdf="SELECT count(*) as didpat from vicidial_inbound_dids where did_pattern='$trimmed';";
                $querydf = $this->asteriskDB->query($stmtdf);
                $rowdf = $querydf->row();
                  
                if ($rowdf->didpat > 0) {
                    $message = "<br>DID NOT ADDED - DID already exist.\n";
                } else {
                    $stmt="INSERT INTO vicidial_inbound_dids (did_pattern,did_description,record_call{$db_column}) values('".mysql_real_escape_string($trimmed)."','".mysql_real_escape_string($did_descriptionfinal)."','N','$did_data');";
                    $query = $this->asteriskDB->query($stmt);
                }
		
                return $message;

		     	
     }
     
     function getdidvalues($didid=null) {
     	$stmt="SELECT did_id,did_pattern,did_description,did_active,did_route,extension,exten_context,voicemail_ext,phone,server_ip,user,user_unavailable_action,user_route_settings_ingroup,group_id,call_handle_method,agent_search_method,list_id,campaign_id,phone_code,menu_id,record_call,filter_inbound_number,filter_phone_group_id,filter_url,filter_action,filter_extension,filter_exten_context,filter_voicemail_ext,filter_phone,filter_server_ip,filter_user,filter_user_unavailable_action,filter_user_route_settings_ingroup,filter_group_id,filter_call_handle_method,filter_agent_search_method,filter_list_id,filter_campaign_id,filter_phone_code,filter_menu_id,filter_clean_cid_number from vicidial_inbound_dids where did_id='$didid';";
     	
     	//echo $stmt;
     	
     	$listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }
              

	      return $lists;
     	
     }

	  function geteditvalues($group_id=null) {
	  		
	  		$stmt = "SELECT group_id,group_name,group_color,active,web_form_address,voicemail_ext,next_agent_call,fronter_display,ingroup_script,get_call_launch,xferconf_a_dtmf,xferconf_a_number,xferconf_b_dtmf,xferconf_b_number,drop_call_seconds,drop_action,drop_exten,call_time_id,after_hours_action,after_hours_message_filename,after_hours_exten,after_hours_voicemail,welcome_message_filename,moh_context,onhold_prompt_filename,prompt_interval,agent_alert_exten,agent_alert_delay,default_xfer_group,queue_priority,drop_inbound_group,ingroup_recording_override,ingroup_rec_filename,afterhours_xfer_group,qc_enabled,qc_statuses,qc_shift_id,qc_get_record_launch,qc_show_recording,qc_web_form_address,qc_script,play_place_in_line,play_estimate_hold_time,hold_time_option,hold_time_option_seconds,hold_time_option_exten,hold_time_option_voicemail,hold_time_option_xfer_group,hold_time_option_callback_filename,hold_time_option_callback_list_id,hold_recall_xfer_group,no_delay_call_route,play_welcome_message,answer_sec_pct_rt_stat_one,answer_sec_pct_rt_stat_two,default_group_alias,no_agent_no_queue,no_agent_action,no_agent_action_value,web_form_address_two,timer_action,timer_action_message,timer_action_seconds,start_call_url,dispo_call_url,xferconf_c_number,xferconf_d_number,xferconf_e_number,ignore_list_script_override,extension_appended_cidname,uniqueid_status_display,uniqueid_status_prefix,hold_time_option_minimum,hold_time_option_press_filename,hold_time_option_callmenu,onhold_prompt_no_block,onhold_prompt_seconds,hold_time_option_no_block,hold_time_option_prompt_seconds,hold_time_second_option,hold_time_third_option,wait_hold_option_priority,wait_time_option,wait_time_second_option,wait_time_third_option,wait_time_option_seconds,wait_time_option_exten,wait_time_option_voicemail,wait_time_option_xfer_group,wait_time_option_callmenu,wait_time_option_callback_filename,wait_time_option_callback_list_id,wait_time_option_press_filename,wait_time_option_no_block,wait_time_option_prompt_seconds,timer_action_destination,calculate_estimated_hold_seconds,add_lead_url,eht_minimum_prompt_filename, eht_minimum_prompt_no_block, eht_minimum_prompt_seconds,on_hook_ring_time from vicidial_inbound_groups where group_id='$group_id';";
	  		//echo $stmt;
			$listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }
              

	      return $lists;
	  		
	  }
	  
	  function getcallmenudetails($menu_id=null) {
	  		
	  		 $stmt="SELECT menu_name,menu_prompt,menu_timeout,menu_timeout_prompt,menu_invalid_prompt,menu_repeat,menu_time_check,call_time_id,track_in_vdac,custom_dialplan_entry,tracking_group from vicidial_call_menu where menu_id='$menu_id';";

			$listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }
              

	      return $lists;
	  		
	  }
	  
	  function getcallmenuoptions($menu_id=null,$route_id=null,$optval=null)
	  {
		  if (strlen($route_id) > 0)
			   $routeSQL = "AND option_route='$route_id' AND option_value='$optval'";
		  
		  $stmt="SELECT option_value,option_description,option_route,option_route_value,option_route_value_context FROM vicidial_call_menu_options WHERE menu_id='$menu_id' $routeSQL ORDER BY option_value";
		  
		  $listval = $this->asteriskDB->query($stmt);
		  $ctr = 0;
		  foreach ($listval->result() as $info)
		  {
			   $lists[$ctr] = $info;
			   $ctr++;
		  }
		  
		  return $lists;
	  }
	  
	  ##### get callmenu listings for dynamic pulldown
	  function getcallmenu($userlevel=null,$accounts=null) {
	  	 
			if ($userlevel < 7) {	   
		      $addedMenuSQL = "where menu_name LIKE '$accounts %'";
		    }
	      
	      $stmt="SELECT menu_id,menu_name from vicidial_call_menu $addedMenuSQL order by menu_id";
	      
	      $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }

	      return $lists;
	  }
	  
	  function getphonelist()
	  {
		  $stmt="SELECT extension,server_ip,dialplan_number,voicemail_id FROM phones ORDER BY extension";
		  $listval = $this->asteriskDB->query($stmt);
		  $ctr = 0;
		  foreach($listval->result() as $info)
		  {
			   $lists[$ctr] = $info;
			   $ctr++;
		  }
		  
		  return $lists;
	  }

	  function groupalias() {
	  	
	  		$stmt="SELECT group_alias_id,group_alias_name from groups_alias where active='Y' order by group_alias_id";
	      	$listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }

	      return $lists;
	  }
	  
     function ingrouppulldown($userlevel=null,$accounts=null) {

	   	if ($userlevel < 7) {
				$addedSQL = "and uniqueid_status_prefix='$accounts'";
		  }
		
	      $stmt="SELECT group_id,group_name from vicidial_inbound_groups where group_id NOT IN('AGENTDIRECT') $addedSQL order by group_id";
	     	
	      $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }

	      return $lists;
		     	
     }
     
     function calltimespulldown($userlevel=null,$accounts=null) {

        if($userlevel > 6) {
        	$stmt="SELECT call_time_id,call_time_name,ct_default_start,ct_default_stop from vicidial_call_times order by call_time_id";
        } else {
          $stmt="SELECT call_time_id,call_time_name,ct_default_start,ct_default_stop from vicidial_call_times where accountno='$accounts' order by call_time_id";	
        }
          
	      $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }

	      return $lists;
     	
     }	  
     
     
     function agentranks($group_id=null) {
			
			$userlevel = $this->goingroup->getuserlevel($this->session->userdata('user_name'));
			//$account = $this->goingroup->getaccounts($this->session->userdata('user_name')); // get account number

     		if ($userlevel < 7) {
	               $addedSQL = "and user_group='$accounts'";
	       }

          $stmt="SELECT user,full_name,closer_campaigns from vicidial_users where active='Y' $addedSQL order by user;";
          $listval = $this->asteriskDB->query($stmt);
		      
		      $users_to_print = $listval->num_rows;
		      $o = 0;
					     
		      foreach ($listval->result_array() as $row) {
		   		$o++;
		   		
							$camp_name[$ctr] = $row['user'];
							$ARIG_user[$o] =        $row['user'];
                        $ARIG_name[$o] =        $row['full_name'];
                        $ARIG_close[$o] =       $row['closer_campaigns'];
                        $ARIG_check[$o] =       '';
                        if (preg_match("/ $group_id /",$ARIG_close[$o]))
                                {$ARIG_check[$o] = ' CHECKED';}
            
             }
             
             $o=0;
             $ARIG_changenotes='';
             $stmtDlog='';
             
             while ($users_to_print > $o) {
             $o++;
                     $stmtx="SELECT group_rank,calls_today from vicidial_inbound_group_agents where group_id='$group_id' and user='$ARIG_user[$o]';";
						
                     $rsltx = $this->asteriskDB->query($stmtx);
                     $viga_to_print = $rsltx->num_rows;
                     
                     if ($viga_to_print > 0) {
                     			foreach ($rsltx->result_array() as $rowx) {
                              		$ARIG_rank[$o] =        $rowx[0];
                             		$ARIG_calls[$o] =       $rowx[1];

	                             		if($ARIG_calls[$o]==null){
												$ARIG_calls[$o]="0";                             			
	                             		}
	                             		
                             	}
						} else {
                             	$stmtD="INSERT INTO vicidial_inbound_group_agents set calls_today='0',group_rank='0',group_weight='0',user='$ARIG_user[$o]',group_id='$group_id';";
                             	$rslt=$this->asteriskDB->query($stmtD);
                             	$stmtDlog .= "$stmtD|";
                             	$ARIG_changenotes .= "added missing user to viga table $ARIG_user[$o]|";
                             	$ARIG_rank[$o] =        '0';
                             	$ARIG_calls[$o] =       '0';
						}
              }
              
              $users_output .= "<tr><td>USER</td><td>SELECTED</td><td> &nbsp; &nbsp; RANK</td><td> &nbsp; &nbsp; CALLS TODAY</td></tr>\n";	
              $checkbox_count=0;
              $o=0;
              while ($users_to_print > $o) {
              $o++;

                        if (eregi("1$|3$|5$|7$|9$", $o))
                                {$bgcolor='bgcolor="#cccccc"';}
                        else
                                {$bgcolor='bgcolor="#bcbcbc"';}

/*                        $checkbox_field="CHECK_$ARIG_user[$o]$US$group_id";
                        $rank_field="RANK_$ARIG_user[$o]$US$group_id";
*/							$checkbox_field="CHECK_$ARIG_user[$o]";
                        $rank_field="RANK_$ARIG_user[$o]";

                        $checkbox_list .= "|$checkbox_field";
                        $checkbox_count++;

                        $users_output .= "<tr $bgcolor><td><font size=1>$ARIG_user[$o] - $ARIG_name[$o]</td>\n";
                        $users_output .= "<td align=center><input type=checkbox name=\"$checkbox_field\" id=\"$checkbox_field\" value=\"YES\"$ARIG_check[$o]></td>\n";
                        $users_output .= "<td align=center><select size=1 name=$rank_field>\n";
                        $h="9";
                        while ($h>=-9)
                                {
                                $users_output .= "<option value=\"$h\"";
                                if ($h==$ARIG_rank[$o])
                                        {$users_output .= " SELECTED";}
                                $users_output .= ">$h</option>";
                                $h--;
                                }
                        $users_output .= "</select></td>\n";
                        $users_output .= "<td align=center><font size=1>$ARIG_calls[$o]</td></tr>\n";
                	
              }
				$users_output .= "<tr><td align=center colspan=4><input type=button name=submit value=SUBMIT onclick=\"checkdatas('$group_id');\"></td></tr>\n";
				
				return $users_output;     	
     }
	  
	  
	  
	  
	  
	  
	  
	  /* list functions */     
     
     

	   //autogen
     function autogenlist($accnt) {
	      
	      $stmt = "SELECT TRIM(allowed_campaigns) as trimallowedcamp  FROM vicidial_user_groups WHERE user_group='$accnt';";
	      $query = $this->asteriskDB->query($stmt);
	      $row = $query->row_array();	
	      $allowed_campaigns = $row['trimallowedcamp'];
	      $camp_list=str_replace(" ",",",str_replace(" -","",$allowed_campaigns));
         $allowed_campaigns=str_replace(" ","','",str_replace(" -","",$allowed_campaigns));
	

         $stmt="SELECT TRIM(campaign_name) as trimcampname FROM vicidial_campaigns WHERE campaign_id IN('$allowed_campaigns');";
	      $query = $this->asteriskDB->query($stmt);
	      $num = $query->num_rows;		

	      $ctr = 0;
	      foreach ($query->result_array() as $row) {
    			$camp_name[$ctr] = $row['trimcampname'];
				$ctr++;
	      }

	      $stmt="SELECT list_id FROM vicidial_lists WHERE campaign_id IN('$allowed_campaigns') order by list_id desc limit 1;";
	      $query = $this->asteriskDB->query($stmt);
	      $num = $query->num_rows;		
	      $lists = $query->row_array();
	      $listID = $lists['list_id'];
		
         $cnt=str_replace(ltrim(substr($accnt,0,5),"0")."1","",$listID);
    		$cnt=intval("$cnt");
    			if ($cnt < $num) {
        			$cnt = $num;
    			}
	       $camp_name = implode(",",$camp_name);
 			 $camps = "$cnt\n$camp_list\n".$camp_name;

	    return $camps;
     }
     
     function showtable($list_id_override) {
     		$tablecount_to_print=0;
			$fieldscount_to_print=0;
			$fields_to_print=0;
								
	  		$stmt="SHOW TABLES LIKE \"custom_$list_id_override\";";
			$syslook = $this->asteriskDB->query($stmt);
			$fieldscount_to_print = $syslook->num_rows;
			
				if ($fieldscount_to_print > 0) {
						$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id_override';";
						$listcount = $this->asteriskDB->query($stmt);
						$fields_to_print = $listcount->num_rows;
						
						if ($fieldscount_to_print > 0) {
							$stmt="SELECT field_label,field_type from vicidial_lists_fields where list_id='$list_id_override' order by field_rank,field_order,field_label;";
							$labelcount = $this->asteriskDB->query($stmt);
							$fields_to_print = $labelcount->num_rows;
							$fields_list='';
							$o=0;
								foreach($labelcount->result() as $info){
                  			$A_field_label[$o] = $info->field_label;
                  			$A_field_type[$o] = $info->field_type;
                  			$A_field_value[$o] =	'';
                  			$o++;
             				} 

						}
				}
	  } // end function

	  

 	
	  function editvalues($query=null) {
	  		$this->asteriskDB->query($query);
	  }
	  
	  function deletevalues($query=null) {
	  		$this->asteriskDB->query($query);
	  }
	  
	  function resetleads($query=null) {
	  		$this->asteriskDB->query($query);
	  }
	  
	  function getphonecodes() {
	  	
	  		$stmt="select distinct country_code, country from vicidial_phone_codes;";
	  		$pcodes = $this->asteriskDB->query($stmt);
	  		$ctr = 0;
              foreach($pcodes->result() as $info){
                  $phonecodes[$ctr] = $info;
                  $ctr++;
              }

	      return $phonecodes;
	  		
	  }
	  
	  function systemsettingslookup() {

	  		$stmt = "SELECT use_non_latin,admin_web_directory,custom_fields_enabled FROM system_settings;";
	  		$syslook = $this->asteriskDB->query($stmt);
	  		$ctr = 0;
              foreach($syslook->result() as $info){
                  $syslookup[$ctr] = $info;
                  $ctr++;
              }

	      return $syslookup;
	  		
	  }
	  
	  function getlistme() {
			$fields_stmt = "SELECT vendor_lead_code, source_id, list_id, phone_code, phone_number, title, first_name, middle_initial, last_name, address1, address2, address3, city, state, province, postal_code, country_code, gender, date_of_birth, alt_phone, email, security_phrase, comments, rank, owner from vicidial_list limit 1";
			$fields = $this->asteriskDB->query($fields_stmt);
	  		$ctr = 0;
              foreach($fields->result() as $info){
                  $allfields[$ctr] = $info;
                  $ctr++;
              }

	      return $allfields;
	  }	  
	  


     function getinboundgrp($wherecampaigns=null) {
	     
	      $stmt="";
	      $inboundgrp = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($inboundgrp->result() as $info){
                  $inboundgrps[$ctr] = $info;
                  $ctr++;
              }

	      return $inboundgrps;
     }


     
     function getcustomlist(){

			$stmt="SELECT list_id,list_name,active,campaign_id from vicidial_lists $whereLOGallowed_campaignsSQL order by list_id;";
			
			$clist = $this->asteriskDB->query($stmt);
			$lists_to_print = $clist->num_rows;
         	$o = 0;
       		foreach($clist->result() as $info){
                  $clistss[$o] = $info;
                  $o++;
              }
			
				foreach($clistss as $clistInfo){
					 $A_list_id = $clistInfo->list_id;
					 $A_list_name = $clistInfo->list_name;
					 $A_active = $clistInfo->active;
					 $A_campaign_id = $clistInfo->campaign_id;
					 $stmt2 = "SELECT count(*) as countfields from vicidial_lists_fields where list_id='$A_list_id'";
					 $custlist = $this->asteriskDB->query($stmt2);
					 $o=0;
					
					foreach($custlist->result() as $info2) {
		            $clistss2[$o] = $info2;
		            $o++;
		          	}	

//		            $custall .="<br>";

              	 foreach($clistss2 as $clistInfo2) {
						$fieldscount = $clistInfo2->countfields;
						$custall .= "<tr align=left class=tr".alternator('1', '2').">";
						$custall .= "<td align=center>$A_list_id</td>";
						$custall .= "<td>$A_list_name</td>";
							
						if($A_active =="Y") {
							$A_active = "<b><font color=green>Active</font></b>";
						}	else {
							$A_active = "<b><font color=red>Inactive</font></b>";
						}	
									
						$custall .= "<td align=center>$A_active</td>";
						$custall .= "<td align=center>$A_campaign_id</td>";
						$custall .= "<td align=center>$fieldscount</td>";
						$custall .= "<td colspan=2 align=center><a href=\"http://".$_SERVER['SERVER_NAME']."/index.php/go_list/editcustomlist/$A_list_id\">[- modify fields -]</a></td></tr>\n";
					  }
				}

              return $custall;
     }
     
	  function custeditview($list_id) {

				$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|';

			   $custom_records_count=0;
				$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
				$rslt = $this->asteriskDB->query($stmt);
				$tablecount_to_print = $rslt->num_rows;
				if ($tablecount_to_print > 0) 
					{$table_exists =	1;}
				if ($table_exists > 0){
					$stmt="SELECT count(*) as countcustom from custom_$list_id;";
					$rslt = $this->asteriskDB->query($stmt);
					$fieldscount_to_print = $rslt->num_rows;
					if ($fieldscount_to_print > 0) {
						$rowx=$rslt->row();
						$custom_records_count =	$rowx->countcustom;
						}
					}
				
		
			$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,
				field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
				
		
			$rslt=$this->asteriskDB->query($stmt);
			$fields_to_print = $rslt->num_rows;
			$fields_list='';
			$o=0;

       		foreach($rslt->result() as $info){
                  $fieldsval[$o] = $info;
                  $o++;
              }
			
				
				
				$o++;
				$rank_select .= "<option>$o</option>";
				$last_rank = $o;

				
	
					### SUMMARY OF FIELDS ###
					$field_HTML .= "<br>";
					$field_HTML .= "<form name=\"formcustomview\" id=\"formcustomview\">";
					
					$field_HTML .= "<b>Summary of fields</b> <a id=\"activator\" class=\"activator\" style=\"margin-left: 38.3%;\" href=\"#\" onClick=\"viewadd('$list_id');\"> [- Create custom field -] </a> &nbsp; <a id=\"activator\" class=\"activator\" href=\"#\" onClick=\"customviews('$list_id');\"> [- View custom fields -] </a><a id=\"activator\" class=\"activator\" href=\"http://".$_SERVER['SERVER_NAME']."/index.php/go_list/go_list#tabs-2\">[- View custom listings -]</a></div>";
					//$field_HTML .= "<table width=\"100%\" class=\"tableedit\"><tr><td></td></tr></table>";
					//$field_HTML .= "<br>";
					
					$field_HTML .= "<center>";
					
					$field_HTML .= "<TABLE class=\"tableedit\" width=100%>\n";
					$field_HTML .=  "<tr><td>&nbsp;&nbsp;</td></tr>\n";
					$field_HTML .=  "<tr><td>&nbsp;&nbsp;</td></tr>\n";
					$field_HTML .=  "<TR>";
					$field_HTML .=  "<TD align=\"center\"><b>Rank</b></TD>";
					$field_HTML .=  "<TD align=\"center\"><b>Label</b></TD>";
					$field_HTML .=  "<TD align=\"center\"><b>Name</b></TD>";
					$field_HTML .=  "<TD align=\"center\"><b>Type</b></TD>";
					$field_HTML .=  "<TD align=\"center\"><b>Action</b></TD>\n";
					$field_HTML .=  "</TR>\n";
									
				
				
					$fieldcount = count($fieldsval);
					if($fieldcount > 0) {
					
					foreach($fieldsval as $fieldsvalues){
					
					$A_field_id =			$fieldsvalues->field_id;
					$A_field_label =		$fieldsvalues->field_label;
					$A_field_name =			$fieldsvalues->field_name;
					$A_field_description =	$fieldsvalues->field_description;
					$A_field_rank =			$fieldsvalues->field_rank;
					$A_field_help =			$fieldsvalues->field_help;
					$A_field_type =			$fieldsvalues->field_type;
					$A_field_options =		$fieldsvalues->field_options;
					$A_field_size =			$fieldsvalues->field_size;
					$A_field_max =			$fieldsvalues->field_max;
					$A_field_default =		$fieldsvalues->field_default;
					$A_field_cost =			$fieldsvalues->field_cost;
					$A_field_required =		$fieldsvalues->field_required;
					$A_multi_position =		$fieldsvalues->multi_position;
					$A_name_position =		$fieldsvalues->name_position;
					$A_field_order =		$fieldsvalues->field_order;
					$rank_select .= "<option>$o</option>";


					$field_HTML .= "<tr class=tr".alternator('1', '2').">";
					$field_HTML .=  "<td align=\"center\">$A_field_rank - $A_field_order &nbsp; &nbsp; </td>";
					$field_HTML .=  "<td align=\"center\">$A_field_label &nbsp; &nbsp; </td>";
					$field_HTML .=  "<td align=\"center\"> $A_field_name &nbsp; &nbsp; </td>";
					$field_HTML .=  "<td align=\"center\"> $A_field_type &nbsp; &nbsp; </td>";
					$field_HTML .=  "<td align=\"center\"><a id=\"activator\" class=\"activator\" href=\"#\"  onClick=\"custompostval('$A_field_id','$list_id');\"> &nbsp;&nbsp;[- modify -]&nbsp;&nbsp;</a><a id=\"activator\" class=\"activator\" href=\"#\" > [- delete -]&nbsp;&nbsp;</a> </td></tr>\n";
			
					$total_cost = ($total_cost + $A_field_cost);
					
					
					}
					}				
					
			

					$field_HTML .=  "</table></form></center><BR><BR>\n";

					return $field_HTML;
					
		}  // end function
		
		
		function customeditfield($fieldid,$listid) {

			$stmt = "SELECT `field_id`, `list_id`, `field_label`, `field_name`, `field_description`, `field_rank`, `field_help`, `field_type`, `field_options`, `field_size`, `field_max`, `field_default`, `field_cost`, `field_required`, `name_position`, `multi_position`, `field_order` FROM `vicidial_lists_fields` WHERE `field_id`='$fieldid' and `list_id` = '$listid' ";					
						
			$fields = $this->asteriskDB->query($stmt);
	  		$ctr = 0;
              foreach($fields->result() as $info){
                  $allfields[$ctr] = $info;
                  $ctr++;
              }

	      return $allfields;
			
		}
		
		function countfields($listid) {
			$stmt = "SELECT * FROM vicidial_lists_fields WHERE list_id='$listid'";
			$rslt = $this->asteriskDB->query($stmt);
			$countfields = $rslt->num_rows;
			
			return $countfields;
			
		}
		
		function customviewmodel($list_id) {
			
			$custom_records_count=0;
				$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
				$rslt = $this->asteriskDB->query($stmt);
				$tablecount_to_print = $rslt->num_rows;
				if ($tablecount_to_print > 0) 
					{$table_exists =	1;}
				if ($table_exists > 0){
					$stmt="SELECT count(*) as countcustom from custom_$list_id;";
					$rslt = $this->asteriskDB->query($stmt);
					$fieldscount_to_print = $rslt->num_rows;
					if ($fieldscount_to_print > 0) {
						$rowx=$rslt->row();
						$custom_records_count =	$rowx->countcustom;
						}
					}
				
		
			$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,
				field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
				
		
			$rslt=$this->asteriskDB->query($stmt);
			$fields_to_print = $rslt->num_rows;
			$fields_list='';
			$o=0;

       		foreach($rslt->result() as $info){
                  $fieldsval[$o] = $info;
                  $o++;
              }	
              
              return $fieldsval;
              
		}   

     public $result=null;	
     function phone(){
          $username = $this->session->userdata('user_name');
          if(!empty($username)){
              $this->asteriskDB->where(array('user_group'=>$username,'phones.active'=>'Y','vicidial_users.active'=>'Y')); 
              $this->asteriskDB->join('phones',"vicidial_users.phone_login = phones.extension",'left');
              $phones = $this->asteriskDB->get('vicidial_users')->result();
              $this->result = $phones;
          }
     }

     function results(){
         return $this->result;
     }

     function getallagents() {
	  $query = $this->asteriskDB->query("SELECT user,full_name FROM vicidial_users WHERE user_level < '9' AND user NOT IN ('VDAD','VDCL') AND full_name NOT RLIKE 'Survey' ORDER BY user");
	  
	  return $query->result();
     }

     function getallactivephones() {
	  $query = $this->asteriskDB->query("SELECT extension,server_ip,dialplan_number FROM phones WHERE active = 'Y' ORDER BY extension");
	  
	  return $query->result();
     }

     
}

?>
