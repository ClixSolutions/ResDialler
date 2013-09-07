<?php
############################################################################################
####  Name:             go_campaign_list.php                                            ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

if($permissions->campaign_read == "N"){
	die("<br />Error: You do not have permission to view the list of campaigns.");
}
?>
<style type="text/css">
#selectAction, #selectStatusAction, #selectCampStatusAction,
#selectLeadRecycleAction, #selectCampLeadRecycleAction,
#selectPhoneCodeAction, #selectCampPhoneCodeAction,
#selectHotKeysAction, #selectCampHotKeysAction {
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

#mainTable th,
#statusesTable th,
#leadRecyclingTable th,
#pauseCodeTable th,
#hotKeysTable th {
	text-align:left;
}

.buttones,.buttons {
	color:#7A9E22;
	cursor:pointer;
}

.buttones:hover,.buttons:hover{
	font-weight:bold;
}

.buttons {
	font-size: 12px;
}

/* Table Sorter */
table.tablesorter thead tr .header {
/*	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;*/
	cursor: pointer;
}
/*table.tablesorter thead tr .headerSortUp {
	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/asc.gif);
}
table.tablesorter thead tr .headerSortDown {
	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/desc.gif);
}*/
/* Table Sorter */

.modify-value {
  font-weight: bold;
  color: #7f7f7f;
}
</style>
<script>
function modify(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '860px', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#box').animate({
			top: "70px"
		}, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_settings/'+camp).fadeIn("slow");
	} else {
			alert("Error: You do not have permission to modify this campaign.");
	}
}

function delCamp(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete this Campaign?\n\n'+camp+'\n\nPlease make sure to transfer any existing list ids\nthat have leads uploaded to it to any available campaign.');
		if (what)
		{
			$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_update_campaign_list/delete/'+camp+'/');
		}
	} else {
			alert("Error: You do not have permission to delete campaigns.");
	}
}

function modifyStatus(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'fixed'});
		$('#box').animate({
			top: "70px"
		}, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_campaign_statuses/'+camp).fadeIn("slow");
	} else {
			alert("Error: You do not have permission to modify campaign statuses.");
	}
}

function delStatus(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete the selected campaign\'s statuses?');
		if (what)
		{
			$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_delete_campaign_statuses_list/'+camp);
		}
	} else {
			alert("Error: You do not have permission to delete campaign statuses.");
	}
}

function delLeadRecycle(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete the selected campaign\'s statuses?');
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/delete/"+camp, function()
			{
				if ($('#request').text() == 'showLeadRecycling')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete lead recycling statuses.");
	}
}

function delPauseCode(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete the selected campaign\'s pause codes?');
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/delete/"+camp, function()
			{
				if ($('#request').text() == 'showPauseCodes')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete campaign pause codes.");
	}
}

function delHotKeys(camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete the selected campaign\'s hotkeys?');
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/delete/"+camp, function()
			{
				if ($('#request').text() == 'showHotKeys')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete campaign hotkeys.");
	}
}

