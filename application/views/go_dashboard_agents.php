<?php
########################################################################################################
####  Name:             	go_dashboard_agents.php                                             ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:      	Franco E. Hora                                                      ####
####                    	Jericho James Milo                                                  ####
####                            Chris Lomuntad                                                      ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

?>

<p class="sub">Agents Resources</p>
<table>
	<tbody>
	<tr class="first">
			<td class="b"><a class="cur_hand toolTip" style="cursor:pointer" onclick="agentMonitoring()" title="Click to monitor agents"><? echo $total_agents_call; ?></a></td>
			<td class="t bold"><a class="cur_hand toolTip" style="cursor:pointer" onclick="agentMonitoring()" title="Click to monitor agents">Agent(s) on Call</a></td>
	</tr>
	<tr>
			<td class="c"><a class="cur_hand toolTip" style="cursor:pointer" onclick="agentMonitoring()" title="Click to monitor agents"><? echo $total_agents_paused; ?></a></td>
			<td class="r"><a class="cur_hand toolTip" style="cursor:pointer" onclick="agentMonitoring()" title="Click to monitor agents">Agent(s) on Paused</a></td>
	</tr>
	<tr>
			<td class="c"><a class="cur_hand toolTip" style="cursor:pointer" onclick="agentMonitoring()" title="Click to monitor agents"><? echo $total_agents_wait_calls; ?></a></td>
			<td class="r "><a class="cur_hand toolTip" style="cursor:pointer" onclick="agentMonitoring()" title="Click to monitor agents">Agent(s) Waiting</a></td>
	</tr>
	<tr>
			<td class="b"><a class="cur_hand toolTip" style="cursor:pointer" onclick="agentMonitoring()" title="Click to monitor agents"><? echo $total_agents_online; ?></a></td>
			<td class="t bold"><a class="cur_hand toolTip" style="cursor:pointer" onclick="agentMonitoring()" title="Click to monitor agents">Total Agents Online</a></td>
	</tr>
	</tbody>
</table>