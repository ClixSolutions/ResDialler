<?php
############################################################################################
####  Name:             go_ingroup.php                                                  ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  License:          AGPLv2                                                          ####
############################################################################################

header( 'Cache-Control: no-store, no-cache' );
$base = base_url();
?>

<script>
$(document).ready(function(){

	$(".toolTip").tipTip();
	$("#menuid").val('');
	$("#menuname").val('');
	$("#custom_dialplan").val('');
	$("#enter_filename").val('');
	$("#id_number_filename").val('');
	$("#confirm_filename").val('');
	$("#hangup_audio").val('');
	$("#extension").val('');
	$("#route").val($("#route option:first").val());
	$("#diventer_filename").text('');
	$("#divid_number_filename").text('');
	$("#divconfirm_filename").text('');
	$("#divhangup_audio").text('');
	$("#divvoicemail_box").text('');
	var $tabs = $('#tabs').tabs();
	var $tabvalsel = $('#tabvalsel').val();				
	
	$( "#tabs" ).tabs();
	
	$('#atab1').click(function () {
	$("#t1").css("display", "block");
    	$("#t2").css("display", "none");
    	$("#t3").css("display", "none");
    	
	});
	
	$('#atab2').click(function () {
   	
    	$("#t1").css("display", "none");
    	$("#t2").css("display", "block");
    	$("#t3").css("display", "none");
    	
	});
	
	$('#atab3').click(function () {

    	$("#t1").css("display", "none");
    	$("#t2").css("display", "none");
    	$("#t3").css("display", "block");
    	
	});
	
	$("#submitCallMenu").click(function()
	{
		var isEmpty = 0;
		if ($("#menu_id").val().length > 0)
		{
			if ($('#err_menu_id').html().match(/Not Available/))
			{
				alert("Menu ID Not Available.");
				isEmpty = 1;
			}
			
			if (!isEmpty)
			{
				alert('Call Menu ID '+$("#menu_id").val()+' Created');
				$("#go_callmenufrm").submit();
			}
		} else {
			alert('Menu ID should NOT be empty.');
		}
	});
	
	$("#edid_route").change(function() {
		switch ($(this).val()) {
			case "AGENT":
				$(".didAgentGroup").show();
				$(".didExtensionGroup,.didVoicemailGroup,.didPhoneGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "EXTEN":
				$(".didExtensionGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didPhoneGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "VOICEMAIL":
				$(".didVoicemailGroup").show();
				$(".didAgentGroup,.didExtensionGroup,.didPhoneGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "PHONE":
				$(".didPhoneGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didExtensionGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "IN_GROUP":
				$(".didInboundGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didPhoneGroup,.didExtensionGroup,.didCallMenuGroup").hide();
				break;
			case "CALLMENU":
				$(".didCallMenuGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didPhoneGroup,.didInboundGroup,.didExtensionGroup").hide();
				break;
		}
		
		if ($(".didAdvanceSettings").is(":visible")) {
			showAdvanceOptions($(this).val(),'show');
		}
	});
	
		$("#efilter_inbound_number").change(function() {
			var route = $("#efilter_action").val();
			switch ($(this).val()) {
				case "GROUP":
					$(".filterGroup").show();
					$(".filterAction").show();
					$(".filterURL").hide();
					showRouteOptions(route,'Filter');
					break;
				case "URL":
					$(".filterURL").show();
					$(".filterAction").show();
					$(".filterGroup").hide();
					showRouteOptions(route,'Filter');
					break;
				default:
					$(".filterGroup,.filterURL,.filterAction").hide();
					showRouteOptions('DISABLE','Filter');
			}
		});
	
	$("#submitCallMenuEdit").click(function()
	{
		if ($("#menuvals").val().length > 0)
		{
			editcallmenupost($("#menuvals").val());
		} else {
			alert('Menu ID should NOT be empty.');
		}
	});
	
	$("#advDIDLink").click(function()
	{
		var route = $('#edid_route').val();
		if ($(".didAdvanceSettings").is(":hidden"))
		{
			$('#advDIDLinkCross').html('\[-\]');
			$('.didAdvanceSettings').show();
			showAdvanceOptions(route,'show');
		} else {
			$('#advDIDLinkCross').html('\[+\]');
			$('.didAdvanceSettings').hide();
			showAdvanceOptions(route,'hide');
		}
	});
	
	$("#nextStepCallMenu").click(function()
	{
		var isEmpty = 0;
		if ($("#menu_id").val().length > 0)
		{
			if ($('#err_menu_id').html().match(/Not Available/))
			{
				alert("Menu ID Not Available.");
				isEmpty = 1;
			}
			
			if (!isEmpty)
			{
				$("#ivrMenuStep1").hide();
				$("#nextStepCallMenu").hide();
				$("#ivrMenuStep2").show();
				$("#backCallMenu").show();
				$("#submitCallMenu").show();
				$(".divider").show();
				$("#wizardHeader").html("Call Menu Wizard » Create New Call Menu » Call Menu Options");
				$("#small_step_number > img").attr('src','<?=$base?>img/step2of2-navigation-small.png');
				$("#step_number > img").attr('src','<?=$base?>img/step2-trans.png')
				$("#calladdlist").css('width','70%');
				$("#calladdlist").css('left','10%');
			}
		} else {
			alert('Menu ID should NOT be empty.');
		}
	});
	
	$("#backCallMenu").click(function()
	{
		$("#ivrMenuStep1").show();
		$("#nextStepCallMenu").show();
		$("#ivrMenuStep2").hide();
		$("#backCallMenu").hide();
		$("#submitCallMenu").hide();
		$(".divider").hide();
		$("#wizardHeader").html("Call Menu Wizard » Create New Call Menu");
		$("#small_step_number > img").attr('src','<?=$base?>img/step1of2-navigation-small.png');
		$("#step_number > img").attr('src','<?=$base?>img/step1-trans.png')
		$("#calladdlist").css('width','55%');
		$("#calladdlist").css('left','20%');
	});
});

function showAdvanceOptions(route, type) {
    if (type == "hide") {
	$(".didCallMenuAdvance,.didAgentAdvance,.didVoicemailAdvance,.didPhoneAdvance,.didInboundAdvance,.didExtensionAdvance").hide();
	$(".filterGroup,.filterURL,.filterAction").hide();
	showRouteOptions('DISABLED','Filter');
    } else {
	switch (route) {
		case "AGENT":
			$(".didAgentAdvance").show();
			$(".didInboundAdvance").hide();
			break;
		case "IN_GROUP":
			$(".didInboundAdvance").show();
			$(".didAgentAdvance").hide();
			break;
		default:
			$(".didAgentAdvance,.didInboundAdvance").hide();
	}
	
	var filterRoute = $("#efilter_inbound_number").val();
	showFilterOptions(filterRoute);
    }
}

function showAdvanceMenuOptions(num)
{
	if ($(".advanceCallMenu_"+num).is(':visible')) {
		$(".advanceCallMenu_"+num).hide();
		$(".minMax").html("<pre style='display:inline'>[+]</pre> Advance Settings");
	} else {
		$(".advanceCallMenu_"+num).show();
		$(".minMax").html("<pre style='display:inline'>[-]</pre> Advance Settings");
	}
}

function showFilterOptions(action)
{
	if ($("#efilter_inbound_number").is(':visible')) {
		var route = $("#efilter_action").val();
		switch (action) {
			case "GROUP":
				$(".filterGroup").show();
				$(".filterAction").show();
				$(".filterURL").hide();
				showRouteOptions(route,'Filter');
				break;
			case "URL":
				$(".filterURL").show();
				$(".filterAction").show();
				$(".filterGroup").hide();
				showRouteOptions(route,'Filter');
				break;
			default:
				$(".filterGroup,.filterURL,.filterAction").hide();
				showRouteOptions('DISABLED','Filter');
		}
	} else {
		showRouteOptions('DISABLED','Filter');
	}
}

function showRouteOptions(route,type)
{
    switch (route) {
	    case "AGENT":
		    $(".didAgent"+type).show();
		    $(".didExtension"+type+",.didVoicemail"+type+",.didPhone"+type+",.didInbound"+type+",.didCallMenu"+type).hide();
		    break;
	    case "EXTEN":
		    $(".didExtension"+type).show();
		    $(".didAgent"+type+",.didVoicemail"+type+",.didPhone"+type+",.didInbound"+type+",.didCallMenu"+type).hide();
		    break;
	    case "VOICEMAIL":
		    $(".didVoicemail"+type).show();
		    $(".didAgent"+type+",.didExtension"+type+",.didPhone"+type+",.didInbound"+type+",.didCallMenu"+type).hide();
		    break;
	    case "PHONE":
		    $(".didPhone"+type).show();
		    $(".didAgent"+type+",.didVoicemail"+type+",.didExtension"+type+",.didInbound"+type+",.didCallMenu"+type).hide();
		    break;
	    case "IN_GROUP":
		    $(".didInbound"+type).show();
		    $(".didAgent"+type+",.didVoicemail"+type+",.didPhone"+type+",.didExtension"+type+",.didCallMenu"+type).hide();
		    break;
	    case "CALLMENU":
		    $(".didCallMenu"+type).show();
		    $(".didAgent"+type+",.didVoicemail"+type+",.didPhone"+type+",.didInbound"+type+",.didExtension"+type).hide();
		    break;
	    default:
		    $(".didCallMenu"+type+",.didAgent"+type+",.didVoicemail"+type+",.didPhone"+type+",.didInbound"+type+",.didExtension"+type).hide();
    }
}
</script>
<script>
$(document).ready(function() 
    { 
				   $("#did_pattern").keydown(function(event) {
				   if(event.shiftKey)
				        event.preventDefault();
				   if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
				   }
				   else {
				        if (event.keyCode < 95) {
				          if (event.keyCode < 48 || event.keyCode > 57) {
				                event.preventDefault();
				          }
				        }
				        else {
				              if (event.keyCode < 96 || event.keyCode > 105) {
				                  event.preventDefault();
				              }
				        }
				      }
				   });

                                   $("#edid_pattern").keydown(function(event) {
                                   if(event.shiftKey)
                                        event.preventDefault();
                                   if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
                                   }                                    
                                   else {                                               
                                        if (event.keyCode < 95) {
                                          if (event.keyCode < 48 || event.keyCode > 57) {
                                                event.preventDefault(); 
                                          }             
                                        }                                       
                                        else {  
                                              if (event.keyCode < 96 || event.keyCode > 105) {
                                                  event.preventDefault();
                                              }
                                        }
                                      }
                                   });
        
    }
); 
</script>

<script type = "text/javascript">
   
var isShift=false;
         
      function isAlphaNumericwospace(keyCode)
      {    
            if (keyCode == 16)
                isShift = true;

            return (((keyCode >= 48 && keyCode <= 57) && isShift == false) || (keyCode >= 65 && keyCode <= 90) || keyCode == 8 ||  keyCode == 35 ||  keyCode == 36 || keyCode == 37 || keyCode == 39 || keyCode == 46 || (keyCode >= 96 && keyCode <= 105));

            // 8  - Backspace Key
            // 35 - Home Key
            // 36 - End Key
            // 37 - Left Arrow Key
            // 39 - Right Arrow Key
            // 46 - Del Key  
     }    

      function isAlphaNumericwspace(keyCode)
      {    
            if (keyCode == 16)
                isShift = true;

            return (((keyCode >= 48 && keyCode <= 57) && isShift == false) || (keyCode >= 65 && keyCode <= 90) || keyCode == 8 ||  keyCode == 35 ||  keyCode == 36 || keyCode == 37 || keyCode == 39 || keyCode == 46 || (keyCode >= 96 && keyCode <= 105) || keyCode == 32 || keyCode == 222);

            // 8  - Backspace Key
            // 35 - Home Key
            // 36 - End Key
            // 37 - Left Arrow Key
            // 39 - Right Arrow Key
            // 46 - Del Key  
     } 


     function KeyUp(keyCode)
     {
      if (keyCode == 16)
            isShift = false;
     }

     function formsubmitdid() 
     {
	var did_pattern = document.getElementById('did_pattern').value;		
	var did_description = document.getElementById('did_description').value;		

	if(did_pattern == "") {
		alert('Extension is a required field.');
		return false;						
	}	
	
	if(did_description == "") {
		alert('Description is a required field.');
		return false;						
	}	

	document['go_didfrm'].submit();				

     }

     function formsubmitlist() 
     {
        var group_id = document.getElementById('group_id').value;         
        var group_name = document.getElementById('group_name').value;         

        if(group_id == "") {
                alert('Group I.D. is a required field.');
                return false;                                           
        }       
        
        if(group_name == "") {
                alert('Group Name is a required field.');
                return false;                                           
        }       

        document['go_listfrm'].submit();                         

     }

</script>

<script>
$(document).ready(function() 
    { 
         
        $("#ingrouptable").tablesorter({sortList:[[0,0]], headers: { 5: { sorter: false}, 6: {sorter: false} }});
        $("#didtable").tablesorter({headers: { 5: { sorter: false}, 6: {sorter: false} }});
        $("#callmenutable").tablesorter({sortList:[[0,0]], headers: { 5: { sorter: false}, 6: {sorter: false} }});

    } 
); 
</script>

<style type="text/css">
 /*@import url("../../../css/go_common_ce.css");*/
.go_action_menu{
        z-index:999;
        position:absolute;
        top:188px;
        border:#CCC 1px solid;
        background-color:#FFF;
        display:none;
        cursor:pointer;
        padding:1px;
}


#go_action_menu ul {
	list-style-type:none;
	padding: 1px;
	margin: 0px;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.go_action_submenu {
	padding: 3px 10px 3px 5px;
	margin: 0px;
}
</style> 

<link type="text/css" rel="stylesheet" href="<?=base_url()?>css/go_common_ce.css">
<link href="<?=base_url()?>css/go_callmenu/go_callmenu.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<? echo $base; ?>js/go_ingroup/jscolor.js"></script>
<!--  Javascript section -->
<script language="javascript">


	function getselval() {
    		var account_num = document.getElementById('campaign_id').value;
	}

	function showaddlist(listid) {
		document.getElementById('addlist').style.display='block';
		document.getElementById('showlist').style.display='none';
		document.getElementById('list_id').value = listid;
	}


	function showRow() {
    		var autoGen = document.getElementById('auto_gen');
    		var selectCamp_old = document.getElementById('campaign_list_hidden');
    		var account_num = document.getElementById('account_num');

    		if (autoGen.checked) {
        		selectCamp_old.innerHTML = document.getElementById('campaign_list').innerHTML;
        		document.getElementById('account_numTD').style.display = 'table-row';
        		document.getElementById('list_id').readOnly = true;
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
    		} else {
        		document.getElementById('account_numTD').style.display = 'none';
       	 		document.getElementById('list_id').readOnly = false;
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
        		document.getElementById('campaign_list').innerHTML = selectCamp_old.innerHTML;
        		account_num.selectedIndex = 0;
    		}
	}


	function genListID(accnt) {
    		var account_num = document.getElementById('account_num');
    		var cntX=0;


    		if (accnt>0) {
        		var autoListID = account_num.options[accnt].value;
        			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            				xmlhttp=new XMLHttpRequest();
            			} else {// code for IE6, IE5
            				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        			}
        	
			xmlhttp.onreadystatechange=function()
            		{
            			if (xmlhttp.readyState==4 && xmlhttp.status==200)
                		{
                			var returnString=xmlhttp.responseText;
                			var returnArray=returnString.split("\n");
                			var cnt=returnArray[0];
                			var camp_list=returnArray[1].split(",");
                			var camp_name=returnArray[2].split(",");
                			var i=0;
                			cnt++;
                		
	
					var selectCamp = '<select name=campaign_ids id=campaign_ids>';
  	             				for (i=0;i<camp_list.length;i++) {
                    					selectCamp = selectCamp + '<option value="'+camp_list[i]+'">'+camp_list[i]+' - '+camp_name[i]+'</option>';
                				}
                				selectCamp = selectCamp + '</select>';
                		
					if (cnt < 1000000) {
                    				cntX="1" + cnt;
                			}
                			if (cnt < 100000) {
                    				cntX="10" + cnt;
                			}
                			if (cnt < 10000) {
                    				cntX="100" + cnt;
                			}
                			if (cnt < 1000) {
                    				cntX="1000" + cnt;
                			}
                			if (cnt < 100) {
                    				cntX="10000" + cnt;
                			}
                			if (cnt < 10) {
                    				cntX="100000" + cnt;
                			}
                			
							var currentTime = new Date();
							var month = currentTime.getMonth() + 1;
							var day = currentTime.getDate();
							var year = currentTime.getFullYear();
							var comdate = month+'-'+day+'-'+year;

							document.getElementById('list_name').value = autoListID + ' ListID ' + cntX;
                			document.getElementById('list_description').value = 'Auto-Generated ListID - '+comdate;
                			document.getElementById('list_id').value = autoListID.substr(0,5) + cntX;
                			document.getElementById('campaign_list').innerHTML = selectCamp;
                		}	
            		}
        		xmlhttp.open("GET","go_list/go_list.php?stage=addLIST&accnt="+autoListID,true);
        		//xmlhttp.open("GET",window.location.pathName+"?stage=addLIST&accnt="+autoListID,true);
        		xmlhttp.send();
    		} else {
        		var selectCamp_old = document.getElementById('campaign_list_hidden');
        		document.getElementById('list_id').value = '';
        		document.getElementById('list_name').value = '';
        		document.getElementById('list_description').value = '';
        		document.getElementById('campaign_list').innerHTML = selectCamp_old.innerHTML;
    		}
	} //end function
	
	function ParseFileName() {
			
	if (!document.uploadform.OK_to_process) 
		{	
		
		var filename = document.getElementById("leadfile").value;
		var endstr = filename.lastIndexOf('');
			
		if (endstr>-1) 
			{
			endstr++;
			document.getElementById('leadfile_name').value=filename;
			}
		}
	}

	function ShowProgress(good, bad, total, dup, post) {
		parent.lead_count.document.open();
		parent.lead_count.document.write('<html><body><table border=0 width=200 cellpadding=10 cellspacing=0 align=center valign=top><tr bgcolor="#000000"><th colspan=2><font face="arial, helvetica" size=3 color=white>Current file status:</font></th></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B>Good:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+good+'</B></font></td></tr><tr bgcolor="#990000"><td align=right><font face="arial, helvetica" size=2 color=white><B>Bad:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+bad+'</B></font></td></tr><tr bgcolor="#000099"><td align=right><font face="arial, helvetica" size=2 color=white><B>Total:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+total+'</B></font></td></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B> &nbsp; </B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B> &nbsp; </B></font></td></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B>Duplicate:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+dup+'</B></font></td></tr><tr bgcolor="#009900"><td align=right><font face="arial, helvetica" size=2 color=white><B>Postal Match:</B></font></td><td align=left><font face="arial, helvetica" size=2 color=white><B>'+post+'</B></font></td></tr></table><body></html>');
		parent.lead_count.document.close();
	}	
	

	function postval(groupid) {

                 <?php
                             $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
                             if($permissions->inbound_update == "N"){
                                echo("alert('You don\'t have permission to update this record(s)');");
                                echo "return false;";
                             }
                ?>

		document.getElementById('showval').value=groupid;
		document.getElementById('showvaledit').value=groupid;

		$('#goLoading').show();
		$('#advanceid').hide();
		$('#clickadvanceplus').show();
		$('#clickadvanceminus').hide();
		$('#overlay').fadeIn('fast',function(){
			$('#box').show('fast');
			$('#box').animate({'top':'2625px'},500);
		});
		 
		$('#boxclose').click(function(){
			$('#box').animate({'top':'-550px'},500,function(){
				$('#overlay').fadeOut('fast');
				
			});
		});	
		var items = $('#showlistview').serialize();
		$.post("<?=$base?>index.php/go_ingroup/editview", { items: items, action: "editlist" },
		function(data){



			var datas = data.split("##");
			
			var i=0;
			var count_listid = datas.length;

			for (i=0;i<count_listid;i++) {

				if(datas[i]=="") {
					datas[i]=" ";
				}
					
				document.getElementById('egroup_id').innerHTML=datas[0];
				document.getElementById('egroup_name').value=datas[1];
				document.getElementById('egroup_color').value=datas[2];
				document.getElementById('escript_id').value=datas[8];
				document.getElementById('eactive').value=datas[3];
				document.getElementById('eweb_form_address').value=datas[4];
				document.getElementById('enext_agent_call').value=datas[6];
				document.getElementById('equeue_priority').value=datas[29];
				document.getElementById('eon_hook_ring_time').value=datas[101];
				document.getElementById('efronter_display').value=datas[7];
				document.getElementById('eget_call_launch').value=datas[9];
				document.getElementById('exferconf_a_dtmf').value=datas[10];
				document.getElementById('exferconf_a_number').value=datas[11];
				document.getElementById('exferconf_b_dtmf').value=datas[12];
				document.getElementById('exferconf_b_number').value=datas[13];
				document.getElementById('exferconf_c_number').value=datas[65];
				document.getElementById('exferconf_d_number').value=datas[66];
				document.getElementById('exferconf_e_number').value=datas[67];
				document.getElementById('etimer_action').value=datas[60];
				document.getElementById('etimer_action_message').value=datas[61];
				document.getElementById('etimer_action_seconds').value=datas[62];
				document.getElementById('etimer_action_destination').value=datas[95];
				document.getElementById('edrop_call_seconds').value=datas[14];
				document.getElementById('edrop_action').value=datas[15];
				document.getElementById('edrop_exten').value=datas[16];
				document.getElementById('evoicemail_ext').value=datas[5];
				document.getElementById('eafter_hours_action').value=datas[18];
				document.getElementById('eignore_list_script_override').value=datas[68]; 
				document.getElementById('after_hours_message_filename').value=datas[19];  
				document.getElementById('eafter_hours_exten').value=datas[20]; 
				document.getElementById('after_hours_voicemail').value=datas[21];  
				document.getElementById('eno_agent_no_queue').value=datas[56]; 
				document.getElementById('no_agent_action').value=datas[57]; 
				document.getElementById('welcome_message_filename').value=datas[22];  
				document.getElementById('eplay_welcome_message').value=datas[52];
				document.getElementById('moh_context').value=datas[23];      
				document.getElementById('onhold_prompt_filename').value=datas[24];
				document.getElementById('prompt_interval').value=datas[25];
				document.getElementById('onhold_prompt_no_block').value=datas[75];
				document.getElementById('onhold_prompt_seconds').value=datas[76];
				document.getElementById('play_place_in_line').value=datas[41];
				document.getElementById('play_estimate_hold_time').value=datas[42];
				document.getElementById('calculate_estimated_hold_seconds').value=datas[96];
				document.getElementById('eht_minimum_prompt_filename').value=datas[98];
				document.getElementById('eht_minimum_prompt_no_block').value=datas[99];
				document.getElementById('eht_minimum_prompt_seconds').value=datas[100];
				document.getElementById('wait_time_option').value=datas[82];
				document.getElementById('wait_time_second_option').value=datas[83];
				document.getElementById('wait_time_third_option').value=datas[84];
				document.getElementById('wait_time_option_seconds').value=datas[85];
				document.getElementById('wait_time_option_exten').value=datas[86];
				document.getElementById('wait_time_option_voicemail').value=datas[87];
				document.getElementById('wait_time_option_press_filename').value=datas[92];
				document.getElementById('wait_time_option_no_block').value=datas[93];
				document.getElementById('wait_time_option_prompt_seconds').value=datas[94];
				document.getElementById('wait_time_option_callback_filename').value=datas[90];
				document.getElementById('wait_time_option_callback_list_id').value=datas[91];
				document.getElementById('wait_hold_option_priority').value=datas[81];
				document.getElementById('hold_time_option').value=datas[43];
				document.getElementById('hold_time_second_option').value=datas[79];
				document.getElementById('hold_time_option_seconds').value = datas[44];
				document.getElementById('hold_time_option_minimum').value = datas[72];
				document.getElementById('hold_time_option_exten').value=datas[45];
				document.getElementById('hold_time_option_voicemail').value=datas[46];
				document.getElementById('hold_time_option_press_filename').value=datas[73];
				document.getElementById('hold_time_option_no_block').value=datas[77];
				document.getElementById('hold_time_option_prompt_seconds').value=datas[78];
				document.getElementById('hold_time_option_callback_filename').value=datas[48];
				document.getElementById('hold_time_option_callback_list_id').value=datas[49];
				document.getElementById('agent_alert_exten').value=datas[26];
				document.getElementById('agent_alert_delay').value=datas[27];
				document.getElementById('no_delay_call_route').value=datas[51];
				document.getElementById('ingroup_recording_override').value=datas[31];
				document.getElementById('ingroup_rec_filename').value =datas[32];
				document.getElementById('answer_sec_pct_rt_stat_one').value=datas[53];
				document.getElementById('answer_sec_pct_rt_stat_two').value=datas[54];
				document.getElementById('start_call_url').value=datas[63];
				document.getElementById('dispo_call_url').value=datas[64];
				document.getElementById('add_lead_url').value=datas[97];
				document.getElementById('extension_appended_cidname').value = datas[69];
				document.getElementById('uniqueid_status_display').value = datas[70];
				document.getElementById('uniqueid_status_prefix').value = datas[71];
				document.getElementById('edrop_inbound_group').value=datas[30];
				document.getElementById('ecall_time_id').value=datas[17];
				document.getElementById('afterhours_xfer_group').value=datas[33];
				document.getElementById('wait_time_option_callmenu').value=datas[89];
				document.getElementById('wait_time_option_xfer_group').value=datas[88];
				document.getElementById('hold_time_option_callmenu').value=datas[74];
				document.getElementById('hold_time_option_xfer_group').value=datas[47];
				document.getElementById('default_xfer_group').value=datas[28];
				document.getElementById('hold_recall_xfer_group').value=datas[50];
				document.getElementById('default_group_alias').value=datas[55];
			}
			
			$('#goLoading').hide();
		});
	}
	
	function editpost(groupID) {
	
		document.getElementById('showval').value=groupID;
		document.getElementById('showvaledit').value=groupID;


		var itemsumit = $('#edit_go_listfrm').serialize();
		$.post("<?=$base?>index.php/go_ingroup/editsubmit", { itemsumit: itemsumit, action: "editlistfinal" },
		function(data){
			var datas = data.split(":");
			var i=0;
			var count_listid = datas.length;
			
			for (i=0;i<count_listid;i++) {

					if(datas[i]=="") {
							datas[i]=" ";
					}
			}
			
			if(datas[0]=="SUCCESS") {
				alert(data);
				location.reload();
			}
			
			$('#boxclose').click(function(){
				$('#box').animate({'top':'-550px'},500,function(){
					$('#overlay').fadeOut('fast');
				});
			});	
		});
	}
	
	function deletepost(listID) {
			
				var confirmmessage=confirm("Confirm to delete "+listID+"?");
				if (confirmmessage==true){
					
					
					$.post("<?=$base?>index.php/go_list/deletesubmit", { listid_delete: listID, action: "deletelist" },
					function(data){
                			
	             				var datas = data.split(":");
                				var i=0;
                				var count_listid = datas.length;

 								if(datas[0]=="SUCCESS") {
 									alert(listID+" successfully deleted");
	             					location.reload();
 								}
 								
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								});	
							 		
					});	
				} 
				
	}
	
	function deletepostingroup(groupid) {

             <?php
                  $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
                  if($permissions->inbound_delete == "N"){
                     echo("alert('You don\'t have permission to delete this record(s)');");
                     echo "return false;";
                  }
	      ?>	
				var confirmmessage=confirm("Confirm to delete "+groupid+"?");
				if (confirmmessage==true){
					
					
					$.post("<?=$base?>index.php/go_ingroup/deletesubmit", { listid_delete: groupid, action: "deletelist" },
					function(data){
                			
	             				var datas = data.split(":");
                				var i=0;
                				var count_listid = datas.length;
			
 								if(datas[0]=="SUCCESS") {
 									//alert(listID+" successfully deleted");
	             					location.reload();
 								}
 								
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								});	
							 		
					});	
				} 
				
	}
	
	
	function deletepostdid(didid,diddescs) {
	     <?php
                  $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
                  if($permissions->inbound_delete == "N"){
                     echo("alert('You don\'t have permission to delete this record(s)');");
                     echo "return false;";
                  }
	      ?>		
				var confirmmessage=confirm("Confirm to delete "+diddescs+"?");
				if (confirmmessage==true){
					
					
					$.post("<?=$base?>index.php/go_ingroup/deletesubmit", { didid_delete: didid, action: "deletedid" },
					function(data){
                			
	             				var datas = data.split(":");
                				var i=0;
                				var count_listid = datas.length;

 								if(datas[0]=="SUCCESS") {
 									alert(diddescs+" successfully deleted");
	             					location.reload();
 								}
 								
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								});	
							 		
					});	
				} 
				
	}	
	
	function deletepostcallmenu(callmenu) {
			
             <?php
                  $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
                  if($permissions->inbound_delete == "N"){
                     echo("alert('You don\'t have permission to delete this record(s)');");
                     echo "return false;";
                  }
	      ?>
				var confirmmessage=confirm("Confirm to delete "+callmenu+"?");
				if (confirmmessage==true){
					
					
					$.post("<?=$base?>index.php/go_ingroup/deletesubmit", { callmenu_delete: callmenu, action: "deletecallmenu" },
					function(data){
                			
	             				var datas = data.split(":");
                				var i=0;
                				var count_listid = datas.length;
			
 								if(datas[0]=="SUCCESS") {
 					
	             					location.reload();
 								}
 								
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								});	
							 		
					});	
				} 
				
	}
	
	function viewpost(groupid) {
		
		document.getElementById('showval').value=groupid;
		document.getElementById('showvaledit').value=groupid;

		var items = $('#showlistview').serialize();
			$.post("<?=$base?>index.php/go_ingroup/editview", { items: items, action: "editlist" },
				 function(data){

                				var datas = data.split("##");

                			
                				
                				var i=0;
                				var count_listid = datas.length;
				
                				for (i=0;i<count_listid;i++) {

                						if(datas[i]=="") {
									datas[i]=" ";
 								}
 											
											document.getElementById('viewinboundid').innerHTML=datas[0];
											document.getElementById('viewinbounddesc').innerHTML=datas[1];
											document.getElementById('viewinboundstatus').innerHTML=datas[3];
						} 
 											
 								  $('#overlay').fadeIn('fast',function(){
 								  		$('#boxviewlist').show('fast');
	                  			$('#boxviewlist').animate({'top':'70px'},500);
			             			});
	             				 
	             				$('#boxclose').click(function(){
	              					$('#boxviewlist').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	                  					
	              					});
								 	});		
						 });	


	}
	
		function didviewpost(didid) {
		
		document.getElementById('didval').value=didid;

		var items = $('#showlistview').serialize();
			$.post("<?=$base?>index.php/go_ingroup/editview", { items: items, action: "editdid" },
				 function(data){

                				var datas = data.split("##");

                				document.getElementById('agentrankvalue').innerHTML=datas[102];
                				
                				var i=0;
                				var count_listid = datas.length;
				
                				for (i=0;i<count_listid;i++) {

                						if(datas[i]=="") {
												datas[i]=" ";
 											}
 											
											document.getElementById('viewdid_id').innerHTML=datas[0];
											document.getElementById('viewdid_desc').innerHTML=datas[2];
											document.getElementById('viewdid_status').innerHTML=datas[3];
							
									} 
 											
 								  $('#overlaydidview').fadeIn('fast',function(){
 								  		$('#didview').show('fast');
	                  			$('#didview').animate({'top':'70px'},500);
			             			});
	             				 
	             				$('#boxclose').click(function(){
	              					$('#didview').animate({'top':'-550px'},500,function(){
	                  					$('#overlaydidview').fadeOut('fast');
	                  					
	              					});
								 	});		
						 });	


	}
	
	function didpostval(didid) {
                 <?php
                             $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
                             if($permissions->inbound_update == "N"){
                                echo("alert('You don\'t have permission to update this record(s)');");
                                echo "return false;";
                             }
                ?>
		document.getElementById('didval').value=didid;
		

		var items = $('#didlist').serialize();
			$.post("<?=$base?>index.php/go_ingroup/editview", { items: items, action: "editdid" },
				 function(data){
				 				var datas = data.split("##");

                				//document.getElementById('agentrankvalue').innerHTML=datas[102];
                				
                				var i=0;
                				var count_listid = datas.length;
                				for (i=0;i<count_listid;i++) {
                					
                						if(datas[i]=="") {
												datas[i]=" ";
 										}
 										
 										document.getElementById('didvals').value=datas[0];
										document.getElementById('ediddesc').innerHTML=datas[0]; 										
 										document.getElementById('edid_pattern').value=datas[1];
 										document.getElementById('edid_description').value=datas[2];
 										document.getElementById('edid_active').value=datas[3];
 										document.getElementById('edid_route').value=datas[4];
										showRouteOptions(datas[4],'Group');
 										document.getElementById('erecord_call').value=datas[20];											
										document.getElementById('eextension').value=datas[5];
										document.getElementById('eexten_context').value=datas[6]; 
										document.getElementById('evoicemail_ext').value=datas[7]; 
										document.getElementById('ephone').value=datas[8];
										document.getElementById('emenu_id').value=datas[19];
										document.getElementById('euser').value=datas[10];
										document.getElementById('euser_unavailable_action').value=datas[11];
										document.getElementById('euser_route_settings_ingroup').value=datas[12];
                                                                                if(datas[13] == "") {
                                                                                document.getElementById('egroup_id2').value="---NONE---";
                                                                                } else {
                                                                                document.getElementById('egroup_id2').value=datas[13];
                                                                                } 
										document.getElementById('ecall_handle_method').value=datas[14];
										document.getElementById('eagent_search_method').value=datas[15];
										document.getElementById('elist_id').value=datas[16];
										document.getElementById('ecampaign_id').value=datas[17];
										document.getElementById('ephone_code').value=datas[18];
										document.getElementById('efilter_clean_cid_number').value=datas[40];
										document.getElementById('efilter_inbound_number').value=datas[21];
										showFilterOptions(datas[21]);
										document.getElementById('efilter_phone_group_id').value=datas[22];
										document.getElementById('efilter_url').value=datas[23];
										document.getElementById('efilter_action').value=datas[24];
										if (datas[21] != 'DISABLED' && $('#efilter_inbound_number').is(':visible'))
										{
											showRouteOptions(datas[24],'Filter');
										}
										document.getElementById('efilter_extension').value=datas[25];
										document.getElementById('efilter_exten_context').value=datas[26];
										document.getElementById('efilter_voicemail_ext').value=datas[27];
										document.getElementById('efilter_phone').value=datas[28];
										document.getElementById('efilter_server_ip').value=datas[29];
										document.getElementById('efilter_menu_id').value=datas[39];
										document.getElementById('efilter_user').value=datas[30];
										document.getElementById('efilter_user_unavailable_action').value=datas[31];
										document.getElementById('efilter_user_route_settings_ingroup').value=datas[32];
										document.getElementById('efilter_group_id').value=datas[33];
										document.getElementById('efilter_call_handle_method').value=datas[34];
										document.getElementById('efilter_agent_search_method').value=datas[35];
										document.getElementById('efilter_list_id').value=datas[36];
										document.getElementById('efilter_campaign_id').value=datas[37];
										document.getElementById('efilter_phone_code').value=datas[38];
 										
 									} 
 											
 								  $('#overlayeditdid').fadeIn('fast',function(){
 								  		$('#dideditlist').show('fast');
	                  			$('#dideditlist').animate({'top':'730px'},500);
			             			});
	             				 
	             				$('#boxclose').click(function(){
	              					$('#dideditlist').animate({'top':'-550px'},500,function(){
	                  					$('#overlayeditdid').fadeOut('fast');
	                  					
	              					});
								 	});		
						 });	
	}
	
	function editpostdid(didid) {
		
			document.getElementById('didval').value=didid;

			var itemsumit = $('#go_editdidfrm').serialize();
				$.post("<?=$base?>index.php/go_ingroup/editsubmit", { itemsumit: itemsumit, action: "editdidfinal" },
				function(data){
                			
	             				var datas = data.split(":");
                				var i=0;
                				var count_listid = datas.length;
                				
                				for (i=0;i<count_listid;i++) {

                						if(datas[i]=="") {
												datas[i]=" ";
 										}
 										
 								}
 								
 								if(datas[0]=="SUCCESS") {
 									alert(data);
	             					location.reload();
 								}
 								
	             				$('#boxclose').click(function(){
	              					$('#box').animate({'top':'-550px'},500,function(){
	                  					$('#overlay').fadeOut('fast');
	              					});
								});	
							 		
				});	
	}
	
	function callmenupostval(menuid) {
                 <?php
                             $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
                             if($permissions->inbound_update == "N"){
                                echo("alert('You don\'t have permission to update this record(s)');");
                                echo "return false;";
                             }
                ?>
		document.getElementById('menuvals').value=menuid;
		

		var items = $('#go_editcallmenufrm').serialize();
			$.post("<?=$base?>index.php/go_ingroup/editview", { items: items, action: "editcallmenu" },
				 function(data){
				 				var datas = data.split("##");
                				//document.getElementById('agentrankvalue').innerHTML=datas[102];
                				
                				var i=0;
                				var count_listid = datas.length;
				
                				for (i=0;i<count_listid;i++) {
                					
                						if(datas[i]=="") {
												datas[i]=" ";
 										}
 										
 										document.getElementById('menuvals').value=datas[0];
 										document.getElementById('edit_menu_id').innerHTML=datas[0];
										document.getElementById('edit_menu_desc').innerHTML=datas[0];
										document.getElementById('edit_menu_name').value=datas[1];
 										document.getElementById('edit_menu_prompt').value=datas[2];
 										document.getElementById('edit_menu_timeout').value=datas[3];
 										document.getElementById('edit_menu_timeout_prompt').value=datas[4];
 										document.getElementById('edit_menu_invalid_prompt').value=datas[5];											
										document.getElementById('edit_menu_repeat').value=datas[6];
										document.getElementById('edit_menu_time_check').value=datas[7]; 
										document.getElementById('edit_call_time_id').value=datas[8];
										document.getElementById('edit_track_in_vdac').value=datas[9];
										document.getElementById('edit_custom_dialplan_entry').innerHTML=datas[10];
										document.getElementById('edit_tracking_group').value=datas[11];
										document.getElementById('tblcallmenuoptions').innerHTML=datas[12];
 										
 									} 
 											
									$('#overlaycallmenueditlist').fadeIn('fast',function(){
 								  		$('#calleditlist').show('fast');
										$('#calleditlist').animate({'top':'-70px'},500);
			             			});
	             				 
									$('#boxclose').click(function(){
										$('#calleditlist').animate({'top':'-2550px'},500,function(){
											$('#overlaycallmenueditlist').fadeOut('fast');
										});
									});		
						 });	
	}
	
	function showoptionpostval(menuid,optionval,route,ctr)
	{
		$.post("<?=$base?>index.php/go_ingroup/showoption", { menuid: menuid, optionval: optionval, route: route, ctr: ctr, action: "showoption" }, function(data){
			if (data.length > 0)
			{
				$('.option_hidden_'+ctr).slideDown('slow');
				$('.option_display_'+ctr).html(data);
			} else {
				$('.option_hidden_'+ctr).slideUp('slow');
				$('.option_display_'+ctr).html('');
			}
		});
	}
	
	function checkoptionval(option,ctr)
	{
		var isExist = 0;
		if (option.length > 0)
		{
			for (x=0;x<15;x++)
			{
				if (option == $('#option_value_'+x).val() && x != ctr)
				{
					$('#option_value_'+x).css('border','1px solid #FF0000');
					isExist = 1;
				} else {
					if (isExist && x != ctr)
					{
						$('#option_value_'+x).attr('disabled','true');
					}
					else if (isExist && x == ctr)
					{
						$('#option_value_'+x).css('border','1px solid #FF0000');
					}
					else
					{
						$('#option_value_'+x).removeAttr('disabled');
						$('#option_value_'+x).css('border','1px solid #DFDFDF');
					}
				}
			}
		}
		
		if (isExist)
		{
			alert('The option you selected is already in use.');
		}
	}
	
	function editcallmenupost(callmenu)
	{
		var itemsubmit = $('#go_editcallmenufrm').serialize();
		$.post("<?=$base?>index.php/go_ingroup/editsubmit", { itemsubmit: itemsubmit, action: "editcallmenufinal" },
		function(data){
			var datas = data.split(":");
			var i=0;
			var count_listid = datas.length;
			
                        if(data.indexOf("SUCCESS") !== -1){
                            alert(datas);
                            callmenupostval(callmenu);
                        }
			for (i=0;i<count_listid;i++) {

					if(datas[i]=="") {
							datas[i]=" ";
					}
			}
			
			$('#boxclose').click(function(){
				$('#calleditlist').animate({'top':'-2550px'},500,function(){
					$('#overlaycallmenueditlist').fadeOut('fast');
				});
			});	
		});
	}
   
        $(function(){
             $("#finishCallMenuEdit").click(function(){
                  closemeeditcallmenu();
             });
        });
	
	
   function addlistoverlay() {

        <?
          $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
          if($permissions->inbound_create == "N"){
            echo("alert('You don\'t have permission to add new record(s)');");
            echo "return false;";
          }
        ?> 
	
	 $('#overlay').fadeIn('fast',function(){
	                  			$('#boxaddlist').animate({'top':'70px'},500);
		             			});
	
	              					/*$('#boxaddlist').animate({'top':'-550px'},500,function(){
	                  			$('#overlay').fadeOut('fast');
	              					});*/
		
	
  }
  
  function adddidoverlay() {
        <?
          $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
          if($permissions->inbound_create == "N"){
            echo("alert('You don\'t have permission to add new record(s)');");
            echo "return false;";
          }
        ?> 
	var routeid = $('#did_route').val();
	showRouteOptions(routeid,'Wizard');
	$('#overlaydidlist').fadeIn('fast',function(){
		$('#didaddlist').animate({'top':'70px'},500);
	});
	 
  }

  function addcallmenuoverlay() {
        <?
          $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
          if($permissions->inbound_create == "N"){
            echo("alert('You don\'t have permission to add new record(s)');");
            echo "return false;";
          }
        ?> 
	$('#menu_id').val('');
	$('#err_menu_id').html('');
	$('#overlaycallmenulist').fadeIn('fast',function(){
		$('#calladdlist').animate({'top':'-45px'},500);
	});
	
  }

  function closeme() {

        $('#box').animate({'top':'-550px'},500,function(){
	$('#overlay').fadeOut('fast');
	$('#box').hide('fast');
	});

  }

  function closemeadd() {
	
     $('#boxaddlist').animate({'top':'-550px'},500,function(){
	$('#overlay').fadeOut('fast');
	});

  }
 
  function closemeadddid() {
	
     $('#didaddlist').animate({'top':'-550px'},500,function(){
	  	$('#overlaydidlist').fadeOut('fast');
	  });
	  
  }  
  
  function closemeeditdid() {
	
     $('#dideditlist').animate({'top':'-900px'},500,function(){
	$('#overlayeditdid').fadeOut('fast');
	$('#dideditlist').hide('fast')
     });
	  
  }  
  
  function closemeeditcallmenu() {
	
     $('#calleditlist').animate({'top':'-2550px'},500,function(){
	  	$('#overlaycallmenueditlist').fadeOut('fast');
	  	$('#calleditlist').hide('fast')
	  	
	  });
	
	location.reload();
  } 
  
  function closemeaddcallmenu() {
	
     $('#calladdlist').animate({'top':'-2550px'},500,function(){
	  	$('#overlaycallmenulist').fadeOut('fast');
	  });
	  
	location.reload();
  }  
  
  function closemedidview() {
	
     $('#didview').animate({'top':'-550px'},500,function(){
	  	$('#overlaydidview').fadeOut('fast');
	  });
	  
  }  
  
  
  
  
	function launch_chooser(fieldname,stage,vposition,defvalue) {
		$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "sounds_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
		function(data){
			document.getElementById("div"+fieldname).innerHTML=data;
			$('#tbl'+fieldname).show();
		});	
	}

 	function setDivVal(divid,idval) {
		document.getElementById(divid).value=idval;
 	}
	
	function launch_vm_chooser(fieldname,stage,vposition,defvalue) {
	    $.post("<?=$base?>index.php/go_ingroup/chooser", { action: "vm_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
		function(data){
			document.getElementById("div"+fieldname).innerHTML=data;
		});	
	}
	
	function launch_moh_chooser(fieldname,stage,vposition,defvalue) {
		$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "moh_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
		function(data){
			document.getElementById("div"+fieldname).innerHTML=data;
		});	

	}
	
	function checkdatas(groupID) {
		
		var itemdatas = $('#agentrankform').serialize();
		$.post("<?=$base?>index.php/go_ingroup/checkagentrank", { itemrank: itemdatas, action: "getcheckagentrank", idgroup: groupID  },
			function(data){
				});	
	}
	
	 function closemeview() {
	
     $('#boxviewlist').animate({'top':'-550px'},500,function(){
	                  			$('#overlay').fadeOut('fast');
	                  						
	              					});
  }