function viewInfo(camp, type, h)
{
	$('#statusOverlay').fadeIn('fast');
	$('#statusBox').css({'width': '400px','height': h,'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '20px', 'position': 'fixed'});
	$('#statusBox').animate({
// 		top: Math.max(0, (($(window).height() - $('#statusBox').outerHeight()) / 2) + $(window).scrollTop()) + "px"
		top: "70px"
	}, 500);
	
	$("#statusOverlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#statusOverlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_view_info/'+camp+'/'+type).fadeIn("slow");		
}

$(function ()
{
	var request = $('#request').html();
	var tabName = '';
	var cntCamps = <?php echo count($campaign['list']); ?>;
	$('.tabtoggle').each(function()
	{
		tabName = $(this).attr('id');
		if (tabName == request)
		{
			$('#' + tabName + '_div').show();
		}
		else
		{
			$('#' + tabName + '_div').hide();
		}
	});
	
	$('#selectAll').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delCampaign[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delCampaign[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllStatus').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delStatus[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delStatus[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllRecycle').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delLeadRecycling[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delLeadRecycling[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllPauseCodes').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delPauseCodes[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delPauseCodes[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllHotKeys').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delHotKeys[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delHotKeys[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	var toggleAction = $('#go_action_menu').css('display');
	$('#selectAction').click(function()
	{
		if (toggleAction == 'none')
		{
			var position = $(this).offset();
			$('#go_action_menu').css('left',position.left-68);
			$('#go_action_menu').css('top',position.top+16);
			$('#go_action_menu').slideDown('fast');
			toggleAction = $('#go_action_menu').css('display');
		}
		else
		{
			$('#go_action_menu').slideUp('fast');
			$('#go_action_menu').hide();
			toggleAction = $('#go_action_menu').css('display');
		}
	});
	
	var toggleStatus = $('#go_status_menu').css('display');
	$('#selectStatusAction').click(function()
	{
		if (toggleStatus == 'none')
		{
			var position = $(this).offset();
			$('#go_status_menu').css('left',position.left-42);
			$('#go_status_menu').css('top',position.top+16);
			$('#go_status_menu').slideDown('fast');
			toggleStatus = $('#go_status_menu').css('display');
		}
		else
		{
			$('#go_status_menu').slideUp('fast');
			$('#go_status_menu').hide();
			toggleStatus = $('#go_status_menu').css('display');
		}
	});
	
	var toggleLeadRecycling = $('#go_lead_recycle_menu').css('display');
	$('#selectRecycleAction').click(function()
	{
		if (toggleLeadRecycling == 'none')
		{
			var position = $(this).offset();
			$('#go_lead_recycle_menu').css('left',position.left-42);
			$('#go_lead_recycle_menu').css('top',position.top+16);
			$('#go_lead_recycle_menu').slideDown('fast');
			toggleLeadRecycling = $('#go_lead_recycle_menu').css('display');
		}
		else
		{
			$('#go_lead_recycle_menu').slideUp('fast');
			$('#go_lead_recycle_menu').hide();
			toggleLeadRecycling = $('#go_lead_recycle_menu').css('display');
		}
	});
	
	var togglePauseCodes = $('#go_pausecodes_menu').css('display');
	$('#selectPauseCodeAction').click(function()
	{
		if (togglePauseCodes == 'none')
		{
			var position = $(this).offset();
			$('#go_pausecodes_menu').css('left',position.left-42);
			$('#go_pausecodes_menu').css('top',position.top+16);
			$('#go_pausecodes_menu').slideDown('fast');
			togglePauseCodes = $('#go_pausecodes_menu').css('display');
		}
		else
		{
			$('#go_pausecodes_menu').slideUp('fast');
			$('#go_pausecodes_menu').hide();
			togglePauseCodes = $('#go_pausecodes_menu').css('display');
		}
	});
	
	var toggleHotKeys = $('#go_hotkeys_menu').css('display');
	$('#selectHotKeysAction').click(function()
	{
		if (toggleHotKeys == 'none')
		{
			var position = $(this).offset();
			$('#go_hotkeys_menu').css('left',position.left-42);
			$('#go_hotkeys_menu').css('top',position.top+16);
			$('#go_hotkeys_menu').slideDown('fast');
			toggleHotKeys = $('#go_hotkeys_menu').css('display');
		}
		else
		{
			$('#go_hotkeys_menu').slideUp('fast');
			$('#go_hotkeys_menu').hide();
			toggleHotKeys = $('#go_hotkeys_menu').css('display');
		}
	});
	
	$(document).mouseup(function (e)
	{
		var content = $('#go_action_menu, #go_status_menu, #go_camp_status_menu, #go_lead_recycle_menu, #go_camp_lead_recycle_menu, #go_pausecodes_menu, #go_camp_pausecodes_menu, #go_hotkeys_menu, #go_camp_hotkeys_menu');
		if (content.has(e.target).length === 0 && (e.target.id != 'selectAction' && e.target.id != 'selectStatusAction'
				&& e.target.id != 'selectRecycleAction' && e.target.id != 'selectCampRecycleAction'
				&& e.target.id != 'selectPauseCodeAction' && e.target.id != 'selectCampPauseCodeAction'
				&& e.target.id != 'selectHotKeysAction' && e.target.id != 'selectCampHotKeysAction'))
		{
			content.slideUp('fast');
			content.hide();
			toggleAction = $('#go_action_menu').css('display');
			toggleStatus = $('#go_status_menu').css('display');
			toggleCampStatus = $('#go_camp_status_menu').css('display');
			toggleLeadRecycling = $('#go_lead_recycle_menu').css('display');
			toggleCampLeadRecycling = $('#go_camp_lead_recycle_menu').css('display');
			togglePauseCodes = $('#go_pausecodes_menu').css('display');
			toggleCampPauseCodes = $('#go_camp_pausecodes_menu').css('display');
			toggleHotKeys = $('#go_hotkeys_menu').css('display');
			toggleCampHotKeys = $('#go_camp_hotkeys_menu').css('display');
		}
	});
	
	$('.hoverCampID').each(function()
	{
		$(this).hover(function()
		{
			$(this).css('color','red');
		},
		function()
		{
			$(this).css('color','black');
		});
	});
	
	// Tool Tip
	$(".toolTip").tipTip();

	if (cntCamps > 0)
	{
		// Pagination
		$('#mainTable').tablePagination();
		$('#statusesTable').tablePagination();
		$('#leadRecyclingTable').tablePagination();
		$('#pauseCodeTable').tablePagination();
		$('#hotKeysTable').tablePagination();

		// Table Sorter 
		$("#mainTable").tablesorter({headers: { 6: { sorter: false}, 7: {sorter: false} }});
		$("#statusesTable").tablesorter({headers: { 3: { sorter: false}, 4: {sorter: false} }});
		$("#leadRecyclingTable").tablesorter({headers: { 3: { sorter: false}, 4: {sorter: false} }});
		$("#pauseCodeTable").tablesorter({headers: { 3: { sorter: false}, 4: {sorter: false} }});
		$("#hotKeysTable").tablesorter({headers: { 3: { sorter: false}, 4: {sorter: false} }});
	}
	else
	{
		addNewCampaign();
	}

	$('#closeboxStatus').click(function()
	{
		var camp = $(this).attr('rel');
		$('#boxStatus').animate({'top':'-2550px'},500);
		$('#overlayStatus').fadeOut('slow');

		if ($('#request').text() == 'showStatuses')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
		}
	});

	$('#closeboxLeadRecycle').click(function()
	{
		var camp = $(this).attr('rel');
		$('#boxLeadRecycle').animate({'top':'-2550px'},500);
		$('#overlayLeadRecycle').fadeOut('slow');

		if ($('#request').text() == 'showLeadRecycling')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
		}
	});

	$('#closeboxPauseCodes').click(function()
	{
		var camp = $(this).attr('rel');
		$('#boxPauseCodes').animate({'top':'-2550px'},500);
		$('#overlayPauseCodes').fadeOut('slow');

		if ($('#request').text() == 'showPauseCodes')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
		}
	});

	$('#closeboxHotKeys').click(function()
	{
		var camp = $(this).attr('rel');
		$('#boxHotKeys').animate({'top':'-2550px'},500);
		$('#overlayHotKeys').fadeOut('slow');

		if ($('#request').text() == 'showHotKeys')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
		}
	});

	$('#statusSubmit').click(function()
	{
		var campID = $('#statusCampID').val();
		var string = $('.statusResult').serialize();
		var err_msg = 'Please fill-in the following:<br /><br />';
		var err = 0;

		if ($('#statusID').val() == '')
		{
			err_msg += 'STATUS<br />';
			err++;
		}

		if ($('#statusName').val() == '')
		{
			err_msg += 'STATUS NAME<br />';
			err++;
		}

		if (err > 0)
		{
			alert(err_msg);
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_add_new_statuses/"+campID+"/add_status/"+string, function()
			{
				$('#boxStatus').animate({'top':'-2550px'},500);
				$('#overlayStatus').fadeOut('slow');

				if ($('#request').text() == 'showStatuses')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	});

	$('#leadRecycleSubmit').click(function()
	{
		var campID = $('#leadCampID').val();
		var string = $('.leadRecycleResult').serialize();
		var err = 0;

		if (campID.length < 1)
		{
			$('#leadCampID').css('border','solid 1px red');
			err++;
		}

		if ($('#attempt_delay').val().length < 1)
		{
			$('#attempt_delay').css('border','solid 1px red');
			err++;
		}

		if (err > 0)
		{
			alert('Please select or fill-in all the fields.');
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/add/"+string, function()
			{
				$('#boxStatus').animate({'top':'-2550px'},500);
				$('#overlayStatus').fadeOut('slow');
			
				if ($('#request').text() == 'showLeadRecycling')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	});

	$('#pauseCodeSubmit').click(function()
	{
		var campID = $('#pauseCampID').val();
		var string = $('.pauseCodeResult').serialize();
		var err = 0;

		if (campID.length < 1)
		{
			$('#pauseCampID').css('border','solid 1px red');
			err++;
		}

		if ($('#pause_code').val().length < 1)
		{
			$('#pause_code').css('border','solid 1px red');
			err++;
		}

		if ($('#pause_code_name').val().length < 1)
		{
			$('#pause_code_name').css('border','solid 1px red');
			err++;
		}

		if (err > 0)
		{
			alert('Please select or fill-in all the fields.');
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/add/"+string, function()
			{
				$('#boxPauseCodes').animate({'top':'-2550px'},500);
				$('#overlayPauseCodes').fadeOut('slow');
			
				if ($('#request').text() == 'showPauseCodes')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	});

	$('#hotKeysSubmit').click(function()
	{
		var campID = $('#hotKeysCampID').val();
		var string = $('.hotKeysResult').serialize();
		var err = 0;
		var notAvail = 0;

		if (campID.length < 1)
		{
			$('#hotKeysCampID').css('border','solid 1px red');
			err++;
		}

		if ($('#hotKeys').val().length < 1)
		{
			$('#hotKeys').css('border','solid 1px red');
			err++;
		}

		if ($('#statusHotKeys').val().length < 1)
		{
			$('#statusHotKeys').css('border','solid 1px red');
			err++;
		}
		
		if ($('#kloading').html().match(/Not Available/))
		{
			alert("HotKey "+$('#hotKeys').val()+" Not Available.");
			notAvail = 1;
			err++;
		}

		if (err > 0)
		{
			if (!notAvail)
				alert('Please select or fill-in all the fields.');
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/add/"+string, function()
			{
				$('#boxHotKeys').animate({'top':'-2550px'},500);
				$('#overlayHotKeys').fadeOut('slow');
			
				if ($('#request').text() == 'showHotKeys')
				{
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
				}
			});
		}
	});
	
	$('#leadCampID').change(function(e)
	{
		$(this).css('border','solid 1px #dfdfdf');
	});

	$('#statusID').keypress(function(e)
	{
		if(e.which === 32)
			return false;
	});
	
	$('#attempt_delay').keyup(function(e)
	{
		if (parseInt($(this).val()) < 2)
		{
			$(this).css('border','solid 1px red');
		} else {
			$(this).css('border','solid 1px #dfdfdf');
		}
		
		if (parseInt($(this).val()) > 720)
			$(this).val('720');
	});
	
	$('#pause_code').keyup(function(e)
	{
		if(e.which === 32)
			return false;
		checkPauseCodes();
	});
	
	$('#pause_code').keydown(function(e)
	{
		if(e.which === 32)
			return false;
	});
	
	$('#attempt_delay').keydown(function(event)
	{
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
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
	});

});

function checkLeadRecycle()
{
	var campid = $('#leadCampID').val();
	var statusid = $('#leadStatusID').val();
	
	if (campid.length > 0 && statusid.length > 0)
		$('#lloading').load('<? echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/check/'+campid+'/'+statusid);
}

function checkPauseCodes()
{
	var campid = $('#pauseCampID').val();
	var pauseCode = $('#pause_code').val();
	
	if (campid.length > 0 && pauseCode.length > 0)
		$('#ploading').load('<? echo $base; ?>index.php/go_campaign_ce/go_pause_codes/check/'+campid+'/'+pauseCode);
}

function checkHotKeys()
{
	var campid = $('#hotKeysCampID').val();
	var hotKeys = $('#hotKeys').val();
	var statusHotKeys = $('#statusHotKeys').val();
	
	if (campid.length > 0 && hotKeys.length > 0 && statusHotKeys.length > 0)
		$('#kloading').load('<? echo $base; ?>index.php/go_campaign_ce/go_hot_keys/check/'+campid+'/'+hotKeys);
}

function modifyRecycle(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'fixed'});
		$('#box').animate({
			top: "70px"
		}, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/modify/'+camp).fadeIn("slow");
	} else {
		alert("Error: You do not have permission to modify lead recycling statuses.");
	}
}

function delLeadRecycling(status,camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var answer = confirm("Are you sure you want to delete this Status?\n\n"+status);
	
		if (answer)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/delete/"+camp+"/"+status, function()
			{
				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/modify/'+camp).fadeIn("slow");
			});
		}
	} else {
		alert("Error: You do not have permission to delete lead recycling statuses.");
	}
}

function modifyLeadRecycling(status,camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/view/"+camp+"/"+status, function(data)
		{
			var items = jQuery.parseJSON(data);
			
			$('#spanLeadStatus').html(status);
			$('#leadStatus').val(status);
			$('#attemptDelay').val(items.attempt_delay);
			$('#attemptMaximum').val(items.attempt_maximum);
			$('#isActive').val(items.active);
			$('.hiddenRecyclingTable').slideDown(500);
		});
	} else {
		alert("Error: You do not have permission to modify lead recycling statuses.");
	}
}

function modifyPauseCode(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'fixed'});
		$('#box').animate({
			top: "70px"
		}, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_pause_codes/modify/'+camp).fadeIn("slow");
	} else {
		alert("Error: You do not have permission to modify campaign pause codes.");
	}
}

