<?php
####################################################################################################
####  Name:             	go_usergroup_wizard.php                                             ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Originated by:        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
$NOW = date('Y-m-d');
?>
<style type="text/css">
#usergroupTable input,
#usergroupTable select,
#usergroupTable textarea {
/*	border: 1px solid #999; */
}

#campTable td{
	padding:0px 5px 0px 5px;
}

#saveButtons{
	float:right;
	width:150px;
	text-align:right;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

#saveButtons span{
	text-align:center;
	color:#7A9E22;
	cursor:pointer;
	width:40px;
}

#saveButtons span:hover{
	font-weight:bold;
}

::-webkit-input-placeholder { /* WebKit browsers */
    color:    #999;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    color:    #999;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
    color:    #999;
}
:-ms-input-placeholder { /* Internet Explorer 10+ */
    color:    #999;
}

.permissions{background-color:#EFFBEF;}
</style>

<script>



$(function()
{
	$('#user_group').keyup(function(e)
	{
		if ($(this).val().length > 2)
		{
			$('#group_name').css('border','solid 1px #999');
			$('#user_group').css('border','solid 1px #999');
		
			if(e.which === 32) 
				return false;
			
			$('#aloading').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
			$('#aloading').load('<? echo $base; ?>index.php/go_usergroup_ce/go_check_usergroup/'+$(this).val());
		} else {
			$('#aloading').html("<small style=\"color:red;\">Minimum of 3 characters.</small>");
		}
	});
	
	$('#user_group,#group_name').keydown(function(event)
	{
		//console.log(event.keyCode);
		if (event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
			|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
			|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
			return false;
		
		if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
			return false;
		
		$(this).css('border','solid 1px #999');
		if ($(this).attr('id')=='user_group')
		{
			if (event.keyCode == 32)
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		}
	});
	
	$('#submit').click(function()
	{
		var isEmpty = 0;
		if ($('#user_group').val() === "" || $('#user_group').val().length < 3)
		{
			$('#user_group').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#group_name').val() === "")
		{
			$('#group_name').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			alert("User Group Not Available.");
			isEmpty = 1;
		}
		
		if (!isEmpty)
		{
     
			var items = "",$permissions={};
                        $(".usergroup-input").each(function(){
                            items += $(this).attr("name") + "=" + $(this).val() + "&"; 
                        });

                        $(".permissions").each(function(){
                             var $container = {};
                             $(this).children("td:nth-child(2)").children("input.permission-box").each(function(){
                                  $container[$(this).attr("name")] = (($(this).prop("checked")===true)?$(this).val():"N");
                             });
                             if($(this).children("td:nth-child(1)").text() !== "Group Template:" && $(this).children("td:nth-child(1)").text() !== "Group Level:"){
                                 $permissions[$(this).children("td:nth-child(1)").text().toLowerCase().replace("&","").replace(":","").replace(/ /g,"")] = $container;
                             }
                        });

			$.post("<?=$base?>index.php/go_usergroup_ce/go_usergroup_wizard", { items: items, action: "add_new_usergroup", permiso : JSON.stringify($permissions),group_level : $("#group_level").val() },
			function(data){
				if (data=="SUCCESS")
				{
					alert(data);
				
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				}
	
			});
		}
	});

        $("#template").change(function(){
            getpermissions($("#template").val());
        });

        // setting new
        getpermissions($("#template").val());
});
</script>
<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1-nav-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;">User Group Wizard &raquo; Add New User Group</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
				<form id="usergroupForm" method="POST">
                <table id="usergroupTable" style="width:100%;">
                    <tr>
                        <td style="text-align:right;width:10%;height:10px;font-weight:bold;">
                        User Group:
                        </td>
                        <td>
                        <?=form_input('user_group',null,'id="user_group" maxlength="20" size="15" class="usergroup-input"') ?>
						<span id="aloading"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:10%;height:10px;font-weight:bold;">
                        Group Name:
                        </td>
                        <td>
                        <?=form_input('group_name',null,'id="group_name" maxlength="40" size="40" class="usergroup-input"') ?>
                        </td>
                    </tr>
                    <tr class="permissions">
                         <td style="text-align:right;width:10%;height:10px;font-weight:bold;">Group Template:</td>
                         <td>
                         <?php 
                              if(!empty($access)){
                                   foreach($access as $permit){
                                       $accesses[$permit->id] = $permit->user_group;
                                   }
                              }
                              echo form_dropdown("template",$accesses,6,"id='template'");
                         ?>
                         </td>
                    </tr>
                    <tr class="permissions">
                         <td style="text-align:right;width:10%;height:10px;font-weight:bold;">Group Level:</td>
                         <td>  
                            <?php
                                $levels = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
                                echo form_dropdown("group_level",$levels,null,"id='group_level'");
                            ?>
                         </td>
                    </tr>
                    <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Dashboard:</td>
                       <td>
                            <?php
                                 echo form_checkbox('dashboard_todays_status',"Y",null,"id ='dashboard_todays_status' class='permission-box'")." Todays Status " .
                                      form_checkbox('dashboard_account_info',"Y",null,"id='dashboard_account_info' class='permission-box'") . " Account Info " . 
                                      form_checkbox('dashboard_agent_lead_status',"Y",null,"id='dashboard_agent_lead_status' class='permission-box'"). " Agent Lead Status ".
                                      form_checkbox('dashboard_server_settings',"Y",null,"id='dashboard_server_settings' class='permission-box'") . " Server Settings <br/>" .
                                      form_checkbox('dashboard_go_analytics',"Y",null,"id='dashboard_go_analytics' class='permission-box'") . " Go Analytics " .
                                      form_checkbox('dashboard_system_service',"Y",null,"id='dashboard_system_service' class='permission-box'") . " System Service ";
                            ?>
                       </td> 
                   </tr>
                    <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">User:</td>
                       <td>
                            <?php
                                 echo form_checkbox('user_create',"C",null,"id='user_create' class='permission-box'")." Create " .
                                      form_checkbox('user_read',"R",null,"id='user_read' class='permission-box'") . " Read " . 
                                      form_checkbox('user_update',"U",null,"id='user_update' class='permission-box'"). " Update ".
                                      form_checkbox('user_delete',"D",null,"id='user_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
                    </tr>
                     <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Campaign:</td>
                       <td>
                            <?php
                                 echo form_checkbox('campaign_create',"C",null,"id='campaign_create' class='permission-box'")." Create " .
                                      form_checkbox('campaign_read',"R",null,"id='campaign_read' class='permission-box'") . " Read " . 
                                      form_checkbox('campaign_update',"U",null,"id='campaign_update' class='permission-box'"). " Update ".
                                      form_checkbox('campaign_delete',"D",null,"id='campaign_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
                    </tr>
                     <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">List:</td>
                       <td>
                            <?php
                                 echo form_checkbox('list_create',"C",null,"id='list_create' class='permission-box'")." Create " .
                                      form_checkbox('list_read',"R",null,"id='list_read' class='permission-box'") . " Read " . 
                                      form_checkbox('list_update',"U",null,"id='list_update' class='permission-box'"). " Update ".
                                      form_checkbox('list_delete',"D",null,"id='list_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
                    </tr>
                     <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Custom Fields:</td>
                       <td>
                            <?php
                                 echo form_checkbox('customfields_create',"C",null,"id='customfields_create' class='permission-box'")." Create " .
                                      form_checkbox('customfields_read',"R",null,"id='customfields_read' class='permission-box'") . " Read " . 
                                      form_checkbox('customfields_update',"U",null,"id='customfields_update' class='permission-box'"). " Update ".
                                      form_checkbox('customfields_delete',"D",null,"id='customfields_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
                    </tr>
                     <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Load Leads:</td>
                       <td>
                            <?php
                                 echo form_checkbox('loadleads_read',"R",null,"id='loadleads_read' class='permission-box'") . " Read ";
                            ?>
                       </td> 
                    </tr>
                      <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Script:</td>
                       <td>
                            <?php
                                 echo form_checkbox('script_create',"C",null,"id='script_create' class='permission-box'")." Create " .
                                      form_checkbox('script_read',"R",null,"id='script_read' class='permission-box'") . " Read " . 
                                      form_checkbox('script_update',"U",null,"id='script_update' class='permission-box'"). " Update ".
                                      form_checkbox('script_delete',"D",null,"id='script_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
                    </tr> 
                     <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Inbound:</td>
                       <td>
                            <?php
                                 echo form_checkbox('inbound_create',"C",null,"id='inbound_create' class='permission-box'")." Create " .
                                      form_checkbox('inbound_read',"R",null,"id='inbound_read' class='permission-box'") . " Read " . 
                                      form_checkbox('inbound_update',"U",null,"id='inbound_update' class='permission-box'"). " Update ".
                                      form_checkbox('inbound_delete',"D",null,"id='inbound_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
                    </tr>
                      <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Voice Files:</td>
                       <td>
                            <?php
                                 echo form_checkbox('voicefile_upload',"C",null,"id='voicefile_upload' class='permission-box'")." Upload " .
                                      form_checkbox('voicefile_delete',"D",null,"id='voicefile_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
                    </tr>
                    <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Reports & Analytics:</td>
                       <td>
                            <?php
                                 echo form_checkbox('reportsanalytics_statistical_report',"Y",null,"id='reportsanalytics_statistical_report' class='permission-box'")." Statistical Report " .
                                      form_checkbox('reportsanalytics_agent_time_detail',"Y",null,"id='reportsanalytics_agent_time_detail' class='permission-box'") . " Agent Time Detail " . 
                                      form_checkbox('reportsanalytics_agent_performance_detail',"Y",null,"id='reportsanalytics_agent_performance_detail' class='permission-box'"). " Agent Performance Detail <br/>".
                                      form_checkbox('reportsanalytics_dial_status_summary',"Y",null,"id='reportsanalytics_dial_status_summary' class='permission-box'") . " Dial Status Summary  " .
                                      form_checkbox('reportsanalytics_sales_per_agent',"Y",null,"id='reportsanalytics_sales_per_agent' class='permission-box'") . " Sales Per Agent " .
                                      form_checkbox('reportsanalytics_sales_tracker',"Y",null,"id='reportsanalytics_sales_tracker' class='permission-box'") . " Sales Tracker <br/>" .
                                      form_checkbox('reportsanalytics_inbound_call_report',"Y",null,"id='reportsanalytics_inbound_call_report' class='permission-box'") . " Inbound Call Report " .
                                      form_checkbox('reportsanalytics_export_call_report',"Y",null,"id='reportsanalytics_export_call_report' class='permission-box'") . " Export Call Report " .
                                      form_checkbox('reportsanalytics_dashboard',"Y",null,"id='reportsanalytics_dashboard' class='permission-box'") . " Dashboard <br/>" .
                                      form_checkbox('reportsanalytics_advance_script',"Y",null,"id='reportsanalytics_advance_script' class='permission-box'") . " Advance Script " 
                                      ;
                            ?>
                       </td> 
                   </tr>
                    <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Recordings:</td>
                       <td>
                            <?php
                                  echo  form_checkbox('recordings_display',"Y",null,"id='recordings_display' class='permission-box'") . " Allowed Recording View ";
                            ?>
                       </td> 
                   </tr>
                    <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Support:</td>
                       <td>
                            <?php
                                  echo  form_checkbox('support_display',"Y",null,"id='support_display' class='permission-box'") . " Allowed Support ";
                            ?>
                       </td> 
                   </tr>
                 </table>
		</form>
            </span>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="submit" style="white-space: nowrap;">Submit</span></span>
