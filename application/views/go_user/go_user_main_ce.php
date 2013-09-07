<?php
############################################################################################
####  Name:             go_user_main_ce.php                                             ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Franco E. Hora                                                  ####
####  Modified by:      Christopher P. Lomuntad - 25042013                              ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base_path = base_url();
?>
<link rel="stylesheet" type="text/css" href="<?=$base_path?>css/go_user/go_user_ce.css" />
<script src="<?=$base_path?>js/jquery-validate/jquery.validate.min.js"></script>
<script src="<?=$base_path?>js/gopagination/gopagination.js" type="text/javascript"></script>
<script src="<?=$base_path?>js/gopagination/gopaginate.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=$base_path?>js/go_user/go_user_ce.js"></script>
<script type="text/javascript" src="<?=$base_path?>js/go_user/go_user_panel1_ce.js"></script>

<!--//layout for datepicker//-->
<!-- <script type="text/javascript" src="<?=$base_path?>js/datepicker/datepicker-modify.js"></script> 
<script type="text/javascript" src="<?=$base_path?>js/datepicker/eye.js"></script>
<script type="text/javascript" src="<?=$base_path?>js/datepicker/utils.js"></script>  
<script type="text/javascript" src="<?=$base_path?>js/datepicker/layout.js?ver=1.0.2"></script> -->

<div id='outbody' class="wrap">
    <div id="icon-user" class="icon32"></div>
    <h2><?=$bannertitle?></h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class="postbox-container" style="width:99%;min-width:1200px;">
                    <!-- List holder-->
                    <!-- <div class="postbox minimum" style="width:99%;min-width:1230px;" > -->
                    <!--<div class="postbox minimum"  >-->
                        <div class="meta-box-sortables ui-sortables">
					<div id="account_info_status" class="postbox">

                        <!-- <div class="handlediv" title="Click to toggle">
                            <br>
                        </div> -->
                        <!--<div class="user-add">
                            <a href="javascript:void(0);" id="call-user-wizard">Add User</a>&nbsp;<?#=img('img/cross.png')?>
                        </div> -->
                       <!--  <div class="copy-user">&nbsp;Copy User</div> 
                        <div class="wizard-call-separator"><span>|</span></div> -->
                        <div class="user-add rightdiv" id="call-user-wizard" style="color:#555555">
                            Add New User<?#=img('img/cross.png')?>
                        </div>
                        <h3 class="hndle">
                            <span><span id="title_bar" /><?=$bannertitle?>&nbsp;Lists</span>
                                    <span class="postbox-title-action"></span>
                            </span>
                        </h3>

                            <div id="tab-nav" class="tab-nav">
                                <ul>
                                    <li><a href="<?=$base_path?>index.php/go_user_ce/index/<?=$user_group?>"><span>Users</span></a></li>
                                    <!-- <li><a href="userstatus"><span>User Status</span></a></li> -->
                                    <?if($user_level > 8){?>
                                    <!-- <li><a href="advancemodifyuser"><span>Advance</span></a></li> -->
                                    <?}?>
                                </ul>

                            </div>
                            </div> 	
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="overlay"></div>
<div class="overlay-leadinfo"></div>
    <div class="wizard-box">
        <div class='add-close'>&nbsp;</div>
        <br class="clear"/>
        <div class='wizard-breadcrumb'>
             <div class="wizard-title"><strong>Users Wizard &raquo; Add New User</strong></div>
             <?=img("img/step2of2-navigation-small.png")?>
             <br class="clear"/>
        </div>
        <div class="wizard-content">
             <div class="wizard-content-left"><?=img("img/step2-trans.png")?></div>
             <div class="wizard-content-right">
                  <?=form_open(null,'id="add-new-user"')?>
                        <div class="boxleftside">
                             <span><strong>User Group:</strong></span>
                        </div>
                        <div class="boxrightside"> 
                             <?php #if($user_level < 9){?>
                                  <!-- <span><?#=$username?></span> -->
                             <?php #} else {
                                       $attr = 'id="accountNum" ';
                                       echo form_dropdown('accountNum',$accnt_list,'AGENTS',$attr);
                                       echo "<script>var go_accounts = $foradd</script>";
                                   #}
                             ?>
                        </div><br class="clear"/>
                        <!-- <div class="boxleftside">
                            <span>User Group</span>
                        </div>
                        <div class="boxrightside">
                           <?php
                                #if($user_level < 9){
                           ?> 
                               <span><?#=$userfulname?></span>
                               <?#=form_hidden('hidcompany',$userfulname)?>
                           <?php
                                #}else{
                                #      echo "<span id='comp_name'>&nbsp;</span>";
                                #      echo form_hidden('hidcompany',null);
                                #}
                           ?>
                        </div><br class="clear"/> -->
                        <div class="boxleftside">
                           <span><strong>Current Users:</strong></span>
                        </div>
                        <div class="boxrightside">
                           <?php #if($user_level < 9){?> 
                                    <span><?=$a2wizard?></span>
                                    <?=form_hidden(array('hidcount'=>$a2wizard))?>
                           <?php #}else{
                                 #     echo "<span id='count'>&nbsp;</span>";
                                 #     echo "<input type='hidden' value='' name='hidcount' id='hidcount'>";
                                 #}
                           ?>
                        </div><br class="clear"/>
                        <div class="boxleftside">
                            <span><strong>Additional Seat(s):</strong></span>
                        </div>
                        <div class="boxrightside"> 
                            <?php
                               $usercount = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,
                                                  11=>11,12=>12,13=>13,14=>14,15=>15,16=>16,17=>17,18=>18,19=>19,20=>20);
                            ?>
                            <?=form_dropdown('txtSeats',$usercount,1)?>
                            <?=form_hidden('skip','skip')?>
                        </div><br class="clear"/>
                  <?=form_close();?>
             </div>
             <br class="clear"/>
        </div>
        <br class="clear"/>
        <div class="wizard-action">
              <a onclick="cancelWizard(this)">Cancel</a>&nbsp;|&nbsp;
              <a onclick="next()">Next</a>
        </div>
        <br class="clear"/>
    </div>