</script>

<script>
$(document).ready(function() 
    { 
        $('#ingrouptable').tablePagination();
        $('#didtable').tablePagination();
        $('#callmenutable').tablePagination();
        
        $('#selectAllING').click(function()
        {
			if ($(this).is(':checked'))
			{
				$('input:checkbox[id="delIngroup[]"]').each(function()
				{
						$(this).attr('checked',true);
				});
			}
			else
			{
				$('input:checkbox[id="delIngroup[]"]').each(function()
				{
						$(this).removeAttr('checked');
				});
			}
        });
        
        $('#selectAllDID').click(function()
        {
			if ($(this).is(':checked'))
			{
				$('input:checkbox[id="delDID[]"]').each(function()
				{
						$(this).attr('checked',true);
				});
			}
			else
			{
				$('input:checkbox[id="delDID[]"]').each(function()
				{
						$(this).removeAttr('checked');
				});
			}
        });
        
        $('#selectAllIVR').click(function()
        {
			if ($(this).is(':checked'))
			{
				$('input:checkbox[id="delIVR[]"]').each(function()
				{
						$(this).attr('checked',true);
				});
			}
			else
			{
				$('input:checkbox[id="delIVR[]"]').each(function()
				{
						$(this).removeAttr('checked');
				});
			}
        });
		
		$('#menu_id').keyup(function(e)
		{		
			if ($(this).val().length > 3)
			{
				$('#err_menu_id').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
				$('#err_menu_id').load('<? echo $base; ?>index.php/go_ingroup/go_check_ingroup/'+$(this).val());
			} else {
				$('#err_menu_id').html("<small style=\"color:red;\">Minimum of 4 digits.</small>");
			}
		});
		
		$('#menu_id').keydown(function (event)
		{
			$(this).css('border','solid 1px #DFDFDF');
			// Allow: backspace, delete, tab, escape, and enter
			if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
				 // Allow: Ctrl+A
				(event.keyCode == 65 && event.ctrlKey === true) || 
				 // Allow: Ctrl+X
				(event.keyCode == 88 && event.ctrlKey === true) || 
				 // Allow: Ctrl+C
				(event.keyCode == 67 && event.ctrlKey === true) || 
				 // Allow: Ctrl+V
				(event.keyCode == 86 && event.ctrlKey === true) || 
				 // Allow: Ctrl+Z
				(event.keyCode == 90 && event.ctrlKey === true) || 
				 // Allow: Ctrl+Y
				(event.keyCode == 89 && event.ctrlKey === true) || 
				 // Allow: home, end, left, right
				(event.keyCode >= 35 && event.keyCode <= 39)) {
					 // let it happen, don't do anything
					 return true;
			}
			else {
				// Ensure that it is a number and stop the keypress
				if ((event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) &&
				// Ensure that it is a letter and stop the keypress
					((event.keyCode < 65 || event.keyCode > 90) && (event.keyCode < 97 || event.keyCode > 122))) {
					event.preventDefault(); 
				}   
			}
		});
        
    }


 
);

