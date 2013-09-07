<?php
####################################################################################################
####  Name:             	go_logs_list.php                                                    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1375243200                                                          ####
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
	// Pagination
	$('#logsTable').tablePagination();

	// Table Sorter
	$("#logsTable").tablesorter({headers: {1: {sorter:"ipAddress"}, 2: {sorter:"text"}}, widgets: ['zebra']});
	
	// Tooltip
	$(".toolTip").tipTip();
});
</script>
<table id="logsTable" class="tablesorter" style="width:100%;" cellpadding=0 cellspacing=0>
	<thead>
		<tr style="font-weight:bold;">
			<!--<th style="text-align: left;">&nbsp;ID</th>-->
			<th style="text-align: left;">&nbsp;USER</th>
			<th style="text-align: left;">&nbsp;IP ADDRESS</th>
			<th style="text-align: left;">&nbsp;DATE</th>
			<th style="text-align: left;">&nbsp;ACTION</th>
			<th style="text-align: left;">&nbsp;DETAILS</th>
			<th style="text-align: left;">&nbsp;DB QUERY</th>
		</tr>
	</thead>
	<tbody>
	<?php
	echo $admin_logs;
	if (count($admin_logs) > 0) {
		$x = 0;
		foreach ($admin_logs->result() as $idx => $logs)
		{
			$details = (strlen($logs->details) > 30) ? substr($logs->details,0,30) . "... &nbsp;<span id=\"descTruncate\" style=\"cursor:pointer;color:blue;font-size:10px;line-height:10px;\" class=\"toolTip\" title=\"".$logs->details."\">more</span>" : $logs->details;
			$db_query = (strlen($logs->db_query) > 30) ? substr($logs->db_query,0,30) . "... &nbsp;<span id=\"descTruncate\" style=\"cursor:pointer;color:blue;font-size:10px;line-height:10px;\" class=\"toolTip\" title=\"".str_replace('\\','',str_replace('\r','',str_replace('\n',' ',$logs->db_query)))."\">more</span>" : $logs->db_query;
			$class = ($x&1) ? "odd" : "even";
			echo "<tr class='$class'>";
			//echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;".($idx+1)."</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$logs->user}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$logs->ip_address}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$logs->event_date}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$logs->action}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$details}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$db_query}</td>";
			echo "</tr>";
			$x++;
		}
	} else {
			echo "<tr class='odd'>";
			echo "<td colspan='6' style='border-top:#D0D0D0 dashed 1px;color:red;text-align:center;font-weight:bold;'>&nbsp;No log(s) found.</td>";
			echo "</tr>";
	}
	?>
	</tbody>
</table>