<div class="overlay-modify">
</div>
    <div class="wizard-box-modify">
        <div class='add-close-modify'>&nbsp;</div>
        <br class="clear"/>
        <div class="box-header"><center><strong style="font-size:16px;">Modify User : admin</strong></center></div><br class="clear"/>
       
        <?=form_open(null,'id="form" class="edit-user"');?>
            <div class="boxleftside boxleftside-modify">Agent ID:</div>
            <div class="boxrightside boxrightside-modify">&nbsp;<input type="hidden" id="users_id" name="users_id"><input type="hidden" id="vicidial_user_id" name="vicidial_user_id"></div><br class="clear">
            <div class="boxleftside boxleftside-modify">Password:</div>
            <div class="boxrightside boxrightside-modify"><?=form_input('pass',null,'id="pass"')?></div><br class="clear">
            <div class="boxleftside boxleftside-modify">Full Name:</div>
            <div class="boxrightside boxrightside-modify"><?=form_input('full_name',null,'id="full_name"')?></div><br class="clear">
            <div class="boxleftside boxleftside-modify">Phone Login:</div>
            <div class="boxrightside boxrightside-modify"><?=form_input('phone_login',null,'id="phone_login"')?></div><br class="clear">
            <div class="boxleftside boxleftside-modify">Phone pass:</div>
            <div class="boxrightside boxrightside-modify"><?=form_input('phone_pass',null,'id="phone_pass"')?></div><br class="clear">
            <div class="boxleftside boxleftside-modify">
                 User Group:
            </div>
            <div class="boxrightside boxrightside-modify"> 
                <?php 
                     $attr = 'id="user_group"';
                     echo form_dropdown('user_group',$accnt_list,null,$attr);
                ?>
            </div><br class="clear"/>
            <div class="boxleftside boxleftside-modify">Active:</div>
            <div class="boxrightside boxrightside-modify">
                 <?=form_dropdown('active',array('Y'=>'Yes','N'=>'No'),null,'id="active"')?>
            </div><br class="clear">
            <div class="boxleftside boxleftside-modify">Hotkeys:</div>
            <div class="boxrightside boxrightside-modify">
                 <?=form_dropdown('hotkeys_active',array('1'=>'Yes','0'=>'No'),null,'id="hotkeys_active"')?>
            </div>
            <div class="boxleftside boxleftside-modify">User Level:</div>
            <div class="boxrightside boxrightside-modify">
                 <?$levels = range(0,9);unset($levels[0])?>
                 <?=form_dropdown('user_level',$levels,null,'id="user_level"')?>
            </div>
            <!-- <div class="boxleftside"><a class="useraccess-remote" id="advance-toggle">Advance [+] </a></div><br class="clear"/>
            <?#foreach($useraccess as $group => $access):?>
                <br class="clear"/><div class="boxleftside boxleftside-modify <?=substr($group,0,strpos($group," "))?>" style="padding:10px;"><?=$group?></div><br class="boxleftside-modify clear"/>
                <?php
                      #foreach($access as $cols => $name){
                      #    echo "<div class='boxleftside boxleftside-modify ".substr($group,0,strpos($group," "))."'>$name:</div>";
                      #    $tickbox = array('value'=>1,'name'=>$cols,'id'=>$cols);
                      #    echo "<div class='boxrightside boxrightside-modify boxrightside-modify-leftalign ".substr($group,0,strpos($group," "))."'>".form_checkbox($tickbox)."</div>";
                      #}
                ?>
            <?#endforeach;?> -->
        <?=form_close();?> 
        <br class="clear"/>
        <div class="quick-action-set">
            <a id="actions-update">Update</a> 
        </div>
        <br class="clear"/>
    </div>
