<?php
####################################################################################################
####  Name:             	go_voicemail_list.php                                               ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
?>
<script>
$(function()
{
	$('#selectAll').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delVoicemail[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delVoicemail[]"]').each(function()
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
			$('#go_action_menu').css('left',position.left-70);
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

	$('li.go_action_submenu,li.go_status_submenu,li.go_camp_status_submenu').hover(function()
	{
		$(this).css('background-color','#ccc');
	},function()
	{
		$(this).css('background-color','#fff');
	});

	$('li.go_action_submenu').click(function () {
		var selectedVoicemails = [];
		$('input:checkbox[id="delVoicemail[]"]:checked').each(function()
		{
			selectedVoicemails.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedVoicemails.length<1)
		{
			alert('Please select a Voicemail.');
		}
		else
		{
			var s = '';
			if (selectedVoicemails.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm('Are you sure you want to delete the selected Voicemail'+s+'?');
				if (what)
				{
					$('#table_container').load('<? echo $base; ?>index.php/go_voicemail_ce/go_update_voicemail_list/'+action+'/'+selectedVoicemails+'/');
				}
			}
			else
			{
				$('#table_container').load('<? echo $base; ?>index.php/go_voicemail_ce/go_update_voicemail_list/'+action+'/'+selectedVoicemails+'/');
			}
		}
	});

	if (<?php echo count($voicemails); ?> > 0)
	{
		// Pagination
		$('#mainTable').tablePagination();

		// Table Sorter
		$("#mainTable").tablesorter({sortList:[[0,0]], headers: { 6: { sorter: false}, 7: {sorter: false} }});
	}
	else
	{
		addNewVoicemails();
	}
	
	// Tool Tip
	$(".toolTip").tipTip();
});
</script>
<table id="mainTable" class="tablesorter" style="width:100%;" cellpadding=0 cellspacing=0>
	<thead>
		<tr style="font-weight:bold;">
			<th style="white-space:nowrap;width:12%;">&nbsp;VOICEMAIL ID</th>
			<th style="white-space:nowrap">&nbsp;NAME</th>
			<th style="white-space:nowrap;width:20%;">&nbsp;STATUS</th>
			<th style="white-space:nowrap;width:10%;">&nbsp;NEW MESSAGES</th>
			<th style="white-space:nowrap;width:10%;">&nbsp;OLD MESSAGES</th>
			<th style="white-space:nowrap;width:10%;">&nbsp;DELETE</th>
			<th colspan="3" style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
		</tr>
	</thead>
	<tbody>
<?php
$x = 0;
foreach ($voicemails as $list)
{		
	if ($x==0) {
		$bgcolor = "#E0F8E0";
		$x=1;
	} else {
		$bgcolor = "#EFFBEF";
		$x=0;
	}
	
	switch($list->active)
	{
		case "Y":
			$active = "<span style='color:green'>ACTIVE</span>";
			break;
		case "N":
			$active = "<span style='color:red'>INACTIVE</span>";
			break;
	}
	
	switch($list->delete_vm_after_email)
	{
		case "Y":
			$delete = "YES";
			break;
		case "N":
			$delete = "NO";
			break;
	}
	
	$acolor = ($status=="ACTIVE") ? "green" : "#F00";
	
	echo "<tr style='background-color:$bgcolor;'>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->voicemail_id}')\">{$list->voicemail_id}</a></td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->voicemail_id}')\">{$list->fullname}</a></td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;font-weight:bold;'>&nbsp;$active</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;font-weight:bold;'>&nbsp;{$list->messages}</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;font-weight:bold;'>&nbsp;{$list->old_messages}</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;$delete</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('{$list->voicemail_id}')\" style='cursor:pointer;' class='toolTip' title='MODIFY VOICEMAIL<br />{$list->voicemail_id}'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span onclick=\"delVoicemail('{$list->voicemail_id}')\" style='cursor:pointer;' class='toolTip' title='DELETE VOICEMAIL<br />{$list->voicemail_id}'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span><img src='{$base}img/status_display_i_grayed.png' style='width:12px;' /></span></td>\n";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delVoicemail[]' value='{$list->voicemail_id}' /></td>\n";
	echo "</tr>";
}
?>
	</tbody>
</table>