$(function(){
    $(".selectAction").click(function(){

                $puwesto = $(this).position();

                if($('ul.ui-tabs-nav > li:nth-child(1)').hasClass('ui-state-active')){
                     $("#go_action_menu").css({top:($puwesto.top +  ($("#go_action_menu").outerHeight() / 2) + 10),left:($puwesto.left - 60)}).slideToggle('fast');
                }else if($('ul.ui-tabs-nav > li:nth-child(2)').hasClass('ui-state-active')){
                     $("#go_action_menu").css({top:($puwesto.top +  ($("#go_action_menu").outerHeight() / 2) + 10),left:($puwesto.left - 60)}).slideToggle('fast');
                }else{
                     $("#go_action_menu").css({top:($puwesto.top +  ($("#go_action_menu").outerHeight() / 2) + 10),left:($puwesto.left - 60)}).slideToggle('fast');
                }
    });
	
	$('li.go_action_submenu').hover(function()
	{
		$(this).css('background-color','#ccc');
	},function()
	{
		$(this).css('background-color','#fff');
	});
 
    $(".go_action_menu > ul").delegate('li.go_action_submenu','click',function(){

         var $id = $(this).attr("id"),db_$table = '',$data = {},$tab = '',$table='';

         if($('ul.ui-tabs-nav > li:nth-child(1)').hasClass('ui-state-active')){
            $db_table = 'vicidial_inbound_groups';
            $table = 'ingrouptable';
            $col = 9;
         }else if($('ul.ui-tabs-nav > li:nth-child(2)').hasClass('ui-state-active')){
            $db_table = 'vicidial_inbound_dids';
            $table = 'didtable';
            $col = 9;
         }else{
            $db_table = 'vicidial_call_menu';
            $table = 'callmenutable';
            $col = 8;
         }

         $.extend($data,{table:$db_table,action:'',batch:[]});
         switch($id){
             case 'activate':
                 $data.action = "Y";
                 $("#"+$table+" > tbody > tr > td:nth-child("+$col+") > input[type='checkbox']").each(function(){
                       if($(this).prop("checked") === true){
                           $data.batch[$data.batch.length] = $(this).val();
                       }
                 });
             break;
             case 'deactivate':
                 $data.action = "N";
                 $("#"+$table+" > tbody > tr > td:nth-child("+$col+") > input[type='checkbox']").each(function(){
                       if($(this).prop("checked") === true){
                           $data.batch[$data.batch.length] = $(this).val();
                       }
                 });
             break;
             case 'delete':
		var confdel = confirm("Are you sure you want to delete this data(s)?");
		   if(confdel == true){
                        $data.action = "D";
                        $("#"+$table+" > tbody > tr > td:nth-child("+$col+") > input[type='checkbox']").each(function(){
                             if($(this).prop("checked") === true){
                                 $data.batch[$data.batch.length] = $(this).val();
                             }
                        });
                   } else {
			return false;
		   }
             break;
             default:
             break;
         }
         
         $.post(
                "<?=base_url()?>index.php/go_ingroup/batchupdate",
                $data,
                function(data){
                    alert(data);
		    location.reload();
                }
         );
    });
}); 
</script>
<!-- end Javascript section -->

		<!-- CSS section -->
		<style type="text/css">
			
			a.back{
	            width:256px;
	            height:73px;
	            /*position:fixed;*/
	            bottom:15px;
	            right:15px;
/*	            background:#fff url(codrops_back.png) no-repeat top left;*/
	            z-index:1;
	            cursor:pointer;
	        }
	        a.activator{
	            width:153px;
	            height:150px;
	          /*  position:absolute;
	            top:0px;
	            left:0px;
/*	            background:#fff url(clickme.png) no-repeat top left;*/
	            z-index:1;
	            cursor:pointer;
	            font-size: 13px;
			font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;
	        }
	        /* Style for overlay and box */
	        .overlay{
/*	            background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;*/
	            background:transparent url(<? echo $base; ?>img/overlay.png) repeat top left;
	            position:fixed;
	            top:0px;
	            bottom:0px;
	            left:0px;
	            right:0px;
	            z-index:100;
	        }
			
			 .overlaydidlist {
			 	background:transparent url(<? echo $base; ?>img/overlay.png) repeat top left;
	            position:fixed;
	            top:0px;
	            bottom:0px;
	            left:0px;
	            right:0px;
	            z-index:100;
			 }	 
			 
			 .overlayeditdid {
			 	background:transparent url(<? echo $base; ?>img/overlay.png) repeat top left;
	            position:fixed;
	            top:0px;
	            bottom:0px;
	            left:0px;
	            right:0px;
	            z-index:100;
			 }	
			 
			 .overlaycallmenulist {
			 	background:transparent url(<? echo $base; ?>img/overlay.png) repeat top left;
	            position:fixed;
	            top:0px;
	            bottom:0px;
	            left:0px;
	            right:0px;
	            z-index:100;
			 }
			 
			 .overlaydidview {
			 	background:transparent url(<? echo $base; ?>img/overlay.png) repeat top left;
	            position:fixed;
	            top:0px;
	            bottom:0px;
	            left:0px;
	            right:0px;
	            z-index:100;
			 }	 
			 
			 .overlaycallmenueditlist {
			 	background:transparent url(<? echo $base; ?>img/overlay.png) repeat top left;
	            position:fixed;
	            top:0px;
	            bottom:0px;
	            left:0px;
	            right:0px;
	            z-index:100;
			 }      
	        
	        .box{
	            position:absolute;
	            top:-550px;
/*	            top:-200px;*/
	            left: 10%;
	            right: 30%;
	            background-color: white;
	            color:#7F7F7F;
	            padding:20px;
	          /*  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;*/
	            z-index:101;
	            width: 70%;
	            
	        }
	        .boxaddlist{
	             position:fixed;
	            top:-550px;
/*	            top:-200px;*/
	            left:20%;
	            right:30%;
	            background-color: white;
	            color:#7F7F7F;
	            padding: 20px 20px 5px 20px;
	           /* padding:20px;*/
	          /*  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;*/
	            z-index:101;
	            width: 60%;
	        	}
	        	
	        	.didaddlist {
      		     position:fixed;
	            top:-550px;
	            left:20%;
	            right:10%;
	            background-color: white;
	            color:#7F7F7F;
	            padding: 20px 20px 5px 20px;
	            z-index:101;
	            width: 55%;
	            /*  
	          	  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*
	            background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;			*/
	        		
	        	}
	        	
	        	.dideditlist {
      		     position:absolute;
	            top:-900px;
	            left:20%;
	            right:10%;
	            background-color: white;
	            color:#7F7F7F;
	            padding: 20px 20px 5px 20px;
	            z-index:101;
	            width: 50%;
	            /*  
	          	  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*
	            background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;			*/
	        		
	        	}
	        	
	        	.didview {
      		     position:fixed;
	            top:-550px;
	            left:20%;
	            right:10%;
	            background-color: white;
	            color:#7F7F7F;
	            padding: 20px 20px 5px 20px;
	            z-index:101;
	            width: 50%;
	            /*  
	          	  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*
	            background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;			*/
	        		
	        	}

				.calladdlist {
					position:absolute;
	            top:-2550px;
	            left:20%;
	            right:10%;
	            background-color: white;
	            color:#7F7F7F;
	            padding: 20px 20px 5px 20px;
	            z-index:101;
	            width: 55%;
	            /*  
	          	  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*
	            background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;			*/
					
					
				}
				
				.calleditlist {
				position: absolute;
	            top:-2550px;
	            left:20%;
	            right:10%;
	            background-color: white;
	            color:#7F7F7F;
	            padding: 20px;
	            z-index:101;
	            /*  
	          	  border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*
	            background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;			*/
					
					
				}
	        	
	        	.boxviewlist{
	             position:fixed;
	            top:-550px;
/*	            top:-200px;*/
	            left:30%;
	            right:30%;
	            background-color: white;
	            color:#7F7F7F;
	            padding:20px;
	          	/*  
	          	border:2px solid #ccc;
	            -moz-border-radius: 20px;
	            -webkit-border-radius:20px;
	            -khtml-border-radius:20px;
	            -moz-box-shadow: 0 1px 5px #333;
	            -webkit-box-shadow: 0 1px 5px #333;*/
	            
	            -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	            /*
	            background:rgba(48,70,115,0.2);-webkit-box-shadow: #414A39 2px 2px 2px;-moz-box-shadow: #414A39 2px 2px 2px; box-shadow: #414A39 2px 2px 2px;			*/
	            z-index:101;
	            width: 40%;
	        	}
	        	
	        .box h1{
	            border-bottom: 1px dashed #7F7F7F;
	            margin:-20px -20px 0px -20px;
	            padding:50px;
	            background-color:#FFEFEF;
	            color:#EF7777;
	            -moz-border-radius:20px 20px 0px 0px;
	            -webkit-border-top-left-radius: 20px;
	            -webkit-border-top-right-radius: 20px;
	            -khtml-border-top-left-radius: 20px;
	            -khtml-border-top-right-radius: 20px;
	        }
	        
	        a.boxclose{
	            float:right;
	            width:26px;
	            height:26px;
	            background:transparent url(<? echo $base; ?>img/cancel.png) repeat top left;
	            margin-top:-30px;
	            margin-right:-30px;
	            cursor:pointer;
	        }
	        
			.nowrap { 
			   background: white;
			   font-size: 12px;
			   font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;
			}
			table {
			    /*border-top: 1px dashed rgb(208,208,208);*/
			}
			/*td {
			    font-size : 10px;
			}*/

			.tabnoborder {
				border: none;
			}
			
			.title-header {
				font-weight: bold;
				color: black;
				font-size: 15px;	
			}
			
			.modify-value {
				font-weight: bold;
				color: #7f7f7f;
			}
			.lblback {
				background:#E0F8E0;
			}
			
			.tableedit {
				/*border-top: 1px double; rgb(208,208,208);*/
			}
			
			
			.tableeditingroup {
				/*border-top: 1px double; rgb(208,208,208);*/
			}

			.tableadvace {
				display: none;	
			}
			
			.tablenodouble {
				/*border-top: 0px double; rgb(208,208,208);*/
			}

			td {
					font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;
				color: black;
			}
			
			.thheader {
				/*background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/bg.gif);*/
        		background-repeat: no-repeat;
        		background-position: center right;
        		cursor: pointer;
			
			}       

			
			.tr1 td{ background:#E0F8E0; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal; color:#000;  border-top: 1px dashed rgb(208,208,208); }
			.tr2 td{ background:#EFFBEF; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;  color:#000;  border-top: 1px dashed rgb(208,208,208); }
			.tredit td{ background:#EFFBEF;font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal; color:#000;  border-bottom: 1px dashed rgb(208,208,208); } 
			.trview td{ background:#EFFBEF; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 13px; font-stretch: normal;  color:#000; }			  
			
			A#searchcallhistory:link {text-decoration: none; color: black;}
			A#searchcallhistory:visited {text-decoration: none; color: black;}
			A#searchcallhistory:active {text-decoration: none; color: black;}
			A#searchcallhistory:hover {text-decoration: none; font-weight:bold;}
			
			A#idlink:link {text-decoration: none; color: black;}
			A#idlink:visited {text-decoration: none; color: black;}
			A#idlink:active {text-decoration: none; color: black;}
			A#idlink:hover {text-decoration: none; color: red}
			
		        a.menuIdcall{cursor:pointer;}
                        a.menuIdcall:hover{color:red;}	

			
			
		</style>
		<!-- end CSS section -->

<!-- begin body -->
<?php
	$countthis = count($ingrouplists);
	if($countthis > 0){
echo "<body>";
} else {	
?>
<body onload='addlistoverlay(); genListID("<?=$gouser?>")'>
<?php
}
?>

<div id='outbody' class="wrap">
    <div id="icon-inbound" class="icon32"></div>
    <h2 style="font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;">Inbound</h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">

    	    <!-- start box -->
            <div class="postbox-container" style="width: 99%; min-width: 1200px;">
                <div class="meta-box-sortables ui-sortables">
                    <!-- List holder-->
                    <div class="postbox">
                        <div class="">
                            
                        </div>
                        <!--<span style="margin-left: 800px; position: fixed;">
	                    </span>-->
	                        
 							<div class="rightdiv toolTip" title="Add New In-Group" style="display: block;" id="t1">
<a id="activator" class="activator"  onClick="addlistoverlay();" style="text-decoration: none; ;"><b>Add New In-Group</b>  </a>
                        </div>
                    		<div class="rightdiv toolTip" title="Add New DID" style="display: none;" id="t2">
<a id="activator" class="activator"  onClick="adddidoverlay();" style="text-decoration: none;"><b>Add New DID</b>  </a>
                        </div>
                        <div class="rightdiv toolTip" title="Add New Call Menu" style="display: none;" id="t3">
<a id="callmenuadd" class="activator" onClick="addcallmenuoverlay();" style="text-decoration: none;"><b>Add New Call Menu</b>  </a>
                        </div>

                        <h3 class="hndle" style="height:13px">
							<span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;">Inbounds Listings</span>
                        </h3>

                        <!--<div class="inside">-->
							<div class="inside inside-tab">


<!--<div id="tabs" class=""  style="border: none;">-->
	<div id="tabs" class="tab-container" style="border: none;">
		<ul style="background: transparent; border-top: 0;">
			<li><a href="#tabs-1" id="atab1" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif;font-stretch: normal;">Ingroups</a></li>
			<li><a href="#tabs-2" id="atab2" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;">Phone Numbers (DIDs/TFNs)</a></li>
			<li><a href="#tabs-3" id="atab3" class="tab" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;">Interactive Voice Response (IVR) Menus</a></li>
		</ul>


	<div id="tabs-1">

				<!-- Ingroup TAB -->
				<div id="showlist" style="display: block;">
				
				<form name="showlistview" id="showlistview">
				<input type="hidden" name="showval" id="showval">

<!--				<table cellspacing="1" cellpadding="1" border="0" style="margin-left:auto; margin-right:auto; width:95%; border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; ">-->

                               <?php
                                            $permissions = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
                               ?>
				<table id="ingrouptable" class="tablesorter list-table" style="margin-top:4px;margin-left:auto; margin-right:auto; width:100%;" cellspacing="0px">
					<thead>
					<tr align="left" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;">
						<th class="thheader">&nbsp;&nbsp;<b>IN-GROUP</b> <!--<img src="http://192.168.100.112/img/arrow_down.png">--></th>
						<th colspan="" class="thheader"><b>DESCRIPTIONS</b> <!--<img src="http://192.168.100.112/img/arrow_down.png">--></th>
						<th class="thheader" align="center"><b>PRIORITY</b>&nbsp; <!--<img src="http://192.168.100.112/img/arrow_down.png">--></th>
						<th class="thheader"><b>STATUS</b>&nbsp; <!--<img src="http://192.168.100.112/img/arrow_down.png">--></th>
						<th class="thheader"><b>TIME</b> <!--<img src="http://192.168.100.112/img/arrow_down.png">--></th>
						<!--<td><b>COLOR</b></td>-->
						<!--<th><b>ACTION</b> <img src="http://192.168.100.112/img/arrow_down.png">  </th>-->
						<!--<th><input id="selectall" value="selectall" type="checkbox"></th>-->
						<th colspan="3" class="thheader" style="width:7%;text-align: right;">
						<span style="cursor:pointer;" id="selectAction" class="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span>
						</th>
						<th align="center" width="30px">
									<!--<input id="sellist" value="<?=$ingroupInfo->group_id;?>" type="checkbox">-->	
									<input type="checkbox" id="selectAllING"/>							 
						</th>
					</tr>
					</thead>
					<tbody> 
					<?php
					$countingroups = count($ingrouplists);

                                            if($permissions->inbound_read == "N"){
                                                echo("<tr class='tr2'><td colspan='9'>You don't have permission to view this record(s)</td></tr>");
                                                $countingroups = 0;
                                                $justpermission = true;
                                            }
					
					if($countingroups > 0 ) {

  
						
					   foreach($ingrouplists as $ingroupInfo){
					   	
					?>
							 <tr align="left" class="tr<?php echo alternator('1', '2') ?>">
							 
								<td align="left">&nbsp;
									<a id="idlink" class="leftDiv toolTip" style="cursor: pointer;" title="MODIFY <?=$ingroupInfo->group_id?>"  onClick="postval('<? echo $ingroupInfo->group_id; ?>');" ><? echo $ingroupInfo->group_id; ?></a>									

								</td>
								<td colspan="">
								<?
									echo ucwords(strtolower($ingroupInfo->group_name));							 
								?>
								</td>
								<td align="center">
									<?php
									echo $ingroupInfo->queue_priority;
									?>
								</td>
								<td align="left">
								<?php
								 	if($ingroupInfo->active=="Y") {
								 		echo "<b><font color=green>ACTIVE</font></b>";
								 	} else {
								 		echo "<b><font color=red>INACTIVE</font></b>";	
								 	}
								 	
								?>
								</td>
								<td align="left"><? echo $ingroupInfo->call_time_id; ?></td>
  								 <td style="text-align: center;">
  								 <img src="<?=$base?>img/edit.png" onclick="postval('<? echo $ingroupInfo->group_id; ?>');"  class="rightdiv toolTip" style="cursor:pointer;width:14px; padding: 3px;" title="MODIFY <?=$ingroupInfo->group_id?>"  />
  								 </td>
  								  <td align="left">
  								
								<?php
								
								if($ingroupInfo->group_id == "AGENTDIRECT") {
								?>
								<div class="rightdiv toolTip" title="Cannot delete AGENTDIRECT." style="padding:3px;">
								<img src="<?=$base?>img/delete_grayed.png" style="cursor:pointer;width:12px;" />
								<?php
								} else {
								?>
								<div class="rightdiv toolTip" title="DELETE <?=$ingroupInfo->group_id?>" style="padding:3px;">
						 			<img src="<?=$base?>img/delete.png" onclick="deletepostingroup('<? echo $ingroupInfo->group_id; ?>');" style="cursor:pointer;width:12px;"  />
								<?php
								}
								?>
						 			
						 			</div>
								 </td>
  								 <td align="left" style="text-align: center;">
  								 <div class="rightdiv toolTip" title="VIEW INFO FOR INBOUND <?=$ingroupInfo->group_id?>" style="padding: 3px;">
									<img style="cursor:pointer;width:12px;" src="<?=$base?>img/status_display_i.png" onclick="viewpost('<? echo $ingroupInfo->group_id; ?>');" style="cursor:pointer;width:14px;">
								</div>
								 </td>
								 <td align="center">
								<?php
								
								if($ingroupInfo->group_id == "AGENTDIRECT") {
								?>
									<input type="checkbox" id="AGENTDIRECTdeletenot[]" disabled/>						 			<?php
								} else {
									
								?>
									<input type="checkbox" id="delIngroup[]" value="<?=$ingroupInfo->group_id?>" />
								<?php
								}
								?>
						 			
								 </td>
								<!-- <td align="center">
									<input id="selingroup[]" value="<?=$ingroupInfo->group_id;?>" type="checkbox">								 
								 </td>	-->							  
								 
							 </tr>
							<?php
							$i++;
							}
							} else {
						
                                                            if(!$justpermission){	
							        echo "<td colspan=\"7\" align=\"center\" style=\"background-color: #EFEFEF;\"><font color=\"red\"><b>No record(s) found!</b></font></td>";		
                                                            }
							}
							?>
							
				</tbody> 							
				</table>
				</form>
				</div>
				<!-- end view -->
				
				
				<div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="boxaddlist" id="boxaddlist">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closemeadd();"></a>
				<!-- start add -->
				<div id="small_step_number" style="float:right; margin-top: -5px;">
					<img src="<?=$base?>img/step1-nav-small.png">
				</div>
				<div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
					<font color="black" style="font-size:16px;"><b>In-Group Wizard » Create New In-Group</b></font>
				</div>
				
				<div id="addlist" style="display: block;">
					<form  method="POST" id="go_listfrm" name="go_listfrm" method="POST">
				   	<input type="hidden" id="selectval" name="selectval" value="">
				   	<input type="hidden" id="addSUBMIT" name="addSUBMIT" value="addSUBMIT">
				   	
				   	
				<!--   <div align="left" class="title-header" style="font-family: Verdana;">Create new In-Group</div>-->
					<br>
						<table class="tableedit" width="100%" >
						  <tr>
						  		<td valign="top" style="width:20%">
									<div id="step_number" style="padding:0px 10px 0px 30px;">
								<img src="<?=$base?>img/step1-trans.png">
								</div>
								</td>
								<td style="padding-left:50px;" valign="top" colspan="2">
									<table width="100%">
										<tr>
											<td><label class="modify-value">Group ID:</label></td>
											<td><input type="text" name="group_id" id="group_id" size="30" onkeydown="return isAlphaNumericwospace(event.keyCode);" onkeyup="KeyUp(event.keyCode);"><br>
												<font color="red" size="1">
												*(no spaces). 2 and 20 characters in length
												</font>
											</td>
										</tr>
										<tr>
											<td><label class="modify-value">Group Name:</label></td>
											<td><input type="text" name="group_name" id="group_name" size="30" onkeydown="return isAlphaNumericwspace(event.keyCode);" onkeyup="KeyUp(event.keyCode);"><br>
												<font color="red" size="1">
												*2 and 20 characters in length
												</font>
											</td>
										</tr>
										<tr>
									       <td><label class="modify-value">Group Color:</label></td>
					                		<td><input class="color" type="text" name="group_color" id="group_color" size="7" maxlength="7" value="66ff00">
		                					</td>
		                				</tr>
		                				<tr>
											<td><label class="modify-value">Active:</label></td>
					                		<td>
					                			<select size="1" name="active" id="active">
					                				<option SELECTED>Y</option>
					                				<option>N</option>
					                			</select>
					                		</td>
					                 </tr>
					                	<tr>
					                		<td><label class="modify-value">Web Form:</label></td>
					                		<td>
												<input type="text" name="web_form_address" size="30" maxlength="1055">
											</td>
									  	</tr>
					                	<tr>
					                		<td><label class="modify-value">Voicemail:</label></td>
					                		<td>
					                			<input type="text" name="voicemail_ext" id="iWizvoicemail_ext" size=10 maxlength=10>
										<a href="javascript:launch_vm_chooser('iWizvoicemail_ext','vm',500,document.getElementById('iWizvoicemail_ext').value);"><FONT color="blue" size="1">[ Voicemail Chooser ]</a><div id="diviWizvoicemail_ext"></div>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td><label class="modify-value">Next Agent Call:</label></td>
					                		<td>
												<select size="1" name="next_agent_call" id="next_agent_call">
													<option>random</option>
													<option>oldest_call_start</option>
													<option>oldest_call_finish</option>
													<option>overall_user_level</option>
													<option>inbound_group_rank</option>
													<option>campaign_rank</option>
													<option>fewest_calls</option>
													<option>fewest_calls_campaign</option>
													<option>longest_wait_time</option>
													<option>ring_all</option>
												</select>
											</td>
										</tr>
					                	<tr>
					                		<td><label class="modify-value">Fronter Display:</label></td>
											<td>
												<select size="1" name="fronter_display">
													<option SELECTED>Y</option>
													<option>N</option>
												</select>
											</td>
										</tr>
										<tr>
											<td><label class="modify-value">Script:</label></td>
											<td>
												<select size="1" name="script_id" id="script_id">
													<?php
														foreach($scriptlists as $scriptlistsInfo){
															$script_id = $scriptlistsInfo->script_id;
															$script_name = $scriptlistsInfo->script_name;
															echo '<option value="'.$script_id.'">'.$script_id.'---'.$script_name.'</option>';
														}
													?>
												</select>					
										</tr>              
					   	           	<tr>
		   	           					<td><label class="modify-value">Get Call Launch:</label></td>
											<td> <select>
													<option selected="">NONE</option>
													<option>SCRIPT</option>
													<option>WEBFORM</option>
													<option>FORM</option>
												  </select> 
											</td>   	           	
					   	           	</tr>
					   	           	<tr><td colspan="2">&nbsp;</td></tr>
									</table>																	
								</td>
						  </tr>
						  <tr>
								<td align="right" colspan="8">
											<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
												<a id="searchcallhistory" style="cursor: pointer;" onclick="return formsubmitlist();"><font color="#7A9E22">Submit</font></a>		
											</div>		
											
								</td>			  
						  </tr>		
						</table>
			
					</form>
					
					</div>
				</div>
				<!-- end add -->
				
				<!--<br><br><br><br><br><br>	-->			
				
				<!-- edit -->
				<div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="box" id="box" style=" margin-top: -2700px;">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closeme();"></a>
				<div style="z-index: 999;position: absolute;height: 95%;width:95%;background-color:#FFF;" id="goLoading"><center><br /><br /><br /><br /><br /><br /><br /><br /><img src="<? echo $base; ?>img/goloading.gif" /></center></div>
				<form  method="POST" id="edit_go_listfrm" name="edit_go_listfrm">
					<input type="hidden" name="editlist" value="editlist">
					<input type="hidden" name="editval" id="editval">
					<input type="hidden" name="showvaledit" id="showvaledit" value="">
					<!--<input type="hidden" name="oldcampaignid" id="oldcampaignid" value="">-->
		
					
					<div id="listid_edit" align="left" class="title-header"> </div>
					<div align="center" class="title-header"> MODIFY INBOUND: <label id="egroup_id"></label></div>
					<br>
					<div align="left">					
					<!--<label class="modify-value">Change Date:</label>-->
					<!--<table width="100%" class="tableedit">
						<tr><td align="left"><div id="cdates"></div></td><td align="right"><div id="lcdates"></div></td></tr>
						<tr><td>Group ID: </td><td align=left><b></b></td></tr>
					</table>
					-->
					
					</div>  
<!--					<div style="width: auto; height:500px; overflow:scroll; overflow-x: hidden;">-->
					<div style="width: auto;">
					<?php
					
					## callmenu pulldown
					$countmenus = count($callmenupulldown);
					if($countmenus > 0){
					foreach($callmenupulldown as $callmenupulldownInfo){
						$menu_id = $callmenupulldownInfo->menu_id;
						$menu_name = $callmenupulldownInfo->menu_name;
						
						$Xmenuslist .= "<option ";
						$Xmenuslist .= "value=\"$menu_id\">$menu_id - $menu_name</option>\n";
					}			
					}
						if ($Xmenus_selected < 1) 
						{
							$Xmenuslist .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";
						}
					
					## ingroup pulldown
					$Xgroups_menu='';
					$Xgroups_selected=0;
					$Dgroups_menu='';
					$Dgroups_selected=0;
					$Agroups_menu='';
					$Agroups_selected=0;
					$Hgroups_menu='';
					$Hgroups_selected=0;
					$Tgroups_menu='';
					$Tgroups_selected=0;
				  

												
						  foreach($ingrouppulldown as $ingrouppulldownInfo){
						  			$group_id = $ingrouppulldownInfo->group_id;
						  			$group_name = $ingrouppulldownInfo->group_name;
						  			
									$Xgroups_menu .= "<option ";
									$Dgroups_menu .= "<option ";
									$Agroups_menu .= "<option ";
									$Tgroups_menu .= "<option ";
									$Hgroups_menu .= "<option ";
									
									if ($default_xfer_group == "$group_id") 
										{
										$Xgroups_menu .= "SELECTED ";
										$Xgroups_selected++;
										}
									if ($drop_inbound_group == "$group_id") 
										{
										$Dgroups_menu .= "SELECTED ";
										$Dgroups_selected++;
										}
									if ($afterhours_xfer_group == "$group_id") 
										{
										$Agroups_menu .= "SELECTED ";
										$Agroups_selected++;
										}
									if ($hold_time_option_xfer_group == "$group_id") 
										{
										$Tgroups_menu .= "SELECTED ";
										$Tgroups_selected++;
										}
									if ($hold_recall_xfer_group == "$group_id") 
										{
										$Hgroups_menu .= "SELECTED ";
										$Hgroups_selected++;
										}
									$Xgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
									$Dgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
									/*if ($group_id!=$group_id)
										{
*/										$Agroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
										$Tgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
										$Hgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
	//									}
						  			
						  } // end foreach
					
					  		if ($Xgroups_selected < 1) 
								{$Xgroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Xgroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
							if ($Dgroups_selected < 1) 
								{$Dgroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Dgroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
							if ($Agroups_selected < 1) 
								{$Agroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Agroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
							if ($Tgroups_selected < 1) 
								{$Tgroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Tgroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
							if ($Hgroups_selected < 1) 
								{$Hgroups_menu .= "<option SELECTED value=\"---NONE---\">---NONE---</option>\n";}
							else 
								{$Hgroups_menu .= "<option value=\"---NONE---\">---NONE---</option>\n";}
					
		                echo "<center><TABLE width=95% class=\"tableeditingroup\">\n";

		                echo "<tr class=trview><td align=right>Description: </td><td align=left><input type=text name=group_name id=egroup_name size=30 maxlength=30 onkeydown=\"return isAlphaNumericwspace(event.keyCode);\" onkeyup=\"KeyUp(event.keyCode);\"></td></tr>\n";

		                echo "<tr class=trview><td align=right>Color: </td><td align=left id=\"group_color_td\"><input class=color type=text name=group_color id=egroup_color size=7 maxlength=7></td></tr>\n";

		                echo "<tr class=trview><td align=right>Active: </td><td align=left><select size=1 name=active id=eactive><option>Y</option><option>N</option></select></td></tr>\n";

		                echo "<tr class=trview><td align=right>Web Form: </td><td align=left><input type=text id=eweb_form_address name=web_form_address size=70 maxlength=500></td></tr>\n";

		                echo "<tr class=trview><td align=right>Next Agent Call: </td><td align=left><select size=1 name=next_agent_call id=enext_agent_call><option >random</option><option>oldest_call_start</option><option>oldest_call_finish</option><option>overall_user_level</option><option>inbound_group_rank</option><option>campaign_rank</option><option>fewest_calls</option><option>fewest_calls_campaign</option><option>longest_wait_time</option><option>ring_all</option></select></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Queue Priority: </td><td align=left><select size=1 name=queue_priority id=equeue_priority>\n";
		                $n=99;
		                while ($n>=-99) {
		                        $dtl = 'Even';
		                        if ($n<0) {$dtl = 'Lower';}
		                        if ($n>0) {$dtl = 'Higher';}
		                        if ($n == $queue_priority)
		                                {echo "<option SELECTED value=\"$n\">$n - $dtl</option>\n";}
		                        else
		                                {echo "<option value=\"$n\">$n - $dtl</option>\n";}
		                        $n--;
		                }
		                echo "</select> </td></tr>\n";
		                
		                
		                echo "<tr class=trview><td align=right>Fronter Display: </td><td align=left><select size=1 name=fronter_display id=efronter_display><option>Y</option><option>N</option></select></td></tr>\n";
		                
		                echo "<tr class=trview><td align=right>Script: </td><td align=left>";
		                		$scripts_listS="<option value=\"\">NONE</option>\n";
		                		$countscipts = count($scriptlists);
		                		if($scriptlists > 0) {
													foreach($scriptlists as $scriptlistsInfo){
														$script_id = $scriptlistsInfo->script_id;
														$script_name = $scriptlistsInfo->script_name;
														$scripts_listS .= "<option value=\"$script_id\">$script_id - $script_name</option>\n";
														$scriptname_listS["$script_id"] = "$script_name";
														//echo '<option value="'.$script_id.'">'.$script_id.'---'.$script_name.'</option>';
													}
									}
						   echo "<select size=\"1\" name=\"ingroup_script\" id=\"escript_id\">";
						   echo "$scripts_listS";
						   echo "</select></td></tr>";
						   echo "<tr><td colspan=\"2\" align=\"left\">&nbsp;</td></tr>";
						   echo "<tr><td colspan=\"2\" align=\"left\">";
						   echo "<a id=\"clickadvanceplus\" style=\"cursor: pointer;\" onclick=\"$('#advanceid').css('display', 'block'); $('#clickadvanceplus').css('display', 'none'); $('#clickadvanceminus').css('display', 'block');  \"><font color=\"#7A9E22\" size=\"1px\">[ + ADVANCE SETTINGS ]</font></a><a id=\"clickadvanceminus\" style=\"cursor: pointer; display: none;\" onclick=\"$('#advanceid').css('display', 'none'); $('#clickadvanceplus').css('display', 'block'); $('#clickadvanceminus').css('display', 'none');\"><font color=\"#7A9E22\" size=\"1px\">[ - ADVANCE SETTINGS ]</font></a></td></tr>";
						   echo "</table>";
						   
						   echo "<TABLE width=\"95%\" class=\"tableadvace\" id=\"advanceid\">\n";
						  	echo "<tr class=trview><td align=right>On-Hook Ring Time: </td><td align=left><input type=text name=on_hook_ring_time id=eon_hook_ring_time size=5 maxlength=4 ></td></tr>\n";
						   echo "<tr class=trview><td align=right>Ignore List Script Override: </td><td align=left><select size=1 name=ignore_list_script_override id=eignore_list_script_override><option>Y</option><option>N</option></select></td></tr>\n";
		
						   echo "<tr class=trview><td align=right>Get Call Launch: </td><td align=left> <select name=get_call_launch id=eget_call_launch><option selected=\"\">NONE</option><option>SCRIPT</option><option>WEBFORM</option><option>FORM</option></select></td></tr>";
		
		                echo "<tr class=trview><td align=right>Transfer-Conf DTMF 1: </td><td align=left><input type=text name=xferconf_a_dtmf id=exferconf_a_dtmf size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Transfer-Conf Number 1: </td><td align=left><input type=text name=xferconf_a_number id=exferconf_a_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Transfer-Conf DTMF 2: </td><td align=left><input type=text name=xferconf_b_dtmf id=exferconf_b_dtmf size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Transfer-Conf Number 2: </td><td align=left><input type=text name=xferconf_b_number id=exferconf_b_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Transfer-Conf Number 3: </td><td align=left><input type=text name=xferconf_c_number id=exferconf_c_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Transfer-Conf Number 4: </td><td align=left><input type=text name=xferconf_d_number id=exferconf_d_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Transfer-Conf Number 5: </td><td align=left><input type=text name=xferconf_e_number id=exferconf_e_number size=20 maxlength=50></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Timer Action: </td><td align=left><select size=1 name=timer_action id=etimer_action><option selected>NONE</option><option>D1_DIAL</option><option>D2_DIAL</option><option>D3_DIAL</option><option>D4_DIAL</option><option>D5_DIAL</option><option>MESSAGE_ONLY</option><option>WEBFORM</option><option>HANGUP</option><option>CALLMENU</option><option>EXTENSION</option><option>IN_GROUP</option></select></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Timer Action Message: </td><td align=left><input type=text name=timer_action_message id=etimer_action_message size=50 maxlength=255></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Timer Action Seconds: </td><td align=left><input type=text name=timer_action_seconds id=etimer_action_seconds size=10 maxlength=10></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Timer Action Destination: </td><td align=left><input type=text name=timer_action_destination id=etimer_action_destination size=25 maxlength=30></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Drop Call Seconds: </td><td align=left><input type=text name=drop_call_seconds id=edrop_call_seconds size=5 maxlength=4></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Drop Action: </td><td align=left><select size=1 name=drop_action id=edrop_action><option>HANGUP</option><option>MESSAGE</option><option>VOICEMAIL</option><option>IN_GROUP</option></select></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>Drop Exten: </td><td align=left><input type=text name=drop_exten id=edrop_exten size=10 maxlength=20></td></tr>\n";
				
		                echo "<tr class=trview><td align=right>Voicemail: </td><td align=left><input type=text name=voicemail_ext id=evoicemail_ext size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('evoicemail_ext','vm',500,document.getElementById('evoicemail_ext').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</a><div id=\"divevoicemail_ext\"></div></td></tr>\n";
		
		                echo "<tr bgcolor=#99FFCC><td>Drop Transfer Group: </td><td align=left><select size=1 name=drop_inbound_group id=edrop_inbound_group>";
		                echo "$Dgroups_menu";
		                echo "</select></td></tr>\n";
		
		
		                echo "<tr class=trview><td align=right>Call Time: </td><td align=left><select size=1 name=call_time_id id=ecall_time_id>\n";

		                foreach($calltimespulldown as $calltimespulldownInfo){
			                	$call_time_id = $calltimespulldownInfo->call_time_id;
			                	$call_time_name = $calltimespulldownInfo->call_time_name;
		         				$selected_time="selected";
                				$call_times_list .= "<option value=\"$call_time_id\" $selected_time>$call_time_id - $call_time_name</option>\n";

                		  }
                		  
		                echo "$call_times_list";
		                echo "<option selected value=\"$call_time_id\">$call_time_id - $call_timename_list[$call_time_id]</option>\n";
		                echo "</select></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>After Hours Action: </td><td align=left><select size=1 name=after_hours_action id=eafter_hours_action><option>HANGUP</option><option>MESSAGE</option><option>EXTENSION</option><option>VOICEMAIL</option><option>IN_GROUP</option></select></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>After Hours Message Filename: </td><td align=left><input type=text name=after_hours_message_filename id=after_hours_message_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('after_hours_message_filename','date',600,document.getElementById('after_hours_message_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a><div id=\"divafter_hours_message_filename\"></div> </td></tr>\n";
		
		                echo "<t><td>After Hours Extension: </td><td align=left><input type=text name=after_hours_exten id=eafter_hours_exten size=10 maxlength=20></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>After Hours Voicemail: </td><td align=left><input type=text name=after_hours_voicemail id=after_hours_voicemail size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('after_hours_voicemail','vm',700,document.getElementById('after_hours_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divafter_hours_voicemail\"></div></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>After Hours Transfer Group: </td><td align=left><select size=1 name=afterhours_xfer_group id=afterhours_xfer_group>";
		                echo "<option value=\"\">--NONE--</option>\n";
		                echo "$Agroups_menu";
		                echo "</select></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>No Agents No Queueing: </td><td align=left><select size=1 name=no_agent_no_queue id=eno_agent_no_queue><option>Y</option><option>N</option><option>NO_PAUSED</option></select></td></tr>\n";
		
		                echo "<tr class=trview><td align=right>No Agent No Queue Action: </td><td align=left><select size=1 name=no_agent_action id=no_agent_action onChange=\"dynamic_call_action('no_agent_action','$no_agent_action','$no_agent_action_value','600');\"><option>CALLMENU</option><option>INGROUP</option><option>DID</option><option>MESSAGE</option><option>EXTENSION</option><option>VOICEMAIL</option></select>\n";				
						
							echo "<tr class=trview><td align=right>Welcome Message Filename: </td><td align=left><input type=text name=welcome_message_filename id=welcome_message_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('welcome_message_filename','date',800,document.getElementById('welcome_message_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divwelcome_message_filename\"></div> </td></tr>\n";
					
							echo "<tr class=trview><td align=right>Play Welcome Message: </td><td align=left><select size=1 name=play_welcome_message id=eplay_welcome_message><option>ALWAYS</option><option>NEVER</option><option>IF_WAIT_ONLY</option><option>YES_UNLESS_NODELAY</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Music On Hold Context: </td><td align=left><input type=text name=moh_context id=moh_context size=30 maxlength=50> <a href=\"javascript:launch_moh_chooser('moh_context','moh',800,document.getElementById('moh_context').value);\"><FONT color=\"blue\">[ Moh Chooser ]</font></a> <div id=\"divmoh_context\"></div></td></tr>\n";
					
							echo "<tr class=trview><td align=right>On Hold Prompt Filename: </td><td align=left><input type=text name=onhold_prompt_filename id=onhold_prompt_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('onhold_prompt_filename','date',800,document.getElementById('onhold_prompt_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divonhold_prompt_filename\"></div></td></tr>\n";
					
							echo "<tr class=trview><td align=right>On Hold Prompt Interval: </td><td align=left><input type=text name=prompt_interval id=prompt_interval size=5 maxlength=5></td></tr>\n";
					
							echo "<tr class=trview><td align=right>On Hold Prompt No Block: </td><td align=left><select size=1 name=onhold_prompt_no_block id=onhold_prompt_no_block><option>N</option><option>Y</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>On Hold Prompt Seconds: </td><td align=left><input type=text name=onhold_prompt_seconds id=onhold_prompt_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Play Place in Line: </td><td align=left><select size=1 name=play_place_in_line id=play_place_in_line><option>Y</option><option>N</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Play Estimated Hold Time: </td><td align=left><select size=1 name=play_estimate_hold_time id=play_estimate_hold_time><option>Y</option><option>N</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Calculate Estimated Hold Seconds: </td><td align=left><input type=text name=calculate_estimated_hold_seconds id=calculate_estimated_hold_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Estimated Hold Time Minimum Filename: </td><td align=left><input type=text name=eht_minimum_prompt_filename id=eht_minimum_prompt_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('eht_minimum_prompt_filename','date',800,document.getElementById('eht_minimum_prompt_filename').value);\"><FONT color=\"blue\"> [ Audio Chooser ]</font></a> <div id=\"diveht_minimum_prompt_filename\"></div> </td></tr>\n";
					
							echo "<tr class=trview><td align=right>Estimated Hold Time Minimum Prompt No Block: </td><td align=left><select size=1 name=eht_minimum_prompt_no_block id=eht_minimum_prompt_no_block><option>N</option><option>Y</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Estimated Hold Time Minimum Prompt Seconds: </td><td align=left><input type=text name=eht_minimum_prompt_seconds id=eht_minimum_prompt_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option: </td><td align=left><select size=1 name=wait_time_option id=wait_time_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Second Option: </td><td align=left><select size=1 name=wait_time_second_option id=wait_time_second_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Third Option: </td><td align=left><select size=1 name=wait_time_third_option id=wait_time_third_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option Seconds: </td><td align=left><input type=text name=wait_time_option_seconds id=wait_time_option_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option Extension: </td><td align=left><input type=text name=wait_time_option_exten id=wait_time_option_exten size=20 maxlength=20></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option Callmenu: </td><td align=left><select size=1 name=wait_time_option_callmenu id=wait_time_option_callmenu>\n";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Xmenuslist";
							echo "</select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option Voicemail: </td><td align=left><input type=text name=wait_time_option_voicemail id=wait_time_option_voicemail size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('wait_time_option_voicemail','vm',1100,document.getElementById('wait_time_option_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divwait_time_option_voicemail\"></div> </td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option Transfer In-Group: </td><td align=left><select size=1 name=wait_time_option_xfer_group id=wait_time_option_xfer_group>";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Tgroups_menu";
							echo "</select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option Press Filename: </td><td align=left><input type=text name=wait_time_option_press_filename id=wait_time_option_press_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('wait_time_option_press_filename','date',1200,document.getElementById('wait_time_option_press_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divwait_time_option_press_filename\"></div> </td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option Press No Block: </td><td align=left><select size=1 name=wait_time_option_no_block id=wait_time_option_no_block><option>N</option><option>Y</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option Press Filename Seconds: </td><td align=left><input type=text name=wait_time_option_prompt_seconds id=wait_time_option_prompt_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option After Press Filename: </td><td align=left><input type=text name=wait_time_option_callback_filename id=wait_time_option_callback_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('wait_time_option_callback_filename','date',1300,document.getElementById('wait_time_option_callback_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divwait_time_option_callback_filename\"></div></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Time Option Callback List ID: </td><td align=left><input type=text name=wait_time_option_callback_list_id id=wait_time_option_callback_list_id size=14 maxlength=14></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Wait Hold Option Priority: </td><td align=left><select size=1 name=wait_hold_option_priority id=wait_hold_option_priority><option>WAIT</option><option>BOTH</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Estimated Hold Time Option: </td><td align=left><select size=1 name=hold_time_option id=hold_time_option><option>NONE</option><option>EXTENSION</option><option>CALL_MENU</option><option>VOICEMAIL</option><option>IN_GROUP</option><option>CALLERID_CALLBACK</option><option>DROP_ACTION</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Second Option: </td><td align=left><select size=1 name=hold_time_second_option id=hold_time_second_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Third Option: </td><td align=left><select size=1 name=hold_time_third_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option Seconds: </td><td align=left><input type=text name=hold_time_option_seconds id=hold_time_option_seconds size=5 maxlength=5></td></tr>\n";

							echo "<tr class=trview><td align=right>Hold Time Option Minimum: </td><td align=left><input type=text name=hold_time_option_minimum id=hold_time_option_minimum size=5 maxlength=5></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option Extension: </td><td align=left><input type=text name=hold_time_option_exten id=hold_time_option_exten size=20 maxlength=20></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option Callmenu: </td><td align=left><select size=1 name=hold_time_option_callmenu id=hold_time_option_callmenu>\n";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Xmenuslist";
							echo "</select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option Voicemail: </td><td align=left><input type=text name=hold_time_option_voicemail id=hold_time_option_voicemail size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('hold_time_option_voicemail','vm',1100,document.getElementById('hold_time_option_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divhold_time_option_voicemail\"></div> </td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option Transfer In-Group: </td><td align=left><select size=1 name=hold_time_option_xfer_group id=hold_time_option_xfer_group>";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Tgroups_menu";
							echo "</select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option Press Filename: </td><td align=left><input type=text name=hold_time_option_press_filename id=hold_time_option_press_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('hold_time_option_press_filename','date',1200,document.getElementById('hold_time_option_press_filename').value);\"><FONT color=\"blue\"><FONT color=\"blue\">[ Audio Chooser]</font></a> <div id=\"divhold_time_option_press_filename\"></div></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option Press No Block: </td><td align=left><select size=1 name=hold_time_option_no_block id=hold_time_option_no_block><option>N</option><option>Y</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option Press Filename Seconds: </td><td align=left><input type=text name=hold_time_option_prompt_seconds id=hold_time_option_prompt_seconds size=5 maxlength=5></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option After Press Filename: </td><td align=left><input type=text name=hold_time_option_callback_filename id=hold_time_option_callback_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('hold_time_option_callback_filename','date',1300,document.getElementById('hold_time_option_callback_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a><div id=\"divhold_time_option_callback_filename\"></div> </td></tr>\n";
					
							echo "<tr class=trview><td align=right>Hold Time Option Callback List ID: </td><td align=left><input type=text name=hold_time_option_callback_list_id id=hold_time_option_callback_list_id size=14 maxlength=14></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Agent Alert Filename: </td><td align=left><input type=text name=agent_alert_exten id=agent_alert_exten size=30 maxlength=100> <a href=\"javascript:launch_chooser('agent_alert_exten','date',1500,document.getElementById('agent_alert_exten').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divagent_alert_exten\"></div></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Agent Alert Delay: </td><td align=left><input type=text name=agent_alert_delay id=agent_alert_delay size=6 maxlength=6></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Default Transfer Group: </td><td align=left><select size=1 name=default_xfer_group id=default_xfer_group>";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "<option value=\"AGENTDIRECT\">AGENTDIRECT - Single Agent Direct Queue</option>";
							echo "$Xgroups_menu";
							echo "</select></td></tr>\n";
						
							echo "<tr><td align=left>Default Group Alias: </td><td align=left><select size=1 name=default_group_alias id=default_group_alias>";
							## group alias pulldown
							foreach($groupaliaspulldown as $groupaliaspulldownInfo) {
								$group_alias_id = $groupaliaspulldownInfo->group_alias_id;
								$group_alias_name = $groupaliaspulldownInfo->group_alias_name;
						
								$group_alias_menu .= "value=\"$group_alias_id\">$group_alias_id - $group_alias_name</option>\n";
							}
					
							echo "<option value=\"\">--NONE--</option>";
							echo "$group_alias_menu";
							echo "</select></td></tr>\n";
							
							echo "<tr class=trview><td align=right>Hold Recall Transfer In-Group: </td><td align=left><select size=1 name=hold_recall_xfer_group id=hold_recall_xfer_group>";
							echo "<option value=\"\">--NONE--</option>\n";
							echo "$Hgroups_menu";
							echo "</select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>No Delay Call Route: </td><td align=left><select size=1 name=no_delay_call_route id=no_delay_call_route><option>Y</option><option>N</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>In-Group Recording Override: </td><td align=left><select size=1 name=ingroup_recording_override id=ingroup_recording_override><option>DISABLED</option><option>NEVER</option><option>ONDEMAND</option><option>ALLCALLS</option><option>ALLFORCE</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>In-Group Recording Filename: </td><td align=left><input type=text name=ingroup_rec_filename id=ingroup_rec_filename size=50 maxlength=50></td></tr>\n";
							
							echo "<tr class=trview><td align=right>Stats Percent of Calls Answered Within X seconds 1: </td><td align=left><input type=text name=answer_sec_pct_rt_stat_one id=answer_sec_pct_rt_stat_one size=5 maxlength=5 ></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Stats Percent of Calls Answered Within X seconds 2: </td><td align=left><input type=text name=answer_sec_pct_rt_stat_two id=answer_sec_pct_rt_stat_two size=5 maxlength=5></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Start Call URL: </td><td align=left><input type=text name=start_call_url id=start_call_url size=70 maxlength=2000 ></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Dispo Call URL: </td><td align=left><input type=text name=dispo_call_url id=dispo_call_url size=70 maxlength=2000 ></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Add Lead URL: </td><td align=left><input type=text name=add_lead_url id=add_lead_url size=70 maxlength=2000></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Extension Append CID: </td><td align=left><select size=1 name=extension_appended_cidname id=extension_appended_cidname><option>Y</option><option>N</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Uniqueid Status Display: </td><td align=left><select size=1 name=uniqueid_status_display id=uniqueid_status_display><option>DISABLED</option><option>ENABLED</option><option>ENABLED_PREFIX</option><option>ENABLED_PRESERVE</option></select></td></tr>\n";
					
							echo "<tr class=trview><td align=right>Uniqueid Status Prefix: </td><td align=left><input type=text name=uniqueid_status_prefix id=uniqueid_status_prefix size=10 maxlength=50></td></tr>\n";
							
							
				?>
				</table>
				<TABLE width="95%" class="tableeditingroup">
				<tr>					
				  <td colspan="2" align="right">
				 <!-- <input type="button" name="editSUBMIT" value="MODIFY" onclick="editpost(document.getElementById('showvaledit').value);">-->

				 <div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">

				  <a id="searchcallhistory" style="cursor: pointer;" onclick="editpost(document.getElementById('showvaledit').value);"><font color="#7A9E22">SAVE SETTINGS</font></a>
				  </div>	
				  </td>
				</tr>
				</table>
				</form>
				<br><br>
				
				<form  method="POST" id="agentrankform" name="agentrankform">
				<?php
					/*echo "<center>\n";
					echo "<br><b><font color=black>AGENT RANKS FOR THIS INBOUND GROUP:</b></font><br>\n";
					*/
					echo "<TABLE width=\"60%\" cellspacing=3 class=\"tableeditingroup\" id=\"agentrankvalue\">\n";										
					
					echo "</TABLE></center>";
				?>
				</form>
								
				</div>
				</center>
				
				</div>	
				<!-- end edit -->
				
							    <!-- view edit -->
			    <div class="overlay" id="overlay" style="display:none;"></div>
		
				<div class="boxviewlist" id="boxviewlist">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closemeview();"></a>
 				
 				<table summary="" >
					<tr>
					<td>
						<b>Inbound I.D.: </b>					
					</td>
					<td>
						<div id="viewinboundid" align="left"> </div>
					</td>
					</tr>
					<tr>
					<td>
						<b>Description: </b>					
					</td>
					<td>
						<div id="viewinbounddesc" align="left"> </div>
					</td>
					</tr>
					<tr>					
					<td>
						<b>Status: </b>					
					</td>
					<td>
						<div id="viewinboundstatus" align="left"> </div>
					</td>					
					</tr>
					<!--<tr>
					<td>
						<b>Last call date: </b>					
					</td>
					<td>
						<div id="viewlistcalldate" align="left"> </div>
					</td>
					
					</tr>-->
				</table>			
	 				
			    </center>
			    </div>
			    <!-- end view edit -->						

	</div> <!-- end LISTs -->
	
	
	
	
	
	
	
	
	<!-- DID FIELDS -->
	<div id="tabs-2">
		<div style="display: block;">
				<!--<a id="activator" class="activator"  onClick="adddidoverlay();" style="text-decoration: none;"><b>Add New DID</b>  </a>-->
				<form id="didlist" name="didlist">
				<input type="hidden" name="didval" id="didval">
				<table id="didtable" class="tablesorter list-table" style="margin-top:4px;margin-left:auto; margin-right:auto; width: 100%; " cellspacing="0px">
					<thead>
						<tr align="left" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;">
						<th class="thheader">&nbsp;&nbsp;<b>Phone Numbers</b> </th>
						<th colspan="" class="thheader"><b>DESCRIPTIONS</b></th>
						<th class="thheader"><b>STATUS</b>&nbsp; </th>
						<th class="thheader"><b>ROUTE</b> </th>
						<th class="thheader"><b>REC</b> </th>
						<th colspan="3" class="thheader" style="width:7%;" align="right">
						<span style="cursor:pointer;" id="selectAction" class="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span>
						</th>
						<th align="center" width="30px">
									<input type="checkbox" id="selectAllDID" />							 
						</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$countdids = count($getdids);

                                                        if($permissions->inbound_read == "N"){
                                                             echo("<tr class='tr2'><td colspan='7'>You don't have permission to view this record(s)</td></tr>");
                                                             $countdids = 0;
                                                             $justpermission = true;
                                                        }
							
							if($countdids > 0 ) {
						
					   			foreach($getdids as $getdidsInfo){
						?>
								<tr align="left" class="tr<?php echo alternator('1', '2') ?>">
									<td align="left">&nbsp;
										<a id="idlink" class="leftDiv toolTip" style="cursor:pointer;" title="MODIFY <?=$getdidsInfo->did_id;?>"  onclick="didpostval('<? echo $getdidsInfo->did_id; ?>');">
										<?php
											echo $getdidsInfo->did_pattern;
										?></a>
									</td>
									<td colspan="">
										<?php
											echo $getdidsInfo->did_description;
										?>
									</td>
									<td>
										<?php
								 		if($getdidsInfo->did_active=="Y") {
								 			echo "<b><font color=green>ACTIVE</font></b>";
								 		} else {
								 			echo "<b><font color=red>INACTIVE</font></b>";	
								 		
								 		}
								 		?>
									</td>
									<td>
										<?php
											echo $getdidsInfo->did_route;
										?>									
									</td>
									<td>
										<?php
											echo $getdidsInfo->record_call; 
										?>									
									</td>
									 <td>
  								 <img src="<?=$base?>img/edit.png" onclick="didpostval('<? echo $getdidsInfo->did_id; ?>');"  class="rightdiv toolTip" style="cursor:pointer;width:14px; padding: 3px;" title="MODIFY <?=$getdidsInfo->did_id;?>"  />
  								 </td>
  								  <td align="">
  								 <div class="rightdiv toolTip" title="DELETE <?=$getdidsInfo->did_pattern;?>" style="padding:3px;">
						 			<img src="<?=$base?>img/delete.png" onclick="deletepostdid('<?=$getdidsInfo->did_id;?>','<?=$getdidsInfo->did_description?>');" style="cursor:pointer;width:12px;"  />
						 			</div>
								 </td>
  								 <td align="">
  									<div class="rightdiv toolTip" title="VIEW INFO FOR DID <?=$getdidsInfo->did_id;?>" style="padding: 3px;">
									<img style="cursor:pointer;width:12px;" src="<?=$base?>img/status_display_i.png" onclick="didviewpost('<? echo $getdidsInfo->did_id; ?>');" style="cursor:pointer;width:14px;">
									</div>
								 </td>
								 <td align="center" >
							<input type="checkbox" id="delDID[]" value="<?=$getdidsInfo->did_id;?>" />						 
								 </td>
								</tr>
							<?php
								}
							} else {
                                                            if(!$justpermission){
							?>
						<tr>
							<td colspan="8" align="center" style="background-color: #EFEFEF;">
								<font color="red"><b>No record(s) found!</b></font>
							</td>	
						</tr>
							<?php
                                                             }
							}
							?>
					</tbody>
			</table>			
			</form>
		</div>
		
		<!--- ADD DID -->
		<div class="overlaydidlist" id="overlaydidlist" style="display:none;"></div>
				<div class="didaddlist" id="didaddlist">
				<center>
 					<a class="boxclose" id="" onclick="closemeadddid();"></a>
 					<div id="small_step_number" style="float:right; margin-top: -5px;">
						<img src="<?=$base?>img/step1-nav-small.png">
					</div>
					<div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
						<font color="black" style="font-size:16px;"><b>DID Wizard » Create New DID</b></font>
					</div>
					<br>
					<form  method="POST" id="go_didfrm" name="go_didfrm" method="POST">
				   	<input type="hidden" id="selectval" name="selectval" value="">
				   	<input type="hidden" id="addDID" name="addDID" value="addDID">	
				  			
 						<table class="tableedit" width="99%" >
						  <tr>
						  		<td valign="top" style="width:20%">
						  			<div id="step_number" style="padding:0px 10px 0px 30px;">
										<img src="<?=$base?>img/step1-trans.png">
									</div>
								</td>
								<td style="padding-left:50px;" valign="top" colspan="2">
									<table width="100%">
										<tr>
											<td><label class="modify-value">DID Extension:</label></td>
											<td><input type="text" name="did_pattern" id="did_pattern" size="30" maxlength="50">
											</td>
										</tr>
										<tr>
											<td><label class="modify-value">DID Description:</label></td>
											<td>
												<input type="text" name="did_description" id="did_description" size="30" maxlength="50" onkeydown="return isAlphaNumericwspace(event.keyCode);" onkeyup="KeyUp(event.keyCode);">
												<!--<br>
												<font color="red" size="1">
													*(do not remove your account code)
												</font>-->
											</td>
										</tr>
										<tr>
											<td><label class="modify-value">Active:</label></td>
											<td><?php echo form_dropdown('active',array('Y'=>'Y','N'=>'N'),null,'id="active"'); ?>
											</td>
										</tr>
										<tr>
											<td><label class="modify-value">DID Route:</label></td>
											<td><?php echo form_dropdown('did_route',array('AGENT'=>'Agent','IN_GROUP'=>'In-group','PHONE'=>'Phone','CALLMENU'=>'Call Menu / IVR','VOICEMAIL'=>'Voicemail','EXTEN'=>'Custom Extension'),null,'id="did_route" onchange="showRouteOptions(document.getElementById(\'did_route\').value,\'Wizard\')"'); ?>
											</td>
										</tr>
										<?php
										if (count($agent_list))
										{
											$Agent_menu = "<option value=\"\">--NONE--</option>";
											foreach ($agent_list as $agent)
											{
												$Agent_menu .= "<option value=\"{$agent->user}\">{$agent->user} - {$agent->full_name}</option>\n";
											}
										} else {
											$Agent_menu = "<option value=\"\">--NONE--</option>";
										}
										?>
										<tr class="didAgentWizard" style="display: none">
											<td><label class="modify-value">Agent ID:</label></td>
											<td><select id="user" name="user">
											<?php echo "$Agent_menu"; ?>
											</select>
											</td>
										</tr>
										<tr class="didAgentWizard" style="display: none">
											<td><label class="modify-value">Agent Unavailable Action:</label></td>
											<td><?php echo form_dropdown('user_unavailable_action',array('VOICEMAIL'=>'Voicemail','PHONE'=>'Phone','IN_GROUP'=>'In-group','EXTEN'=>'Custom Extension'),null,'id="user_unavailable_action"'); ?>
											</td>
										</tr>
										<tr class="" style="display: none">
											<td style="white-space: nowrap;"><label class="modify-value">Agent Route Settings In-Group:</label></td>
											<td><select id="user_route_settings_ingroup" name="user_route_settings_ingroup" style="width:300px;">
											<option value="AGENTDIRECT">AGENTDIRECT - Single Agent Direct Queue</option>
											<?php echo "$Xgroups_menu"; ?>
											</select>
											<script>
											$(function()
											{
												$('#user_route_settings_ingroup').val('AGENTDIRECT');
											});
											</script>
											</td>
										</tr>
										<tr class="didExtensionWizard" style="display: none">
											<td><label class="modify-value">Extension:</label></td>
											<td><?php echo form_input('extension',null,'id="extension" size="30" maxlength="50"'); ?>
											</td>
										</tr>
										<tr class="didExtensionWizard" style="display: none">
											<td><label class="modify-value">Extension Context:</label></td>
											<td><?php echo form_input('exten_context',null,'id="exten_context" size="30" maxlength="50"'); ?>
											</td>
										</tr>
										<tr class='didVoicemailWizard' style='display:none'>
											<td><label class="modify-value">Voicemail Box:</label></td>
											<td align=left><input type=text name=voicemail_ext id=wizvoicemail_ext size=12 maxlength=10> <a href="javascript:launch_vm_chooser('wizvoicemail_ext','vm',500,document.getElementById('wizvoicemail_ext').value);"><FONT color="blue" size="1">[ Voicemail Chooser ]</a><div id="divwizvoicemail_ext"></div>
											</td>
										</tr>
										<?php
										if (count($phone_list))
										{
											$Phone_menu = "<option value=\"\">--NONE--</option>";
											foreach ($phone_list as $phone)
											{
												$Phone_menu .= "<option value=\"{$phone->extension}\">{$phone->extension} - {$phone->server_ip} - {$phone->dialplan_number}</option>\n";
											}
										} else {
											$Phone_menu = "<option value=\"\">--NONE--</option>";
										}
										?>
										<tr class="didPhoneWizard" style="display: none">
											<td><label class="modify-value">Phone Extension:</label></td>
											<td><select id="phone" name="phone">
											<?php echo "$Phone_menu"; ?>
											</select>
											</td>
										</tr>
										<?php
										$countgetservers = count($getservers);
										$servers_list = "";
										if($countgetservers > 0) {
											foreach($getservers as $getserversInfo) {
												$server_ip = $getserversInfo->server_ip;
												$server_description = $getserversInfo->server_description;
												$external_server_ip = $getserversInfo->external_server_ip;
												$servers_list .= "<option value=\"$server_ip\">$server_ip - $server_description - $external_server_ip </option>\n";
											}
											$servers_list .= "<option value=\"\">--NONE--</option>";
										} else {
											$servers_list .= "<option value=\"\">--NONE--</option>";	
										}
										?>
										<tr class="didPhoneWizard" style="display: none">
											<td><label class="modify-value">Server IP:</label></td>
											<td><select id="server_ip" name="server_ip" style="width:300px;">
											<?php echo "$servers_list"; ?>
											</select>
											</td>
										</tr>
										<tr class='didInboundWizard' style='display:none'>
											<td><label class="modify-value">In-Group ID:</label></td>
											<td><select size=1 name=group_id id=wizgroup_id style="width:300px">
											<option value="AGENTDIRECT">AGENTDIRECT - Single Agent Direct Queue</option>
											<?php echo "$Dgroups_menu"; ?>
											</select>
											<script>
											$(function()
											{
												$('#wizgroup_id').val('AGENTDIRECT');
											});
											</script>
											</td>
										</tr>										
										<tr class='' style='display:none'>
											<td><label class="modify-value">In-Group List ID:</label></td>
											<td><input type=text name=list_id id=wizlist_id size=14 maxlength=14 value="999" >
											</td>
										</tr>
										<?php
										$countcampaigns_list = count($campaigns_list);
										if($countcampaigns_list > 0) {
											foreach($campaigns_list as $campaigns_listInfo){
												$campaign_id = $campaigns_listInfo->campaign_id;
												$campaign_name = $campaigns_listInfo->campaign_name;
												$campaigns_list .= "<option value=\"$campaign_id\">$campaign_id - $campaign_name</option>\n";
											}
										}
										?>
										<tr class='' style='display:none'>
											<td style="white-space: nowrap"><label class="modify-value">In-Group Campaign ID:</label></td>
											<td><select size=1 name=campaign_id id=wizcampaign_id style="width:300px">
											<option value=\"\">--NONE--</option>
											<?php echo "$campaigns_list"; ?>
											</select>
											</td>
										</tr>
										<tr class='didCallMenuWizard' style='display:none'>
											<td><label class="modify-value">Call Menu:</label></td>
											<td><select size=1 name=menu_id id=wizmenu_id style="width:350px">";
											<?php echo "$Xmenuslist"; ?>
											</select>
											</td>
										</tr>
										<tr><td colspan="2">&nbsp;</td></tr>
										<tr><td colspan="2">&nbsp;</td></tr>
									</table>																	
								</td>
						  </tr>
						  <tr>
								<td align="right" colspan="4">
									<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
									<!-- <a id="searchcallhistory" style="cursor: pointer;" onclick="document['go_didfrm'].submit()"><font color="#7A9E22">Submit</font></a>-->		
									<a id="searchcallhistory" style="cursor: pointer;" onclick="return formsubmitdid();"><font color="#7A9E22">Submit</font></a>		
									</div>		
											
								</td>			  
						  </tr>
						  		
						</table>
 						</form>
 				</center>
 				</div>
			<!--- ADD END DID --> 
			
			<!-- EDIT DID -->
			<div class="overlayeditdid" id="overlayeditdid" style="display:none;"></div>
			<div class="dideditlist" id="dideditlist" style=" margin-top: -800px;">
			
			<a class="boxclose" id="" onclick="closemeeditdid();"></a>
			<div align="center" class="title-header"> MODIFY DID RECORD: <label id="ediddesc"></label></div>
			<br>
			<form  method="POST" id="go_editdidfrm" name="go_editdidfrm" method="POST">
				<input type="hidden" id="selectval" name="selectval" value="">
				<input type="hidden" id="editDID" name="editDID" value="editDID">	
				<input type="hidden" id="didvals" name="didvals">
		<?

		$countcampaigns_list = count($campaigns_list);
		if($countcampaigns_list > 0) {
			foreach($campaigns_list as $campaigns_listInfo){
				$campaign_id = $campaigns_listInfo->campaign_id;
				$campaign_name = $campaigns_listInfo->campaign_name;
				$campaigns_list .= "<option value=\"$campaign_id\">$campaign_id - $campaign_name</option>\n";
			}
			$campaigns_list .= "<option value=\"\">--NONE--</option>";
		} else {
			$campaigns_list .= "<option value=\"\">--NONE--</option>";	
		}
		
		$countgetservers = count($getservers);
		$servers_list = "";
		if($countgetservers > 0) {
			foreach($getservers as $getserversInfo) {
				$server_ip = $getserversInfo->server_ip;
				$server_description = $getserversInfo->server_description;
				$external_server_ip = $getserversInfo->external_server_ip;
				$servers_list .= "<option value=\"$server_ip\">$server_ip - $server_description - $external_server_ip </option>\n";
			}
			$servers_list .= "<option value=\"\">--NONE--</option>";
		} else {
			$servers_list .= "<option value=\"\">--NONE--</option>";	
		}
		
		$countgetfpgroups = count($getfpgroups);		
		if($countgetfpgroups > 0) {
			foreach($getfpgroups as $getfpgroupsInfo) {
				$filter_phone_group_id = $getfpgroupsInfo->filter_phone_group_id;
				$filter_phone_group_name = $getfpgroupsInfo->filter_phone_group_name;
				$Fgroups_list .= "<option value=\"$filter_phone_group_id\">$filter_phone_group_id - $filter_phone_group_name</option>";
			}
			$Fgroups_list .= "<option value=\"\">--NONE--</option>";
		} else {
			$Fgroups_list .= "<option value=\"\">--NONE--</option>";	
		}
		
		$countingroups2 = count($ingrouplists);
		
		if($countingroups2 > 0 ) {
			foreach($ingrouplists as $ingroupInfo2) {
				$egroup_id = $ingroupInfo2->group_id;
				$egroup_name = $ingroupInfo2->group_name;
				$FXgroups_menu .= "<option value=\"$egroup_id\">$egroup_id - $egroup_name</option>\n";
			}
			$FXgroups_menu .= "<option value=\"\">--NONE--</option>";
		} else {
			$FXgroups_menu .= "<option value=\"\">--NONE--</option>";
		}
		
		$countingrouppulldown2 = count($ingrouppulldown);
		if($countingrouppulldown2 > 0) {
			foreach($ingrouppulldown as $ingrouppulldownInfo2) {
				$edgroup_id = $ingrouppulldownInfo2->group_id;
				$edgroup_name = $ingrouppulldownInfo2->group_name;
				$FDgroups_menu .= "<option value=\"$edgroup_id\">$edgroup_id - $edgroup_name</option>\n";
			}
			$FDgroups_menu .= "<option value=\"\">--NONE--</option>";
		} else {
			$FDgroups_menu .= "<option value=\"\">--NONE--</option>";
		}
		
		if (count($agent_list))
		{
			$Agent_menu = "<option value=\"\">--NONE--</option>";
			foreach ($agent_list as $agent)
			{
				$Agent_menu .= "<option value=\"{$agent->user}\">{$agent->user} - {$agent->full_name}</option>\n";
			}
		} else {
			$Agent_menu = "<option value=\"\">--NONE--</option>";
		}
		
		if (count($phone_list))
		{
			$Phone_menu = "<option value=\"\">--NONE--</option>";
			foreach ($phone_list as $phone)
			{
				$Phone_menu .= "<option value=\"{$phone->extension}\">{$phone->extension} - {$phone->server_ip} - {$phone->dialplan_number}</option>\n";
			}
		} else {
			$Phone_menu = "<option value=\"\">--NONE--</option>";
		}
		

		echo "<center><TABLE width='100%'>\n";
		echo "<tr><td align=right width='35%'>DID Extension: </td><td align=left><input type=text name=did_pattern id=edid_pattern size=30 maxlength=50></td></tr>\n";
		
		echo "<tr><td align=right>DID Description: </td><td align=left><input type=text name=did_description id=edid_description size=40 maxlength=50 onkeydown=\"return isAlphaNumericwspace(event.keyCode);\" onkeyup=\"KeyUp(event.keyCode);\"></td></tr>\n";
		
		echo "<tr><td align=right>Active: </td><td align=left><select size=1 name=did_active id=edid_active><option>Y</option><option>N</option></select></td></tr>\n";
		
		echo "<tr class='didAdvanceSettings' style='display:none'><td align=right>Record Call: </td><td align=left><select size=1 name=record_call id=erecord_call><option>N</option><option>Y_QUEUESTOP</option><option>Y</option></select></td></tr>\n";
		
		echo "<tr><td align=right>DID Route: </td><td align=left><select size=1 name=did_route id=edid_route><option value=\"AGENT\">Agent</option><option value=\"IN_GROUP\">In-group</option><option value=\"PHONE\">Phone</option><option value=\"CALLMENU\">Call Menu / IVR</option><option value=\"VOICEMAIL\">Voicemail</option><option value=\"EXTEN\">Custom Extension</option></select></td></tr>\n";
		
		echo "<tr class='trview didExtensionGroup' style='display:none'><td align=right>Extension: </td><td align=left><input type=text name=extension id=eextension size=40 maxlength=50></td></tr>\n";
		
		echo "<tr class='trview didExtensionGroup' style='display:none'><td align=right>Extension Context: </td><td align=left><input type=text name=exten_context id=eexten_context size=40 maxlength=50></td></tr>\n";
		
		echo "<tr class='trview didVoicemailGroup' style='display:none'><td align=right>Voicemail Box: </td><td align=left><input type=text name=voicemail_ext id=didvoicemail_ext size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('didvoicemail_ext','vm',500,document.getElementById('didvoicemail_ext').value);\"><FONT color=\"blue\" size=\"1\">[ Voicemail Chooser ]</a><div id=\"divdidvoicemail_ext\"></div></td></tr>\n";
		
		echo "<tr class='trview didPhoneGroup' style='display:none'><td align=right>Phone Extension: </td><td align=left><select name=phone id=ephone>";
		echo "$Phone_menu";
		echo "</select></td></tr>\n";
		
		echo "<tr class='trview didPhoneGroup' style='display:none'><td align=right>Server IP: </td><td align=left><select size=1 name=server_ip>\n";
		echo "$servers_list";
		echo "</select></td></tr>\n";
		
		echo "<tr class='trview didCallMenuGroup' style='display:none'><td align=right>Call Menu: </td><td align=left><select size=1 name=menu_id id=emenu_id>";
		echo "$Xmenuslist";
		echo "</select></td></tr>\n";

		echo "<tr class='trview didAgentGroup' style='display:none'><td align=right>Agent ID: </td><td align=left><select name=user id=euser>";
		echo "$Agent_menu";
		echo "</select></td></tr>\n";
		
		echo "<tr class='trview didAgentAdvance' style='display:none'><td align=right>Agent Unavailable Action: </td><td align=left><select size=1 name=user_unavailable_action id=euser_unavailable_action><option value=\"VOICEMAIL\">Voicemail</option><option value=\"PHONE\">Phone</option><option value=\"IN_GROUP\">In-group</option><option value=\"EXTEN\">Custom Extension</option></select></td></tr>\n";
		
		echo "<tr class='trview didAgentAdvance' style='display:none'><td align=right>Agent Route Settings In-Group: </td><td align=left><select size=1 name=user_route_settings_ingroup id=euser_route_settings_ingroup>";
		echo "<option value=\"AGENTDIRECT\">AGENTDIRECT - Single Agent Direct Queue</option>";
		echo "$Xgroups_menu";
		echo "</select></td></tr>\n";
		
		echo "<tr class='trview didInboundGroup' style='display:none'><td align=right>In-Group ID: </td><td align=left><select size=1 name=group_id id=egroup_id2>";
		echo "$Dgroups_menu";
		echo "</select></td></tr>\n";
		
		echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group Call Handle Method: </td><td align=left><select size=1 name=call_handle_method id=ecall_handle_method><option>CID</option><option>CIDLOOKUP</option><option>CIDLOOKUPRL</option><option>CIDLOOKUPRC</option><option>ANI</option><option>ANILOOKUP</option><option>ANILOOKUPRL</option><option>VIDPROMPT</option><option>VIDPROMPTLOOKUP</option><option>VIDPROMPTLOOKUPRL</option><option>VIDPROMPTLOOKUPRC</option><option>CLOSER</option><option>3DIGITID</option><option>4DIGITID</option><option>5DIGITID</option><option>10DIGITID</option></select></td></tr>\n";
		
		echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group Agent Search Method: </td><td align=left><select size=1 name=agent_search_method id=eagent_search_method><option value=\"LB\">LB - Load Balanced</option><option value=\"LO\">LO - Load Balanced Overflow</option><option value=\"SO\">SO - Server Only</option></select></td></tr>\n";
		
		echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group List ID: </td><td align=left><input type=text name=list_id id=elist_id size=14 maxlength=14 ></td></tr>\n";
		
		echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group Campaign ID: </td><td align=left><select size=1 name=campaign_id id=ecampaign_id>\n";
		echo "<option value=\"\">--NONE--</option>";
		echo "$campaigns_list";
		echo "</select></td></tr>\n";
		
		
		
		echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group Phone Code: </td><td align=left><input type=text name=phone_code id=ephone_code size=14 maxlength=14></td></tr>\n";


		echo "<tr class='didAdvanceSettings' style='display:none'><td align=right>Clean CID Number: </td><td align=left><input type=text name=filter_clean_cid_number id=efilter_clean_cid_number size=20 maxlength=20 ></td></tr>\n";

		echo "<tr class='didAdvanceSettings' style='display:none'><td align=right>Filter Inbound Number: </td><td align=left><select size=1 name=filter_inbound_number id=efilter_inbound_number><option value=\"DISABLED\">DISABLED</option><option value=\"GROUP\">GROUP</option><option value=\"URL\">URL</option></select></td></tr>\n";


		echo "<tr class='trview filterGroup' style='display:none'><td align=right>Filter Phone Group ID: </td><td align=left><select size=1 name=filter_phone_group_id id=efilter_phone_group_id>$Fgroups_list</select></td></tr>\n";

		echo "<tr class='trview filterURL' style='display:none'><td align=right>Filter URL: </td><td align=left><input type=text name=filter_url id=efilter_url size=30 maxlength=1000 ></td></tr>\n";
		
		echo "<tr class='trview filterAction' style='display:none'><td align=right>Filter Action: </td><td align=left><select size=1 name=filter_action id=efilter_action onchange=\"showRouteOptions(document.getElementById('efilter_action').value,'Filter')\"><option value=\"AGENT\">Agent</option><option value=\"IN_GROUP\">In-group</option><option value=\"PHONE\">Phone</option><option value=\"CALLMENU\">Call Menu / IVR</option><option value=\"VOICEMAIL\">Voicemail</option><option value=\"EXTEN\">Custom Extension</option></select></td></tr>\n";

		echo "<tr class='trview didExtensionFilter' style='display:none'><td align=right>Filter Extension: </td><td align=left><input type=text name=filter_extension id=efilter_extension size=40 maxlength=50></td></tr>\n";
		
		echo "<tr class='trview didExtensionFilter' style='display:none'><td align=right>Filter Extension Context: </td><td align=left><input type=text name=filter_exten_context id=efilter_exten_context size=40 maxlength=50></td></tr>\n";
		
		echo "<tr class='trview didVoicemailFilter' style='display:none'><td align=right>Filter Voicemail Box: </td><td align=left><input type=text name=filter_voicemail_ext id=efilter_voicemail_ext size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('efilter_voicemail_ext','vm',500,document.getElementById('efilter_voicemail_ext').value);\"><FONT color=\"blue\" size=\"1\">[ Voicemail Chooser ]</a><div id=\"divefilter_voicemail_ext\"></div></td></tr>\n";
		
		echo "<tr class='trview didPhoneFilter' style='display:none'><td align=right>Filter Phone Extension: </td><td align=left><input type=text name=filter_phone id=efilter_phone size=20 maxlength=100></td></tr>\n";
		
		echo "<tr class='trview didPhoneFilter' style='display:none'><td align=right>Filter Server IP: </td><td align=left><select size=1 name=filter_server_ip id=efilter_server_ip>\n";
		echo "$servers_list";
		//echo "<option SELECTED>$filter_server_ip</option>\n";
		echo "</select></td></tr>\n";

		echo "<tr class='trview didCallMenuFilter' style='display:none'><td align=right>Filter Call Menu: </td><td align=left><select size=1 name=filter_menu_id id=efilter_menu_id>";
		echo "$Xmenuslist";
		echo "</select></td></tr>\n";

		echo "<tr class='trview didAgentFilter' style='display:none'><td align=right>Filter Agent ID: </td><td align=left><input type=text name=filter_user id=efilter_user size=20 maxlength=20></td></tr>\n";
		
		echo "<tr class='trview didAgentFilter' style='display:none'><td align=right>Filter Agent Unavailable Action: </td><td align=left><select size=1 name=filter_user_unavailable_action id=efilter_user_unavailable_action><option value=\"VOICEMAIL\">Voicemail</option><option value=\"PHONE\">Phone</option><option value=\"IN_GROUP\">In-group</option><option value=\"EXTEN\">Custom Extension</option></select></td></tr>\n";
		
		echo "<tr class='trview didAgentFilter' style='display:none'><td align=right style=\"white-space:nowrap;\">Filter Agent Route Settings In-Group: </td><td align=left><select size=1 name=filter_user_route_settings_ingroup id=efilter_user_route_settings_ingroup style=\"width:350px\">";
		
		echo "$FXgroups_menu";
		echo "<option value=\"AGENTDIRECT\">AGENTDIRECT</option>";
		echo "</select></td></tr>\n";
		
		echo "<tr class='trview didInboundFilter' style='display:none'><td align=right>Filter In-Group ID: </td><td align=left><select size=1 name=filter_group_id id=efilter_group_id>";
		echo "$FDgroups_menu";
		echo "</select></td></tr>\n";
		
		echo "<tr class='trview' style='display:none'><td align=right>Filter In-Group Call Handle Method: </td><td align=left><select size=1 name=filter_call_handle_method id=efilter_call_handle_method><option>CID</option><option>CIDLOOKUP</option><option>CIDLOOKUPRL</option><option>CIDLOOKUPRC</option><option>ANI</option><option>ANILOOKUP</option><option>ANILOOKUPRL</option><option>VIDPROMPT</option><option>VIDPROMPTLOOKUP</option><option>VIDPROMPTLOOKUPRL</option><option>VIDPROMPTLOOKUPRC</option><option>CLOSER</option><option>3DIGITID</option><option>4DIGITID</option><option>5DIGITID</option><option>10DIGITID</option></select></td></tr>\n";
		
		echo "<tr class='trview' style='display:none'><td align=right>Filter In-Group Agent Search Method: </td><td align=left><select size=1 name=filter_agent_search_method id=efilter_agent_search_method><option value=\"LB\">LB - Load Balanced</option><option value=\"LO\">LO - Load Balanced Overflow</option><option value=\"SO\">SO - Server Only</option></select></td></tr>\n";
		
		echo "<tr class='trview didInboundFilter' style='display:none'><td align=right>Filter In-Group List ID: </td><td align=left><input type=text name=filter_list_id id=efilter_list_id size=14 maxlength=14></td></tr>\n";
		
		echo "<tr class='trview didInboundFilter' style='display:none'><td align=right>Filter In-Group Campaign ID: </td><td align=left><select size=1 name=filter_campaign_id id=efilter_campaign_id>\n";
		echo "$campaigns_list";
		//echo "<option SELECTED>$filter_campaign_id</option>\n";
		echo "</select></td></tr>\n";
		
		echo "<tr class='trview' style='display:none'><td align=right>Filter In-Group Phone Code: </td><td align=left><input type=text name=filter_phone_code id=efilter_phone_code size=14 maxlength=14></td></tr>\n";
		
		echo "<tr><td colspan=2></td></tr>";
		echo "<tr><td colspan=2></td></tr>";
		echo "<tr><td colspan=2></td></tr>";
		echo "<tr>";
		echo "<td align=\"right\" colspan=\"2\">";
		echo "<div style=\"text-align:left;padding: 15px 0;font-size: 10px;cursor: pointer;color: #7A9E22;\" id=\"advDIDLink\"><pre style=\"float:left;\" id=\"advDIDLinkCross\">[+]</pre> ADVANCE SETTINGS</div>";
		echo "<div style=\"border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;\" align=\"right\">";
?>
		<a id="searchcallhistory" style="cursor: pointer;" onclick="editpostdid(document.getElementById('didval').value);"><font color="#7A9E22">SAVE SETTINGS</font></a>
				
		</div>		
											
			</td>			  
		</tr>
		<?php						 
		echo "</center>";
		echo "</table>";
		
		?>
		</form>
			</div>
			<!-- END EDIT DID -->	
			
			<!-- VIEW DID -->
			<!--<div class="overlay" id="overlay" style="display:none;"></div>-->
			<div class="overlaydidview" id="overlaydidview" style="display:none;"></div>
		
				<div class="didview" id="didview">
				<center>
				
 				<a class="boxclose" id="boxclose" onclick="closemedidview();"></a>
 				
 				<table summary="" >
					<tr>
					<td>
						<b>DID I.D.: </b>					
					</td>
					<td>
						<div id="viewdid_id" align="left"> </div>
					</td>
					</tr>
					<tr>
					<td>
						<b>DID Description: </b>					
					</td>
					<td>
						<div id="viewdid_desc" align="left"> </div>
					</td>
					</tr>
					<tr>					
					<td>
						<b>Status: </b>					
					</td>
					<td>
						<div id="viewdid_status" align="left"> </div>
					</td>					
					</tr>
					<!--<tr>
					<td>
						<b>Last call date: </b>					
					</td>
					<td>
						<div id="viewlistcalldate" align="left"> </div>
					</td>
					
					</tr>-->
				</table>			
	 				
			    </center>
			    </div>

			<!-- END VIEW -->			
 				
		</div>
		<!-- end tab2 -->
	<!-- END DID FIELDS -->
	
	<!-- START CALL MENU FIELDS -->
	<div id="tabs-3">
		<div style="display: block;">
		
	<div class="overlay" id="overlay" style="display:none;"></div>
		<!--<a id="/ctivator" class="activator"  onClick="addcallmenuoverlay();" style="text-decoration: none;"><b>Add New Call Menu</b>  </a>-->
                                <form id="callmenu-form">
				<table id="callmenutable" class="tablesorter list-table" style="margin-top:4px;margin-left:auto; margin-right:auto; width: 100%; " cellspacing="0px">
					<thead>
						<tr align="left" style="font-family: Verdana,Arial,Helvetica,sans-serif; font-stretch: normal;">
						<th class="thheader">&nbsp;&nbsp;<b>MENU ID</b> </th>
						<th class="thheader"><b>DESCRIPTIONS</b></th>
						<th class="thheader"><b>PROMPT</b>&nbsp; </th>
						<th class="thheader"><b>TIMEOUT</b> </th>
						<!--<th class="thheader"><b>OPTIONS</b> </th>-->
						<th colspan="3" class="thheader" style="width:7%;" align="right">
						<span style="cursor:pointer;" id="selectAction" class="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span>
						</th>
						<th align="center" width="30px">
									<input type="checkbox" id="selectAllIVR" />
						</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$countgetallcallmenus = count($getallcallmenus);
                                                        if($permissions->inbound_read == "N"){
                                                             echo("<tr class='tr2'><td colspan='7'>You don't have permission to view this record(s)</td></tr>");
                                                             $countgetallcallmenus = 0;
                                                             $justpermission = true;
                                                        }
						if($countgetallcallmenus > 0) {
							foreach($getallcallmenus as $getallcallmenusInfo) {
								if ($getallcallmenusInfo->menu_id!='defaultlog') {
						?>
							<tr align="left" class="tr<?php echo alternator('1', '2'); ?>">
								<td>&nbsp;&nbsp;<? echo "<a class='menuIdcall' onclick=\"callmenupostval('$getallcallmenusInfo->menu_id')\">$getallcallmenusInfo->menu_id</a>"; ?></td>
								<td colspan=""><? echo $getallcallmenusInfo->menu_name; ?></td>
								<td><? echo $getallcallmenusInfo->menu_prompt; ?></td>
								<td><? echo $getallcallmenusInfo->menu_timeout; ?></td>
								<td>
  								 <img src="<?=$base?>img/edit.png" onclick="callmenupostval('<? echo $getallcallmenusInfo->menu_id; ?>');"  class="rightdiv toolTip" style="cursor:pointer;width:14px; padding: 3px;" title="MODIFY <?=$getallcallmenusInfo->menu_id;?>"  />
  								 </td>
  								  <td align="">
  								 <div class="rightdiv toolTip" title="DELETE <?=$getallcallmenusInfo->menu_id;?>" style="padding:3px;">
						 			<img src="<?=$base?>img/delete.png" onclick="deletepostcallmenu('<?=$getallcallmenusInfo->menu_id;?>')" style="cursor:pointer;width:12px;"  />
						 			</div>
								 </td>
  								 <td align="">
  									<div class="rightdiv toolTip" title="VIEW INFO FOR DID <?=$getallcallmenusInfo->menu_id;?>" style="padding: 3px;">
									<img style="cursor:pointer;width:12px;" src="<?=$base?>img/status_display_i.png" style="cursor:pointer;width:14px;">
									</div>
								 </td>
								 <td align="center" >
							<input type="checkbox" id="delIVR[]" value="<?=$getallcallmenusInfo->menu_id;?>" />						 
								 </td>
							</tr>
						<?php
								}
							}
						} else {
                                                     if(!$justpermission){
						?>
						<tr>
						<td colspan="8" align="center" style="background-color: #EFEFEF;">
							<font color="red"><b>No record(s) found!</b></font>
						</td>	
						</tr>
						<?php
                                                      }
						}
						?>
					</tbody>
				</table>			
                                </form>
		</div>
		<br><br>
		<?
		//include "/var/www/html/go_ce/application/views/go_ingroup/go_callmenu_form.php";
		?>
		
				<div class="overlaycallmenulist" id="overlaycallmenulist" style="display:none;"></div>
				<div class="calladdlist" id="calladdlist">
				<center>
 					<a class="boxclose" id="" onclick="closemeaddcallmenu();"></a>
 					<div id="small_step_number" style="float:right; margin-top: -5px;">
						<img src="<?=$base?>img/step1of2-navigation-small.png">
					</div>
					<div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
						<font color="black" style="font-size:16px;font-weight:bold;" id="wizardHeader">Call Menu Wizard » Create New Call Menu</font>
					</div>
					<br>
					<form  method="POST" id="go_callmenufrm" name="go_callmenufrm">
				   	<input type="hidden" id="selectval" name="selectval" value="">
				   	<input type="hidden" id="addCALLMENU" name="addCALLMENU" value="addCALLMENU">					
 						<table class="tableedit" width="100%" >
						  <tr>
						  		<td valign="top" style="width:20%">
						  			<div id="step_number" style="padding:0px 10px 0px 30px;">
										<img src="<?=$base?>img/step1-trans.png">
									</div>
								</td>
								<td style="padding-left:20px;" valign="top" colspan="2">
									<table width="100%" id="ivrMenuStep1">
										<tr>
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu ID: </td>
											<td><input type="text" name="menu_id" id="menu_id" size="25" maxlength="50" /> <span id="err_menu_id"></span>
											</td>
										</tr>
										<tr>
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Name: </td>
											<td>
												<input type="text" name="menu_name" id="menu_name" size="30" maxlength="100" />
											</td>
										</tr>
										<tr>
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Greeting: </td>
											<td style="white-space: nowrap;">
												<input type="text" name="menu_prompt" id="menu_prompt" size="30" maxlength="255" /> 
												 <a href="javascript:launch_chooser('menu_prompt','date',1200,document.getElementById('menu_prompt').value);"><font color="blue" size="1">[ audio chooser ]</font></a>
											</td>
										</tr>
										<tr style="display:none;" id="tblmenu_prompt">
											<td style="white-space: nowrap;">&nbsp;</td>
											<td>
												<div id="divmenu_prompt"></div>
											</td>
										</tr>
										<tr>
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Timeout: </td>
											<td>
												<input type="text" name="menu_timeout" id="menu_timeout" size="10" maxlength="5" value="10" />
											</td>
										</tr>
										<tr>
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Timeout Greeting: </td>
											<td style="white-space: nowrap;">
												<input type="text" name="menu_timeout_prompt" id="menu_timeout_prompt" size="30" maxlength="255" /> 
												 <a href="javascript:launch_chooser('menu_timeout_prompt','date',1200,document.getElementById('menu_timeout_prompt').value);"><font color="blue" size="1">[ audio chooser ]</font></a>
											</td>
										</tr>
										<tr style="display:none;" id="tblmenu_timeout_prompt">
											<td style="white-space: nowrap;">&nbsp;</td>
											<td>
												<div id="divmenu_timeout_prompt"></div>
											</td>
										</tr>
										<tr>
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Invalid Greeting: </td>
											<td style="white-space: nowrap;">
												<input type="text" name="menu_invalid_prompt" id="menu_invalid_prompt" size="30" maxlength="255" /> 
												 <a href="javascript:launch_chooser('menu_invalid_prompt','date',1200,document.getElementById('menu_invalid_prompt').value);"><font color="blue" size="1">[ audio chooser ]</font></a>
											</td>
										</tr>
										<tr style="display:none;" id="tblmenu_invalid_prompt">
											<td style="white-space: nowrap;">&nbsp;</td>
											<td>
												<div id="divmenu_invalid_prompt"></div>
											</td>
										</tr>
										<tr>
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Repeat: </td>
											<td>
												<input type="text" name="menu_repeat" id="menu_repeat" size="5" maxlength="3" value="1" />
											</td>
										</tr>
										<tr style="display:none">
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Time Check: </td>
											<td>
												<?php
												$options = array('0 - No Time Check','1 - Time Check');
												echo form_dropdown('menu_time_check',$options,'1','id="menu_time_check"');
												?>
											</td>
										</tr>
										<tr style="display:none">
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Call Time: </td>
											<td>
												<?php
												$calltimeArray = array();
												foreach ($calltimespulldown as $calltime)
												{
												   $calltimeArray[$calltime->call_time_id] = "{$calltime->call_time_id} - {$calltime->call_time_name}";
												}
												echo form_dropdown('call_time_id',$calltimeArray,'24hours','id="call_time_id"');
												?>
											</td>
										</tr>
										<tr style="display:none">
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Track Calls in<br />Real-Time Report: </td>
											<td>
												<?php
												$options = array('0 - No Realtime Tracking','1 - Realtime Tracking');
												echo form_dropdown('track_in_vdac',$options,'1','id="track_in_vdac"');
												?>
											</td>
										</tr>
										<tr>
											<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Tracking Group: </td>
											<td>
												<?php
												$ingroupArray = array();
												$ingroupArray['CALLMENU'] = "CALLMENU - Default";
												foreach ($ingrouppulldown as $ingroup)
												{
													$ingroupArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
													
													if (strlen($ingroupArray[$ingroup->group_id]) > 35)
														$trackGroupWidth = 'style="width:325px"';
												}
												echo form_dropdown('tracking_group',$ingroupArray,'CALLMENU','id="tracking_group" '.$trackGroupWidth);
												?>
											</td>
										</tr>
										<tr><td colspan="2">&nbsp;</td></tr>
									</table>
									<table id="ivrMenuStep2" style="display:none">
									<?php
									echo "<tr>";
									echo "<td colspan='6' style='font-weight:bold'><div style='border-bottom:2px solid #DFDFDF; padding-bottom: 5px;'>Default Call Menu Entry</div></td>";
									echo "</tr><tr>";
									echo "<td style='padding-left:10px'>Option:</td><td>".form_dropdown('',array('TIMEOUT'=>'TIMEOUT','TIMECHECK'=>'TIMECHECK'),'TIMEOUT','disabled')."</td>";
									echo "<td>Description:</td><td>".form_input('','Hangup','maxlength="255" size="30" disabled')."</td>";
									echo "<td>Route:</td><td>".form_dropdown('',array('HANGUP'=>'Hangup','EXTENSION'=>'Custom Extension'),'HANGUP','disabled')."</td>";
									echo "</tr>\n";
									echo "<tr>";
									echo "<td colspan=\"6\" style=\"text-align:center;\">Audio File: ".form_input('','vm-goodbye','size="30" disabled')."</td>";
									echo "</tr>";
									echo "<tr><td colspan='6' style='font-weight:bold'><div style='border-bottom:2px solid #DFDFDF;padding:10px 0 5px 0;'>Add New Call Menu Options</div></td></tr>";
									$ctr = 0;
									while ($ctr < 10)
									{
										// onChange="javascript:showoptionpostval(this.options[this.selectedIndex].value,'.$ctr.');"
										$optionDD = form_dropdown('option_value_'.$ctr,array(''=>'','0','1','2','3','4','5','6','7','8','9','#'=>'#','*'=>'*','TIMECHECK'=>'TIMECHECK','INVALID'=>'INVALID'),'','id="option_value_'.$ctr.'" onChange="javascript:checkoptionval(this.options[this.selectedIndex].value,'.$ctr.');"');
										$optionRoute = form_dropdown('option_route_'.$ctr,array(''=>'','CALLMENU'=>'Call Menu / IVR','INGROUP'=>'In-group','DID'=>'DID','HANGUP'=>'Hangup','EXTENSION'=>'Custom Extension','PHONE'=>'Phone','VOICEMAIL'=>'Voicemail','AGI'=>'AGI'),'','id="option_route_'.$ctr.'" onChange="javascript:showoptionpostval(\''.$dataval.'\',document.getElementById(\'option_value_'.$ctr.'\').options[document.getElementById(\'option_value_'.$ctr.'\').selectedIndex].value,this.options[this.selectedIndex].value,'.$ctr.');"');
										echo "<tr class=\"trview\">";
										echo "<td style='padding-left:10px'>Option:</td><td>$optionDD</td>";
										echo "<td>Description:</td><td>".form_input('option_description_'.$ctr,'','maxlength="255" size="30"')."</td>";
										echo "<td>Route:</td><td>$optionRoute</td>";
										echo "</tr>\n";
										echo "<tr class=\"trview option_hidden_$ctr\" style=\"display:none;\">";
										echo "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"></td>";
										echo "</tr>";
										$ctr++;
									}
									?>
									</table>
								</td>
						  </tr>
						  <tr>
								<td align="right" colspan="4">
									<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
									<a id="backCallMenu" style="cursor: pointer;display:none;color:#7A9E22;">Back</a><span class="divider" style="display:none"> | </span><a id="nextStepCallMenu" style="cursor: pointer;color:#7A9E22;">Next</a><a id="submitCallMenu" style="cursor: pointer;color:#7A9E22;display:none;">Finish</a>
									</div>		
											
								</td>			  
						  </tr>
						  		
						</table>
 						</form>
 				</center>
 				</div>
 				
 				<!-- EDIT CALL MENU -->
 				<div class="overlaycallmenueditlist" id="overlaycallmenueditlist" style="display:none;"></div>
				<div class="calleditlist" id="calleditlist">
				<a class="boxclose" id="" onclick="closemeeditcallmenu();"></a>
				<div align="center" class="title-header"> MODIFY CALLMENU: <label id="edit_menu_desc"></label></div>
				<br>
				<form  method="POST" id="go_editcallmenufrm" name="go_editcallmenufrm" method="POST">
					<input type="hidden" id="menuvals" name="menuvals">
					<input type="hidden" id="selectval" name="selectval" value="">
					<input type="hidden" id="editCALLMENU" name="editCALLMENU" value="editCALLMENU">					
 						<table class="tableedit" width="100%" >
						  <tr>
								<td valign="top" colspan="2" align="center">
									<table width="100%">
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Menu ID: </td>
											<td style="padding: 5px 0 5px 3px;"><span id="edit_menu_id"></span>
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Menu Name: </td>
											<td>
												<input type="text" name="menu_name" id="edit_menu_name" size="30" maxlength="100" />
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Menu Prompt: </td>
											<td>
												<input type="text" name="menu_prompt" id="edit_menu_prompt" size="30" maxlength="255" /> 
												 <a href="javascript:launch_chooser('edit_menu_prompt','date',1200,document.getElementById('edit_menu_prompt').value);"><font color="blue" size="1">[ audio chooser ]</font></a>
											</td>
										</tr>
										<tr class="trview" style="display:none;" id="tbledit_menu_prompt">
											<td style="white-space: nowrap;">&nbsp;</td>
											<td>
												<div id="divedit_menu_prompt"></div>
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Menu Timeout: </td>
											<td>
												<input type="text" name="menu_timeout" id="edit_menu_timeout" size="10" maxlength="5" value="10" />
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Menu Timeout Prompt: </td>
											<td>
												<input type="text" name="menu_timeout_prompt" id="edit_menu_timeout_prompt" size="30" maxlength="255" /> 
												 <a href="javascript:launch_chooser('edit_menu_timeout_prompt','date',1200,document.getElementById('edit_menu_timeout_prompt').value);"><font color="blue" size="1">[ audio chooser ]</font></a>
											</td>
										</tr>
										<tr class="trview" style="display:none;" id="tbledit_menu_timeout_prompt">
											<td style="white-space: nowrap;">&nbsp;</td>
											<td>
												<div id="divedit_menu_timeout_prompt"></div>
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Menu Invalid Prompt: </td>
											<td>
												<input type="text" name="menu_invalid_prompt" id="edit_menu_invalid_prompt" size="30" maxlength="255" /> 
												 <a href="javascript:launch_chooser('edit_menu_invalid_prompt','date',1200,document.getElementById('edit_menu_invalid_prompt').value);"><font color="blue" size="1">[ audio chooser ]</font></a>
											</td>
										</tr>
										<tr class="trview" style="display:none;" id="tbledit_menu_invalid_prompt">
											<td style="white-space: nowrap;">&nbsp;</td>
											<td>
												<div id="divedit_menu_invalid_prompt"></div>
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Menu Repeat: </td>
											<td>
												<input type="text" name="menu_repeat" id="edit_menu_repeat" size="5" maxlength="3" value="1" />
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Menu Time Check: </td>
											<td>
												<?php
												$options = array('0 - No Time Check','1 - Time Check');
												echo form_dropdown('menu_time_check',$options,'0','id="edit_menu_time_check"');
												?>
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Call Time: </td>
											<td>
												<?php
												$calltimeArray = array();
												foreach ($calltimespulldown as $calltime)
												{
												   $calltimeArray[$calltime->call_time_id] = "{$calltime->call_time_id} - {$calltime->call_time_name}";
												}
												echo form_dropdown('call_time_id',$calltimeArray,'24hours','id="edit_call_time_id"');
												?>
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Track Calls in<br />Real-Time Report: </td>
											<td>
												<?php
												$options = array('0 - No Realtime Tracking','1 - Realtime Tracking');
												echo form_dropdown('track_in_vdac',$options,'1','id="edit_track_in_vdac"');
												?>
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Tracking Group: </td>
											<td>
												<?php
												$ingroupArray = array();
												$ingroupArray['CALLMENU'] = "CALLMENU - Default";
												foreach ($ingrouppulldown as $ingroup)
												{
													$ingroupArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
													
													if (strlen($ingroupArray[$ingroup->group_id]) > 35)
														$trackGroupWidth = 'style="width:325px"';
												}
												echo form_dropdown('tracking_group',$ingroupArray,'CALLMENU','id="edit_tracking_group" '.$trackGroupWidth);
												?>
											</td>
										</tr>
										<tr class="trview">
											<td style="white-space: nowrap;text-align: right;">Custom Dialplan Entry: </td>
											<td>
												<textarea id="edit_custom_dialplan_entry" name="custom_dialplan_entry" cols="70" rows="5" style="resize: none"></textarea>
											</td>
										</tr>
										<tr><td colspan="2" style="font-size:6px;">&nbsp;</td></tr>
										<tr class="trview">
											<td colspan="2" style="white-space: nowrap;text-align: center;font-weight:bold;padding: 5px 0 5px 3px;">Call Menu Options</td>
										</tr>
										<tr>
											<td colspan="2">
												<div id="tblcallmenuoptions"></div>
											</td>
										</tr>
										<tr><td colspan="2">&nbsp;</td></tr>
									</table>																	
								</td>
						  </tr>
						  <tr>
								<td align="right" colspan="4">
									<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
									<a id="submitCallMenuEdit" style="cursor: pointer;"><font color="#7A9E22">Save</font></a> | <a id="finishCallMenuEdit" style="cursor: pointer;"><font color="#7A9E22">Finish</font></a>
									</div>		
											
								</td>			  
						  </tr>
						  		
						</table>
				</form>
				
				</div>
				<!-- EDIT end call menu -->

		
	</div> <!-- end tab3 -->
	<!-- END START CALL MENU FIELDS -->



	<!-- tab3 -->
	<!-- upload leads -->
<!--	<div id="tabs-3">
				<div id="uploadlist" style="display: block;" align="left">
				<form action="go_list" name="uploadform" method="post" onSubmit="ParseFileName()" enctype="multipart/form-data">
				<input type="hidden" name="leadsload" id="leadsloadok" value="ok">
				<input type="hidden" name="tabvalsel" id="tabvalsel" value="<?=$tabvalsel?>">
				<input type=hidden name='leadfile_name' id="leadfile_name" value="<?php echo $leadfile_name ?>">
				<b>Load Leads</b>
				<table class="tableedit" width="100%">
					<tr><td colspan="2">&nbsp;&nbsp;</td></tr>
				</table>
				
				<center>
				<table class="tablenodouble" width="80%">
					<tr>
						<td colspan="2">&nbsp;&nbsp;</td>
					</tr>
		  			<tr>
						<td><label class="modify-value">Load leads from this file:</label></td>
						<td><input type="file" name="leadfile" id="leadfile" value="<?php echo $leadfile ?>"></td>
		  			</tr>
					<tr>
						<td><label class="modify-value">List ID Override:</label></td>
						<td>
							<select name="list_id_override">
								<option value='in_file' selected='yes'>Load from Lead File</option>
								<?php
									foreach($lists as $listsInfo){
											$load_list_id = $listsInfo->list_id;
											$load_list_name = $listsInfo->list_name;
											echo '<option value="'.$load_list_id.'">'.$load_list_id.'---'.$load_list_name.'</option>';	 
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">Phone Code Override: </label></td>
						<td><font face="arial, helvetica" size="1">
								<select name="phone_code_override">
                        	<option value='in_file' selected>Load from Lead File</option>
                        	<?php
                        		foreach($phonedoces as $listcodes) {
                        			$country_code = $listcodes->country_code;
                        			$country = $listcodes->country;
                        			echo '<option value="'.$country_code.'">'.$country_code.'---'.$country.'</option>';
										}
                        	?>
                        </select>
                        </td>
                </tr>
                <tr>
						<td><label class="modify-value">Lead Duplicate Check: </label></td>
						<td><font face="arial, helvetica" size="1">
							<select size="1" name="dupcheck">
								<option value="NONE">NO DUPLICATE CHECK</option>
								<option value="DUPLIST">CHECK FOR DUPLICATES BY PHONE IN LIST ID*</option>
								<option value="DUPCAMP">CHECK FOR DUPLICATES BY PHONE IN ALL CAMPAIGN LISTS</option>
								<option value="DUPSYS">CHECK FOR DUPLICATES BY PHONE IN ENTIRE SYSTEM</option>
								<option value="DUPTITLEALTPHONELIST">CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN LIST ID</option>
								<option value="DUPTITLEALTPHONESYS">CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN ENTIRE SYSTEM</option>
							</select>
						</td>
		  			 </tr>
		  			 <tr>
		  			 <td><label class="modify-value">Lead Time Zone Lookup: </label></td>
						<td><font face="arial, helvetica" size="1">
							<select size="1" name="postalgmt">
								<option value="AREA" selected>COUNTRY CODE AND AREA CODE ONLY</option>
								<option value="POSTAL">POSTAL CODE FIRST</option>
								<option value="TZCODE">OWNER TIME ZONE CODE FIRST</option>
							</select>
						</td>
					 </tr>
					 <tr>
					 	<tr><td colspan="2">&nbsp;&nbsp;</td></tr>
					 	<td colspan="2">
					 		<center>
					 			<input type="submit" value="SUBMIT" name="submit_file" id="submit_file">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					 			<input type="button" onClick="javascript:document.location='go_list/'" value="START OVER" name='reload_page'>
					 		</center>
					 	</td>
					 	
					 </tr>
					 </form>
					 <?php
					 if($fields!=null) {
					 ?>
					<form action="go_list" name="uploadform" method="post" onSubmit="ParseFileName()" enctype="multipart/form-data">
					<input type="hidden" name="leadsload" value="okfinal">
					<input type="hidden" name="lead_file" id="lead_file" value="<?=$lead_file?>">
					<input type="hidden" name="leadfile" id="leadfile" value="<?=$leadfile?>">
					<input type="hidden" name="list_id_override" value="<?=$list_id_override?>">
					<input type="hidden" name="phone_code_override" value="<?=$phone_code_override?>">
					<input type="hidden" name="dupcheck" value="<?=$dupcheck?>">
					<input type="hidden" name="leadfile_name" id="leadfile_name" value="<?=$leadfile_name?>">
					

					 <tr>
					 	<td colspan="2">
					 			<tr bgcolor="#efefef">
					 			<td align="center">GoAutoDial Fields</td>
					 			<td align="center">CSV Fields</td>
					 			</tr>
					 			<?php	
					 			
									$noview = array("lead_id","entry_date","modify_date","status","user","list_id","gmt_offset_now","called_since_last_reset","called_count","last_local_call_time","entry_list_id");					 				
					 				
					 				foreach ($fields as $field) {
					 					
					 					if(in_array("$field", $noview)) {
											echo "";					 						
					 					} else {
								
											echo "  <tr bgcolor=#efefef>\r\n";
											echo "    <td align=right><font class=standard>".strtoupper(eregi_replace("_", " ", $field)).": </font></td>\r\n";
											echo "    <td align=center><select name='".$field."_field'>\r\n";
											echo "     <option value='-1'>(none)</option>\r\n";

											for ($j=0; $j<count($fieldrow); $j++) {
												eregi_replace("\"", "", $fieldrow[$j]);
												echo "     <option value='$j'>\"$fieldrow[$j]\"</option>\r\n";
											}
									
											echo "    </select></td>\r\n";
											echo "  </tr>\r\n";
					
										}
									} // end for
								
					 			?>		
					 	</td>
					 </tr>
					 
					 <tr>
				    	<td colspan="2">
				    		<input type="submit" name="OK_to_process" value="SUBMIT">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				    		<input type="button" onClick="javascript:document.location='go_list/'" value="BACK" name="reload_page">
						</td>
					 <?php
					 	}
					 ?>
					 </tr>
					 </form>
		  		</table>
		  		</center>
				</div>
	</div>
-->	
	<!-- end tab3 -->
				

									<div style="display: none;" class="demo-description">
										<p>Click tabs to swap between content that is broken into logical sections.</p>
									</div><!-- End demo-description -->							
				
                            <div class="container">
                               <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    <div id='go_action_menu' class='go_action_menu'>
                        <ul>
                          <li class="go_action_submenu" title="Activate Selected" id="activate">Activate Selected</li>
                          <li class="go_action_submenu" title="Deactivate Selected" id="deactivate">Deactivate Selected</li>
                          <li class="go_action_submenu" title="Delete Selected" id="delete">Delete Selected</li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</div> <!-- wpwrap -->
</div>
<!-- end body -->