<div class="user-cornerall overlay-type">
    <div class='type-close'>&nbsp;</div>
    <br class="clear"/>
    <div class='wizard-breadcrumb'>
       <div class="wizard-title"><strong>Users Wizard </strong></div>
          <?=img("img/step1of2-navigation-small.png")?>
          <br class="clear"/>
       </div>
       <div class="wizard-content">
             <div class="wizard-content-left"><?=img("img/step1-trans.png")?></div>
             <div class="wizard-content-right">
                  <div class="boxleftside"><strong>Wizard Type:</strong></div>
                  <div class="boxrightside"><?=form_dropdown('wizard_type',array('add'=>'Add New User','copy'=>'Copy User'),'add','id="wizard_type"')?></div>
             </div>
       </div>
        <br class="clear"/>
        <div class="wizard-action">
              <a onclick="showtype()">Next</a>
        </div>
        <br class="clear"/>
</div>
<div class="overlay-info"></div>
    <div class="user-cornerall wizard-copy">
        <div class="copy-close">&nbsp;</div>
        <br class="clear"/>
        <div class='wizard-breadcrumb'>
             <div class="wizard-title"><strong>Users Wizard &raquo; Copy User</strong></div>
             <?=img("img/step2of2-navigation-small.png")?>
             <br class="clear"/>
        </div>
        <div class="wizard-content">
             <div class="wizard-content-left"><?=img("img/step2-trans.png")?></div>
             <div class="wizard-content-right">
                <form id="copy-form">
                   <div class="copy-boxleftside"><strong>Users:</strong></div><div class="copy-boxrightside"><?=form_dropdown('source_user',$users,null,'id="source_user"')?></div>
                   <div class="copy-boxleftside"><strong>User ID:</strong></div><div class="copy-boxrightside"><?=form_input('user',null,'id="user"')?></div>
                   <div class="copy-boxleftside"><strong>Password:</strong></div><div class="copy-boxrightside"><?=form_input('pass',null,'id="pass"')?></div>
                   <div class="copy-boxleftside"><strong>Fullname:</strong></div><div class="copy-boxrightside"><?=form_input('full_name',null,'id="full_name"')?></div>
                   <br class="clear"/>
                   <br class="clear"/>
                </form>
                <br class="clear"/>
             </div>
         </div>
         <br class="clear"/>
        <div class="wizard-action">
          <a onclick="cancelWizard(this)">Cancel</a>&nbsp;|&nbsp;<a id="copy-proceed">Submit</a>
        </div>
    </div>
    <div class="user-cornerall wizard-box-info">
         <div class='add-close-info'>&nbsp;</div>
         <div class="datepicker-container">
              <div class="hovermenu" id="widgetField">
                    <div id="widgetDate"><span id="user-stat-<?=$userinfo[0]->user?>"><? echo date('Y-m-d'); ?> to <? echo date('Y-m-d'); ?></span></div>
                    <a href="javascript:void(0);" id="daterange">Select date range</a>
              </div>
              <div id="widgetCalendar"></div> <!--//calendar layout//-->
         </div>
         <br class="clear"/>
         <!-- <div class="adv-user-status-client">
             <div class="adv-toggle"></div>
             <h3>User Status</h3>
             <div class="adv-user-detail user-corners">
                  <div class="userstatus-display">
                  <?Php
                       #echo '<strong></strong><br/>';
                       #echo "<div class='leftside'>Agent logged in at server</div><div class='rightside' id='server_ip'> </div><br/>";
                       #echo "<div class='leftside'>In session</div><div class='rightside' id='conf_exten'> </div><br/>";
                       #echo "<div class='leftside'>From phone</div><div class='rightside' id='extension'> </div><br/>";
                       #echo "<div class='leftside'>Agent is in campaign</div><div class='rightside' id='campaign_id'> </div><br/>";
                       #echo "<div class='leftside'>Status</div><div class='rightside' id='status'> </div><br/>";
                       #echo "<div class='leftside'>Hang-up last call at</div><div class='rightside' id='last_call_finish'> </div><br/>";
                       #echo "<div class='leftside'>Closer groups</div><div class='rightside' id='close_campaigns'></div><br/>";
                  ?> 
                  </div>
                  <div id="<?#=$userinfo[0]->user?>" class="emergency-container"><a href="javascript:void(0);" class="emergency">Force Logout</a></div>
                  <br class="clear"/>
             </div>
         </div> -->
         <div class="agent-talk-time user-cornerall">
               <strong>Agent Talk Time and Status </strong>
               <br class="clear"/>
                  <div class="userstatus-display">
                  <?Php
                       echo '<br/><strong></strong><br/>';
                       #echo "<div class='leftside'>Agent logged in at server</div><div class='rightside' id='server_ip'> </div><br/>";
                       #echo "<div class='leftside'>In session</div><div class='rightside' id='conf_exten'> </div><br/>";
                       echo "<div class='leftside'>Phone extension:</div><div class='rightside' id='extension'> </div><br/>";
                       echo "<div class='leftside'>Agent is in campaign:</div><div class='rightside' id='campaign_id'> </div><br/>";
                       echo "<div class='leftside'>Status:</div><div class='rightside' id='status'> </div><br/>";
                       echo "<div class='leftside'>Hang-up last call at:</div><div class='rightside' id='last_call_finish'> </div><br/>";
                       echo "<div class='leftside'>Closer groups:</div><div class='rightside' id='close_campaigns'></div><br/>";
                  ?> 
                  </div>
               <br class="clear"/>
               <div class="time-status-tbl">
                  <div class="time-stat-hdr">
                      <div class="cols">Status</div>
                      <div class="cols">Count</div>
                      <div class="cols">HH:MM:SS</div>
                  </div>
                  <br class="clear"/> 
                  <div class="time-stat-content"></div>
               </div>
         </div>
         <br class="clear"/>
         <div class="user-stats">
              <span>Other Statics Info</span><br class="clear"/>
              <label><a id="agentloginlogout">Agent Login/Logout</a></label>
              <label><a id="outboundthistime">Outbound Calls This Time</a></label>
              <label><a id="inboundthistime">Inbound/Closer Calls</a></label>
              <label><a id="agentactivity">Agent Activity</a></label>
              <label><a id="recordingthistime">Recording</a></label>
              <label><a id="manualoutbound">Manual Outbound</a></label>
              <label><a id="leadsearchthistime">Lead Searches</a></label>
         </div>
         <br class="clear"/>
         <div id="<?=$userinfo[0]->user?>" class="emergency-container"><a href="javascript:void(0);" class="emergency">Force Logout</a></div>
         <br class="clear"/>
    </div>
         <div class="agent-loginlogout-time user-cornerall">
              <strong>Agent Login/Logout Time</strong><a class="userstat-closer">Close</a>
              <br class="clear"/>
              <br class="clear"/>
              <div class="time-loginlogout-tbl">
                   <div class="agent-loginlogout-hdr">
                        <div class="cols">Event</div>
                        <div class="cols">Date</div>
                        <div class="cols">Campaign</div>
                        <div class="cols">Group</div>
                        <div class="cols">HH:MM:SS</div>
                        <div class="cols">Session</div>
                        <div class="cols">Server</div>
                        <div class="cols">Phone</div>
                        <div class="cols">Computer</div>
                   </div>
                   <br class="clear"/> 
                   <div class="time-loginlogout-content"></div>
                   <div class="pager-container agent-loginlogout-pager">
                       <div class="totaltime">
                            <div class="labelcols">Total Calls</div>
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">dispo</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="spacercols">&nbsp;</div> 
                            <br class="clear"/>
                       </div>
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;per page</span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"loginlogout-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"loginlogout-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('loginlogout-currpage',1,'id="loginlogout-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='loginlogout-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"loginlogout-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"loginlogout-lastpage","src"=>"img/last.gif"))?>
                       </span>
                  </div> 
              </div>
         </div>
         <div class="agent-outbound-thistime user-cornerall">
              <strong>Outbound Calls For This Time Period(25 record limit)</strong><a class="userstat-closer">Close</a>
              <br class="clear"/>
              <br class="clear"/>
              <div class="outbound-thistime-tbl">
                   <div class="outbound-thistime-hdr">
                        <div class="cols">Date/Time</div>
                        <div class="cols">Length</div>
                        <div class="cols">Status</div>
                        <div class="cols">Phone</div>
                        <div class="cols">Campaign</div>
                        <div class="cols">Group</div>
                        <div class="cols">List</div>
                        <div class="cols">Lead</div>
                        <div class="cols">Hangup Reason</div>
                   </div>
                   <br class="clear"/> 
                   <div class="outbound-thistime-content"></div>
                   <!-- <div class="outbound-paginator"></div>  -->
                   <div class="pager-container outbound-thistime-pager">
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;per page</span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"outbound-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"outbound-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('outbound-currpage',1,'id="outbound-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='outbound-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"outbound-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"outbound-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div> 
              </div>
         </div>
         <div class="agent-inbound-thistime user-cornerall">
              <strong>Inbound/Closer Calls For This Time Period(25 record limit)</strong><a class="userstat-closer">Close</a>
              <br class="clear"/>
              <br class="clear"/>
              <div class="inbound-thistime-tbl">
                  <div class="inbound-thistime-hdr">
                       <div class="cols">Date/Time</div>
                       <div class="cols">Length</div>
                       <div class="cols">Status</div>
                       <div class="cols">Phone</div>
                       <div class="cols">Campaign</div>
                       <div class="cols">Wait(s)</div>
                       <div class="cols">Agent(s)</div>
                       <div class="cols">List</div>
                       <div class="cols">Lead</div>
                       <div class="cols">Hangup Reason</div>
                  </div>
                  <br class="clear"/> 
                  <div class="inbound-thistime-content"></div>
                   <div class="pager-container inbound-thistime-pager">
                       <div class="totaltime">
                            <div class="labelcols">Total Calls</div>
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="spacercols">&nbsp;</div> 
                            <br class="clear"/>
                       </div>
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;per page</span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"inbound-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"inbound-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('inbound-currpage',1,'id="inbound-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='inbound-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"inbound-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"inbound-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div>
              </div>
         </div>
         <div class="agent-activity-thistime user-cornerall">
              <strong>Agent Activity For This Time Period(25 limit)</strong><a class="userstat-closer">Close</a>
              <br class="clear"/>
              <br class="clear"/>
              <div class="agent-activity-tbl">
                   <div class="agentactivity-thistime-hdr">
                        <div class="cols">Date/Time</div>
                        <div class="cols">Pause</div>
                        <div class="cols">Wait</div>
                        <div class="cols">Talk</div>
                        <div class="cols">Dispo</div>
                        <div class="cols">Dead</div>
                        <div class="cols">Customer</div>
                        <div class="cols">Status</div>
                        <div class="cols">Lead</div>
                        <div class="cols">Campaign</div>
                        <div class="cols">Pause Code</div>
                   </div>
                   <br class="clear"/>
                   <div class="agent-activity-content"></div>
                   <div class="pager-container agent-activity-pager">
                       <div class="totaltime">
                            <div class="labelcols">Total Calls</div>
                            <div class="totalcols totalpause">&nbsp;</div> 
                            <div class="totalcols totalwait">&nbsp;</div> 
                            <div class="totalcols totaltalk">&nbsp;</div> 
                            <div class="totalcols totaldispo">&nbsp;</div> 
                            <div class="totalcols totaldead">&nbsp;</div> 
                            <div class="totalcols totalcustomer">&nbsp;</div> 
                            <div class="spacercols">&nbsp;</div> 
                            <br class="clear"/>
                       </div>
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;per page</span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"agentactivity-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"agentactivity-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('agentactivity-currpage',1,'id="agentactivity-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='agentactivity-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"agentactivity-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"agentactivity-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div>
              </div>
         </div>
         <div class="agent-recording-thistime user-cornerall">
              <strong>Recording For This Time Period(25 record limit)</strong><a class="userstat-closer">Close</a>
              <br class="clear"/>
              <br class="clear"/>
              <div class="recording-thistime-tbl">
                   <div class="recording-thistime-hdr">
                        <div class="cols">Lead</div>
                        <div class="cols">Date/Time</div>
                        <div class="cols">Seconds</div>
                        <div class="cols">RECID</div>
                        <div class="cols">Filename</div>
                        <div class="cols">Location</div>
                   </div>
                   <br class="clear"/> 
                   <div class="recording-thistime-content"></div>
                   <div class="pager-container recording-thistime-pager">
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;per page</span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"recording-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"recording-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('recording-currpage',1,'id="recording-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='recording-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"recording-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"recording-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div>
              </div>
         </div>
         <div class="agent-manualoutbound-thistime user-cornerall">
              <strong>Manual Outbound Calls For This Time Period(25 record limit)</strong><a class="userstat-closer">Close</a>
              <br class="clear"/>
              <br class="clear"/>
              <div class="manualoutbound-thistime-tbl">
                   <div class="manualoutbound-thistime-hdr">
                        <div class="cols">Date/Time</div>
                        <div class="cols">Call Type</div>
                        <div class="cols">Server</div>
                        <div class="cols">Phone</div>
                        <!-- <div class="cols">Dialed</div> -->
                        <div class="cols">Lead</div>
                        <div class="cols">Caller Id</div>
                        <!-- <div class="cols">Alias</div>
                        <div class="cols">Preset</div>
                        <div class="cols">C3HU</div> -->
                   </div>
                   <br class="clear"/> 
                   <div class="manualoutbound-thistime-content"></div>
                   <div class="pager-container manualoutbound-thistime-pager">
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;per page</span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"manualoutbound-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"manualoutbound-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('manualoutbound-currpage',1,'id="manualoutbound-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='manualoutbound-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"manualoutbound-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"manualoutbound-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div>
              </div>
         </div>
         <div class="agent-leadsearch-thistime user-cornerall">
              <strong>Lead Searches For This Time Period(25 record limit)</strong><a class="userstat-closer">Close</a>
              <br class="clear"/>
              <br class="clear"/>
              <div class="leadsearch-thistime-tbl">
                  <div class="leadsearch-thistime-hdr">
                       <div class="cols">Date/Time</div>
                       <div class="cols">Type</div>
                       <div class="cols">Results</div>
                       <div class="cols">Sec</div>
                       <div class="cols">Query</div>
                  </div>
                  <br class="clear"/> 
                  <div class="leadsearch-thistime-content"></div>
              </div>
         </div>
         <div class="leadinfo user-cornerall">
              <strong>Lead Information</strong><a class="userstat-closer">Close</a><br/>
              <div class="leadinfolabel">Lead ID</div>
              <div class="leadinfocont lead_id"></div>
              <div class="leadinfolabel">List ID</div>
              <div class="leadinfocont list_id"></div>
              <div class="leadinfolabel">Address</div>
              <div class="leadinfocont address1"></div>
              <div class="leadinfolabel">Phone Code</div>
              <div class="leadinfocont phone_code"></div>
              <div class="leadinfolabel">Phone Number</div>
              <div class="leadinfocont phone_number"></div>
              <div class="leadinfolabel">City</div>
              <div class="leadinfocont city"></div>
              <div class="leadinfolabel">State</div>
              <div class="leadinfocont state"></div>
              <div class="leadinfolabel">Postal Code</div>
              <div class="leadinfocont postal_code"></div>
              <div class="leadinfolabel">Comment</div>
              <div class="leadinfocont comment"></div>
              <br class="clear"/>
         </div>
</div> <!-- wpwrap -->
