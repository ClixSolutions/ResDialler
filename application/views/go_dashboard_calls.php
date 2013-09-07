<?php
########################################################################################################
####  Name:             	go_dashboard_calls.php                     	    		    ####
####  Type:             	ci views - administrator					    ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();

if ($calls_outbound_queue_today > 0) {
	$setOColor = "color:#F00;";
} else {
	$setOColor = "";
}

if ($calls_inbound_queue_today > 0) {
	$setIColor = "color:#F00;";
} else {
	$setIColor = "";
}
?>

<p class="sub">Calls</p>
<table id="callsTable">
	<tbody>
	<tr  class="first">

			<td class="o"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed"><? echo $calls_ringing_today; ?></a></td>
			<td class="t bold"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed">Call(s) Ringing</a></td>


	</tr>
	<tr style="display:none;">


			<td class="c"><a class="toolTip" style="cursor:pointer;<?=$setOColor?>" onclick="callMonitoring()" title="Click to see calls being placed"><? echo $calls_outbound_queue_today; ?></a></td>
			<td class="t "><a class="toolTip" style="cursor:pointer;<?=$setOColor?>" onclick="callMonitoring()" title="Click to see calls being placed">Call(s) in Outgoing Queue</a></td>


	</tr>
	<tr>


			<td class="c"><a class="toolTip" style="cursor:pointer;<?=$setIColor?>" onclick="callMonitoring()" title="Click to see calls being placed"><? echo $calls_inbound_queue_today; ?></a></td>
			<td class="t "><a class="toolTip" style="cursor:pointer;<?=$setIColor?>" onclick="callMonitoring()" title="Click to see calls being placed">Call(s) in Incoming Queue</a></td>


	</tr>
	<tr>


			<td class="c"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed"><? echo $live_inbound_today; ?></a></td>
			<td class="t"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed">Live Inbound</a></td>



	</tr>
	<tr>
			<td class="c"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed"><? echo $live_outbound_today; ?></a></td>
			<td class="r"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed">Live Outbound</a></td>
	</tr>
	<tr style="display:none">
			<td class="c"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed"><? echo $ibcalls_morethan_minute; ?></a></td>
			<td class="r"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed">IN Call(s) > a Minute</a></td>
	</tr>
	<tr style="display:none">
			<td class="c"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed"><? echo $obcalls_morethan_minute; ?></a></td>
			<td class="r"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed">OUT Call(s) > a Minute</a></td>
	</tr>
	<tr>
			<td class="c"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed"><? echo $total_calls; ?></a></td>
			<td class="r"><a class="toolTip" style="cursor:pointer" onclick="callMonitoring()" title="Click to see calls being placed">Total Calls</a></td>
	</tr>
	</tbody>
</table>

<!--<table>
	<tbody>
		<br>
		<br class="clear">
		<br class="clear">

		<tr>

		 <td class='dp'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td>
		 <td class='today_showmore'>
		 
		 <a id='today_showmore' class='today_showmore'>&nbsp;&raquo; Click here to show more... </a>
		 <a style='display: none' id='today_showmore' class='today_showmore'>&nbsp;&raquo; Click here to hide... </a>
		
		 </td>
		</tr>
	</tbody>
</table>-->