function modifyCampPauseCodes(code,camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/view/"+camp+"/"+code, function(data)
		{
			var items = jQuery.parseJSON(data);
			
			$('#spanPauseCode').html(code);
			$('#pauseCodeID').val(code);
			$('#pauseCodeName').val(items.pause_code_name.replace("+"," "));
			$('#isBillable').val(items.billable);
			$('.hiddenPauseCodesTable').slideDown(500);
		});
	} else {
		alert("Error: You do not have permission to modify campaign pause codes.");
	}
}

function delCampPauseCodes(code,camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var answer = confirm("Are you sure you want to delete this Pause Code?\n\n"+code);
	
		if (answer)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/delete/"+camp+"/"+code, function()
			{
				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/modify/'+camp).fadeIn("slow");
			});
		}
	} else {
		alert("Error: You do not have permission to delete campaign pause codes.");
	}
}

function modifyHotKeys(camp)
{
	if ('<?php echo $permissions->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'fixed'});
		$('#box').animate({
			top: "70px"
		}, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_hot_keys/modify/'+camp).fadeIn("slow");
	} else {
		alert("Error: You do not have permission to modify campaign hotkeys.");
	}
}

function delCampHotKeys(hotkey,camp)
{
	if ('<?php echo $permissions->campaign_delete; ?>'!='N')
	{
		var answer = confirm("Are you sure you want to delete this HotKey?\n\n"+hotkey);
	
		if (answer)
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/delete/"+camp+"/"+hotkey, function()
			{
				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/modify/'+camp).fadeIn("slow");
			});
		}
	} else {
		alert("Error: You do not have permission to delete campaign hotkeys.");
	}
}
</script>
<div id="showList_div">
<table id="mainTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th>&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;DIAL METHOD</th>
            <th>&nbsp;&nbsp;STATUS</th>
            <th style="display:none;">&nbsp;&nbsp;LEVEL</th>
            <th style="display:none;">&nbsp;&nbsp;REMOTE AGENT STATUS</th>
            <th colspan="3" style="width:6%;text-align:center;" nowrap>
		<span style="cursor:pointer;" id="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
        </tr>
    </thead>
    <tbody>
