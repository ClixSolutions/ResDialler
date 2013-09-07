<?php
####################################################################################################
####  Name:             	go_usergroup_list.php                                               ####
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
			$('input:checkbox[id="delUserGroup[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delUserGroup[]"]').each(function()
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
			$('#go_action_menu').css('left',position.left-40);
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
		var selectedUserGroups = [];
		$('input:checkbox[id="delUserGroup[]"]:checked').each(function()
		{
			selectedUserGroups.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedUserGroups.length<1)
		{
			alert('Please select a User Group.');
		}
		else
		{
			var s = '';
			if (selectedUserGroups.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm('Are you sure you want to delete the selected User Group'+s+'?');
				if (what)
				{
					$('#table_container').load('<? echo $base; ?>index.php/go_usergroup_ce/go_update_usergroup_list/'+action+'/'+selectedUserGroups+'/');
				}
			}
			else
			{
				$('#table_container').load('<? echo $base; ?>index.php/go_usergroup_ce/go_update_usergroup_list/'+action+'/'+selectedUserGroups+'/');
			}
		}
	});

	if (<?php echo count($usergroups); ?> > 0)
	{
		// Pagination
		$('#mainTable').tablePagination();

		// Table Sorter
		$("#mainTable").tablesorter({sortList:[[0,0]], headers: { 3: { sorter: false}, 4: {sorter: false} }});
	}
	else
	{
		addNewUserGroups();
	}
	
	// Tool Tip
	$(".toolTip").tipTip();
});
</script>
<table id="mainTable" class="tablesorter" style="width:100%;" cellpadding=0 cellspacing=0>
	<thead>
		<tr style="font-weight:bold;">
			<th style="white-space:nowrap">&nbsp;USER GROUP</th>
			<th style="white-space:nowrap">&nbsp;GROUP NAME</th>
			<th style="white-space:nowrap">&nbsp;FORCED TIMECLOCK</th>
			<th colspan="3" style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
		</tr>
	</thead>
	<tbody>
<?php
$x = 0;
foreach ($usergroups as $list)
{		
	if ($x==0) {
		$bgcolor = "#E0F8E0";
		$x=1;
	} else {
		$bgcolor = "#EFFBEF";
		$x=0;
	}
	
	switch($list->forced_timeclock_login)
	{
		case "Y":
			$forced_timeclock = "YES";
			break;
		case "N":
			$forced_timeclock = "NO";
			break;
		default:
			$forced_timeclock = "ADMIN EXEMPT";
	}
	
	$acolor = ($status=="ACTIVE") ? "green" : "#F00";
	
	echo "<tr style='background-color:$bgcolor;'>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->user_group}')\">{$list->user_group}</a></td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->user_group}')\">{$list->group_name}</a></td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;$forced_timeclock</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('{$list->user_group}')\" style='cursor:pointer;' class='toolTip' title='MODIFY USER GROUP<br />{$list->user_group}'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span onclick=\"delUserGroup('{$list->user_group}')\" style='cursor:pointer;' class='toolTip' title='DELETE USER GROUP<br />{$list->user_group}'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span><img src='{$base}img/status_display_i_grayed.png' style='width:12px;' /></span></td>\n";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delUserGroup[]' value='{$list->user_group}' /></td>\n";
	echo "</tr>";
}
?>
	</tbody>
</table>