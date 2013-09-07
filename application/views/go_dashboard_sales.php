<?php
########################################################################################################
####  Name:             	go_dashboard_sales.php                     	    		    ####
####  Type:             	ci views - administrator					    ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
?>

<p class="sub">Sales</p>
<table>
	<tbody>
	<tr class="first">
			<td class="b"><a class="cur_hand"><? echo $inbound_today + $outbound_today; ?></a></td>
			<td class="t bold"><a class="cur_hand">Total Sales</a></td>
	</tr>
	<tr>
			<td class="c"><a class="cur_hand"><? echo $inbound_today; ?></a></td>
			<td class="t"><a class="cur_hand">Inbound Sales</a></td>
	</tr>
	<tr>
			<td class="c"><a class="cur_hand"><? echo $outbound_today; ?></a></td>
			<td class="r"><a class="cur_hand">Outbound Sales</a></td></tr>
	<tr>
			<td class="c"><a class="cur_hand"><? echo $inbound_sph; ?></a></td>
			<td class="r"><a class="cur_hand">IN Sales / Hour</a></td>
	</tr>
	<tr>
			<td class="c"><a class="cur_hand"><? echo $outbound_sph; ?></a></td>
			<td class="r"><a class="cur_hand">OUT Sales / Hour</a></td>
	</tr>
	</tbody>
</table>