<?php
	$x=0;
	foreach ($campaign['list'] as $row)
	{
		$remote = $campaign['remote'];
		$remote_status = ($remote[$row->campaign_id] != '') ? $remote[$row->campaign_id] : '<em>NON SURVEY CAMPAIGN</em>';
		
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}
		
		switch ($row->dial_method)
		{
			case "RATIO":
				$dial_method = "Auto-Dial";
				break;
			case "ADAPT_AVERAGE":
				$dial_method = "Predictive";
				break;
			case "MANUAL":
				$dial_method = "Manual";
				break;
			case "INBOUND_MAN":
				$dial_method = "Inbound-Man";
				break;
			default:
				$dial_method = $row->dial_method;
		}
		
		switch ($row->auto_dial_level)
		{
			case "0":
				$auto_dial_level = "OFF";
				break;
			case "1.0":
				$auto_dial_level = "SLOW";
				break;
			case "2.0":
				$auto_dial_level = "NORMAL";
				break;
			case "4.0":
				$auto_dial_level = "HIGH";
				break;
			case "6.0":
				$auto_dial_level = "MAX";
				break;
			default:
				$auto_dial_level = "ADVANCE";
				$auto_dial_num = $row->auto_dial_level;
		}

		if ($row->active == 'Y')
		{
			$active = '<span style="color:green;font-weight:bold;">ACTIVE</span>';
		} else {
			$active = '<span style="color:#F00;font-weight:bold;">INACTIVE</span>';
		}
		
		echo "<tr style=\"background-color:$bgcolor;\">\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modify('".$row->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN<br />".$row->campaign_id."\">".$row->campaign_id."</span></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modify('".$row->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN<br />".$row->campaign_id."\">".str_replace("-","&#150;",$row->campaign_name)."</span></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$dial_method</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$active</td>\n";
//		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$auto_dial_level</td>\n";
//		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$remote_status</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modify('".$row->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN<br />".$row->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delCamp('".$row->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN<br />".$row->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"viewInfo('".$row->campaign_id."','info','135px')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"VIEW INFO FOR CAMPAIGN<br />".$row->campaign_id."\"><img src=\"{$base}img/status_display_i.png\" style=\"cursor:pointer;width:12px;\" /></span></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delCampaign[]\" value=\"".$row->campaign_id."\" /></td>\n";
		echo "</tr>\n";
	}
?>
	</tbody>
</table>
</div>

<div id="showStatuses_div" class="hideSpan" align="center">
<table id="statusesTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th style="width:20%">&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;CUSTOM DISPOSITIONS</th>
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectStatusAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllStatus" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	$x=0;
        foreach ($campaign['list'] as $status)
        {
            if ($x==0) {
                $bgcolor = "#E0F8E0";
                $x=1;
            } else {
                $bgcolor = "#EFFBEF";
                $x=0;
            }
            
            $statuses = ($camp_status[$status->campaign_id] != '') ? str_replace(' ',', ',trim($camp_status[$status->campaign_id])) : '<span style="text-decoration:line-through;font-style:italic;color:#777;">NONE</span>';
            
            echo "<tr style=\"background-color:$bgcolor;\">\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modifyStatus('".$status->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN STATUSES<br />".$status->campaign_id."\">".$status->campaign_id."</span>&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modifyStatus('".$status->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN STATUSES<br />".$status->campaign_id."\">".str_replace("-","&#150;",$status->campaign_name)."</span>&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$statuses&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyStatus('".$status->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN STATUSES<br />".$status->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delStatus('".$status->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN STATUSES<br />".$status->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"viewInfo('".$status->campaign_id."','dispo','auto')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"VIEW DISPOSITIONS FOR CAMPAIGN ".$status->campaign_id."\"><img src=\"{$base}img/status_display_i.png\" style=\"cursor:pointer;width:12px;\" /></span></td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delStatus[]\" value=\"".$status->campaign_id."\" /></td>\n";
            echo "</tr>\n";
        }
        ?>
    </tbody>
</table>
</div>

<div id="showLeadRecycling_div" class="hideSpan" align="center">
<table id="leadRecyclingTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th style="width:20%">&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;LEAD RECYCLES</th>
            <!--<th>&nbsp;&nbsp;ATTEMPT DELAY</th>-->
            <!--<th>&nbsp;&nbsp;MAXIMUM ATTEMPTS</th>-->
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectRecycleAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllRecycle" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	$x=0;
        foreach ($campaign['list'] as $leadrec)
        {
            if ($x==0) {
                $bgcolor = "#E0F8E0";
                $x=1;
            } else {
                $bgcolor = "#EFFBEF";
                $x=0;
            }
            
            $statuses = ($lead_status[$leadrec->campaign_id] != '') ? str_replace(' ',', ',trim($lead_status[$leadrec->campaign_id])) : '<span style="text-decoration:line-through;font-style:italic;color:#777;">NONE</span>';
            
            echo "<tr style=\"background-color:$bgcolor;\">\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modifyRecycle('".$leadrec->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN LEAD RECYCLING<br />".$leadrec->campaign_id."\">".$leadrec->campaign_id."</span>&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modifyRecycle('".$leadrec->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN LEAD RECYCLING<br />".$leadrec->campaign_id."\">".str_replace("-","&#150;",$leadrec->campaign_name)."</span>&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$statuses&nbsp;&nbsp;</td>\n";
            //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_delay."&nbsp;&nbsp;</td>\n";
            //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_maximum."&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyRecycle('".$leadrec->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN LEAD RECYCLING<br />".$leadrec->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delLeadRecycle('".$leadrec->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN LEAD RECYCLING<br />".$leadrec->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delLeadRecycling[]\" value=\"".$leadrec->campaign_id."\" /></td>\n";
            echo "</tr>\n";
        }
        ?>
    </tbody>
</table>
</div>

<div id="showPauseCodes_div" class="hideSpan" align="center">
<table id="pauseCodeTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th style="width:20%">&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;PAUSE CODES</th>
            <!--<th>&nbsp;&nbsp;ATTEMPT DELAY</th>-->
            <!--<th>&nbsp;&nbsp;MAXIMUM ATTEMPTS</th>-->
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectPauseCodeAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllPauseCodes" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	$x=0;
        foreach ($campaign['list'] as $pausecode)
        {
            if ($x==0) {
                $bgcolor = "#E0F8E0";
                $x=1;
            } else {
                $bgcolor = "#EFFBEF";
                $x=0;
            }
            //var_dump($pause_status);
            $pausecodes = ($pause_status[$pausecode->campaign_id] != '') ? str_replace(' ',', ',trim($pause_status[$pausecode->campaign_id])) : '<span style="text-decoration:line-through;font-style:italic;color:#777;">NONE</span>';
            
            echo "<tr style=\"background-color:$bgcolor;\">\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modifyPauseCode('".$pausecode->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN PAUSE CODES<br />".$pausecode->campaign_id."\">".$pausecode->campaign_id."</span>&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modifyPauseCode('".$pausecode->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN PAUSE CODES<br />".$pausecode->campaign_id."\">".str_replace("-","&#150;",$pausecode->campaign_name)."</span>&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$pausecodes&nbsp;&nbsp;</td>\n";
            //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_delay."&nbsp;&nbsp;</td>\n";
            //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_maximum."&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyPauseCode('".$pausecode->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN PAUSE CODES<br />".$pausecode->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delPauseCode('".$pausecode->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN PAUSE CODES<br />".$pausecode->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delPauseCodes[]\" value=\"".$pausecode->campaign_id."\" /></td>\n";
            echo "</tr>\n";
        }
        ?>
    </tbody>
</table>
</div>

<div id="showHotKeys_div" class="hideSpan" align="center">
<table id="hotKeysTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;CAMPAIGN ID</th>
            <th style="width:20%">&nbsp;&nbsp;CAMPAIGN NAME</th>
            <th>&nbsp;&nbsp;HOTKEYS</th>
            <!--<th>&nbsp;&nbsp;ATTEMPT DELAY</th>-->
            <!--<th>&nbsp;&nbsp;MAXIMUM ATTEMPTS</th>-->
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectHotKeysAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllHotKeys" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	$x=0;
        foreach ($campaign['list'] as $hotkey)
        {
            if ($x==0) {
                $bgcolor = "#E0F8E0";
                $x=1;
            } else {
                $bgcolor = "#EFFBEF";
                $x=0;
            }
            //var_dump($pause_status);
            $hotkeys = ($hotkey_status[$hotkey->campaign_id] != '') ? str_replace(' ',', ',trim($hotkey_status[$hotkey->campaign_id])) : '<span style="text-decoration:line-through;font-style:italic;color:#777;">NONE</span>';
            
            echo "<tr style=\"background-color:$bgcolor;\">\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modifyHotKeys('".$hotkey->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN HOTKEYS<br />".$hotkey->campaign_id."\">".$hotkey->campaign_id."</span>&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modifyHotKeys('".$hotkey->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"MODIFY CAMPAIGN HOTKEYS<br />".$hotkey->campaign_id."\">".str_replace("-","&#150;",$hotkey->campaign_name)."</span>&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$hotkeys&nbsp;&nbsp;</td>\n";
            //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_delay."&nbsp;&nbsp;</td>\n";
            //echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$leadrec->attempt_maximum."&nbsp;&nbsp;</td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyHotKeys('".$hotkey->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"MODIFY CAMPAIGN HOTKEYS<br />".$hotkey->campaign_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delHotKeys('".$hotkey->campaign_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"DELETE CAMPAIGN HOTKEYS<br />".$hotkey->campaign_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></td>\n";
            echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delHotKeys[]\" value=\"".$hotkey->campaign_id."\" /></td>\n";
            echo "</tr>\n";
        }
        ?>
    </tbody>
</table>
</div>

<!-- Status Overlay -->
<div id="overlayStatus" style="display:none;"></div>
<div id="boxStatus">
<a id="closeboxStatus" class="toolTip" title="CLOSE"></a>
<div id="overlayContentStatus">
	<!-- <span style="font-weight:bold;font-size:16px;">ADD NEW STATUS</span> -->
        <div id="small_step_number" style="float:right; margin-top: -5px;">
          <img src="<?=$base?>img/step1-nav-small.png">
        </div>
        <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
          <font color="#333" style="font-size:16px;"><b>Status Wizard » Create New Status</b></font>
        </div>

        <br>
         <table class="tableedit" width="100%">
           <tr>
              <td valign="top" style="width:20%">
                <div id="step_number" style="padding:0px 10px 0px 30px;">
                   <img src="<?=$base?>img/step1-trans.png">
                </div>
              </td>
        <td style="padding-left:50px;" valign="top" colspan="2">

	<table style="width:90%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Campaign:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="statusCampID" name="statusCampID">
					<option value="ALLCAMP">--- ALL CAMPAIGN ---</option>
					<?php
					foreach ($campaign['list'] as $camp)
					{
						echo "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Status:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><input type="text" maxlength="6" size="8" style="font-size:12px;" id="statusID" name="status" class="statusResult" /><font size="1" color="red">&nbsp;eg. NEW</font></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Status Name:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><input type="text" maxlength="25" size="25" style="font-size:12px;" id="statusName" name="status_name" class="statusResult" /><font size="1" color="red">&nbsp;eg. New Campaign Status</font></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Selectable:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="selectable" class="statusResult"><option value="Y">YES</option><option value="N">NO</option></select></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Human Answered:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="human_answered" class="statusResult"><option value="N">NO</option><option value="Y">YES</option></select></td>
		</tr>
	 </table>
	 </tr>
         <tr>
		<td align="right" colspan="9">	 
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<input type="hidden" name="category" value="UNDEFINED" class="statusResult" />
				<input type="button" value="Submit" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;" class="buttones" id="statusSubmit" />
			</div>
                </td>
         </tr>
         </table>

</div>
</div>

<!-- Lead Recycle Overlay -->
<div id="overlayLeadRecycle" style="display:none;"></div>
<div id="boxLeadRecycle">
<a id="closeboxLeadRecycle" class="toolTip" title="CLOSE"></a>
<div id="overlayContentLeadRecycle">
	<!-- <span style="font-weight:bold;font-size:16px;">ADD NEW STATUS</span> -->
        <div id="small_step_number" style="float:right; margin-top: -5px;">
          <img src="<?=$base?>img/step1-nav-small.png">
        </div>
        <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
          <font color="#333" style="font-size:16px;"><b>Lead Recycling Wizard » Create New Lead Recycling</b></font>
        </div>

        <br>
         <table class="tableedit" width="100%">
           <tr>
              <td valign="top" style="width:20%">
                <div id="step_number" style="padding:0px 10px 0px 30px;">
                   <img src="<?=$base?>img/step1-trans.png">
                </div>
              </td>
        <td style="padding-left:50px;" valign="top" colspan="2">

	<table style="width:90%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Campaign:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="leadCampID" name="leadCampID" class="leadRecycleResult" onchange="javascript:checkLeadRecycle();">
					<option value="">--- SELECT A CAMPAIGN ---</option>
					<?php
					foreach ($campaign['list'] as $camp)
					{
						echo "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>";
					}
					?>
				</select> <span id="lloading"></span>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Status:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="leadStatusID" name="leadStatusID" class="leadRecycleResult" onchange="javascript:checkLeadRecycle();">
					<?php
					ksort($all_statuses['list']);
					foreach ($all_statuses['list'] as $key => $value)
					{
						ksort($value);
						echo "<optgroup label=\"".str_replace("_"," ",$key)."\">";
						foreach ($value as $xstatus => $istatus)
						{
							echo "<option value=\"{$xstatus}\">{$xstatus} - {$istatus}</option>";
						}
						echo "</optgroup>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Attempt Delay:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><input type="text" maxlength="3" size="7" style="font-size:12px;" id="attempt_delay" name="attempt_delay" class="leadRecycleResult" /><font size="1" color="red">&nbsp;Should be from 2 to 720 mins (12 hrs).</font></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Attempt Maximum:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><select style="font-size:12px;" name="attempt_maximum" class="leadRecycleResult">
			<?php
			for ($x=1;$x<=10;$x++)
			{
				echo "<option>$x</option>";
			}
			?>
			</select></td>
		</tr>
	 </table>
	 </tr>
         <tr>
		<td align="right" colspan="9">	 
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<input type="button" value="Submit" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;" class="buttons" id="leadRecycleSubmit" />
			</div>
                </td>
         </tr>
         </table>

</div>
</div>

<!-- Pause Codes Overlay -->
<div id="overlayPauseCodes" style="display:none;"></div>
<div id="boxPauseCodes">
<a id="closeboxPauseCodes" class="toolTip" title="CLOSE"></a>
<div id="overlayContentPauseCodes">
	<!-- <span style="font-weight:bold;font-size:16px;">ADD NEW STATUS</span> -->
        <div id="small_step_number" style="float:right; margin-top: -5px;">
          <img src="<?=$base?>img/step1-nav-small.png">
        </div>
        <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
          <font color="#333" style="font-size:16px;"><b>Pause Code Wizard » Create New Pause Code</b></font>
        </div>

        <br>
         <table class="tableedit" width="100%">
           <tr>
              <td valign="top" style="width:20%">
                <div id="step_number" style="padding:0px 10px 0px 30px;">
                   <img src="<?=$base?>img/step1-trans.png">
                </div>
              </td>
        <td style="padding-left:50px;" valign="top" colspan="2">

	<table style="width:90%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Campaign:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="pauseCampID" name="pauseCampID" class="pauseCodeResult" onchange="javascript:checkPauseCodes();">
					<option value="">--- SELECT A CAMPAIGN ---</option>
					<?php
					foreach ($campaign['list'] as $camp)
					{
						echo "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Pause Code:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<input type="text" maxlength="6" size="8" style="font-size:12px;" id="pause_code" name="pause_code" class="pauseCodeResult" /> 
				<span id="ploading"></span>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Pause Code Name:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;"><input type="text" maxlength="30" size="30" style="font-size:12px;" id="pause_code_name" name="pause_code_name" class="pauseCodeResult" /></td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Billable:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="font-size:12px;" id="billable" name="billable" class="pauseCodeResult">
					<option value="YES">YES</option>
					<option value="NO">NO</option>
					<option value="HALF">HALF</option>
				</select>
			</td>
		</tr>
	 </table>
	 </tr>
         <tr>
		<td align="right" colspan="9">	 
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<input type="button" value="Submit" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;" class="buttons" id="pauseCodeSubmit" />
			</div>
                </td>
         </tr>
         </table>

</div>
</div>

<!-- HotKeys Overlay -->
<div id="overlayHotKeys" style="display:none;"></div>
<div id="boxHotKeys">
<a id="closeboxHotKeys" class="toolTip" title="CLOSE"></a>
<div id="overlayContentHotKeys">
	<!-- <span style="font-weight:bold;font-size:16px;">ADD NEW STATUS</span> -->
        <div id="small_step_number" style="float:right; margin-top: -5px;">
          <img src="<?=$base?>img/step1-nav-small.png">
        </div>
        <div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
          <font color="#333" style="font-size:16px;"><b>HotKeys Wizard » Create New HotKey</b></font>
        </div>

        <br>
         <table class="tableedit" width="100%">
           <tr>
              <td valign="top" style="width:20%">
                <div id="step_number" style="padding:0px 10px 0px 30px;">
                   <img src="<?=$base?>img/step1-trans.png">
                </div>
              </td>
        <td style="padding-left:50px;" valign="top" colspan="2">

	<table style="width:90%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right"><label class="modify-value">Campaign:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="hotKeysCampID" name="hotKeysCampID" class="hotKeysResult" onchange="javascript:checkHotKeys();">
					<option value="">--- SELECT A CAMPAIGN ---</option>
					<?php
					foreach ($campaign['list'] as $camp)
					{
						echo "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">HotKey:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="font-size:12px;" id="hotKeys" name="hotKeys" class="hotKeysResult" onchange="javascript:checkHotKeys();">
					<?php
					for ($i=1;$i<10;$i++)
					{
						echo "<option value=\"$i\">$i</option>";
					}
					?>
				</select> &nbsp; <span id="kloading"></span>
			</td>
		</tr>
		<tr>
			<td align="right"><label class="modify-value">Status:&nbsp;&nbsp;&nbsp;</label></td>
			<td style="white-space:nowrap;">
				<select style="width:250px;font-size:12px;" id="statusHotKeys" name="statusHotKeys" class="hotKeysResult" onchange="javascript:checkHotKeys();">
					<?php
					ksort($selectable_statuses['list']);
					foreach ($selectable_statuses['list'] as $key => $value)
					{
						ksort($value);
						echo "<optgroup label=\"".str_replace("_"," ",$key)."\">";
						foreach ($value as $xstatus => $istatus)
						{
							echo "<option value=\"{$xstatus}\">{$xstatus} - {$istatus}</option>";
						}
						echo "</optgroup>";
					}
					?>
				</select>
			</td>
		</tr>
	 </table>
	 </tr>
         <tr>
		<td align="right" colspan="9">	 
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<input type="button" value="Submit" style="font-size:12px;border:0px;color:#7A9E22;padding-top:-20px;" class="buttons" id="hotKeysSubmit" />
			</div>
                </td>
         </tr>
         </table>

</div>
</div>

<div id="showRealtime_div" class="hideSpan" align="center">
<img src="<?php echo $base; ?>img/goloading.gif" />
</div>
